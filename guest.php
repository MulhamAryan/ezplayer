<?php
include "config.php";

$sys = new System();
$auth = new Authentication();
$username = rand(100000,999999);
$guestInfo = array();
$guestInfo["is_logged"]   = true;
$guestInfo["user_id"]     = "guest_" . $username;
$guestInfo["user_login"]  = $username;
$guestInfo["forename"]    = "Guest";
$guestInfo["surname"]     = $username;
$guestInfo["permissions"] = 0;

$auth->createSession($guestInfo);