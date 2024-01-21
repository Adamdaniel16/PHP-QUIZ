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

// retourne la BD
function get_bd(){
    $file_db = new PDO('sqlite:questionnaire.sqlite3');
    $file_db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
    return $file_db;
}

// retourne le nombre d'instances
function get_nb_instances(){
    $file_db = get_bd();
    $stmt = $file_db->query('SELECT * FROM question');
    $quests = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return count($quests);
}

// retourne les instances
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

// retourne les choix d'une question
function get_choices($q){
    $file_db = get_bd();
    $requete = 'SELECT * FROM choices WHERE idq=:idq';
    $stmt = $file_db->prepare($requete);
    $stmt->bindParam(':idq', $q['idq']);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// retourne les réponses d'une question
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

// retourne une question en fonction de son id
function get_question_by_id($id){
    $file_db = get_bd();
    $requete = 'SELECT * FROM question WHERE idq=:idq';
    $stmt = $file_db->query($requete);
    $stmt = $file_db->prepare($requete);
    $stmt->bindParam(':idq', $id);
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return createQuestion($res[0]);
}
?>
