<?php

use \KrowdByz\controllers\PlayerListController;

$categoryID = PlayerListController::start();
$all = PlayerListController::show($categoryID);

?>

<div id="player_list_table">
<table class="table" id="player-list">
  <thead>
  <tr>
    <th>Username</th>
    <th>Status</th>
  </tr>
  </thead>
  <tbody>
  <?php foreach ($all as $player) { ?>
    <?php $available = $player['status'] === 'available' ?>
    <?php if ($available) {
	 if($player['username']==$_SESSION['player_username'])
	 echo "<tr id='{$player['username']}'><td class='alert-info'>{$player['username']}"; 
	 else
	 echo "<tr id='{$player['username']}'><td><a href=\"#\">{$player['username']}</a>"; 
	 } else { 
     echo "<tr id='{$player['username']}'><td>{$player['username']}"; } ?></td>
      <td class="alert<?php if ($available) echo '-success';?>"><?php echo $player['status'] ?></td>
    </tr>
  <?php } ?>
  </tbody>
</table>
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