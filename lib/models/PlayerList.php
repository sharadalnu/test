<?php

namespace KrowdByz\models;
use \KrowdByz\helpers\Database;

class PlayerList {
  
  public static function addPlayer($username, $categoryID) {
    $db = Database::databaseInstance();
	
	$time=date("Ymdhms");
    $statement = $db->prepare("INSERT INTO player_list (category_id, username,time) VALUES (?, ? ,$time)");
    
    if (!$statement) {
      die('Could not create user');
    }
    
    $statement->bind_param('is', $categoryID, $username);
	  
	  if (!$statement->execute()) {
	    if ($db->error) {
	      return $db->error;
	    }
	  }
	  
	  return '';
  }
  
  public static function findPlayers($categoryID) {
    $players = array();

		// select categories from DB
		$statement = Database::databaseInstance()->prepare("SELECT username, status FROM player_list WHERE category_id = ?");
		
		if (!$statement) {
		  die("Could not retrieve categories");
		}
		
		$statement->bind_param('i', $categoryID);
		
		$ret = $statement->execute();
		
		if (!$ret) {
		  die("Could not retrieve categories");
		}
		
		$statement->bind_result($username, $status);
		
		while ($statement->fetch()) {
		  
		  
			array_push($players, array(
			                      'username' => $username,
			                      'status' => ($status == 0) ? 'available' : 'busy'
			));
		}
		
		return $players;
  }
  
}

?>