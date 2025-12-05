<?php
/**
 * Ce fichier est le template principal qui "contient" ce qui aura été généré par les autres vues.  
 * 
 * Les variables qui doivent impérativement être définie sont : 
 *      $title string : le titre de la page.
 *      $content string : le contenu de la page. 
 */

$cssFile = __DIR__ . '/../../css/style.css';
$timestamp = filemtime($cssFile);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résineo</title>
    <link rel="stylesheet" href="./css/style.css?v=<?= $timestamp ?>">
</head>

<body>
    <header class="main-header">
        <div class="header-left">
            <a href="index.php?action=home">
                <img src="img/logo.png" alt="Resineo Logo" class="logo">
            </a>
        </div>

        <div class="header-search">
            <form action="index.php" method="GET" class="search-form">
                <input type="hidden" name="action" value="catalogue">
                <input type="text" name="search" placeholder="Rechercher un produit">
                <button type="submit">
                    <img src="img/loupe.png" alt="Rechercher">
                </button>
            </form>
        </div>

        <nav class="header-nav">
            <a href="index.php?action=catalogue">Catalogue</a>
            <a href="index.php?action=resines">Résines</a>
            <a href="index.php?action=entretien">Entretien</a>
            <a href="index.php?action=outillage">Outillage</a>
        </nav>

        <div class="header-actions">
            <a href="index.php?action=compte">
                <img src="img/icone-compte.png" alt="Mon compte">
            </a>
            <a href="index.php?action=devis" class="header-icon-link">
                <img src="img/icone-devis.png" alt="Mon panier">
                <?php
                $quoteCount = 0;
                if (isset($_SESSION['quote'])) {
                    $quoteCount = array_sum($_SESSION['quote']);
                }
                if ($quoteCount > 0): ?>
                    <span class="quote-badge"><?= $quoteCount ?></span>
                <?php endif; ?>
            </a>
        </div>
    </header>

    <main>
        <?php if (isset($_SESSION['flash'])): ?>
            <div class="flash-message" id="flash-message">
                <?= $_SESSION['flash'] ?>
            </div>
            <?php unset($_SESSION['flash']); ?>
            <script>
                setTimeout(function () {
                    const flash = document.getElementById('flash-message');
                    if (flash) {
                        flash.style.opacity = '0';
                        setTimeout(() => flash.remove(), 500); // Wait for fade out
                    }
                }, 3000); // 3 seconds
            </script>
        <?php endif; ?>
        <?= $content /* Ici est affiché le contenu de la page. */ ?>
    </main>

    <footer class="global-footer">
        <img src="img/resines.png" alt="Ambiance Résines" class="footer-top-image">

        <div class="applicator-banner">
            <p style="font-size: 1.5rem; margin: 0;">Vous souhaitez devenir applicateur ? Rejoignez notre réseau !</p>
            <a href="#" class="btn btn-white">Devenir applicateur</a>
        </div>

        <div class="main-footer">
            <div class="footer-content">
                <div class="footer-logo">
                    <img src="img/logo.png" alt="Resineo Logo">
                </div>

                <div class="footer-contact">
                    <div class="contact-item">
                        <img src="img/telephone.png" alt="Téléphone">
                        <a href="tel:+330980405320">+33 (0)9 80 40 53 20</a>
                    </div>
                    <div class="contact-item">
                        <img src="img/mail.png" alt="Email">
                        <a href="mailto:contact@resineo.com">contact@resineo.com</a>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <p style="font-size: 1rem;">Copyright © 2025 - Site web créé par Pichinov.</p>

                <div class="social-links">
                    <a href="#"><img src="img/facebook.png" alt="Facebook"></a>
                    <a href="#"><img src="img/instagram.png" alt="Instagram"></a>
                    <a href="#"><img src="img/linkedin.png" alt="LinkedIn"></a>
                    <a href="#"><img src="img/youtube.png" alt="YouTube"></a>
                </div>

                <div class="legal-links">
                    <span>Politique de confidentialité</span>
                    <span>Mentions légales</span>
                </div>
            </div>
        </div>
    </footer>

    <script src="js/quote-actions.js"></script>
</body>

</html>