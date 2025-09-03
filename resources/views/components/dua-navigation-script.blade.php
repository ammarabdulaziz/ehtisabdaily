<script>
// Check for stored record ID on page load and open the modal
document.addEventListener('DOMContentLoaded', function() {
    const openRecordId = sessionStorage.getItem('openRecordId');
    if (openRecordId) {
        sessionStorage.removeItem('openRecordId');

        setTimeout(() => {
            const viewBtn = document.querySelector(`button.dua-id-${openRecordId}`);
            if (viewBtn) {
                viewBtn.click();
            }
        }, 1000);
    }
});

window.duaNavigation = function(recordIds, currentId, currentIndex) {
    return {
        recordIds: recordIds,
        currentId: currentId,
        currentIndex: currentIndex,

        navigateToPrevious() {
            if (this.currentIndex > 0) {
                const previousId = this.recordIds[this.currentIndex - 1];
                this.navigateToRecord(previousId);
            }
        },

        navigateToNext() {
            if (this.currentIndex < this.recordIds.length - 1) {
                const nextId = this.recordIds[this.currentIndex + 1];
                this.navigateToRecord(nextId);
            }
        },

        navigateToRecord(recordId) {
            // Close current modal first
            const modal = this.$el.closest('.fi-modal');
            if (modal) {
                const closeBtn = modal.querySelector('[data-close-modal]') ||
                                modal.querySelector('.fi-modal-close-btn') ||
                                modal.querySelector('button[type="button"]');
                if (closeBtn) {
                    closeBtn.click();
                }
            }

            // Use a direct approach without page reload
            setTimeout(() => {
                const viewBtn = document.querySelector(`button.dua-id-${recordId}`);
                if (viewBtn) {
                    viewBtn.click();
                } else {
                    // Fallback to page reload approach if button not found
                    sessionStorage.setItem('openRecordId', recordId);
                    window.location.reload();
                }
            }, 300);
        }
    };
};
</script>
