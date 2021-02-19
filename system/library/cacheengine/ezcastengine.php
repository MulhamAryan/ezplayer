<?php

    class CacheEngine{
        /**
         * @var stdClass
         */
        private $config;

        public function __construct()
        {
            global $config;
            $this->config = $config;
        }

        public function setCache(string $filename,array $info){
            $filepath = $this->config->cache["dir"] . $filename;
            $dirname = dirname($filepath);
            $this->createCacheDir($dirname);
            $jsonConvert = json_encode($info,true);
            try{
                file_put_contents($filepath,$jsonConvert);
            }
            catch (Exception $e){
                die("<pre>ERROR : Could not create your cache file system {$e->getTraceAsString()}</pre>");
            }
        }

        public function getCache(string $filename){
            $file = $this->config->cache["dir"] . $filename;
            if(file_exists($file)) {
                $cacheContent = file_get_contents($file);
                $cacheContent = json_decode($cacheContent, true);
                return $cacheContent;
            }
        }

        public function checkCache(string $filename)
        {
            if(file_exists($this->config->cache["dir"] . $filename)) {
                return true;
            }
            else{
                return false;
            }
        }

        public function clearCache(string $path)
        {
            $path = $this->config->cache["dir"] . $path;
            if(is_dir($path)){
                System::deleteDirectory($path);
            }
            else{
                unlink($path);
            }
        }

        public function updateCacheFile(string $filename, array $info)
        {
            if($this->checkCache($filename) == true){
                $this->clearCache($filename);
            }
            $this->setCache($filename,$info);
        }

        public function createCacheDir($dir)
        {
            if(!is_dir($dir)){
                try{
                    mkdir($dir,0755, true);
                }
                catch (Exception $e){
                    die($e->getMessage());
                }
            }
        }

        public function getCacheEngine(){
            return $this->config->cache["ezcastengine"];
        }


    }