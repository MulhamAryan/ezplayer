<?php
    define("repository",repository);
    define("queues_recorder_uploads",queues_recorder_uploads);
    define("queues_recorder_uploads_ok",queues_recorder_uploads_ok);
    define("queues_recorder_uploads_failed",queues_recorder_uploads_failed);
    define("queues_submit_uploads",queues_submit_uploads);
    define("queues_submit_uploads_ok",queues_submit_uploads_ok);
    define("queues_submit_uploads_failed",queues_submit_uploads_failed);
    define("queues_rendering",queues_rendering);
    define("queues_rendering_uploading",queues_rendering_uploading);
    define("queues_rendering_processed",queues_rendering_processed);

    $config->debug = true;
    if($config->debug == true){
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }
    $config->url = "https://ezcasttest.ulb.ac.be/newezplayer/";
    $config->mail = "noreply@ezcasttest.ulb.ac.be";
    $config->repository = "/var/www/repository/";
    $config->activeTemplate = "ezcast";
    $config->template = array(
        "css"    => $config->mainUrl . "/public/css.php?file=",
        "js"     => $config->mainUrl . "/public/javascript.php?file=",
        "cssdir" => $config->mainUrl . "/templates/" . $config->activeTemplate . "/css/",
        "images" => $config->mainUrl . "/templates/" . $config->activeTemplate . "/images/",
        "views"  => $config->mainUrl . "/templates/" . $config->activeTemplate . "/views/",
    );

    $config->activeLanguage = array(
        "fr" => "Français",
        "en" => "English",
        "nl" => "Nederlands"
    );
    $config->randonKey = "f4qw87g6h89ertnmuytre";
    $config->main_upload_dir = "/var/www/ezcast/";

    $config->uploadDir = array(
        repository                     => $config->main_upload_dir . "/repository/",
        queues_recorder_uploads        => $config->main_upload_dir . "/queues/recorder_uploads",
        queues_recorder_uploads_ok     => $config->main_upload_dir . "/queues/recorder_uploads_ok",
        queues_recorder_uploads_failed => $config->main_upload_dir . "/queues/recorder_uploads_failed",

        queues_submit_uploads          => $config->main_upload_dir . "/queues/submit_uploads",
        queues_submit_uploads_ok       => $config->main_upload_dir . "/queues/submit_uploads_ok",
        queues_submit_uploads_failed   => $config->main_upload_dir . "/queues/submit_uploads_failed",

        queues_rendering               => $config->main_upload_dir . "/queues/rendering",
        queues_rendering_uploading     => $config->main_upload_dir . "/queues/rendering/uploading",
        queues_rendering_processed     => $config->main_upload_dir . "/queues/rendering/processed",
    );

    $config->date_format = "Y_m_d_H\hi";
    $config->qualities = array("high","low");
    $config->defaultQuality = $config->qualities[1];
    $config->recorders = array("new" => array("camrecord","sliderecord"),"old" => array("cam","slide"));
    $config->streamurl = $config->url . "/player/";