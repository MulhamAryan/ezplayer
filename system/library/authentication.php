<?php
    define("LOGIN_USER_ID",'LOGIN_USER_ID');
    define("LOGIN_USER_LOGIN",'LOGIN_USER_LOGIN');
    define("LOGIN_FULLNAME",'LOGIN_FULLNAME');
    define("LOGIN_PERMISSIONS",'LOGIN_PERMISSIONS');
    define("LOGIN_FORNAME",'LOGIN_FORNAME');
    define("LOGIN_LASTNAME",'LOGIN_LASTNAME');
    define("LOGIN_IS_GUEST",'LOGIN_IS_GUEST');
    class Authentication extends System {

        /**
         * @var Databases
         */
        const ACCESSLOGINPERMISSIONS = array(0,1); //0- Simple User, 1- Admin

        private $db;

        public function __construct(){
            parent::__construct();
            $this->db = $this->DBDriver;
        }

        public function checkLoginInfo(String $username,String $userpass)
        {
            $usersTable = $this->getTable(Databases::users);
            $userinfo = $this->db->sql("SELECT * FROM {$usersTable} where user_ID = '{$username}' and permissions IN ('" . implode("','", Authentication::ACCESSLOGINPERMISSIONS) . "')", "select");
            if ($userinfo != false){
                $oldpassCheck = $this->oldCryptCheck($userpass, $userinfo["recorder_passwd"]);

                //Update old password crypt if true
                if ($oldpassCheck == true) {
                    $newPassword = (string)$this->encrypt($userpass);
                    $updatequery = array(
                        "table" => Databases::users,
                        "fields" => "recorder_passwd = '{$newPassword}', updated_password = 1 where id='{$userinfo["id"]}'"
                    );
                    $this->db->update($updatequery);
                }

                //Auto load authentications methods
                $authmethod = $this->config->directory["library"] . "/authentications/";
                foreach (new DirectoryIterator($authmethod) as $methodFile) {
                    if (!$methodFile->isDot()) {
                        $accessAnswer = require_once $authmethod . $methodFile->getFilename();
                        if ($accessAnswer == "access_granted") {
                            break;
                        } elseif ($accessAnswer == "email_activation_needed") {
                            break;
                        }
                    }
                }
                if ($accessAnswer == "access_granted") {
                    $userInfoArray = array(
                        "table" => Databases::users,
                        "fields" => array(
                            "user_ID" => $username
                        )
                    );
                    $userinfo = $this->db->select($userInfoArray);
                    $userArrayInfo = array(
                        "user_id" => $userinfo['id'],
                        "user_login" => $userinfo['user_ID'],
                        "forename" => $userinfo['forename'],
                        "surname" => $userinfo['surname'],
                        "permissions" => $userinfo['permissions'],
                    );
                    $this->createSession($userArrayInfo);
                    return $accessAnswer;
                } else {
                    return $accessAnswer;
                }
            }
            else{
                return "access_not_granted";
            }
        }

        public function createSession(array $arrayUserInfo) {
            if(isset($arrayUserInfo["is_guest"]) == true){
                $_SESSION["is_guest"] = true;
            }
            else{
                $_SESSION["is_guest"] = false;
            }
            $_SESSION["is_logged"]   = true;
            $_SESSION["user_id"]     = $arrayUserInfo["user_id"];
            $_SESSION["user_login"]  = $arrayUserInfo["user_login"];
            $_SESSION["forename"]    = $arrayUserInfo["forename"];
            $_SESSION["surname"]     = $arrayUserInfo["surname"];
            $_SESSION["permissions"] = $arrayUserInfo["permissions"];

            $this->setSessionID();

            if($_SESSION["is_guest"] == false) {
                $this->setCache(Cache::userDir . "/{$arrayUserInfo["user_login"]}/" . Cache::user_courses_menu,$this->getUserCourses());
            }
            else{
                $courseEnrollment = array(
                    "type" => $arrayUserInfo["enrType"],
                    "id" => $arrayUserInfo["id"],
                    "token" => $arrayUserInfo["token"]
                );

                $this->setCache(Cache::guestDir . "/{$arrayUserInfo["user_login"]}/" . Cache::user_courses_menu,$this->getUserCourses($arrayUserInfo));
                $this->setCache(Cache::guestDir . "/{$arrayUserInfo["user_login"]}/" . Cache::guestEnrollments,$courseEnrollment);
            }
        }

        public function createUser(array $array){

            $insertUser = $this->db->insert(Databases::users,$array);
            if($insertUser == true){
                $arraySql = array(
                    "table" => Databases::users,
                    "fields" => array(
                        "user_ID" => $array["user_ID"]
                    )
                );
                $createdUser = $this->select($arraySql);
            }
            else{
                $createdUser = false;
            }
            return $createdUser;
        }

        public function isLogged(){
            return (isset($_SESSION["is_logged"]) == true) ? true : false;
        }

        public function getInfo(string $param,int $userid = null){
            if($this->isLogged()) {
                if ($userid == 0)
                    $userid = $_SESSION["user_id"];

                switch ($param) {
                    case LOGIN_USER_ID:
                        return (int) $_SESSION["user_id"];
                        break;

                    case LOGIN_USER_LOGIN:
                        return (string) $_SESSION["user_login"];
                        break;

                    case LOGIN_FULLNAME:
                        return (string) $_SESSION["forename"] . " " . $_SESSION["surname"];
                        break;

                    case LOGIN_FORNAME:
                        return (string) $_SESSION["forename"];
                        break;

                    case LOGIN_LASTNAME:
                        return (string)  $_SESSION["surname"];
                        break;

                    case LOGIN_PERMISSIONS:
                        return (int) $_SESSION["permissions"];
                        break;

                    case LOGIN_IS_GUEST:
                        return (int) $_SESSION["is_guest"];
                        break;

                    default:
                        try {
                            throw new Exception("Unknown function name (getInfo({$userid},{$param}))");
                        } catch (Exception $e) {
                            $this->errorException($e);
                        }
                        break;
                }
            }
        }

        public function setSessionID(){
            $_SESSION["secret_session_id"] = $this->generateToken();
            $_SESSION["session_id"] = session_create_id();
        }

        public function getSessionID(){
            return $_SESSION["session_id"];
        }

        public function sessionDestroy(){
            session_destroy();
            unset($_SESSION);
        }

        public function requireLogin()
        {
            if($this->isLogged() != true){
                if(strpos($_SERVER['REQUEST_URI'],System::fileCourse)){
                    $id    = $this->input("id",SET_INT);
                    $token = $this->input("token",SET_STRING);

                    $courseTable = $this->getTable(Databases::courses);
                    $course = $this->sql("SELECT * FROM {$courseTable} where id = '{$id}' and token = '{$token}' and anon_access = 3 or anon_access = 4 ", "select");

                    if($course["anon_access"] == 3){
                        $url = $this->url(array("file" => System::fileSignup, "parameters" => array("id" => $course["id"], "token" => $course["token"], "type" => "course")));
                    }
                    elseif ($course["anon_access"] == 4){
                        $url = $this->url(array("file" => System::fileIndex, "parameters" => array("guest" => 1, "id" => $course["id"], "token" => $course["token"], "type" => "course")));
                    }
                    else{
                        $this->sessionDestroy();
                        $url = $this->url(array("file" => System::fileIndex));
                    }
                }
                elseif(strpos($_SERVER['REQUEST_URI'],System::fileRecord)){
                    $url = "";
                }
                else{
                    $_SERVER["REQUEST_URI"] = explode("/",$_SERVER["REQUEST_URI"]);
                    $url = urlencode($this->config->mainUrl . $_SERVER["REQUEST_URI"][count($_SERVER["REQUEST_URI"])-1]);
                    $_SESSION["redirect"] = $url;
                    $url = "index.php?redirect={$url}";
                }
                $this->redirect($url);
                exit();
            }
        }

        public function isAdmin(){
            $permission = $this->getInfo(LOGIN_PERMISSIONS);
            $userid = $this->getInfo(LOGIN_USER_ID);
            if($permission != 1){
                $this->redirect($this->url(array("file" => System::fileIndex, "parameters" => array("error" => "not_admin", "userid" => $userid))));
            }
        }
        public function checkSessionID(string $sessionid){
            if ($sessionid != $this->getSessionID()){
                exit("Wrong Session ID !");
            }
        }

        public function getSecHash(string $randomvalue = null){
            $hash = sha1(md5($randomvalue . "_" . $this->getSessionID() . "_" . $this->config->randonKey));
            return $hash;
        }

        public function validateHash(string $hash, string $randomvalue = null){
            return ($hash == $this->getSecHash($randomvalue) ? true : false);
        }

        public function getEnrollment($function,int $courseid,int $userid = null){

            /*
             * Role id number
               0- Guest
               1- Student
               2- Assistant
               3- Teacher
            */
            if($_SESSION["is_guest"] != true){
                $userid = (empty($userid) ? $_SESSION["user_id"] : $userid);
                $enrInfoArray = array(
                    "table" => Databases::enrollment,
                    "fields" => array(
                        "courseid" => $courseid,
                        "userid" => $userid
                    )
                );
                $enrollment = $this->select($enrInfoArray);

                $userInfoArray = array(
                    "table" => Databases::users,
                    "fields" => array(
                        "id" => $userid
                    )
                );
                $userinfo = $this->select($userInfoArray);
            }
            elseif($_SESSION["is_guest"] == true){
                $userinfo = array(
                    "user_ID" => $this->getInfo(LOGIN_USER_ID),
                    "surname" => $this->getInfo(LOGIN_LASTNAME),
                    "forename" => $this->getInfo(LOGIN_FORNAME),
                    "permissions" => false,
                );
                $enrollment = array(
                    "courseid" => $courseid,
                    "role" => 0,
                );
            }
            else{
                $this->sessionDestroy();
                $this->redirect("index.php");
                exit();
            }

            include_once "functions/enrollment.php";

            switch ($function){
                case ENR_ACCESS_TYPE:
                    return accessType($enrollment,$userinfo);
                    break;

                case ENR_CAN_ACCESS:
                    return canAccess($enrollment,$userinfo);
                    break;

                default:
                    return $this->errorException("Unknown function name '{$function}'");
                    break;
            }
        }

        public function instance($function, int $instanceid)
        {
            //TODO Create anonyme access (For course and record)
            require_once "functions/instance_existence.php";
            switch ($function){
                case CHK_COURSE:
                    return courseExists($instanceid);
                    break;

                case CHK_RECORD:
                    return recordExists($instanceid);
                    break;

                default:
                    return $this->errorException("Unknown function name '{$function}'");
                    break;
            }
        }
    }