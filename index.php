<?php
    require("php/setup.php");
    session_start();
    $user = isset($_SESSION["username"]) ? $_SESSION["username"] : null;
    $q = readQuestions();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <title>iAhull</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="icon" href="img/logo.png" type="image/x-icon"/>
    <link rel="stylesheet" type="text/css" media="screen" href="css/style.css"/>
    <script src="libs/jQuery.js"></script>
    <script src="js/script.js"></script>
    <!-- DEBUG -->
    <!--    <link rel="stylesheet" type="text/css" media="screen" href="css/debug.css"/>-->
    <!--    <script src="js/debug.js"></script>-->
</head>

<body>
    <header>
        <h1>iAhull</h1>
        <div class="user">
            <h1 id="user-initial">
                <?php
                    if (!is_null($user)) echo substr($user, 0, 1);
                    else echo "?";
                ?>
            </h1>
            <label>
                <?php
                    if (!is_null($user)) {
                        echo explode(" ", $user)[0] . " <a href='login.php?a=exit'>Sair</a>";
                    } else {
                        echo "<a href='login.php'>Login</a> ou <a href='login.php?a=new'>Cadastrar</a>";
                    }
                ?>
            </label>
        </div>
        <?php
            if (!is_null($user)) {
                echo "<div id=\"add-question\" onclick=\"initModal('" . (sizeof($q) == 0 ? "Fazer primeira pergunta" : "Fazer pergunta") . "', false)\">" .
                    "<img src=\"img/add.svg\" alt=\"Fazer uma pergunta\"/>" .
                    "</div>";
            }
        ?>
        <div class="menu" style="display: none" onclick="showHamburguer()">
            <img src="img/menu.svg" alt="Menu Hamburguer"/>
        </div>
        <div class="burger" style="display: none">
            <div class="burger-header">
                <a href="#" onclick="showHamburguer()"><img class="back-button" src="img/burger-back.svg" alt="Voltar"></a>
            </div>
            <div class="burger-section">
                <div class="line-block">
                    <?php
                        echo !is_null($user) ? $user : "Não conectado";
                    ?>
                </div>
                <div class="fill-block">
                    <?php
                        echo !is_null($user) ? "<a href='login.php?a=exit'>Sair</a>" : "<a href='login.php'>Login</a> ou <a href='login.php?a=new'>Cadastrar</a>";
                    ?>
                </div>
            </div>
        </div>
    </header>
    <section>
        <?php
            //            echo "<pre>" . print_r($q, true) . "</pre>";
            if (sizeof($q) == 0) {
                echo "<h1>Parece que não há perguntas ainda...</h1><div class='center-advice'><a href='#' onclick='initModal(\"Fazer a primeira pergunta\", false)'>Seja o primeiro a perguntar!</a></div>";
            } else {
                foreach ($q as $card) {
                    echo "<div class='question-card' data-filename='" . $card["path"] . "'>" .
                        "<span class='username'>" . $card["data"]["author"] . "</span>" .
                        "<h1 class='question-title'>" . $card["question"] . "</h1>" .
                        "<div class='reaction-box'>" .
                        "<img src='img/like.svg' alt='Gostei' onclick='changeQuestion(this.parentElement.parentElement, \"likes\")'>" .
                        "<span class='likes-counter'>" . $card["data"]["likes"] . "</span>" .
                        "<img src='img/dislike.svg' alt='Não Gostei' onclick='changeQuestion(this.parentElement.parentElement, \"dislikes\")'>" .
                        "<span class='dislikes-counter'>" . $card["data"]["dislikes"] . "</span>" .
                        "</div>" .
                        "<a href='#' onclick='showAnswers(this)'>Ver respostas</a>" .
                        (!is_null($user) ? "<a href='#' onclick='initModal(this.parentElement, true)'>Responder</a>" : "") .
                        "</div>" .
                        "<div class='answers' style='display: none'>";
                    if (!count($card["answers"])) echo "<div class='question-card answer'><h1 class='question-title answer equal-margin' style='text-align: center'>Não há respostas para esta pergunta ainda</h1></div>";
                    else {
                        foreach ($card["answers"] as $ans) {
//                    echo "<pre>" . print_r($ans, true) . "</pre>";
                            echo "<div class='question-card answer' data-filename='" . $ans["path"] . "'>" .
                                "<span class='username' style='color: #7c5fb5'>" . $ans["data"]["author"] . "</span>" .
                                "<h1 class='question-title answer'>" . $ans["answer"] . "</h1>" .
                                "<div class='reaction-box'>" .
                                "<img src='img/like.svg' alt='Gostei' onclick='changeQuestion(this.parentElement.parentElement, \"likes\")'>" .
                                "<span class='likes-counter'>" . $ans["data"]["likes"] . "</span>" .
                                "<img src='img/dislike.svg' alt='Não Gostei' onclick='changeQuestion(this.parentElement.parentElement, \"dislikes\")'>" .
                                "<span class='dislikes-counter'>" . $ans["data"]["dislikes"] . "</span>" .
                                "</div>" .
                                "</div>";
                        }
                    }
                    echo "</div>";
                }
            }
        ?>
    </section>
    <div id="modal-base" style="display: none">
        <div id="modal-header">
            <div id="source-element"></div>
        </div>
        <div id="modal-section">
            <textarea></textarea>
        </div>
        <div id="modal-footer">
            <button type="button" onclick="submitModalData()">Enviar</button>
            <button type="button" onclick="closeModal()">Cancelar</button>
        </div>
    </div>
    <div id="warn-toast" style="display:none;"></div>
</body>

</html>