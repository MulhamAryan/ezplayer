<?php

    $config->encoders = array(
        "original" => array(
            "enabled" => false,
            "r" => 25,
            "vprofile" => "main",
            "preset" => "slow",
            "b:v" => "2688k",
            "maxrate" => "2688k",
            "bufsize" => "5376k",
            "threads" => 0,
            "ar" => 44100,
            "ac" => 2,
            "y" => " ",
            "pix_fmt" => "yuv420p",
            "acodec" => "aac"
        ),
        "high" => array(
            "enabled" => true,
            "c:v" => "libx264",
            "crf" => 23,
            "r" => 25,
            "vprofile" => "main",
            "preset" => "slow",
            "b:v" => "4M",
            "maxrate" => "4M",
            "bufsize" => "8M",
            "threads" => 0,
            "ar" => 44100,
            "ac" => 2,
            "y" => "",
            "pix_fmt" => "yuv420p",
            "acodec" => "aac"
        ),
        "medium" => array(
            "enabled" => false,
            "r" => 25,
            "vprofile" => "main",
            "preset" => "slow",
            "b:v" => "1000k",
            "maxrate" => "1000k",
            "bufsize" => "2000k",
            "threads" => 0,
            "ar" => 44100,
            "ac" => 2,
            "y" => " ",
            "pix_fmt" => "yuv420p",
            "acodec" => "aac"
        ),
        "low" => array(
            "enabled" => true,
            "c:v" => "libx264",
            "crf" => 23,
            "r" => 25,
            "vprofile" => "baseline",
            "preset" => "slow",
            "b:v" => "384k",
            "maxrate" => "384k",
            "bufsize" => "768k",
            "threads" => 0,
            "ar" => 44100,
            "ac" => 2,
            "y" => " ",
            "pix_fmt" => "yuv420p",
            "acodec" => "aac"
        )
    );

    $config->fileExtensions = array("mp4","mov","m3u8","mp3");