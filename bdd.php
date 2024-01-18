<?php
include_once 'functions.php';
require_once 'Classes/autoloader.php';
use Form\Type\Question;
use Form\Type\QuestionText;
use Form\Type\QuestionCheckbox;
use Form\Type\QuestionRadio;

date_default_timezone_set('Europe/Paris');
try{
    // le fic de BD s'appelle contacts.sqlite3
    $file_db = new PDO('sqlite:questionnaire.sqlite3');
    $file_db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);

    // table question
    $file_db->exec("CREATE TABLE IF NOT EXISTS question(
    idq int(3) PRIMARY KEY,
    typeq TEXT CHECK(typeq IN ('text', 'radio', 'checkbox')),
    textq VARCHAR(100),
    answer VARCHAR(42),
    score int(3))");

    // table choices
    $file_db->exec("CREATE TABLE IF NOT EXISTS choices(
    idc VARCHAR(42) PRIMARY KEY,
    idq int(3),
    textc VARCHAR(100),
    FOREIGN KEY(idq) REFERENCES question(idq))");

    // table answers
    $file_db->exec("CREATE TABLE IF NOT EXISTS answers(
        ida VARCHAR(42) PRIMARY KEY,
        idq int(3),
        texta VARCHAR(100),
        FOREIGN KEY(idq) REFERENCES question(idq))");
    
    // insertion questions
    $questions=array(
        new QuestionText(1, 'text','Citez le chiffre de pi','3.142',1),
        new QuestionCheckbox(2, 'checkbox','Quelles sont les couleurs primaires ?',array('Bleu','Rouge','Jaune'),1,array('Vert','Blanc','Bleu','Rouge','Noir','Jaune')),
        new QuestionRadio(3, 'radio',"Quel est le symbole chimique de l'or ?",'Au',1,array('H2O','Gd','Au','Or')),
    );

    foreach($questions as $q){
        add_question($q);
    }

    echo "Insertion en base réussie !";

    $file_db=null;

}catch(PDOException $ex){
    echo $ex->getMessage();
}    
?>
