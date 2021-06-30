<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
require_once $config->directory["library"] . "/ssh.php";

unset($error);

$ssh = new SSH($renderer_username,$renderer_url);
if($ssh->errorMessage["error"] == false) {
    $testCLI = array(
        "php" => $phpCLI,
        "ffmpeg" => $ffmpegCLI,
        "ffprobe" => $ffprobeCLI,
        "rsync" => $rsyncCLI,
        "git" => $gitCLI
    );
    foreach ($testCLI as $cli){
        $test = $ssh->exec("{$config->cli["which"]} {$cli}");
        if(empty($test)){
            $error = true;
            $ssh->errorMessage = array(
                "error" => true,
                "message" => "<b>{$cli}</b> NOT FOUND in => {$renderer_url} !"
            );
            break;
        }
    }
    $gitCloneCmd = trim($ssh->exec("{$gitCLI} clone {$gitRepo} {$server_path}; if test -d {$server_path}; then echo \"created_dir\"; else echo \"can_not_create_dir\"; fi"));
    if($gitCloneCmd == "created_dir"){
        $installDBCmd = $ssh->exec("{$phpCLI} {$server_path}/install_db.php \"{$config->database["type"]}\" \"{$config->database["charset"]}\" \"{$config->database["host"]} \"{$config->database["dbname"]} \"{$config->database["dbuser"]}\" \"{$config->database["dbpass"]}\" \"{$config->database["prefix"]}\"");
        if(trim($installDBCmd) != "success"){
            $error = true;
            $ssh->errorMessage = array(
                "error" => true,
                "message" => "ERROR while installing renderer DB : {$installDBCmd}"
            );
        }
        else{
            $installRendererCmd = $ssh->exec("{$phpCLI} {$server_path}/install.php \"{$phpCLI}\" \"{$ffmpegCLI}\" \"{$ffprobeCLI}\" \"{$rsyncCLI}\" \"{$gitCLI}\" \"{$server_path}\" \"{$maxJob}\"");
            if(trim($installRendererCmd) != "success"){
                $error = true;
                $ssh->errorMessage = array(
                    "error" => true,
                    "message" => "ERROR while installing renderer : {$installRendererCmd}"
                );
            }
            else{
                $rendererSql = array(
                    "table" => Databases::renderers,
                    "fields" => array(
                        "hosturl" => $renderer_url
                    )
                );
                if($sys->select($rendererSql) == false){
                    echo "ok";
                    $insertRenderer = array(
                        "name" => $render_name,
                        "username" => $renderer_username,
                        "hosturl" => $renderer_url,
                        "encode_program" => "ffmpeg",
                        "maxjob" => $maxJob,
                        "enabled" => 1
                    );
                    if($sys->insert(Databases::renderers,$insertRenderer) == true){
                        $sys->redirect($sys->url(array("file" => System::fileRendering)));
                    }
                }
            }
        }
    }
    else{
        $error = true;
    }
}

?>