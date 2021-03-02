<?php

    class Cache
    {
        /**
         * @var stdClass
         * @var CacheEngine
         */
        private $config;
        private $cacheEngine;
        const courseDir         = "courses_cache";
        const userDir           = "users_cache";
        const guestDir          = "guests";
        const guestEnrollments  = "enrollments.json";
        const user_courses_menu = "user_courses_menu.json";
        const records_list      = "records_list.json";
        const courseInfo        = "course.json";

        public function __construct()
        {
            global $config;
            $this->config = $config;
            require_once "cacheengine/{$this->config->cache["engine"]}.php";
            $this->cacheEngine = new CacheEngine();
            $this->cacheEngine->createCacheDir($this->config->cache["dir"]);
        }

        public function setCache(string $filename,array $info){
            $this->cacheEngine->setCache($filename,$info);
        }

        public function getCache(string $filename){
            return $this->cacheEngine->getCache($filename);
        }

        public function clearCache(string $dir){
            $this->cacheEngine->clearCache($dir);
        }

        public function updateCacheFile(string $filename, array $info){
            $this->cacheEngine->updateCacheFile($filename,$info);
        }

        public function checkCache(string $filename){
            return $this->cacheEngine->checkCache($filename);
        }

        public function getCacheEngine(){
            return $this->cacheEngine->getCacheEngine();
        }


    }