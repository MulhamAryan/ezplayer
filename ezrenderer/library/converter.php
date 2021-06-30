<?php

    class Converter extends System {

        private $file;
        public  $stream;
        private $encodersCmd;
        private $record;
        private $transcodedPartition;
        public $recordDirectory;
        public $errorMsg;
        //private $db;

        public $enableFilter;
        public $defaultConvertion;
        public $outputFile;
        public $repository;
        public $currentRendering;

        const original = "original";
        const cam = "cam";
        const slide = "slide";
        const audio = "audio";
        const transcoded = "transcoded";


        const scheduled  = "scheduled";
        const processing = "processing";
        const processed  = "processed";
        const failed     = "failed";

        /**
         * @var string
         */
        //private $qualitiesDir;
        /**
         * @var array|bool|null
         */


        /**
         * @var Databases
         */


        public function __construct()
        {
            //TODO ADD LOG
            parent::__construct();
            $this->stream = array();
            $this->record = array();
            $this->enableFilter = true;
            $this->defaultConvertion = "m3u8";
            $this->repository = $this->config->directory["repository"];
            $this->recordDirectory = "";
        }

        public function __destruct()
        {
            $this->file = "";
            $this->stream = "";
            $this->record = "";
            $this->enableFilter = "";
            $this->defaultConvertion = "";
            $this->recordDirectory = "";
        }

        public function runCommand(string $command){
            $exec = shell_exec($command);

            return $exec;
        }

        public function getFileInfo(string $file){
            if(file_exists($file)){
                $info = new SplFileInfo($file);
                return $info;
            }
            else{
                return false;
            }
        }
        public function setRecordID(int $recordid){
            $recordTable  = $this->getTable(Databases::records);
            $usersTable   = $this->getTable(Databases::users);
            $coursesTable = $this->getTable(Databases::courses);
            $record = $this->sql("SELECT record.*, course.course_code as course_code, course.course_name as course_name, users.user_ID as username, users.surname as surname, users.forename as forename FROM {$recordTable} as record INNER JOIN {$usersTable} as users on users.id = record.user_id INNER JOIN {$coursesTable} as course on record.course_id = course.id WHERE record.id = {$recordid}","select");

            if($record != false){
                $this->record = $record;
                $this->recordDirectory = "{$this->repository}/{$this->record["filepath"]}";
                $this->initRecordRendering();
            }
            else{
                echo $recordid . " Record id not found !" . PHP_EOL;
                exit();
            }
        }
        public function createRenderingDir(){
            $renderingDir = $this->recordDirectory . "/rendering";
            if(!is_dir($renderingDir) && !mkdir($renderingDir,0755, true)){
                $this->errorMsg["dir"] = error_get_last()["message"] . PHP_EOL . "Can't create rendering directory in : {$renderingDir} please check the permission and owner.";
                $this->updateRenderingHistory("Fail Creating Redering dir {$renderingDir}:" . time());
                return false;
            }
            else {
                $this->updateRenderingHistory(__FUNCTION__ . ":" . time());
                foreach ($this->record["record_type"] as $dirKey => $dirVal) {
                    foreach ($this->config->encoders as $qualityKey => $qualityVal) {
                        if($this->config->encoders[$qualityKey]["enabled"] == true){
                            $qualityDir = "{$renderingDir}/{$qualityKey}_{$dirKey}";
                            $this->stream["directories"][] = $qualityDir;
                            if (!is_dir($qualityDir) && !@mkdir($qualityDir, 0755, true)) {
                                $this->errorMsg["dir"] = error_get_last()["message"] . PHP_EOL . "Can't create rendering quality directory in : {$qualityDir} please check the permission and owner.";
                                $this->updateRenderingHistory("Can't create rendering quality directory in => {$qualityDir} :" . time());
                                return false;
                            }
                            $this->updateRenderingHistory("Creating {$qualityDir} dir success:" . time());
                        }
                    }
                }
                return true;
            }
        }

        public function initRecordRendering(){
            $getRendering = $this->select(array("table" => Databases::renderings,"fields" => array("record_id" => $this->record["id"])));
            if($getRendering == false){
                $sqlRendering = array(
                    "record_id" => $this->record["id"],
                    "renderer_id" => $this->config->rendererid,
                    "current_status" => "initRendering",
                    "history" => "",
                    "starttime" => time(),
                    "endtime" => ""
                );
                $this->insert(Databases::renderings,$sqlRendering);
                $this->currentRendering = $this->select(array("table" => Databases::renderings, "fields" => array("record_id" => $this->record["id"])));
                $this->updateCurrentStatus(__FUNCTION__);
                $this->updateRenderingHistory(__FUNCTION__ . ":" . time());
                $this->updateRecord(self::processing);
            }
            $this->record["record_type"] = json_decode($this->record["record_type"],true);
            if(!$this->createRenderingDir()){
                return $this->errorMsg["dir"];
            }
            else{
                foreach ($this->record["record_type"] as $fileKey => $fileValue){
                    $this->setStreamInfo($fileKey,$fileValue);
                }
            }
        }

        public function setStreamInfo(string $fileKey, string $fileValue){
            $this->stream[$fileKey]["file"] = "{$this->recordDirectory}/{$fileValue}";

            $informations = $this->runCommand("{$this->config->cli["ffprobe"]} -v error -select_streams v:0 -show_entries stream -of json {$this->stream[$fileKey]["file"]}");
            $informations = json_decode($informations,true);
            $stream = $informations["streams"][0];

            $this->stream[$fileKey]["codec_name"]           = $stream["codec_name"];
            $this->stream[$fileKey]["profile"]              = $stream["profile"];
            $this->stream[$fileKey]["width"]                = $stream["width"];
            $this->stream[$fileKey]["height"]               = $stream["height"];
            $this->stream[$fileKey]["duration"]             = ((isset($stream["duration"]) && !empty($stream["duration"])) ? $stream["duration"] : 0);
            $this->stream[$fileKey]["codec_name"]           = $stream["codec_name"];
            $this->stream[$fileKey]["display_aspect_ratio"] = $stream["display_aspect_ratio"];
            $this->stream[$fileKey]["sample_aspect_ratio"]  = $stream["sample_aspect_ratio"];

            $this->setMetadata($fileKey);
            $this->setFilter($fileKey);
            $this->setEncoders($fileKey);
            $this->setDuration($fileKey);
            $this->setRenderingCmd($fileKey);
        }

        public function setMetadata(string $streamKey){
            $metadata = "-metadata title=\"{$this->record["title"]}\" -metadata copyright=\"ULB Podcast\" -metadata author=\"{$this->record["forename"]} {$this->record["surname"]}\" -metadata album=\"{$this->record["course_code"]} {$this->record["course_name"]}\" -metadata year=\"" . date("Y") . "\"";
            $inputFile = $this->getFileInfo($this->stream[$streamKey]["file"]);
            $this->transcodedPartition[$streamKey] = $this->recordDirectory . "/rendering/" . Converter::transcoded . "_" . $inputFile->getFilename();
            $this->stream[$streamKey]["cmd"]["setMetadata"] = "{$this->config->cli["ffmpeg"]} -i {$this->stream[$streamKey]["file"]} {$metadata} -c:v copy -c:a copy {$this->transcodedPartition[$streamKey]} &>> {$this->recordDirectory}/rendering/" . __FUNCTION__ . ".log";
            $this->updateRenderingHistory(__FUNCTION__ . "_{$streamKey}:" . time());
        }

        public function setFilter(string $streamKey){
            if($this->enableFilter == true) {
                $pixel_correction = (!isset($this->stream[$streamKey]["sample_aspect_ratio"])) ? array(1, 1) : explode(':', $this->stream[$streamKey]["sample_aspect_ratio"]);
                $inputWidth  = ($pixel_correction[0] == 0) ? 1 : $pixel_correction[0];
                $inputHeight = ($pixel_correction[1] == 0) ? 1 : $pixel_correction[1];

                $filterCmd  = "scale=iw*min({$this->stream[$streamKey]["width"]}/iw\,{$this->stream[$streamKey]["height"]}/(ih/{$inputWidth}*{$inputHeight})):(ih/{$inputWidth}*{$inputHeight})*min({$this->stream[$streamKey]["width"]}/iw\,{$this->stream[$streamKey]["height"]}/(ih/{$inputWidth}*{$inputHeight})), ";
                $filterCmd .= "pad={$this->stream[$streamKey]["width"]}:{$this->stream[$streamKey]["height"]}:({$this->stream[$streamKey]["width"]}-iw)/2:({$this->stream[$streamKey]["height"]}-ih)/2";
            }
            else{
                $filterCmd = "scale={$this->stream[$streamKey]["width"]}:{$this->stream[$streamKey]["height"]}";
            }
            $this->stream[$streamKey]["filter"] = $filterCmd;
        }

        public function setRenderingCmd(string $streamKey){

            foreach ($this->encodersCmd[$streamKey] as $qualityKey => $qualityVal){
                $this->setConvertType($streamKey,$qualityKey);
                $this->stream[$streamKey]["cmd"][$qualityKey] = "{$this->config->cli["ffmpeg"]} -i {$this->transcodedPartition[$streamKey]} {$qualityVal} {$this->outputFile}  &>> {$this->recordDirectory}/rendering/" . __FUNCTION__ . "_{$qualityKey}.log";
            }
        }

        public function setConvertType(string $streamKey, string $qualityKey){
            $hls = "";
            if($this->defaultConvertion == "m3u8"){
                foreach ($this->config->ffmpeg as $itemKey => $itemVal){
                    if($itemKey != "moviefile")
                        $hls .= "-{$itemKey} {$itemVal} ";
                }
                $output = "{$hls} {$this->recordDirectory}/rendering/{$qualityKey}_{$streamKey}/{$this->config->ffmpeg["moviefile"]}.{$this->defaultConvertion}";
            }
            else{
                $output = "";
            }
            $this->outputFile = $output;
        }

        public function setEncoders(string $streamKey){
            $quality = $this->config->encoders;

            foreach ($quality as $qualityKey => $qualityVal) {
                if($qualityVal["enabled"] == true){
                    $cmd[$qualityKey] = "";
                    unset($qualityVal["enabled"]);
                    foreach ($qualityVal as $encoderKey => $encoderVal) {
                        $cmd[$qualityKey] .= "-{$encoderKey} {$encoderVal} ";
                    }
                    $cmd[$qualityKey] .= "-vf \"{$this->stream[$streamKey]["filter"]}\" ";
                }
            }
            $this->encodersCmd[$streamKey] = $cmd;
        }

        private function updateRenderingHistory(string $newValue){
            if($this->currentRendering){
                if(!is_null($this->currentRendering["history"])){
                    $this->currentRendering["history"] = json_decode($this->currentRendering["history"],true);
                }
                $this->currentRendering["history"][] = $newValue;
                $this->currentRendering["history"]   = json_encode($this->currentRendering["history"],true);
                $this->update(array("table" => Databases::renderings, "fields" => "history = '{$this->currentRendering["history"]}' where id = '{$this->currentRendering["id"]}'"));
            }
        }

        private function updateCurrentStatus(string $newValue){
            if($this->currentRendering){
                $this->update(array("table" => Databases::renderings, "fields" => "current_status = '{$newValue}' where id = '{$this->currentRendering["id"]}'"));
            }
        }

        private function updateRecord(string $status){
            $this->update(array("table" => Databases::records,"fields" => "status = '{$status}' where id = '{$this->record["id"]}'"));
            //TODO Podcast cache need to be updated every time !
        }

        public function getEncodersCmd(array $info = null){
            if(empty($info))
                $encoders = $this->encodersCmd;
            else{
                $encoders = $this->encodersCmd[$info["key"]][$info["value"]];
            }
            return $encoders;
        }
        public function startRendering(string $type = null){
            $i = 0;
            foreach ($this->stream as $item => $value){
                $i++;
                foreach ($value["cmd"] as $cmdKey => $cmdVal) {
                    echo "-------------- Start {$item} {$cmdKey} " . time() . " new Block Rendering ---------------" . PHP_EOL;
                    $this->updateCurrentStatus("{$cmdKey}_{$item}");
                    $this->updateRenderingHistory("{$cmdKey}_{$item}:" . time());
                    $this->runCommand("{$cmdVal}");
                    echo "-------------- End of {$cmdKey} " . time() . " Block Rendering ---------------" . PHP_EOL;
                }
            }
            if(count($this->stream) == $i){
                $this->update(array("table" => Databases::renderings, "fields" => "current_status = 'done', endtime = '" . time() . "' where id = '{$this->currentRendering["id"]}'"));
                $this->finishRendering();
            }
            else{
                $this->update(array("table" => Databases::renderings, "fields" => "current_status = 'error', endtime = '" . time() . "'  where id = '{$this->currentRendering["id"]}'"));
                $this->finishRendering(self::failed);
            }
        }

        public function finishRendering(string $status = self::processed){
            foreach ($this->transcodedPartition as $file){
                unlink($file);
            }
            foreach ($this->stream["directories"] as $dir){
                $this->runCommand("{$this->config->cli["mv"]} {$dir} {$this->recordDirectory}");
            }
            $this->updateRecord($status);
        }

        private function setDuration(string $fileKey)
        {
            //$this->update(array("table" => Databases::records,"fields" => "duration = '{$this->stream[$fileKey]["duration"]}' where id = '{$this->record["id"]}'"));
        }
    }