<?php
    include "../config.php";
    $sys = new System();

    include "tokens.php";
    $clientToken = $sys->input("token",SET_STRING);
    $data        = $sys->input("data",SET_ARRAY);
    $clientIp    = $_SERVER['REMOTE_ADDR'];
    $data = json_decode(file_get_contents('php://input'), true);

    foreach ($data as $d){
        var_dump($d);
    }
    foreach ($tokens as $token){
        $grantAccess = false;
        if($token["enabled"] == true && $token["enabled"] == $clientIp && $token["token"] == $clientToken){
            $grantAccess = true;
            break;
        }
    }
    var_dump($grantAccess);