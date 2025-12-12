document.addEventListener('DOMContentLoaded', function () {
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
                        // Mise à jour du badge
                        const badgeContainer = document.querySelector('.header-icon-link');
                        let badge = document.querySelector('.quote-badge');

                        if (data.quoteCount > 0) {
                            if (!badge) {
                                badge = document.createElement('span');
                                badge.className = 'quote-badge';
                                badgeContainer.appendChild(badge);
                            }
                            badge.textContent = data.quoteCount;
                        } else if (badge) {
                            badge.remove();
                        }

                        // Affichage du message flash
                        showFlashMessage(data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    });
});

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

    document.querySelector('main').appendChild(flash);

    setTimeout(() => {
        flash.style.opacity = '0';
        setTimeout(() => flash.remove(), 500);
    }, 3000);
}
