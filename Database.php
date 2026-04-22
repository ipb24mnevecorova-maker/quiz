<?php 



class Database {
    private $pdo;

    public function __construct($config){
        $dsn = "mysql:" . http_build_query($config, arg_separator: ";");
        try {
            $this->pdo = new PDO($dsn);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Datubāzes savienojuma kļūda: " . $e->getMessage());
        }
    }
    
    public function query($sql, $params = []) {
        try {
            $statement = $this->pdo->prepare($sql);
            $statement->execute($params);
            return $statement;
        } catch (PDOException $e) {
            die("SQL kļūda: " . $e->getMessage() . "<br>SQL: " . $sql);
        }
    }   
}