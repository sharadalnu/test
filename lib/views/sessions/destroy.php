<?php

use \KrowdByz\controllers\SessionController;
use \KrowdByz\helpers\Util;

SessionController::logout();
Util::redirect_home();

?>