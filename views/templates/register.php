<div class="auth-container">
    <h1>S'inscrire</h1>
    <form action="index.php?action=register" method="post" class="login-form">
        <div class="form-row">
            <div class="form-group">
                <label for="firstname">Prénom<span class="required">*</span></label>
                <input type="text" name="firstname" id="firstname" required>
            </div>
            <div class="form-group">
                <label for="lastname">Nom<span class="required">*</span></label>
                <input type="text" name="lastname" id="lastname" required>
            </div>
        </div>
        <div class="form-group">
            <label for="email">Email<span class="required">*</span></label>
            <input type="email" name="email" id="email" required>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe<span class="required">*</span></label>
            <input type="password" name="password" id="password" required>
        </div>
        <div class="form-group">
            <label for="confirmPassword">Confirmez votre mot de passe<span class="required">*</span></label>
            <input type="password" name="confirmPassword" id="confirmPassword" required>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">S'inscrire</button>
            <p class="register-link">Déjà inscrit ? <a href="index.php?action=loginForm">Connectez-vous !</a></p>
        </div>
    </form>
</div>