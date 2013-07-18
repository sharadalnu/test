<?php use \KrowdByz\controllers\SessionController; ?>

<!DOCTYPE html>
<html lang="en" class="no-js">

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

        <script src="assets/js/jquery.min.js"></script>
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/timeTo.css">
 	<link rel="stylesheet" href="assets/css/custom.css">
	<script src="assets/js/modernizr.js"></script>
</head>

<body>
  
  <?php require("navbar.php") ?>
  
  <?php
    $e = \KrowdByz\helpers\Util::error_box();
    $error_message = $e[0];
    $button = $e[1];
  ?>
  
  <!-- Alert messages -->
  <div id="message-box" class="container">
    <div id="notifications" class="alert alert-block fade in <?php if (!$error_message) { echo 'hide'; } ?>">
      <button type="button" class="close">&times;</button>
      <h4 class="alert-heading"><?php if ($error_message) { echo $error_message; } ?></h4>
      <p></p>
      <p>
        <a class="btn btn-warning <?php if (!$button) { echo 'hide'; } ?>" href="#"><?php if ($button) { echo $button; } ?></a>
      </p>
    </div>
  </div>
  
  <?php require('_modal.php') ?>
  
  <?php
  
    if (isset($views)) {
      foreach ($views as $view) {
        require($view);
      }
    }
  
  ?>
  
  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/js/jquery.timeTo.min.js"></script>
  <script src="assets/js/jquery.typewatch.js"></script>
  <script src="assets/js/jquery.sticky.js"></script>
  <script src="assets/js/spin.min.js"></script>
  <script src="assets/js/jquery.spin.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
  <script src="assets/js/custom.js"></script>
  <?php if (!SessionController::is_logged_in()) { ?>
  <script>
    $('.btn-skill').parent().submit(function() {
      $('#signin-modal #signin-modal-signin').removeClass('hide');
      $('#signin-modal').modal();
      return false;
    });
    
    $('#register-btn').click(function() {
      $('#signin-modal #signin-modal-signin').addClass('hide');
      $('#signin-modal').modal();
      return false;
    });

  </script>
  <?php } ?>
  <script src="assets/js/autobahn.js"></script>
  <script src="assets/js/pubsub.js"></script>
  <script src="assets/js/swfobject.js"></script>
  <script src="assets/js/web_socket.js"></script>
  
</body>

</html>
