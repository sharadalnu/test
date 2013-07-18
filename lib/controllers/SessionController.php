<?php

namespace KrowdByz\controllers;
use \KrowdByz\models\Player;

class SessionController {
  
  public static function login($username, $password) {
    if (empty($username) || empty($password)) {
      return '';
    }
    
    $player = Player::find_by_username($username);
    
    if ($player === null) {
      return 'No such account';
    }
    
    if (!Player::verify_password($password, $player['password'])) {
      return 'Wrong password';
    }
    
    $_SESSION['player_id'] = $player['id'];
    $_SESSION['player_username'] = $player['username'];
    $_SESSION['player_motto'] = $player['motto'];
    $_SESSION['player_time'] = time();
    
    return 'Success';
  }
  
  public static function verbose_login($username, $password) {
    echo self::login($username, $password);
  }
  
  public static function logout() {
    self::destroy_session();
  }
  
  public static function is_logged_in() {
    $in = !empty($_SESSION['player_username']);
    
    if ($in && time() - $_SESSION['player_time'] > 2 * 60 * 60 * 1000) {
      $in = false;
      self::destroy_session();
    }
    
    return $in;
  }
  
  private static function destroy_session() {
    unset($_SESSION['player_id']);
    unset($_SESSION['player_username']);
    unset($_SESSION['player_motto']);
    unset($_SESSION['player_time']);

  }
  
}

?>