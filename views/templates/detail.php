<div class="product-detail-page">
    <div class="detail-container">


        <div class="product-main">
            <div class="product-image-large">
                <img src="<?= htmlspecialchars($product->getImage()) ?>"
                    alt="<?= htmlspecialchars($product->getName()) ?>">
            </div>

            <div class="product-info-detail">
                <div class="detail-breadcrumb">
                    <?php
                    $catSlugMap = [
                        'Résines' => 'resines',
                        'Entretien' => 'entretien',
                        'Outillage' => 'outillage'
                    ];
                    $catKey = isset($categoryName) ? (string) $categoryName : '';
                    $catSlug = $catSlugMap[$catKey] ?? 'catalogue';
                    ?>
                    <a href="index.php?action=home">Accueil</a> > <a
                        href="index.php?action=<?= $catSlug ?>"><?= $categoryName ?></a> >
                    <?= $product->getName() ?>
                </div>
                <h1><?= htmlspecialchars($product->getName()) ?></h1>

                <p class="description">
                    <?= nl2br(htmlspecialchars($product->getDescription())) ?>
                </p>

                <div class="price-action-row">
                    <span class="price-large"><?= number_format($product->getPrice(), 2, ',', ' ') ?> €</span>

                    <?php if (!isset($_SESSION['user']) || $_SESSION['user']->getRole() !== 'admin'): ?>
                        <form action="index.php?action=addToQuote" method="POST" class="add-to-quote-detail">
                            <input type="hidden" name="product_id" value="<?= $product->getId() ?>">

                            <div class="qty-group">
                                <button type="button" class="qty-btn" onclick="decreaseQty()">-</button>
                                <input type="number" name="quantity" id="quantity" value="1" min="1" readonly>
                                <button type="button" class="qty-btn" onclick="increaseQty()">+</button>
                            </div>

                            <button type="submit" class="btn btn-dark">Ajouter au devis</button>
                        </form>
                    <?php endif; ?>
                </div>

                <?php if (isset($_SESSION['user']) && $_SESSION['user']->getRole() === 'admin'): ?>
                    <div class="admin-actions">
                        <button id="btn-edit-product" class="btn btn-dark">Modifier le produit</button>
                        <button type="button" id="btn-delete-product" class="btn btn-white">Supprimer le produit</button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div id="edit-modal" class="modal hidden">
    <div class="modal-content">
        <form id="edit-form" action="index.php?action=updateProduct" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $product->getId() ?>">

            <div class="modal-grid">
                <div class="modal-left">
                    <div class="image-preview-container">
                        <img src="<?= htmlspecialchars($product->getImage()) ?>" alt="Aperçu" id="edit-image-preview">
                    </div>
                    <label for="edit-image" class="btn btn-dark btn-small btn-full">Modifier l'image</label>
                    <input type="file" name="image" id="edit-image" accept="image/*" class="hidden">
                </div>

                <div class="modal-right">
                    <div class="form-group">
                        <label for="edit-name">Titre</label>
                        <input type="text" name="name" id="edit-name"
                            value="<?= htmlspecialchars($product->getName()) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="edit-description">Description</label>
                        <textarea name="description" id="edit-description"
                            rows="5"><?= htmlspecialchars($product->getDescription()) ?></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit-category">Catégorie</label>
                            <select name="category_id" id="edit-category" required>
                                <option value="1" <?= $product->getCategoryId() == 1 ? 'selected' : '' ?>>Résines</option>
                                <option value="2" <?= $product->getCategoryId() == 2 ? 'selected' : '' ?>>Entretien
                                </option>
                                <option value="3" <?= $product->getCategoryId() == 3 ? 'selected' : '' ?>>Outillage
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit-price">Prix</label>
                            <input type="number" name="price" id="edit-price" step="0.01"
                                value="<?= $product->getPrice() ?>" required>
                        </div>

                        <div class="form-group dynamic-field hidden" id="edit-field-color">
                            <label for="edit-color">Couleur</label>
                            <select name="color" id="edit-color">
                                <option value="">Choisir une couleur</option>
                                <?php foreach ($distinctColors as $color): ?>
                                    <option value="<?= htmlspecialchars($color) ?>" <?= $product->getColor() === $color ? 'selected' : '' ?>><?= htmlspecialchars($color) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group dynamic-field hidden" id="edit-field-scent">
                            <label for="edit-no-scent">Sans odeur ?</label>
                            <select name="no_scent" id="edit-no-scent">
                                <option value="no" <?= $product->getScent() !== 'Sans odeur' ? 'selected' : '' ?>>Non
                                </option>
                                <option value="yes" <?= $product->getScent() === 'Sans odeur' ? 'selected' : '' ?>>Oui
                                </option>
                            </select>
                        </div>

                        <div class="form-group dynamic-field hidden" id="edit-field-tool-type">
                            <label for="edit-tool-type">Type</label>
                            <select name="tool_type" id="edit-tool-type">
                                <option value="">Choisir un type</option>
                                <?php foreach ($distinctToolTypes as $type): ?>
                                    <option value="<?= htmlspecialchars($type) ?>" <?= $product->getToolType() === $type ? 'selected' : '' ?>><?= htmlspecialchars($type) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="modal-actions">
                        <button type="submit" class="btn btn-dark">Enregistrer</button>
                    </div>
                </div>
            </div>
        </form>
        <button id="close-edit-modal" class="modal-close">&times;</button>
    </div>
</div>

<div id="delete-modal" class="modal-overlay hidden">
    <div class="modal-content">
        <p id="modal-message-delete">Voulez-vous vraiment supprimer le produit ?</p>
        <div class="modal-actions modal-actions-centered">
            <button id="confirm-delete" class="btn btn-dark">Supprimer</button>
            <button id="cancel-delete" class="btn btn-white">Annuler</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const editModal = document.getElementById('edit-modal');
        const editBtn = document.getElementById('btn-edit-product');
        const closeEditBtn = document.getElementById('close-edit-modal');

        function toggleEditModal() {
            editModal.classList.toggle('hidden');
        }

        if (editBtn) editBtn.addEventListener('click', toggleEditModal);
        if (closeEditBtn) closeEditBtn.addEventListener('click', toggleEditModal);

        const deleteBtn = document.getElementById('btn-delete-product');
        const deleteModal = setupConfirmationModal({
            modalId: 'delete-modal',
            messageId: 'modal-message-delete',
            confirmBtnId: 'confirm-delete',
            cancelBtnId: 'cancel-delete'
        });

        if (deleteBtn && deleteModal) {
            deleteBtn.addEventListener('click', () => {
                deleteModal.show("Voulez-vous vraiment supprimer le produit ?", () => {
                    window.location.href = 'index.php?action=deleteProduct&id=<?= $product->getId() ?>';
                });
            });
        }

        const catSelect = document.getElementById('edit-category');
        const fColor = document.getElementById('edit-field-color');
        const fScent = document.getElementById('edit-field-scent');
        const fTool = document.getElementById('edit-field-tool-type');

        function updateEditFields() {
            if (!catSelect) return;
            const val = catSelect.value;
            [fColor, fScent, fTool].forEach(f => {
                if (f) f.classList.add('hidden');
            });

            if (val === '1' && fColor) fColor.classList.remove('hidden');
            else if (val === '2' && fScent) fScent.classList.remove('hidden');
            else if (val === '3' && fTool) fTool.classList.remove('hidden');
        }

        if (catSelect) {
            catSelect.addEventListener('change', updateEditFields);
            updateEditFields();
        }

        const imgInput = document.getElementById('edit-image');
        const imgPreview = document.getElementById('edit-image-preview');

        if (imgInput) {
            imgInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) { imgPreview.src = e.target.result; }
                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        const mainElement = document.querySelector('main');
        if (mainElement) {
            mainElement.style.maxWidth = '100%';
            mainElement.style.padding = '0';
            mainElement.style.margin = '0';
        }
    });

    function increaseQty() {
        var qtyInput = document.getElementById('quantity');
        var currentVal = parseInt(qtyInput.value);
        qtyInput.value = currentVal + 1;
    }

    function decreaseQty() {
        var qtyInput = document.getElementById('quantity');
        var currentVal = parseInt(qtyInput.value);
        if (currentVal > 1) {
            qtyInput.value = currentVal - 1;
        }
    }
</script>