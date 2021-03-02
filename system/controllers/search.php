<?php
    global $search, $tmp, $sys, $auth;
    $requireElements = array("requireLeftMenu" => true, "requireBody" => true);

    $tmp->getHeader($requireElements);

    $permission = $auth->getInfo(LOGIN_PERMISSIONS); // IF 0 -> Normal user and search in cache file, 1 -> Administrator and search in databases
    $permission = 0;
    if($permission == 0){
        $courseCache = $sys->getUserCourses();
        echo "<pre>";
        foreach ($courseCache as $course){
            $courses[] = $course["id"];
        }
        if(!empty($courses)){
            var_dump($courses);
        }
        echo "</pre>";
    }
    include $tmp->load("search.php");

    $tmp->getFooter($requireElements);