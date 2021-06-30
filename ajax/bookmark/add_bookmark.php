<?php
    include "../../config.php";

    $sys  = new System();
    $auth = new Authentication();
    $log  = new Log();

    $auth->requireLogin();

    require_once $config->directory["library"] . "/bookmarks.php";

    echo json_encode(addBookmark(),true);

