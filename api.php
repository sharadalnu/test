<?php
include 'connect.php';

$dbh = new mysqli($hostname, $username,$password,$dbname);
$cmd=$_POST['cmd'];
date_default_timezone_set('America/Los_Angeles');
if($cmd==0) //delete player from player_list

{ 
 $user=$_POST['username'];
  $stmt = $dbh->prepare("delete FROM player_list WHERE username='$user';");
  $stmt->execute();
          
  
 }
if($cmd==1) 

{$a=array();
 $user=$_POST['username'];
 $c=$_POST['category'];
  $stmt = $dbh->query("select username FROM player_list WHERE category_id=$c;");
 
  $result = $stmt->fetch_all(MYSQLI_NUM);
  echo json_encode($result);
  
 }

if($cmd==2) //player status is busy

{
 
 $user=$_POST['username'];
  $time=date("YmdHis");
  $stmt = $dbh->prepare("update player_list set status=1,time=$time WHERE username='$user';");
  $stmt->execute();

  
 }
if($cmd==3)  //player status is available

{
 
 $user=$_POST['username'];
 $c=$_POST['category'];
 $stmt = $dbh->query("select * from player_list WHERE username='$user';");

 $result=$stmt->num_rows;
 //echo $result;
 $time=date("YmdHis");
  //echo $time;
 if($result>0)
 {$stmt = $dbh->prepare("update player_list set status=0,time='$time',category_id=$c WHERE username='$user'");}
 else
 {
  $stmt = $dbh->prepare("insert into player_list values($c,'$user',0,$time);");
  }
  $stmt->execute();

  
 }

?>