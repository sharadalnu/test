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