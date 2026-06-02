<?php
# cette classe va servir a faire une relation entre le php et la base de données plus particulièrement la table "scores".
class ScoreManager extends AbstractManager{
    public function __construct(){
        parent::__construct();
    }

    public function findAll() : array  #pour trouver tout les scores de la table
    {
        $query = $this->db->prepare('SELECT scores.*, themes.id AS theme_id, themes.name AS theme_name,
                                         users.id AS user_id, users.firstName AS user_firstName, users.lastName AS user_lastName, users.pseudo AS user_pseudo, users.email AS user_email, users.password AS user_password, users.role AS user_role  
                                         FROM users INNER JOIN scores ON users.id = scores.user_id INNER JOIN themes ON scores.theme_id = themes.id ');
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $scores = [];

        foreach($result as $item)
        {
            $theme = new Theme($item["theme_name"], $item["theme_id"]);
            $user = new User($item["user_firstName"], $item["user_lastName"], $item["user_pseudo"], $item["user_email"], $item["user_password"], Role::from($item["user_role"]), $item["user_id"]);
            $score = new Score($user, $theme,$item["points"], $item["serie_max"], $item["mode"], $item["score_date"]);
            $scores[] = $score;
        }

        return $scores;
    }

    public function findByThemeId(int $theme_id) : array #pour trouver tous les scores par theme
    {
        $query = $this->db->prepare('SELECT scores.*, themes.id AS theme_id, themes.name AS theme_name,
                                         users.id AS user_id, users.firstName AS user_firstName, users.lastName AS user_lastName, users.pseudo AS user_pseudo, users.email AS user_email, users.password AS user_password, users.role AS user_role  
                                         FROM users INNER JOIN scores ON users.id = scores.user_id INNER JOIN themes ON scores.theme_id = themes.id WHERE scores.theme_id = :theme_id ');
        $parameters = [
            "theme_id" => $theme_id
        ];
        $query->execute($parameters);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $scores = [];

        foreach($result as $item)
        {
            $theme = new Theme($item["theme_name"], $item["theme_id"]);
            $user = new User($item["user_firstName"], $item["user_lastName"], $item["user_pseudo"], $item["user_email"], $item["user_password"], Role::from($item["user_role"]), $item["user_id"]);
            $score = new Score($user, $theme,$item["points"], $item["serie_max"], $item["mode"], $item["score_date"]);
            $scores[] = $score;
        }

        return $scores;
    }

    public function findByUserId(int $user_id) : array # pour trouver tous les scores d'un joueur
    {
        $query = $this->db->prepare('SELECT scores.*, themes.id AS theme_id, themes.name AS theme_name,
                                         users.id AS user_id, users.firstName AS user_firstName, users.lastName AS user_lastName, users.pseudo AS user_pseudo, users.email AS user_email, users.password AS user_password, users.role AS user_role  
                                         FROM users INNER JOIN scores ON users.id = scores.user_id INNER JOIN themes ON scores.theme_id = themes.id WHERE scores.user_id = :user_id ');
        $parameters = [
            "user_id" => $user_id
        ];
        $query->execute($parameters);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $scores = [];

        foreach($result as $item)
        {
            $theme = new Theme($item["theme_name"], $item["theme_id"]);
            $user = new User($item["user_firstName"], $item["user_lastName"], $item["user_pseudo"], $item["user_email"], $item["user_password"], Role::from($item["user_role"]), $item["user_id"]);
            $score = new Score($user, $theme,$item["points"], $item["serie_max"], $item["mode"], $item["score_date"]);
            $scores[] = $score;
        }

        return $scores;
    }

    public function findById(int $id) : ?Score # pour trouver un score par son id
    {
        $query = $this->db->prepare('SELECT scores.*, themes.id AS theme_id, themes.name AS theme_name,
                                         users.id AS user_id, users.firstName AS user_firstName, users.lastName AS user_lastName, users.pseudo AS user_pseudo, users.email AS user_email, users.password AS user_password, users.role AS user_role  
                                         FROM users INNER JOIN scores ON users.id = scores.user_id INNER JOIN themes ON scores.theme_id = themes.id WHERE scores.id = :id');
        $parameters = [
            "id" => $id
        ];
        $query->execute($parameters);
        $item = $query->fetch(PDO::FETCH_ASSOC);

        if($item)
        {
            $theme = new Theme($item["theme_name"], $item["theme_id"]);
            $user = new User($item["user_firstName"], $item["user_lastName"], $item["user_pseudo"], $item["user_email"], $item["user_password"], Role::from($item["user_role"]), $item["user_id"]);
            return new Score($user, $theme,$item["points"], $item["serie_max"], $item["mode"], $item["score_date"]);
        }

        return null;
    }

     public function findByDate(DateTime $date) : array # pour trouver les scores d'une date précise
    {
        $query = $this->db->prepare('SELECT scores.*, themes.id AS theme_id, themes.name AS theme_name,
                                         users.id AS user_id, users.firstName AS user_firstName, users.lastName AS user_lastName, users.pseudo AS user_pseudo, users.email AS user_email, users.password AS user_password, users.role AS user_role  
                                         FROM users INNER JOIN scores ON users.id = scores.user_id INNER JOIN themes ON scores.theme_id = themes.id WHERE DATE(scores.score_date) = :date ');
        $parameters = [
            "date" => $date->format('Y-m-d')
        ];
        $query->execute($parameters);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $scores = [];

        foreach($result as $item)
        {
            $theme = new Theme($item["theme_name"], $item["theme_id"]);
            $user = new User($item["user_firstName"], $item["user_lastName"], $item["user_pseudo"], $item["user_email"], $item["user_password"], Role::from($item["user_role"]), $item["user_id"]);
            $score = new Score($user, $theme,$item["points"], $item["serie_max"], $item["mode"], $item["score_date"]);
            $scores[] = $score;
        }

        return $scores;
    }

    public function create(Score $score) : void # pour enregistrer un score dans la bdd
    {
        $query = $this->db->prepare('INSERT INTO scores (user_id, theme_id, points, serie_max, mode, score_date) VALUES (:user_id, :theme_id, :points, :serie_max, :mode, :score_date)');
        $parameters = [
            "user_id" => $score->getUser()->getId(),
            "theme_id" => $score->getTheme()->getId(),
            "points" => $score->getPoints(),
            "serie_max" => $score->getSerieMax(),
            "mode" => $score->getMode()->value,
            "score_date" => $score->getDate()->format('Y-m-m H:i:s'),
        ];
        $query->execute($parameters);
        $score->setId((int)$this->db->lastInsertId());
    }

    public function update(Score $score) : void # pour changer un score dans la bdd
    {
        $query = $this->db->prepare('UPDATE scores SET user_id = :user_id, theme_id = :theme_id, points = :points, serie_max = :serie_max, mode = :mode, score_date = :score_date  WHERE id = :id');
        $parameters = [
            "user_id" => $score->getUser()->getId(),
            "theme_id" => $score->getTheme()->getId(),
            "points" => $score->getPoints(),
            "serie_max" => $score->getSerieMax(),
            "mode" => $score->getMode(),
            "score_date" => $score->getScoreDate(),
            "id" => $score->getId()
        ];
        $query->execute($parameters);
    }

    public function delete(Score $score) : void # pour supprimer un score en bdd
    {
        $query = $this->db->prepare('DELETE FROM scores WHERE id = :id');
        $parameters = [
            "id" => $score->getId()
        ];
        $query->execute($parameters);
    }
    
}