<?php

class ThemeController extends AbstractController{
    public function home(){
        if(!isset($_SESSION["id"]))
        {
            $this->redirect('index.php?route=login');
            exit;
        }
        else{
            $this->render('theme/home.phtml', []);
        }
    }
}