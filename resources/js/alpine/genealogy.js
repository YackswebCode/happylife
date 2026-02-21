document.addEventListener('alpine:init', () => {
    Alpine.data('genealogyTree', (initialTree) => ({
        tree: initialTree,

        renderNode(node, position) {
            // This is a placeholder; we actually render via Blade partial on load.
            // For dynamic loading, we fetch HTML from server.
            return ''; // Handled by x-html from server response
        },

        async loadMore(memberId, position, buttonElement) {
            try {
                const response = await fetch(`/member/genealogy/load?member=${memberId}`);
                const data = await response.json();

                if (data.error) {
                    alert(data.error);
                    return;
                }

                // Replace the load more button with the children nodes
                const wrapper = buttonElement.closest('.tree-node-wrapper');
                
                if (position === 'left' && data.left) {
                    // Create a new container for the left child's subtree
                    const leftHtml = data.left;
                    // Insert after the current node (which is the parent's node)
                    // We need to find where to place it.
                    // Since our structure is simple, we can replace the wrapper's content
                    // or append the new nodes inside a new div.
                    // Better: the server returns the full node HTML, we append after the load button.
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = leftHtml;
                    const newNode = tempDiv.firstChild;
                    
                    // Insert before the load more button
                    buttonElement.parentElement.insertAdjacentHTML('beforebegin', leftHtml);
                }
                
                if (position === 'right' && data.right) {
                    buttonElement.parentElement.insertAdjacentHTML('beforebegin', data.right);
                }

                // Remove the load more button after successful load
                buttonElement.closest('.load-more').remove();

                // Update the has_more flag in the Alpine data (optional)
                if (position === 'left') {
                    this.tree.left.has_more = false;
                } else {
                    this.tree.right.has_more = false;
                }

            } catch (error) {
                console.error('Error loading children:', error);
                alert('Failed to load more nodes. Please try again.');
            }
        }
    }));
});