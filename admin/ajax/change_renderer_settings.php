<?php

    include "../../config.php";
    include "../configurations.php";

    $serverid   = $sys->input("id", SET_INT);
    $sessionid = $sys->input("sessionid",SET_STRING);
    $auth->checkSessionID($sessionid);

    $server = $sys->select(array("table" => Databases::renderers, "fields" => array("id" => $serverid)));

    if($server != false){
        $status = ($server["enabled"] == 1 ? 0 : 1);
        $updateServer = $sys->update(array("table" => System::renderers, "fields" => "enabled = '{$status}' where id = '{$serverid}'"));
        if($updateServer == true){
            $json = array("error" => false, "new_status" => $status);
        }
        else{
            $json = array("error" => true, "message" => $lang["renderer"]["update_fail"]);
        }
    }
    else{
        $json = array("error" => true, "message" => $serverid . " => " . $lang["renderer"]["server_not_found"]);
    }
    echo json_encode($json,true);
    unset($json);