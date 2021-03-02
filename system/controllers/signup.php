<?php
    global $sys;
    global $auth;
    global $tmp;
    global $instance;

    if($instance != false) {
        $requireElements = array("requireLeftMenu" => false, "requireBody" => true);
        $tmp->getHeader($requireElements);
        include $tmp->load("signup.php");
        $tmp->getFooter($requireElements);
    }