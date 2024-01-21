<?php
include_once 'functions.php';
require_once 'Classes/autoloader.php';
use Form\Type\Question;
use Form\Type\QuestionText;
use Form\Type\QuestionCheckbox;
use Form\Type\QuestionRadio;

// suppression d'une question

if (isset($_GET['idq'])) {
    $file_db = get_bd();
    $idq = $_GET['idq'];

    // delete_question_by_id($idq);
    $q = get_question_by_id($idq);
    $q->delete_question($file_db, $idq);

    header("Location: all-question.php");
    exit();
} else {
    echo "ERREUR";
}
?>
