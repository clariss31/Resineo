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
    <header>
        <img src="img/logo.png" alt="Logo" class="logo">
        <nav>
            <a href="index.php?action=catalogue">Catalogue</a>
            <a href="index.php?action=resines">Resines</a>
            <a href="index.php?action=entretien">Entretien</a>
            <a href="index.php?action=outillage">Outillage</a>
            <a href="index.php?action=compte"><img src="img/icone-compte.png" alt="Mon compte" class="nav-icon"></a>
            <a href="index.php?action=devis"><img src="img/icone-devis.png" alt="Mon devis" class="nav-icon"></a>
        </nav>
        <h1><?= $title ?></h1>
    </header>

    <main>
        <?= $content /* Ici est affiché le contenu de la page. */ ?>
    </main>

    <footer>
        <p>Copyright © Resineo 2025 - Pichinov</p>
    </footer>

</body>

</html>