<div class="page-header header-bg-search">
    <div class="header-content">
        <h1>Résultats de la recherche pour "<?= htmlspecialchars($searchTerm) ?>"</h1>
        <div class="breadcrumb">
            <a href="index.php?action=home">Accueil</a> > <span>Recherche</span>
        </div>
    </div>
</div>

<div class="catalogue-container">
    <aside class="sidebar">
        <form id="filter-form" action="index.php" method="GET">
            <input type="hidden" name="action" value="search">
            <input type="hidden" name="search" value="<?= htmlspecialchars($searchTerm) ?>">
            
            <div class="filter-group">
                <h3>Prix</h3>
                <div class="price-range-controls">
                    <div class="range-slider">
                        <div class="slider-track"></div>
                        <input type="range" id="price-min" name="min_price" 
                               min="<?= $minPrice ?>" max="<?= $maxPrice ?>" 
                               value="<?= $currentMinPrice ?>" step="1"
                               oninput="updatePriceDisplay()" onchange="this.form.submit()">
                        <input type="range" id="price-max" name="max_price" 
                               min="<?= $minPrice ?>" max="<?= $maxPrice ?>" 
                               value="<?= $currentMaxPrice ?>" step="1"
                               oninput="updatePriceDisplay()" onchange="this.form.submit()">
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
                                <?= (is_array($currentCategories) && in_array($id, $currentCategories)) ? 'checked' : '' ?>
                                onchange="this.form.submit()">
                            <label for="cat-<?= $id ?>"><?= $name ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </form>
    </aside>

    <main class="catalogue-content">
        <div class="results-header">
            <span class="results-count"><?= count($products) ?> résultat<?= count($products) > 1 ? 's' : '' ?></span>
            
            <div class="right-header-controls">
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
            <?php if (empty($products)): ?>
                <p class="no-results-message">Aucun produit ne correspond à votre recherche.</p>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <div class="product-card" id="product-<?= $product->getId() ?>">
                        <a href="index.php?action=showProduct&id=<?= $product->getId() ?>">
                            <img src="<?= $product->getImage() ?>" alt="<?= $product->getName() ?>">
                        </a>
                        <div class="product-info">
                            <a href="index.php?action=showProduct&id=<?= $product->getId() ?>" style="text-decoration: none; color: inherit;">
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
            <?php endif; ?>
        </div>
    </main>
</div>

<script>
function updatePriceDisplay() {
    const minRange = document.getElementById('price-min');
    const maxRange = document.getElementById('price-max');
    const minDisplay = document.getElementById('price-min-display');
    const maxDisplay = document.getElementById('price-max-display');

    if (parseInt(minRange.value) > parseInt(maxRange.value)) {
        const temp = minRange.value;
        minRange.value = maxRange.value;
        maxRange.value = temp;
    }

    minDisplay.textContent = minRange.value + ' €';
    maxDisplay.textContent = maxRange.value + ' €';
}
</script>
