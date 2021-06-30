<?php
    include "../../config.php";
    include "../configurations.php";
    $requireElements = array("requireLeftMenu" => true, "requireBody" => true);
    $tmp->getHeader($requireElements);
    $do = $sys->input("do",SET_STRING);
    if(file_exists($config->admin["rsa_key_file"])) {
        $rsa_key = file_get_contents($config->admin["rsa_key_file"]);
    }
    else{
        $rsa_key = "no key found !";
    }

    if($do == "add"){
        $install = $sys->input("start_installation", SET_STRING);
        if(!empty($install)) {
            $render_name       = $sys->input("render_name",SET_STRING);
            $renderer_url      = $sys->input("renderer_url",SET_STRING);
            $renderer_username = $sys->input("render_username",SET_STRING);
            $phpCLI            = $sys->input("php_cli",SET_STRING);
            $ffmpegCLI         = $sys->input("ffmpeg_cli",SET_STRING);
            $ffprobeCLI        = $sys->input("ffprobe_cli",SET_STRING);
            $gitCLI            = $sys->input("git_cli",SET_STRING);
            $rsyncCLI          = $sys->input("rsync_cli",SET_STRING);
            $server_path       = $sys->input("server_path",SET_STRING);
            $gitRepo           = $sys->input("git_repo",SET_STRING);
            $maxJob            = $sys->input("max_job",SET_STRING);
            include "renderer/install.php";
        }
        include $tmp->load("admin/monitorings/add_renderer.php");

    }
    elseif($do == "edit"){
        $id = $sys->input("id",SET_INT);
        $sessionid = $sys->input("sessionid",SET_STRING);
        $auth->checkSessionID($sessionid);
        $getRenderer = $sys->select(array("table" => Databases::renderers, "fields" => array("id" => $id)));
        if($getRenderer != false) {
            $install = $sys->input("start_installation", SET_STRING);
            if (!empty($install)) {

            }
            include $tmp->load("admin/monitorings/add_renderer.php");

        }
        else{
            echo $tmp->getError("not found !");
        }
    }
    elseif($do == "delete"){

        //TODO To Delete the renderer first of all you need to stop the crontab of the task reader and then delete it from DB
    }
    $renderers = $sys->fetch(array("table" => Databases::renderers));
    include $tmp->load("admin/monitorings/renderers.php");

    $tmp->getFooter($requireElements);
