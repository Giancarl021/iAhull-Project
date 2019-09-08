<?php
    define("user_fd", "../data/users/");
    function createUser($user) {
        $fp = @fopen(user_fd . $user["username"] . ".txt", "x");
        if (!$fp) return false;
        fwrite($fp, $user["pssw"]);
        fclose($fp);
        return true;
    }

    function initSession($user) {
        session_start();
        $_SESSION["username"] = $user["username"];
    }

    function logout() {
        session_start();
        $_SESSION["username"] = null;
    }

    function readQuestions() {
        $data = [];
        $main = glob("data/questions/*", GLOB_ONLYDIR);
        foreach ($main as $q) {
            $sub = ["path" => $q];
            $temp = glob($q . "/*");
            $sub["question"] = _readFile($temp[2]);
            $sub["data"] = _readData($temp[1]);
            $sub["answers"] = _readAnswers($temp[0]);
            $data[] = $sub;
        }
        return $data;
    }

    function _readData($path) {
        $str = _readFile($path);
        $temp = explode(";", $str);
        if (sizeof($temp) < 3) return -1;
        return ["author" => $temp[0], "likes" => $temp[1], "dislikes" => $temp[2]];
    }

    function _writeData($path, $data) {
        $fp = @fopen($path, "w");
        if (!$fp) return false;
        fwrite($fp, $data["author"] . ";" . $data["likes"] . ";" . $data["dislikes"]);
        fclose($fp);
        return true;
    }

    function _readFile($path) {
        $fp = @fopen($path, "r");
        if (!$fp) return false;
        $str = "";
        while (!feof($fp)) {
            $str .= fgets($fp);
        }
        fclose($fp);
        return $str;
    }

    function _readAnswers($path) {
        $r = [];
        $ans = glob($path . "/*");
        foreach ($ans as $an) {
            $tmp = [];
            $temp = glob($an . "/*");
            $tmp["path"] = $an;
            $tmp["data"] = _readData($temp[1]);
            $tmp["answer"] = _readFile($temp[0]);
            $r[] = $tmp;
        }
        return $r;
    }

    function _createFolder($path, $data, $is_answer) {
        // $path = Endereço da pasta pai (data/questions ou data/questions/question/answers)
        // $data = ["data" => ["author" => "fulano de tal", "likes" => 0, "dislikes" => 0], "text" => "question/answer"]
        // $is_answer: bool, define se é uma pasta com respostas ou não

        if($is_answer) $path .= "/answers";

        if(!file_exists("$path/rubber_duck.html") && !file_exists("$path/../../rubber_duck.html")) return false; // Evitar HTML Injection
        $fd = "$path/" . round(microtime(true) * 100);
        mkdir($fd);
        if(!$is_answer) mkdir("$fd/answers");
        if (!_writeData("$fd/data.txt", $data["data"])) return false;
        $fp = @fopen("$fd/" . ($is_answer ? "answer.txt" : "question.txt"), "w");
        fwrite($fp, $data["text"]);
        fclose($fp);
        return true;
    }