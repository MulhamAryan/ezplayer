<?php
    define("LOGIN_USER_ID",'LOGIN_USER_ID');
    define("LOGIN_USER_LOGIN",'LOGIN_USER_LOGIN');
    define("LOGIN_FULLNAME",'LOGIN_FULLNAME');
    define("LOGIN_PERMISSIONS",'LOGIN_PERMISSIONS');
    define("LOGIN_FORNAME",'LOGIN_FORNAME');
    define("LOGIN_LASTNAME",'LOGIN_LASTNAME');

    class Authentication extends System {

        /**
         * @var Databases
         */
        private $db;

        public function __construct(){
            parent::__construct();
            $this->db = $this->DBDriver;
        }

        public function checkLoginInfo(String $username,String $userpass)
        {
            $userInfoArray = array(
                "table" => Databases::users,
                "fields" => array(
                    "user_ID" => $username
                )
            );
            $userinfo = $this->db->select($userInfoArray);
            $oldpassCheck = $this->oldCryptCheck($userpass,$userinfo["recorder_passwd"]);

            //Update old password crypt if true
            if($oldpassCheck == true){
                $newPassword = (string) $this->encrypt($userpass);
                $updatequery = array(
                    "table" => Databases::users,
                    "fields" => "recorder_passwd = '{$newPassword}', updated_password = 1 where id='{$userinfo["id"]}'"
                );
                $this->db->update($updatequery);
            }

            //Auto load authentications methods
            $authmethod = $this->config->directory["library"] . "/authentications/";
            foreach (new DirectoryIterator($authmethod) as $methodFile) {
                //If user doesn't exists in Database search in LDAP or CAS
                if(!$methodFile->isDot()){
                    $rep = require_once $authmethod . $methodFile->getFilename();
                    if ($rep == true) {
                        $rep = true;
                        break;
                    }
                    else{
                        $rep = false;
                    }
                }
                else {
                    $rep = false;
                }
            }
            //Create sessions
            if ($rep == true) {
                $userInfoArray = array(
                    "table" => Databases::users,
                    "fields" => array(
                        "user_ID" => $username
                    )
                );

                $userinfo = $this->db->select($userInfoArray);
                $userArrayInfo = array(
                    "user_id"     => $userinfo['id'],
                    "user_login"  => $userinfo['user_ID'],
                    "forename"    => $userinfo['forename'],
                    "surname"     => $userinfo['surname'],
                    "permissions" => $userinfo['permissions'],
                );
                $this->createSession($userArrayInfo);
                return true;
            } else {
                $this->sessionDestroy();
                return false;
            }
        }

        public function createSession(array $arrayUserInfo) {
            $_SESSION["is_logged"]   = true;
            $_SESSION["user_id"]     = $arrayUserInfo["user_id"];
            $_SESSION["user_login"]  = $arrayUserInfo["user_login"];
            $_SESSION["forename"]    = $arrayUserInfo["forename"];
            $_SESSION["surname"]     = $arrayUserInfo["surname"];
            $_SESSION["permissions"] = $arrayUserInfo["permissions"];
            $this->setSessionID();

            $this->setCache(Cache::userDir . "/{$arrayUserInfo["user_login"]}/" . Cache::user_courses_menu,$this->getUserCourses());
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
                $insertUser = $this->select($arraySql);
            }
            return $insertUser;
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
                    $sqlCourseArray = array(
                        "table" => Databases::courses,
                        "fields" => array(
                            "id" => $id,
                            "token" => $token,
                            "anon_access" => 3
                        )
                    );
                    $course = $this->select($sqlCourseArray);
                    if($course != false){
                        $url = $this->url(array("file" => System::fileSignup, "parameters" => array("id" => $course["id"], "token" => $course["token"], "type" => "course")));
                    }
                    else{
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

        public function checkSessionID(string $sessionid){
            if ($sessionid != $this->getSessionID()){
                exit();
            }
        }

        public function getSecHash(string $randomvalue = null){
            $hash = sha1(md5($randomvalue . "_" . $this->getSessionID() . "_" . $this->config->randonKey));
            return $hash;
        }

        public function validateHash(string $hash, string $randomvalue = null){
            return ($hash == $this->getSecHash($randomvalue) ? true : false);
        }

        public function createGuestSession(){

        }
    }