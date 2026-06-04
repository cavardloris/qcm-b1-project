<?php

class Router{
    private AuthController $ac;
    private HomeController $hc;
    private ThemeController $tc;
    private MortSubiteController $msc;
    private CultureGeneraleController $cgc;

    public function __construct()
    {
        $this->ac = new AuthController();
        $this->hc = new HomeController();
        $this->tc = new ThemeController();
        $this->msc = new MortSubiteController();
        $this->cgc = new CultureGeneraleController();
    }

    public function handLeRequest(array $get)
    {
        if(isset($get["route"])){
            if($get["route"] === "login"){
                $this->ac->login();
            } elseif($get["route"] === "logout"){
                $this->ac->logout();
            } elseif($get["route"] === "register"){
                $this->ac->register();
            }elseif($get["route"] === "home"){
                $this->hc->home();
            }elseif($get["route"] === "choix-theme"){
                $this->tc->home();
            } elseif($get["route"] === "start-quiz"){
            $this->tc->startQuiz();
            }elseif($get["route"] === "quiz-theme-game"){
                $this->tc->Game();
            }elseif($get["route"] === "submit-answer-theme"){
                $this->tc->submitAnswer();
            }elseif($get["route"] === "quiz-theme-results"){
                $this->tc->displayResults();
            }elseif($get["route"] === "start-quiz-cgc"){
            $this->cgc->startQuiz();
            }elseif($get["route"] === "quiz-cgc-game"){
            $this->cgc->game();
            }elseif($get["route"] === "submit-answer-cgc"){
                $this->cgc->submitAnswer();
            }elseif($get["route"] === "quiz-cgc-results"){
                $this->cgc->displayResults();
            }elseif($get["route"] === "start-quiz-ms"){
            $this->msc->startQuiz();
            }elseif($get["route"] === "quiz-ms-game"){
            $this->msc->game();
            }else {
                $this->ac->notFound();
            }
        }
        else{
            $this->ac->login();
        }
    }
}