<?php
    global $sys;
    global $auth;
    global $tmp;
    global $courseID;
    global $canAccess;
    global $permissions;
    global $recordInfo;
    $requireElements = array("requireLeftMenu" => true, "requireBody" => true);

    $tmp->getHeader($requireElements);
    if(($canAccess == true && $recordInfo["private"] == 0) || ($recordInfo["private"] == 1 && in_array("edit",$permissions))) {
        include $tmp->load("record/home.php");
    }
    else{
        include $tmp->load("course/noaccess.php");
    }
    $tmp->getFooter($requireElements);