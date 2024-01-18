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
        <title>Add Question</title>
        <link rel="stylesheet" href="css/add-question.css">
        <link rel="stylesheet" href="css/base.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Questrial&display=swap" rel="stylesheet"> 
        <style>
            .question-form {
                display: none;
            }

            .question-form.active {
                display: block;
            }
        </style>
    </head>
    <body>
        <h1>ADD QUESTION</h1>

        <ul>
            <li><a href="#" onclick="showForm('text')"><button>Text</button></a></li>
            <li><a href="#" onclick="showForm('radio')"><button>Radio</button></a></li>
            <li><a href="#" onclick="showForm('checkbox')"><button>Checkbox</button></a></li>
        </ul>

        <div id="text-form" class="question-form">
            <h2>Text</h2>
            <form method='POST' action='add-question.php'>
                <?php
                    // $idq = get_nb_instances() + 1;
                    echo "<input type='hidden' name='idq'>";
                ?>
                <input type='hidden' name='typeq' value='text'>
                <label for='textq'>Question</label>
                <input type='text' name='textq'><br/>
                <label for='answer'>Answer</label>
                <input type='text' name='answer'><br/>
                <label for='score'>Score</label>
                <input type='number' name='score' min="0" value="1" id='no'><br/>

                <input type='submit' value='Ajouter' id='no'>
            </form>
        </div>

        <div id="radio-form" class="question-form">
            <h2>Radio</h2>
            <form method='POST' action='add-question.php'>
                <?php
                // $idq = get_nb_instances() + 1;
                echo "<input type='hidden' name='idq'>";
                ?>
                <input type='hidden' name='typeq' value='radio'>
                <label for='textq'>Question</label>
                <input type='text' name='textq'><br/>
                <label for='answer'>Answer</label>
                <input type='text' name='answer'><br/>
                <label for='score'>Score</label>
                <input type='number' name='score' min="0" value="1" id='no'><br/>
                <label for='score'>Choices</label>
                <input type='text' name='choices'><br/>

                <input type='submit' value='Ajouter' id='no'>
            </form>
        </div>
        
        <div id="checkbox-form" class="question-form">
            <h2>Checkbox</h2>
            <form method='POST' action='add-question.php'>
                <?php
                // $idq = get_nb_instances() + 1;
                echo "<input type='hidden' name='idq'>";
                ?>
                <input type='hidden' name='typeq' value='checkbox'>
                <label for='textq'>Question</label>
                <input type='text' name='textq'><br/>
                <label for='answer'>Answer</label>
                <input type='text' name='answer'><br/>
                <label for='score'>Score</label>
                <input type='number' name='score' min="0" value="1" id='no'><br/>
                <label for='score'>Choices</label>
                <input type='text' name='choices'><br/>

                <input type='submit' value='Ajouter' id='no'>
            </form>
        </div>
        <script>
            function showForm(formType) {
                // Hide all forms
                var forms = document.querySelectorAll('.question-form');
                forms.forEach(function(form) {
                    form.classList.remove('active');
                });

                // Show the selected form
                var selectedForm = document.getElementById(formType + '-form');
                selectedForm.classList.add('active');
            }
        </script>
        <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $quest = $_POST;
                $quest['idq'] = get_nb_instances() + 1;
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
                add_question($objet);
            }
        ?>
        <form action='home.php'>
            <button action='home.php'>Go to Homepage</button>
        </form>
    </body>