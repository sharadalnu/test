<?php 
use \KrowdByz\controllers\SessionController;
use \KrowdByz\config\Settings; 
?>

<div id="main-bar" class="navbar navbar-inverse navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container">
      
      <a class="brand" href="<?php echo Settings::$MAIN_PAGE ?>"><img src="assets/img/logo.png"></a>
      <div class="nav-collapse collapse">
        
        <?php if (SessionController::is_logged_in()) { ?>
          
          <ul class="nav pull-right">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" id="welcome-user">
                Welcome, <?php echo $_SESSION['player_username'] ?>
                <b class="caret"></b>
              </a>
              <ul class="dropdown-menu">
                <li><a tabindex="-1" href="#"><i class="icon-search"></i><span class="dropdown-entry">Score History</span></a></li>
                <li><a tabindex="-1" href="#"><i class="icon-user"></i><span class="dropdown-entry">Edit Profile</span></a></li>
                <li><a tabindex="-1" href="signout.php"><i class="icon-eject"></i><span class="dropdown-entry">Sign Out</span></a></li>
              </ul>
            </li>
          </ul>
        
        <?php } else { ?>
        
        <a href="register.php" id="register-btn" class="btn btn-primary pull-right">Register</a>
        <form id="signin-form" class="navbar-form pull-right" action="signin.php" method="post">
          <input type="text" class="span2" name="username" placeholder="Username">
          <input type="password" class="span2" name="password" placeholder="Password">
          <button type="submit" class="btn">Sign In</button>
        </form>
        
        <?php } ?>
        
      </div>
      
    </div>
  </div>
</div>