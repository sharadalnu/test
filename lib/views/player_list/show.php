<style>
img {display:inline;float:left}
blockquote {margin-top:10px;margin-bottom:-10px}
</style>

<div class="container" id="content">
  <div class="row">
    <div style="display:inline-block">
    <h2 id='category' style="display:inline" name='<?php echo $_GET['cid'];?>'><?php echo $_GET['category']?></h2>
	<a href="ssi.php">&nbsp Change skill?</a>
    <br>
	<div>Step 2:
	Choose your opponent and<br> <b>let's begin the game!</b></div>
	</div>
	<blockquote style="display:inline;float:right">
    <img style="width:32px;height:30px;" src="assets/img/1.png"></img><h4 >Single Player ?</h4>
	See how well you know <?php echo $_GET['category']?>.
    <button id="PvS" class="btn btn-warning" >Play</button>
    </blockquote>
	
	<blockquote style="display:inline;float:right;margin-right:20px">
    <img style="width:40px;height:30px;" src="assets/img/2.png"></img><h4>Two Player Mode</h4>
	Compete in real time against other players.
    <button id="PvP" href="#" class="btn btn-warning" >Play</button>
    </blockquote>
	
    <?php require('_test.php'); ?>  
    <div class="page-header" style="padding-bottom:0px;margin-bottom:10px">
	</div>

	<blockquote style="display:inline-block;margin-right:15px">	
    <img style="width:72px;height:30px;" src="assets/img/3.png"></img><h4>Choose your opponent</h4>
    You can invite the user from the list by clicking the name!	
    </blockquote>
		
	<button id='Out' style='float:right' class="btn btn-danger">Offline</button>
    <button id='In'  style='float:right' class="btn btn-success">Online</button>
    <div style='float:right;width:300px;margin-right:100px' class="alert alert-error hide"></div>
	<br>
	
	<br>
    <?php require('_table.php'); ?>
    
 </div>   
</div>

<script type="text/javascript">
  window.pubsub = true;
</script>
