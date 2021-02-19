<?php

    function regenerateToken(string $type,int $id){
        global $sys;
        global $auth;
        if($type == "course"){
            $permission = $sys->getEnrollment(ENR_ACCESS_TYPE,$id);
            if(in_array("edit",$permission)) {
                $courseSql = array(
                    "table" => Databases::courses,
                    "fields" => array(
                        "id" => $id
                    )
                );
                $select = $sys->select($courseSql);
                if ($select != false) {
                    $newToken = $sys->generateToken();
                    $newHistory = array(
                        "type" => "update_token",
                        "userid" => $auth->getInfo(LOGIN_USER_ID),
                        "time" => time(),
                        "old_token" => $select["token"]
                    );
                    $newHistory = serialize($newHistory);
                    $history = $select["history"] . "," . $newHistory;

                    $sys->update(array("table" => Databases::courses, "fields" => "token = '{$newToken}', history = '{$history}' where id = '{$id}'"));
                    $sys->updateCourseCache($id);
                    $ans = $newToken;
                } else {
                    $ans = false;
                }
            }
            else{
                $ans = false;
            }
        }
        elseif($type == "record"){
            $ans = $sys->generateToken();
        }
        return $ans;
    }