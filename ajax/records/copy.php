<?php
    include "../../config.php";
    require_once $config->directory["library"] . "/templates.php";
    require_once $config->directory["library"] . "/authentication.php";
    global $lang;

    $sys         = new System();
    $auth        = new Authentication();
    $tmp         = new Templates();

    $coursesList = $sys->input("courseid",SET_ARRAY);
    $recordID    = $sys->input("recordid",SET_INT);
    $sessionID   = $sys->input("sessionID",SET_STRING);
    $type        = $sys->input("type",SET_STRING);

    if(!empty($coursesList[0])) {
        foreach ($coursesList as $courseid) {
            $course = $sys->input($courseid, SET_INT, true);
            $courseInfo = $auth->instance(CHK_COURSE, $course);
            $accessType = $auth->getEnrollment(ENR_ACCESS_TYPE,$course);

            if ($courseInfo && in_array("edit",$accessType)) {
                $recordInfo = $sys->select(["table" => Databases::records, "fields" => ["id" => $recordID]]);
                if (!empty($recordInfo)) {


                    $checkRecord = $sys->select(["table" => Databases::records, "fields" => ["title" => $recordInfo["title"], "course_id" => $course]]);
                    if (empty($checkRecord)) {
                        if($type == "copy") {
                            $recordInfo["course_id"] = $course;
                            $recordInfo["token"] = $sys->generateToken();
                            $recordInfo["history"] = "original_record:{$recordInfo["id"]}";
                            $recordInfo["addtime"] = time();
                            unset($recordInfo["id"]);
                            $sys->insert(Databases::records, $recordInfo);
                        }
                        elseif ($type == "move"){
                            $sys->update(["table" => Databases::records, "fields" => "course_id = '{$course}' where id = '{$recordID}'"]);
                        }
                        else{
                            exit();
                        }
                        echo $tmp->getSuccess($lang["record_copied"] . "<b>" . $courseInfo["course_name"] . "</b>");
                    } else {
                        echo $tmp->getError($lang["record_exist"] . "<b>" . $courseInfo["course_name"] . "</b>");
                    }
                    $sys->updateCourseCache($course);
                } else {
                    echo $tmp->getError($lang["no_record_found"]);
                }
            }
            else{
                echo $tmp->getError($lang["no_access"] . " <b>" .$courseInfo["course_name"] . "</b>");
            }
        }
    }
    else{
        echo $tmp->getError($lang["min_one_course"]);
    }