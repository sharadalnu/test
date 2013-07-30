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
              <a  href="#" class="dropdown-toggle" data-toggle="dropdown" id="welcome-user">
                Welcome, <?php echo $_SESSION['player_username'] ?>
                <b class="caret"></b>
              </a>
              <ul style="margin-top:0px;" class="dropdown-menu">
                <li><a id='p_score' tabindex="-1" href="javascript:openprofile()"><i class="icon-search"></i><span class="dropdown-entry">Game History</span></a></li>
                <li><a id='p_profile' tabindex="-1" href="#"><i class="icon-user"></i><span class="dropdown-entry">Edit Profile</span></a></li>
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
<script>
function openprofile()
{$('#score_history').modal('show');
 $.ajax({
            url: "api.php",
            type: "post",
            data: {cmd:6,                   
                   category:$('#category').attr("name")
                   },
            dataType: "html",
            success: function(data) {
			$("#score_history #categoryList").html(data);
						}
			
			});
        show_score_summary($('#category').attr("name"));
        show_score_records($('#category').attr("name"));

}
function loadscoreTable(category)
{       show_score_summary(category);
        show_score_records(category);
}
function show_score_summary(category)
{$.ajax({
            url: "api.php",
            type: "post",
            data: {cmd:5,
                   username:getUsername(),
                   category:category
                   },
            dataType: "html",
            success: function(data) {
			$("#score_history #summary").html(data);
						}
			
			});
}
function show_score_records(category)
{$.ajax({
            url: "api.php",
            type: "post",
            data: {cmd:4,
                   username:getUsername(),
                   category:category
                   },
            dataType: "html",
            success: function(data) {
			$("#score_history tbody").html(data);
						}
						});
}
</script>