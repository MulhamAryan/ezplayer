<?php
    include "config.php";
    require_once $config->directory["library"] . "/templates.php";
    require_once $config->directory["library"] . "/authentication.php";
    require_once $config->directory["library"] . "/controller.php";

    $sys  = new System();
    $auth = new Authentication();
    $tmp  = new Templates();
    $ctrl = new Controller();

    if(empty($auth->getInfo(LOGIN_USER_ID))) {
        $id    = $sys->input("id", SET_INT);
        $token = $sys->input("token", SET_STRING);
        $type  = $sys->input("type", SET_STRING);
        if ($type == "course") {
            if($config->cache["enabled"] == true){
                $courseFile = Cache::courseDir . "/" . $id . "/" . Cache::courseInfo;
                $instance   = $sys->getCache($courseFile);
            }
            else {
                $courseSql = array(
                    "table" => Databases::courses,
                    "fields" => array(
                        "id" => $id,
                        "token" => $token,
                        "anon_access" => 3
                    )
                );
                $instance = $sys->select($courseSql);
            }
        }
        else{
            $sys->redirect("index.php");
        }
        if($instance != false) {
            $instance["type"] = $type;
            $ctrl->load("signup");
        }
        else{
            $sys->redirect($sys->url(array("file" => System::fileIndex)));
        }
    }
    else{
        $sys->redirect($sys->url(array("file" => System::fileIndex)));
    }