<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $config = new stdClass();
    $config->rendererid = 1;

    $config->cli = array(
        "ffmpeg" => "/usr/bin/ffmpeg",
        "ffprobe" => "/usr/bin/ffprobe",
        "php" => "/usr/bin/php",
        "screen" => "/usr/bin/screen",
        "mv" => "/bin/mv",
        "ssh" => "/usr/bin/ssh",
        "rsync" => "/usr/bin/rsync"
    );
    $config->maindir   = "/var/www/html/newezplayer/";
    //todo should be removed and integrated in directory
    $config->director = array(
        "library" => $config->maindir . "/ezrenderer/library",
        "config"  => $config->maindir . "/ezrenderer/config",
    );
    ///////
    $config->systemDir = $config->maindir . "/system";
    $config->directory = array(
        "config"      => $config->systemDir . "/config",
        "controllers" => $config->systemDir . "/controllers",
        "library"     => $config->systemDir . "/library",
        "languages"   => $config->systemDir . "/languages",
        "repository"  => "/var/lib/ezcast/repository/",
        "log"         => "/var/log/ezrenderer/",
        "clidir"      => $config->maindir . "/ezrenderer/cli/"
    );
    $config->recorderMainDir = "/var/www/recorderdata/movies/";
    $config->ezrecorderDir   = array(
        "local_processing"   => $config->recorderMainDir . "/local_processing/",
        "trash"              => $config->recorderMainDir . "/trash/",
        "upload_to_server"   => $config->recorderMainDir . "/upload_to_server/",
        "upload_ok"          => $config->recorderMainDir . "/upload_ok/"
    );

    include "/var/www/html/newezplayer/system/config/cache.php";
    include $config->director["config"] . "/database.php";
    include $config->director["config"] . "/encoders.php";
    include $config->director["config"] . "/ffmpeg.php";
    include $config->director["config"] . "/rsync.php";

    include "/var/www/html/newezplayer/system/library/cache.php"; //TODO TEMPORARY
    include "/var/www/html/newezplayer/system/library/databases.php"; //TODO TEMPORARY

    include $config->director["library"] . "/system.php";
    include $config->director["library"] . "/converter.php";
