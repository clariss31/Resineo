<div class="page-header header-bg-resines">
    <div class="header-content">
        <h1>Résines</h1>
        <div class="breadcrumb">
            <a href="index.php?action=home">Accueil</a> > <span>Résines</span>
        </div>
    </div>
</div>

<div class="catalogue-container">
    <aside class="sidebar">
        <form id="filter-form" action="index.php" method="GET">
            <input type="hidden" name="action" value="resines">
            
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
                <h3>Couleur</h3>
                <div class="color-filters">
                    <label class="color-option">
                        <input type="radio" name="color" value="" onchange="this.form.submit()" <?= empty($currentFilter) ? 'checked' : '' ?>>
                        <span class="color-circle transparent">
                             <!-- Cross for "All" -->
                             <span class="reset-cross">✕</span>
                        </span>
                        Toutes
                    </label>
                    <?php foreach ($filterOptions as $color): ?>
                        <label class="color-option">
                            <input type="radio" name="color" value="<?= $color ?>" onchange="this.form.submit()" <?= ($currentFilter === $color) ? 'checked' : '' ?>>
                            <span class="color-circle <?= $color ?>"></span>
                            <?= $color ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

        </form>
    </aside>

    <main class="catalogue-content">
        <div class="results-header">
            <span class="results-count"><?= count($products) ?> résultats</span>
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

        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <img src="<?= $product->getImage() ?>" alt="<?= $product->getName() ?>">
                    <div class="product-info">
                        <h3><?= $product->getName() ?></h3>
                        <p class="price"><?= number_format($product->getPrice(), 2) ?> €</p>
                        <form action="index.php?action=addToQuote" method="post">
                            <input type="hidden" name="product_id" value="<?= $product->getId() ?>">
                            <button type="submit" class="btn-quote">Ajouter au devis</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
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