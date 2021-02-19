<?php
    include "../config.php";
    include $config->directory["library"] . "/functions/enrollment.php";
    $sys  = new System();
    $auth = new Authentication();

    $id          = $sys->input("id",SET_INT);
    $token       = $sys->input("token",SET_STRING);
    $type        = $sys->input("type",SET_STRING);
    $surname     = $sys->input("surname",SET_STRING);
    $forname     = $sys->input("forname",SET_STRING);
    $username    = $sys->input("username",SET_STRING);
    $usermail    = $sys->input("usermail",SET_STRING);
    $userpass    = $sys->input("userpass",SET_STRING);
    $confirmpass = $sys->input("confirmpass",SET_STRING);
    $captcha     = $sys->input("captcha",SET_STRING);
    $captcha_ses = (isset($_SESSION["captcha_code"]) ? $_SESSION["captcha_code"] : "");
    manualEnrollment(3);
    if($type == "course"){
        $sqlArray = array(
            "table" => Databases::courses,
            "fields" => array(
                "id" => $id,
                "token" => $token,
                "anon_access" => 3
            )
        );
    }
    elseif($type == "course"){
        $msg = array("rec");
        exit(); // TODO
    }
    else{
        $msg = array("ex");
        exit();
    }

    if(!empty($id) || !empty($token) || !empty($type) || !empty($surname) || !empty($forname) || !empty($username) || !empty($usermail) || !empty($userpass) || !empty($confirmpass) || !empty($captcha)){
        $checkSignupType = $sys->select($sqlArray);
        $userTable = $sys->getTable(Databases::users);

        $checkUserExistence = $sys->sql("SELECT user_ID,usermail FROM {$userTable} where usermail = '{$usermail}' or user_ID = '{$username}'","select");

        if($checkSignupType != false) {
            if ($captcha_ses != $captcha) {
                $msg = array(
                    "error" => true,
                    "msg" => $lang["error_captcha"]
                );
            }
            elseif ($username == $checkUserExistence["user_ID"]){
                $msg = array(
                    "error" => true,
                    "msg" => $lang["username_exists"]
                );
            }
            elseif ($usermail == $checkUserExistence["usermail"]){
                $msg = array(
                    "error" => true,
                    "msg" => $lang["usermail_exists"]
                );
            }
            elseif (!filter_var($usermail, FILTER_SANITIZE_EMAIL)) {
                $msg = array(
                    "error" => true,
                    "msg" => $lang["wrong_email_format"]
                );
            }
            elseif (preg_match("/[^[:alnum:]]/",$username)) {
                $msg = array(
                    "error" => true,
                    "msg" => $lang["wrong_username_format"]
                );
            }
            elseif($userpass != $confirmpass){
                $msg = array(
                    "error" => true,
                    "msg" => $lang["wrong_password_validation"]
                );
            }
            else{
                $msg = array(
                    "error" => false,
                    "msg" => $lang["signup_successful"]
                );

                $sqlArray = array(
                    "user_ID" => $username,
                    "surname" => $surname,
                    "forename" => $forname,
                    "passwd" => $sys->encrypt($userpass),
                    "usermail" => $usermail,
                    "permissions" => $sys->generateToken(),
                    "origin" => "external",
                    "ip" => $sys->getIp(),
                    "course_id" => $checkSignupType["id"],
                    "course_anon_access" => $checkSignupType["anon_access"]
                );
                $userInfo = $auth->createUser($sqlArray);
            }
        }
        else{
            $msg = array(
                "error" => true,
                "msg" => $lang["permission_denied"]
            );

        }
    }
    else{
        $msg = array(
            "error" => true,
            "msg" => $lang["permission_denied"]
        );
    }
    $json = json_encode($msg,JSON_PRETTY_PRINT);

    echo $json;
