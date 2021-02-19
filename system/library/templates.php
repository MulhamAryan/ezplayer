<?php

    class Templates extends System {

        private $auth;
        //private $lang;

        public function __construct()
        {
            parent::__construct();
            global $config;
            global $auth;
            global $lang;

            $this->config = $config;
            $this->auth   = $auth;
            $this->lang   = $lang;
        }

        public function load($file)
        {
            $file = $this->config->directory["templates"] . "/" . $this->config->activeTemplate . "/views/" . $file;
            if(file_exists($file)) {
                return $file;
            }
            else {
                die("<pre>Template file not found <br>{$file}</pre>");
            }
        }

        public function getHeader(array $array = null)
        {
            $ajax = $this->input("ajax",SET_INT);
            if($this->auth->isLogged() == true) {
                $userCoursesCache = $this->getCache(Cache::userDir . "/{$this->auth->getInfo(LOGIN_USER_LOGIN, 0)}/" . Cache::user_courses_menu);
            }
            if($ajax != 1) {
                require $this->load("header.php");
            }
        }

        public function getFooter(array $array = null){
            $ajax = $this->input("ajax",SET_INT);
            if($ajax != 1) {
                require $this->load("footer.php");
            }
        }

        public function getError(string $message){
            include $this->load("error.php");
            return $msg;
        }

        public function getSuccess(string $message){
            include $this->load("success.php");
            return $msg;
        }

        public function getModal(array $modalInfo){
            include $this->load("modal.php");
        }

        public function convertTime($timestamp){
            $diff =  time() - $timestamp;
            if ($diff / 60 <1 ) $value = intval($diff%60) . "s";
            elseif ($diff / 60 < 60) $value = intval($diff/60) . "m";
            elseif ($diff / 3600 < 24) $value = intval($diff/3600) . "h";
            elseif ($diff/ 86400 < 30) $value = intval($diff/86400) . "d";
            else $value = date("d/m/Y - h:i",$timestamp);
            return $value;
        }

        public function selected($val1,$val2){
            return ($val1 == $val2 ? "selected" : "");
        }
    }