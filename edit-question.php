<!-- Page pour editer une question -->
<html>
    <head>
        <?php
            include_once 'functions.php';
            require_once 'Classes/autoloader.php';
            use Form\Type\Question;
            use Form\Type\QuestionText;
            use Form\Type\QuestionCheckbox;
            use Form\Type\QuestionRadio;
        ?>
            <title>Edit Question</title>
            <link rel="stylesheet" href="css/edit-question.css">
            <link rel="stylesheet" href="css/base.css">
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Questrial&display=swap" rel="stylesheet"> 
    </head>
    <body>
        <h1>EDIT QUESTION</h1>
        <?php
            $idq = isset($_GET['idq']) ? $_GET['idq'] : null;
            if($idq!==null){
                $question = get_question_by_id($idq);
                $typeq = $question->typeq;
                $textq = $question->textq;
                $score = $question->score;
                echo "<form method='POST' action='edit-question.php'>
                <input type='hidden' name='idq' value='$idq'>
                <input type='hidden' name='typeq' value='$typeq'>
                <label for='textq'>Question</label>
                <input type='text' name='textq' value=\"$textq\"><br/>
                <label for='score'>Score</label>
                <input type='number' name='score' min='0' value='$score'><br/>";

                // vérifier le type des questions différents
                switch($typeq){
                    case 'text':
                        $answer = $question->answer;
                        break;
                    case 'radio':
                        $answer = $question->answer;
                        $choices = array_map(function($item){return $item['textc'];}, $question->choices);
                        $choices = implode(";",$choices);
                        echo "<label for='score'>Choices</label>
                        <input type='text' name='choices' value='$choices'><br/>";
                        break;
                    case 'checkbox':
                        $answer = implode(";",$question->answer);
                        $choices = array_map(function($item){return $item['textc'];}, $question->choices);
                        $choices = implode(";",$choices);
                        echo "<label for='score'>Choices</label>
                        <input type='text' name='choices' value='$choices'><br/>";
                        break;
                }

                echo "<label for='answer'>Answer</label>
                <input type='text' name='answer' value='$answer'><br/>
                <input type='submit' value='Mettre à jour'>
                </form>";
            }

            // pour mettre à jour la question
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $quest = $_POST;
                switch($quest['typeq']){
                    case 'text':
                        $objet = new QuestionText($quest['idq'], $quest['typeq'],$quest['textq'],$quest['answer'],$quest['score']);
                        break;
                    case 'radio':
                        $quest['choices'] = explode(';', $quest['choices']);
                        $objet = new QuestionRadio($quest['idq'], $quest['typeq'],$quest['textq'],$quest['answer'],$quest['score'],$quest['choices']);
                        break;
                    case 'checkbox':
                        $quest['answer'] = explode(';', $quest['answer']);
                        $quest['choices'] = explode(';', $quest['choices']);
                        $objet = new QuestionCheckbox($quest['idq'], $quest['typeq'],$quest['textq'],$quest['answer'],$quest['score'],$quest['choices']);
                        break;
                }
                edit_question($objet);
                header("Location: all-question.php");
            }
        ?>
        <form action='all-question.php'>
            <button type="submit">Go to Homepage</button>
        </form>
    </body>