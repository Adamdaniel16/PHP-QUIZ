<!-- Page l'affichage de toutes les questions -->
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
            <title>All Question</title>
            <link rel="stylesheet" href="css/add-question.css">
            <link rel="stylesheet" href="css/base.css">
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Questrial&display=swap" rel="stylesheet"> 
    </head>
    <body>
        <h1>EDIT A QUESTION</h1>
        <table>
            <tr>
                <th>Type</th>
                <th>Question</th>
                <th>Score</th>
                <th>Choices</th>
                <th>Answer</th>
            </tr>
        <?php
            foreach(get_instances() as $question){
                $idq = $question->idq;
                $typeq = $question->typeq;
                $textq = $question->textq;
                $score = $question->score;

                echo "<tr>
                <td>$typeq</td>
                <td>$textq</td>
                <td>$score</td>";

                // vérif de type de question distinct
                switch($typeq){
                    case 'text':
                        echo "<td></td>";
                        $answer = $question->answer;
                        break;
                    case 'radio':
                        $answer = $question->answer;
                        $choices = array_map(function($item){return $item['textc'];}, $question->choices);
                        $choices = implode(";",$choices);
                        echo "<td>$choices</td>";
                        break;
                    case 'checkbox':
                        $answer = implode(";",$question->answer);
                        $choices = array_map(function($item){return $item['textc'];}, $question->choices);
                        $choices = implode(";",$choices);
                        echo "<td>$choices</td>";
                        break;
                }

                echo "<td>$answer</td>
                <td><a href='edit-question.php?idq=$idq'>editer</a></td>
                <td><a href='delete-question.php?idq=$idq' onclick='return confirm(\"Are you sure you want to delete this question?\")'>supprimer</a></td>
                </tr>";
            }
        ?>
        <form action='home.php'>
            <button type="submit">Go to Homepage</button>
        </form>
    </body>