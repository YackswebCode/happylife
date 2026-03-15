<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GenealogyController extends Controller
{
    /**
     * Display the genealogy tree (root only, dynamic loading).
     */
    public function index(Request $request)
    {
        $memberId = $request->get('member', Auth::id());
        $root = User::with(['package', 'rank'])->findOrFail($memberId);

        // Build only the root node (no children yet)
        $nodes = [$this->nodeToArray($root, null)];

        return view('member.genealogy.index', [
            'root'      => $root,
            'nodesJson' => json_encode($nodes)
        ]);
    }

    /**
     * AJAX endpoint: return children of a given node (left and right).
     */
    public function getChildren(Request $request)
    {
        $nodeId = $request->get('id');
        $user = User::with(['leftChildren', 'rightChildren', 'package', 'rank'])->find($nodeId);

        if (!$user) {
            return response()->json([]);
        }

        $children = [];

        // Left child (real or placeholder)
        $leftChild = $user->leftChildren->first();
        if ($leftChild) {
            $children[] = $this->nodeToArray($leftChild, $nodeId, 'left');
        } else {
            $children[] = $this->emptyNode($nodeId, 'left');
        }

        // Right child (real or placeholder)
        $rightChild = $user->rightChildren->first();
        if ($rightChild) {
            $children[] = $this->nodeToArray($rightChild, $nodeId, 'right');
        } else {
            $children[] = $this->emptyNode($nodeId, 'right');
        }

        return response()->json($children);
    }

    /**
     * Convert a User model to the array format expected by OrgChart.
     * Uses stored left_count / right_count for total leg members.
     */
    private function nodeToArray($user, $parentId = null, $position = null)
    {
        return [
            'id'          => $user->id,
            'pid'         => $parentId,
            'name'        => $user->name,
            'username'    => $user->username,
            'pv'          => ($user->left_pv ?? 0) + ($user->right_pv ?? 0),
            'rank'        => $user->rank->name ?? 'Member',
            'package'     => $user->package->name ?? null,
            'left_count'  => $user->left_count,   // total in left leg
            'right_count' => $user->right_count,  // total in right leg
            'position'    => $position,
            'is_me'       => ($user->id == Auth::id()),
            'initial'     => strtoupper(substr($user->name, 0, 1)),
            'isEmpty'     => false,
        ];
    }

    /**
     * Create a placeholder node for an empty slot.
     */
    private function emptyNode($parentId, $position)
    {
        return [
            'id'          => 'empty-' . $parentId . '-' . $position,
            'pid'         => $parentId,
            'name'        => 'Vacant',
            'username'    => '—',
            'pv'          => 0,
            'rank'        => '—',
            'package'     => null,
            'left_count'  => 0,
            'right_count' => 0,
            'position'    => $position,
            'is_me'       => false,
            'initial'     => '<i class="bi bi-plus-lg"></i>',
            'isEmpty'     => true,
        ];
    }

    /**
     * Legacy AJAX endpoint: returns HTML for children (if still used elsewhere).
     * (Optional – keep if needed)
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