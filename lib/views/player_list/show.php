

<div class="container" id="content">
  <div class="row">
    <h3 id='category' name='<?php echo $_GET['cid'];?>'><?php echo $_GET['category']?></h3>
    <div>
	Step 2:
	Choose the mode
	</div>
	<div class="page-header" style="padding-bottom:0px;">
	<h2 style='display:inline-block;margin-bottom:0px'>PvS</h2>
    </div>
	<button id="PvS" class="btn btn-large btn-primary" >PvS Mode</button>
    <?php require('_test.php'); ?>  
    <div class="page-header" style="padding-bottom:0px;">
	   <h2 style='display:inline-block;margin-bottom:0px'>PvP</h2> 
		
			<button id='Out' style='float:right' class=" btn btn-large btn-primary">Offline</button>
			<button id='In'  style='float:right' class=" btn btn-large btn-primary">Online</button>
		
    </div>
    
	<h4>Player List</h4>
	<button id="PvP" class="btn btn-large btn-primary" >Auto Selection</button>
	<br>
    <?php require('_table.php'); ?>
    </div>
    
  <ul class="thumbnails">
    <li class="span4">
      <div class="thumbnail">
        <div class="caption">
          <h3>Challenge Request</h3>
          <p class="challenge-request"></p>
          <p><a href="#" class="btn btn-primary">Accept</a> <a href="#" class="btn">Decline</a></p>
        </div>
      </div>
    </li>
  </ul>
</div>

<script type="text/javascript">
  window.pubsub = true;
</script>
