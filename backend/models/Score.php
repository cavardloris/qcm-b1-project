<?php

class Score{
    public function __construct(private User $user, private ?Theme $theme, private int $points = 0, private int $serie_max, private Mode $mode, private DateTime $date = new DateTime(), private ?int $id)
    {

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getUser():User
    {
        return $this->user;
    }

    public function setUser(User $user):void
    {
        $this->user = $user;
    }

    public function getTheme():?Theme
    {
        return $this->theme;
    }

    public function setTheme(Theme $theme):void
    {
        $this->theme = $theme;
    }

    public function getPoints(): int
    {
        return $this->points;
    }

    public function setPoints(int $points): void
    {
        $this->points = $points;
    }

    public function getSerieMax(): int
    {
        return $this->serie_max;
    }

    public function setSerieMax(int $serie_max): void
    {
        $this->serie_max = $serie_max;
    }

    public function getMode(): Mode
    {
        return $this->mode;
    }

    public function setMode(Mode $mode): void
    {
        $this->mode = $mode;
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }
    
    public function setDate(DateTime $date): void
    {
        $this->date = $date;
    }
}