<?php
include "../../config.php";
include "../configurations.php";

$requireElements = array("requireLeftMenu" => true, "requireBody" => true);
$tmp->getHeader($requireElements);
$do = $sys->input("do",SET_STRING);
if($do == "add"){
    $submit = $sys->input("submit",SET_STRING);
    if(!empty($submit)){
        $auditorium_name   = $sys->input("auditorium_name",SET_STRING);
        $machine_usernane  = $sys->input("machine_username",SET_STRING);
        $auditorium_ip     = $sys->input("auditorium_ip",SET_STRING);
        $install_dir       = $sys->input("installation_dir",SET_STRING);
        $apache_dir        = $sys->input("apache_dir",SET_STRING);
        $recorder_data_dir = $sys->input("record_dir",SET_STRING);
        $ezrecorder_git    = $sys->input("giturl",SET_STRING);
        $phpcli            = $sys->input("php_cli",SET_ARRAY_WITH_KEY);
        $ffmpegcli         = $sys->input("ffmpeg_cli",SET_ARRAY_WITH_KEY);
        $ffprobecli        = $sys->input("ffprobe_cli",SET_ARRAY_WITH_KEY);
        $api_key           = $sys->input("api_key",SET_STRING);
        $offline_install   = $sys->input("offline_install",SET_INT);
        if($offline_install == 1){
            $success_install = 1;
        }
        if($success_install == 1){
            $auditoriumTable = $sys->getTable(Databases::auditoriums);
            
            //$checkExistance = $sys->sql("SELECT * FROM {$auditoriumTable}","select");
        }
    }
    include $tmp->load("admin/auditorium/add.php");
}
include $tmp->load("admin/auditorium/home.php");
$tmp->getFooter($requireElements);