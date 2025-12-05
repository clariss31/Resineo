<div class="auth-container">
    <h2>Connexion</h2>
    <form action="index.php?action=connectUser" method="post">
        <div class="form-group">
            <label for="email">Email :</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe :</label>
            <input type="password" name="password" id="password" required>
        </div>
        <button type="submit" class="btn">Se connecter</button>
    </form>
    <p>Pas encore de compte ? <a href="index.php?action=registerForm">Inscrivez-vous !</a></p>
</div>