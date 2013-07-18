<?php

namespace KrowdByz\helpers;
use \KrowdByz\config\Settings;

class Util {
  
  public static function redirect_home() {
    $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';
    $url .= $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
    header("Location: " . $url ."/". Settings::$MAIN_PAGE);
  }
  
  public static function error_box() {
    $error_message = null;
    $button = null;
    
    if (isset($_SESSION['error']) && !empty($_SESSION['error']['message'])) {
      $error_message = $_SESSION['error']['message'];
      $button = (isset($_SESSION['error']['button'])) ? $_SESSION['error']['button'] : null;
      unset($_SESSION['error']);
    }
    
    return array($error_message, $button);
  }
  
}

?>