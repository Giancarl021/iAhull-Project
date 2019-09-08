<?php
    require("setup.php");

    if (isset($_GET["getUser"])) {
        session_start();
        $user = isset($_SESSION["username"]) ? $_SESSION["username"] : null;
        if(is_null($user)) echo ";";
        else echo $_SESSION["username"];
        die;
    }

    $data = [];
    $cadastro = false;
    foreach (["username", "pssw"] as $var) {
        if (isset($_POST[$var]) && trim($_POST[$var] != "")) $data[$var] = $_POST[$var];
        else {
            header("location: ../login.php?login_failed=0");
            die;
        }
    }

    $data["username"] = trim($data["username"]);

    if (isset($_POST["c-pssw"])) {
        $data["c-pssw"] = $_POST["c-pssw"];
        $cadastro = true;
    }

    if (!$cadastro) {
        $fp = @fopen("../data/users/" . $data["username"] . ".txt", "r");
        if (!$fp) {
            header("location: ../login.php?login_failed=0"); # Usuário não existe
            die;
        }
        $pssw = "";
        while (!feof($fp)) {
            $pssw .= fgets($fp);
        }
        fclose($fp);
        if ($data["pssw"] != $pssw) {
            header("location: ../login.php?login_failed=1"); # Senha incorreta
            die;
        }
        initSession($data); # Iniciar sessão
        header("location: ../index.php"); # Login bem-sucedido
    } else {
        if ($data["pssw"] != $data["c-pssw"]) {
            header('location: ../login.php?a=new&login_failed=2'); # Senha e Confirma senha não conferem
            die;
        }

        if (preg_match("/;/", $data["username"])) {
            header("location: ../login.php?a=new&login_failed=4"); # Nome de usuário contém ponto e vírgula
            die;
        }

        if (!createUser($data)) {
            header("location: ../login.php?a=new&login_failed=3"); # Usuário já existe
            die;
        }
        initSession($data); # Iniciar sessão
        header("location: ../index.php"); # Cadastro bem-sucedido
    }