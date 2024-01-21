<?php

namespace Form\Type;

// Classe abstrait de Question
abstract class Question {
    public int $idq;
    public string $typeq;
    public string $textq;
    public int $score;

    public function __construct(int $idq, string $typeq, string $textq, int $score) {
        $this->idq = $idq;
        $this->typeq = $typeq;
        $this->textq = $textq;
        $this->score = $score;
    }

    abstract public function display_question();

    abstract public function display_answer($v);

    abstract public function delete_question($file_db, $idq);

    abstract public function edit_question($file_db);

    abstract public function add_question($file_db);


}

?>