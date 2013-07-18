<?php
  use \KrowdByz\controllers\CategoryController;
?>

<div class="container" id="content">
  <div class="hero-unit">
    <div class="row">
      <div class="span5">
        <h4 class="muted">Step 1:</h4>
        <h1 id="choose-header">Choose.</h1>
        <h4>Choose the skill you want to compete in.</h4>
      </div>
      
      <div id="skills" class="span4">
<?php CategoryController::iterate(<<<EOD
        <form action="osi.php">
          <input type="hidden" name="cid" value="{{categoryid}}">
		  <input type="hidden" name="category" value="{{category}}">
          <button type="submit" class="btn btn-skill btn-{{color}}">{{category}}</button>
        </form>
EOD
); ?>
      </div>
    </div>
  </div>	  
</div>
<script type="text/javascript">
  window.onload=function(){
  offline();
  };
</script>