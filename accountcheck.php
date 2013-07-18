<?php

require_once 'lib/app.php';

$username = $_POST['username'];
if (!isset($username) || empty($username)) {
  exit;
}

use \KrowdByz\controllers\PlayerController;
if (PlayerController::is_username_taken($username)) {
  echo $username;
  exit;
}

?>