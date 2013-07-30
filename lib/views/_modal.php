<?php if (!isset($_SESSION['close modal'])) { ?>
<!-- Modal -->
<div id="signin-modal" class="modal hide fade">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3>Sign In/Register</h3>
  </div>
  <div class="modal-body">
    <div class="row-fluid">
      <div id="signin-modal-signin" class="span6 hide">
        <?php require('sessions/create.php') ?>
      </div>
      <div class="span6">
        <?php require('players/_form.php') ?>
      </div>
    </div>
  </div>
</div>
<?php } else { unset($_SESSION['close modal']); } ?>

<div id="score_history" class="modal hide fade">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3>score history</h3>
  </div>
  <div class="modal-body">
    <blockquote style="margin-bottom:10px;">
    <select id="categoryList"
            onchange="loadscoreTable(this.options[this.selectedIndex].value);">
    </select>
	<div id="summary">
	</div>
    </blockquote>
  
    <table class="table table-bordered table-striped">
	<thead>
	<tr class="warning"><th>Skill</th><th>Correct #</th><th>Using time</th><th>Result</th><th>Opponent</th><th>Game Date</th></tr></thead>
	<tbody>
	</tbody>
	</table>
  </div>
  <div class="modal-footer">   
  </div>
</div>