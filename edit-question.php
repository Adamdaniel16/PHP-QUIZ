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
                <input type='text' name='textq' value='$textq'><br/>
                <label for='score'>Score</label>
                <input type='number' name='score' min='0' value='$score'><br/>";

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
            }
        ?>
        <form action='all-question.php'>
            <button type="submit">Back</button>
        </form>
    </body>