<?php
    include "../config.php";


    $sys = new System();

    $DB = new Databases();
    $currentlyRendering = $sys->getCurrentRendering();
    $rendererinfo       = $sys->getRendererInfo();

    echo "--------- Check if there is a waiting record from SUBMIT ---------" . PHP_EOL;
    if($currentlyRendering <= $rendererinfo["maxjob"]) {
        $submitWaitList = $DB->fetch(
            array(
                "table" => Databases::records,
                "fields" =>
                    array(
                        "status" => "scheduled",
                        "origin" => "SUBMIT"
                    )
            )
        );
        if(empty($submitWaitList)){
            echo "No scheduled recording found :)" . PHP_EOL;
        }
        else {
            foreach ($submitWaitList as $record) {
                if ($currentlyRendering != $rendererinfo["maxjob"]) {
                    $cmd = "{$config->cli["php"]} {$config->directory["clidir"]}/rendering.php {$record["id"]} > {$config->directory["log"]}/rendering-{$record["id"]}.log 2>&1 &";
                    exec($cmd);
                    echo "- {$record["id"]} Start rendering." . PHP_EOL;
                    $currentlyRendering++;
                    echo $currentlyRendering;
                } else {
                    break;
                }
            }
        }
    }
    else{
        echo "I'm currently busy rendering {$currentlyRendering} please try again later" . PHP_EOL;
    }
    echo "--------- Check if there is a waiting record from auditorium ---------" . PHP_EOL;

    $recordTable = $sys->getTable(Databases::records);

    $recorderWaitList = $DB->sql("SELECT * FROM {$recordTable} where status = 'scheduled' and origin != 'SUBMIT' order by id asc ","fetch");
    if(empty($recorderWaitList)){
        echo "No scheduled recording found :)" . PHP_EOL;
    }
    else{
        foreach ($recorderWaitList as $record){
            $cmd = "{$config->cli["php"]} {$config->directory["clidir"]}/import_auditorium.php {$record["id"]} > {$config->directory["log"]}/import-record-{$record["id"]}.log 2>&1 &";
            exec($cmd);
            echo "- {$record["id"]} : Start importing from auditorium." . PHP_EOL;

        }
    }

