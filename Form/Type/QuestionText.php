<?php

namespace Form\Type;

// Classe de question type text
class QuestionText extends Question {
    public string $answer;

    public function __construct(int $idq, string $typeq, string $textq, string $answer, int $score) {
        $this->answer = $answer;
        parent::__construct($idq, $typeq, $textq, $score);
    }

    public function display_question() {
        echo ("<p>".$this->textq . "</p><br><input type='text' name='$this->idq'><br>");
    }

    public function display_answer($v) {
        global $question_correct, $score_total, $score_correct;
        $score_total += $this->score;
        if (is_null($v)) return;
        if (strtolower($this->answer) == strtolower($v)) {
            $question_correct += 1;
            $score_correct += $this->score;
        }
    }

    public function delete_question($file_db, $idq){        
        $deleteq = "DELETE FROM question WHERE idq = $idq";
        $file_db->exec($deleteq);
    }
    
    public function edit_question($file_db){
    
        $updateq = "UPDATE question SET textq = :textq, answer = :answer, score = :score WHERE idq = :idq";
        $stmtq = $file_db->prepare($updateq);
    
        // on lie les param aux var
        $stmtq->bindParam(':textq', $textq);
        $stmtq->bindParam(':answer', $answer);
        $stmtq->bindParam(':score', $score);
        $stmtq->bindParam(':idq', $idq);
    
        $idq=$this->idq;
        $textq=$this->textq;
        $answer=!is_array($this->answer) ? $this->answer : null;
        $score=$this->score;
        $stmtq->execute();
    }

    public function add_question($file_db){
    
        $insertq = "INSERT INTO question(idq, typeq, textq, answer, score) VALUES (:idq, :typeq, :textq, :answer, :score)";
        $stmtq = $file_db->prepare($insertq);
    
        // on lie les param aux var
        $stmtq->bindParam(':idq', $idq);
        $stmtq->bindParam(':typeq', $typeq);
        $stmtq->bindParam(':textq', $textq);
        $stmtq->bindParam(':answer', $answer);
        $stmtq->bindParam(':score', $score);
    
        $idq=$this->idq;
        $typeq=$this->typeq;
        $textq=$this->textq;
        $answer=!is_array($this->answer) ? $this->answer : null;
        $score=$this->score;
        $stmtq->execute();
    }

}

?>