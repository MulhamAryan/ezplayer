<?php

    class Controller extends System {
        /**
         * @var stdClass
         */

        public function __construct()
        {
            parent::__construct();
        }

        public function load($controller){
            $controller = $this->config->directory["controllers"] . "/" . $controller . ".php";
            if(file_exists($controller)){
                include $controller;
            }
            else{
                try{
                    throw new Exception("Error controller not found in {$controller}");
                }
                catch (Exception $e){
                    $this->errorException($e);
                }
            }
        }
    }