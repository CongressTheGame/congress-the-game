<?php include 'core/init.php';
protect_page();
$league_id = $_SESSION['league_id'];
if(is_commissioner($session_user_id,$league_id) === false){
	header ("Location: league.php");
} else {

if(isset($_POST['random']) == true){
		$team_id = $_SESSION['team_id'];

mysql_query("DELETE FROM `rosters` WHERE team_id='$team_id' AND period_id=0") or die(mysql_error('Delete: '));
$query="SELECT members.member_id FROM members, rosters, teams, leagues WHERE members.member_id=rosters.member_id AND rosters.period_id=0 AND rosters.team_id=teams.team_id AND leagues.league_id=teams.league_id AND leagues.league_id='$league_id'";
$result=mysql_query($query);

while ($row=mysql_fetch_array($result)) { 
	$taken[]=$row['member_id'];
	}
if (empty($taken)===true){
$q="SELECT member_id from members WHERE type='sen' AND member_id ORDER BY RAND() LIMIT 6";
} else {	
$q="SELECT member_id from members WHERE type='sen' AND member_id NOT IN (".implode(',',$taken).") ORDER BY RAND() LIMIT 6";
}
$r=mysql_query($q) or die(mysql_error());
while ($ro=mysql_fetch_array($r)) { 
			$roster_data=array(
				'period_id'		=> 0,
				'team_id'		=> $team_id,
				'member_id'		=> $ro['member_id'],
				'active'		=> 0,
			);
draft_team($roster_data);
}
if (empty($taken)===true){
$q2="SELECT member_id from members WHERE type='rep' AND member_id ORDER BY RAND() LIMIT 12";
} else {	
$q2="SELECT member_id from members WHERE type='rep' AND member_id NOT IN (".implode(',',$taken).") ORDER BY RAND() LIMIT 12";
}
$r2=mysql_query($q2) or die(mysql_error());
while ($ro2=mysql_fetch_array($r2)) { 
			$roster_data=array(
				'period_id'		=> 0,
				'team_id'		=> $team_id,
				'member_id'		=> $ro2['member_id'],
				'active'		=> 0,
			);
draft_team($roster_data);
}
		header('Location: draft.php?success');
		exit();

} else {


if(isset($_POST['member_id']) === true) {
		$team_id = $_SESSION['team_id'];
	$required_fields = array('member_id');
	foreach($_POST as $key=>$v){
		if(in_array(0,$v) === true){
			$errors[]='Must fill out roster completely.';
			break 1;
		}
		$v_unique  = array_unique($v);
		
		if($v !== $v_unique){
			$errors[]='You selected the same member more than once.' ;
			break 1;	
		}
		foreach($v as $member_id){
			if(member_in_league($member_id, $league_id, $team_id) === true){
				$member_data = member_data($member_id, 'name');
				$errors[]= $member_data['name']." is already on a team in this league.";
			}
		}
		
	}
	
}
if (isset($_POST['member_id']) === true && empty($errors) === true) {
		$roster_data=array();
		$team_id = $_SESSION['team_id'];
	    mysql_query("DELETE FROM `rosters` WHERE team_id='$team_id' AND period_id=0") or die(mysql_error('Delete: '));
	
	foreach($_POST['member_id'] as $key=>$value){
			$roster_data=array(
				'period_id'		=> 0,
				'team_id'		=> $team_id,
				'member_id'		=> $value,
				'active'		=> 0,
			);
	draft_team($roster_data);
	}
		header('Location: draft.php?success');
		exit();
}
}
	include 'includes/overall/header.php';

	if(isset($_POST['team_id']) === true) {
		unset($_SESSION['team_id']);
		//}
	//if(isset($_SESSION['team_id']) === false) {
		$_SESSION['team_id'] = $_POST['team_id'];
		$team_id = $_SESSION['team_id'];
	}

//POPULATE TEAM LISTING
	$query="SELECT `team_id`,`team_name` FROM `teams` WHERE league_id = '$league_id' ORDER BY `team_name`"; 
	$result=mysql_query($query) or die(mysql_error()); 
	$options=""; 

	while ($row=mysql_fetch_array($result)) { 
    	$teamid = $row["team_id"]; 
    	$team_name = $row["team_name"]; 
    	$teams.="<OPTION VALUE=\"$teamid\">".$team_name; 
	}

	$query="SELECT `league_name` FROM leagues WHERE league_id ='$league_id'";
	$result=mysql_query($query);
	$league_name = mysql_result($result,0);
?>

<h1><?php echo $league_name." Draft";?></h1>

<form action="draft.php" method="post">
<ul>
   <li>
      <SELECT NAME=team_id> 
      <OPTION VALUE=0>Choose Team...
      <?=$teams?> </OPTION>
      </SELECT>
   </li>
   <li>
      <input type="submit" value="Select Team"> 
   </li>
</ul>
</form>

<?php
//SUCCESS
	if(isset($_GET['success'])){
		$team_id = $_SESSION['team_id'];
		$query="SELECT `team_name` FROM teams WHERE team_id ='$team_id'";
$result=mysql_query($query);
$team_name = mysql_result($result,0);
$query = "SELECT rosters.member_id , members.name FROM rosters, members WHERE members.type='sen' AND rosters.member_id = members.member_id AND rosters.team_id='$team_id' AND period_id=0 ORDER BY members.lastname";
$result = mysql_query($query) or die(mysql_error());
echo "<ul><li><h2>SUCCESS! Set " . $team_name."'s Roster To:</h2></li><li><h3>Senators</h3></li>";
while($row = mysql_fetch_array($result)) {
	echo "<li class='double'>"; 
	echo $row['name'];
        echo "</li>";
       }
   echo "</ul><br>";

$query = "SELECT rosters.member_id , members.name FROM rosters, members WHERE members.type='rep' AND rosters.member_id = members.member_id and rosters.team_id='$team_id' AND period_id=0 ORDER BY members.lastname";
$result = mysql_query($query) or die(mysql_error());
echo "<ul><li><h3>Representatives</h3></li>";
while($row = mysql_fetch_array($result)) {
	echo "<li class='double'>"; 
	echo $row['name'];
        echo "</li>";
       }
   echo "</ul>";    
		unset($_SESSION['team_id']);
} else if(empty($errors) === false){
		echo output_errors($errors);
}

if (empty($_POST) === true || $_POST['team_id']===0) {
echo 'Choose a team to start the drafting process.';
} else { 


//VIEW CURRENT ROSTER
$team_id = $_SESSION['team_id'];
$query="SELECT `team_name` FROM teams WHERE team_id ='$team_id'";
$result=mysql_query($query);
$team_name = mysql_result($result,0);

echo "<h1>".$team_name."</h1>";

$query = "SELECT rosters.member_id , members.name FROM rosters, members WHERE members.type='sen' AND rosters.member_id = members.member_id AND rosters.team_id='$team_id' AND period_id=0 ORDER BY members.lastname";
$result = mysql_query($query) or die(mysql_error());
echo "<div id='results'><ul><li><h2>Current Roster</h2></li><li><h3>Senators</h3></li>";
while($row = mysql_fetch_array($result)) {
	echo "<li class='double'>"; 
	echo $row['name'];
        echo "</li>";
       }
   echo "</ul>";
   echo "<br>";

$query = "SELECT rosters.member_id , members.name FROM rosters, members WHERE members.type='rep' AND rosters.member_id = members.member_id AND rosters.team_id='$team_id' AND period_id=0 ORDER BY members.lastname";
$result = mysql_query($query) or die(mysql_error());
echo "<ul><li><h3>Representatives</h3></li>";
while($row = mysql_fetch_array($result)) {
	echo "<li class='double'>"; 
	echo $row['name'];
        echo "</li>";
       }
   echo "</ul></div>";    

//SELECT NEW MEMBERS

$query1="SELECT `member_id` FROM `members` WHERE `type`='sen' AND `current`=1 ORDER BY `lastname`"; 
    $result1=mysql_query($query1); 
	$options1=""; 
 
while ($row1=mysql_fetch_array($result1)) { 
    $member_id1=$row1['member_id'];
	$member_data1 	= member_data($member_id1, 'type', 'middlename', 'lastname','firstname','title','state');
	 
		
    
    if (trim($member_data1['middlename']) <> "") {
    	$middle1 = $member_data1['middlename'] . " ";
    } else{
    	$middle1 ="";
    }
    $name1= $member_data1["lastname"] . ", " . $member_data1['firstname'] . " " . $middle1 . "(" . $member_data1['title'] . ", " . $member_data1['state'] . ")";
    	$member1.="<OPTION VALUE=\"$member_id1\">".$name1;
}

$query2="SELECT `member_id` FROM `members` WHERE `type`='rep' AND `current`=1 ORDER BY `lastname`"; 
    $result2=mysql_query($query2); 
	$options2=""; 
 
while ($row2=mysql_fetch_array($result2)) { 
    $member_id2=$row2['member_id'];
	$member_data2 	= member_data($member_id2, 'type', 'district', 'middlename', 'lastname','firstname','title','state');
	
		
    if (trim($member_data2['type']) === "rep"){
    	$district2 = " - " . $member_data2['district'];
    } else{
    	$district2 ="";
    }
    if (trim($member_data2['middlename']) <> "") {
    	$middle2 = $member_data2['middlename'] . " ";
    } else{
    	$middle2 ="";
    }
    $name2= $member_data2["lastname"] . ", " . $member_data2['firstname'] . " " . $middle2 . "(" . 		$member_data2['title'] . ", " . $member_data2['state'] . $district2 . ")"; 
    	$member2.="<OPTION VALUE=\"$member_id2\">".$name2;	 
}
?>
<form action="" method="post">
<ul>
   <li>
      <h3>Senators</h3>
   </li>
   <?php for ($num=1; $num <= 6; $num++){?>
   <li class='double'>
      <SELECT NAME=member_id[] class="draft"> 
      <OPTION VALUE=0 class="draft">Choose a Senator...
      <?php echo $member1;?> </OPTION>
      </SELECT>
 
   </li>
   <?php }?>
</ul>
<br />
<ul>
   <li>
      <h3>Representatives</h3>
   </li>
   <?php for ($num=1; $num <= 12; $num++){?>
   <li class='double'>
      <SELECT NAME=member_id[] class="draft"> 
      <OPTION VALUE=0 class="draft">Choose a Representative...
      <?php echo $member2;?> </OPTION>
      </SELECT>
    </li>
   <?php }?>
</ul>
<br>
<ul>
<li class="double"><input type="submit" id="register" value="Draft"</li>
<li class="double"><input name="random" type="submit" id="register" value="Randomly Assign Members"></li>
</ul>
<h3 align="center"><?php echo $random; ?></h3>
</form>

<?php
    
echo '';
}

}

include 'includes/overall/footer.php';?>