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
    $recordInfo = $auth->instance(CHK_RECORD,$recordID);

    $canAccess   = $auth->getEnrollment(ENR_CAN_ACCESS,$recordInfo["course_id"]);
    $permissions = $auth->getEnrollment(ENR_ACCESS_TYPE,$recordInfo["course_id"]);

    $ctrl->load("record");