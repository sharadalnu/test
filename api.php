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
 $result=array();
 while ($name = $stmt->fetch_array(MYSQLI_ASSOC))
 {
  array_push($result,$name['username']);
  }
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
if($cmd==4)  
{
 $c="";
 $user=$_POST['username'];
 if(isset($_POST['category'])) $c=$_POST['category'];
 if($c=="")$stmt = $dbh->query("select player_name,win,time,usedtime,correct_num,opponent,category from score s,categories c where s.category_id=c.id and player_name='$user' order by s.id desc");
 else
 $stmt = $dbh->query("select player_name,win,usedtime,time,correct_num,opponent,category from score s,categories c where s.category_id=c.id and player_name='$user' and category_id=$c order by s.id desc");
 $list="";
 while ($row = $stmt->fetch_array(MYSQLI_ASSOC))
 {
  $list .= "<tr><td>".$row['category']."</td><td>".$row['correct_num']."</td><td>".$row['usedtime']." s</td><td>";
  if($row['win']==1) $list .="Win";
  else $list .= "Lose";
  $list .="</td><td>".$row['opponent']."</td><td>".$row['time']."</td></tr>" ;
  }
  echo $list;
  return $list;
 
 }
 if($cmd==5)  
{
 $c="";
 $user=$_POST['username'];
 if(isset($_POST['category'])) $c=$_POST['category'];
 if($c=="") $stmt = $dbh->query("select count(*),t.score
								from score,
								( select sum(score) as score from scoreboard where player_name='$user') t
                                where player_name='$user'								
								 ");		  
 else  $stmt = $dbh->query("select count(*),score 
                      from score,scoreboard 
                      where score.player_name='$user' and score.player_name=scoreboard.player_name and score.category_id=$c and scoreboard.category_id=$c");
 $row = $stmt->fetch_array(MYSQLI_ASSOC);
 if($row['score']=="") $row['score']= 0;
 
 if($c=="") 
 $result=$dbh->query("select count(*) as rank
                      from (select player_name
                        from scoreboard 
						having sum(score)>".$row['score']." 
						) t
					  where t.player_name='$user'") ;
 else
 $result=$dbh->query("select count(*) as rank
                        from scoreboard where score>".$row['score']." and category_id=$c");
						
 $list="";
 $result=$result->fetch_array(MYSQLI_ASSOC);

 if($result['rank']==0&&$row['count(*)']==0) $rank="";
 else $rank=$result['rank']+1;
 $list="<div>Score: ".$row['score']."</div>
        <div>Rank: ".$rank."</div>
		<div>Total Number Of Games You Played: ".$row['count(*)']."</div>";
 echo $list;
 
 
 }
  if($cmd==6)  
{
 $c="";
  if(isset($_POST['category'])) $c=$_POST['category'];

 $stmt = $dbh->query("select * from categories;");
 $list="";
 $first_option="<option value=''>--All--</option>";
 $last_option="";
 while($row = $stmt->fetch_array(MYSQLI_ASSOC)){
  if($c!=""&&$row['id']==$c) { $last_option=$first_option; $first_option="<option value=".$row['id'].">".$row['category']."</option>";}
  else $list .="<option value=".$row['id'].">".$row['category']."</option>";	
}					
 echo $first_option.$list.$last_option;
 return $first_option.$list;
 
 }
?>