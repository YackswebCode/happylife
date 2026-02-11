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

        // Validate
        $request->validate([
            'document_type'   => 'required|in:national_id,passport,driver_license,voter_id,utility_bill',
            'id_number'       => 'nullable|string|max:100',
            'issue_date'      => 'nullable|date',
            'expiry_date'     => 'nullable|date|after:issue_date',
            'place_of_issue'  => 'nullable|string|max:100',
            'front_image'     => 'required|image|mimes:jpeg,png,jpg,pdf|max:5120', // 5MB
            'back_image'      => 'nullable|image|mimes:jpeg,png,jpg,pdf|max:5120',
            'selfie_image'    => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        // Delete any previous pending KYC? Or allow only one submission?
        // Here we deactivate previous pending/rejected by setting new one as current.
        // Option: soft delete or archive; simple approach: user can have multiple records,
        // we just create a new one and keep history.
        
        // Upload files
        $frontPath = $request->file('front_image')->store('kyc/' . $user->id, 'public');
        $backPath  = $request->hasFile('back_image') 
            ? $request->file('back_image')->store('kyc/' . $user->id, 'public') 
            : null;
        $selfiePath = $request->hasFile('selfie_image') 
            ? $request->file('selfie_image')->store('kyc/' . $user->id, 'public') 
            : null;

        // Create KYC record
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
     * Update existing KYC (if rejected, user can resubmit)
     */
    public function update(Request $request, Kyc $kyc)
    {
        // Ensure the KYC belongs to the authenticated user and is in reject status
        $this->authorize('update', $kyc); // optional policy

        if ($kyc->user_id !== Auth::id() || $kyc->status !== 'rejected') {
            abort(403);
        }

        // Validate similar to store, but allow existing files to be kept if not re-uploaded
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

        // Update fields
        $kyc->document_type  = $request->document_type;
        $kyc->id_number      = $request->id_number;
        $kyc->issue_date     = $request->issue_date;
        $kyc->expiry_date    = $request->expiry_date;
        $kyc->place_of_issue = $request->place_of_issue;

        // Handle file updates
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

        $kyc->status       = 'pending';
        $kyc->admin_comment = null;
        $kyc->submitted_at = now();
        $kyc->verified_at  = null;
        $kyc->verified_by  = null;
        $kyc->save();

        return redirect()->route('member.kyc.index')
            ->with('success', 'Your KYC has been resubmitted for verification.');
    }

    /**
     * View uploaded document (optional)
     */
    public function viewDocument($filename)
    {
        // Secure: only allow user to view their own documents, or admin
        $path = 'kyc/' . Auth::id() . '/' . $filename;
        if (!Storage::disk('public')->exists($path)) {
            abort(404);
        }
        return response()->file(Storage::disk('public')->path($path));
    }
}