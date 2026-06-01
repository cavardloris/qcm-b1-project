<?php

class ThemeController extends AbstractController{
    public function home(){
        if(!isset($_SESSION["id"]))
        {
            $this->redirect('index.php?route=login');
            exit;
        }
        $themeManager = new ThemeManager();
        $themes = $themeManager->findAll();
        $this->render('theme/themeHome.phtml', ['themes' => $themes]);
       
    }

    public function startQuiz(){
        if(!isset($_SESSION["id"])){
            $this->redirect('index.php?route=login');
            exit;
        }
        if (!isset($_GET['themeId'])) {
            $this->redirect('index.php?route=choix-theme');
            exit;
        }
        $themeId = $_GET['themeId'];
        $questionManager = new QuestionManager();
        $questions = $questionManager->findByThemeId($themeId);

        if (empty($questions)) {
            $this->redirect('index.php?route=choix-theme'); // Si un thème n'a pas de question on renvoie sur le choix des thèmes
            exit;
        }

        $_SESSION['quiz'] = [ // on conserve en session les questions, l'indice de la question en cours et le score.
        'questions' => $questions,
        'current_index' => 0, 
        'score' => 0
        ];

    
        $this->redirect('index.php?route=quiz-theme-game');
        exit;
    }


    public function game(){
        if (!isset($_SESSION['quiz'])) {
            $this->redirect('index.php?route=choix-theme');
            exit;
        }
        $currentIndex = $_SESSION['quiz']['current_index']; // on obtient l'indice auquel nous sommes dans notre liste de questions
        $currentQuestion = $_SESSION['quiz']['questions'][$currentIndex]; //on obtient la question a l'indice "currentIndex" dans notre liste de questions
        $score = $_SESSION['quiz']['score'];
        $answerManager = new AnswerManager();
        $answers = $answerManager->findByQuestionId($currentQuestion->getId()); // currentQuestion étant une question dans notre liste de questions, on peut utiliser notre getter
    
        $this->render('theme/quizThemeGame.phtml', [
            'answers' => $answers,
            'currentQuestion' => $currentQuestion,
            'currentIndex' => $currentIndex,
            'score' => $score
        ]);
    }

    public function submitAnswer(){
       if (!isset($_SESSION['quiz']) || !isset($_POST['answer_id'])) {
            $this->redirect('index.php?route=choix-theme');
            exit;
        }

        $answerId = (int)$_POST["answer_id"];
        $currentIndex = $_SESSION['quiz']['current_index'];
        $questions = $_SESSION['quiz']['questions'];
        $currentQuestion = $questions[$currentIndex];
        $answerManager = new AnswerManager();
        $answers = $answerManager->findByQuestionId($currentQuestion->getId());

        foreach($answers as $answer){
            if(((int)$answer->getId() === $answerId) && (int)$answer->getIsCorrect() === 1){
                $_SESSION['quiz']['score']++;
                break;
            }
        }
        $_SESSION['quiz']['current_index'] ++;
        exit;

    }
}