<?php
    include "../config.php";
    $sys = new System();
    $query = array(
        "fields" => "*",
        "table" => "courses"
    );
    $coursesInfoArray = array(
        "table" => Databases::courses,
    );
    $courses_list = $sys->fetch($coursesInfoArray);
    foreach ($courses_list as $cl) {
        if ($cl["date_created"] == "0000-00-00") {
            $cl["date_created"] = "2012-09-16";
        }
        $timestamp = strtotime($cl["date_created"]);

        $update_query = array(
            "table" => Databases::courses,
            "fields" => "date_created = '{$timestamp}' where course_code = '{$cl["course_code"]}'"
        );

        var_dump($sys->update($update_query));
    }