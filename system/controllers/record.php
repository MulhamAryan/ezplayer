<?php
    global $sys;
    global $auth;
    global $tmp;
    global $courseID;
    global $canAccess;
    global $permissions;
    global $recordInfo;

    $requireElements = array("requireLeftMenu" => true, "requireBody" => true);

    $tmp->getHeader($requireElements);
    $do = $sys->input("do", SET_STRING);

    $permitAccess = ($canAccess == true && $recordInfo["private"] == 0) ? true : false;
    $isModerator  = (($recordInfo["private"] == 0 || $recordInfo["private"] == 1) && in_array("edit",$permissions)) ? true : false;
    if($permitAccess || $isModerator) {
        $recordType = $recordInfo["record_type"];
        $recordType = json_decode($recordType, true);

        if (empty($do))
            include $tmp->load("record/home.php");

        elseif ($do == "edit" && $isModerator){
            $save = $sys->input("save_modification", SET_STRING);
            if(!empty($save)){
                require_once $sys->config->directory["library"] . "/functions/record.php";
                $recordTitle = $sys->input("record_title",SET_STRING);
                $recordDesc  = $sys->input("record_description", SET_STRING);

                $update = updateRecord(array("id" => $recordInfo["record_id"], "title" => $recordTitle, "description" => $recordDesc, "course_id" => $recordInfo["course_id"]));
                if($update == true) {
                    $sys->redirect($sys->url(array("file" => System::fileRecord, "parameters" => array("id" => $recordInfo["record_id"]))));
                }
                else{
                    $error = $this->lang["records"]["error_save"];
                }
            }
            include $tmp->load("record/edit.php");
        }
        else{
            $sys->redirect($sys->url(array("file" => System::fileIndex)));
        }
    }
    else{
        include $tmp->load("course/noaccess.php");
    }
    $tmp->getFooter($requireElements);