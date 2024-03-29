<?php

    global $sys;
    global $auth;
    global $tmp;
    global $courseID;
    global $canAccess;
    global $permissions;
    global $courseInfo;

    include $this->config->directory["library"] . "/course.php";

    $requireElements = array("requireLeftMenu" => true, "requireBody" => true);

    $tmp->getHeader($requireElements);

    if($canAccess == true) {
        if($this->config->cache["enabled"] == true){
            if($sys->checkCache(Cache::courseDir . "/{$courseID}/" . Cache::records_list) != true){
                $sys->updateCourseCache($courseID);
            }

            $records = $sys->getCache(Cache::courseDir . "/{$courseID}/" . Cache::records_list);
        }
        else{
            $recordsInfoArray = array(
                "table" => Databases::records,
                "fields" => array(
                    "course_id" => $courseID
                ),
                "keyword" => "order by id desc"
            );

            $courseInfoArray = array(
                "table" => Databases::courses,
                "fields" => array(
                    "id" => $courseID
                )
            );
            $records = $sys->fetch($recordsInfoArray);
        }
        $canAdd    = in_array("add",$permissions);
        $canEdit   = in_array("edit",$permissions);
        $canDelete = in_array("delete",$permissions);

        $countPrivateRecord = 0;
        $countPublicRecord  = 0;
        $countNotProcessed  = 0;
        if(!empty($records)){
            foreach ($records as $recordCount){
                if($recordCount["private"] == 1 && $recordCount["status"] == "processed")
                    $countPrivateRecord++;

                elseif($recordCount["private"] == 0 && $recordCount["status"] == "processed")
                    $countPublicRecord++;

                elseif ($recordCount["status"] != "processed" && $canEdit == true) {
                    if ($recordCount["status"] == "processing") {
                        $recordList["processing"][] = $recordCount;
                    }
                    elseif ($recordCount["status"] == "scheduled") {
                        $recordList["scheduled"][] = $recordCount;
                    }
                    else{
                        $recordList["error"][] = $recordCount;
                    }
                    $countNotProcessed++;
                }
            }
        }

        $edit = $sys->input("edit",SET_STRING);

        include $tmp->load("course/header.php");

        if($edit == "") {
            include $tmp->load("course/home.php");
        }
        elseif ($edit == "upload" && $canAdd == true){
            $sessionid = $sys->input("sessionid",SET_STRING);
            if($sessionid == $auth->getSessionID()) {
                include $tmp->load("course/upload_content.php");
            }
            else{
                $sys->redirect($this->url(array("file" => System::fileCourse, "parameters" => array("id" => $courseID))));
            }
        }
        elseif ($edit == "access" && $canEdit == true) {
            $sessionid = $sys->input("sessionid",SET_STRING);
            if($sessionid == $auth->getSessionID()) {
                if(isset($_POST["saveModifications"])){
                    $newAccessType = $sys->input("accessmenu",SET_INT);
                    $modifications = array(
                        "course_id" => $courseID,
                        "access" => $newAccessType
                    );
                    if(updateCourseAccess($modifications) == true){
                        $saved = true;
                        if($this->config->cache["enabled"] == true)
                            $courseInfo = $sys->getCache(Cache::courseDir . "/{$courseID}/" . Cache::courseInfo);
                        else {
                            $courseInfoArray = array(
                                "table" => Databases::courses,
                                "fields" => array(
                                    "id" => $courseID
                                )
                            );
                            $courseInfo = $sys->select($courseInfoArray);
                        }
                    }
                }
                include $tmp->load("course/editaccess.php");
            }
            else{
                $sys->redirect($this->url(array("file" => System::fileCourse, "parameters" => array("id" => $courseID))));
            }
        }
        else {
            $sys->redirect("index.php");
        }
        include $tmp->load("course/footer.php");
    }
    else{
        include $tmp->load("course/noaccess.php");
    }
    $tmp->getFooter($requireElements);