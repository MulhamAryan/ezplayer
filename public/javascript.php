<?php
    include "../config.php";
    header('Content-Type: text/javascript');
    require $config->directory["library"] . "/minifier.php";
    $fileName = preg_replace('/[^A-Za-z0-9.\/_\-]/', '', $_GET["file"]);

    $jsFile = "{$config->directory["templates"]}/{$config->activeTemplate}/js/{$fileName}";
    $minifier = new Minifier();
    echo $minifier->js($jsFile);
