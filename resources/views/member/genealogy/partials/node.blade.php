@props(['node', 'position' => 'root', 'isRoot' => false])

<div class="tree-node {{ $position }} @if($isRoot) border-2 border-red @endif" 
     data-id="{{ $node->id }}" 
     data-position="{{ $position }}">
    @if($node->id === auth()->id())
        <div class="current-user-badge" title="You">
            <i class="bi bi-person-fill"></i>
        </div>
    @endif
    <div class="node-header">
        <div class="node-avatar">
            {{ strtoupper(substr($node->name, 0, 1)) }}
        </div>
        <div class="node-info">
            <div class="node-name">{{ \Illuminate\Support\Str::limit($node->name, 18) }}</div>
            <div class="node-username">{{ $node->username }}</div>
        </div>
    </div>
    <div class="node-details">
        <span class="node-pv">
            <i class="bi bi-lightning-charge-fill"></i> {{ number_format($node->left_pv + $node->right_pv, 1) }} PV
        </span>
        @if($node->rank)
            <span class="node-rank">
                <i class="bi bi-award-fill"></i> {{ $node->rank->name }}
            </span>
        @endif
    </div>
    @if($node->package)
        <div class="node-package">
            <i class="bi bi-box-seam"></i> {{ $node->package->name }}
        </div>
    @endif
    @if(!$isRoot)
        <div class="mt-2 text-end">
            <a href="{{ route('member.genealogy', ['member' => $node->id]) }}" 
               class="small text-red text-decoration-none">
                <i class="bi bi-eye"></i> View Tree
            </a>
        </div>
    @endif
</div>