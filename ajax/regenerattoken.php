<?php
    include "../config.php";

    $sys = new System();
    $auth = new Authentication();

    $auth->requireLogin();

    $id     = $sys->input("id",SET_INT);
    $hashid = $sys->input("hashid",SET_STRING);

    if($auth->validateHash($hashid,$id) == true) {
        require_once $config->directory["library"] . "/access.php";
        $newToken = regenerateToken("course", $id);
        if ($newToken != false) {
            echo $sys->url(array("file" => System::fileCourse, "parameters" => array("id" => $id, "token" => $newToken)));
        }
    }
    else{
        echo $lang["permission_denied"];
    }