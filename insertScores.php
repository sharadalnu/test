<?php
include 'connect.php';
$dbh = new mysqli($hostname, $username,$password,$dbname);


//echo("INSERT INTO score VALUES ('".$_POST['usename']."',".$_POST['cat'].",".$_POST['win'].",'".$_POST['dbtime']."',".$_POST['cor'].",'".$_POST['op']."');");

$stmt = $dbh->prepare("INSERT INTO score (`player_name`, `category_id`, `win`, `usedtime`, `correct_num`, `opponent`, `time`) VALUES ('".$_POST['usename']."',".$_POST['cat'].",".$_POST['win'].",'".$_POST['dbtime']."',".$_POST['cor'].",'".$_POST['op']."','".$_POST['gametime']."');");


							  

if(! $stmt->execute())
		{
			echo("Problem executing");
		}
		else echo("sucess");

		?>
		
