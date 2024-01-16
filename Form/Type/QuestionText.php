<?php

namespace Form\Type;

// Classe de question type text
class QuestionText extends Question {
    public string $answer;

    public function __construct(int $idq, string $typeq, string $textq, string $answer, int $score) {
        $this->answer = $answer;
        parent::__construct($idq, $typeq, $textq, $score);
    }
}

?>