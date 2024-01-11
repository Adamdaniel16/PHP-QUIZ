
<?php
date_default_timezone_set('Europe/Paris');
try{
    // le fic de BD s'appelle contacts.sqlite3
    $file_db = new PDO('sqlite:questionnaire.sqlite3');
    $file_db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
    // table question
    $file_db->exec("CREATE TABLE IF NOT EXISTS question(
    nameq VARCHAR(42) PRIMARY KEY,
    typeq TEXT CHECK(typeq IN ('text', 'radio', 'checkbox')),
    textq VARCHAR(42),
    answer VARCHAR(42),
    score int(3))");
    // table choices
    $file_db->exec("CREATE TABLE IF NOT EXISTS choices(
    idc VARCHAR(42) PRIMARY KEY,
    nameq VARCHAR(42),
    textc VARCHAR(42),
    FOREIGN KEY(nameq) REFERENCES question(idq))");

    // insertion questions
    $questions=array(
        array(
            'nameq' => 'pi',
            'typeq' => 'text',
            'textq' => 'Citez le chiffre de pi',
            'answer' => '3.142',
            'score' => 1),
        // array(
        //     'nameq' => 'couleurs',
        //     'typeq' => 'checkbox',
        //     'textq' => 'Qeul(les) sont le(s) couleur(s) primaire(s) ?',
        //     'answer' => array('Bleu','Rouge','Jaune'),
        //     'score' => 1,
        //     'choices' => array('Vert','Blanc','Bleu','Rouge','Noir','Jaune')),
        array(
            'nameq' => 'or',
            'typeq' => 'radio',
            'textq' => "Quel est le symbole chimique de l'or ?",
            'answer' => 'Au',
            'score' => 1,
            'choices' => array('H2O','Gd','Au','Or'))
    );

    $insertq = "INSERT INTO question(nameq, typeq, textq, answer, score) VALUES (:nameq, :typeq, :textq, :answer, :score)";
    $stmtq = $file_db->prepare($insertq);

    $insertc = "INSERT INTO choices(idc, nameq, textc) VALUES (:idc, :nameq, :textc)";
    $stmtc = $file_db->prepare($insertc);

    // on lie les param aux var
    $stmtq->bindParam(':nameq', $nameq);
    $stmtq->bindParam(':typeq', $typeq);
    $stmtq->bindParam(':textq', $textq);
    $stmtq->bindParam(':answer', $answer);
    $stmtq->bindParam(':score', $score);

    $stmtc->bindParam(':idc', $idc);
    $stmtc->bindParam(':nameq', $nameq);
    $stmtc->bindParam(':textc', $textc);

    foreach($questions as $q){
        $nameq=$q['nameq'];
        $typeq=$q['typeq'];
        $textq=$q['textq'];
        $answer=$q['answer'];
        $score=$q['score'];
        $stmtq->execute();
        if(isset($q['choices'])){
            $i = 0;
            foreach($q['choices'] as $c){
                $i += 1;
                $idc=$q['nameq'] . $i;
                $nameq=$q['nameq'];
                $textc=$c;
                $stmtc->execute();
            }
        }
    }

    // tester affichage
    // $result = $file_db->query('SELECT * FROM question');
    // $cpt = 1;
    // foreach($result as $r){
    //     echo "<li>".$cpt.$r['textq']."</li>";
    // }

    // echo "Insertion en base réussie !";

    $file_db=null;

}catch(PDOException $ex){
    echo $ex->getMessage();
}
?>