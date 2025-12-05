<h1>Catalogue</h1>
<div class="filters">
    <form action="index.php" method="GET">
        <input type="hidden" name="action" value="catalogue">
        <label for="category_id">Filtrer par catégorie :</label>
        <select name="category_id" id="category_id" onchange="this.form.submit()">
            <option value="">Toutes les catégories</option>
            <?php foreach ($categories as $id => $name): ?>
                <option value="<?= $id ?>" <?= (isset($currentCategory) && $currentCategory == $id) ? 'selected' : '' ?>>
                    <?= $name ?>
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