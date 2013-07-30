<?php

namespace KrowdByz\controllers;
use \KrowdByz\models\Category;

class CategoryController {
  
  public static function iterate($html) {
    $categories = Category::find();
    $colors = self::calculateColors(count($categories));
    
    for ($i = 0; $i < count($categories); $i++) {
      $resp = str_replace("{{category}}", $categories[$i][1], $html);
	  $resp=str_replace("{{categoryid}}", $categories[$i][0], $resp);
      echo str_replace("{{color}}", $colors[$i], $resp);
    }
  }
  
  private static function calculateColors($amount) {
    $rainbow = array ("green", "orange", "blue"); // available colors
    $colors = array();
    for ($i = 0; $i < $amount; $i++) {
      array_push($colors, $rainbow[$i % count($rainbow)]);
    }
    
    return $colors;
  }
  
}

?>