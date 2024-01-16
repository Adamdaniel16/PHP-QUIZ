<html>
    <head></head>
    <body>
    <h1>QUIZ</h1>
    <?php
    $question_total = 0;
    $question_correct = 0;
    $score_total = 0;
    $score_correct = 0;
    include 'functions.php';
    $q = get_instances(); // récupérer les questions

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        echo "<form method='POST' action='questionnaire.php'><ol>";
        $id = 0;
        foreach ($q as $quest) {
            echo "<li>";
            $id += 1;
            $question_handlers[$quest->typeq]($quest, $id);
        }
        echo "</ol><input type='submit' value='Envoyer'></form>";
    } else {
        $question_total = 0;
        $question_correct = 0;
        $score_total = 0;
        $score_correct = 0;
        foreach ($q as $quest) {
            $answer_handlers[$quest->typeq]($quest, $_POST[$quest->idq] ?? NULL);
        }
        echo "Réponses correctes: " . $question_correct . "/" . $question_total . "<br>";
        echo "Votre score: " . $score_correct . "/" . $score_total . "<br>";
        echo "Votre score: " . round($score_correct/$score_total*100, 2) . "%<br>";
    }
    ?>
    <form action='home.php'>
        <button type='submit'>Go to Homepage</button>
    </form>
    </body>
</html>