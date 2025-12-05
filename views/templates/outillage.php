<h1>Catégorie : Outillage</h1>
<div class="filters">
    <h3>Filtres</h3>
    <form action="index.php" method="GET">
        <input type="hidden" name="action" value="outillage">
        <label for="tool_type">Type :</label>
        <select name="tool_type" id="tool_type" onchange="this.form.submit()">
            <option value="">Tous</option>
            <?php foreach ($filterOptions as $type): ?>
                <option value="<?= $type ?>" <?= (isset($currentFilter) && $currentFilter == $type) ? 'selected' : '' ?>>
                    <?= $type ?>
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
                <span class="price"><?= number_format($product->getPrice(), 2) ?> €</span>
                <form action="index.php?action=addToQuote" method="post">
                    <input type="hidden" name="product_id" value="<?= $product->getId() ?>">
                    <button type="submit" class="btn-quote">Ajouter au devis</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>