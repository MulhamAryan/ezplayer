<?php
    $ulbid = htmlspecialchars($_GET["ulbid"]);
    $eventtime = htmlspecialchars($_GET["event_time"]);
    $employee_code = htmlspecialchars($_GET["employee_code"]);
    $badge_number = htmlspecialchars($_GET["badge_number"]);
    $first_name = htmlspecialchars($_GET["first_name"]);
    $last_name = htmlspecialchars($_GET["last_name"]);
    $reader = htmlspecialchars($_GET["reader"]);
    $reader_desc = htmlspecialchars($_GET["reader_desc"]);
    $api_key = htmlspecialchars($_GET["api_key"]);

    $apiKey = "XLZrGmP6FaOKQzOQ1gVWUQOA5DwoYKmm";
    if($api_key == $apiKey){
        $dir = "/tmp/auditoire_access/access.csv";
        $fp = fopen($dir, 'a');
        fwrite($fp, "event_time:{$eventtime};employee_code:{$employee_code};badge_number:{$badge_number};first_name:{$first_name};last_name:{$last_name};reader:{$reader};reader_desc:{$reader_desc};ulbid:{$ulbid};" . PHP_EOL);
        fclose($fp);
        echo "access_granted";
    }
    else{
        echo "WRONG_API_KEY";
    }

    //access.php?api_key=XLZrGmP6FaOKQzOQ1gVWUQOA5DwoYKmm&event_time=changed_event_time&employee_code=changed_employee_code&badge_number=changed_badge_number&first_name=changed_first_name&last_name=changed_last_name&reader=changed_reader&reader_desc=changed_reader_desc&ulbid=changed_ulbid