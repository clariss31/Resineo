/**
 * Affiche un message flash temporaire.
 * @param {string} message Le message à afficher
 */
function showFlashMessage(message) {
    let flash = document.getElementById('flash-message');
    if (flash) {
        flash.remove();
    }

    flash = document.createElement('div');
    flash.className = 'flash-message';
    flash.id = 'flash-message';
    flash.textContent = message;

    const main = document.querySelector('main');
    if (main) {
        main.appendChild(flash);
    } else {
        document.body.appendChild(flash);
    }

    setTimeout(() => {
        flash.style.opacity = '0';
        setTimeout(() => {
            if (flash.parentNode) {
                flash.remove();
            }
        }, 500);
    }, 3000);
}

/**
 * Configure une modale de confirmation générique.
 * @param {Object} config Configuration (modalId, messageId, confirmBtnId, cancelBtnId)
 * @returns {Object} Un objet avec les fonctions show et hide
 */
function setupConfirmationModal(config = {}) {
    const modal = document.getElementById(config.modalId || 'confirmation-modal');
    if (!modal) return null;

    const messageEl = document.getElementById(config.messageId || 'modal-message');
    const confirmBtn = document.getElementById(config.confirmBtnId || 'modal-confirm-btn');
    const cancelBtn = document.getElementById(config.cancelBtnId || 'modal-cancel-btn');

    let onConfirm = null;

    function show(message, callback) {
        if (messageEl) messageEl.textContent = message;
        onConfirm = callback;
        modal.classList.remove('hidden');
    }

    function hide() {
        modal.classList.add('hidden');
        onConfirm = null;
    }

    if (confirmBtn) {
        confirmBtn.addEventListener('click', () => {
            if (onConfirm) onConfirm();
            hide();
        });
    }

    if (cancelBtn) cancelBtn.addEventListener('click', hide);

    modal.addEventListener('click', (e) => {
        if (e.target === modal) hide();
    });

    return { show, hide };
}

/**
 * Met à jour l'affichage des valeurs min/max du filtre prix
 * et empêche le croisement des curseurs.
 */
function updatePriceDisplay() {
    const minRange = document.getElementById('price-min');
    const maxRange = document.getElementById('price-max');
    const minDisplay = document.getElementById('price-min-display');
    const maxDisplay = document.getElementById('price-max-display');

    if (minRange && maxRange && minDisplay && maxDisplay) {
        // Empêcher le croisement des valeurs (le min ne peut pas être > au max)
        if (parseInt(minRange.value) > parseInt(maxRange.value)) {
            const temp = minRange.value;
            minRange.value = maxRange.value;
            maxRange.value = temp;
        }

        // Mise à jour de l'affichage texte
        minDisplay.textContent = minRange.value + ' €';
        maxDisplay.textContent = maxRange.value + ' €';
    }
}
