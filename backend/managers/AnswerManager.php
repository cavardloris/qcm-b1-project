<?php
# cette classe va servir a faire une relation entre le php et la base de données plus particulièrement la table "answers".
class AnswerManager extends AbstractManager{
    public function __construct(){
        parent::__construct();
    }

    public function findAll() : array # pour trouver toutes les réponses
    {
        $query = $this->db->prepare('SELECT questions.*, themes.id AS theme_id, themes.name AS theme_name,
                                         answers.id AS answer_id, answers.answer AS answer_answer, answers.is_correct AS answer_is_correct, answers.question_id AS answer_question_id 
                                         FROM answers INNER JOIN questions ON answers.question_id = questions.id INNER JOIN themes ON questions.theme_id = themes.id ');
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $answers = [];

        foreach($result as $item)
        {
            $theme = new Theme($item["theme_name"], $item["theme_id"]);
            $question = new Question($item["statement"], $item["explication"], $theme, $item["id"]);
            $answer = new Answer($item["answer_answer"], $item["answer_is_correct"], $question, $item["answer_id"]);
            $answers[] = $answer;
        }

        return $answers;
    }

    public function findById(int $id) : ?Answer # pour trouver une réponse en particulier par son Id
    {
        $query = $this->db->prepare('SELECT questions.*, themes.id AS theme_id, themes.name AS theme_name,
                                         answers.id AS answer_id, answers.answer AS answer_answer, answers.is_correct AS answer_is_correct, answers.question_id AS answer_question_id 
                                         FROM answers INNER JOIN questions ON answers.question_id = questions.id INNER JOIN themes ON questions.theme_id = themes.id WHERE answers.id = :id');
        $parameters = [
            "id" => $id
        ];
        $query->execute($parameters);
        $item = $query->fetch(PDO::FETCH_ASSOC);

        if($item)
        {
            $theme = new Theme($item["theme_name"], $item["theme_id"]);
            $question = new Question($item["statement"], $item["explication"], $theme, $item["id"]);
            return new Answer($item["answer_answer"], $item["answer_is_correct"], $question, $item["answer_id"]);
        }

        return null;
    }

    public function findByQuestionId(int $id) : array # pour trouver les réponses correspondantes à une question en particulier
    {
        $query = $this->db->prepare('SELECT questions.*, themes.id AS theme_id, themes.name AS theme_name,
                                         answers.id AS answer_id, answers.answer AS answer_answer, answers.is_correct AS answer_is_correct, answers.question_id AS answer_question_id 
                                         FROM answers INNER JOIN questions ON answers.question_id = questions.id INNER JOIN themes ON questions.theme_id = themes.id WHERE answers.question_id = :id');
        $parameters = [
            "id" => $id
        ];
        $query->execute($parameters);
        $result= $query->fetchAll(PDO::FETCH_ASSOC);
        $answers = [];

        foreach($result as $item)
        {
            $theme = new Theme($item["theme_name"], $item["theme_id"]);
            $question = new Question($item["statement"], $item["explication"], $theme, $item["id"]);
            $answer = new Answer($item["answer_answer"], $item["answer_is_correct"], $question, $item["answer_id"]);
            $answers[] = $answer;
        }

        return $answers;
    }

    public function findCorrect() : Answer # Pour trouver la bonne réponse à la question
    {
        $query = $this->db->prepare('SELECT questions.*, themes.id AS theme_id, themes.name AS theme_name,
                                         answers.id AS answer_id, answers.answer AS answer_answer, answers.is_correct AS answer_is_correct, answers.question_id AS answer_question_id 
                                         FROM answers INNER JOIN questions ON answers.question_id = questions.id INNER JOIN themes ON questions.theme_id = themes.id WHERE answer.question_id = :id AND answers.is_correct = 1');
        $parameters = [
            "id" => $id
        ];
        $query->execute($parameters);
        $item = $query->fetch(PDO::FETCH_ASSOC);

        if($item)
        {
            $theme = new Theme($item["theme_name"], $item["theme_id"]);
            $question = new Question($item["statement"], $item["explication"], $theme, $item["id"]);
            return new Answer($item["answer_answer"], $item["answer_is_correct"], $question, $item["answer_id"]);
        }

        return null;
    }

    public function create(Answer $answer) : void # pour ajouter une réponse dans la bdd
    {
        $query = $this->db->prepare('INSERT INTO answers (answer, is_correct, question_id) VALUES (:answer, :is_correct, :question_id)');
        $parameters = [
            "answer" => $answer->getAnswer(),
            "is_correct" => $answer->getIsCorrect(),
            "question_id" => $answer->getQuestion()->getId()
        ];
        $query->execute($parameters);
        $answer->setId((int)$this->db->lastInsertId());
    }

    public function update(Answer $answer) : void # pour changer une réponse dans la bdd
    {
        $query = $this->db->prepare('UPDATE answers SET answer = :answer, is_correct = :is_correct, question_id = :question_id WHERE id = :id');
        $parameters = [
            "answer" => $answer->getAnswer(),
            "is_correct" => $answer->getIsCorrect(),
            "question_id" => $answer->getQuestion()->getId(),
            "id" => $answer->getId()
        ];
        $query->execute($parameters);
    }

    public function delete(Answer $answer) : void # pour supprimer une réponse dans la bdd
    {
        $query = $this->db->prepare('DELETE FROM answers WHERE id = :id');
        $parameters = [
            "id" => $answer->getId()
        ];
        $query->execute($parameters);
    }
}