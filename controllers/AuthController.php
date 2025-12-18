<?php

class AuthController
{
    /**
     * Affiche le formulaire d'inscription.
     * @return void
     */
    public function showRegister()
    {
        $view = new View("Inscription");
        $view->render("register");
    }

    /**
     * Traite l'inscription d'un nouvel utilisateur.
     * Vérifie les données, hash le mot de passe et crée l'utilisateur.
     * @return void
     * @throws Exception Si les champs sont vides ou si l'email existe déjà.
     */
    public function register()
    {
        // On récupère les données du formulaire
        $email = Utils::request("email");
        $password = Utils::request("password");
        $confirmPassword = Utils::request("confirmPassword");
        $firstname = Utils::request("firstname");
        $lastname = Utils::request("lastname");

        // Vérification basique (à améliorer)
        if (empty($email) || empty($password) || empty($firstname) || empty($lastname)) {
            $_SESSION['flash'] = "Tous les champs sont obligatoires.";
            Utils::redirect("registerForm");
        }

        if ($password !== $confirmPassword) {
            $_SESSION['flash'] = "Les mots de passe ne correspondent pas.";
            Utils::redirect("registerForm");
        }

        // On vérifie si l'email existe déjà
        $userManager = new UserManager();
        $existingUser = $userManager->getByEmail($email);
        if ($existingUser) {
            $_SESSION['flash'] = "Cet email est déjà utilisé. Veuillez vous connecter ou utiliser une autre adresse.";
            Utils::redirect("registerForm");
        }

        // On hash le mot de passe
        $pass_hash = password_hash($password, PASSWORD_DEFAULT);

        // On crée l'utilisateur
        $user = new User([
            'email' => $email,
            'password' => $pass_hash,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'role' => 'client',
            'image' => 'img/avatar-default.png'
        ]);

        $userManager->create($user);

        // On redirige vers la connexion
        Utils::redirect("loginForm");
    }

    /**
     * Affiche le formulaire de connexion.
     * @return void
     */
    public function showLogin()
    {
        $view = new View("Connexion");
        $view->render("login");
    }

    /**
     * Traite la connexion de l'utilisateur.
     * Vérifie les identifiants et initialise la session.
     * @return void
     * @throws Exception Si les identifiants sont incorrects.
     */
    public function login()
    {
        $email = Utils::request("email");
        $password = Utils::request("password");

        if (empty($email) || empty($password)) {
            $_SESSION['flash'] = "Tous les champs sont obligatoires.";
            Utils::redirect("loginForm");
        }

        $userManager = new UserManager();
        $user = $userManager->getByEmail($email);

        if (!$user) {
            $_SESSION['flash'] = "Utilisateur inconnu.";
            Utils::redirect("loginForm");
        }

        if (password_verify($password, $user->getPassword())) {
            // Connexion réussie
            $_SESSION['user'] = $user;
            Utils::redirect("home");
        } else {
            $_SESSION['flash'] = "Mot de passe incorrect.";
            Utils::redirect("loginForm");
        }
    }

    /**
     * Déconnecte l'utilisateur et détruit la session.
     * @return void
     */
    public function logout()
    {
        unset($_SESSION['user']);
        session_destroy();
        Utils::redirect("loginForm");
    }
}
