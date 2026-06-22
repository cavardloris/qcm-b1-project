<?php
# cette classe va servir a faire une relation entre le php et la base de données plus particulièrement la table "themes".
 class ThemeManager extends AbstractManager{
    public function __construct(){
        parent::__construct();
    }

    public function findAll() : array # pour trouver tous les themes
    {
        $query = $this->db->prepare('SELECT * FROM themes');
        $parameters = [

        ];
        $query->execute($parameters);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $themes = [];

        foreach($result as $item)
        {
            $theme = new Theme($item["name"], $item["id"]);
            $themes[] = $theme;
        }

        return $themes;
    }

    public function findById(int $id) : ? Theme # pour trouver un thème en particulier par son id
    {
        $query = $this->db->prepare('SELECT * FROM themes WHERE id = :id');
        $parameters = [
            "id" => $id
        ];
        $query->execute($parameters);
        $item = $query->fetch(PDO::FETCH_ASSOC);

        if($item)
        {
            return new Theme($item["name"], $item["id"]);
        }

        return null;
    }

    public function findByName(string $name) : ? Theme # pour trouver un thème par son nom
    {
        $query = $this->db->prepare('SELECT * FROM themes WHERE name = :name');
        $parameters = [
            "name" => $name
        ];
        $query->execute($parameters);
        $item = $query->fetch(PDO::FETCH_ASSOC);

        if($item)
        {
            return new Theme($item["name"], $item["id"]);
        }

        return null;
    }

    public function count():int
    {
        $query = $this->db->prepare('SELECT COUNT(id) FROM themes');
            $query->execute();   
            return (int) $query->fetchColumn(); 
    }

    public function create(Theme $theme) : void # pour ajouter un nouveau thème dans la bdd
    {
        $query = $this->db->prepare('INSERT INTO themes (name) VALUES (:name)');
        $parameters = [
            "name" => $theme->getName()
        ];
        $query->execute($parameters);
        $theme->setId($this->db->lastInsertId());
    }

    public function update(Theme $theme) : void # pour modifier un thème dans la bdd
    {
        $query = $this->db->prepare('UPDATE themes SET name = :name WHERE id = :id');
        $parameters = [
            "id" => $theme->getId(),
            "name" => $theme->getName()
        ];
        $query->execute($parameters);
    }

    public function delete(Theme $theme) : void # pour supprimer un thème dans la bdd 
    {
        $query = $this->db->prepare('DELETE FROM themes WHERE id = :id');
        $parameters = [
            "id" => $theme->getId()
        ];
        $query->execute($parameters);
    }
 }