document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('product-modal');
    const openBtn = document.getElementById('btn-add-product');
    const closeBtn = document.getElementById('close-modal');
    const form = document.getElementById('product-form');
    
    // Inputs
    const categorySelect = document.getElementById('product-category');
    const imageInput = document.getElementById('product-image');
    const imagePreview = document.getElementById('image-preview');

    // Dynamic Fields
    const fieldColor = document.getElementById('field-color');
    const fieldScent = document.getElementById('field-scent');
    const fieldToolType = document.getElementById('field-tool-type');

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
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                toggleModal();
            }
        });
    }

    // Dynamic Fields Logic
    function updateFields() {
        const categoryId = categorySelect.value;
        
        // Reset all
        fieldColor.classList.add('hidden');
        fieldScent.classList.add('hidden');
        fieldToolType.classList.add('hidden');

        // Logic based on Category ID
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

    // Image Preview Logic
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
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
