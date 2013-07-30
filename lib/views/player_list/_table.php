<style>
.bubble-box{
background:#EEE;
min-width:280px;
margin-bottom:2px;


}
.bubble-box .wrap{
background:#EEE;
-webkit-background-clip:padding-box;-moz-background-clip:padding;background-clip:padding-box;border:1px solid #ccc;border:1px solid rgba(0, 0, 0, 0.2);
-webkit-border-radius:6px;
-moz-border-radius:6px;
border-radius:6px;
-webkit-box-shadow:5px 5px 10px rgba(0, 0, 0, 0.2);
-moz-box-shadow:5px 5px 10px rgba(0, 0, 0, 0.2);
box-shadow:5px 5px 10px rgba(0, 0, 0, 0.2);
white-space:normal;
_position:relative;
_z-index:10;
}
/* arrow-effect */
.arrow-left{ border-left:20px solid #FFF; border-top:20px solid #EEE; margin-top:20px;}
.arrow-right{ border-right:20px solid #FFF; border-top:20px solid #EEE;}
.arrow-top{ border-left:20px solid #EEE; border-top:20px solid #FFF; margin-left:20px;}
.arrow-bottom{ border-left:20px solid #EEE; border-bottom:20px solid #FFF; margin-left:20px;}
.arrow-left .wrap,
.arrow-right .wrap{
border-left-width:0;
min-width:280px;
padding:12px 10px 12px 10px;
margin-top:-40px;
}
.arrow-top, .arrow-bottom{ width:140px;}
.arrow-top .wrap,
.arrow-bottom .wrap{
min-width:180px;
padding:12px 10px 12px 10px;
margin-left:-40px;
} 
.listarea
{max-height:300px;
 overflow-y: auto;
 }

</style>

<?php

use \KrowdByz\controllers\PlayerListController;

$categoryID = PlayerListController::start();
$all = PlayerListController::show($categoryID);
$winners = PlayerListController::showLeaders($categoryID,10);
?>
<script type="text/javascript">
    $(function () {
        $('body').popover({
            selector: '[data-toggle="popover"]'
        });

        $('body').tooltip({
            selector: 'a[rel="tooltip"], [data-toggle="tooltip"]'
        });
    });
</script>
<div id="leader_list_table" class="well" style='display:inline-block;float:left;width:18%;margin-right:10px;' >
<div style="text-align:center;"><h4 style="font-color:#FFFFFF">Leaderboard</h4></div>
<?php
echo("<ol>");
	foreach ($winners as $player)
	{
		echo("<li>". $player['username'] ."<div style='display:inline-block;float:right'>". $player['score'] . "<div></li>");
	}
	echo("</ol>");
?>
</div>

<div id="player_list_table" style='display:inline-block;float:left;width:80%'>
<div class='listarea' style=''>
<table class="table table-bordered" id="player-list" style="background-color:#FFFFFF">
  <thead>
  <tr>
    <th>Username</th>
    <th style="">Status</th>
  </tr>
  </thead>

  <tbody>

  <?php 
  
   echo "<tr id=".$_SESSION['player_username']."><td class='alert-info'>".$_SESSION['player_username']."</td><td class='alert-success'>avaliable</td><tr>"; 
   foreach ($all as $player) { 
     if($player['username']!=$_SESSION['player_username'])
	 {
     if($player['status']=='available')
	     echo "<tr id='{$player['username']}'><td><a href=\"#\" onclick=\"sendout('{$player['username']}')\">{$player['username']}</a></td><td class='alert-success'>available</td></tr>";	 
         else echo "<tr id='{$player['username']}'><td><a href=\"#\">{$player['username']}</a></td><td class='alert'>busy</td></tr>";
     }  
	}
  ?>
  </tbody>
</table>
</div>
</div>

<div id="message_table" style="display:inline;width:450px;float:left;" >
<div class='listarea' >
<table class="" id="message-list" style="background-color:#FFFFFF">
<thead><tr><th></th></tr> </thead>
<tbody></tbody>
</table>
</div>
</div>



<script type="text/javascript">
function insert_db(){
$.ajax({
			url: "api.php",
			type: "post",
			data: {cmd:3,
				   username:getUsername(),
				   category:$('#category').attr("name")
				   }
	        });

}

</script>