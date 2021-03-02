<?php

    define("ENR_ACCESS_TYPE",'ENR_ACCESS_TYPE');
    define("ENR_CAN_ACCESS",'ENR_CAN_ACCESS');

    define("SET_INT",'SET_INT');
    define("SET_FLOAT",'SET_FLOAT');
    define("SET_STRING",'SET_STRING');
    define("SET_ARRAY",'SET_ARRAY');
    define("SET_ALPHA_NUM",'SET_ALPHA_NUM');
    define("SET_PASSWORD",'SET_PASSWORD');

    define("CHK_COURSE",'CHK_COURSE');
    define("CHK_RECORD",'CHK_RECORD');

    define("USER_FULLNAME","USER_FULLNAME");
    define("USER_EMAIL","USER_EMAIL");
    define("USER_LOGIN","USER_NAME");

    class System extends Databases {
        const fileIndex  = "index.php";
        const fileCourse = "course.php";
        const fileRecord = "record.php";
        const fileSignup = "signup.php";
        const folderAdmin = "admin";
        const fileUserConf = System::folderAdmin . "/users/index.php";

        public function __construct(){
            parent::__construct();
        }

        public function input($param,$type,$convert = null){
            if($convert == true){
                $inputType = $param;
            }
            else{
                $input = array_merge($_GET,$_POST);
                $inputType = (isset($input[$param])) ? $input[$param] : false;
                $inputType = addslashes($inputType);
            }
            if(isset($inputType)) {
                switch ($type) {
                    case SET_INT:
                        return (int) $inputType;
                        break;

                    case SET_FLOAT:
                        return (float) $inputType;
                        break;

                    case SET_STRING:
                        return (string) $inputType;
                        break;

                    case SET_ARRAY:
                        return (array) $inputType;
                        break;

                    case SET_ALPHA_NUM:
                        return preg_replace('/[^A-Za-z0-9\-_\/]/', '', $inputType);
                        break;

                    case SET_PASSWORD:
                        return $this->encrypt($inputType);
                        break;

                    default:
                        try{
                            throw new Exception('Undefined case type input $sys->input("' . $param . '","' . $type . '"): '. $type .' for ' . $param . '');
                        }
                        catch (Exception $e){
                            $this->errorException($e);
                        }
                        break;
                }
            }
            else{
                return false;
            }
        }

        public function getUserCourses(array $array = null){
            if(isset($array["is_guest"]) == true){
                $courses = $this->getTable(Databases::courses);
                $query = "SELECT {$courses}.id as id, {$courses}.course_code as course_code, {$courses}.course_name as course_name FROM {$courses} where id = '{$array["id"]}' and token = '{$array["token"]}'";
            }
            else {
                if (empty($array["user_id"]))
                    $array["user_id"] = $_SESSION["user_id"];

                $enrollment = $this->getTable(Databases::enrollment);
                $courses = $this->getTable(Databases::courses);
                $query = "SELECT {$courses}.id as id, {$courses}.course_code as course_code, {$courses}.course_name as course_name FROM {$enrollment} inner JOIN {$courses} ON {$courses}.id={$enrollment}.courseid where {$enrollment}.userid = {$array["user_id"]}";
            }
            $result = $this->sql($query, "fetch");
            return $result;

        }

        public function getUserInfo(string $param, int $userid){
            $sqlArray = array(
                "table" => Databases::users,
                "fields" => array(
                    "id" => $userid
                )
            );
            $user = $this->select($sqlArray);
            if($user){
                switch ($param){
                    case USER_FULLNAME:
                        return $user["forename"] . " " . $user["surname"];
                        break;

                    case USER_LOGIN:
                        return $user["user_ID"];
                        break;

                    case USER_EMAIL:
                        return "";
                        break;

                    default:
                        return $param . " UNKNOWN FUNCTION NAME ?";
                        break;
                }
            }
            else{
                return $userid . " : UNKNOWN USER ID!";
            }
        }

        public static function deleteDirectory($path){
            $files = array_diff(scandir($path), array('.','..'));
            foreach ($files as $file) {
                (is_dir("$path/$file")) ? System::deleteDirectory("$path/$file") : unlink("$path/$file");
            }
            return rmdir($path);
        }

        public function updateUserCourseCache(int $userid){
            $userLogin = $this->getUserInfo(USER_LOGIN,$userid);
            $userCoursesFile = Cache::userDir . "/{$userLogin}/" . Cache::user_courses_menu;

            $this->updateCacheFile($userCoursesFile,$this->getUserCourses());
        }

        public function updateCourseCache(int $courseID){
            $reocordsFile = Cache::courseDir . "/{$courseID}/" . Cache::records_list;
            $courseFile   = Cache::courseDir . "/{$courseID}/" . Cache::courseInfo;
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
            //Update record list cache in course
            $records = $this->fetch($recordsInfoArray);
            $this->updateCacheFile($reocordsFile,$records);

            //Update course cache file
            $courseInfo = $this->select($courseInfoArray);
            $this->updateCacheFile($courseFile,$courseInfo);
        }

        public function errorException($param){
            die("<pre>{$param->getMessage()}<br>{$param->getTraceAsString()}</pre>");
        }

        public function redirect($link){
            header("LOCATION:{$link}");
        }
        public function encrypt($password){
            $password = password_hash($password,PASSWORD_DEFAULT);
            return $password;
        }

        public function verifyPassword($password,$hash){
            $pass = password_verify($password,$hash);
            return $pass;
        }

        public function oldCryptFunction($password){
            $des_seed = chr(rand(33, 126)) . chr(rand(33, 126));
            $encrypted_passwd = crypt($password, $des_seed);
            return $encrypted_passwd;
        }

        public function oldCryptCheck($clearpw,$encpw){
            $salt = substr($encpw, 0, 2);
            $cpasswd = crypt($clearpw, $salt);
            $fpasswd = rtrim($encpw);
            $ans = ($fpasswd == $cpasswd) ? true : false;
            return $ans;
        }

        public function generateToken(){
            $token = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz_-'),1,8);
            return $token;
        }

        public function url(array $array){
            $file = $array["file"];
            $i = 0;
            $params = "";
            if(!empty($array["parameters"])) {
                foreach ($array["parameters"] as $key => $val) {
                    $and = ($i != 0 ? "&" : "");
                    $interog = ($i == 0 ? "?" : "");
                    $parameters[] = "{$interog}{$and}{$key}={$val}";
                    $i++;
                }
                $params = implode("",$parameters);
            }

            $url = $this->config->url."/".$file.$params;
            return $url;
        }

        public function getIp(){
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
            }
            elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
            else {
                $ipAddress = $_SERVER['REMOTE_ADDR'];
            }
            return $ipAddress;
        }

        public function sendMail(array $mailInfo){
            $from    = $this->config->mail;
            $to      = $mailInfo["email"];
            $subject = $mailInfo["subject"];
            $message = $mailInfo["body"];

            $headers  = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= "From: <{$from}>" . "\r\n";

            mail($to,$subject,$message,$headers);
        }
    }
