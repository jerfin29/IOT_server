<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';

class Database {
    static $db;
    public static function getConnection(){
        $config_json = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/../env.json');
        $config = json_decode($config_json, true);
        if (Database::$db != NULL) {
            return Database::$db;
        } else {
            // Database::$db = mysqli_connect($config['server'],$config['username'],$config['password'], $config['database']);
            $mongoClient  = new MongoDB\Client("mongodb://127.0.0.1:27017");
            // $mongoClient = new MongoDB\Client("mongodb://{$config['username']}:{$config['password']}@cluster0.mongodb.net/users");

            // $mongoClient = new MongoDB\Client("mongodb://Jerlin:U!N2DP12HxUL@10.11.3.25:27017/?authMechanism=DEFAULT&authSource=users");
            Database::$db = $mongoClient->{$config['database']};
            // echo $config['database'];
            if (!Database::$db) {
                die("MongoDB Connection failed");
            } else {    
                return Database::$db;
            }
        }
    }

    public static function getCurrentDB() {
        $config_json = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/../env.json');
        $config = json_decode($config_json, true);
        return $config['database'];
    }

    public static function getArray($doc){
        return json_decode(json_encode($doc), true);
    }
    
}