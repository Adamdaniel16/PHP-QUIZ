<?php

namespace Form\Type;

// Classe abstrait de Question
abstract class Question {
    public string $typeq;
    public string $textq;
    public int $score;

    public function __construct(string $typeq, string $textq, int $score) {
        $this->typeq = $typeq;
        $this->textq = $textq;
        $this->score = $score;
    }
}

?>