<?php
    require "config.php";
    require_once $config->directory["library"] . "/templates.php";
    require_once $config->directory["library"] . "/authentication.php";
    require_once $config->directory["library"] . "/controller.php";

    $sys  = new System();
    $auth = new Authentication();
    $tmp  = new Templates();
    $ctrl = new Controller();

    $auth->requireLogin();

    $recordID   = $sys->input("id",SET_INT);
    $recordInfo = $sys->instance(CHK_RECORD,$recordID);

    $canAccess   = $sys->getEnrollment(ENR_CAN_ACCESS,$recordInfo["course_id"]);
    $permissions = $sys->getEnrollment(ENR_ACCESS_TYPE,$recordInfo["course_id"]);

    $ctrl->load("record");