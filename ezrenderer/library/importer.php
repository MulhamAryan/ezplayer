<?php
    include "rsync.php";

    class Importer extends System {
        const importing = "importing";
        /**
         * @var array|bool|null
         */
        private $record = false;
        /**
         * @var int
         */
        private $recordid;

        public function __construct()
        {
            parent::__construct();
        }

        public function setRecord(int $recordid)
        {
            $this->recordid = $recordid;
            $recordInfo = array(
                "table" => Databases::records,
                "fields" => array(
                    "id" => $recordid
                )
            );

            $checkRecord = $this->select($recordInfo);
            if($checkRecord != false)
                $this->record = $checkRecord;

        }

        private function checkRepository(){
            echo "---- " . date('h:i:s') . " Start checking repository ----" . PHP_EOL;
            $repository = $this->config->directory["repository"] . "/" . $this->record["filepath"];
            try{
                if(!is_dir($repository)){
                    throw new Exception("Directory " . $repository . " doesn't exists !");
                }
            }
            catch (Exception $exception){
                $this->updateStatus('failed:inexistent_dir');
                die($exception->getMessage());
            }
            echo "{$repository} OK !" . PHP_EOL;
        }
        private function readCutListFile(string $file){
            $array = array();
            $cut_list = trim(file_get_contents($file));
            $line = preg_split("/((\r?\n)|(\r\n?))/", $cut_list);

            $count = 0;
            for ($i = 1; $i < count($line); $i++) {
                $export = explode(":", $line[$i]);
                if ($i < count($line) - 1) {
                    $exportplus = explode(":", $line[$i + 1]);
                }
                if ($export[0] == "play" or $export[0] == "resume") {
                    $array[$count][] = $export[3];
                    if ($exportplus[0] == "pause") {
                        $array[$count][] = $exportplus[3];
                        $count++;
                    }
                    else{
                        $array[$count][] = $exportplus[3];
                        break;
                    }
                }
            }
            return $array;
        }

        private function renderRecord(){
            $recordType = json_decode($this->record["record_type"],true);

            foreach ($recordType as $typeKey => $typeValue){
                $recordTypeDir = $this->config->directory["repository"] . "/" . $this->record["filepath"] . "/{$typeKey}record/";
                $qualitiesDir = new DirectoryIterator($recordTypeDir);

                foreach ($qualitiesDir as $quality) {
                    if($quality->isDir() && !$quality->isDot() && ($quality->getFilename() == "high" || $quality->getFilename() == "low")) {
                        echo "----  " . date('h:i:s') . " Start of {$typeKey}record/_cut_list.txt adaptation ----" . PHP_EOL;
                        $newFFMPEGFilesDir = "{$this->config->directory["repository"]}/{$this->record["filepath"]}/{$quality}_{$typeKey}";

                        if(!is_dir($newFFMPEGFilesDir)) {
                            if (!mkdir($newFFMPEGFilesDir)) {
                                $this->updateStatus("failed:create_{$quality}_{$typeKey}_dir");
                                die("Unable to create paused directory in {$newFFMPEGFilesDir}");
                            }
                        }
                        $oldQualityDir = $this->config->directory["repository"] . "/{$this->record["filepath"]}/{$typeKey}record/{$quality}/";


                        $cutListFiles = $this->readCutListFile($recordTypeDir . "/_cut_list.txt");
                        $concat = array();
                        foreach ($cutListFiles as $partition){
                            for($i = $partition[0]; $i <= $partition[1]; $i++){
                                $concat[] = "file 'ffmpegmovie{$i}.ts'";
                            }
                        }
                        $implodedConcat = implode("\n",$concat);
                        $concatFile = $oldQualityDir . '/concat.txt';
                        if(file_exists($concatFile)){
                            unlink($concatFile);
                        }

                        if(file_put_contents($concatFile,$implodedConcat, FILE_APPEND | LOCK_EX)){
                            echo "{$oldQualityDir}/concat.txt' OK !" . PHP_EOL;
                        }
                        else{
                            $this->updateStatus("failed:concat_file_error");
                            die("Unable to create concat.txt file in $oldQualityDir !");
                        }
                        $ffmpegCmd = $this->config->cli["ffmpeg"] . " -f concat -safe 0 -i {$concatFile} -f hls -threads 1 -fflags nobuffer -flags low_delay -strict experimental -r 25 -c:v copy -c:a copy -hls_list_size 0 -hls_wrap 0 -flags output_corrupt -start_number 1 {$newFFMPEGFilesDir}/ffmpegmovie.m3u8";

                        //-thread_queue_size 127 -threads 1 -fflags nobuffer -flags low_delay -strict experimental -r 25 -i rtsp://10.0.2.3:554/1 -c:v copy -c:a copy -hls_list_size 0 -hls_wrap 0 -flags output_corrupt -start_number 1
                        exec($ffmpegCmd,$ffmpegOutput,$ffmpegAns);
                        if($ffmpegAns == 0) {
                            foreach ($ffmpegOutput as $foutput) {
                                echo $foutput . PHP_EOL;
                            }
                        }
                        else{
                            $this->updateStatus("failed:ffmpeg_concat");
                            die("Unable to concat using ffmpeg in {$newFFMPEGFilesDir}");
                        }
                        $generatedQualities[$typeKey][] = $quality->getFilename();
                        unset($concatFile);
                        unset($ffmpegCmd);
                    }
                }
            }
            foreach ($generatedQualities as $qualityCheckKey => $qualityCheckVal){
                if(!in_array("low",$generatedQualities[$qualityCheckKey])){
                    echo $qualityCheckKey . " low not found";
                }
                //var_dump($generatedQualities[$qualityCheckKey]);
            }
            exit();
        }

        private function syncAuditorium(){
            //1- Check ssh connexion
            echo "---- " . date('h:i:s') . " Checking auditorium existence ----" . PHP_EOL;
            $auditorium = $this->select(array("table" => Databases::auditoriums,"fields" => array("machineip" => $this->record["origin"])));
            if($auditorium != false) {
                echo $auditorium["machineip"] . " Exists OK !" . PHP_EOL;

                $sshCmd = $this->config->cli["ssh"] . " -o ConnectTimeout=3 -o BatchMode=yes {$auditorium["username"]}@{$auditorium["machineip"]} exit";
                exec($sshCmd, $output, $sshAns);
                if($sshAns == 0){
                    echo $auditorium["machineip"] . " SSH OK !" . PHP_EOL;
                    //2- Start synchronizing record
                    foreach ($this->config->rsync['includedFile'] as $includedFile){
                        $included[] = "--include='*{$includedFile}'";
                    }
                    $includedFiles = implode(" ",$included);
                    $folder = explode('/', $this->record["filepath"]);
                    $folder = end($folder);
                    $cmd = $this->config->cli["rsync"] . " -zarv {$includedFiles}  --exclude='*' {$auditorium["username"]}@{$auditorium["machineip"]}:{$this->config->ezrecorderDir["local_processing"]}/{$folder} {$this->config->directory["repository"]}/{$this->record["course_id"]}/"; //  >> {$this->rsync_log}  2>&1 &
                    exec($cmd,$outputs,$rsyncAns);
                    //$rsyncAns = 0;
                    if($rsyncAns == 0){
                        echo "---- " . date('h:i:s') . " Start RSYNC {$folder} from {$auditorium["machineip"]} ----" . PHP_EOL;
                        echo "CMD : {$cmd}" . PHP_EOL;
                        foreach ($outputs as $output){
                            echo $output . PHP_EOL;
                        }

                        $this->renderRecord();

                    }
                    else{
                        $this->updateStatus('failed:can_not_sync');
                        die("Unable to synchronize repository from {$auditorium["username"]}@{$auditorium["machineip"]}:{$this->config->ezrecorderDir["local_processing"]}/{$folder}");
                    }
                }
                else{
                    $this->updateStatus("failed:ssh_connection");
                    die("Couldn't connect to recorder host ! -> " . $auditorium["machineip"]);
                }
            }
            else{
                $this->updateStatus("failed:auditorium_not_found");
                die("{$this->record["origin"]} auditorium not found !");
            }
            //return true;
        }

        public function startImporting()
        {
            echo "---- " . date("d/m/Y") . " Start importing record from auditorium" . PHP_EOL;
            if($this->record != false){
                echo $this->record["id"] . " Record found OK!" . PHP_EOL;
                $this->checkRepository(); //First check if the repository was created in the repository
                $this->syncAuditorium(); //Then start synchronizing the content from the auditorium

            }
            else{
                die($this->recordid . ' record not found !' . PHP_EOL);
            }
        }

        private function updateStatus(string $status){

        }
    }
/*
file 'ffmpegmovie12.ts'
file 'ffmpegmovie13.ts'
file 'ffmpegmovie14.ts'
file 'ffmpegmovie15.ts'
file 'ffmpegmovie16.ts'
file 'ffmpegmovie17.ts'
file 'ffmpegmovie18.ts'
file 'ffmpegmovie22.ts'
file 'ffmpegmovie23.ts'
file 'ffmpegmovie24.ts'
file 'ffmpegmovie25.ts'
file 'ffmpegmovie26.ts'
file 'ffmpegmovie27.ts'
*/

//ffmpeg -i "concat:ffmpegmovie3.ts|ffmpegmovie4.ts|ffmpegmovie5.ts|ffmpegmovie10.ts|ffmpegmovie11.ts|ffmpegmovie12.ts|ffmpegmovie13.ts|ffmpegmovie14.ts|ffmpegmovie15.ts|ffmpegmovie16.ts|ffmpegmovie17.ts|ffmpegmovie18.ts|ffmpegmovie19.ts|ffmpegmovie20.ts|ffmpegmovie22.ts|ffmpegmovie23.ts|ffmpegmovie24.ts|ffmpegmovie25.ts|ffmpegmovie26.ts|ffmpegmovie27.ts|ffmpegmovie28.ts|ffmpegmovie29.ts|ffmpegmovie30.ts|ffmpegmovie31.ts" -c copy  -f segment -segment_time 2 -segment_list ../high_cam/ffmpegmovie.m3u8 -y -segment_format mpegts  ../high_cam/ffmpegmovie%05d.ts
//ffmpeg  -f concat -safe 0 -i concat.txt -c copy  -f segment -segment_time 2 -hls_playlist_type vod -segment_list ../high_cam/ffmpegmovie.m3u8 -y -segment_format mpegts  ../high_cam/ffmpegmovie%d.ts
//ffmpeg -f concat -safe 0 -i sliderecord/high/concat.txt -c copy -hls_playlist_type vod -hls_time 10 -hls_list_size 0 -hls_wrap 0 -flags output_corrupt -start_number 1 high_slide/ffmpegmovie.m3u8
///usr/bin/ffmpeg -t 12:00:00 -f rtsp -rtsp_transport tcp -thread_queue_size 127 -threads 1 -fflags nobuffer -flags low_delay -strict experimental -r 25 -i rtsp://10.0.2.3:554/1 -c:v copy -c:a copy -hls_list_size 0 -hls_wrap 0 -flags output_corrupt -start_number 1 /var/www/recorderdata//movies/local_processing/2021_06_22_10h50_PODC-I-000/camrecord/high/ffmpegmovie.m3u8 -vf fps=1 -y -update 1 /var/www/recorderdata//var//camrecord.jpg