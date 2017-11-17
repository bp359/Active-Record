<? php
ini_set('display_errors','On');
error_reporting(E_ALL);

define('CONNECTION','sql2.njit.edu');
define('USERNAME','bp359');
define('PASSWORD','bTDLnIOQ');
define('DATABASE','bp359');

class dbConn{
	protected static $db;
	private function  __construct(){
		try{
			//assigning PDO object to the database variable
			self::$db=new PDO('mysql:host='.CONNECTION .;'dbname='.DATABASE,USERNAME,PASSWORD);
			self::$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			echo "Connected Successfully <br>";
		}
		catch(PDOException $e){
			//output error-logs into the file rather than output to user
			echo "Connection Error : ".$e->getMessage();
		}
		//get connectuin function. Static method is accessible without instation.
		public static function getConnection(){
			//guarantess single instance, if no connection object exists then create one.
			if(!self::$db){
				//new connection object.
				new dbConn();
			}
			//return connection.
			return self::$db;
		}


		}
	
}

?>