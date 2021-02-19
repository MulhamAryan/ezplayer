<?php
    $config->recordinfo = array(
        "type" => array(
            "cam" => array("cam"),
            "slide" => array("slide"),
            "camslide" => array("cam","slide"),
            "audio" => array("audio")
        ),
        "quality" => array("hd","sd")
    );

    $config->allowed_extensions = array(

        "extensions" => array(
            'mov', 'm4v', 'mp4', 'mpg4', 'mpg', 'nuv', 'ac3', 'mpeg4',
            'avi', 'mpeg', 'flv', 'wmv', 'mka', 'mks', 'rmvb', 'divx',
            'xvid', 'vob', 'mkv', 'f4v','wav','flac','mp3'),

        "mimetype"   => array(
            'text/x-fortran','image/jp2', 'application/x-dosexec',
            'application/octet-stream', 'video/quicktime', 'video/ogg',
            'video/h264','video/x-f4v','video/x-m4v','video/x-flv',
            'video/mp4','application/x-mpegURL', 'video/MP2T','video/3gpp',
            'video/3gpp2','video/x-msvideo','video/x-ms-wmv','video/x-msvideo',
            'video/mpeg', 'video/webm','audio/x-wav','audio/x-flac','audio/mpeg')
    );

    $config->titling = array(
        "intro"   => array("Semeur","false"),
        "credits" => array("ulb","false"),
        "title"   => array("FlyingTitle","false")
    );

    $config->maxFileSize = 2048000000; //2GB