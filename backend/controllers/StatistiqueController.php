<?php

class StatistiqueController extends AbstractController{

    public function home(){
        if(!isset($_SESSION["id"]))
        {
            $this->redirect('index.php?route=login');
            exit;
        }
        $themeManager = new ThemeManager();
        $scoreManager = new ScoreManager();
        $userManager = new UserManager();

        $userId = $_SESSION["id"];

        $gamePlayed = $scoreManager->countGame($userId);
        $gamePlayedInMS = $scoreManager->countGameByMode($userId, 'sudden_death');
        $maxScoreMS = $scoreManager->MaxScoreMortSubite($userId);
        $maxScoresMS = $scoreManager->MaxScoresMortSubite($userId);
        $totalPoints = $scoreManager->countPoints($userId);
        $gamePlayedInTheme = $scoreManager->countGameByMode($userId, 'theme');

        
        $themes = $themeManager->findAll();
        $this->render('stats/statsHome.phtml', [
            'gamePlayed' => $gamePlayed,
            'themes' => $themes,
            'gamePlayedInMS' => $gamePlayedInMS,
            'maxScoreMS' => $maxScoreMS,
            'maxScoresMS' => $maxScoresMS,
            'totalPoints' => $totalPoints,
            'gamePlayedInTheme' => $gamePlayedInTheme
            ]);
       
    }
}