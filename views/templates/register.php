<div class="auth-container">
    <h2>Inscription</h2>
    <form action="index.php?action=register" method="post">
        <div class="form-group">
            <label for="firstname">Prénom :</label>
            <input type="text" name="firstname" id="firstname" required>
        </div>
        <div class="form-group">
            <label for="lastname">Nom :</label>
            <input type="text" name="lastname" id="lastname" required>
        </div>
        <div class="form-group">
            <label for="email">Email :</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe :</label>
            <input type="password" name="password" id="password" required>
        </div>
        <button type="submit" class="btn">S'inscrire</button>
    </form>
    <p>Vous avez déjà un compte ? <a href="index.php?action=loginForm">Connectez-vous !</a></p>
</div>