<?php

class AdminController extends AbstractController{
    public function questionDisplay(){
        $questionManager = new QuestionManager();
        $questions = $questionManager->findAll();
        $questionsCount = $questionManager->countByThemeId();
        $totalQuestions = $questionManager->count();

        $themeManager = new ThemeManager();
        $themes = $themeManager->findAll();

        $this->render('admin/questionsEdit.phtml', [
            "questions" => $questions,
            "themes" => $themes,
            "questionsCount" => $questionsCount,
            "totalQuestions" => $totalQuestions
        ]);
    }

    public function deleteQuestion(){
        if(!empty($_GET["id"])){
            $id = (int) $_GET["id"];
        }

        $questionManager = new QuestionManager();
        $question = $questionManager->findById($id);

        if($question){
            $answerManager = new AnswerManager();
            $answerManager->deleteByQuestionId($question->getId());

            $questionManager->delete($question);
        }
        $this->redirect('index.php?route=questions-edit');
    }

    public function addQuestion(){
        $error = [];
        $themeManager = new ThemeManager();
        $themes = $themeManager->findAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (empty($_POST['statement']) || empty($_POST['theme_id']) || empty($_POST['answer_1']) || empty($_POST['answer_2']) || empty($_POST['answer_3']) || empty($_POST['answer_4'])){
                $error[] = "Veuillez remplir tous les champs";
                $this->render('admin/questionsAdd.phtml', [
                    "errors" => $error,
                    "themes" => $themes
                ]);
                return;
            }

            $statement = $_POST["statement"];
            $themeId = (int) $_POST["theme_id"];
            $correctAnswerIndex = (int) $_POST["correct_answer"];

            $theme = $themeManager->findById($themeId);
            $question = new Question($statement, null, $theme, null);

            $questionManager = new QuestionManager();
            $questionManager->create($question);

            $answerManager = new AnswerManager();
            for ($i = 1; $i <= 4; $i++) {
                $isCorrect = ($i === $correctAnswerIndex); 
                $answerText = $_POST['answer_' . $i];
    
                $answer = new Answer($answerText, $isCorrect, $question, null);
                $answerManager->create($answer);
            }

            $this->redirect('index.php?route=questions-edit');
            exit;
        }

        $this->render('admin/questionsAdd.phtml', [
            "themes" => $themes
        ]);
    }

    public function displayUsers(){
        $userManager = new UserManager();
        $users = $userManager->findAll();

        

        $this->render('admin/usersDisplay.phtml', ["users" => $users]);

    }

    public function deleteUser(){
        
        if (!isset($_GET["id"]) || empty($_GET["id"])) {
            $this->redirect('index.php?route=users-edit');
            return;
        }

        $userManager = new UserManager();
        $user = $userManager->findById($_GET["id"]);

        if($user){
            $scoreManager = new ScoreManager();
            $userScores = $scoreManager->findByUserId($_GET["id"]);
            foreach($userScores as $userScore){
                $scoreManager->delete($userScore);
            }
            $userManager->delete($user);
        }
        

        $this->redirect('index.php?route=users-edit');
    }
} 