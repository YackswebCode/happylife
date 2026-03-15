<script>
    // Nodes from controller (only root)
    var nodes = {!! $nodesJson !!};

    document.addEventListener('DOMContentLoaded', function() {
        // ----- Template definition (same as before) -----
        OrgChart.templates.myTemplate = Object.assign({}, OrgChart.templates.ana);
        var t = OrgChart.templates.myTemplate;
        t.size = [230, 210];

        t.node = function(node, data) {
            // ... same card HTML as before ...
            let positionClass = data.position || '';
            let emptyClass = data.isEmpty ? 'empty' : '';
            let youBadge = data.is_me ? '<span class="badge-you"><i class="bi bi-person-fill me-1"></i> You</span>' : '';
            let pv = data.isEmpty ? '0.0' : parseFloat(data.pv || 0).toFixed(1);
            let packageBlock = (!data.isEmpty && data.package) 
                ? '<div class="d-flex align-items-center text-teal small"><i class="bi bi-box-seam me-1"></i><span class="fw-semibold">' + data.package + '</span></div>'
                : '';
            let rank = data.isEmpty ? '—' : (data.rank || 'Member');
            let leftCount = data.isEmpty ? 0 : (data.left_count || 0);
            let rightCount = data.isEmpty ? 0 : (data.right_count || 0);
            let initial = data.isEmpty 
                ? '<i class="bi bi-plus-lg fs-4"></i>' 
                : (data.initial || '?');
            let username = data.isEmpty ? '—' : ('@' + (data.username || ''));

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

            return `<foreignObject width="${node.w}" height="${node.h}">
                        <div xmlns="http://www.w3.org/1999/xhtml" style="width:100%; height:100%;">
                            ${html}
                        </div>
                    </foreignObject>`;
        };

        t.fields = [];

        // ----- Initialize chart with dynamic loading -----
        var chart = new OrgChart('#orgchart-tree', {
            nodes: nodes,
            template: 'myTemplate',
            nodeBinding: {},
            layout: 2,
            align: OrgChart.ORIENTATION,
            depth: 2,              // initial visible depth
            scaleInitial: 0.85,
            enableZoom: true,
            enablePan: true,
            nodeMenu: false,
            menu: false,
            nodeChildren: function(node) {
                // Fetch children via AJAX when a node is expanded
                var promise = new Promise(function(resolve, reject) {
                    if (node.isEmpty) {
                        resolve([]); // empty nodes have no children
                        return;
                    }
                    fetch('{{ route("member.genealogy.children") }}?id=' + node.id)
                        .then(response => response.json())
                        .then(data => resolve(data))
                        .catch(error => reject(error));
                });
                return promise;
            },
            nodeClick: function(node) {
                if (node.isEmpty) return;
                // Navigate to the tree of the clicked member
                window.location = '{{ route("member.genealogy.index") }}?member=' + node.id;
            }
        });
    });
</script>