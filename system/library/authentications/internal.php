<?php
    //If user is stocked in the DB compare username and userpassowrd
    $password = $this->verifyPassword($userpass,$userinfo["recorder_passwd"]);

    if ($password == true) {
        return true;
    }
    else{
        return false;
    }