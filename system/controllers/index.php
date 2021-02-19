<?php
    global $auth;
    global $ctrl;
    global $tmp;
    global $sys;

    $requireElements = array("requireLeftMenu" => true, "requireBody" => true);
    $tmp->getHeader($requireElements);

    if($sys->input("signout",SET_STRING) == "true"){
        $auth->sessionDestroy();
        $this->redirect("index.php");
    }

    $tmp->getFooter($requireElements);