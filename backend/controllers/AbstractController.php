<?php

abstract class AbstractController{
    public function __construct()
    {

    }

    protected function render(string $template, array $data): void
    {
        extract($data);
        $template = $template;
        require "templates/layout.phtml";
    }

    protected function redirect(string $route): void
    {
        header("Location: $route");
    }
    
    public function notFound():void
    {
        $this->render('notFound.phtml', []);
        exit;
    }
}