<?php

namespace KrowdByz\models;
use \KrowdByz\helpers\Database;
use \KrowdByz\helpers\BCrypt;

class Player {
  
  public static function find_by_username($username) {
    $statement = Database::databaseInstance()->prepare("SELECT * FROM players WHERE username = ?");
	  $statement->bind_param('s', $username);
	  $ret = $statement->execute();
	  
	  if (!$ret) {
	    die("Could not connect to database");
	  }
	  
	  $statement->bind_result($id, $username, $password, $motto);
	  $ret = $statement->fetch();
	  
	  if ($ret === false) {
	    die('User Error');
	  }
	  
	  return ($ret === null) ? $ret : array(
	                                    'id'        => $id,
	                                    'username'  => $username,
	                                    'password'  => $password,
	                                    'motto'     => $motto
	                                  );
	}
	
	public static function create($username, $password, $motto) {
	  $db = Database::databaseInstance();
    $statement = $db->prepare("INSERT INTO players (username, password, motto) VALUES (?, ?, ?)");
    
    if (!$statement) {
      die('Could not create user');
    }
    
    $hash = self::hash_password($password);
	  $statement->bind_param('sss', $username, $hash, $motto);
	  
	  if (!$statement->execute()) {
	    if ($db->error) {
	      return $db->error;
	    }
	  }
	  
	  return '';
  }
	
	public static function hash_password($password) {
	  $crypt = new BCrypt();
	  return $crypt->hash($password);
	}
	
	public static function verify_password($password, $hash) {
	  $crypt = new BCrypt();
	  return $crypt->verify($password, $hash);
	}
  
}

?>