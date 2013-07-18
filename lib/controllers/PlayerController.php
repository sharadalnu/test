<?php

namespace KrowdByz\controllers;
use \KrowdByz\models\Player;

class PlayerController {
  
  public static function create($username, $password, $motto) {
    $ret = Player::create($username, $password, $motto);
    
    return strpos($ret, 'Duplicate') !== 0;
  }
  
  public static function is_username_taken($username) {
    return Player::find_by_username($username) !== null;
  }
  
}

?>