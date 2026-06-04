<?php
# cette classe va servir a faire une relation entre le php et la base de données plus particulièrement la table "questions".
class QuestionManager extends AbstractManager{
    public function __construct(){
        parent::__construct();
    }

    public function findAll() : array # pour trouver toutes les questions
    {
        $query = $this->db->prepare('SELECT questions.*, themes.id AS theme_id, themes.name AS theme_name FROM questions INNER JOIN themes ON questions.theme_id = themes.id ORDER BY RAND()');
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $questions = [];

        foreach($result as $item)
        {
            $theme = new Theme($item["theme_name"], $item["theme_id"]);
            $question = new Question($item["statement"], $item["explication"], $theme, $item["id"]);
            $questions[] = $question;
        }

        return $questions;
    }

    public function findById(int $id) : ?Question # pour trouver une question en particulier par son Id
    {
        $query = $this->db->prepare('SELECT questions.*, themes.id AS theme_id, themes.name AS theme_name FROM questions INNER JOIN themes ON questions.theme_id = themes.id WHERE questions.id = :id ');
        $parameters = [
            "id" => $id
        ];
        $query->execute($parameters);
        $item = $query->fetch(PDO::FETCH_ASSOC);

        if($item)
        {
            $theme = new Theme($item["theme_name"], $item["theme_id"]);
            return new Question($item["statement"], $item["explication"], $theme, $item["id"]);
        }

        return null;
    }

    public function findByThemeId(int $id) : array # pour trouver les questions par thème
    {
        $query = $this->db->prepare('SELECT questions.*, themes.id AS theme_id, themes.name AS theme_name FROM questions INNER JOIN themes ON questions.theme_id = themes.id WHERE themes.id = :id ORDER BY RAND() LIMIT 12 '); //ORDER BY RAND() mélange les lignes
        $parameters = [
            "id" => $id
        ];
        $query->execute($parameters);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $questions = [];

        foreach($result as $item)
        {
            $theme = new Theme($item["theme_name"], $item["theme_id"]);
            $question = new Question($item["statement"], $item["explication"], $theme, $item["id"]);
            $questions[] = $question;
        }

        return $questions;
    }

    public function find12Random() : array 
    {
        $query = $this->db->prepare('SELECT questions.*, themes.id AS theme_id, themes.name AS theme_name FROM questions INNER JOIN themes ON questions.theme_id = themes.id ORDER BY RAND() LIMIT 12 '); //ORDER BY RAND() mélange les lignes
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $questions = [];

        foreach($result as $item)
        {
            $theme = new Theme($item["theme_name"], $item["theme_id"]);
            $question = new Question($item["statement"], $item["explication"], $theme, $item["id"]);
            $questions[] = $question;
        }

        return $questions;
    }

    public function count(): int
    {
        $query = $this->db->prepare('SELECT COUNT(id) FROM questions');
        $query->execute();   
        return (int) $query->fetchColumn(); // Grace a fetchColumn on obtient un tableau simple au lieu d'un tableau associatif
    }

    public function create(Question $question) : void # pour ajouter une question en bdd
    {
        $query = $this->db->prepare('INSERT INTO questions (statement, explication, theme_id) VALUES (:statement, :explication, :theme_id)');
        $parameters = [
            "statement" => $question->getStatement(),
            "explication" => $question->getExplication(),
            "theme_id" => $question->getTheme()->getId()
        ];
        $query->execute($parameters);
        $question->setId((int)$this->db->lastInsertId());
    }

    public function update(Question $question) : void # pour modifier une question dans la bdd
    {
        $query = $this->db->prepare('UPDATE questions SET statement = :statement, explication = :explication, theme_id = :theme_id WHERE id = :id');
        $parameters = [
            "statement" => $question->getStatement(),
            "explication" => $question->getExplication(),
            "theme_id" => $question->getTheme()->getId(),
            "id" => $question->getId()
        ];
        $query->execute($parameters);
    }

    public function delete(Question $question) : void # pour supprimer une question dans la bdd
    {
        $query = $this->db->prepare('DELETE FROM questions WHERE id = :id');
        $parameters = [
            "id" => $question->getId()
        ];
        $query->execute($parameters);
    }
}