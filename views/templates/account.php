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
            // Check if file exists to be safe, or just trust DB? Default to avatar-default if null.
            // If absolute path logic isn't used, assume img/ relative.
            ?>
            <img src="<?= $avatar ?>" alt="Avatar" class="avatar" style="cursor: pointer;"
                onclick="document.getElementById('avatar-input').click();">
            <a href="index.php?action=disconnectUser" class="logout-link">Déconnexion</a>
        </div>
        <nav class="account-nav">
            <a href="index.php?action=showAccount" class="account-nav-item active">
                <img src="img/icone-infos.png" alt="Infos">
                Informations
            </a>
            <a href="index.php?action=showMessaging" class="account-nav-item">
                <img src="img/icone-messagerie.png" alt="Messagerie">
                Messagerie
            </a>
        </nav>
    </div>

    <div class="account-content">
        <div class="auth-container" style="margin: 0; max-width: 100%;">
            <?php if (isset($_SESSION['flash'])): ?>
                <div class="flash-message">
                    <?= $_SESSION['flash'];
                    unset($_SESSION['flash']); ?>
                </div>
            <?php endif; ?>

            <form action="index.php?action=updateAccount" method="post" class="account-form"
                enctype="multipart/form-data">
                <!-- Hidden file input triggered by avatar click -->
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

                <!-- Removed separate buttons, image doesn't show them clearly but functionality requires save. 
                     If image doesn't show, maybe auto-save? But clearly user wants "champs modifiables". 
                     I'll add a hidden submit or rely on enter, but better to have a button. 
                     Wait, the image cuts off the bottom? Or assumes simple fields. 
                     I'll add a standard save button for UX. -->
                <div class="form-actions" style="margin-top: 1rem;">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>