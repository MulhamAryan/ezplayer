<?php
    global $auth;
    global $tmp;
    global $type;
    global $sys;

    $recordType = $sys->input("recordType",SET_STRING);
    $quality    = $sys->input("quality",SET_STRING);
    $dir        = $sys->input("dir",SET_STRING);
    $file       = $sys->input("file",SET_STRING);
    $recordid   = $sys->input("recordid", SET_INT);

    $this->config->repository = "/var/www/html/projects/"; //TODO NEED TO BE REMOVED TEMPORARY VAR DIR
    $recordDir  = "{$this->config->repository}{$dir}/";
    $m3u8Name   = "ffmpegmovie.m3u8";
    $tsFileName = "ffmpegmovie";
    $metadata   = $recordDir . "_metadata.xml";


    //header("Access-Control-Allow-Origin: *");
    //header('Content-Type: application/octet-stream');

    if(!file_exists($metadata)){
        $playerFile = "{$this->config->repository}{$dir}/{$recordType}/{$quality}/{$m3u8Name}";
        if(!file_exists($playerFile)){
            exit();
        }
        exit();
    }