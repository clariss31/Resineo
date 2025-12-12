document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('product-modal');
    const openBtn = document.getElementById('btn-add-product');
    const closeBtn = document.getElementById('close-modal');
    const form = document.getElementById('product-form');

    // Champs de saisie
    const categorySelect = document.getElementById('product-category');
    const imageInput = document.getElementById('product-image');
    const imagePreview = document.getElementById('image-preview');

    // Champs dynamiques
    const fieldColor = document.getElementById('field-color');
    const fieldScent = document.getElementById('field-scent');
    const fieldToolType = document.getElementById('field-tool-type');

    /**
     * Bascule l'affichage de la modale.
     */
    function toggleModal() {
        modal.classList.toggle('hidden');
    }

    if (openBtn) {
        openBtn.addEventListener('click', toggleModal);
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', toggleModal);
    }

    if (modal) {
        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                toggleModal();
            }
        });
    }

    /**
     * Met à jour l'affichage des champs dynamiques selon la catégorie sélectionnée.
     */
    function updateFields() {
        const categoryId = categorySelect.value;

        // Réinitialiser tout
        fieldColor.classList.add('hidden');
        fieldScent.classList.add('hidden');
        fieldToolType.classList.add('hidden');

        // Logique basée sur l'ID de catégorie
        // 1: Résines -> Color
        // 2: Entretien -> Scent
        // 3: Outillage -> Tool Type
        if (categoryId === '1') {
            fieldColor.classList.remove('hidden');
        } else if (categoryId === '2') {
            fieldScent.classList.remove('hidden');
        } else if (categoryId === '3') {
            fieldToolType.classList.remove('hidden');
        }
    }

    if (categorySelect) {
        categorySelect.addEventListener('change', updateFields);
        updateFields(); // Init on load
    }

    // Logique de prévisualisation de l'image
    if (imageInput) {
        imageInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    imagePreview.src = e.target.result;
                    // Reset styling for placeholder
                    imagePreview.style.width = '100%';
                    imagePreview.style.height = '100%';
                    imagePreview.style.opacity = '1';
                }
                reader.readAsDataURL(file);
            }
        });
    }
});
