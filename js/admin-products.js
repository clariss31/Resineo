/**
 * Gestion du formulaire d'ajout de produit (Administration)
 */
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('product-modal');
    const openBtn = document.getElementById('btn-add-product');
    const closeBtn = document.getElementById('close-modal');

    // Éléments du formulaire
    const categorySelect = document.getElementById('product-category');
    const imageInput = document.getElementById('product-image');
    const imagePreview = document.getElementById('image-preview');

    // Zones de champs spécifiques (dynamiques)
    const fieldColor = document.getElementById('field-color');
    const fieldScent = document.getElementById('field-scent');
    const fieldToolType = document.getElementById('field-tool-type');

    /**
     * Alterne l'affichage de la fenêtre modale.
     */
    function toggleModal() {
        if (modal) {
            modal.classList.toggle('hidden');
        }
    }

    // Ouverture de la modale
    if (openBtn) {
        openBtn.addEventListener('click', toggleModal);
    }

    // Fermeture avec le bouton (croix ou annuler)
    if (closeBtn) {
        closeBtn.addEventListener('click', toggleModal);
    }

    // Fermeture en cliquant sur l'arrière-plan
    if (modal) {
        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                toggleModal();
            }
        });
    }

    /**
     * Met à jour l'affichage des champs dynamiques selon la catégorie sélectionnée.
     * ID 1: Résines -> Couleur
     * ID 2: Entretien -> Odeur (Oui/Non)
     * ID 3: Outillage -> Type d'outil
     */
    function updateFields() {
        if (!categorySelect) return;

        const categoryId = categorySelect.value;

        // On masque tout avant de réafficher le nécessaire
        [fieldColor, fieldScent, fieldToolType].forEach(f => {
            if (f) f.classList.add('hidden');
        });

        if (categoryId === '1') {
            if (fieldColor) fieldColor.classList.remove('hidden');
        } else if (categoryId === '2') {
            if (fieldScent) fieldScent.classList.remove('hidden');
        } else if (categoryId === '3') {
            if (fieldToolType) fieldToolType.classList.remove('hidden');
        }
    }

    // Mise à jour au changement de catégorie et à l'initialisation
    if (categorySelect) {
        categorySelect.addEventListener('change', updateFields);
        updateFields();
    }

    /**
     * Prévisualisation de l'image sélectionnée avant enregistrement.
     */
    if (imageInput && imagePreview) {
        imageInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    imagePreview.src = e.target.result;
                    // L'opacité et les dimensions sont gérées par le CSS (.image-preview-container img)
                    // On s'assure juste que l'opacité est à 1 si elle avait été baissée pour le placeholder
                    imagePreview.style.opacity = '1';
                }
                reader.readAsDataURL(file);
            }
        });
    }
});
