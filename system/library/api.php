<?php

    class Api extends Authentication{

        /**
         * @var array|bool|false|float|int|string|string[]|null
         */
        private $getData;

        public function __construct()
        {
            parent::__construct();
            $this->getData = false;
        }

        public function checkTokenAccess()
        {
            $token       = $this->input("token",SET_STRING);
            $access_type = $this->input("access_type",SET_STRING);
            $clientIp    = (filter_var($_SERVER['REMOTE_ADDR'],FILTER_VALIDATE_IP) ? $_SERVER['REMOTE_ADDR'] : false);

            $getAccess = $this->select(array("table" => Databases::api_tokens, "fields" => array("token" => $token, "hostip" => $clientIp)));
            if($getAccess != false) {
                $accessType = unserialize($getAccess["access_type"]);
                if(in_array("all",$accessType) || in_array($access_type,$accessType)){
                    $answer = true;
                    $this->getData = $this->input("data", SET_ARRAY);
                }
                elseif (in_array("upload_from_auditorium",$accessType) || in_array($access_type,$accessType)){
                    $answer = true;
                }
                else{
                    $answer = array("error" => "unauthorized_access_option", "msg" => $this->lang["api"]["unauthorized_access"]);
                }
            }
            else{
                $answer = array("error" => "unauthorized_access_token", "msg" => $this->lang["api"]["unauthorized_access_option"]);
            }
            return $answer;
        }

        public function createCourses($coursesArray = array())
        {
            if(!empty($coursesArray)){
                $this->getData = $coursesArray;
            }
            if($this->getData != false){

                //foreach ($this->getData as $courses){
                    unset($this->getData["option"]);
                    $checkCourse = $this->select(array("table" => Databases::courses, "fields" => array("course_code" => $this->getData["course_code"])));
                    if($checkCourse == false){
                        $this->getData["token"] = $this->generateToken();
                        $createCourse = $this->insert(Databases::courses,$this->getData);
                        $status = ($createCourse == true ? "success" : "error");
                    }
                    else{
                        if($this->getData["course_name"] != $checkCourse["course_name"]){
                            $updateArrayFields["course_name"]["key"] = "course_name";
                            $updateArrayFields["course_name"]["value"] = $this->getData["course_name"];
                        }
                        if(!empty($updateArrayFields)){
                            $updateCourse = $this->update(array("table" => Databases::courses,"fields" => "{$updateArrayFields["course_name"]["key"]} = '{$updateArrayFields["course_name"]["value"]}' where id = {$checkCourse["id"]}"));
                        }
                        else{
                            $updateCourse = true;
                        }
                        $status = ($updateCourse == true ? "success" : "error");
                    }
                    //$answerStatus[] = array("course_code" => $this->getData["course_code"], "status" => $status);
                    //$answerStatus = "success";
                //}
            }
            else{
                $status = "error";
            }
            return $status;
        }
    }