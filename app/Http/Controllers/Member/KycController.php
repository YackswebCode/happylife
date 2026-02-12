<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Kyc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KycController extends Controller
{
    /**
     * Display KYC status and form
     */
    public function index()
    {
        $user = Auth::user();
        $kyc = $user->kyc; // assumes hasOne relation in User model

        return view('member.profile.kyc', compact('user', 'kyc'));
    }

    /**
     * Store new KYC submission
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Prevent multiple pending submissions
        if ($user->kyc && $user->kyc->status === 'pending') {
            return redirect()->route('member.kyc.index')
                ->with('error', 'You already have a pending KYC submission. Please wait for verification.');
        }

        $request->validate([
            'document_type'   => 'required|in:national_id,passport,driver_license,voter_id,utility_bill',
            'id_number'       => 'nullable|string|max:100',
            'issue_date'      => 'nullable|date',
            'expiry_date'     => 'nullable|date|after:issue_date',
            'place_of_issue'  => 'nullable|string|max:100',
            'front_image'     => 'required|image|mimes:jpeg,png,jpg,pdf|max:5120',
            'back_image'      => 'nullable|image|mimes:jpeg,png,jpg,pdf|max:5120',
            'selfie_image'    => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        // Upload files – store in storage/app/public/kyc/{user_id}/
        $frontPath = $request->file('front_image')->store('kyc/' . $user->id, 'public');
        $backPath  = $request->hasFile('back_image') 
            ? $request->file('back_image')->store('kyc/' . $user->id, 'public') 
            : null;
        $selfiePath = $request->hasFile('selfie_image') 
            ? $request->file('selfie_image')->store('kyc/' . $user->id, 'public') 
            : null;

        $kyc = new Kyc();
        $kyc->user_id        = $user->id;
        $kyc->document_type  = $request->document_type;
        $kyc->id_number      = $request->id_number;
        $kyc->issue_date     = $request->issue_date;
        $kyc->expiry_date    = $request->expiry_date;
        $kyc->place_of_issue = $request->place_of_issue;
        $kyc->front_image    = $frontPath;
        $kyc->back_image     = $backPath;
        $kyc->selfie_image   = $selfiePath;
        $kyc->status         = 'pending';
        $kyc->submitted_at   = now();
        $kyc->save();

        return redirect()->route('member.kyc.index')
            ->with('success', 'KYC documents submitted successfully. We will verify your information shortly.');
    }

    /**
     * Update existing KYC (only when rejected)
     */
    public function update(Request $request, Kyc $kyc)
    {
        // Ensure the KYC belongs to the authenticated user and is rejected
        if ($kyc->user_id !== Auth::id() || $kyc->status !== 'rejected') {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'document_type'   => 'required|in:national_id,passport,driver_license,voter_id,utility_bill',
            'id_number'       => 'nullable|string|max:100',
            'issue_date'      => 'nullable|date',
            'expiry_date'     => 'nullable|date|after:issue_date',
            'place_of_issue'  => 'nullable|string|max:100',
            'front_image'     => 'nullable|image|mimes:jpeg,png,jpg,pdf|max:5120',
            'back_image'      => 'nullable|image|mimes:jpeg,png,jpg,pdf|max:5120',
            'selfie_image'    => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $kyc->document_type  = $request->document_type;
        $kyc->id_number      = $request->id_number;
        $kyc->issue_date     = $request->issue_date;
        $kyc->expiry_date    = $request->expiry_date;
        $kyc->place_of_issue = $request->place_of_issue;

        // Handle file updates – delete old files and store new ones
        if ($request->hasFile('front_image')) {
            Storage::disk('public')->delete($kyc->front_image);
            $kyc->front_image = $request->file('front_image')->store('kyc/' . $kyc->user_id, 'public');
        }
        if ($request->hasFile('back_image')) {
            Storage::disk('public')->delete($kyc->back_image);
            $kyc->back_image = $request->file('back_image')->store('kyc/' . $kyc->user_id, 'public');
        }
        if ($request->hasFile('selfie_image')) {
            Storage::disk('public')->delete($kyc->selfie_image);
            $kyc->selfie_image = $request->file('selfie_image')->store('kyc/' . $kyc->user_id, 'public');
        }

        $kyc->status        = 'pending';
        $kyc->admin_comment = null;
        $kyc->submitted_at  = now();
        $kyc->verified_at   = null;
        $kyc->verified_by   = null;
        $kyc->save();

        return redirect()->route('member.kyc.index')
            ->with('success', 'Your KYC has been resubmitted for verification.');
    }

    /**
     * View a specific KYC document securely.
     * Route: /member/kyc/document/{kyc}/{field}
     */
    public function viewDocument(Kyc $kyc, $field)
    {
        // Authorize: only the owner or admin can view
        if ($kyc->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        // Allowed fields that contain file paths
        if (!in_array($field, ['front_image', 'back_image', 'selfie_image'])) {
            abort(404);
        }

        $path = $kyc->$field;
        if (!$path || !Storage::disk('public')->exists($path)) {
            abort(404);
        }

        return response()->file(Storage::disk('public')->path($path));
    }
}