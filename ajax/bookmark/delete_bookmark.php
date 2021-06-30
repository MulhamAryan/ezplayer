<?php
    include "../../config.php";

    $sys  = new System();
    $auth = new Authentication();
    $log  = new Log();

    $auth->requireLogin();

    require_once $config->directory["library"] . "/bookmarks.php";
    $bookmarkid = $sys->input("bookmarkid",SET_INT);
    $hash       = $sys->input("hash",SET_STRING);

    echo json_encode(delete_bookmark($bookmarkid,$hash),true);
