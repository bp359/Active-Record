<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL);
define('DATABASE', 'bp359');
define('USERNAME', 'bp359');
define('PASSWORD', 'bTDLnIOQ');
define('CONNECTION', 'sql2.njit.edu');

class dbConn{
    protected static $db;

    public function __construct() {
        try {
            // assign PDO object to db variable
            self::$db = new PDO( 'mysql:host=' . CONNECTION .';dbname=' . DATABASE, USERNAME, PASSWORD );

            self::$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            echo 'Connected successfully<br>';
        }

        catch (PDOException $e) {
            //Output error - would normally log this to error file rather than output to user.
            echo "Connection Error: " . $e->getMessage();
        }
    }
    // get connection function. Static method - accessible without instantiation
    public static function getConnection() {
        //Guarantees single instance, if no connection object exists then create one.
        if (!self::$db) {
            //new connection object.
            new dbConn();
        }
        //return connection.
        return self::$db;
    }
}
class collection{
        static public function create(){
            $model=new static ::$modelName;
            return $model;
        }
        static public function findAll(){

            $db=dbConn::getConnection();
            $tableName=get_called_class();
            $sql='SELECT*FROM'.$tableName;
            $statement=$db->prepare ($sql);
            $statement->execute();
            $class=static::$modelName;
            $statement->setFetchmode(PDO::FETCH_CLASS,$class);
            $recordSet=$statement->fetchAll();
            return $recordSet;
        }
        public function findOne($id){
            $db = dbConn::getConnection();

            $tableName = get_called_class();

            $sql = 'SELECT * FROM ' . $tableName . ' WHERE id =' . $id;           
            $statement = $db->prepare($sql);
             $statement->execute();
            $class = static::$modelName;
            $statement->setFetchMode(PDO::FETCH_CLASS,$class);
            $recordsSet  =  $statement->fetchAll();
            return $recordsSet;
        }
        class accounts extends collection{
            protected static $modelName='accounts';
        }
             
         class todos extends collection{
             protected static $modelName='todos';
         }
           
            
            
        
}


