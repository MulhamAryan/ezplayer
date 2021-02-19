<?php

    class Upload extends System{

        private $destination;
        private $recordDir;
        private $courseID;
        private $userID;
        private $courseCode;
        private $courseInfo;

        function __construct($destination,array $courseInfo,int $userID)
        {
            parent::__construct();
            $this->destination = $destination;
            $this->courseID    = $courseInfo["id"];
            $this->courseCode  = $courseInfo["course_code"];
            $this->courseInfo  = $courseInfo;
            $this->userID      = $userID;
            $this->recordDir   = date($this->config->date_format) . "_" . $this->courseCode;
            //TODO CHECK IF DIRS EXISTS
        }

        function initUpload(array $info){
            $auth = new Authentication();

            //1 - Initiates the upload/record


            $createRecDir  = $this->createRecordDir();
            $info["token"] = $this->setToken();
            $info["addtime"] = time();
            $info["user_id"] = $this->userID;
            $info["course_id"] = $this->courseID;
            $info["album"] = $this->courseID;
            $info["status"] = "initupload";
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
                    $file_extension = pathinfo($file["name"][$fileinfokey][0])["extension"];
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
                        $filename = $fileinfokey . ".mov";
                        move_uploaded_file($file["tmp_name"][$fileinfokey][0], $this->getUploadDir() . "/" . $filename);
                    }
                }
            }
            if (empty($msg)) {
                $msg = array(
                    "error" => false,
                    "msg" => $this->lang["file_uploaded_process"]
                );
                $this->finishUpload();
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

        public function setToken(){
            $token = "";
            for ($idx = 0; $idx < 8; $idx++) {
                $token.= chr(rand(65, 90));
            }

            return $token;
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

        private function finishUpload()
        {
            //Update status
            $this->update(array("table"=>Databases::records, "fields" => "status = 'processing' where id = '{$this->getUploadID()}'"));
            //Update cache
            $this->updateCourseCache($this->courseID);
        }

        private function createRecordDir(){
            $dir = $this->config->uploadDir[$this->destination] . "/" . $this->recordDir;
            if(!is_dir($dir)) {
                mkdir($dir, 0755, true);
                return true;
            }
            else {
                return false;
            }
        }

        private function getUploadDir(){
            return $this->config->uploadDir[$this->destination] . "/" . $this->recordDir;
        }

    }