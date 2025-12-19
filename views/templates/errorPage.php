<div class="error-page-container">
    <h1>Une erreur est survenue</h1>
    <p><?= isset($errorMessage) ? $errorMessage : "Désolé, une erreur inattendue s'est produite." ?></p>
    <a href="index.php?action=home" class="btn btn-dark">Revenir à l'accueil</a>
</div>