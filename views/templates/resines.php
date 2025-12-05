<h2>Catégorie : Résines</h2>
<p>Liste des produits de la catégorie Résines.</p>
<div class="filters">
    <h3>Filtres</h3>
    <form action="index.php" method="GET">
        <input type="hidden" name="action" value="resines">
        <label for="color">Couleur :</label>
        <select name="color" id="color" onchange="this.form.submit()">
            <option value="">Toutes</option>
            <?php foreach ($filterOptions as $color): ?>
                <option value="<?= $color ?>" <?= (isset($currentFilter) && $currentFilter == $color) ? 'selected' : '' ?>>
                    <?= $color ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
</div>

<div class="product-list">
    <?php if (empty($products)): ?>
        <p>Aucun produit trouvé.</p>
    <?php else: ?>
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <img src="<?= $product->getImage() ?>" alt="<?= $product->getName() ?>">
                <h3><?= $product->getName() ?></h3>
                <p><?= $product->getDescription() ?></p>
                <span class="price"><?= number_format($product->getPrice(), 2) ?> €</span>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>