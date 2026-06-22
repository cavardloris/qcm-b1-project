<?php
# cette classe va servir a faire une relation entre le php et la base de données plus particulièrement la table "users".
class UserManager extends AbstractManager{
    public function __construct(){
        parent::__construct();
    }

    public function findAll() : array #cette fonction permet de trouver un utilisateur par son email dans la bdd
    {
        $query = $this->db->prepare('SELECT * FROM users');
        $parameters = [

        ];
        $query->execute($parameters);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $users = [];

        foreach($result as $item)
        {
            $user = new User($item["firstName"], $item["lastName"], $item["pseudo"], $item["email"], $item["password"], Role::from($item["role"]), $item["id"]);
            $users[] = $user;
        }

        return $users;
    }

    public function findById(int $id) : ? User #cette fonction permet de trouver un utilisateur et ses informations par son id dans la bdd
    {
        $query = $this->db->prepare('SELECT * FROM users WHERE id = :id');
        $parameters = [
            "id" => $id
        ];
        $query->execute($parameters);
        $item = $query->fetch(PDO::FETCH_ASSOC);

        if($item)
        {
            return new User($item["firstName"], $item["lastName"], $item["pseudo"], $item["email"], $item["password"], Role::from($item["role"]), $item["id"]);
        }

        return null;
    }

    public function findByPseudo(string $pseudo) : ? User #cette fonction permet de trouver un utilisateur et ses informations par son pseudo dans la bdd
    {
        $query = $this->db->prepare('SELECT * FROM users WHERE pseudo = :pseudo');
        $parameters = [
            "pseudo" => $pseudo
        ];
        $query->execute($parameters);
        $item = $query->fetch(PDO::FETCH_ASSOC);

        if($item)
        {
            return new User($item["firstName"], $item["lastName"], $item["pseudo"], $item["email"], $item["password"], Role::from($item["role"]), $item["id"]);
        }

        return null;
    }

    public function findByEmail(string $email) : ? User #cette fonction permet de trouver un utilisateur et ses informations par son email dans la bdd
    {
        $query = $this->db->prepare('SELECT * FROM users WHERE email = :email');
        $parameters = [
            "email" => $email
        ];
        $query->execute($parameters);
        $item = $query->fetch(PDO::FETCH_ASSOC);

        if($item)
        {
           return new User($item["firstName"], $item["lastName"], $item["pseudo"], $item["email"], $item["password"], Role::from($item["role"]), $item["id"]); #On transforme la string SQL en objet Enum
        }

        return null;
    }

    public function count():int
    {
        $query = $this->db->prepare('SELECT COUNT(id) FROM users');
            $query->execute();   
            return (int) $query->fetchColumn(); 
    }

    public function create(User $user) : void # cette fonction va servir a créer un utilisateur dans la base de données
    {
        $query = $this->db->prepare('INSERT INTO users (firstName,lastName,pseudo,email,password,role) VALUES (:firstName, :lastName, :pseudo, :email, :password, :role)');
        $parameters = [
            "firstName" => $user->getFirstName(),
            "lastName" => $user->getLastName(),
            "pseudo" => $user->getPseudo(),
            "email" => $user->getEmail(),
            "password" => $user->getPassword(),
            "role" => $user->getRole()->value # comme c'est un enum on met ->value
        ];
        $query->execute($parameters);
        $user->setId($this->db->lastInsertId());
    }

    public function update(User $user) : void
    {
        $query = $this->db->prepare('UPDATE users SET firstName = :firstName, lastName = :lastName, pseudo = :pseudo, email = :email, password = :password, role = :role WHERE id = :id');
        $parameters = [
            "firstName" => $user->getFirstName(),
            "lastName" => $user->getLastName(),
            "pseudo" => $user->getPseudo(),
            "email" => $user->getEmail(),
            "password" => $user->getPassword(),
            "role" => $user->getRole()->value, # comme c'est un enum on met ->value
            "id" => $user->getId()
        ];
        $query->execute($parameters);
    }

    public function delete(User $user) : void
    {
        $query = $this->db->prepare('DELETE FROM users WHERE id = :id');
        $parameters = [
            "id" => $user->getId()
        ];
        $query->execute($parameters);
    }
}