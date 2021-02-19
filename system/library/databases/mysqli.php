<?php

    class DBDriver{
        private $config;
        public function __construct($host,$dbuser,$dbpass,$dbname)
        {
            global $config;

            mysqli_report(MYSQLI_REPORT_STRICT);
            $this->config = $config;

            try {
                $this->mysqli = new mysqli($host, $dbuser, $dbpass, $dbname);
            }
            catch (mysqli_sql_exception $e){
                throw $e;
            }
        }

        public function sql($query,string $function){
            $select = $this->mysqli->query($query);
            if($select){
                if($function == "fetch"){
                    $result = $select->fetch_all(MYSQLI_ASSOC);
                    $select->free_result();
                    return (is_null($result) ? false : $result);
                }
                elseif($function == "select"){
                    $result = $select->fetch_assoc();
                    $select->free_result();
                    return (is_null($result) ? false : $result);
                }
                else{
                    die("Unknown function for 'Database->sql({$query},function : '{$function}')'");
                }
            }
            else{
                throw new mysqli_sql_exception($this->mysqli->error);
            }
        }

        /*public function query(array $query){
            $condition = (array_key_exists("condition",$query) ? $query["condition"] : "");
            $fields    = (array_key_exists("fields",$query) ? $query["fields"] : "*");

            $select = $this->mysqli->query("SELECT {$fields} FROM {$this->config->database["prefix"]}{$query["table"]} {$condition}");
            if($select){
                return $select;
            }
            else{
                throw new mysqli_sql_exception($this->mysqli->error);
            }
        }*/

        public function query(array $query){
            $sqlQuery = Databases::initQuery($query);

            $select = $this->mysqli->query($sqlQuery);
            if($select){
                return $select;
            }
            else{
                throw new mysqli_sql_exception($this->mysqli->error);
            }
        }

        public function select(array $query){
            $select = $this->query($query);

            $result = $select->fetch_assoc();
            $select->free_result();

            return (is_null($result) ? false : $result);
        }

        public function fetch(array $query){
            $select = $this->query($query);
            $result = $select->fetch_all(MYSQLI_ASSOC);
            $select->free_result();

            return (is_null($result) ? false : $result);
        }

        public function insert(String $table,array $fields){
            foreach ($fields as $c => $v){
                $v = (is_string($v) ? "'{$v}'" : $v);
                $columns[] = $c;
                $values[]  = $v;
            }
            $columns = implode(", ",$columns);
            $values  = implode(", ",$values);

            $sql = $this->mysqli->query("INSERT INTO {$this->config->database["prefix"]}{$table} ({$columns}) VALUES ($values)");
            return $this->validate($sql);
        }

        public function update(array $query){
            $sql = $this->mysqli->query("UPDATE {$this->config->database["prefix"]}{$query["table"]} SET {$query["fields"]}");
            return $this->validate($sql);
        }

        public function delete(array $query){
            $sql = $this->mysqli->query("DELETE FROM {$this->config->database["prefix"]}{$query["table"]} where {$query["condition"]}");
            return $this->validate($sql);
        }

        public function validate($statement){
            if($statement){
                return true;
            }
            else{
                throw new mysqli_sql_exception($this->mysqli->error);
            }
        }

        public function close(){
            $this->mysqli->close();
        }
    }