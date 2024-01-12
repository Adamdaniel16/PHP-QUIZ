<html>
    <head></head>
    <body>
    <?php
    $question_total = 0;
    $question_correct = 0;
    $score_total = 0;
    $score_correct = 0;
    include 'functions.php';
    $q = get_questions(); // récupérer les questions

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        echo "<form method='POST' action='questionnaire.php'><ol>";
        foreach ($q as $quest) {
            echo "<li>";
            $question_handlers[$quest["typeq"]]($quest);
        }
        echo "</ol><input type='submit' value='Envoyer'></form>";
    } else {
        $question_total = 0;
        $question_correct = 0;
        $score_total = 0;
        $score_correct = 0;
        foreach ($q as $quest) {
            $question_total += 1;
            $answer_handlers[$quest["typeq"]]($quest, $_POST[$quest["nameq"]] ?? NULL);
        }
        echo "Réponses correctes: " . $question_correct . "/" . $question_total . "<br>";
        echo "Votre score: " . $score_correct . "/" . $score_total . "<br>";
        echo "<form action='home.php' method='post'>
                <button type='submit'>Go to Homepage</button>
            </form>";
    }
    ?>
    </body>
</html>