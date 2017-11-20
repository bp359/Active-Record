<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL);
define('DATABASE', 'bp359');
define('USERNAME', 'bp359');
define('PASSWORD', 'bTDLnIOQ');
define('CONNECTION', 'sql1.njit.edu');

class dbConn{
    protected static $db;

    public function __construct() {
        try {
            // assign PDO object to db variable
            self::$db = new PDO( 'mysql:host=' . CONNECTION .';dbname=' . DATABASE, USERNAME, PASSWORD );

            self::$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            echo 'Connected successfully <br>';
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
class collection
{
    static public function create()
      {
        $model = new static::$modelName;
        return $model;
      }
       
    public  function findAll()
      {
         $db = dbConn::getConnection();
         $tableName = get_called_class();
         $sql = 'SELECT * FROM ' . $tableName;
         $statement = $db->prepare($sql);
         $statement->execute();
            
         $class = static::$modelName;
         $statement->setFetchMode(PDO::FETCH_CLASS, $class);
        
         $recordsSet =  $statement->fetchAll();
         return $recordsSet;
      }
    public  function findOne($id)
      {
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
}
      
class accounts extends collection
{
    protected static $modelName='accounts';
}
class todos extends collection
{
    protected static $modelName='todos';
}
class model
{
          static $columnString;
          static $valueString;
       
          public function save()
           {
             if (static::$id == '')
              {
               $db=dbConn::getConnection();
               $array = get_object_vars($this);
               static::$columnString = implode(', ', $array);
               static::$valueString = implode(', ',array_fill(0,count($array),'?'));
               $sql = $this->insert();
               $stmt=$db->prepare($sql);
               $stmt->execute(static::$data);
              }

             else
              {
               $db=dbConn::getConnection();
               $array = get_object_vars($this);
               $sql = $this->update();
               $stmt=$db->prepare($sql);
               $stmt->execute();
              }

           }

           private function insert()
            {
                $sql = "INSERT INTO ".static::$tableName." (". static::$columnString . ") Values(". static::$valueString . ") ";
                return $sql;
            }
           private function update()
            {
                $sql = "UPDATE ".static::$tableName. " SET ".static::$columnUpdate."='".static::$newInfo."' WHERE id=".static::$id;
                return $sql;
             }
                    
                   
            public function delete()
             {
                $db=dbConn::getConnection();
                $sql = 'Delete From '.static::$tableName.' WHERE id='.static::$id;
                $stmt=$db->prepare($sql);
                $stmt->execute();
                echo'Deleted record which has ID :'.static::$id;
             }
}
     
class account extends model
{
              public $email = 'email';
              public $fname = 'fname';
              public $lname = 'lname';
              public $phone =  'phone';
              public $birthday = 'birthday';
              public $gender= 'gender';
              public $password = 'password';
              static $tableName = 'accounts';
              static $id = '8';
              static $data = array('bp359@njit.edu','Bikki','Pahari','1','1991-10-05','Male','biiku');
              
              static $columnUpdate = 'lname';
              static $newInfo ='Jacobs';
}
class todo extends model
{
               public $owneremail = 'owneremail';
               public $ownerid = 'ownerid';
               public $createddate = 'createddate';
               public $duedate = 'duedate';
               public $message = 'message';
               public $isdone = 'isdone';
               static $tableName = 'todos';
               static $id = '6';
               static $data = array('nyu@gmail.com','6','2017-11-18','2017-11-22','Done','01');
               
               static $columnUpdate = 'isdone';
               static $newInfo ='1';
}
class table
{
        static  function createTable($result)
        {
            echo '<table>';
            echo "<table cellpadding='10px' border='2px' style='border-collapse:collapse' text-align :'center' width ='100%'white-space : nowrap'font-''weight:bold'>";
            foreach($result as $column)
            {

                echo '<tr>';
                foreach($column as $row)
                   {
                     echo '<td>';
                     echo $row;
                     echo '</td>';
                    }
                echo '</tr>';
            }
            echo '</table>';
        }
}
         echo '<h2>Select all records from Accounts Table  </h2>';

         $records = accounts::create();
         $result = $records->findAll();
         table::createTable($result);


         echo '<br>';
       


         echo '<h2>Select an ID from Accounts Table where ID is : 10 </h2>';

         $result= $records->findOne(10);
         table::createTable($result);
         echo '<br>';
         


         echo '<h2>Select records from Todos Table </h2>';

         $records = todos::create();
         $result= $records->findAll();
         table::createTable($result);
         
         echo '<br>';
      


         echo '<h2>Select ID  from Todos Table where ID: 124 <h2>';

         $result= $records->findOne(124);
         table::createTable($result);

         
         echo '<h2>Insert New Row in Todos Column </h2>';
         
            $obj = new todo;
            $obj->save();

            $records = todos::create();
            $result= $records->findAll();
            table::createTable($result);
            echo '<br>';



         echo '<h2>Update Phone Column in Accounts Table</h2>';
        
                $obj = new account;
                $obj->save();

                $records = accounts::create();
                $result = $records->findAll();
         table::createTable($result);
         
         echo '<br>';

        echo '<h2>Delete ID from Todos Table </h2>';
            $obj = new todo;
            $obj->delete(6);
            
            $records = todos::create();
            $result= $records->findAll();
            table::createTable($result);


        
?>