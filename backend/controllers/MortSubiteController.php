<?php

class MortSubiteController extends AbstractController{

    public function startQuiz(){
        if(!isset($_SESSION["id"])){
            $this->redirect('index.php?route=login');
            exit;
        }
        $questionManager = new QuestionManager();
        $questions = $questionManager->findAll();

        if (empty($questions)) {
            $this->redirect('index.php?route=home'); // Si on ne trouve pas de question dans la bdd
            exit;
        }

        $_SESSION['quiz'] = [ // on conserve en session les questions, l'indice de la question en cours et le score.
        'questions' => $questions,
        'current_index' => 0, 
        'score' => 0
        ];

        $this->redirect('index.php?route=quiz-ms-game');
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
            $this->redirect('index.php?route=quiz-ms-results');
            exit;
        }

        $currentQuestion = $questions[$currentIndex]; //on obtient la question a l'indice "currentIndex" dans notre liste de questions
        $score = $_SESSION['quiz']['score'];

        $questionManager = new QuestionManager();
        $totalQuestion = $questionManager->count();
        $answerManager = new AnswerManager();
        $answers = $answerManager->findByQuestionId($currentQuestion->getId()); // currentQuestion étant une question dans notre liste de questions, on peut utiliser notre getter
    
        $this->render('mortSubite/quizMortSubite.phtml', [
            'answers' => $answers,
            'currentQuestion' => $currentQuestion,
            'currentIndex' => $currentIndex,
            'score' => $score,
            'totalQuestion' => $totalQuestion
        ]);
    }

     public function submitAnswer(){
       if (!isset($_SESSION['quiz'])) {
            $this->redirect('index.php?route=home');
            exit;
        }

        $answerId = (int)$_POST["answer_id"];
        $currentIndex = $_SESSION['quiz']['current_index'];
        $questions = $_SESSION['quiz']['questions'];
        $currentQuestion = $questions[$currentIndex];
        $answerManager = new AnswerManager();
        $answers = $answerManager->findByQuestionId($currentQuestion->getId());

        $isCorrectAnswer = false;

        foreach($answers as $answer){
            if(((int)$answer->getId() === $answerId) && (int)$answer->getIsCorrect() === 1){
                $isCorrectAnswer = true;
            }
        }

        if($isCorrectAnswer){
            $_SESSION['quiz']['score']++;
            $_SESSION['quiz']['current_index']++;

            $this->redirect('index.php?route=quiz-ms-game');
            exit;
        }else{
            $this->redirect('index.php?route=quiz-ms-results');
            exit;
        }
    }

    public function displayResults(){
        if (!isset($_SESSION['quiz'])) {
            $this->redirect('index.php?route=home');
            exit;
        }
        $finalScore = $_SESSION['quiz']['score'];
        $questions = $_SESSION['quiz']['questions'];


        $pseudo = $_SESSION["pseudo"];
        $userId = $_SESSION["id"];
        $currentDate = new \DateTime();
        $questionManager = new QuestionManager();
        $totalQuestion = $questionManager->count();

        if($userId !== null){
           $userManager = new UserManager();
           $user = $userManager->findById($userId);

           $score = new Score($user, null, 0, $finalScore, Mode::from('sudden_death'), $currentDate, null);
           $scoreManager = new ScoreManager();
           $scoreManager->create($score);
        }

        unset($_SESSION['quiz']);

        $this->render('mortSubite/resultsMS.phtml', [
            "finalScore" => $finalScore,
            "pseudo" => $pseudo,
            "totalQuestion" => $totalQuestion
        ]);
        exit;
    }
}