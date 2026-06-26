<?php

class AdminController extends AbstractController{


    public function checkAccess(){

        if (!isset($_SESSION["role"]) || $_SESSION["role"] !== 'ADMIN') {
            $this->redirect('index.php?route=home');
            exit;
        }
        
    }
    public function questionDisplay(){

        $this->checkAccess();

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

        $this->checkAccess();

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

        $this->checkAccess();

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

        $this->checkAccess();

        $userManager = new UserManager();
        $users = $userManager->findAll();

        $this->render('admin/usersDisplay.phtml', ["users" => $users]);

    }

    public function deleteUser(){

        $this->checkAccess();

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

    public function statsDisplay(){

            $this->checkAccess();

            $scoreManager = new ScoreManager();
            $countScore = $scoreManager->count();
            $mostPlayers = $scoreManager->mostPlayed();

            $userManager = new UserManager();
            $countUser = $userManager->count();

            $questionManager = new QuestionManager();
            $countQuestion = $questionManager->count();

            $themeManager = new ThemeManager();
            $countTheme = $themeManager->count();

            $this->render('admin/statsDisplay.phtml', [
                "countScore" => $countScore,
                "mostPlayers" => $mostPlayers,
                "countUser" => $countUser,
                "countQuestion" => $countQuestion,
                "countTheme" => $countTheme
            ]);

    }

    public function themesDisplay(){

        $this->checkAccess();

        $themeManager = new ThemeManager();
        $themes = $themeManager->findAll();
        $countTheme = $themeManager->count();

        $questionManager = new QuestionManager();
        $totalByTheme = $questionManager->countByThemeId();


        $this->render('admin/themesEdit.phtml', [
            "themes" => $themes,
            "totalByTheme" => $totalByTheme,
            "countTheme" => $countTheme
        ]);
    }

    public function addTheme(){

        $this->checkAccess();

        $error = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {
            if(empty($_POST["theme"])){
                $error = "Veuillez donner au thème que vous souhaitez créer";
            }

            $themeManager = new ThemeManager();
            $themes = $themeManager->findAll();

            foreach($themes as $theme){
                if(strtolower($_POST["theme"]) === strtolower($theme->getName())){
                    $error[] = "Ce thème est deja existant";
                    break;
                }
            }
            if(empty($error)){
                $theme = new Theme($_POST["theme"],null);
                $themeManager->create($theme);

                $this->redirect('index.php?route=themes-edit');
                exit;
            }
           
        }

        $this->render('admin/themesAdd.phtml',["errors" => $error]);

    }

    public function deleteTheme(){

        $this->checkAccess();
        $error = [];

        $themeId = (int)$_GET["id"];
        $questionManager = new QuestionManager();
        $countQuestion = $questionManager->countQuestionByTheme($themeId);

        if($countQuestion!=0)
        {
            $themeManager = new ThemeManager();
            $themes = $themeManager->findAll();
            $totalByTheme = $questionManager->countByThemeId();
            $countTheme = count($themes);
            $error[] = "Un thème ne peut être supprimé si des questions y sont encore associées.";
            $this->render('admin/themesEdit.phtml', [
                "errors" => $error,
                "themes" => $themes,
                "totalByTheme" => $totalByTheme,
                "countTheme" => $countTheme
            ]);
            exit;
        }

        $themeManager = new ThemeManager();
        $theme = $themeManager->findById($themeId);
        if($theme)
        {
            $themeManager->delete($theme);
        }
        

        $this->redirect('index.php?route=themes-edit');

    }
    

} 