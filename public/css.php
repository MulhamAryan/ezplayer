<?php
    include "../config.php";
    header('Content-Type: text/css');
    require $config->directory["library"] . "/minifier.php";
    $fileName = preg_replace('/[^A-Za-z0-9.\/_\-]/', '', $_GET["file"]);

    $jsFile = "{$config->directory["templates"]}/{$config->activeTemplate}/css/{$fileName}";
    $minifier = new Minifier();
    echo $minifier->css($jsFile);
