<?php

    function createCourse(array $courseArray){
        global $sys;
        $courseSql = array(
            "table" => Databases::courses,
            "fields" => array(
                "course_code" => $courseArray["course_code"]
            )
        );
        $checkCourse = $sys->select($courseSql);
        if($checkCourse == false){
            $courseArray["course_code"] = strtoupper($courseArray["course_code"]);
            $inserCourse = $sys->insert(Databases::courses,$courseArray);
            if($inserCourse == true) {
                $checkCourse = $sys->select($courseSql);
                $sys->updateCourseCache($checkCourse["id"]);
                return "course_created";
            }
            else{
                return "course_not_created";
            }
        }
        else {
            return $checkCourse;
        }
    }

    function updateAllCoursesCache(){
        global $sys;
        $coursesArray = array(
            "table" => Databases::courses,
        );
        $courses = $sys->fetch($coursesArray);
        foreach ($courses as $cours){
            $sys->updateCourseCache($cours["id"]);
        }
    }