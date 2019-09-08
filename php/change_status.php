<?php
    require("setup.php");
    session_start();
    if (is_null(isset($_SESSION["username"]) ? $_SESSION["username"] : null)) {
        echo "null";
        die;
    }
    $filename = $action = null;
    foreach(["filename", "action"] as $var) {
        if(isset($_GET[$var])) $$var = $_GET[$var];
        else {
            echo "err";
            die;
        }
    }

    $data = _readData("../" . $filename . "/data.txt");

    if ($data == -1) {
        header("HTTP/1.0 400 Question Not Founded");
        die;
    }
    if (preg_match("/-/", $action)) {
        $reverse = true;
        $attrib = str_replace("-", "", $action);
    }

    if (!isset($data[$action])) {
        header("HTTP/1.0 400 Action Not Founded");
        die;
    }

    $data[$action]++;

    if (!_writeData("../" . $filename . "/data.txt", $data)) {
        header("HTTP/1.0 400 Writing Data Error");
        die;
    }

    echo $data["likes"] . "&" . $data["dislikes"];
