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
        <!-- User Summary Box -->
        <div class="user-summary">
            <div class="user-info">
                <?php $avatar = $user->getImage() ? $user->getImage() : "img/avatar-default.png"; ?>
                <img src="<?= $avatar ?>" alt="Avatar" class="avatar" style="cursor: pointer;"
                    onclick="document.getElementById('avatar-input').click();">
            </div>
            <a href="index.php?action=disconnectUser" class="logout-link">Déconnexion</a>
        </div>

        <!-- Navigation -->
        <nav class="account-nav">
            <a href="index.php?action=compte" class="account-nav-item active">
                <img src="img/icone-infos.png" alt="Infos">
                Informations
            </a>
            <a href="index.php?action=messagerie" class="account-nav-item">
                <img src="img/icone-messagerie.png" alt="Messagerie">
                Messagerie
            </a>
        </nav>
    </div>

    <div class="account-content">
        <?php if (isset($_SESSION['flash'])): ?>
            <div class="flash-message">
                <?= $_SESSION['flash'];
                unset($_SESSION['flash']); ?>
            </div>
        <?php endif; ?>

        <form action="index.php?action=updateAccount" method="post" class="account-form" enctype="multipart/form-data">
            <input type="file" name="avatar" id="avatar-input" style="display: none;" onchange="this.form.submit()">

            <div class="form-row">
                <div class="form-group">
                    <label for="firstname">Prénom</label>
                    <input type="text" name="firstname" id="firstname"
                        value="<?= htmlspecialchars($user->getFirstname()) ?>">
                </div>
                <div class="form-group">
                    <label for="lastname">Nom</label>
                    <input type="text" name="lastname" id="lastname"
                        value="<?= htmlspecialchars($user->getLastname()) ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($user->getEmail()) ?>">
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" placeholder="Laisser vide pour ne pas changer">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-dark">Enregistrer</button>
                <!-- Hidden submit for enter key -->
            </div>
        </form>
    </div>
</div>