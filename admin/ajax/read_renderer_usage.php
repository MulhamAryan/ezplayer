<?php

    include "../../config.php";
    include "../configurations.php";

    require_once $config->directory["library"] . "/ssh.php";
    $username = $sys->input("username", SET_STRING);
    $hosturl  = $sys->input("hosturl",SET_STRING);
    $sessionid = $sys->input("sessionid",SET_STRING);
    $auth->checkSessionID($sessionid);

    $ssh = new SSH($username,$hosturl);
    if($ssh->errorMessage["error"] == false){
        $usage = $ssh->exec("/bin/echo \"`echo $[100-$(/usr/bin/vmstat 1 2| /usr/bin/tail -1 | /usr/bin/awk '{print $15}')]`,`/usr/bin/free -m | /usr/bin/awk '/Mem:/ { printf(\"%3.1f\", $3/$2*100) }'`,`/bin/df -h / | /usr/bin/awk '/\// {print $(NF-1)}'`\"");
        $usage = trim($usage);
        $usage = str_replace("%","",$usage);
        $usage = explode(",",$usage);
        $usage = json_encode($usage,true);
        echo $usage;
    }
    else{
        echo json_encode($ssh->errorMessage, true);
    }