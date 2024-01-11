<?php
function get_questions(){
    $file_db = new PDO('sqlite:questionnaire.sqlite3');
    $file_db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
    $stmt = $file_db->query('SELECT * FROM question');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_choices($q){
    $file_db = new PDO('sqlite:questionnaire.sqlite3');
    $file_db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
    $requete = 'SELECT * FROM choices WHERE nameq=:nameq';
    $stmt = $file_db->prepare($requete);
    $stmt->bindParam(':nameq', $q['nameq']);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function question_text($q) {
    echo ($q["textq"] . "<br><input type='text' name='$q[nameq]'><br>");
}

function answer_text($q, $v) {
    global $question_correct, $score_total, $score_correct;
    $score_total += $q["score"];
    if (is_null($v)) return;
    if ($q["answer"] == $v) {
        $question_correct += 1;
        $score_correct += $q["score"];
    }
}

function question_radio($q) {
    $html = $q["textq"] . "<br>";
    $i = 0;
    foreach (get_choices($q) as $c) {
        $i += 1;
        $html .= "<input type='radio' name='$q[nameq]' value='$c[textc]' id='$q[nameq]-$i'>";
        $html .= "<label for='$q[nameq]-$i'>$c[textc]</label>";
    }
    echo $html;
}

function answer_radio($q, $v) {
    global $question_correct, $score_total, $score_correct;
    $score_total += $q["score"];
    if (is_null($v)) return;
    if ($q["answer"] == $v) {
        $question_correct += 1;
        $score_correct += $q["score"];
    }
}

function question_checkbox($q) {
    $html = $q["textq"] . "<br>";
    $i = 0;
    foreach ($q["choices"] as $c) {
        $i += 1;
        $html .= "<input type='checkbox' name='$q[nameq][]' value='$c' id='$q[nameq]-$i'>";
        $html .= "<label for='$q[nameq]-$i'>$c</label>";
    }
    echo $html;
}

function answer_checkbox($q, $v) {
    global $question_correct, $score_total, $score_correct;
    $score_total += $q["score"];
    if (is_null($v)) return;
    $diff1 = array_diff($q["answer"], $v);
    $diff2 = array_diff($v, $q["answer"]);
    if (count($diff1) == 0 && count($diff2) == 0) {
        $question_correct += 1;
        $score_correct += $q["score"];
    }
}

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