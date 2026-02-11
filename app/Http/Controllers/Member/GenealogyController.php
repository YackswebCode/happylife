<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GenealogyController extends Controller
{
    /**
     * Display the genealogy tree.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get the root member to view (default: current user)
        $rootId = $request->get('member', $user->id);
        $root = User::with(['package', 'rank'])
                    ->where('id', $rootId)
                    ->firstOrFail();
        
        // Security: ensure user can view this member (only descendants or self)
        if (!$this->canViewGenealogy($user, $root)) {
            abort(403, 'You do not have permission to view this member’s tree.');
        }

        // Load immediate children (left and right) with minimal data
        $root->load(['leftChild', 'rightChild']);

        // Load deeper levels via a recursive function (or AJAX lazy load)
        // For initial page load we load first 3 levels
        $tree = $this->buildTree($root, 3);

        return view('member.genealogy.index', compact('user', 'root', 'tree'));
    }

    /**
     * AJAX endpoint to load more nodes when expanding.
     */
    public function loadChildren(Request $request)
    {
        $memberId = $request->get('member');
        $member = User::with(['leftChild', 'rightChild'])->findOrFail($memberId);

        // Security check: can current user view this member?
        if (!$this->canViewGenealogy(Auth::user(), $member)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $html = '';
        if ($member->leftChild) {
            $html .= view('member.genealogy.partials.node', ['node' => $member->leftChild, 'position' => 'left'])->render();
        }
        if ($member->rightChild) {
            $html .= view('member.genealogy.partials.node', ['node' => $member->rightChild, 'position' => 'right'])->render();
        }

        return response()->json([
            'left'  => $member->leftChild ? view('member.genealogy.partials.node', ['node' => $member->leftChild, 'position' => 'left'])->render() : null,
            'right' => $member->rightChild ? view('member.genealogy.partials.node', ['node' => $member->rightChild, 'position' => 'right'])->render() : null,
        ]);
    }

    /**
     * Check if the authenticated user can view the genealogy of a given member.
     * Rules: admin can view all, member can view own tree and downlines.
     */
    private function canViewGenealogy(User $authUser, User $targetUser): bool
    {
        if ($authUser->role === 'admin') {
            return true;
        }

        // User can view their own tree
        if ($authUser->id === $targetUser->id) {
            return true;
        }

        // Check if target is a downline of auth user (descendant)
        // We can either use a recursive query or a materialized path.
        // For simplicity, we'll assume the tree is binary and we have placement_id chain.
        // Here we check if auth user is an ancestor of target user.
        return $this->isDescendantOf($authUser, $targetUser);
    }

    /**
     * Check if $descendant is a descendant of $ancestor.
     */
    private function isDescendantOf(User $ancestor, User $descendant): bool
    {
        $maxDepth = 50; // safety
        $current = $descendant;
        while ($current->placement_id && $maxDepth-- > 0) {
            if ($current->placement_id == $ancestor->id) {
                return true;
            }
            $current = User::find($current->placement_id);
            if (!$current) break;
        }
        return false;
    }

    /**
     * Recursively build the tree up to a certain depth.
     */
    private function buildTree(User $node, int $depth = 3): array
    {
        if ($depth <= 0) {
            return [
                'id'        => $node->id,
                'name'      => $node->name,
                'username'  => $node->username,
                'has_more'  => ($node->left_count + $node->right_count) > 0,
                'left_pv'   => $node->left_pv,
                'right_pv'  => $node->right_pv,
                'left'      => null,
                'right'     => null,
            ];
        }

        $leftChild  = $node->leftChild;
        $rightChild = $node->rightChild;

        return [
            'id'        => $node->id,
            'name'      => $node->name,
            'username'  => $node->username,
            'package'   => $node->package?->name,
            'rank'      => $node->rank?->name,
            'has_more'  => ($node->left_count + $node->right_count) > 0,
            'left_pv'   => $node->left_pv,
            'right_pv'  => $node->right_pv,
            'left'      => $leftChild ? $this->buildTree($leftChild, $depth - 1) : null,
            'right'     => $rightChild ? $this->buildTree($rightChild, $depth - 1) : null,
        ];
    }
}