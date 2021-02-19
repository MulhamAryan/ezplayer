<?php
    global $auth;
    global $tmp;

    $tmp->getHeader();

    if(!$auth->isLogged()) {
        $username = $this->input("username", SET_ALPHA_NUM);
        $userpass = $this->input("userpass", SET_STRING);
        $guest = $this->input("guest", SET_INT);
        if ($guest == 1) {
            $fullname = $this->input("fullname",SET_STRING);
            $captcha  = $this->input("captcha",SET_STRING);

            if(isset($fullname) && !empty($fullname) && $captcha == $_SESSION["captcha_code"]){

            }
            else{
                $error = $tmp->getError($this->lang["auth_error"]);
            }
            include $tmp->load("guest_login.php");
        } else {
            if ($username) {
                $checkAuth = $auth->checkLoginInfo($username, $userpass);
                if ($checkAuth == true) {
                    if (isset($_SESSION["redirect"]) && !empty($_SESSION["redirect"])) {
                        $redirectUrl = urldecode($_SESSION["redirect"]);
                        $this->redirect($redirectUrl);
                        unset($_SESSION["redirect"]);
                        unset($redirectUrl);
                    } else {
                        $this->redirect("index.php?login=true");
                    }
                } else {
                    $error = $tmp->getError($this->lang["signinerror"]);
                }
            }
            include $tmp->load("login.php");
        }
    }
    else{
        $this->redirect("index.php");
    }
    unset($error);
    $tmp->getFooter();