<?php
    include $config->directory["library"] . "/upload.php";
    $title        = $sys->input("title",SET_STRING);
    $description  = $sys->input("description",SET_STRING);
    $type         = $sys->input("type",SET_STRING);
    $private      = $sys->input("private",SET_INT);
    $user_id      = $sys->input("user_id",SET_INT);
    $origin       = $sys->input("origin", SET_STRING);
    $course_id    = $sys->input("course_id",SET_INT);
    $record_type  = $sys->input("record_type",SET_STRING);
    $duration     = $sys->input("duration",SET_INT);
    $record_dir   = $sys->input("record_dir", SET_STRING);

    $answer = array("error" => false);
    if(empty($title)) {
        $answer = array("error" => true, "msg" => "empty_title");
    }
    elseif (empty($user_id)) {
        $answer = array("error" => true, "msg" => "empty_userid");
    }
    elseif(empty($origin)){
        $answer = array("error" => true, "msg" => "empty_origin");
    }
    elseif(empty($course_id)){
        $answer = array("error" => true, "msg" => "empty_courseid");
    }
    elseif(empty($record_type)){
        $answer = array("error" => true, "msg" => "empty_recordtype");
    }
    elseif(empty($duration)){
        $answer = array("error" => true, "msg" => "empty_duration");
    }
    elseif (empty($record_dir)){
        $answer = array("error" => true, "msg" => "empty_record_dir");
    }
    else{
        if($record_type == "camslide"){
            $record_type = array("cam" => "cam.mov", "slide" => "slide.mov");
        }
        else{
            $record_type = array($record_type => "{$record_type}.mov");
        }
        $record_type = json_encode($record_type,true);
        $recordPath = $config->directory["repository"] . "/" . $course_id . "/" . $record_dir;
        $newRecordSql = array(
            "title" => (string) $title,
            "description" => (string) $description,
            "origin" => (string) $origin,
            "user_id" => $user_id,
            "course_id" => $course_id,
            "token" => (string) $sys->generateToken(),
            "album" => 0,
            "filepath" => (string) $recordPath,
            "record_type" => $record_type,
            "status" => Upload::scheduled,
            "private" => $private,
            "codec" => "",
            "duration" => $duration,
            "downloadable" => 1,
            "deleted" => 0,
            "history" => "",
            "addtime" => time(),
        );

        if(!is_dir($config->directory["repository"] . "/" . $course_id)) {

            if(!@mkdir($config->directory["repository"] . "/" . $course_id)){
                $error = error_get_last();
                $answer = array("error" => true, "msg" => $error["message"]);
            }

        }

        if(!is_dir($config->directory["repository"] . "/" . $course_id . "/" . $record_dir)){

            if(!@mkdir($config->directory["repository"] . "/" . $course_id . "/" . $record_dir)){
                $error = error_get_last();
                $answer = array("error" => true, "msg" => $error["message"]);
            }

        }

        if($answer["error"] != true){
            $sys->insert(Databases::records,$newRecordSql);
            $rsyncFolder = array(
                "camrecord" => array("high", "low"),
                "sliderecord" => array("high", "low")
            );
            $answer = array("error" => false,"msg" => "record_created");
        }
    }
    echo json_encode($answer,true) . PHP_EOL;

//rsync -a podclient@10.128.0.3:/var/www/recorderdata/movies/upload_to_server/2020_10_05_16h05_INFO-H-415/ /var/lib/ezcast/repository/1/2020_10_05_16h05_INFO-H-415
//curl -k "https://ezcasttest.ulb.ac.be/newezplayer/api/webservices/access.php?token=g4w8e6t7h86jr4t6t8k7i68y74" -d "data[option]=create_record" -d "title=title&description=description'&type=type&private=1&user_id=222&origin=auditorium&course_id=1&record_type=camslide&duration=445&record_dir=2020_10_05_16h05_INFO-H-415"