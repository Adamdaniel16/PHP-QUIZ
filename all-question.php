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

                // echo "<td>$answer</td>
                // <td><a>editer</a></td>
                // <td><a>supprimer</a></td>
                // </tr>";

                echo "<td>$answer</td>
            <td><a href='edit-question.php?idq=$idq'>editer</a></td>
        </tr>";
            }
        ?>
        <form action='home.php'>
            <button type="submit">Go to Homepage</button>
        </form>
    </body>