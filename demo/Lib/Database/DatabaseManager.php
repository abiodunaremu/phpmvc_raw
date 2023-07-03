<?php
namespace Lib\Database;

class DatabaseManager{
    private static $databaseConnection;
    private static $databaseManager;

    private function __construct(){
    }

    static function getInstance(){
        if(self::$databaseManager === null){
            self::$databaseManager = new DatabaseManager();
            self::connect_mysql();
        }
        return self::$databaseManager;
    }

    static function getConnection(){
        if(self::$databaseConnection === null){
            self::connect_mysql();
        }
        return self::$databaseConnection;
    }

    private static function connect_mysql()
    {    
        self::$databaseConnection = mysqli_connect("localhost",
        "root","welcome","yourbooks");
        
        // self::$databaseConnection = mysqli_connect("mirvellecom.ipagemysql.com",
        // "yb_usr","welCOME", "yourbooks_db");
    }

    function connect_mysql_defaultDB()
    {    
        // $databaseConnection = connect_mysql();
        self::$databaseConnection = self::connect_mysql();
        if (!self::$databaseConnection) {
            die('Could not connect: ' . mysqli_error($databaseConnection));
        }
        //mysqli_select_db("freemobile",$databaseConnection);
        return self::$databaseConnection;
    }

    static function mysql_query($query){
        if(self::$databaseConnection === null)
        self::$databaseConnection = self::getConnection();
        return mysqli_query(self::$databaseConnection,$query);
    }

    static function mysql_num_rows($result){
        return mysqli_num_rows($result);
    }

    static function mysql_fetch_array($result){
        return mysqli_fetch_array($result);
    }

    static function mysql_error(){
        if(self::$databaseConnection === null)
        self::$databaseConnection = self::getConnection();
        return mysqli_error(self::$databaseConnection);
    }
}
?>