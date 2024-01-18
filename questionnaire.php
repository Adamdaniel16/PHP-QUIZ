<html>
    <head>
        <title>Questionnaire</title>
        <link rel="stylesheet" href="css/questionnaire.css">
        <link rel="stylesheet" href="css/base.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Questrial&display=swap" rel="stylesheet"> 
    </head>
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
        foreach ($q as $quest) {
            echo "<li>";
            $question_handlers[$quest->typeq]($quest, $quest->idq);
        }
        echo "</ol><input type='submit' value='Envoyer'></form>";
    } else {
        $question_total = get_nb_instances();
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
        <button action='home.php'>Go to Homepage</button>
    </form>
    </body>
</html>