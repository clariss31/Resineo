<?php

class UserManager extends AbstractEntityManager
{
    /**
     * Récupère un utilisateur par son email.
     * @param string $email
     * @return User|null
     */
    public function getByEmail(string $email): ?User
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $result = $this->db->query($sql, ['email' => $email]);
        $user = $result->fetch();

        if ($user) {
            return new User($user);
        }
        return null;
    }

    /**
     * Crée un nouvel utilisateur.
     * @param User $user
     * @return void
     */
    public function create(User $user): void
    {
        $sql = "INSERT INTO users (email, password, firstname, lastname, role, image, created_at) VALUES (:email, :password, :firstname, :lastname, :role, :image, NOW())";
        $this->db->query($sql, [
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'role' => $user->getRole(),
            'image' => $user->getImage()
        ]);
    }

    /**
     * Met à jour les informations d'un utilisateur.
     * @param User $user
     * @return void
     */
    public function update(User $user): void
    {
        $sql = "UPDATE users SET email = :email, password = :password, firstname = :firstname, lastname = :lastname, image = :image WHERE id = :id";
        $this->db->query($sql, [
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'image' => $user->getImage(),
            'id' => $user->getId()
        ]);
    }
}
