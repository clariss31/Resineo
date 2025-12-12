<div class="page-header header-bg-account">
    <div class="header-content">
        <h1>Mon compte</h1>
        <div class="breadcrumb">
            <a href="index.php?action=home">Accueil</a> > Mon compte
        </div>
    </div>
</div>

<div class="account-container">
    <div class="account-sidebar">
        <div class="user-summary">
            <?php
            $avatar = $user->getImage() ? "img/" . $user->getImage() : "img/avatar-default.png";
            ?>
            <img src="<?= $avatar ?>" alt="Avatar" class="avatar">
            <a href="index.php?action=disconnectUser" class="logout-link">Déconnexion</a>
        </div>
        <nav class="account-nav">
            <a href="index.php?action=showAccount" class="account-nav-item">
                <img src="img/avatar-default.png" alt="Infos">
                Informations
            </a>
            <a href="index.php?action=showMessaging" class="account-nav-item active">
                <img src="img/icone-messagerie-active.png" alt="Messagerie">
                Messagerie
            </a>
        </nav>
    </div>

    <div class="account-content">
        <!-- Empty as requested -->
    </div>
</div>