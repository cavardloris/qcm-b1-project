<?php

class HomeController extends AbstractController{
    public function home(){
        if(!isset($_SESSION["id"])){
            $this->redirect("?route=login");
            exit;
        }

        $this->render('home/home.phtml', []);
    }
}