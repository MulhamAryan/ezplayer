<?php
    global $auth;
    global $tmp;

    $tmp->getHeader();

    if(!$auth->isLogged()) {
        $guest = $this->input("guest", SET_INT);
        if ($guest == 1) {
            $fullname = $this->input("fullname",SET_STRING);
            $captcha  = $this->input("captcha",SET_STRING);
            $id       = $this->input("id",SET_INT);
            $token    = $this->input("token",SET_STRING);
            $courseCheck = $this->select(array("table" => Databases::courses,"fields" => array("id" => $id, "token" => $token, "anon_access" => 4)));
            if($courseCheck != false) {
                if (!empty($fullname)) {
                    if ($captcha == $_SESSION["captcha_code"]) {
                        $randomId = rand(100000, 9999999);
                        $guestInfo = array(
                            "is_guest" => true,
                            "user_id" => $randomId,
                            "user_login" => "guest_" . $randomId . $auth->generateToken(),
                            "forename" => $fullname,
                            "surname" => "",
                            "permissions" => 0,
                            "id" => $id,
                            "token" => $token,
                            "enrType" => "course"
                        );

                        $auth->createSession($guestInfo);
                        $this->redirect("index.php?login=true");
                    } else {
                        $error = $tmp->getError($this->lang["auth_error"]);
                    }
                }
                include $tmp->load("guest_login.php");
            }
            else{
                $this->redirect("index.php");
            }
        } else {
            $username = $this->input("username", SET_ALPHA_NUM);
            $userpass = $this->input("userpass", SET_STRING);
            if ($username) {
                $checkAuth = $auth->checkLoginInfo($username, $userpass);

                if ($checkAuth == "access_granted") {
                    if (isset($_SESSION["redirect"]) && !empty($_SESSION["redirect"])) {
                        $redirectUrl = urldecode($_SESSION["redirect"]);
                        $this->redirect($redirectUrl);
                        unset($_SESSION["redirect"]);
                        unset($redirectUrl);
                    } else {
                        $this->redirect("index.php?login=true");
                    }
                }
                elseif($checkAuth == "email_activation_needed"){
                    $error = $tmp->getError($this->lang["email_activation_needed"]);
                }
                else{
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