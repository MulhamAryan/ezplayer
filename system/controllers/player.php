<?php
    global $auth;
    global $tmp;
    global $type;
    global $sys;

    $recordType = $sys->input("recordType",SET_STRING);
    $quality    = $sys->input("quality",SET_STRING);
    $dir        = $sys->input("dir",SET_STRING);
    $file       = $sys->input("file",SET_STRING);
    $recordid   = $sys->input("recordid", SET_INT);


    if($type == "hls"){
        if(empty($file)) {
            $recordType .= "record";
            include "player/hls.php";
        }
        else
            include "player/ts.php";
    }
    elseif ($type == "html"){
        include "player/html.php";
    }
    else{
        exit();
    }