<?php
# on fait une classe pour créer un objet question
class Question{
    public function __construct(private string $statement, private ?string $explication, private Theme $theme, private ?int $id){

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getStatement() : string
    {
        return $this->statement;
    }

    public function setStatement(string $statement) : void
    {
        $this->statement = $statement;
    }

    public function getExplication() : ?string
    {
        return $this->explication;
    }

    public function setExplication(?string $explication) : void
    {
        $this->explication = $explication;
    }

    public function getTheme() : Theme
    {
        return $this->theme;
    }

    public function setTheme(Theme $theme) : void
    {
        $this->theme = $theme;
    }
}