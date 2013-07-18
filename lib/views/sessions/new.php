<?php

use \KrowdByz\controllers\SessionController;

SessionController::verbose_login($_POST['username'], $_POST['password']);

?>