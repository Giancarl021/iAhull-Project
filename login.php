<?php
    require("php/setup.php");
    $action = isset($_GET["a"]) ? $_GET["a"] : null;
    if ($action == "exit") logout();
    else session_start();
    $user = isset($_SESSION["username"]) ? $_SESSION["username"] : null;
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <title>iAhull</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="icon" href="img/logo.png" type="image/x-icon">
    <link rel="stylesheet" type="text/css" media="screen" href="css/style.css"/>
    <script src="libs/jQuery.js"></script>
    <script src="js/script.js"></script>
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
                        echo "Não Conectado";
                    }
                ?>
            </label>
        </div>
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
            </div>
        </div>
        <a href="index.php"><img class="back-button" src="img/back.svg" alt="Voltar"></a>
    </header>
    <section>
        <h1 class="err-note">
            <?php
                if (isset($_GET["login_failed"])) {
                    $err = $_GET["login_failed"];
                    switch ($err) {
                        case 0:
                            echo "Este usuário não existe";
                            break;
                        case 1:
                            echo "Senha incorreta";
                            break;
                        case 2:
                            echo "Campos \"Senha\" e \"Confirma Senha\" precisam ser iguais";
                            break;
                        case 3:
                            echo "Este usuário já existe";
                            break;
                        case 4:
                            echo "O nome de usuário não pode conter \";\"";
                            break;
                    }
                }
            ?>
        </h1>
        <form action="php/authenticate.php" method="post">
            <label for="i-name">Nome de Usuário:</label><input id="i-name" type="text" name="username" required>
            <label for="i-pssw">Senha:</label><input id="i-pssw" type="password" name="pssw" required>
            <?php
                if ($action == "new") echo "<label for='i-c-pssw'>Confirmar Senha:</label><input id='i-c-pssw' type='password' name='c-pssw' required>";
            ?>
            <button type="submit"><?php echo $action == "new" ? "Cadastrar" : "Entrar" ?></button>
        </form>
        <div class="advice">
            <?php
                if ($action == "new") echo "Já possui conta? <a href=\"login.php\">Entrar</a>";
                else echo "Não possui conta? <a href=\"login.php?a=new\">Cadastrar</a>";
            ?>
        </div>
    </section>
</body>

</html>