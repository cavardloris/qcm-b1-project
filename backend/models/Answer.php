<?php
# on fait une classe pour créer un objet answer
class Answer{
    public function __construct(private string $answer, private bool $is_correct, private Question $question, private ?int $id){
        
    }

    public function getAnswer() : string
    {
        return $this->answer;
    }

    public function setAnswer(string $answer) : void
    {
        $this->answer = $answer;
    }

    public function getIsCorrect(): bool
    {
        return $this->is_correct;
    }

    public function setIsCorrect(bool $is_correct) :void
    {
        $this->is_correct = $is_correct;
    }

    public function getQuestion() : Question
    {
        return $this->question;
    }

    public function setQuestion(Question $question) : void
    {
        $this->question = $question;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }
}