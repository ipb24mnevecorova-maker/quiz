<?php 

class Database{
        private $pdo;

        public function __construct($config){
            $dsn = "mysql:" . http_build_query($config, arg_separator: ";");
            $this->pdo = new PDO($dsn);
        }
        public function query($sql, $params = []) {
            $statment = $this->pdo->prepare($sql);
            $statment->execute($params);
            return $statment;
        }   
    
}