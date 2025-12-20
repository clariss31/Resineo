/**
 * Gestion centralisée du devis (Ajout AJAX et suppression avec confirmation)
 */
document.addEventListener('DOMContentLoaded', function () {
    // 1. Aout au devis (ajax)
    const forms = document.querySelectorAll('.add-to-quote-form');
    forms.forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const url = this.action;

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateQuoteBadge(data.quoteCount);
                        if (typeof showFlashMessage === 'function') {
                            showFlashMessage(data.message);
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    });

    // Mise à jour du nombre de produits dans le panier
    function updateQuoteBadge(count) {
        const badgeContainer = document.querySelector('.header-icon-link');
        if (!badgeContainer) return;

        let badge = document.querySelector('.quote-badge');
        if (count > 0) {
            if (!badge) {
                badge = document.createElement('span');
                badge.className = 'quote-badge';
                badgeContainer.appendChild(badge);
            }
            badge.textContent = count;
        } else if (badge) {
            badge.remove();
        }
    }

    // 2. Actions de suppression (page devis)
    const quoteModal = setupConfirmationModal({
        modalId: 'confirmation-modal',
        messageId: 'modal-message',
        confirmBtnId: 'modal-confirm-btn',
        cancelBtnId: 'modal-cancel-btn'
    });

    if (quoteModal) {
        const clearAllBtn = document.getElementById('clear-quote-btn');
        const deleteBtns = document.querySelectorAll('.btn-delete');

        if (clearAllBtn) {
            clearAllBtn.addEventListener('click', function (e) {
                e.preventDefault();
                quoteModal.show("Voulez-vous vraiment tout supprimer ?", function () {
                    window.location.href = 'index.php?action=clearQuote';
                });
            });
        }

        deleteBtns.forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const url = this.href;
                quoteModal.show("Voulez-vous vraiment supprimer le produit ?", function () {
                    window.location.href = url;
                });
            });
        });
    }
});
