<?php

    function manualEnrollment(int $enrType, array $arrayInfo){
        global $sys;
        global $auth;
        if(empty($arrayInfo["token"])) {
            $token = $sys->input("token", SET_STRING);
        }
        else{
            $token = $arrayInfo["token"];
        }

        $userid = $auth->getInfo(LOGIN_USER_ID);
        //If the course is shared $enrType => !0
        if(!empty($token) && ($arrayInfo["token"] == $token || $arrayInfo["token"] == $token) && $enrType != 0 ) {
            //$enrType => 2 - all students of the institution
            //If the student is already logged and the course is "Only students of the institution"
            if (!empty($userid) && $enrType == 2) {
                $enrAns = enrollInCourse($userid, $arrayInfo["id"], $arrayInfo["anon_access"], $arrayInfo["token"],1);
                $sys->updateUserCourseCache($userid);
            }
            //$enrType => 3 - Create account is required before enrollment
            //If teacher decided to share his course with outside institution
            elseif(empty($userid) && $enrType == 3){
                $userFields = $arrayInfo;
                unset($userFields["course_id"]);
                unset($userFields["course_anon_access"]);
                unset($userFields["token"]);

                $userInfo = $auth->createUser($userFields);
                $enrAns = enrollInCourse($userInfo["id"], $arrayInfo["course_id"], $arrayInfo["course_anon_access"], $token,1);
                $mailInfo = array(
                    "subject" => "",
                    "email" => $userFields["usermail"],
                    "message" => ""
                );
                $sys->sendMail($mailInfo);
                //$sys->updateUserCourseCache($userInfo["id"]);
                unset($userFields);
            }
            else{
                $enrAns = false;
            }
        }
        else{
            $enrAns = false;
        }
        return $enrAns;
    }

    function enrollInCourse(int $userid, int $courseid, int $enroltype, string $enrolkey, int $role){
        //Ii0423_s
        global $sys;
        $sqlArray = array(
            "table" => Databases::users,
            "fields" => array(
                "id" => $userid
            )
        );
        $getUser = $sys->select($sqlArray);

        $sqlEnrArray = array(
            "table" => Databases::enrollment,
            "fields" => array(
                "userid" => $userid,
                "courseid" => $courseid
            )
        );
        $checkEnr = $sys->select($sqlEnrArray);

        if(!empty($getUser) && $checkEnr == false){
            $enrFields = array(
                "userid" => $userid,
                "courseid" => $courseid,
                "role" => $role,
                "enroltype" => $enroltype,
                "enrolkey" => $enrolkey,
                "enrolstart" => 1,
                "enrolend" => 1,
                "timecode" => time()
            );
            $insertEnr = $sys->insert(Databases::enrollment,$enrFields);
            if($insertEnr == true){
                return true;
            }
            else{
                return false;
            }
        }
        else {
            return false;
        }
    }

    function enrolRecord(){

    }

    function accessType($enrollment,$userinfo){
        global $sys;
        if($userinfo["permissions"] == 1 or $enrollment["role"] == 3){
            return array("view","add","edit","delete");
        }
        elseif($enrollment["role"] == 2){
            return array("view","add","edit");
        }
        elseif ($enrollment["role"] == 1){
            return array("view");
        }
        elseif ($enrollment["role"] == 0){
            $courseSql = array(
                "table" => Databases::courses,
                "fields" => array(
                    "id" => $enrollment["courseid"]
                )
            );
            $course = $sys->select($courseSql);
            if($course["anon_access"] == 4){
                return array("view");
            }

        }
        else{
            return array();
        }
    }

    function canAccess($enrollment,$userinfo){
        global $courseInfo;

        $accessType = accessType($enrollment,$userinfo);
        if((in_array("view",$accessType)) == false){
            $enrAns = manualEnrollment(1,$courseInfo);
        }
        else{
            $enrAns = true;
        }
        return $enrAns;
    }
