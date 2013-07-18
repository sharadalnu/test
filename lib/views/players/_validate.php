<?php
  use \KrowdByz\controllers\PlayerController;
  use \KrowdByz\controllers\SessionController;
  use \KrowdByz\helpers\Util;
  
  $_SESSION['close modal'] = true;

  if (!empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['motto'])) {
    $ret = PlayerController::create($_POST['username'], $_POST['password'], $_POST['motto']);
    if ($ret === true) {
      SessionController::login($_POST['username'], $_POST['password']);
      Util::redirect_home();
    } else {
      $_SESSION['register_error'] = array(
        'message' => 'Username taken.'
      );
    }
  }

?>