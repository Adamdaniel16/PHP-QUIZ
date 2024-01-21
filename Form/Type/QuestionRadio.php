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

    public function display_question() {
        $html = "<p>".$this->textq . "</p><br>";
        $i = 0;
        foreach ($this->choices as $c) {
            $i += 1;
            $html .= "<input type='radio' name='$this->idq' value='$c[textc]' id='$this->idq-$i'>";
            $html .= "<label for='$this->idq-$i'>$c[textc]</label>";
        }
        echo $html;
    }

    public function display_answer($v) {
        global $question_correct, $score_total, $score_correct;
        $score_total += $this->score;
        if (is_null($v)) return;
        if ($this->answer == $v) {
            $question_correct += 1;
            $score_correct += $this->score;
        }
    }

    public function delete_question($file_db, $idq){        
        $deleteq = "DELETE FROM question WHERE idq = $idq";
        $deletec = "DELETE FROM choices WHERE idq = $idq";
        $file_db->exec($deleteq);
        $file_db->exec($deletec);
    }

    public function edit_question($file_db){
    
        $updateq = "UPDATE question SET textq = :textq, answer = :answer, score = :score WHERE idq = :idq";
        $stmtq = $file_db->prepare($updateq);
    
        $insertc = "INSERT INTO choices(idc, idq, textc) VALUES (:idc, :idq, :textc)";
        $stmtc = $file_db->prepare($insertc);
    
        // on lie les param aux var
        $stmtq->bindParam(':textq', $textq);
        $stmtq->bindParam(':answer', $answer);
        $stmtq->bindParam(':score', $score);
        $stmtq->bindParam(':idq', $idq);
    
        $stmtc->bindParam(':idc', $idc);
        $stmtc->bindParam(':idq', $idq);
        $stmtc->bindParam(':textc', $textc);
    
        $idq=$this->idq;
        $textq=$this->textq;
        $answer=!is_array($this->answer) ? $this->answer : null;
        $score=$this->score;
        $stmtq->execute();

        $deletec = "DELETE FROM choices WHERE idq = $idq";
        $file_db->exec($deletec);
        $i = 0;
        foreach($this->choices as $c){
            $i += 1;
            $idc=$idq . 'C' . $i;
            $textc=$c;
            $stmtc->execute();
        }
    }

    public function add_question($file_db){
    
        $insertq = "INSERT INTO question(idq, typeq, textq, answer, score) VALUES (:idq, :typeq, :textq, :answer, :score)";
        $stmtq = $file_db->prepare($insertq);
    
        $insertc = "INSERT INTO choices(idc, idq, textc) VALUES (:idc, :idq, :textc)";
        $stmtc = $file_db->prepare($insertc);
    
        // on lie les param aux var
        $stmtq->bindParam(':idq', $idq);
        $stmtq->bindParam(':typeq', $typeq);
        $stmtq->bindParam(':textq', $textq);
        $stmtq->bindParam(':answer', $answer);
        $stmtq->bindParam(':score', $score);
    
        $stmtc->bindParam(':idc', $idc);
        $stmtc->bindParam(':idq', $idq);
        $stmtc->bindParam(':textc', $textc);
    
        $idq=$this->idq;
        $typeq=$this->typeq;
        $textq=$this->textq;
        $answer=!is_array($this->answer) ? $this->answer : null;
        $score=$this->score;
        $stmtq->execute();

        $i = 0;
        foreach($this->choices as $c){
            $i += 1;
            $idc=$idq . 'C' . $i;
            $textc=$c;
            $stmtc->execute();
        }
    }
}

?>