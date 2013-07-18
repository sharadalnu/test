<?php

namespace {
  session_start();
  
  require_once 'helpers/ClassLoader.php';
  $class_loader = new \KrowdByz\helpers\ClassLoader();
  $class_loader->register();
  
  \KrowdByz\helpers\Database::databaseInstance(); // Open DB Connection
}

?>