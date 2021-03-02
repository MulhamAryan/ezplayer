<?php

    class Databases extends Cache {

        /**
         * @var stdClass
         */
        const admin_logs              = "admin_logs";
        const asset_infos             = "asset_infos";
        const classrooms              = "classrooms";
        const comments                = "comment";
        const courses                 = "courses_temp";
        const db_version              = "db_version";
        const enrollment              = "enrollment";
        const events                  = "events";
        const event_asset_parent      = "event_asset_parent";
        const event_last_indexes      = "event_last_indexes";
        const event_status            = "event_status";
        const logs                    = "logs";
        const messages                = "messages";
        const records                 = "records_temp";
        const rsync_album             = "rsync_album";
        const stats_video_infos       = "stats_video_infos";
        const stats_video_month_infos = "stats_video_month_infos";
        const stats_video_view        = "stats_video_view";
        const streams                 = "streams";
        const threads                 = "threads";
        const users                   = "users";
        const users_courses           = "users_courses";
        const user_bookmarks          = "user_bookmarks";
        const user_tokens_list        = "user_tokens_list";

        public $config;
        protected $DBDriver;
        protected $tmp;

        public function __construct()
        {
            parent::__construct();
            global $config;
            global $lang;
            global $tmp;
            $this->config = $config;
            $this->lang   = $lang;
            $this->tmp   = $tmp;
            require_once $this->config->directory["config"] . "/databases.php";
            require_once $this->config->directory["library"] . "/databases/" . $this->config->database["type"] . ".php"; //DB type is defined in /system/config/databases.php
            $this->DBDriver = new DBDriver($this->config->database["host"], $this->config->database["dbuser"], $this->config->database["dbpass"], $this->config->database["dbname"]);
        }

        public function __destruct(){
            $this->DBDriver->close();
        }

        public static function CheckDataType($value){
            switch ($value){
                case is_int($value):
                    return (int) $value;
                    break;

                case is_float($value):
                    return (float) $value;
                    break;

                case is_double($value):
                    return (double) $value;
                    break;

                case is_array($value):
                    return (array) $value;
                    break;

                case is_object($value):
                    return (object) $value;
                    break;

                case is_bool($value):
                    return (bool) $value;
                    break;

                case is_string($value):
                    return (string) addslashes($value);
                    break;

                default:
                    return (string) addslashes($value);
                    break;
            }

        }
        public static function initQuery(array $query){
            global $config;
            $fields  = (array_key_exists("fields",$query) ? $query["fields"] : "");
            $column  = (array_key_exists("column",$query) ? $query["column"] : "*");
            $keyword = (array_key_exists("keyword",$query) ? $query["keyword"] : "");

            if(!empty($fields)) {
                $itemsNumber = count($query["fields"]);
                $i = 0;
                $where[] = "where ";
                foreach ($query["fields"] as $key => $val) {
                    $val = self::CheckDataType($val);
                    $where[] = $key . " = '" . $val . "'";
                    if (++$i != $itemsNumber) {
                        $where[] .= " and ";
                    }

                }
                $where = implode($where);
            }
            else{
                $where = "";
            }
            $sqlQuery = "SELECT {$column} FROM {$config->database["prefix"]}{$query["table"]} {$where} {$keyword}";
            return $sqlQuery;
        }

        public function sql(string $query,string $function){
            return $this->DBDriver->sql($query,$function);
        }

        public function query(array $query){
            return $this->DBDriver->query($query);
        }

        public function select(array $query){
            return $this->DBDriver->select($query);
        }

        public function fetch(array $query){
            return $this->DBDriver->fetch($query);
        }

        public function insert(String $table,array $fields){
            return $this->DBDriver->insert($table,$fields);
        }

        public function update(array $query){
            return $this->DBDriver->update($query);
        }

        public function delete(array $query){
            return $this->DBDriver->delete($query);
        }

        public function getTable(string $tables){
            return $this->config->database["prefix"] . $tables;
        }
    }
