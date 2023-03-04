<?php

class Database {//création d'une classe qi permet la connection à la DB

    private static $dbName = 'connexion' ;//récupération des indentifiants de connection
    private static $dbHost = 'localhost:3306' ;
    private static $dbUsername = 'root';
    private static $dbUserPassword = 'root';
    private static $connexion = null;

    public function __construct() { 
        die("Fonction d'initialisation pas permise !!!");        
    }
    
    public static function connect() { // connection classique
        if ( null == self::$connexion ) { 
            try { 
                self::$connexion = new PDO( "mysql:host=".self::$dbHost.";"."dbname=".self::$dbName, self::$dbUsername, self::$dbUserPassword);  
            } 
            catch(PDOException $e) { 
                die($e->getMessage()); 
            }
        } 
    return self::$connexion;
    }

    public static function disconnect()
    {
        self::$connexion = null;
    }
}
?>