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

    $courseID    = $sys->input("id",SET_INT);

    $courseInfo  = $auth->instance(CHK_COURSE,$courseID);

    $canAccess   = $auth->getEnrollment(ENR_CAN_ACCESS,$courseID);
    $permissions = $auth->getEnrollment(ENR_ACCESS_TYPE,$courseID);

    $ctrl->load("course");