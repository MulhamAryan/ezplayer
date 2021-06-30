<?php

/*$data = [
    'email' => 'jsnow@got.com',
    'token' => 'f4q6w87h6e4r'
];
$username = "auth-user";
$password = "auth-pwd";
$header = array(
    'Accept: application/json'
);

$string = http_build_query($data);

$ch = curl_init("https://ezcasttest.ulb.ac.be/newezplayer/api/create_users.php");
curl_setopt($ch,CURLOPT_HTTPHEADER, $header);
curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch,CURLOPT_POST, true);
curl_setopt($ch,CURLOPT_POSTFIELDS, $string);
curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch,CURLOPT_USERPWD, $username . ":" . $password);
curl_setopt($ch,CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
var_dump($response);
curl_close($ch);*/
define("JSON",'JSON');
define("RAW",'RAW');

class Curl extends System {

    private $options;
    private $curl_init;
    private $dataType;

    public function __construct()
    {
        parent::__construct();
        $this->curl_init = curl_init();
        $this->dataType = RAW;

        $this->options = array();
        $this->options['CURLOPT_USERAGENT']      = 'MoodleBot/1.0';
        $this->options['CURLOPT_HEADER']         = false;
        $this->options['CURLOPT_NOBODY']         = false;
        $this->options['CURLOPT_MAXREDIRS']      = 10;
        $this->options['CURLOPT_RETURNTRANSFER'] = true;
        $this->options['CURLOPT_BINARYTRANSFER'] = false;
        $this->options['CURLOPT_SSL_VERIFYHOST'] = 2;
        $this->options["CURLOPT_SSL_VERIFYPEER"] = false;
        $this->options["CURLOPT_POST"]           = true;
        $this->options["CURLOPT_TIMEOUT"]        = 30;
    }

    public function setOption(string $option,$value)
    {
        $this->options[$option] = $value;
    }

    public function setOptions()
    {
        foreach ($this->options as $key => $value){
            if (is_string($key)) {
                $name = constant(strtoupper($key));
            }
            curl_setopt($this->curl_init, $name, $value);
        }
        echo "<pre>";
        //var_dump($this->options);
    }

    public function setUrl(string $url)
    {
        if(!empty($url)){
            $this->setOption("CURLOPT_URL",$url);
        }
        else{
            $this->setOption("CURLOPT_URL",false);
        }
    }

    public function setParams($parameters = array())
    {
        //var_dump($this->options["CURLOPT_HTTPHEADER"]);
        /*foreach ($this->options["CURLOPT_HTTPHEADER"] as $header){
            $headers[] = $header;
        }
        $headers[] = $parameters;
        $this->setOption("CURLOPT_HTTPHEADER", array('Content-Length: ' . count($headers)));*/
        $this->setOption("CURLOPT_POSTFIELDS",$parameters);
        $this->setOption("CURLOPT_RETURNTRANSFER",true);
    }

    public function post($parameters = array()){
        if($this->dataType == JSON){
            $httpQuery = json_encode($parameters,true);
        }
        else{
            $httpQuery = http_build_query($parameters,'', '&');
        }
        $this->setOption("CURLOPT_POST",1);
        $this->setParams($httpQuery);
    }

    public function send(){

        $this->setOptions();
        //var_dump($this->options);
        $answer = curl_exec($this->curl_init);
        return $answer;
    }

    public function setUserAgent(string $userAgent)
    {
        if(!empty($userAgent)){
            $this->setOption("CURLOPT_USERAGENT",$userAgent);
        }
    }


    public function setType($type)
    {
        switch ($type) {
            case JSON:
                $header = array("Content-type: application/json");
                break;

            default:
                $header = false;
                break;
        }
        $this->dataType = $type;
        if($header != false)
            $this->setOption("CURLOPT_HTTPHEADER", $header);
    }
}