<?php
    include "../config.php";
    $sys  = new System();
    //TODO Create repo variable
    $dirScan = new DirectoryIterator("/var/lib/ezcast/repository/");

    foreach ($dirScan as $file){
        $foldername = $file->getFilename();
        $foldername = str_replace("-pub","",$foldername);
        $foldername = str_replace("-priv","",$foldername);
        $courseInfoArray = array(
            "table" => Databases::courses,
            "fields" => array(
                "course_code" => $foldername
            )
        );
        $getCourse = $sys->select($courseInfoArray);
        if($file->isDir()) {
            $info = array(
                "course_id" => $getCourse["id"],
                "album" => "/var/lib/ezcast/repository/" . $file->getFilename(),
                "album_name" => $file->getFilename()
            );
            if (!empty($getCourse["id"])) {
                updateCourseValue($info);
                createRecord($info);
                unset($info);
            } else {
                createCourse($info);
                unset($info);
            }
        }
        unset($getCourse);
        unset($foldername);
    }

    function createCourse(array $info){
        global $sys;
        $albumRecords = new DirectoryIterator($info["album"]);
        $metadata = $info["album"] . "/_metadata.xml";
        if(file_exists($metadata)) {
            $readMetadata = simplexml_load_file($metadata);
            $course_code = $readMetadata->name;
            $course_name = addslashes($readMetadata->description);
            $courseInfoArray = array(
                "table" => Databases::courses,
                "fields" => array(
                    "course_code" => $course_code
                )
            );
            $checkExists = $sys->select($courseInfoArray);
            if($checkExists == false){
                if(!empty($readMetadata->downloadable) && $readMetadata->downloadable == 1)
                    $downloadable = 1;
                else
                    $downloadable = 0;

                if(!empty($readMetadata->anon_access) && $readMetadata->anon_access == "true")
                    $anon_access = 1;
                else
                    $anon_access = 0;

                $token = file_get_contents($info["album"]."/_token");
                $courseInfo = array(
                    "course_code" => "{$course_code}",
                    "course_code_public" => "",
                    "course_name" => "{$course_name}",
                    "date_created" => time(),
                    "token" => "{$token}",
                    "downloadable" => $downloadable,
                    "anon_access" => $anon_access,
                    "origin" => "external"
                );

                $sys->insert(Databases::courses,$courseInfo);
                echo "Course OK : " . $course_code . PHP_EOL;
            }
            $courseInfoArray = array(
                "table" => Databases::courses,
                "fields" => array(
                    "course_code" => $course_code
                )
            );
            $course = $sys->select($courseInfoArray);
            $infoRecord = array(
                "course_id" => $course["id"],
                "album" => $info["album"],
                "album_name" => $info["album_name"]
            );
            createRecord($infoRecord);
        }
    }

    function createRecord(array $info){
        global $sys;
        echo " --------- " . $info["album"]  . " --------- " . PHP_EOL;
        $albumRecords = new DirectoryIterator($info["album"]);
        foreach ($albumRecords as $recDir) {
            if ($recDir->isDir() && !$recDir->isDot()) {
                $infoRecord = array(
                    "recordpath" => $info["album"] . "/" . $recDir->getFilename()
                );
                if (file_exists($infoRecord["recordpath"] . "/_metadata.xml")) {
                    $readMetadata = simplexml_load_file($infoRecord["recordpath"] . "/_metadata.xml");

                    if (!empty($readMetadata->record_date)) {
                        $readMetadata->record_date = str_replace("_", "-", $readMetadata->record_date);
                        $readMetadata->record_date = str_replace("h", ":", $readMetadata->record_date);
                        $readMetadata->record_date = str_replace("m", ":", $readMetadata->record_date);
                        if (strpos($readMetadata->record_date, "s")) {
                            $record_date = explode("-", $readMetadata->record_date);
                            $recordDate = $record_date[0] . "-" . $record_date[1] . "-" . $record_date[2] . "-" . $record_date[3];
                        } else {
                            $recordDate = $readMetadata->record_date;
                        }
                    } else {
                        $recordDate = time();
                    }

                    echo $recordDate;

                    $title = checkMetadata(@$readMetadata->title);
                    $description = checkMetadata(@$readMetadata->description);
                    $origin = checkMetadata(@$readMetadata->origin);
                    //$user_id = 0;
                    $course_id = $info["course_id"];
                    $timestamp = (new DateTime($recordDate))->getTimestamp();
                    if (file_exists($infoRecord["recordpath"] . "/_token")){
                        $token = file_get_contents($infoRecord["recordpath"] . "/_token");
                        if(strpos($info["album"],"-pub")){
                            $private = 0;//FALSE
                        }
                        else{
                            $private = 1;//TRUE
                        }
                    }
                    else {
                        $token = "unknown";
                        $private = 1;//TRUE
                    }
                    $author = addslashes($readMetadata->author);
                    $filepath = $info["album_name"] . "/" . $recDir->getFilename(); //TODO
                    $record_type = $readMetadata->record_type;
                    $status = $readMetadata->status;
                    $language = $readMetadata->language;
                    $duration = $readMetadata->duration;
                    $downloadable = (empty($readMetadata->downloadable) && $readMetadata->downloadable == 1) ? 1 : 0;
                    $super_highres = (!empty($readMetadata->super_highres)) ? 1 : 0;
                    $ratio = (!empty($readMetadata->ratio)) ? (string) $readMetadata->ratio : NULL;
                    $intro = (!empty($readMetadata->intro)) ? (string) $readMetadata->intro : NULL;
                    $add_title = (!empty($readMetadata->add_title)) ? (string) $readMetadata->add_title : NULL;
                    $codec = array(
                        "super_highres" => $super_highres,
                        "ratio" => $ratio,
                        "intro" => $intro,
                        "add_title" => $add_title
                    );
                    $codec = json_encode($codec,true);
                    $codec = addslashes($codec);
                    $getUserID = $sys->sql("SELECT id,forename,surname from ezcast_users where CONCAT(forename, ' ' ,surname) like '{$author}'","select");
                    $user_id = ($getUserID != false) ? (int) $getUserID["id"] : 0;
                    $recordArray = array(
                        "title" => (string) $title,
                        "description" => (string) $description,
                        "origin" => (string) $origin,
                        "user_id" => $user_id,
                        "course_id" => $course_id,
                        "token" => (string) $token,
                        "album" => 0,
                        "filepath" => (string) $filepath,
                        "record_type" => (string) $record_type,
                        "status" => (string) $status,
                        "codec" => $codec,
                        "duration" => (int) $duration,
                        "downloadable" => $downloadable,
                        "deleted" => 0,
                        "private" => $private,
                        "history" => "",
                        "addtime" => $timestamp
                    );
                    ///echo $codec;
                    //$user = $sys->select(array("table"=>Databases::users,"condition"=>"where")))
                    $sys->insert(Databases::records,$recordArray);
                    echo "Record OK : " . $recDir->getFilename() . PHP_EOL;
                    unset($recordArray);
                    unset($title);
                    unset($description);
                    unset($origin);
                    unset($user_id);
                    unset($course_id);
                    unset($filepath);
                    unset($record_type);
                    unset($status);
                    unset($duration);
                    unset($downloadable);
                    unset($private);
                    unset($timestamp);
                    unset($super_highres);
                    unset($ratio);
                    unset($intro);
                    unset($add_title);
                }
            }
        }
    }

    function updateCourseValue(array $info){

    }

    function checkMetadata($val){
        if(!empty($val))
            return addslashes($val);
        else
            return "";
    }