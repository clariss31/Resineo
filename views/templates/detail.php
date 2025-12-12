<div class="product-detail-page">
    <div class="detail-container">
        <!-- Breadcrumb -->
        <div class="detail-breadcrumb">
            <a href="index.php?action=home">Accueil</a> > <a
                href="index.php?action=<?= strtolower($categoryName) ?>"><?= $categoryName ?></a> >
            <?= $product->getName() ?>
        </div>

        <div class="product-main">
            <!-- Left: Image -->
            <div class="product-image-large">
                <img src="<?= htmlspecialchars($product->getImage()) ?>"
                    alt="<?= htmlspecialchars($product->getName()) ?>">
            </div>

            <!-- Right: Info -->
            <div class="product-info-detail">
                <h1><?= htmlspecialchars($product->getName()) ?></h1>

                <p class="description">
                    <?= nl2br(htmlspecialchars($product->getDescription())) ?>
                </p>

                <div class="price-action-row">
                    <span class="price-large"><?= number_format($product->getPrice(), 2, ',', ' ') ?> €</span>

                    <form action="index.php?action=addToQuote" method="POST" class="add-to-quote-detail">
                        <input type="hidden" name="product_id" value="<?= $product->getId() ?>">

                        <div class="qty-group">
                            <button type="button" class="qty-btn" onclick="decreaseQty()">-</button>
                            <input type="number" name="quantity" id="quantity" value="1" min="1" readonly>
                            <button type="button" class="qty-btn" onclick="increaseQty()">+</button>
                        </div>

                        <button type="submit" class="btn btn-dark">Ajouter au devis</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Force full width for this page by modifying the main container
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