<?php
    include "../../config.php";

    $sys  = new System();
    $auth = new Authentication();
    $log  = new Log();

    $auth->requireLogin();

    require_once $config->directory["library"] . "/comment.php";

    $recordID  = $sys->input("recordid",SET_INT);
    $sessionID = $sys->input("sessionid",SET_STRING);
    $comment   = $sys->input("comment",SET_STRING);

    $commentArray = array(
        "recordid" => $recordID,
        "comment" => $comment,
        "userid" => $auth->getInfo(LOGIN_USER_ID)
    );
    $insertComment = addComment($commentArray);
    echo json_encode($insertComment);