<?php
    include "../../config.php";
    include "../configurations.php";

    $requireElements = array("requireLeftMenu" => true, "requireBody" => true);
    $tmp->getHeader($requireElements);
    $do = $sys->input("do",SET_STRING);
    if($do == "regenerate"){
        require "regeneratecache.php";
    }
    $tmp->getFooter($requireElements);
