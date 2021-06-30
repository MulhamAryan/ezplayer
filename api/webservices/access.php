<?php
    include "../../config.php";
    include $config->directory["library"] . "/api.php";
    $sys = new System();
    $api = new Api();
    //$option = $sys->input("option", SET_STRING);
    $data = $sys->input("data",SET_ARRAY);
    $option = (isset($data["option"]) && !empty($data["option"])) ? $data["option"] : "";

    if($api->checkTokenAccess() == true){
        switch ($option) {
            case "create_courses":
                //TODO Create courses procedure
                echo json_encode($api->createCourses(),true);
                break;

            case "create_record":
                include "create_record/create_record.php";
                break;

            default:
                return false;
                break;
        }
    }
    else{
        http_response_code(403);
    }
