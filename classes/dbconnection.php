<? php

class dbconnection{
	protected static $db;
	private function _construct(){
		try{
			//assigning PDO object to the database variable
			self::$db=new PDO('mysql:host='.CONNECTION .;'dbname='.DATABASE,USERNAME,PASSWORD);
			self::$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			echo"Connected Successfully <br>";
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
				new dbconnection();
			}
			//return connection.
			return self::$db;
		}


		}
	
}