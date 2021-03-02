<?php
    ob_start();
    session_start();

    unset($config);
    global $config;
    $config = new stdClass();

    ////////////////////////////////////////////////
    // 1- Initialize the directories variables
    ////////////////////////////////////////////////
    $config->maindir   = __DIR__;
    $config->systemDir = $config->maindir . "/system";
    $config->mainUrl   = "https://ezcasttest.ulb.ac.be/newezplayer/";
    $config->directory = array(
        "config"      => $config->systemDir . "/config",
        "controllers" => $config->systemDir . "/controllers",
        "library"     => $config->systemDir . "/library",
        "languages"   => $config->systemDir . "/languages",
        "templates"   => $config->maindir . "/templates",
        "admin"       => $config->maindir . "/admin",
        "repository"  => "/Bibliothèque/WebServer/Documents/newezplayer/repository/"
    );

    require_once $config->directory["config"] . "/variables.php";
    require_once $config->directory["config"] . "/cache.php";
    require_once $config->directory["config"] . "/encoding.php";

    require_once $config->directory["library"] . "/cache.php";
    require_once $config->directory["library"] . "/databases.php";
    require_once $config->directory["library"] . "/system.php";
    require_once $config->directory["library"] . "/log.php";

    include $config->directory["languages"] . "/fr.php";

    require_once $config->directory["library"] . "/templates.php";
    require_once $config->directory["library"] . "/authentication.php";
    require_once $config->directory["library"] . "/controller.php";
