<?php

    class SSH extends System {
        private $host;
        private $port;
        private $username;
        private $publicKey;
        private $privateKey;
        private $authPass;
        private $connect;
        private $timeout = 5;
        public $errorMessage = array("error" => false, "message" => "");

        public function __construct($username, $host, $port = 22)
        {
            parent::__construct();
            $this->host = $host;
            $this->username = $username;
            $this->publicKey = $this->config->admin["rsa_key_file"];
            $this->privateKey = $this->config->admin["rsa_private_key"];
            $this->port = $port;

            if ($this->ping()) {
                if (!($this->connect = ssh2_connect($this->host, $this->port))) {
                    $this->errorMessage  = array(
                        "error" => true,
                        "message" => "ERROR 2 : " . $this->lang["admin"]["unavailable_server"]
                    );
                }
                if (@!ssh2_auth_pubkey_file($this->connect, $this->username, $this->publicKey, $this->privateKey, $this->authPass)) {
                    $this->errorMessage  = array(
                        "error" => true,
                        "message" => "ERROR 3 : " . $this->lang["admin"]["unavailable_server"]
                    );
                }
            }
            else{
                $this->errorMessage  = array(
                    "error" => true,
                    "message" => "ERROR 1 : " . $this->lang["admin"]["unavailable_server"]
                );
            }
        }
        public function ping(){
            $fp = @fsockopen($this->host, $this->port, $errno, $errstr, $this->timeout);
            if (!$fp) {
                return false;
            } else {
                fclose($fp);
                return true;
            }
        }
        public function exec($cmd) {
            if (!($stream = ssh2_exec($this->connect, $cmd))) {
                $this->errorMessage  = array(
                    "error" => true,
                    "message" => "ERROR 4 : " . $this->lang["admin"]["unavailable_server"]
                );
                exit();
            }
            stream_set_blocking($stream, true);
            $data = "";
            while ($buf = fread($stream, 4096)) {
                $data .= $buf;
            }
            fclose($stream);
            return $data;
        }
        public function disconnect() {
            if($this->errorMessage["error"] == false) {
                $this->exec('echo "EXITING" && exit;');
                $this->connect = null;
            }
        }

        public function __destruct() {
            $this->disconnect();
        }
    }