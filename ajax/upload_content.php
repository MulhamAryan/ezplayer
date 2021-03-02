<?php
    include "../config.php";
    require_once $config->directory["library"] . "/upload.php";

    $sys  = new System();
    $auth = new Authentication();
    $log  = new Log();

    $auth->requireLogin();
    $courseID = $sys->input("courseid",SET_INT);
    $courseInfo  = $auth->instance(CHK_COURSE,$courseID);
    if($courseInfo != false) {
        $permissions = $auth->getEnrollment(ENR_ACCESS_TYPE, $courseID);

        if (in_array("add", $permissions) == true && !empty($_FILES) && isset($_FILES)) {
            $intro        = $sys->input("intro",SET_STRING);
            $add_title    = $sys->input("add_title",SET_STRING);
            $credits      = $sys->input("credits",SET_STRING);
            $keepQuality  = $sys->input("keepQuality",SET_STRING);
            $ratio        = $sys->input("ratio",SET_STRING);
            $private      = ($sys->input("private",SET_INT) != false) ? 1 : 0;
            $title        = $sys->input("title",SET_STRING);
            $description  = $sys->input("description",SET_STRING);
            $type         = $sys->input("type",SET_STRING);
            $downloadable = $sys->input("downloadable",SET_INT);
            $user_id      = $auth->getInfo(LOGIN_USER_ID);
            $upload       = new Upload(queues_submit_uploads,$courseInfo,$user_id);

            if(empty($intro) || empty($add_title) || empty($credits) || empty($ratio) || empty($title) || empty($type) || empty($downloadable) && empty($_FILES)){
                $msg = array(
                    "error" => true,
                    "msg" => "Empty Fields found !"
                );
                echo json_encode($msg,JSON_PRETTY_PRINT);
            }
            else{
                $codec = array(
                    "super_highres" => 0,
                    "ratio"         => $ratio,
                    "intro"         => $intro,
                    "add_title"     => $add_title
                );
                $codec = json_encode($codec,JSON_PRETTY_PRINT);

                $submitInfo = array(
                    "title"        => $title,
                    "description"  => $description,
                    "record_type"  => $type,
                    "private"      => $private,
                    "codec"        => $codec,
                );

                $initUpload = $upload->initUpload($submitInfo);
                if($initUpload == "rec_dir_exist"){
                    $msg = array(
                        "error" => true,
                        "msg"   => $lang["rec_dir_exist"]
                    );
                    echo json_encode($msg,JSON_PRETTY_PRINT);
                    exit();
                }

                $uploadAns = $upload->uploadFile();
                $ans = json_encode($uploadAns,JSON_PRETTY_PRINT);

                if($uploadAns["error"] == true){
                    echo $ans;
                    exit();
                }
                else{
                    echo $ans;
                    exit();
                }
            }
        }
        else {
            //TODO INSERT LOG SYS
            $log->insertLog(array());
            $msg = array(
                "error" => true,
                "msg"   => $lang["permission_denied"]
            );
            echo json_encode($msg,JSON_PRETTY_PRINT);
            exit();
        }
    }
    else{
        $msg = array(
            "error" => true,
            "msg"   => $lang["no_course_found"]
        );
        echo json_encode($msg,JSON_PRETTY_PRINT);
        exit();
    }

