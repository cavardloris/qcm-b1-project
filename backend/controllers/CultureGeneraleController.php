<?php

class CultureGeneraleController extends AbstractController{
    public function startQuiz(){
        if(!isset($_SESSION["id"])){
            $this->redirect('index.php?route=login');
            exit;
        }
        $questionManager = new QuestionManager();
        $questions = $questionManager->find12Random();

        if (empty($questions)) {
            $this->redirect('index.php?route=home'); // Si on ne trouve pas de question dans la bdd
            exit;
        }

        $_SESSION['quiz'] = [ // on conserve en session les questions, l'indice de la question en cours et le score.
        'questions' => $questions,
        'current_index' => 0, 
        'score' => 0
        ];

        $this->redirect('index.php?route=quiz-cgc-game');
        exit;
    }

    public function game(){
        if (!isset($_SESSION['quiz'])) {
            $this->redirect('index.php?route=home');
            exit;
        }
        $currentIndex = $_SESSION['quiz']['current_index']; // on obtient l'indice auquel nous sommes dans notre liste de questions
        $questions = $_SESSION['quiz']['questions'];

        if($currentIndex >= count($questions)){
            $this->redirect('index.php?route=quiz-cgc-results');
            exit;
        }

        $currentQuestion = $questions[$currentIndex]; //on obtient la question a l'indice "currentIndex" dans notre liste de questions
        $score = $_SESSION['quiz']['score'];

        $answerManager = new AnswerManager();
        $answers = $answerManager->findByQuestionId($currentQuestion->getId()); // currentQuestion étant une question dans notre liste de questions, on peut utiliser notre getter
    
        $this->render('cultureGenerale/quizCultureGenerale.phtml', [
            'answers' => $answers,
            'currentQuestion' => $currentQuestion,
            'currentIndex' => $currentIndex,
            'score' => $score
        ]);
    }

    public function submitAnswer(){
       if (!isset($_SESSION['quiz']) || !isset($_POST['answer_id'])) {
            $this->redirect('index.php?route=home');
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

    public function displayResults(){
        if (!isset($_SESSION['quiz'])) {
            $this->redirect('index.php?route=home');
            exit;
        }
        $finalScore = $_SESSION['quiz']['score'];
        $questions = $_SESSION['quiz']['questions'];
        $totalQuestions = count($questions);

        $pseudo = $_SESSION["pseudo"];
       
        unset($_SESSION['quiz']);

        $this->render('cultureGenerale/resultsCGC.phtml', [
            "finalScore" => $finalScore,
            "totalQuestions" => $totalQuestions,
            "pseudo" => $pseudo
        ]);

    }

}