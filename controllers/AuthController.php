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
        $firstname = Utils::request("firstname");
        $lastname = Utils::request("lastname");

        // Vérification basique (à améliorer)
        if (empty($email) || empty($password) || empty($firstname) || empty($lastname)) {
            throw new Exception("Tous les champs sont obligatoires.");
        }

        // On vérifie si l'email existe déjà
        $userManager = new UserManager();
        $existingUser = $userManager->getByEmail($email);
        if ($existingUser) {
            throw new Exception("Cet email est déjà utilisé.");
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
            'image' => 'avatar-default.png'
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
            throw new Exception("Tous les champs sont obligatoires.");
        }

        $userManager = new UserManager();
        $user = $userManager->getByEmail($email);

        if (!$user) {
            throw new Exception("Utilisateur inconnu.");
        }

        if (password_verify($password, $user->getPassword())) {
            // Connexion réussie
            $_SESSION['user'] = $user;
            Utils::redirect("home");
        } else {
            throw new Exception("Mot de passe incorrect.");
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
