@extends('layouts.member')

@section('title', 'Genealogy Tree - Happylife Multipurpose Int\'l')

@push('styles')
<style>
    .binary-tree {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 20px 0;
    }
    .tree-root {
        margin-bottom: 30px;
    }
    .tree-branch {
        display: flex;
        justify-content: space-around;
        width: 100%;
        position: relative;
    }
    .tree-node-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        padding: 0 15px;
    }
    .tree-node {
        background: white;
        border-radius: 12px;
        padding: 15px;
        min-width: 200px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        border-left: 6px solid var(--color-red);
        transition: all 0.3s;
        position: relative;
        z-index: 2;
    }
    .tree-node:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    .tree-node.left {
        border-left-color: var(--color-teal-blue);
    }
    .tree-node.right {
        border-left-color: var(--color-soft-cyan);
    }
    .node-header {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }
    .node-avatar {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--color-red) 0%, #f05c4e 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        margin-right: 10px;
    }
    .node-info {
        flex: 1;
    }
    .node-name {
        font-weight: 600;
        color: var(--color-dark-gray);
        margin-bottom: 2px;
    }
    .node-username {
        font-size: 0.75rem;
        color: #6c757d;
    }
    .node-details {
        display: flex;
        justify-content: space-between;
        font-size: 0.8rem;
        margin-bottom: 5px;
    }
    .node-pv {
        background: rgba(230, 51, 35, 0.1);
        padding: 2px 8px;
        border-radius: 20px;
        color: var(--color-red);
        font-weight: 600;
    }
    .node-rank {
        background: rgba(255, 193, 7, 0.1);
        padding: 2px 8px;
        border-radius: 20px;
        color: #856404;
    }
    .node-package {
        font-size: 0.75rem;
        color: var(--color-teal-blue);
        font-weight: 600;
    }
    .branch-lines {
        position: absolute;
        top: -20px;
        left: 50%;
        width: 2px;
        height: 20px;
        background: #dee2e6;
    }
    .left-branch::before, .right-branch::before {
        content: '';
        position: absolute;
        top: -20px;
        width: 50%;
        height: 20px;
        border-top: 2px solid #dee2e6;
    }
    .left-branch::before {
        left: 50%;
        border-right: 2px solid #dee2e6;
        border-radius: 0 10px 0 0;
    }
    .right-branch::before {
        right: 50%;
        border-left: 2px solid #dee2e6;
        border-radius: 10px 0 0 0;
    }
    .load-more {
        margin-top: 10px;
        text-align: center;
    }
    .load-more-btn {
        background: none;
        border: 1px dashed var(--color-teal-blue);
        color: var(--color-teal-blue);
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.8rem;
        transition: all 0.3s;
    }
    .load-more-btn:hover {
        background: var(--color-teal-blue);
        color: white;
    }
    .current-user-badge {
        position: absolute;
        top: -8px;
        right: -8px;
        background: var(--color-red);
        color: white;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
    }
    @media (max-width: 768px) {
        .tree-branch {
            flex-direction: column;
            align-items: center;
        }
        .tree-node-wrapper {
            margin-bottom: 20px;
        }
        .branch-lines, .left-branch::before, .right-branch::before {
            display: none;
        }
    }
</style>
@endpush

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-2 text-dark-gray">Genealogy Tree</h1>
                <p class="text-muted mb-0">Visualize your binary network structure.</p>
            </div>
            <div class="d-flex align-items-center">
                <span class="badge bg-light text-dark me-2">
                    <i class="bi bi-people-fill me-1"></i> Left: {{ $root->left_count }}
                </span>
                <span class="badge bg-light text-dark">
                    <i class="bi bi-people-fill me-1"></i> Right: {{ $root->right_count }}
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Tree Controls -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-3">
                <div class="d-flex flex-wrap align-items-center">
                    <div class="me-3 mb-2">
                        <i class="bi bi-info-circle-fill text-teal-blue me-1"></i>
                        <span class="text-muted">Viewing tree for:</span>
                        <strong class="ms-1">{{ $root->name }}</strong>
                        <span class="badge bg-soft-cyan ms-2">{{ $root->username }}</span>
                    </div>
                    @if($root->id !== auth()->id())
                        <div class="ms-auto">
                            <a href="{{ route('member.genealogy') }}" class="btn btn-sm btn-outline-red">
                                <i class="bi bi-arrow-return-left me-1"></i> My Tree
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Binary Tree Visualization -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-dark-gray">
                    <i class="bi bi-diagram-3-fill text-red me-2"></i> Binary Tree
                </h5>
                <div class="d-flex">
                    <span class="badge bg-red me-2">Left</span>
                    <span class="badge bg-teal-blue me-2">Right</span>
                    <span class="badge bg-soft-cyan">You</span>
                </div>
            </div>
            <div class="card-body">
                <div class="binary-tree" x-data="genealogyTree({{ json_encode($tree) }})">
                    <template x-if="tree">
                        <div class="tree-container w-100">
                            <div class="tree-root">
                                <div class="d-flex justify-content-center">
                                    @include('member.genealogy.partials.node', ['node' => $root, 'position' => 'root', 'isRoot' => true])
                                </div>
                            </div>
                            <div class="tree-branch mt-4" x-show="tree.left || tree.right">
                                <div class="tree-node-wrapper" x-show="tree.left" x-transition>
                                    <div x-html="renderNode(tree.left, 'left')"></div>
                                    <div class="load-more" x-show="tree.left.has_more">
                                        <button class="load-more-btn" @click="loadMore(tree.left.id, 'left', $el)">
                                            <i class="bi bi-plus-circle"></i> Load More
                                        </button>
                                    </div>
                                </div>
                                <div class="tree-node-wrapper" x-show="tree.right" x-transition>
                                    <div x-html="renderNode(tree.right, 'right')"></div>
                                    <div class="load-more" x-show="tree.right.has_more">
                                        <button class="load-more-btn" @click="loadMore(tree.right.id, 'right', $el)">
                                            <i class="bi bi-plus-circle"></i> Load More
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <div x-show="!tree" class="text-center py-5">
                        <i class="bi bi-diagram-3 text-light-gray fs-1 mb-2"></i>
                        <p class="text-muted">No genealogy data available.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards (Left/Right Summary) -->
<div class="row mt-4">
    <div class="col-md-6 mb-3">
        <div class="card border-0 bg-light-red">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="bi bi-arrow-left-circle-fill fs-2 text-red"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-1 text-dark-gray">Left Team</h6>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Members:</span>
                            <span class="fw-bold">{{ $root->left_count }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">PV:</span>
                            <span class="fw-bold text-red">{{ number_format($root->left_pv, 1) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card border-0 bg-light-teal">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="bi bi-arrow-right-circle-fill fs-2 text-teal-blue"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-1 text-dark-gray">Right Team</h6>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Members:</span>
                            <span class="fw-bold">{{ $root->right_count }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">PV:</span>
                            <span class="fw-bold text-teal-blue">{{ number_format($root->right_pv, 1) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script src="{{ asset('js/alpine/genealogy.js') }}"></script>
@endpush