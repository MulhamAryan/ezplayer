<?php
    $m3u8Name   = "ffmpegmovie.m3u8";
    $tsFileName = "ffmpegmovie";

    $playerFile = "{$this->config->repository}{$dir}/{$recordType}/{$quality}/{$file}";

    header("Access-Control-Allow-Origin: *");
    header('Content-Type: application/octet-stream');

    if(file_exists($playerFile)){
        $tsPartition = file_get_contents($playerFile);
        echo $tsPartition;
    }