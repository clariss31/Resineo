<div class="page-header header-bg-catalogue">
    <div class="header-content">
        <h1>Catalogue</h1>
        <div class="breadcrumb">
            <a href="index.php?action=home">Accueil</a> > <span>Catalogue</span>
        </div>
    </div>
</div>

<div class="catalogue-container">
    <aside class="sidebar">
        <form id="filter-form" action="index.php" method="GET">
            <input type="hidden" name="action" value="catalogue">
            
            <div class="filter-group">
                <h3>Prix</h3>
                <div class="price-range-controls">
                    <div class="range-slider">
                        <div class="slider-track"></div>
                        <input type="range" id="price-min" name="min_price" 
                               min="<?= $minPrice ?>" max="<?= $maxPrice ?>" 
                               value="<?= $currentMinPrice ?>" step="1"
                               oninput="updatePriceDisplay()" onchange="this.form.submit()" aria-label="Prix minimum">
                        <input type="range" id="price-max" name="max_price" 
                               min="<?= $minPrice ?>" max="<?= $maxPrice ?>" 
                               value="<?= $currentMaxPrice ?>" step="1"
                               oninput="updatePriceDisplay()" onchange="this.form.submit()" aria-label="Prix maximum">
                    </div>
                </div>
                <div class="price-values">
                    <span id="price-min-display"><?= number_format($currentMinPrice, 0, ',', ' ') ?> €</span> - 
                    <span id="price-max-display"><?= number_format($currentMaxPrice, 0, ',', ' ') ?> €</span>
                </div>
            </div>

            <div class="filter-group">
                <h3>Catégorie</h3>
                <div class="category-filters">
                    <?php foreach ($categories as $id => $name): ?>
                        <div class="checkbox-group">
                            <input type="checkbox" id="cat-<?= $id ?>" name="categories[]" value="<?= $id ?>"
                                <?= (is_array($currentCategories) && in_array($id, $currentCategories)) || $currentCategories === $id ? 'checked' : '' ?>
                                onchange="this.form.submit()">
                            <label for="cat-<?= $id ?>"><?= $name ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </form>
    </aside>

    <div class="catalogue-content">
        <div class="results-header">
            <span class="results-count"><?= count($products) ?> résultats</span>
            
            <div class="right-header-controls">
                <?php if (isset($_SESSION['user']) && $_SESSION['user']->getRole() === 'admin'): ?>
                    <button id="btn-add-product" class="btn btn-dark">+ Créer un produit</button>
                <?php endif; ?>

                <div class="sort-controls">
                    <select name="sort" form="filter-form" onchange="this.form.submit()">
                        <option value="">Trier par</option>
                        <option value="price-ASC" <?= $currentSort === 'price-ASC' ? 'selected' : '' ?>>Prix croissant</option>
                        <option value="price-DESC" <?= $currentSort === 'price-DESC' ? 'selected' : '' ?>>Prix décroissant</option>
                        <option value="id-DESC" <?= $currentSort === 'id-DESC' ? 'selected' : '' ?>>Plus récents</option>
                        <option value="id-ASC" <?= $currentSort === 'id-ASC' ? 'selected' : '' ?>>Plus anciens</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <div class="product-card" id="product-<?= $product->getId() ?>">
                    <a href="index.php?action=showProduct&id=<?= $product->getId() ?>">
                        <img src="<?= $product->getImage() ?>" alt="<?= $product->getName() ?>">
                    </a>
                    <div class="product-info">
                        <a href="index.php?action=showProduct&id=<?= $product->getId() ?>" class="product-card-link">
                            <h3><?= $product->getName() ?></h3>
                        </a>
                        <p class="price"><?= number_format($product->getPrice(), 2) ?> €</p>
                        <?php if (!isset($_SESSION['user']) || $_SESSION['user']->getRole() !== 'admin'): ?>
                            <form action="index.php?action=addToQuote" method="post" class="add-to-quote-form">
                                <input type="hidden" name="product_id" value="<?= $product->getId() ?>">
                                <button type="submit" class="btn-quote">Ajouter au devis</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Admin Modal -->
<?php
$openModal = isset($_SESSION['open_modal']);
$oldInput = $_SESSION['form_submitted'] ?? [];
// Nettoyage de la session pour la prochaine fois
unset($_SESSION['open_modal']);
unset($_SESSION['form_submitted']);
?>
<div id="product-modal" class="modal <?= $openModal ? '' : 'hidden' ?>">
    <div class="modal-content">
        <form id="product-form" action="index.php?action=addProduct" method="POST" enctype="multipart/form-data">
            <div class="modal-grid">
                <div class="modal-left">
                    <div class="image-preview-container" id="image-preview-area">
                        <img src="img/photo.png" alt="Aperçu" id="image-preview" class="cursor-pointer" onclick="document.getElementById('product-image').click();">
                    </div>
                    <label for="product-image" class="btn btn-dark btn-small btn-full">Ajouter une image</label>
                    <input type="file" name="image" id="product-image" accept="image/*" class="hidden">
                </div>

                <div class="modal-right">
                    <div class="form-group">
                        <label for="product-name">Titre</label>
                        <input type="text" name="name" id="product-name" required value="<?= htmlspecialchars($oldInput['name'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label for="product-description">Description</label>
                        <textarea name="description" id="product-description" rows="4"><?= htmlspecialchars($oldInput['description'] ?? '') ?></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="product-category">Catégorie</label>
                            <select name="category_id" id="product-category" required>
                                <option value="">Choisir une catégorie</option>
                                <option value="1" <?= ($oldInput['category_id'] ?? '') == 1 ? 'selected' : '' ?>>Résines</option>
                                <option value="2" <?= ($oldInput['category_id'] ?? '') == 2 ? 'selected' : '' ?>>Entretien</option>
                                <option value="3" <?= ($oldInput['category_id'] ?? '') == 3 ? 'selected' : '' ?>>Outillage</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                         <div class="form-group">
                            <label for="product-price">Prix</label>
                            <input type="number" name="price" id="product-price" step="0.01" required value="<?= htmlspecialchars($oldInput['price'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group dynamic-field hidden" id="field-color">
                            <label for="product-color">Couleur</label>
                            <select name="color" id="product-color">
                                <option value="">Choisir une couleur</option>
                                <?php foreach ($distinctColors as $color): ?>
                                    <option value="<?= htmlspecialchars($color) ?>" <?= ($oldInput['color'] ?? '') == $color ? 'selected' : '' ?>><?= htmlspecialchars($color) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group dynamic-field hidden" id="field-scent">
                            <label for="no-scent">Sans odeur ?</label>
                            <select name="no_scent" id="no-scent">
                                <option value="no" <?= ($oldInput['no_scent'] ?? '') == 'no' ? 'selected' : '' ?>>Non</option>
                                <option value="yes" <?= ($oldInput['no_scent'] ?? '') == 'yes' ? 'selected' : '' ?>>Oui</option>
                            </select>
                        </div>

                        <div class="form-group dynamic-field hidden" id="field-tool-type">
                            <label for="product-tool-type">Type</label>
                            <select name="tool_type" id="product-tool-type">
                                <option value="">Choisir un type</option>
                                <?php foreach ($distinctToolTypes as $type): ?>
                                    <option value="<?= htmlspecialchars($type) ?>" <?= ($oldInput['tool_type'] ?? '') == $type ? 'selected' : '' ?>><?= htmlspecialchars($type) ?></option>
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
        <button id="close-modal" class="modal-close">&times;</button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('product-category');
    if (categorySelect.value) {
        categorySelect.dispatchEvent(new Event('change'));
    }
});
</script>

<script src="js/admin-products.js"></script>

<script>
function updatePriceDisplay() {
    const minRange = document.getElementById('price-min');
    const maxRange = document.getElementById('price-max');
    const minDisplay = document.getElementById('price-min-display');
    const maxDisplay = document.getElementById('price-max-display');

    // Prevent crossing
    if (parseInt(minRange.value) > parseInt(maxRange.value)) {
        const temp = minRange.value;
        minRange.value = maxRange.value;
        maxRange.value = temp;
    }

    minDisplay.textContent = minRange.value + ' €';
    maxDisplay.textContent = maxRange.value + ' €';
}
</script>