<?php

    //If user is stocked in the DB compare username and userpassowrd
    $password = $this->verifyPassword($userpass,$userinfo["passwd"]);

    if ($password == true) {
        if($userinfo["permissions"] != (0 || 1)){
            return "email_activation_needed";
        }
        else {
            return "access_granted";
        }
    }
    else{
        return "access_not_granted";
    }