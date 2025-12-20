<!-- Page d'accueil : Section Héro -->
<div class="home-hero">
    <div class="hero-left">
        <h1>Découvrez nos revêtements <br> <span class="text-green">techniques</span> et <span
                class="text-green">innovants</span></h1>

        <div class="applicator-pill">
            <div class="applicator-avatars">
                <img src="img/applicateur1.png" alt="App1">
                <img src="img/applicateur2.png" alt="App2">
                <img src="img/applicateur3.png" alt="App3">
            </div>
            <span>Rejoignez nos 500+ applicateurs !</span>
        </div>

        <a href="index.php?action=messagerie&prefill=Bonjour%2C%20je%20voudrais%20devenir%20applicateur"
            class="btn btn-dark">Devenir applicateur</a>
    </div>

    <div class="hero-right-grid">
        <div class="grid-item item-large bg-resines">
            <a href="index.php?action=resines" class="cat-btn">Résines <img src="img/flèche-droite.png" alt=">"></a>
        </div>
        <div class="grid-col">
            <div class="grid-item item-small bg-entretien">
                <a href="index.php?action=entretien" class="cat-btn">Entretien <img src="img/flèche-droite.png"
                        alt=">"></a>
            </div>
            <div class="grid-item item-small bg-outillage">
                <a href="index.php?action=outillage" class="cat-btn">Outillage <img src="img/flèche-droite.png"
                        alt=">"></a>
            </div>
        </div>
    </div>
</div>

<!-- Section Nouveautés -->
<div class="new-products-section">
    <h2>Nouveautés</h2>

    <div class="products-grid">
        <?php foreach ($newProducts as $product): ?>
            <div class="product-card">
                <div class="product-image">
                    <a href="index.php?action=showProduct&id=<?= $product->getId() ?>" aria-hidden="true" tabindex="-1">
                        <img src="<?= htmlspecialchars($product->getImage()) ?>"
                            alt="<?= htmlspecialchars($product->getName()) ?>">
                    </a>
                </div>
                <div class="product-info">
                    <a href="index.php?action=showProduct&id=<?= $product->getId() ?>" class="product-card-link">
                        <h3><?= htmlspecialchars($product->getName()) ?></h3>
                    </a>
                    <p class="price"><?= number_format($product->getPrice(), 2, ',', ' ') ?> €</p>
                    <?php if (!isset($_SESSION['user']) || $_SESSION['user']->getRole() !== 'admin'): ?>
                        <form action="index.php?action=addToQuote" method="POST" class="add-to-quote-form">
                            <input type="hidden" name="product_id" value="<?= $product->getId() ?>">
                            <button type="submit" class="btn-quote">Ajouter au devis</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="text-center home-catalogue-container">
        <a href="index.php?action=catalogue" class="btn btn-dark">Catalogue</a>
    </div>
</div>

<!-- Section Fonctionnalités -->
<div class="features-section">
    <h2>Résineo, le revêtement de sol drainant <br> esthétique et certifié</h2>

    <div class="features-grid">
        <div class="feature-item">
            <img src="img/technique.png" alt="Technique">
            <h3>TECHNIQUE</h3>
            <p>Drainant et<br>Anti-glissant</p>
        </div>
        <div class="feature-item">
            <img src="img/entretien-facile.png" alt="Entretien">
            <h3>ENTRETIEN FACILE</h3>
            <p>Ne se tache pas</p>
        </div>
        <div class="feature-item">
            <img src="img/durable.png" alt="Durable">
            <h3>DURABLE</h3>
            <p>Résistant<br>au gel</p>
        </div>
        <div class="feature-item">
            <img src="img/certifie.png" alt="Certifié">
            <h3>CERTIFIÉ</h3>
            <p>Revêtement certifié<br>par le CSTB</p>
        </div>
    </div>
</div>