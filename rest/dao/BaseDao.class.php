<?php

require_once __DIR__."/../config.php";

class BaseDao {
    private $connection;
    private $tableName;

    public function __construct($tableName){
        try {
            $this->tableName = $tableName;
            $servername = Config::DB_HOST();
            $username = Config::DB_USERNAME();
            $password = Config::DB_PASSWORD();
            $schema = Config::DB_SCHEMA();
            $port = Config::DB_PORT();
            $this->connection = new PDO("mysql:host=$servername;port=$port;dbname=$schema",$username,$password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //echo "Connected successfully";
        } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    //Method used to get all entities from db
    public function get_all() {
        $stmt = $this->connection->prepare("SELECT * FROM " . $this->tableName);
        $stmt->execute();
        return $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Method used to get entitiy from db by id
    public function get_by_id($id) {
        $stmt = $this->connection->prepare("SELECT * FROM " .  $this->tableName . " WHERE id=:id");
        $stmt->execute(['id' => $id]);
        return $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Method used to add entity to db
    public function add($entity) {
        $query = "INSERT INTO " . $this->tableName . " (";
        foreach($entity as $column => $value){
            $query.= $column . ", ";
        }
        $query = substr($query, 0, -2);
        $query.= ") VALUES (";
        foreach($entity as $column => $value){
            $query.= ":" . $column . ", ";
        }
        $query = substr($query, 0, -2);
        $query.= ")";
        $stmt = $this->connection->prepare($query);
        $stmt->execute($entity);
        $entity['id'] = $this->connection->lastInsertId();
        return $entity;
    }

    //Method used to update entity from db
    public function update($entity, $id, $id_column = "id") {
        $query = "UPDATE " . $this->tableName . " SET ";
        foreach($entity as $column => $value){
            $query.= $column . "=:" . $column . ", ";
        }
        $query = substr($query, 0, -2);
        $query.= " WHERE {$id_column} = :id";
        $entity['id'] = $id;
        $stmt = $this->connection->prepare($query);
        $stmt->execute($entity);
        return $entity;
    }


    //Method used to delete entities from db
    public function delete($id) {
        $stmt = $this->connection->prepare("DELETE FROM " . $this->tableName . " WHERE id=:id");
        $stmt->bindParam(':id', $id); #prevent SQL injection
        $stmt->execute();
    }

    protected function query($query, $params){
        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
      }
  
      protected function query_unique($query, $params){
        $results = $this->query($query, $params);
        return reset($results);
      }
}
?>