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

    $courseInfo  = $sys->instance(CHK_COURSE,$courseID);

    $canAccess   = $sys->getEnrollment(ENR_CAN_ACCESS,$courseID);
    $permissions = $sys->getEnrollment(ENR_ACCESS_TYPE,$courseID);

    $ctrl->load("course");
