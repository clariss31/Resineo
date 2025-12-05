<h2>Produits ajoutés à la demande de devis</h2>

<?php if (empty($quoteItems)): ?>
    <p>Votre devis est vide.</p>
<?php else: ?>
    <div class="quote-list">
        <?php foreach ($quoteItems as $item): ?>
            <div class="quote-item">
                <div class="quote-item-info">
                    <img src="<?= $item->getImage() ?>" alt="<?= $item->getName() ?>">
                    <div class="quote-item-details">
                        <h3><?= $item->getName() ?></h3>
                        <span class="price"><?= number_format($item->getPrice(), 2) ?> €</span>
                    </div>
                </div>
                <div class="quote-item-actions">
                    <div class="quantity-selector">
                        <a href="index.php?action=updateQuantity&product_id=<?= $item->getId() ?>&direction=decrease">-</a>
                        <span><?= $item->quantity ?></span>
                        <a href="index.php?action=updateQuantity&product_id=<?= $item->getId() ?>&direction=increase">+</a>
                    </div>
                    <a href="index.php?action=removeFromQuote&product_id=<?= $item->getId() ?>" class="btn-delete">Supprimer</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="quote-total">
        <h3>Total estimé : <?= number_format($total, 2) ?> €</h3>
    </div>

    <div class="auth-container quote-form-container">
        <h2>Demander un devis</h2>
        <form action="index.php?action=sendQuote" method="post" class="login-form">
            <div class="form-group">
                <label for="title">Titre<span class="required">*</span></label>
                <input type="text" name="title" id="title" required>
            </div>

            <div class="form-group">
                <label for="message">Message<span class="required">*</span></label>
                <textarea name="message" id="message" rows="5" class="quote-form-textarea" required></textarea>
            </div>

            <div class="form-row">
                <div class="form-group form-group-large">
                    <label for="address">Adresse du chantier<span class="required">*</span></label>
                    <input type="text" name="address" id="address" required>
                </div>
                <div class="form-group">
                    <label for="postal_code">Code postal<span class="required">*</span></label>
                    <input type="text" name="postal_code" id="postal_code" required>
                </div>
                <div class="form-group">
                    <label for="city">Ville<span class="required">*</span></label>
                    <input type="text" name="city" id="city" required>
                </div>
            </div>

            <div class="form-actions form-actions-centered">
                <button type="submit" class="btn btn-primary">Envoyer une demande de devis</button>
            </div>
        </form>
    </div>
<?php endif; ?>