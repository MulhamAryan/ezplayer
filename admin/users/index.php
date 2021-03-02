<?php
    include "../../config.php";
    include "../configurations.php";

    $requireElements = array("requireLeftMenu" => true, "requireBody" => true);
    $tmp->getHeader($requireElements);
    $usersTable  = $sys->getTable(Databases::users);

    $search = $sys->input("userSearch",SET_STRING);
    $do     = $sys->input("do",SET_STRING);
    $page   = $sys->input("page",SET_INT);
    $where = "";
    $perPage = 25;

    if($page < 1) $page = 1;
    if(!empty($search)){
        $where = "where user_ID like '%{$search}%' or forename like '%{$search}%' or surname like '%{$search}%' or id like '%{$search}%'";
    }

    $countUsers = $sys->sql("SELECT COUNT(*) as usersnb from {$usersTable}","select");
    $totalPages = ceil($countUsers["usersnb"]/$perPage);
    $startFrom = ($page * $perPage) - $perPage;
    if($totalPages > 5){
        $lastMax  = $totalPages;
        $totalPages = 5;
    }
    foreach (new DirectoryIterator($config->directory["library"] . "/authentications/") as $methodLogin){
        if(!$methodLogin->isDot()){
            $method = explode(".",$methodLogin->getFilename());
            $loginMethod[] = $method[0];
        }
    }
    if($do == "add"){
        $username = $sys->input("username",SET_STRING);
        $forname  = $sys->input("forname",SET_STRING);
        $surname  = $sys->input("surname",SET_STRING);
        $usermail = $sys->input("usermail",SET_STRING);
        $userpass = $sys->input("userpass",SET_STRING);
        $origin   = $sys->input("origin",SET_STRING);
        $permissions = $sys->input("permissions", SET_INT);

        if(!empty($username) && !empty($forname) && !empty($surname) && !empty($usermail) && !empty($userpass) && !empty($origin)){
            $checkUserExists = $sys->sql("SELECT * FROM {$usersTable} where user_ID = '{$username}' or usermail = '{$usermail}'","select");
            if($checkUserExists == false){
                $userSql = array(
                    "user_ID" => $username,
                    "surname" => $surname,
                    "forename" => $forname,
                    "passwd" => $sys->encrypt($userpass),
                    "usermail" => $usermail,
                    "recorder_passwd" => "",
                    "permissions" => $permissions,
                    "origin" => $origin,
                    "ip" => "",
                    "updated_password" => 1
                );
                $createdUser = $auth->createUser($userSql);
                $sys->redirect($sys->url(array("file" => System::fileUserConf, "parameters" => array("do" => "edit", "userid" => $createdUser["id"], "sessionid" => $auth->getSessionID()))));
            }
            else{
                $error = $lang["users"]["exists"];
            }
        }
        else{
            $error = $lang["users"]["empty_field"];
        }
        include $tmp->load("admin/users/add.php");
    }
    elseif($do == "delete"){
        $sessionid = $sys->input("sessionid", SET_STRING);
        $userid    = $sys->input("id", SET_INT);
        $auth->checkSessionID($sessionid);

        $update = $sys->update(array("table" => Databases::users, "fields" => "permissions='deleted' where id = '{$userid}'"));
        if($update == true)
            $success = "USERID : " . $userid . " " .$lang["users"]["deleted"];
        else
            $error = $lang["unknown_problem"];
    }
    elseif($do == "restore"){
        $sessionid = $sys->input("sessionid", SET_STRING);
        $userid    = $sys->input("id", SET_INT);
        $auth->checkSessionID($sessionid);

        $update = $sys->update(array("table" => Databases::users, "fields" => "permissions='0' where id = '{$userid}'"));
        if($update == true)
            $success = "USERID : " . $userid . " " . $lang["users"]["restored"];
        else
            $error = $lang["unknown_problem"];
    }
    $users = $sys->sql("SELECT * FROM {$usersTable} {$where} order by id asc LIMIT {$startFrom}, {$perPage}","fetch");
    include $tmp->load("admin/users/index.php");
    $tmp->getFooter($requireElements);