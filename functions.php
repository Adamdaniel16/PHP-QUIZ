<?php
require_once 'Classes/autoloader.php';
use Form\Type\Question;
use Form\Type\QuestionText;
use Form\Type\QuestionCheckbox;
use Form\Type\QuestionRadio;

function createQuestion($q){
    switch ($q['typeq']) {
        case 'text':
            return new QuestionText(
                $q['idq'],
                $q['typeq'],
                $q['textq'],
                $q['answer'],
                $q['score']
            );
        case 'radio':
            $q['choices'] = get_choices($q);
            return new QuestionRadio(
                $q['idq'],
                $q['typeq'],
                $q['textq'],
                $q['answer'],
                $q['score'],
                $q['choices']
            );
        case 'checkbox':
            $q['choices'] = get_choices($q);
            $q['answer'] = get_answers($q);
            return new QuestionCheckbox(
                $q['idq'],
                $q['typeq'],
                $q['textq'],
                $q['answer'],
                $q['score'],
                $q['choices']
            );

        default:
            throw new InvalidArgumentException("Unsupported question type: $q[typeq]");
    }
}

function get_bd(){
    $file_db = new PDO('sqlite:questionnaire.sqlite3');
    $file_db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
    return $file_db;
}

function get_nb_instances(){
    $file_db = get_bd();
    $stmt = $file_db->query('SELECT * FROM question');
    $quests = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return count($quests);
}

function get_instances(){
    $file_db = get_bd();
    $stmt = $file_db->query('SELECT * FROM question');
    $quests = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $res = array();

    foreach($quests as $q){
        $res[] = createQuestion($q);
    }
    return $res;
}

function get_choices($q){
    $file_db = get_bd();
    $requete = 'SELECT * FROM choices WHERE idq=:idq';
    $stmt = $file_db->prepare($requete);
    $stmt->bindParam(':idq', $q['idq']);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_answers($q){
    $file_db = get_bd();
    $requete = 'SELECT texta FROM answers WHERE idq=:idq';
    $stmt = $file_db->prepare($requete);
    $stmt->bindParam(':idq', $q['idq']);
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $ans = array_column($res, 'texta');
    return $ans;
}

function add_question($q){
    $file_db = get_bd();

    $insertq = "INSERT INTO question(idq, typeq, textq, answer, score) VALUES (:idq, :typeq, :textq, :answer, :score)";
    $stmtq = $file_db->prepare($insertq);

    $insertc = "INSERT INTO choices(idc, idq, textc) VALUES (:idc, :idq, :textc)";
    $stmtc = $file_db->prepare($insertc);

    $inserta = "INSERT INTO answers(ida, idq, texta) VALUES (:ida, :idq, :texta)";
    $stmta = $file_db->prepare($inserta);

    // on lie les param aux var
    $stmtq->bindParam(':idq', $idq);
    $stmtq->bindParam(':typeq', $typeq);
    $stmtq->bindParam(':textq', $textq);
    $stmtq->bindParam(':answer', $answer);
    $stmtq->bindParam(':score', $score);

    $stmtc->bindParam(':idc', $idc);
    $stmtc->bindParam(':idq', $idq);
    $stmtc->bindParam(':textc', $textc);

    $stmta->bindParam(':ida', $ida);
    $stmta->bindParam(':idq', $idq);
    $stmta->bindParam(':texta', $texta);

    $idq=$q->idq;
    $typeq=$q->typeq;
    $textq=$q->textq;
    $answer=!is_array($q->answer) ? $q->answer : null;
    $score=$q->score;
    $stmtq->execute();
    if($typeq=='radio' or $typeq=='checkbox'){
        $i = 0;
        foreach($q->choices as $c){
            $i += 1;
            $idc=$idq . 'C' . $i;
            $textc=$c;
            $stmtc->execute();
        }
    }
    if($typeq=='checkbox'){
        $i = 0;
        foreach($q->answer as $a){
            $i += 1;
            $ida=$idq . 'A' . $i;
            $texta=$a;
            $stmta->execute();
        }
    }
}

function get_question_by_id($id){
    $file_db = get_bd();
    $requete = 'SELECT * FROM question WHERE idq=:idq';
    $stmt = $file_db->query($requete);
    $stmt = $file_db->prepare($requete);
    $stmt->bindParam(':idq', $id);
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return createQuestion($res[0]);

    $file_db = get_bd();
    $requete = 'SELECT texta FROM answers WHERE idq=:idq';
    $stmt = $file_db->prepare($requete);
    $stmt->bindParam(':idq', $q['idq']);
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $ans = array_column($res, 'texta');
    return $ans;
}

function edit_question($q){
    $file_db = get_bd();

    $updateq = "UPDATE question SET textq = :textq, answer = :answer, score = :score WHERE idq = :idq";
    $stmtq = $file_db->prepare($updateq);

    // $insertc = "UPDATE choices SET textq = :textq, answer = :answer, score = :score WHERE idq = :idq";
    // $stmtc = $file_db->prepare($insertc);

    // $inserta = "INSERT INTO answers(ida, idq, texta) VALUES (:ida, :idq, :texta)";
    // $stmta = $file_db->prepare($inserta);

    // on lie les param aux var
    $stmtq->bindParam(':textq', $textq);
    $stmtq->bindParam(':answer', $answer);
    $stmtq->bindParam(':score', $score);
    $stmtq->bindParam(':idq', $idq);

    // $stmtc->bindParam(':idc', $idc);
    // $stmtc->bindParam(':idq', $idq);
    // $stmtc->bindParam(':textc', $textc);

    // $stmta->bindParam(':ida', $ida);
    // $stmta->bindParam(':idq', $idq);
    // $stmta->bindParam(':texta', $texta);

    $idq=$q->idq;
    $textq=$q->textq;
    $answer=!is_array($q->answer) ? $q->answer : null;
    $score=$q->score;
    $stmtq->execute();
    // if($typeq=='radio' or $typeq=='checkbox'){
    //     $i = 0;
    //     foreach($q->choices as $c){
    //         $i += 1;
    //         $idc=$idq . 'C' . $i;
    //         $textc=$c;
    //         $stmtc->execute();
    //     }
    // }
    // if($typeq=='checkbox'){
    //     $i = 0;
    //     foreach($q->answer as $a){
    //         $i += 1;
    //         $ida=$idq . 'A' . $i;
    //         $texta=$a;
    //         $stmta->execute();
    //     }
    // }
}

// AFFICHAGE DES QUESTIONS
$question_handlers = array(
    "text" => "question_text",
    "radio" => "question_radio",
    "checkbox" => "question_checkbox"
);

$answer_handlers = array(
    "text" => "answer_text",
    "radio" => "answer_radio",
    "checkbox" => "answer_checkbox"
);



function question_text($q) {
    echo ($q->textq . "<br><input type='text' name='$q->idq'><br>");
}

function answer_text($q, $v) {
    global $question_correct, $score_total, $score_correct;
    $score_total += $q->score;
    if (is_null($v)) return;
    if ($q->answer == $v) {
        $question_correct += 1;
        $score_correct += $q->score;
    }
}

function question_radio($q) {
    $html = $q->textq . "<br>";
    $i = 0;
    foreach ($q->choices as $c) {
        $i += 1;
        $html .= "<input type='radio' name='$q->idq' value='$c[textc]' id='$q->idq-$i'>";
        $html .= "<label for='$q->idq-$i'>$c[textc]</label>";
    }
    echo $html;
}

function answer_radio($q, $v) {
    global $question_correct, $score_total, $score_correct;
    $score_total += $q->score;
    if (is_null($v)) return;
    if ($q->answer == $v) {
        $question_correct += 1;
        $score_correct += $q->score;
    }
}

function question_checkbox($q) {
    $html = $q->textq . "<br>";
    $i = 0;
    foreach ($q->choices as $c) {
        $i += 1;
        $html .= "<input type='checkbox' name='{$q->idq}[]' value='$c[textc]' id='$q->idq-$i'>";
        $html .= "<label for='$q->idq-$i'>$c[textc]</label>";
    }
    echo $html;
}

function answer_checkbox($q, $v) {
    global $question_correct, $score_total, $score_correct;
    $score_total += $q->score;
    if (is_null($v)) return;
    $diff1 = array_diff($q->answer, $v);
    $diff2 = array_diff($v, $q->answer);
    if (count($diff1) == 0 && count($diff2) == 0) {
        $question_correct += 1;
        $score_correct += $q->score;
    }
}

?>
