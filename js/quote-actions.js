document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('confirmation-modal');
    const modalMessage = document.getElementById('modal-message');
    const confirmBtn = document.getElementById('modal-confirm-btn');
    const cancelBtn = document.getElementById('modal-cancel-btn');
    const clearAllBtn = document.getElementById('clear-quote-btn');
    const deleteBtns = document.querySelectorAll('.btn-delete');

    let confirmAction = null;

    /**
     * Affiche la modale de confirmation.
     * @param {string} message Le message à afficher
     * @param {function} action L'action à exécuter si confirmé
     */
    function showModal(message, action) {
        modalMessage.textContent = message;
        confirmAction = action;
        modal.classList.remove('hidden');
    }

    /**
     * Masque la modale de confirmation.
     */
    function hideModal() {
        modal.classList.add('hidden');
        confirmAction = null;
    }

    if (clearAllBtn) {
        clearAllBtn.addEventListener('click', function (e) {
            e.preventDefault();
            showModal("Voulez-vous vraiment tout supprimer ?", function () {
                window.location.href = 'index.php?action=clearQuote';
            });
        });
    }

    deleteBtns.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const url = this.href;
            showModal("Voulez-vous vraiment supprimer le produit ?", function () {
                window.location.href = url;
            });
        });
    });

    if (confirmBtn) {
        confirmBtn.addEventListener('click', function () {
            if (confirmAction) {
                confirmAction();
            }
            hideModal();
        });
    }

    if (cancelBtn) {
        cancelBtn.addEventListener('click', hideModal);
    }

    // Fermer la modale si clic en dehors du contenu
    if (modal) {
        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                hideModal();
            }
        });
    }
});
