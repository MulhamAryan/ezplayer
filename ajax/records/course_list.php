<?php
    require "../../config.php";
    require_once $config->directory["library"] . "/templates.php";
    require_once $config->directory["library"] . "/authentication.php";
    global $lang;

    $sys  = new System();
    $auth = new Authentication();

    $auth->requireLogin();

    $recordID  = $sys->input("id",SET_INT);
    $sessionID = $sys->input("sessionID",SET_STRING);
    $type      = $sys->input("type", SET_STRING);
    if($type == "copy"){
        $type = "copy";
        $title = $lang["copy_record_to"];
    }
    else{
        $type = "move";
        $title = $lang["move_record_to"];
    }
    $auth->checkSessionID($sessionID);
    //TODO Create course instance to check access
    $recordInfoArray = array(
        "table" => Databases::records,
        "fields" => array(
            "id" => $recordID
        )
    );
    $record = $sys->select($recordInfoArray);
    echo "<b>{$record["title"]}</b><br>";

    echo $title . "<hr>";
    $enrollment  = $sys->getTable(Databases::enrollment);
    $courses     = $sys->getTable(Databases::courses);
    $course_list = $sys->sql("SELECT course.id as id, course.course_code as course_code, course.course_name as course_name FROM {$enrollment} as enrollment INNER join {$courses} as course on course.id = enrollment.courseid and course.id != '{$record["course_id"]}' and enrollment.userid = '{$auth->getInfo(LOGIN_USER_ID)}'","fetch");
    echo '<div class="list-group shadow-sm">';
    echo '<input type="hidden" value="' . $recordID . '" name="recordid">';
    echo '<input type="hidden" value="' . $type . '" name="cmdtype">';
    foreach ($course_list as $course){
        $accessType = $sys->getEnrollment(ENR_ACCESS_TYPE,$course["id"]);
        if(in_array("edit",$accessType)) {
            echo '<span href="#" class="list-group-item list-group-item-action"><label>';
            echo '<input type="' . ($type == "copy" ? 'checkbox' : 'radio') . '" name="courseid[]" value="' . $course["id"] . '" class="custom-control-input"> ';
            echo $course["course_code"] . ' - ' . $course["course_name"] . '</label></span>';
        }
    }
    echo '<div id="' . $type . '_ans"></div></div>';