@extends('layouts.member')

@section('title', 'Genealogy Tree - Happylife Multipurpose Int\'l')

@push('styles')
<style>
    /* ---------- OrgChart Container ---------- */
    #orgchart-tree {
        width: 100%;
        height: 700px;
        margin: 0 auto;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        background: #f9fbfd;
    }

    /* ---------- Custom Card Styles (exactly as before) ---------- */
    .orgchart-card {
        background: white;
        border-radius: 16px;
        padding: 16px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        border-left: 6px solid #e63323;
        transition: transform 0.2s, box-shadow 0.2s;
        min-width: 230px;
        position: relative;
    }
    .orgchart-card.left {
        border-left-color: #1e7e7e;
    }
    .orgchart-card.right {
        border-left-color: #6bc5d2;
    }
    .orgchart-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }
    .orgchart-card.empty {
        background: #f8fafc;
        border: 2px dashed #cbd5e0;
        border-left: 6px solid #94a3b8;
    }
    .orgchart-card.empty .avatar-circle {
        background: #e2e8f0 !important;
        color: #64748b;
        box-shadow: none;
    }

    .badge-you {
        position: absolute;
        top: -10px;
        right: -10px;
        background: #e63323;
        color: white;
        border-radius: 30px;
        padding: 4px 14px;
        font-size: 0.75rem;
        font-weight: 600;
        border: 2px solid white;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        z-index: 20;
    }

    .avatar-circle {
        width: 48px;
        height: 48px;
        background: linear-gradient(145deg, #e63323, #b3281c);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.2rem;
        box-shadow: 0 6px 12px rgba(230,51,35,0.2);
    }
    .left .avatar-circle {
        background: linear-gradient(145deg, #1e7e7e, #145a5a);
    }
    .right .avatar-circle {
        background: linear-gradient(145deg, #6bc5d2, #4f9ca8);
    }

    .text-teal { color: #1e7e7e !important; }
    .text-cyan { color: #6bc5d2 !important; }
    .bg-light-danger { background-color: rgba(230,51,35,0.08) !important; }
    .bg-light-warning { background-color: rgba(255,193,7,0.08) !important; }
    .bg-light-teal { background-color: rgba(30,126,126,0.08) !important; }
    .bg-light-cyan { background-color: rgba(107,197,210,0.08) !important; }

    /* Ensure Bootstrap Icons are loaded */
    @import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css");
</style>
@endpush

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-dark">Genealogy Tree</h1>
            <p class="text-muted mb-0">Binary network (3 generations)</p>
        </div>
        <div class="d-flex gap-2">
            <span class="badge bg-light border text-dark px-3 py-2">
                <i class="bi bi-people-fill text-teal me-1"></i> Left: {{ $root->left_count ?? 0 }}
            </span>
            <span class="badge bg-light border text-dark px-3 py-2">
                <i class="bi bi-people-fill text-cyan me-1"></i> Right: {{ $root->right_count ?? 0 }}
            </span>
        </div>
    </div>

    <!-- Context Bar -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <div class="d-flex flex-wrap align-items-center">
                <i class="bi bi-info-circle-fill text-teal me-2"></i>
                <span class="text-muted me-2">Viewing tree for:</span>
                <strong class="me-2">{{ $root->name }}</strong>
                <span class="badge bg-soft-cyan px-3 py-1 me-3">{{ '@'.$root->username }}</span>
                @if($root->id !== auth()->id())
                    <a href="{{ route('member.genealogy.index') }}" class="btn btn-outline-danger rounded-pill px-4 ms-auto">
                        <i class="bi bi-arrow-return-left me-1"></i> My Tree
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Interactive Tree (BALKAN OrgChart) -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center rounded-4">
            <h5 class="mb-0">
                <i class="bi bi-diagram-3-fill text-danger me-2"></i> Binary Structure
            </h5>
            <div class="d-flex gap-2">
                <span class="badge bg-teal">Left</span>
                <span class="badge bg-cyan">Right</span>
                <span class="badge bg-danger">You</span>
            </div>
        </div>
        <div class="card-body p-4">
            <div id="orgchart-tree"></div>
        </div>
    </div>

    <!-- Left / Right Summary Cards (unchanged) -->
    <div class="row mt-5 g-3">
        <div class="col-md-6">
            <div class="card border-0 bg-light-teal rounded-4">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-white p-3 me-3 shadow-sm">
                            <i class="bi bi-arrow-left-circle-fill fs-2 text-teal"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Left Team</h6>
                            <div class="d-flex gap-3">
                                <span class="text-muted">Members: <strong class="text-dark">{{ $root->left_count ?? 0 }}</strong></span>
                                <span class="text-muted">PV: <strong class="text-teal">{{ number_format($root->left_pv ?? 0, 1) }}</strong></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 bg-light-cyan rounded-4">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-white p-3 me-3 shadow-sm">
                            <i class="bi bi-arrow-right-circle-fill fs-2 text-cyan"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Right Team</h6>
                            <div class="d-flex gap-3">
                                <span class="text-muted">Members: <strong class="text-dark">{{ $root->right_count ?? 0 }}</strong></span>
                                <span class="text-muted">PV: <strong class="text-cyan">{{ number_format($root->right_pv ?? 0, 1) }}</strong></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- BALKAN OrgChart JS -->
<script src="https://cdn.balkan.app/orgchart.js"></script>

<script>
    // Nodes from controller
    var nodes = {!! $nodesJson !!};

    // ---------- Wait for DOM ----------
    document.addEventListener('DOMContentLoaded', function() {
        // ----- 1. Inherit from base template (ana) -----
        OrgChart.templates.myTemplate = Object.assign({}, OrgChart.templates.ana);
        var t = OrgChart.templates.myTemplate;

        // ----- 2. Set node size (match your card width/height) -----
        t.size = [230, 210];

        // ----- 3. Define node as SVG foreignObject containing your HTML card -----
        t.node = function(node, data) {
            // Prepare dynamic classes
            let positionClass = data.position || '';
            let emptyClass = data.isEmpty ? 'empty' : '';
            let youBadge = data.is_me ? '<span class="badge-you"><i class="bi bi-person-fill me-1"></i> You</span>' : '';

            // Format PV
            let pv = data.isEmpty ? '0.0' : parseFloat(data.pv || 0).toFixed(1);

            // Package block
            let packageBlock = (!data.isEmpty && data.package) 
                ? '<div class="d-flex align-items-center text-teal small"><i class="bi bi-box-seam me-1"></i><span class="fw-semibold">' + data.package + '</span></div>'
                : '';

            // Rank
            let rank = data.isEmpty ? '—' : (data.rank || 'Member');

            // Left / Right counts
            let leftCount = data.isEmpty ? 0 : (data.left_count || 0);
            let rightCount = data.isEmpty ? 0 : (data.right_count || 0);

            // Initial (avatar)
            let initial = data.isEmpty 
                ? '<i class="bi bi-plus-lg fs-4"></i>' 
                : (data.initial || '?');

            // Username
            let username = data.isEmpty ? '—' : ('@' + (data.username || ''));

            // ----- The full HTML card (exactly your design) -----
            let html = `
                <div class="orgchart-card ${positionClass} ${emptyClass}" style="width:100%; height:100%; border: 2px solid rgba(255, 4, 4, 0.92); border-radius: 8px;">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="avatar-circle">${initial}</div>
                        <div class="text-start">
                            <div class="fw-bold text-dark">${data.name || ''}</div>
                            <small class="text-muted">${username}</small>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-light-danger text-danger px-3 py-2 rounded-pill">
                            <i class="bi bi-coin me-1"></i> ${pv} PV
                        </span>
                        <span class="badge bg-light-warning text-warning px-3 py-2 rounded-pill">
                            <i class="bi bi-trophy me-1"></i> ${rank}
                        </span>
                    </div>
                    ${packageBlock}
                    <div class="mt-2 pt-2 border-top small text-muted d-flex justify-content-between">
                        <span><i class="bi bi-arrow-left-circle me-1"></i> L: ${leftCount}</span>
                        <span><i class="bi bi-arrow-right-circle me-1"></i> R: ${rightCount}</span>
                    </div>
                    ${youBadge}
                </div>
            `;

            // Wrap in SVG foreignObject
            return `<foreignObject width="${node.w}" height="${node.h}">
                        <div xmlns="http://www.w3.org/1999/xhtml" style="width:100%; height:100%;">
                            ${html}
                        </div>
                    </foreignObject>`;
        };

        // ----- 4. Remove unused fields (we don't need nodeBinding for content) -----
        t.fields = [];
        t.field_0 = '';
        t.field_1 = '';

        // ----- 5. Initialize OrgChart -----
        var chart = new OrgChart('#orgchart-tree', {
            nodes: nodes,
            template: 'myTemplate',
            nodeBinding: {},  // no field binding needed
            layout: 2,
            align: OrgChart.ORIENTATION,
            depth: 2,
            scaleInitial: 0.85,
            enableZoom: true,
            enablePan: true,
            nodeMenu: false,
            menu: false,
            nodeClick: function(node) {
                if (typeof node.id === 'string' && node.id.startsWith('empty-')) return;
                window.location = '/member/genealogy?member=' + node.id;
            }
        });
    });
</script>
@endpush