<?php

namespace KrowdByz\models;
use \KrowdByz\helpers\Database;

class Category {
  
  public static function find() {
    $categories = array();

		// select categories from DB
		$statement = Database::databaseInstance()->prepare("SELECT id,category FROM categories where display=1");
		
		if (!$statement) {
		  die("Could not retrieve categories");
		}
		
		$ret = $statement->execute();
		
		if (!$ret) {
		  die("Could not retrieve categories");
		}
		
		$statement->bind_result($col1, $col2);
		
		while ($statement->fetch()) {
		    $category=array($col1,$col2);
			array_push($categories, $category);
		}
		
		return $categories;
  }
  
  public static function selectIDFromCategory($category) {
    $statement = Database::databaseInstance()->prepare("SELECT id FROM categories WHERE category = ? and display=1");
    $statement->bind_param('s', $category);
	  $ret = $statement->execute();
	  
	  if (!$ret) {
	    die("Could not connect to database");
	  }
	  
	  $statement->bind_result($id);
	  $ret = $statement->fetch();
	  
	  if ($ret === false) {
	    die('User Error');
	  }
	  
	  return $id;
  }
  
}

?>