<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
include "../config.php";
include $config->directory["library"] . "/curl.php";

$curl = new Curl();

$curl->setUrl("https://ezcasttest.ulb.ac.be/newezplayer/api/webservices/create_courses.php");

$curl->setType(RAW);
$curl->setUserAgent("podcast");
$parameters = array();
$parameters["token"] = "46f87qwe4g53h46je7rktl845uhe68";
for($i = 0; $i <= 5; $i++) {
    $parameters["data"][] =
        array(
            "course_code" => "TEST-CONNECTEUR-{$i}", "course_code_public" => "", "course_name" => "WTest creation cours depuis API Podcast {$i}", "shortname" => "TC{$i}", "token" => "",
            "in_recorders" => 1, "has_albums" => 1, "downloadable" => 1, "anon_access" => 0, "date_created" => time(), "origin" => "external", "history" => ""
        );
}
$curl->post($parameters);

var_dump($curl->send());