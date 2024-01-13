<?php

namespace Form\Type;

// Classe de question type checkbox
class QuestionCheckbox extends Question {
    public array $answer;
    public array $choices;

    public function __construct(string $typeq, string $textq, array $answer, int $score, array $choices) {
        $this->answer = $answer;
        $this->choices = $choices;
        parent::__construct($typeq, $textq, $score);
    }
}

?>