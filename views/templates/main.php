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
    <!-- En-tête principal -->
    <header class="main-header">
        <div class="header-left">
            <a href="index.php?action=home">
                <img src="img/logo.png" alt="Resineo Logo" class="logo">
            </a>
        </div>

        <div class="header-search">
            <form action="index.php" method="GET" class="search-form">
                <input type="hidden" name="action" value="search">
                <input type="text" name="search" placeholder="Rechercher un produit" aria-label="Rechercher un produit">
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
            <?php if (!isset($_SESSION['user']) || $_SESSION['user']->getRole() !== 'admin'): ?>
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
            <?php endif; ?>
        </div>
    </header>

    <!-- Contenu principal -->
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

    <!-- Pied de page global -->
    <footer class="global-footer">
        <img src="img/resines.png" alt="Ambiance Résines" class="footer-top-image">

        <div class="applicator-banner">
            <p class="applicator-text applicator-text-nomargin">Vous souhaitez devenir applicateur ? Rejoignez notre
                réseau !
            </p>
            <a href="index.php?action=messagerie&prefill=Bonjour%2C%20je%20voudrais%20devenir%20applicateur"
                class="btn btn-white">Devenir applicateur</a>
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
                <p class="footer-copyright-text">Copyright © 2025 - Site web créé par Pichinov.</p>

                <div class="social-links">
                    <a href="https://www.facebook.com/resineo.fr/?locale=fr_FR" target="_blank"><img
                            src="img/facebook.png" alt="Facebook"></a>
                    <a href="https://www.instagram.com/resineo.fr/?hl=fr" target="_blank"><img src="img/instagram.png"
                            alt="Instagram"></a>
                    <a href="https://www.linkedin.com/company/r%C3%A9sineo/?viewAsMember=true" target="_blank"><img
                            src="img/linkedin.png" alt="LinkedIn"></a>
                    <a href="https://www.youtube.com/channel/UCerY6ufm0D24UDzZZOY09uQ" target="_blank"><img
                            src="img/youtube.png" alt="YouTube"></a>
                </div>

                <div class="legal-links">
                    <a href="index.php?action=politiqueConfidentialite">Politique de confidentialité</a>
                    <a href="index.php?action=mentionsLegales">Mentions légales</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="js/utils.js"></script>
    <script src="js/quote.js"></script>
</body>

</html>