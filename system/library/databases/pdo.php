<?php

    class DBDriver{
        public function __construct($host,$dbuser,$dbpass,$dbname)
        {
            global $config;
            $this->config = $config;
            try {
                $this->pdo = new PDO("mysql:host={$host};dbname={$dbname};charset={$this->config->database["charset"]}", $dbuser, $dbpass);
                //$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            }
            catch (PDOException $e){
                die($e->getMessage());
            }
        }

        public function sql(string $query,string $function){

            $select = $this->pdo->prepare($query);
            if($select){
                if($function == "fetch"){
                    $select->execute();
                    $result = $select->fetchAll(PDO::FETCH_ASSOC);
                    return (is_null($result) ? false : $result);
                }
                elseif($function == "select"){
                    $select->execute();
                    $result = $select->fetch(PDO::FETCH_ASSOC);
                    return (is_null($result) ? false : $result);
                }
                else{
                    die("Unknown function for 'Database->sql({$query},function : '{$function}')'");
                }
            }
            else{
                throw new PDOException($this->pdo->errorInfo());
            }
        }

        /*public function query(array $query){
            $condition = (array_key_exists("condition",$query) ? $query["condition"] : "");
            $fields    = (array_key_exists("fields",$query) ? $query["fields"] : "*");

            $select = $this->pdo->prepare("SELECT {$fields} FROM {$this->config->database["prefix"]}{$query["table"]} {$condition}");
            if($select){
                return $select;
            }
            else{
                throw new PDOException($this->pdo->errorInfo());
            }
        }*/

        public function query(array $query){
            $sqlQuery = Databases::initQuery($query);
            $select = $this->pdo->prepare($sqlQuery);
            if($select){
                return $select;
            }
            else{
                var_dump($this->pdo->errorInfo());
                exit();
                throw new PDOException($this->pdo->errorInfo());
            }
        }

        public function select(array $query){
            $result = $this->query($query);
            $result->execute();

            $result = $result->fetch(PDO::FETCH_ASSOC);

            return (is_null($result) ? false : $result);
        }

        public function fetch(array $query){
            $result = $this->query($query);
            $result->execute();

            $result = $result->fetchAll(PDO::FETCH_ASSOC);

            return (is_null($result) ? false : $result);
        }

        public function insert(String $table,array $fields){
            foreach ($fields as $c => $v){
                $v = (is_string($v) ? "'" . addslashes($v) . "'" :$v);
                $columns[] = $c;
                $values[]  = $v;
            }
            $columns = implode(",",$columns);
            $values  = implode(",",$values);

            $sql = $this->pdo->prepare("INSERT INTO {$this->config->database["prefix"]}{$table} ({$columns}) VALUES ($values)");
            return $this->validate($sql);
        }

        public function update(array $query){
            $sql = $this->pdo->prepare("UPDATE {$this->config->database["prefix"]}{$query["table"]} SET {$query["fields"]}");
            return $this->validate($sql);
        }

        public function delete(array $query){
            $sql = $this->pdo->prepare("DELETE FROM {$this->config->database["prefix"]}{$query["table"]} where {$query["condition"]}");
            return $this->validate($sql);
        }

        public function validate($statement){
            if($statement->execute()){
                return true;
            }
            else{
                var_dump($statement->errorInfo());
            }
        }

        public function close(){
            $this->pdo = null;
        }
    }