<?php

    function courseExists($instanceid){
        global $sys;
        if($sys->config->cache["enabled"] == true){
            $courseFile = Cache::courseDir . "/" . $instanceid . "/" . Cache::courseInfo;
            $getCourse = $sys->getCache($courseFile);
        }
        else {
            $courseInfoArray = array(
                "table" => Databases::courses,
                "fields" => array(
                    "id" => $instanceid
                )
            );
            $getCourse = $sys->select($courseInfoArray);
        }
        if ($getCourse == false) {
            $sys->redirect("index.php?error=course_not_found&id={$instanceid}");
        }
        return $getCourse;
    }

    function recordExists($instanceid){
        global $sys;
        $recordsTable = $sys->getTable(Databases::records);
        $coursesTable = $sys->getTable(Databases::courses);
        $getRecord = $sys->sql("SELECT *, record.id as record_id FROM {$recordsTable} as record INNER JOIN {$coursesTable} as course where record.id = {$instanceid} and course.id = record.course_id", "select");
        if ($getRecord["status"] == "processed") {
            return $getRecord;
        } else {
            (new System)->redirect("index.php?error=record_not_found&id={$instanceid}");
        }
    }