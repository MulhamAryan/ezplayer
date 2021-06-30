<?php

    function updateRecord(array $recordInfo){
        global $sys;
        $recordArray = array(
            "table" => Databases::records,
            "fields" => "title = '{$recordInfo["title"]}', description = '{$recordInfo["description"]}' where id = '{$recordInfo["id"]}'"
        );
        $update = $sys->update($recordArray);
        if($update == true){
            $success = true;
            $sys->updateCourseCache($recordInfo["course_id"]);
        }
        else{
            $success = false;
        }
        return $success;
    }