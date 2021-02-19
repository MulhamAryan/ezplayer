<?php
    require "../../config.php";
    require_once $config->directory["library"] . "/templates.php";
    require_once $config->directory["library"] . "/authentication.php";

    $sys  = new System();
    $auth = new Authentication();

    $auth->requireLogin();

    $recordID  = $sys->input("id",SET_INT);
    $sessionID = $sys->input("sessionID",SET_STRING);

    $auth->checkSessionID($sessionID);

    //TODO Create course instance to check access
    $array = array(
        "table" => Databases::records,
        "fields" => array(
            "id" => $recordID
        )
    );
    $record = $sys->select($array);

    if($record["private"] == 0) {
        $newPrivate = 1;
    }
    elseif ($record["private"] == 1) {
        $newPrivate = 0;
    }
    else {
        $newPrivate = 0;
    }
    $sys->update(array("table" => Databases::records, "fields" => "private = '{$newPrivate}' where id = '{$recordID}'"));
    $sys->updateCourseCache($record["course_id"]);
    echo $newPrivate;