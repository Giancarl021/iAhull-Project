<?php
    require("setup.php");
    $path = $author = $text = $ans = null;
    foreach (["path", "author", "ans", "text"] as $var) {
        if (isset($_GET[$var])) $$var = $_GET[$var];
        else {
            echo "err";
            die;
        }
    }
    $ans = $ans == "true";

    if (!_createFolder("../$path", ["data" => ["author" => $author, "likes" => 0, "dislikes" => 0], "text" => $text], $ans)) {
        echo "err";
    }