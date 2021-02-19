<?php
    require "config.php";
    require_once $config->directory["library"] . "/templates.php";
    require_once $config->directory["library"] . "/authentication.php";
    require_once $config->directory["library"] . "/controller.php";

    $sys  = new System();
    $auth = new Authentication();
    $tmp  = new Templates();
    $ctrl = new Controller();

    //TODO Create Access check
    $type        = $sys->input("type",SET_STRING);
    $recordID    = $sys->input("recordid",SET_INT);
    $recordInfo  = $sys->instance(CHK_RECORD,$recordID);

    $ctrl->load("player");
