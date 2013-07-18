<?php 
SESSION_start();
if(empty($_SESSION['player_username'])||empty($_GET['cid'])||empty($_GET['category']))
header("Location:ssi.php");
session_write_close();
  //require_once('bin/server.php');
  $views = array('player_list/show.php'); // Views to render

  require_once('lib/app.php');
  require('lib/views/main.php');

?>