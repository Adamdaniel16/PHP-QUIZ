<?php

namespace Form\Type;

// Classe de question type radio
class QuestionRadio extends Question {
    public string $answer;
    public array $choices;

    public function __construct(int $idq, string $typeq, string $textq, string $answer, int $score, array $choices) {
        $this->answer = $answer;
        $this->choices = $choices;
        parent::__construct($idq, $typeq, $textq, $score);
    }
}

?>