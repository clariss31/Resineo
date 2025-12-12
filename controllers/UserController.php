<?php

class UserController
{
    /**
     * Affiche la page "Mon compte" avec les informations de l'utilisateur.
     * Redirige vers la connexion si l'utilisateur n'est pas connecté.
     * @return void
     */
    public function showAccount()
    {
        // Si l'utilisateur n'est pas connecté, on le redirige vers la page de connexion
        if (!isset($_SESSION['user'])) {
            Utils::redirect("loginForm");
        }

        // Affiche la page "Mon compte"
        $view = new View("Mon Compte");
        $view->render("account", ['user' => $_SESSION['user']]);
    }

    /**
     * Met à jour les informations du compte utilisateur.
     * Gère la modification du profil, de l'avatar et du mot de passe.
     * @return void
     */
    public function updateAccount()
    {
        if (!isset($_SESSION['user'])) {
            Utils::redirect("loginForm");
        }

        $firstname = Utils::request("firstname");
        $lastname = Utils::request("lastname");
        $email = Utils::request("email");
        $password = Utils::request("password");

        $userManager = new UserManager();
        $user = $_SESSION['user'];

        // Gère le téléchargement de l'avatar
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['avatar']['tmp_name'];
            $fileName = $_FILES['avatar']['name'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg');
            if (in_array($fileExtension, $allowedfileExtensions)) {
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $uploadFileDir = 'img/';
                $dest_path = $uploadFileDir . $newFileName;

                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    $user->setImage('img/' . $newFileName);
                }
            }
        }

        // Mise à jour des champs
        if ($firstname)
            $user->setFirstname($firstname);
        if ($lastname)
            $user->setLastname($lastname);
        if ($email)
            $user->setEmail($email);

        // Mise à jour du mot de passe uniquement s'il est fourni
        if (!empty($password)) {
            $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
        }

        // Sauvegarde en base de données
        $userManager->update($user);

        // Mise à jour de la session
        $_SESSION['user'] = $user;
        $_SESSION['flash'] = "Informations mises à jour avec succès.";

        Utils::redirect("showAccount");
    }

    /**
     * Affiche la messagerie de l'utilisateur.
     * @return void
     */
    public function showMessaging()
    {
        if (!isset($_SESSION['user'])) {
            Utils::redirect("loginForm");
        }

        // Affiche la messagerie
        $view = new View("Messagerie");
        $view->render("messaging", ['user' => $_SESSION['user']]);
    }
}
