<?php

    function updateCourseAccess(array $modifications){
        global $sys;
        global $auth;
        global $permissions;
        global $courseInfo;

        if(in_array("edit",$permissions)){
            $newHistory = array(
                "type" => "update_access",
                "userid" => $auth->getInfo(LOGIN_USER_ID),
                "old_value" => $courseInfo["anon_access"],
                "time" => time()
            );
            $newHistory = serialize($newHistory);
            $update = $sys->update(array("table" => Databases::courses, "fields" => "anon_access = '{$modifications["access"]}', history = '{$courseInfo["history"]},{$newHistory}' where id = '{$modifications["course_id"]}'"));
            $sys->updateCourseCache($modifications["course_id"]);
            return ($update == true ? true : false);
        }

    }
