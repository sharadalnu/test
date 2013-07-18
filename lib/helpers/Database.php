<?php
namespace KrowdByz\helpers;
use \KrowdByz\config\Settings;

class Database {
  
  private static $database;
  
  public static function databaseInstance() {
    if (!isset($database)) {
      $db = new Database();
      self::$database = $db->getConnection();
    }
    
    return self::$database;
  }

	private $connection;

	public function __construct() {
	   $this->connection = new \mysqli(Settings::$DATABASE['host'],
	                                      Settings::$DATABASE['username'], 
	                                      Settings::$DATABASE['password'], 
	                                      Settings::$DATABASE['name']
	                                     
	                                      );
	       
	   if ($this->connection->connect_error) {
       die('Connect Error');
     }
	}
	
	public function getConnection() {
		return $this->connection;
	}
	
	public function __destruct() {
	  $this->connection = null;
	}

}