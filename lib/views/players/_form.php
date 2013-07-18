<form action="register.php" method="post" class="form-horizontal">
  <div id="usernameGroup" class="control-group<?php if ($_SESSION['register_error'] || (!empty($any_set) && empty($_POST['username']))) echo ' error' ?>">
    <label class="control-label" for="inputUsername">Username</label>
    <div class="controls">
      <input name="username" type="text" id="inputUsername" placeholder="Username" <?php if (!empty($_POST['username'])) echo "value={$_POST['username']}" ?>>
      <?php if (!empty($any_set )&& empty($_POST['username'])) { ?><span class="help-inline">You need a Username</span><?php } ?>
      <?php if (!empty($_SESSION['register_error'])) { unset($_SESSION['register_error']); ?><span class="help-inline">Username taken.</span><?php } ?>
    </div>
  </div>
  <div id="passwordGroup" class="control-group<?php if (!empty($any_set) && empty($_POST['password'])) echo ' error' ?>">
    <label class="control-label" for="inputPassword">Password</label>
    <div class="controls">
      <input name="password" type="password" id="inputPassword" placeholder="Password" <?php if (!empty($_POST['password'])) echo "value={$_POST['password']}" ?>>
      <?php if (!empty($any_set )&& empty($_POST['password'])) { ?><span class="help-inline">You need a Password</span><?php } ?>
    </div>
  </div>
  <div id="mottoGroup" class="control-group<?php if (!empty($any_set) && empty($_POST['motto'])) echo ' error' ?>">
    <label class="control-label" for="inputMotto">Motto</label>
    <div class="controls">
      <input name="motto" type="text" id="inputMotto" placeholder="Motto" <?php if (!empty($_POST['motto'])) echo "value={$_POST['motto']}" ?>>
      <?php if (!empty($any_set) && empty($_POST['motto'])) { ?><span class="help-inline">You need a Motto</span><?php } ?>
    </div>
  </div>
  <div id="registerGroup" class="control-group">
    <div class="controls">
      <button type="submit" class="btn">Register</button>
    </div>
  </div>
</form>