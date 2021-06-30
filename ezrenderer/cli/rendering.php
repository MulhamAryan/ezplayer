<?php
    include "../config.php";
    $recordid = intval($argv[1]);

    $converter = new Converter();
    $converter->setRecordID($recordid);

    $converter->startRendering();