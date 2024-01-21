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
    <?php
    $question_total = 0;
    $question_correct = 0;
    $score_total = 0;
    $score_correct = 0;
    require_once 'Classes/autoloader.php';
    use Form\Type\Question;
    use Form\Type\QuestionText;
    use Form\Type\QuestionCheckbox;
    use Form\Type\QuestionRadio;
    include 'functions.php';
    $q = get_instances(); // récupérer les questions

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        echo "<h1>QUIZ</h1>";
        echo "<form method='POST' action='questionnaire.php'><ol>";
        echo ("<label for='user'>Username: <br>");
        echo ("<input type='text' name='user' class='user' required><br>");
        foreach ($q as $quest) {
            echo "<li>";
            $quest->display_question();
        }
        echo "</ol><input type='submit' value='Envoyer'></form>";
    } else {
        echo "<h2>Félicitations ".$_POST["user"]." ! Voici votre score</h2>";
        $question_total = get_nb_instances();
        $question_correct = 0;
        $score_total = 0;
        $score_correct = 0;
        foreach ($q as $quest) {
            $quest->display_answer($_POST[$quest->idq] ?? NULL);
        }
        echo "<p>Réponses correctes: " . $question_correct . "/" . $question_total . "</p><br>";
        echo "<p>Votre score: " . $score_correct . "/" . $score_total . "</p><br>";
        echo "<p>Votre score: " . round($score_correct/$score_total*100, 2) . "%</p><br>";
    }
    ?>
    <form action='home.php'>
        <button action='home.php'>Go to Homepage</button>
    </form>
    </body>
</html>