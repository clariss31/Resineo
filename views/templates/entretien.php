<h2>Catégorie : Entretien</h2>
<p>Liste des produits de la catégorie Entretien.</p>
<div class="filters">
    <h3>Filtres</h3>
    <form action="index.php" method="GET">
        <input type="hidden" name="action" value="entretien">
        <label for="scent">Odeur :</label>
        <select name="scent" id="scent" onchange="this.form.submit()">
            <option value="">Toutes</option>
            <?php foreach ($filterOptions as $scent): ?>
                <?php
                $label = $scent;
                if ($scent === 'Non') {
                    $label = 'Sans odeur';
                }
                ?>
                <option value="<?= $scent ?>" <?= (isset($currentFilter) && $currentFilter == $scent) ? 'selected' : '' ?>>
                    <?= $label ?>
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