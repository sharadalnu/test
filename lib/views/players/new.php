<?php $any_set = !empty($_POST['username']) || !empty($_POST['password']) || !empty($_POST['motto']); ?>

<div class="container" id="content">
  <div class="row">
    <div class="page-header">
      <h1>Register</h1>
    </div>
    <?php require('_form.php') ?>
  </div>
</div>

<!-- Hide Modal -->
<script>
  window.HIDE_MODAL = true;
</script>