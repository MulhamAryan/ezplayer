<?php
    $m3u8Name   = "ffmpegmovie.m3u8";
    $tsFileName = "ffmpegmovie";
    $playerFile = "{$this->config->repository}{$dir}/{$recordType}/{$quality}/{$m3u8Name}";
    header("Access-Control-Allow-Origin: *");
    header('Content-Type: application/octet-stream');

    if(file_exists($playerFile)){
        //$playerFile = file_get_contents($playerFile);
        $playerFileContent = file($playerFile);

        //https://ezcasttest.ulb.ac.be/newezplayer/player.php?type=hls&recordid=7082&recordType=cam&quality=high&dir=2020_11_13_15h52_EDUC-E-520
        //$playerFile = str_replace($tsFileName,$this->config->url . "player.php?type=hls&recordid={$recordid}&recordType={$recordType}&quality={$quality}&dir={$dir}&file={$tsFileName}",$playerFile);
        //echo $playerFile;
        $i = 0;
        foreach ($playerFileContent as $pfc){
            if($i == 3){
                echo "#EXT-X-PLAYLIST-TYPE:VOD" . PHP_EOL;
                echo "#EXT-X-INDEPENDENT-SEGMENTS" . PHP_EOL;
                echo "#EXT-X-STREAM-INF:BANDWIDTH=150000,RESOLUTION=416x234,CODECS=\"avc1.42e00a,mp4a.40.2\"" . PHP_EOL;
            }
            $pfc = str_replace($tsFileName,$this->config->url . "player.php?type=hls&recordid={$recordid}&recordType={$recordType}&quality={$quality}&dir={$dir}&file={$tsFileName}",$pfc);
            echo $pfc;
            $i++;
        }
    }
    else{
        header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
    }