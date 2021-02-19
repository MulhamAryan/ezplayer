<?php

    /*function manualEnrollment(int $enrtype,array $arrayInfo = null){
        global $sys;
        global $auth;
        global $courseInfo;
        $token = $sys->input("token",SET_STRING);

        exit($token);
        if(!empty($token) && $courseInfo["token"] == $token && $enrtype != 0){
            //1 - If organization student
            $userid = $auth->getInfo(LOGIN_USER_ID);
            if(!empty($userid) && $enrtype == 1){
                $enrAns = enrollInCourse($userid, $courseInfo["id"], $courseInfo["anon_access"], $courseInfo["token"],1);
                $sys->updateUserCourseCache($userid);
            }
            //2 - If shared url need create temp account
            elseif(empty($userid) && $enrtype == 3){
                $userInfo = $auth->createUser($arrayInfo);

            }
            //3 - If public access to the course need to generate a temparory session
            elseif(empty($userid) && $courseInfo["anon_access"] == 4){
                //TODO Generate random guest session
            }
            //4 - If personalized access to the course need to generate a temparory session
            elseif(empty($userid) && $courseInfo["anon_access"] == 5){
                //TODO
            }
        }
        else{
            $enrAns = false;
        }
        return $enrAns;
    }*/

    function manualEnrollment(int $enrType, array $arrayInfo){
        global $sys;
        global $auth;

        $token = $sys->input("token",SET_STRING);

        $userid = $auth->getInfo(LOGIN_USER_ID);
        //If the course is shared $enrType => !0
        if(!empty($token) && ($arrayInfo["couse_token"] == $token || $arrayInfo["token"] == $token) && $enrType != 0 ) {
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

                $userInfo = $auth->createUser($userFields);
                $enrAns = enrollInCourse($userInfo["id"], $arrayInfo["course_id"], $arrayInfo["course_anon_access"], $token,1);
                $sys->updateUserCourseCache($userInfo["id"]);
                unset($userFields);
            }
            //$enrType => 4 - Public access to the course
            //If teacher decided to share his course with public
            elseif(empty($userid) && $enrType == 4){
                //Create temporary session
                $guest = $auth->createGuestSession();
                $enrAns = enrollInCourse($userInfo["id"], $arrayInfo["course_id"], $arrayInfo["course_anon_access"], $token,1);
                $sys->updateUserCourseCache($userInfo["id"]);
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
        if($userinfo["permissions"] == 1 or $enrollment["role"] == 3){
            return array("view","add","edit","delete");
        }
        elseif($enrollment["role"] == 2){
            return array("view","add","edit");
        }
        elseif ($enrollment["role"] == 1){
            return array("view");
        }
        else{
            return array();
        }
    }

    function canAccess($enrollment,$userinfo){
        $accessType = accessType($enrollment,$userinfo);
        if((in_array("view",$accessType)) == false){
            $enrAns = manualEnrollment(1);
        }
        else{
            $enrAns = true;
        }
        return $enrAns;
    }
