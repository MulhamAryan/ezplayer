<?php

    class Player extends Authentication {

        private $recordid;
        private $file;
        private $dir;
        private $quality;
        private $recordType;
        private $playerFile;
        private $recordDir;
        private $m3u8Name;
        private $tsFileName;
        private $metadata;
        private $hashid;
        private $generalKey;
        /**
         * @var array|bool|float|int|string|string[]|null
         */
        private $m3uHash;
        /**
         * @var array|bool|float|int|string|string[]|null
         */
        private $m3u8Hash;

        public function __construct()
        {
            parent::__construct();
            $this->config->repository = "/var/www/html/projects/"; //TODO NEED TO BE REMOVED TEMPORARY VAR DIR

            $this->recordType = $this->input("recordType",SET_STRING);
            $this->quality    = $this->input("quality",SET_STRING);
            $this->dir        = $this->input("dir",SET_STRING);
            $this->recordid   = $this->input("recordid", SET_INT);
            $this->hashid     = $this->input("hash",SET_STRING);
            $this->generalKey = $this->getInfo(LOGIN_USER_ID) . "_" . $this->recordid;

            $this->recordDir  = "{$this->config->repository}{$this->dir}/";
            $this->m3u8Name   = "ffmpegmovie.m3u8";
            $this->tsFileName = "ffmpegmovie";
            $this->metadata   = $this->recordDir . "/_metadata.xml";
        }
        private function contentType(array $info = null){
            if(empty($info)) {
                header("Access-Control-Allow-Origin: *");
                header('Content-Type: application/octet-stream');
            }
            elseif($info["type"] == "m3u"){
                header("Access-Control-Allow-Origin: *");
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename=' . $this->recordid . '.' . $info["type"]);
            }
            elseif ($info["type"] == "m3u8"){
                header("Access-Control-Allow-Origin: *");
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename=' . $this->recordid . '.' . $info["type"]);
            }
            elseif ($info["type"] == "ts"){
                header("Access-Control-Allow-Origin: *");
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename=' . $info["file"] . '.' . $info["type"]);
            }
        }
        public function m3u(){
            if($this->validateHash($this->hashid,$this->generalKey) != true){
                exit();
            }
            $this->m3uHash = $this->input("m3uhash",SET_STRING);

            $this->contentType(array("type" => "m3u"));
            $randomHashHQ = $this->config->qualities[1] . "_" . $this->generalKey;
            $HQhashId = $this->getSecHash($randomHashHQ);

            $randomHashLQ = $this->config->qualities[0] . "_" . $this->generalKey;
            $LQhashId = $this->getSecHash($randomHashLQ);

            $stream = "#EXTM3U" . PHP_EOL;
            $stream .= "#EXT-X-STREAM-INF:PROGRAM-ID=1,BANDWIDTH=7680000,CODECS=\"avc1.42e00a,mp4a.40.2\"" . PHP_EOL;
            $stream .= "{$this->config->streamurl}/m3u8.php?recordid={$this->recordid}&recordType={$this->recordType}&quality={$this->config->qualities[1]}&dir={$this->dir}&m3uhash={$HQhashId}" . PHP_EOL;
            $stream .= "#EXT-X-STREAM-INF:PROGRAM-ID=1,BANDWIDTH=1280000,CODECS=\"avc1.42e00a,mp4a.40.2\"" . PHP_EOL;
            $stream .= "{$this->config->streamurl}/m3u8.php?recordid={$this->recordid}&recordType={$this->recordType}&quality={$this->config->qualities[0]}&dir={$this->dir}&m3uhash={$LQhashId}" . PHP_EOL;

            return $stream;
        }

        public function m3u8()
        {
            $this->contentType(array("type" => "m3u8"));
            $this->m3uHash = $this->input("m3uhash",SET_STRING);
            $randomValue = $this->quality . "_" . $this->generalKey;
            if($this->validateHash($this->m3uHash,$randomValue) != true){
                exit();
            }

            $this->m3u8Hash = $this->input("m3u8hash",SET_STRING);
            $this->playerFile = "{$this->config->repository}/{$this->dir}/{$this->recordType}/{$this->quality}/{$this->m3u8Name}";

            $_SESSION["tsrandomkey"] = rand(10000,99999);
            $RandomHashFile = $_SESSION["tsrandomkey"] . "_" . $this->quality . "_" . $this->generalKey;
            $fileHashId = $this->getSecHash($RandomHashFile);

            $playerFileContent = file_get_contents($this->playerFile);
            $playerFileContent = str_replace("#EXTINF:2.400000,","#EXTINF:2.000000,",$playerFileContent);
            $playerFileContent = str_replace($this->tsFileName,$this->config->streamurl . "ts.php?recordid={$this->recordid}&recordType={$this->recordType}&quality={$this->quality}&dir={$this->dir}&m3u8hash={$fileHashId}&file=",$playerFileContent);
            $playerFileContent = str_replace(".ts","",$playerFileContent);

            return $playerFileContent;
        }

        public function ts(){
            /*$this->m3u8Hash = $this->input("m3u8hash",SET_STRING);
            $randomValue = $_SESSION["tsrandomkey"] . "_" . $this->quality . "_" . $this->generalKey;
            if($this->validateHash($this->m3u8Hash,$randomValue) != true){
                echo "error!";
                exit();
            }*/
            //TODO ADD VERIFICATION SYSTEM
            $tsFileName = $this->input("file",SET_STRING);

            $this->contentType(array("file" => $tsFileName, "type" => "ts"));
            $tsFileDir  = "{$this->recordDir}/{$this->recordType}/{$this->quality}/{$this->tsFileName}{$tsFileName}.ts";

            if(file_exists($tsFileDir)){
                return file_get_contents($tsFileDir);
            }
        }

        public function filePlayer(){

        }
    }
