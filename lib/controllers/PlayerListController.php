<?php

namespace KrowdByz\controllers;
use \KrowdByz\models\Category;
use \KrowdByz\models\PlayerList;

class PlayerListController {
  
  public static function start() {
    if (!isset($_GET['category'])) {
      exit;
    }
    
    $id = Category::selectIDFromCategory($_GET['category']);
	
    //PlayerList::addPlayer($_SESSION['player_username'], $id);
    
    return $id;
  }
  public static function online() {
    if (!isset($_GET['category'])) {
      exit;
    }
    
    $id = Category::selectIDFromCategory($_GET['category']);
    PlayerList::addPlayer($_SESSION['player_username'], $id);
     }
  
  public static function show($categoryID) {
    return PlayerList::findPlayers($categoryID);
  }
  public static function showLeaders($categoryID,$numLeaders) {
    return PlayerList::findLeaders($categoryID,$numLeaders);
  }
}

?>