<?php

    require "../config.php";
    //require_once $config->directory["library"] . "/templates.php";
    //require_once $config->directory["library"] . "/authentication.php";
    //require_once $config->directory["library"] . "/controller.php";
    require_once $config->directory["library"] . "/player.php";

    //$sys  = new System();
    //$auth = new Authentication();
    //$tmp  = new Templates();
    //$ctrl = new Controller();
    $player = new Player();
    //TODO Create Access check
    //$type        = $sys->input("type",SET_STRING);
    //$recordID    = $sys->input("recordid",SET_INT);
    //$recordInfo  = $auth->instance(CHK_RECORD,$recordID);
    echo $player->m3u();

    //$ctrl->load("player/m3u");
