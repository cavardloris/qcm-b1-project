<?php

class ProfileController extends AbstractController{
     public function profile() {
        if(!isset($_SESSION["id"])){
            $this->redirect("?route=login");
            exit;
        }

        $userManager = new UserManager();
        $user = $userManager->findById($_SESSION["id"]);

        $scoreManager = new ScoreManager();
        $firstGame = $scoreManager->findFirstGame($_SESSION["id"]);
        
        $this->render('auth/profile.phtml', [
            "user" => $user,
            "firstGame" => $firstGame
        ]);
        
    }
}
