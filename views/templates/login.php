<div class="auth-container">
    <h2>Se connecter</h2>
    <form action="index.php?action=connectUser" method="post" class="login-form">
        <div class="form-group">
            <label for="email">Email<span class="required">*</span></label>
            <input type="email" name="email" id="email" required>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe<span class="required">*</span></label>
            <input type="password" name="password" id="password" required>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Connexion</button>
            <p class="register-link">Pas de compte ? <a href="index.php?action=registerForm">Inscrivez-vous !</a></p>
        </div>
    </form>
</div>