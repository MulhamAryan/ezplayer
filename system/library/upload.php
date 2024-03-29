<?php

    class Upload extends System{

        private $destination;
        private $recordDir;
        private $courseID;
        private $userID;
        private $courseCode;
        private $courseInfo;
        private $recordType;
        private $recordPath;

        const initUpload    = "initupload";
        const processed     = "processed";
        const processing    = "processing";
        const scheduled     = "scheduled";
        const failMoveFile  = "failmovefile";
        const permitedFiles = array("ts","mov","m3u","m3u8");
        const excludedFiles = array();

        function __construct($destination,array $courseInfo,int $userID)
        {
            parent::__construct();

            $this->recordType  = array();
            $this->destination = $destination;
            $this->courseID    = $courseInfo["id"];
            $this->courseCode  = $courseInfo["course_code"];
            $this->courseInfo  = $courseInfo;
            $this->userID      = $userID;
            $this->recordDir   = $courseInfo["id"] . "/" . date($this->config->date_format) . "_" . $this->courseCode;
            //TODO CHECK IF DIRS EXISTS
        }

        function initUpload(array $info){
            $auth = new Authentication();

            //1 - Initiates the upload/record


            $createRecDir  = $this->createRecordDir();
            $info["token"] = $this->generateToken();
            $info["addtime"] = time();
            $info["user_id"] = $this->userID;
            $info["course_id"] = $this->courseID;
            $info["album"] = $this->courseID;
            $info["status"] = Upload::initUpload;
            $info["downloadable"] = $this->courseInfo["downloadable"];
            $info["filepath"] = "filepath"; //TODO Filepath sys

            if($this->destination == queues_submit_uploads) {
                $info["origin"] = "SUBMIT";
            }
            else{
                $info["origin"] = "UNKNONW";
            }

            if($createRecDir == false){
                return "rec_dir_exist";
            }
            else {
                //2 - Create record in db
                $this->insert(Databases::records, $info);

                //3 - Create Metadata file for rendering
                $recordInfoArray = array(
                    "table" => Databases::records,
                    "keyword" => "order by id desc"
                );
                $record = $this->select($recordInfoArray);
                //TODO @deprecated should be removed next versions STORED IN DB !
                $codec                 = json_decode($info["codec"], true);
                $info["id"]            = $record["id"];
                $info["course_name"]   = $this->courseInfo["course_name"];
                $info["author"]        = $auth->getInfo(LOGIN_FULLNAME);
                $info["netid"]         = $auth->getInfo(LOGIN_USER_ID);
                $info["record_date"]   = date($this->config->date_format);
                $info["super_highres"] = $codec["super_highres"];
                $info["intro"]         = $codec["intro"];
                $info["add_title"]     = $codec["add_title"];
                $info["ratio"]         = $codec["ratio"];
                //////////////////////////////////////////////////
                if(!empty($this->config->recordinfo["type"][$info["record_type"]])){
                    foreach ($this->config->recordinfo["type"][$info["record_type"]] as $recType){
                        $info["submitted_" . $recType] = $recType . ".mov";
                    }
                }
                $this->setMetadata($info);
            }
        }

        function uploadFile()
        {
            $log = new Log();
            $msg = array();
            foreach ($_FILES as $file) {
                foreach ($file["name"] as $fileinfokey => $fileinfovalue) {
                    $file_extension = pathinfo($file["name"][$fileinfokey][0]);
                    $file_extension = $file_extension["extension"];
                    if (!in_array($file_extension, $this->config->allowed_extensions["extensions"])) {
                        //TODO INSERT LOG SYS
                        $logError = array(
                            "userid" => $this->userID,
                            "courseid" => $this->courseID,
                            "place" => "upload_file",
                            "type" => "file_upload_error",
                            "info" => $file["name"][$fileinfokey][0],
                        );
                        $this->destroyUpload();
                        $log->insertLog($logError);
                        $msg = array(
                            "error" => true,
                            "msg" => $this->lang["incorrect_file_format"] . " : " . $file["name"][$fileinfokey][0]
                        );
                        return $msg;
                        break;
                    } elseif ($file["size"][$fileinfokey][0] > $this->config->maxFileSize) {
                        $logError = array(
                            "userid" => $this->userID,
                            "courseid" => $this->courseID,
                            "place" => "upload_file",
                            "type" => "file_size_big",
                            "info" => $file["name"][$fileinfokey][0] . " File size : " . $file["size"][$fileinfokey][0],
                        );
                        $log->insertLog($logError);
                        $this->destroyUpload();
                        $msg = array(
                            "error" => true,
                            "msg" => $this->lang["incorrect_file_size"] . " : " . $file["name"][$fileinfokey][0]
                        );
                        return $msg;
                        break;
                    } else {
                        $filename = $this->formatFileName("{$fileinfokey}-{$file["name"][$fileinfokey][0]}");
                        $fileupload = move_uploaded_file($file["tmp_name"][$fileinfokey][0], $this->getUploadDir() . "/" . $filename);
                        if($fileupload == false){
                            $msg = array(
                                "error" => true,
                                "msg" => $this->lang["incorrect_file_format"] . " : " . $file["name"][$fileinfokey][0]
                            );
                            $this->finishUpload(Upload::failMoveFile);
                            return $msg;
                            break;
                        }
                        else{
                            $this->recordType[$fileinfokey] = $filename;
                        }
                    }
                }
            }
            //$this->
            if (empty($msg)) {
                $this->finishUpload(Upload::scheduled);
                $msg = array(
                    "error" => false,
                    "msg" => $this->lang["file_uploaded_process"]
                );
                return $msg;
            }
            else{
                $msg = array(
                    "error" => true,
                    "msg" => $this->lang["unknown_problem"]
                );
                return $msg;
            }
        }

        private function setMetadata(array $info)
        {
            $xmlstr = "<?xml version='1.0' standalone='yes'?>\n<metadata>\n</metadata>\n";
            $xml = new SimpleXMLElement($xmlstr);
            foreach ($info as $key => $value) {
                $xml->addChild($key, htmlspecialchars($value));
            }
            $xml_txt = $xml->asXML();
            $res = file_put_contents($this->getUploadDir() . "/metadata.xml", $xml_txt, LOCK_EX);
            //did we write all the characters
            if ($res != strlen($xml_txt)) {
                return false;
            }

            return true;
        }

        private function getUploadID(){
            $recDir      = $this->getUploadDir();
            $metadata    = $recDir . "/metadata.xml";
            $xmlmetadata = simplexml_load_file($metadata);
            return $xmlmetadata->id;
        }

        private function destroyUpload(){
            if(is_dir($this->getUploadDir())){
                $this->deleteDirectory($this->getUploadDir());
            }
            if(!empty($this->getUploadID())){
                $this->delete(array("table"=>Databases::records,"condition"=> "id = {$this->getUploadID()}"));
            }

        }

        private function finishUpload($status)
        {
            $recordType = json_encode($this->recordType,true);
            $recordPath = $this->recordDir;
            //Update status
            $this->update(array("table"=>Databases::records, "fields" => "status = '{$status}', record_type = '{$recordType}', filepath = '{$recordPath}' where id = '{$this->getUploadID()}'"));
            //Update cache
            $this->updateCourseCache($this->courseID);
        }

        private function createRecordDir(){
            $dir = $this->config->uploadDir["repository"] . "/" . $this->recordDir;
            if(!is_dir($dir)) {
                mkdir($dir, 0755, true);
                return true;
            }
            else {
                return false;
            }
        }

        private function getUploadDir(){
            return $this->config->uploadDir["repository"] . "/" . $this->recordDir;
        }

        private function formatFileName(string $filename){
            $fileArray = explode('.',$filename);
            $extension = end($fileArray);
            $filename = preg_replace('/[^a-zA-Z0-9_-]+/', '-', strtolower($filename)) . ".{$extension}";
            return $filename;
        }

        public function rsyncFromRecorder(string $repository,array $serverinfo)
        {
            $excludedFile = "";
            if (!empty(self::excludedFiles)) {
                foreach (self::excludedFiles as $file) {
                    $excluded[] = "--exclude={$file}";
                }
                $excludedFile = implode(" ",$excluded);
            }

            $cmd = $this->config->cli["rsync"] .
                " -azvP {$excludedFile} --rsync-path=
                \"mkdir -p {$repository} && rsync
                \" {$serverinfo["username"]}@{$serverinfo["ip"]}:{$serverinfo["repository"]}  >> log.log  2>&1 &";
            return $cmd;
        }
    }