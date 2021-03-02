<?php
    include "../../config.php";
    include "../configurations.php";

    $requireElements = array("requireLeftMenu" => true, "requireBody" => true);
    $tmp->getHeader($requireElements);
    $courseTable = $sys->getTable(Databases::courses);
    $enrollments = $sys->getTable(Databases::enrollment);
    $usersTable  = $sys->getTable(Databases::users);
    $search = $sys->input("courseName",SET_STRING);
    $do     = $sys->input("do",SET_STRING);
    $page   = $sys->input("page",SET_INT);
    $where = "";
    $perPage = 25;

    if($page < 1) $page = 1;
    if(!empty($search)){
        $where = "where course_code like '%{$search}%' or course_name like '%{$search}%' or id like '%{$search}%'";
    }

    $countCourses = $sys->sql("SELECT COUNT(*) as coursesnb from {$courseTable}","select");
    $totalPages = ceil($countCourses["coursesnb"]/$perPage);
    $startFrom = ($page * $perPage) - $perPage;
    if($totalPages > 5){
        $lastMax  = $totalPages;
        $totalPages = 5;
    }

    if($do == "add"){
        require_once $config->directory["library"] . "/functions/courses.php";

        $courseCode  = $sys->input("addCourseCode",SET_STRING);
        $courseName  = $sys->input("addCourseName",SET_STRING);
        $courseOrigin = $sys->input("origin",SET_STRING);
        if(!empty($courseCode) && !empty($courseName)){
            $courseArray = array(
                "course_code" => $courseCode,
                "course_code_public" => "",
                "course_name" => $courseName,
                "shortname" => "",
                "token" => $sys->generateToken(),
                "in_recorders" => 1,
                "has_albums" => 0,
                "downloadable" => 1,
                "anon_access" => 0,
                "date_created" => time(),
                "origin" => $courseOrigin,
                "history" => serialize("createdby:{$auth->getInfo(LOGIN_USER_ID)}")
            );
            $checkCourse = createCourse($courseArray);

            if($checkCourse == "course_created"){
                $getCreatedCourse = $sys->select(array("table" => Databases::courses, "fields" => array("course_code" => $courseCode)));
                $sys->redirect($sys->url(array("file" => System::fileCourse, "parameters" => array("id" => $getCreatedCourse["id"],"edit" => "enrolled", "sessionid" => $auth->getSessionID()))));
            }
            elseif ($checkCourse == "course_not_created"){
                $error = "Unknonw error !";
            }
            else{
                $error = "Course Exists ! <a href=\"{$sys->url(array("file" => System::fileCourse, "parameters" => array("id" => $checkCourse["id"])))}\" target=\"_blank\">{$checkCourse["course_code"]} - {$checkCourse["course_name"]}</a>";
            }
        }
        include $tmp->load("admin/courses/add.php");
    }
    $courses = $sys->sql("SELECT * FROM {$courseTable} {$where} order by id asc LIMIT {$startFrom}, {$perPage}","fetch");
    include $tmp->load("admin/courses/index.php");
    $tmp->getFooter($requireElements);