<?php
    include "../config.php";
    include $config->director["library"] . "/importer.php";
    $recordid = intval($argv[1]);

    $convert = new Converter();
    $convert->recordDirectory = "/var/lib/ezcast/repository/1/2020_10_13_10h44_PODC-I-000/";
    $convert->setRecordID($recordid);

    $convert->setStreamInfo("cam","high_cam/ffmpegmovie.m3u8");
    $encoder = $convert->getEncodersCmd(array("key" => "cam","value" => "low"));
    var_dump("{$config->cli["ffmpeg"]} -f concat -safe 0 -i {$convert->recordDirectory}/camrecord/high/concat.txt -f hls {$encoder} -threads 1 -fflags nobuffer -flags low_delay -strict experimental -r 25 -hls_list_size 0 -hls_wrap 0 -flags output_corrupt -start_number 1 {$convert->recordDirectory}/low_cam/ffmpegmovie.m3u8");
    /*$import = new Importer();

    $import->setRecord($recordid);
    echo $import->startImporting();*/

//    string(273) "-c:v libx264 -crf 23 -r 25 -vprofile baseline -preset slow -b:v 384k -maxrate 384k -bufsize 768k -threads 0 -ar 44100 -ac 2 -y   -pix_fmt yuv420p -acodec aac -vf "scale=iw*min(1280/iw\,720/(ih/1*1)):(ih/1*1)*min(1280/iw\,720/(ih/1*1)), pad=1280:720:(1280-iw)/2:(720-ih)/2" "
///usr/bin/ffmpeg -i /var/lib/ezcast/repository//1/2020_10_13_10h44_PODC-I-000/rendering/transcoded_ffmpegmovie.m3u8 -c:v libx264 -crf 23 -r 25 -vprofile baseline -preset slow -b:v 384k -maxrate 384k -bufsize 768k -threads 0 -ar 44100 -ac 2 -y   -pix_fmt yuv420p -acodec aac -vf "scale=iw*min(1920/iw\,1080/(ih/1*1)):(ih/1*1)*min(1920/iw\,1080/(ih/1*1)), pad=1920:1080:(1920-iw)/2:(1080-ih)/2"  -hls_time 10 -hls_list_size 0 -hls_wrap 0 -start_number 1  /var/lib/ezcast/repository//1/2020_10_13_10h44_PODC-I-000/rendering/low_cam/ffmpegmovie.m3u8  &>> /var/lib/ezcast/repository//1/2020_10_13_10h44_PODC-I-000/rendering/setRenderingCmd_low.log