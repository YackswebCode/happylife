<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GenealogyController extends Controller
{
    /**
     * Display the genealogy tree (OrgChart only, 3 generations).
     */
    public function index(Request $request)
    {
        $memberId = $request->get('member', Auth::id());
        $root = User::with(['package', 'rank', 'leftChildren', 'rightChildren'])
                    ->findOrFail($memberId);

        // Build flat node array for OrgChart (depth = 0,1,2)
        $nodes = $this->buildOrgChartNodes($root, null, 0, 2);

        return view('member.genealogy.index', [
            'root'      => $root,
            'nodesJson' => json_encode($nodes) // 👈 THIS WAS MISSING
        ]);
    }

    /**
     * Recursively build OrgChart nodes up to maxDepth.
     */
    private function buildOrgChartNodes($user, $parentId = null, $depth = 0, $maxDepth = 2)
    {
        if ($depth > $maxDepth || !$user) {
            return [];
        }

        $nodes = [];

        // ---- Current user node ----
        $position = null;
        if ($parentId && isset($user->parent_position)) {
            $position = $user->parent_position; // 'left' or 'right'
        }

        $nodes[] = [
            'id'          => $user->id,
            'pid'         => $parentId,
            'name'        => $user->name,
            'username'    => $user->username,
            'pv'          => ($user->left_pv ?? 0) + ($user->right_pv ?? 0),
            'rank'        => $user->rank->name ?? 'Member',
            'package'     => $user->package->name ?? null,
            'left_count'  => $user->leftChildren->count(),
            'right_count' => $user->rightChildren->count(),
            'position'    => $position,
            'is_me'       => ($user->id == Auth::id()),
            'initial'     => strtoupper(substr($user->name, 0, 1)),
            'isEmpty'     => false,
        ];

        // ---- Left child ----
        $leftChild = $user->leftChildren->first(); // only one left child in binary
        if ($leftChild) {
            $leftChild->parent_position = 'left'; // mark for styling
            $nodes = array_merge($nodes, $this->buildOrgChartNodes($leftChild, $user->id, $depth + 1, $maxDepth));
        } else {
            // Empty left slot – create a placeholder node
            $nodes[] = [
                'id'          => 'empty-left-' . $user->id,
                'pid'         => $user->id,
                'name'        => 'Vacant',
                'username'    => '—',
                'pv'          => '0.0',
                'rank'        => '—',
                'package'     => null,
                'left_count'  => 0,
                'right_count' => 0,
                'position'    => 'left',
                'is_me'       => false,
                'initial'     => '<i class="bi bi-plus-lg"></i>',
                'isEmpty'     => true,
            ];
        }

        // ---- Right child ----
        $rightChild = $user->rightChildren->first();
        if ($rightChild) {
            $rightChild->parent_position = 'right';
            $nodes = array_merge($nodes, $this->buildOrgChartNodes($rightChild, $user->id, $depth + 1, $maxDepth));
        } else {
            $nodes[] = [
                'id'          => 'empty-right-' . $user->id,
                'pid'         => $user->id,
                'name'        => 'Vacant',
                'username'    => '—',
                'pv'          => '0.0',
                'rank'        => '—',
                'package'     => null,
                'left_count'  => 0,
                'right_count' => 0,
                'position'    => 'right',
                'is_me'       => false,
                'initial'     => '<i class="bi bi-plus-lg"></i>',
                'isEmpty'     => true,
            ];
        }

        return $nodes;
    }

    /**
     * AJAX endpoint: load children of a specific member and return HTML.
     * (Optional – keep if you still use it elsewhere)
     */
    public function load(Request $request)
    {
        $memberId = $request->get('member');
        $user = User::with(['leftChildren', 'rightChildren', 'package', 'rank'])
                    ->findOrFail($memberId);

        $leftHtml = null;
        $rightHtml = null;

        if ($user->leftChildren->isNotEmpty()) {
            $leftHtml = view('member.genealogy.partials.node', [
                'node' => $user->leftChildren->first(),
                'position' => 'left',
                'isRoot' => false
            ])->render();
        }

        if ($user->rightChildren->isNotEmpty()) {
            $rightHtml = view('member.genealogy.partials.node', [
                'node' => $user->rightChildren->first(),
                'position' => 'right',
                'isRoot' => false
            ])->render();
        }

        return response()->json([
            'left'   => $leftHtml,
            'right'  => $rightHtml,
            'has_left'  => $user->leftChildren->count() > 0,
            'has_right' => $user->rightChildren->count() > 0,
        ]);
    }
}