<?php include 'core/init.php';
protect_page();

//if (has_access($user_id,1) !== true){
//if(ownteam($_SESSION['team_id'],$_SESSION['user_id']) !== true){
//unset($_SESSION['team_id']);
//}
if(isset($_GET['team_id']) === true){
	unset($_SESSION['team_id']);
	$_SESSION['team_id'] = $_GET['team_id'];
	header('Location: team.php?');
}
if(isset($_POST['team_id']) === true){
	unset($_SESSION['team_id']);
	$_SESSION['team_id'] = $_POST['team_id'];
	header('Location: team.php?');
}
if(isset($_POST['period_id']) === true){
	unset($_SESSION['period_id']);
	$_SESSION['period_id'] = $_POST['period_id'];
	header('Location: team.php?');
}

//TEAM SELECTION DROPDOWN	
$user_id = $user_data['user_id'];
if (has_access($user_id,1) === true){
  $result = mysql_query("SELECT distinct teams.team_id, teams.team_name,leagues.league_name FROM `leagues`,`teams` WHERE leagues.league_id=teams.league_id") or die(mysql_error());
} else {
$result = mysql_query("SELECT teams.team_id, teams.team_name,leagues.league_name FROM `leagues`,`teams` WHERE teams.user_id = '$user_id' and leagues.league_id=teams.league_id") or die(mysql_error());
}
$num_rows = mysql_num_rows($result);
if($num_rows > 0) {
	while ($row=mysql_fetch_array($result)) { 
    	$team_id=$row["team_id"];
    	$league_name=$row["league_name"]; 
    	$team_name=$row["team_name"]; 
    	$teams.="<OPTION VALUE=\"$team_id\">".$team_name." (".$league_name.")"; 
	}
	
include 'includes/overall/header.php';

?>

<form action="" method="post">
	<h3>My Teams: 
    <SELECT NAME=team_id> 
    <OPTION VALUE=0>Choose Team...
    <?=$teams?> </OPTION>
    </SELECT>
    <input type="submit" value="View Team">
    </h3>    
</form>

<?php 
//DISPLAY TEAM NAME	
	$team_id=$_SESSION['team_id'];
	if (empty($team_id) === true) {
	echo '<p>Choose a Team.</p>';
	} else {
	$result = mysql_query("SELECT teams.team_name,leagues.league_name, leagues.league_id FROM `leagues`,`teams` WHERE teams.team_id = '$team_id' and leagues.league_id=teams.league_id") or die(mysql_error());
	while ($row=mysql_fetch_array($result)) { 
   		$league_name=$row["league_name"];
		$league_id=$row['league_id']; 
    	$team_name=$row["team_name"]; 
    }
	echo "<div id='results'><h1>".$team_name. " - " . $league_name."</h1>";

//SELECT PERIOD DROPDOWN
	$q="SELECT period_id, startdate, enddate, current FROM period, leagues WHERE DATE(leagues.created) <= DATE(period.enddate) AND period.current <> 2 AND leagues.league_id='$league_id' ORDER BY period_id DESC";
	$r=mysql_query($q) or die(mysql_error());
	while ($per = mysql_fetch_array($r)){
		$period= $per['period_id'];
		$startdate= new DateTime($per['startdate']);
		$startdate= date_format($startdate,'m/d/y');
		$enddate= new DateTime($per['enddate']);
		$enddate= date_format($enddate,'m/d/y');
		$cur=$per['current'];
		if($cur == 1) {
		$periods.="<OPTION VALUE=\"$period\">".$startdate." - ".$enddate."*";
		}else{
		$periods.="<OPTION VALUE=\"$period\">".$startdate." - ".$enddate;
		}
	}
	?>
    
<form action="" method="post"><h2>
<SELECT NAME=period_id> 
    <OPTION VALUE=0>Choose Period...
    <?=$periods?> </OPTION>
    </SELECT>
    <input type="submit" value="Go">
</form>

<?php
//DISPLAY PERIOD INFORMATION
	$period_id = $_SESSION['period_id'];
	if (empty($period_id) === true) {
	echo '</h2><p>Choose a period</p></div>';
	} else {
	$que="SELECT startdate, enddate, current FROM period WHERE period_id='$period_id'";
	$res=mysql_query($que) or die(mysql_error());
	while ($row = mysql_fetch_array($res)){
		$start= new DateTime($row['startdate']);
		$start= date_format($start,'m/d/y');
		$end= new DateTime($row['enddate']);
		$end= date_format($end,'m/d/y');
		$current=$row['current'];
	}
	?>
    Performance - <?php echo $start. " - " .$end;if($current == 1) {echo "*";}?></h2>

<?php

if (isset($_POST['active'])){
	foreach($_POST['active'] as $key=>$value){
	$a[]	= substr($value,0,1);
	}
	$a = array_sum($a);
	if($a > 2) {
	$errors[]='Cannot have more than two active Senators';	
	}
	
	foreach($_POST['active2'] as $key2=>$value2){
	$a2[]	= substr($value2,0,1);
	}
	$a2 = array_sum($a2);
	if($a2 > 5) {
	$errors[]='Cannot have more than five active Representatives';	
	}
}

if (isset($_POST['active']) && isset($_POST['active']) && empty($errors) === true){

	foreach($_POST['active'] as $key=>$value){
	$explode = explode(',',$value);
	$array = array(
		'active'	=> $explode[0],
		'member_id'	=> $explode[1],
		'period_id'	=> $period_id,
		'team_id'		=> $team_id
		);	
		update_roster($array);	
	}

	foreach($_POST['active2'] as $key2=>$value2){
	$explode2 = explode(',',$value2);
	$array2 = array(
		'active'	=> $explode2[0],
		'member_id'	=> $explode2[1],
		'period_id'	=> $period_id,
		'team_id'		=> $team_id
		);		
	
		 update_roster($array2);	
	}
echo '<strong><center>Team Updated!</center></strong>';

} else {
	echo output_errors($errors);
}
if(ownteam($team_id,$user_id) === true || has_access($user_id,1) === true){

/////////////////CURRENT PERIOD/////////////////
if($current == 1) {
?>
<form action"" method="post">
<table class="results">
        <thead>
    	<tr>
        	<th colspan=2>Senators</th>
        </tr>
        <tr> <td colspan=2>Select Two Active Senators</td></tr>
    </thead>
<?php


 //GET TEAM ROSTER
	$sen="SELECT members.member_id, active FROM `rosters`,`members` WHERE `period_id`='$period_id' AND rosters.team_id='$team_id' and members.member_id=rosters.member_id AND members.type='sen' ORDER BY members.lastname"; 
    $sen=mysql_query($sen);
	$num_rows = mysql_num_rows($sen);
	$operiod = $period_id;
if($num_rows == 0) { 
	$sen="SELECT members.member_id, active FROM `rosters`,`members` WHERE `period_id`=0 AND rosters.team_id='$team_id' and members.member_id=rosters.member_id AND members.type='sen' ORDER BY members.lastname"; 
    $sen=mysql_query($sen);
	$operiod = 0;
 }
	while ($sen_row=mysql_fetch_array($sen)) { 
    	$senator_id=$sen_row['member_id'];
		$senator_data 	= member_data($senator_id, 'type', 'district', 'middlename', 'lastname','firstname','title','state','party');
    	if (trim($senator_data['middlename']) <> "") {
    		$senator_middle = $senator_data['middlename'] . " ";
    	} else{
    		$senator_middle ="";
    	}
        if (trim($senator_data['party']) == "Republican") {
    		$senator_party = "R";
    	} else if (trim($senator_data['party']) == "Democrat"){
    		$senator_party ="D";
    	} else {
                $senator_party = "I";
        }
			$active = mysql_result(mysql_query("SELECT active FROM members, rosters where period_id='$operiod' AND members.member_id=rosters.member_id and members.member_id='$senator_id'"),0);
    		$senator_name= $senator_data['title'] . " " .$senator_data["lastname"] . ", " . $senator_data['firstname'] . " " . $senator_middle . "(" .  $senator_data['state'] .", ".$senator_party. ")"; 
			
			?>				
 		<tr>
        	<td>
            <select name="active[]">
            	<option value="<?php echo'0,'.$senator_id;?>" />----</option>
                <option value="<?php echo '1,'.$senator_id;?>" <?php if ($active ==1){echo 'selected="selected"';}?>>Active</option>
            </select>
            </td>
            <th><?php echo "<a href=\"member.php?member_id=".$senator_id."\">".$senator_name."</a>";?></th>
                     
       </tr>
             
<?php
}
?>
	</tbody>
	<thead>
    	<tr>
        	<th colspan=2>Representatives</th>
        </tr>
        <tr> <td colspan=2>Select Five Active Representatives</td></tr>
    </thead>
    <tbody>
<?php
$rep="SELECT members.member_id, active FROM `rosters`,`members` WHERE `period_id`='$period_id' AND rosters.team_id='$team_id' and members.member_id=rosters.member_id AND members.type='rep' ORDER BY members.lastname"; 
    $rep=mysql_query($rep); 
	$num_rows = mysql_num_rows($rep);
	$operiod = $period_id;
if($num_rows == 0) { 
$rep="SELECT members.member_id, active FROM `rosters`,`members` WHERE `period_id`=0 AND rosters.team_id='$team_id' and members.member_id=rosters.member_id AND members.type='rep' ORDER BY members.lastname"; 
    $rep=mysql_query($rep); 
	$operiod = 0;
}
	while ($rep_row=mysql_fetch_array($rep)) { 
    	$rep_id=$rep_row['member_id'];
		$rep_data 	= member_data($rep_id, 'type', 'district', 'middlename', 'lastname','firstname','title','state','party');
    	if (trim($rep_data['type']) === "rep"){
    		$district = " - " . $rep_data['district'];
    	} else{
    		$district ="";
    	}
    	if (trim($rep_data['middlename']) <> "") {
    		$rep_middle = $rep_data['middlename'] . " ";
    	} else{
    		$rep_middle ="";
    	}
        if (trim($rep_data['party']) == "Republican") {
    		$rep_party= "R";
    	} else if (trim($rep_data['party']) == "Democrat"){
    		$rep_party="D";
    	} else {
                $rep_party= "I";
        }
    		$active = mysql_result(mysql_query("SELECT active FROM members, rosters where period_id='$operiod' AND members.member_id=rosters.member_id and members.member_id='$rep_id'"),0);
			$rep_name= $rep_data['title'] . " " .$rep_data["lastname"] . ", " . $rep_data['firstname'] . " " . $rep_middle . "(" .  $rep_data['state'] . $district . ", ".$rep_party.")"; 
			?>				
 		<tr>
        	<td>
            <select name="active2[]">
            	<option value="<?php echo'0,'.$rep_id;?>" />----</option>
                <option value="<?php echo '1,'.$rep_id;?>" <?php if ($active ==1){echo 'selected="selected"';}?>>Active</option></select>
            </td>
            <th><?php echo "<a href=\"member.php?member_id=".$rep_id."\">".$rep_name."</a>";?></th>
   			           
       </tr>
      
<?php       
}
?>
	</tbody>
    <tfoot>
    	<tr>
        	<th colspan="2">
            	<input type="submit" value="Set Roster" id="register" />
            </th>
		</tr>
    </tfoot>
</table>
</form>
</div>
<?php	
} else {
?>
<table class="results">
	<thead>
		<tr> 
        	<th>Active</th>
            <th width="100%">Member</th>  
            <th>Spons./ Conspons.</th> 
            <th>House</th> 
            <th>Senate</th> 
            <th>Enacted</th> 
            <th>Votes / Bipartisan</th> 
            <th>Points</th>
        </tr>
<?php
//SHOW POINT VALUES 
$query="SELECT * FROM points,teams WHERE teams.team_id='$team_id' AND teams.league_id=points.league_id";
$result=mysql_query($query);
$points=mysql_fetch_array($result);
?> 
        <tr>
        	<td colspan=2 rowspan=2>League Point Values</td>
   			<td><?php echo $points['sponsor_introduce'];?></td>
   			<td><?php echo $points['sponsor_pass_house'];?></td>
   			<td><?php echo $points['sponsor_pass_senate'];?></td>
   			<td><?php echo $points['sponsor_enacted'];?></td>
   			<td><?php echo $points['yea_vote'];?></td>
   			<td></td>
        </tr>
        <tr>
        	<td><?php echo $points['cosponsor_introduce'];?></td>
   			<td><?php echo $points['cosponsor_pass_house'];?></td>
   			<td><?php echo $points['cosponsor_pass_senate'];?></td>
   			<td><?php echo $points['cosponsor_enacted'];?></td>
   			<td><?php echo $points['bipartisan'];?></td>
            <td></td>
        </tr>
	</thead>
	<thead>
    	<tr>
        	<th colspan="8">Senators</th>
        </tr>
    </thead>
	<tbody>
<?php

 //GET TEAM ROSTER
	$sen="SELECT members.member_id, active, party FROM `rosters`,`members` WHERE `period_id`='$period_id' AND rosters.team_id='$team_id' and members.member_id=rosters.member_id AND members.type='sen'"; 
    $sen=mysql_query($sen); 
$num_rows = mysql_num_rows($sen);

if($num_rows == 0) { 
$sen="SELECT members.member_id, active FROM `rosters`,`members` WHERE `period_id`=0 AND rosters.team_id='$team_id' and members.member_id=rosters.member_id AND members.type='sen' ORDER BY members.lastname"; 
    $sen=mysql_query($sen); 
	$operiod = 0;
}
	while ($sen_row=mysql_fetch_array($sen)) { 

    	$senator_id=$sen_row['member_id'];
		$senator_data 	= member_data($senator_id, 'type', 'middlename', 'lastname','firstname','title','state', 'party');
    	if (trim($senator_data['middlename']) <> "") {
    		$senator_middle = $senator_data['middlename'] . " ";
    	} else{
    		$senator_middle ="";
    	}
        if (trim($senator_data['party']) == "Republican") {
    		$senator_party = "R";
    	} else if (trim($senator_data['party']) == "Democrat"){
    		$senator_party ="D";
    	} else {
                $senator_party = "I";
        }
    		$senator_name= $senator_data['title'] . " " .$senator_data["lastname"] . ", " . $senator_data['firstname'] . " " . $senator_middle . "(" .  $senator_data['state'] .", ".$senator_party. ")"; 
			$active = $sen_row['active'];
			$party = $sen_row['party'];
			$period = $_SESSION['period_id'];
			$sponsor_score=sponsor_count($senator_id,$period)*$points['sponsor_introduce'];					
			$pass_house_sponsor_score=pass_house_sponsor($senator_id,$period)*$points['sponsor_pass_house'];					
			$pass_senate_sponsor_score=pass_senate_sponsor($senator_id,$period)*$points['sponsor_pass_senate'];					
			$sponsor_enacted_score=sponsor_enacted($senator_id,$period)*$points['sponsor_enacted'];					
			$yea_vote_score=yea_vote($senator_id,$period)*$points['yea_vote'];					
			$cosponsor_score=cosponsor_count($senator_id,$period)*$points['cosponsor_introduce'];					
			$pass_house_cosponsor_score=pass_house_cosponsor($senator_id,$period)*$points['cosponsor_pass_house'];					
			$pass_senate_cosponsor_score=pass_senate_cosponsor($senator_id,$period)*$points['cosponsor_pass_senate'];					
			$cosponsor_enacted_score=cosponsor_enacted($senator_id,$period)*$points['cosponsor_enacted'];					
			$bipartisan_score=bipartisan($senator_id,$period,$party)*$points['bipartisan'];
			$member_points= array($sponsor_score,$pass_house_sponsor_score,$pass_senate_sponsor_score,$sponsor_enacted_score,$yea_vote_score,$cosponsor_score,$pass_house_cosponsor_score,$pass_senate_cosponsor_score,$cosponsor_enacted_score,$bipartisan_score);	
			?>				
 		<tr>
        	<td rowspan="2"><input type="checkbox" disabled="disabled" name="active" <?php if ($active ==1){echo 'checked="checked"';}?>></td>
            <th rowspan="2"><?php echo "<a href=\"member.php?member_id=".$senator_id."\">".$senator_name."</a>";?></th>
   			<td><?php echo $sponsor_score;if ($active ==1){$sponsor_count[] = $sponsor_score;}?></td>
   			<td><?php echo $pass_house_sponsor_score;if ($active ==1){$pass_house_sponsor[] = $pass_house_sponsor_score;}?></td>
   			<td><?php echo $pass_senate_sponsor_score;if ($active ==1){$pass_senate_sponsor[] = $pass_senate_sponsor_score;}?></td>
   			<td><?php echo $sponsor_enacted_score;if ($active ==1){$sponsor_enacted[] = $sponsor_enacted_score;}?></td>
   			<td><?php echo $yea_vote_score;if ($active ==1){$yea_vote[] = $yea_vote_score;}?></td>
            <td rowspan="2"><?php if ($active ==1){echo array_sum($member_points);}else{echo"--";}?></td>            
       </tr>
       <tr>
   			<td><?php echo $cosponsor_score;if ($active ==1){$cosponsor_count[] = $cosponsor_score;}?></td>
   			<td><?php echo $pass_house_cosponsor_score;if ($active ==1){$pass_house_cosponsor[] = $pass_house_cosponsor_score;}?></td>
   			<td><?php echo $pass_senate_cosponsor_score;if ($active ==1){$pass_senate_cosponsor[] = $pass_senate_cosponsor_score;}?></td>
   			<td><?php echo $cosponsor_enacted_score;if ($active ==1){$cosponsor_enacted[] = $cosponsor_enacted_score;}?></td>
   			<td><?php echo $bipartisan_score;if ($active ==1){$bipartisan[] = $bipartisan_score;}?></td>
       </tr>       
<?php
}
?>
	</tbody>
	<thead>
    	<tr>
    		<th colspan=8>
    		<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
		<!-- Index -->
		<ins class="adsbygoogle"
     		style="display:block"
     		data-ad-client="ca-pub-7715624314417434"
     		data-ad-slot="6128435147"
     		data-ad-format="auto"></ins>
		<script>
		(adsbygoogle = window.adsbygoogle || []).push({});
		</script>
		</th>
	</tr>
    	<tr>
        	<th colspan=8>Representatives</th>
        </tr>
    </thead>
    <tbody>
<?php
$rep="SELECT members.member_id, active, party FROM `rosters`,`members` WHERE `period_id`='$period_id' AND rosters.team_id='$team_id' and members.member_id=rosters.member_id AND members.type='rep'"; 
    $rep=mysql_query($rep); 
$num_rows = mysql_num_rows($rep);

if($num_rows == 0) { 
$rep="SELECT members.member_id, active FROM `rosters`,`members` WHERE `period_id`=0 AND rosters.team_id='$team_id' and members.member_id=rosters.member_id AND members.type='rep' ORDER BY members.lastname"; 
    $rep=mysql_query($rep); 
	$operiod = 0;
}
	while ($rep_row=mysql_fetch_array($rep)) { 
    	$rep_id=$rep_row['member_id'];
		$rep_data 	= member_data($rep_id, 'type', 'district', 'middlename', 'lastname','firstname','title','state', 'party');
    	if (trim($rep_data['type']) === "rep"){
    		$district = " - " . $rep_data['district'];
    	} else{
    		$district ="";
    	}
    	if (trim($rep_data['middlename']) <> "") {
    		$rep_middle = $rep_data['middlename'] . " ";
    	} else{
    		$rep_middle ="";
    	}
        if (trim($rep_data['party']) == "Republican") {
    		$rep_party= "R";
    	} else if (trim($rep_data['party']) == "Democrat"){
    		$rep_party="D";
    	} else {
                $rep_party= "I";
        }
			$rep_name= $rep_data['title'] . " " .$rep_data["lastname"] . ", " . $rep_data['firstname'] . " " . $rep_middle . "(" .  $rep_data['state'] . $district . ", ".$rep_party.")"; 
			$active = $rep_row['active'];
			$party = $rep_row['party'];
			$sponsor_score=sponsor_count($rep_id,$period)*$points['sponsor_introduce'];					
			$pass_house_sponsor_score=pass_house_sponsor($rep_id,$period)*$points['sponsor_pass_house'];					
			$pass_senate_sponsor_score=pass_senate_sponsor($rep_id,$period)*$points['sponsor_pass_senate'];					
			$sponsor_enacted_score=sponsor_enacted($rep_id,$period)*$points['sponsor_enacted'];					
			$yea_vote_score=yea_vote($rep_id,$period)*$points['yea_vote'];					
			$cosponsor_score=cosponsor_count($rep_id,$period)*$points['cosponsor_introduce'];					
			$pass_house_cosponsor_score=pass_house_cosponsor($rep_id,$period)*$points['cosponsor_pass_house'];					
			$pass_senate_cosponsor_score=pass_senate_cosponsor($rep_id,$period)*$points['cosponsor_pass_senate'];					
			$cosponsor_enacted_score=cosponsor_enacted($rep_id,$period)*$points['cosponsor_enacted'];					
			$bipartisan_score=bipartisan($rep_id,$period,$party)*$points['bipartisan'];
			$member_points= array($sponsor_score,$pass_house_sponsor_score,$pass_senate_sponsor_score,$sponsor_enacted_score,$yea_vote_score,$cosponsor_score,$pass_house_cosponsor_score,$pass_senate_cosponsor_score,$cosponsor_enacted_score,$bipartisan_score);	
	
			?>				
 		<tr>
        	<td rowspan="2"><input type="checkbox" disabled="disabled" name="active" <?php if ($active ==1){echo 'checked="checked"';}?>></td>
            <th rowspan="2"><?php echo "<a href=\"member.php?member_id=".$rep_id."\">".$rep_name."</a>";?></th>
   			<td><?php echo $sponsor_score;if ($active ==1){$sponsor_count[] = $sponsor_score;}?></td>
   			<td><?php echo $pass_house_sponsor_score;if ($active ==1){$pass_house_sponsor[] = $pass_house_sponsor_score;}?></td>
   			<td><?php echo $pass_senate_sponsor_score;if ($active ==1){$pass_senate_sponsor[] = $pass_senate_sponsor_score;}?></td>
   			<td><?php echo $sponsor_enacted_score;if ($active ==1){$sponsor_enacted[] = $sponsor_enacted_score;}?></td>
   			<td><?php echo $yea_vote_score;if ($active ==1){$yea_vote[] = $yea_vote_score;}?></td>
            <td rowspan="2"><?php if ($active ==1){echo array_sum($member_points);}else{echo"--";}?> </td>           
       </tr>
       <tr>
   			<td><?php echo $cosponsor_score;if ($active ==1){$cosponsor_count[] = $cosponsor_score;}?></td>
   			<td><?php echo $pass_house_cosponsor_score;if ($active ==1){$pass_house_cosponsor[] = $pass_house_cosponsor_score;}?></td>
   			<td><?php echo $pass_senate_cosponsor_score;if ($active ==1){$pass_senate_cosponsor[] = $pass_senate_cosponsor_score;}?></td>
   			<td><?php echo $cosponsor_enacted_score;if ($active ==1){$cosponsor_enacted[] = $cosponsor_enacted_score;}?></td>
   			<td><?php echo $bipartisan_score;if ($active ==1){$bipartisan[] = $bipartisan_score;}?></td>
       </tr>
<?php       
}
?>
	</tbody>
    <tfoot>
    	<tr>
        	<th colspan="2" rowspan="2">Team Total</th>
            <td><?php if (empty($sponsor_count) !== true) {echo array_sum($sponsor_count);} else{echo '0';}?></td>
            <td><?php if (empty($pass_house_sponsor) !== true) {echo array_sum($pass_house_sponsor);} else{echo '0';}?></td>
            <td><?php if (empty($pass_senate_sponsor) !== true) {echo array_sum($pass_senate_sponsor);} else{echo '0';}?></td>
            <td><?php if (empty($sponsor_enacted) !== true) {echo array_sum($sponsor_enacted);} else{echo '0';}?></td>
            <td><?php if (empty($yea_vote) !== true) {echo array_sum($yea_vote);} else{echo '0';}?></td>
            <td rowspan="2"><?php if (empty($yea_vote) !== true) {echo array_sum(array(array_sum($sponsor_count),array_sum($pass_house_sponsor),array_sum($pass_senate_sponsor),array_sum($sponsor_enacted),array_sum($yea_vote),array_sum($cosponsor_count),array_sum($pass_house_cosponsor),array_sum($pass_senate_cosponsor),array_sum($cosponsor_enacted),array_sum($bipartisan)));} else{echo'0';}?></td>
        </tr>
		<tr>
            <td><?php if (empty($cosponsor_count) !== true) {echo array_sum($cosponsor_count);} else{echo '0';}?></td>
            <td><?php if (empty($pass_house_cosponsor) !== true) {echo array_sum($pass_house_cosponsor);} else{echo '0';}?></td>
            <td><?php if (empty($pass_senate_cosponsor) !== true) {echo array_sum($pass_senate_cosponsor);} else{echo '0';}?></td>
            <td><?php if (empty($cosponsor_enacted) !== true) {echo array_sum($cosponsor_enacted);} else{echo '0';}?></td>
            <td><?php if (empty($bipartisan) !== true) {echo array_sum($bipartisan);} else{echo '0';}?></td>
		</tr>
            
        </tr>
	</tfoot>
</table>
</div>

<?php
} 
} else {
?>
<table class="results">
	<thead>
		<tr> 
        	<th>Active</th>
            <th width="100%">Member</th>  
            <th>Spons./ Conspons.</th> 
            <th>House</th> 
            <th>Senate</th> 
            <th>Enacted</th> 
            <th>Votes / Bipartisan</th> 
            <th>Points</th>
        </tr>
<?php
//SHOW POINT VALUES 
$query="SELECT * FROM points,teams WHERE teams.team_id='$team_id' AND teams.league_id=points.league_id";
$result=mysql_query($query);
$points=mysql_fetch_array($result);
?> 
        <tr>
        	<td colspan=2 rowspan=2>League Point Values</td>
   			<td><?php echo $points['sponsor_introduce'];?></td>
   			<td><?php echo $points['sponsor_pass_house'];?></td>
   			<td><?php echo $points['sponsor_pass_senate'];?></td>
   			<td><?php echo $points['sponsor_enacted'];?></td>
   			<td><?php echo $points['yea_vote'];?></td>
   			<td></td>
        </tr>
        <tr>
        	<td><?php echo $points['cosponsor_introduce'];?></td>
   			<td><?php echo $points['cosponsor_pass_house'];?></td>
   			<td><?php echo $points['cosponsor_pass_senate'];?></td>
   			<td><?php echo $points['cosponsor_enacted'];?></td>
   			<td><?php echo $points['bipartisan'];?></td>
            <td></td>
        </tr>
	</thead>
	<thead>
    	<tr>
        	<th colspan="8">Senators</th>
        </tr>
    </thead>
	<tbody>
<?php

 //GET TEAM ROSTER
	$sen="SELECT members.member_id, active, party FROM `rosters`,`members` WHERE `period_id`='$period_id' AND rosters.team_id='$team_id' and members.member_id=rosters.member_id AND members.type='sen'"; 
    $sen=mysql_query($sen); 
$num_rows = mysql_num_rows($sen);

if($num_rows == 0) { 
$sen="SELECT members.member_id, active FROM `rosters`,`members` WHERE `period_id`=0 AND rosters.team_id='$team_id' and members.member_id=rosters.member_id AND members.type='sen' ORDER BY members.lastname"; 
    $sen=mysql_query($sen); 
	$operiod = 0;
}
	while ($sen_row=mysql_fetch_array($sen)) { 

    	$senator_id=$sen_row['member_id'];
		$senator_data 	= member_data($senator_id, 'type', 'middlename', 'lastname','firstname','title','state', 'party');
    	if (trim($senator_data['middlename']) <> "") {
    		$senator_middle = $senator_data['middlename'] . " ";
    	} else{
    		$senator_middle ="";
    	}
        if (trim($senator_data['party']) == "Republican") {
    		$senator_party = "R";
    	} else if (trim($senator_data['party']) == "Democrat"){
    		$senator_party ="D";
    	} else {
                $senator_party = "I";
        }
    		$senator_name= $senator_data['title'] . " " .$senator_data["lastname"] . ", " . $senator_data['firstname'] . " " . $senator_middle . "(" .  $senator_data['state'] .", ".$senator_party. ")"; 
			$active = $sen_row['active'];
			$party = $sen_row['party'];
			$period = $_SESSION['period_id'];
			$sponsor_score=sponsor_count($senator_id,$period)*$points['sponsor_introduce'];					
			$pass_house_sponsor_score=pass_house_sponsor($senator_id,$period)*$points['sponsor_pass_house'];					
			$pass_senate_sponsor_score=pass_senate_sponsor($senator_id,$period)*$points['sponsor_pass_senate'];					
			$sponsor_enacted_score=sponsor_enacted($senator_id,$period)*$points['sponsor_enacted'];					
			$yea_vote_score=yea_vote($senator_id,$period)*$points['yea_vote'];					
			$cosponsor_score=cosponsor_count($senator_id,$period)*$points['cosponsor_introduce'];					
			$pass_house_cosponsor_score=pass_house_cosponsor($senator_id,$period)*$points['cosponsor_pass_house'];					
			$pass_senate_cosponsor_score=pass_senate_cosponsor($senator_id,$period)*$points['cosponsor_pass_senate'];					
			$cosponsor_enacted_score=cosponsor_enacted($senator_id,$period)*$points['cosponsor_enacted'];					
			$bipartisan_score=bipartisan($senator_id,$period,$party)*$points['bipartisan'];
			$member_points= array($sponsor_score,$pass_house_sponsor_score,$pass_senate_sponsor_score,$sponsor_enacted_score,$yea_vote_score,$cosponsor_score,$pass_house_cosponsor_score,$pass_senate_cosponsor_score,$cosponsor_enacted_score,$bipartisan_score);	
			?>				
 		<tr>
        	<td rowspan="2"><input type="checkbox" disabled="disabled" name="active" <?php if ($active ==1){echo 'checked="checked"';}?>></td>
            <th rowspan="2"><?php echo "<a href=\"member.php?member_id=".$senator_id."\">".$senator_name."</a>";?></th>
   			<td><?php echo $sponsor_score;if ($active ==1){$sponsor_count[] = $sponsor_score;}?></td>
   			<td><?php echo $pass_house_sponsor_score;if ($active ==1){$pass_house_sponsor[] = $pass_house_sponsor_score;}?></td>
   			<td><?php echo $pass_senate_sponsor_score;if ($active ==1){$pass_senate_sponsor[] = $pass_senate_sponsor_score;}?></td>
   			<td><?php echo $sponsor_enacted_score;if ($active ==1){$sponsor_enacted[] = $sponsor_enacted_score;}?></td>
   			<td><?php echo $yea_vote_score;if ($active ==1){$yea_vote[] = $yea_vote_score;}?></td>
            <td rowspan="2"><?php if ($active ==1){echo array_sum($member_points);}else{echo"--";}?></td>            
       </tr>
       <tr>
   			<td><?php echo $cosponsor_score;if ($active ==1){$cosponsor_count[] = $cosponsor_score;}?></td>
   			<td><?php echo $pass_house_cosponsor_score;if ($active ==1){$pass_house_cosponsor[] = $pass_house_cosponsor_score;}?></td>
   			<td><?php echo $pass_senate_cosponsor_score;if ($active ==1){$pass_senate_cosponsor[] = $pass_senate_cosponsor_score;}?></td>
   			<td><?php echo $cosponsor_enacted_score;if ($active ==1){$cosponsor_enacted[] = $cosponsor_enacted_score;}?></td>
   			<td><?php echo $bipartisan_score;if ($active ==1){$bipartisan[] = $bipartisan_score;}?></td>
       </tr>       
<?php
}
?>
	</tbody>
	<thead>
    	
    	<tr>
        	<th colspan=8>Representatives</th>
        </tr>
    </thead>
    <tbody>
<?php
$rep="SELECT members.member_id, active, party FROM `rosters`,`members` WHERE `period_id`='$period_id' AND rosters.team_id='$team_id' and members.member_id=rosters.member_id AND members.type='rep'"; 
    $rep=mysql_query($rep); 
$num_rows = mysql_num_rows($rep);

if($num_rows == 0) { 
$rep="SELECT members.member_id, active FROM `rosters`,`members` WHERE `period_id`=0 AND rosters.team_id='$team_id' and members.member_id=rosters.member_id AND members.type='rep' ORDER BY members.lastname"; 
    $rep=mysql_query($rep); 
	$operiod = 0;
}
	while ($rep_row=mysql_fetch_array($rep)) { 
    	$rep_id=$rep_row['member_id'];
		$rep_data 	= member_data($rep_id, 'type', 'district', 'middlename', 'lastname','firstname','title','state', 'party');
    	if (trim($rep_data['type']) === "rep"){
    		$district = " - " . $rep_data['district'];
    	} else{
    		$district ="";
    	}
    	if (trim($rep_data['middlename']) <> "") {
    		$rep_middle = $rep_data['middlename'] . " ";
    	} else{
    		$rep_middle ="";
    	}
        if (trim($rep_data['party']) == "Republican") {
    		$rep_party= "R";
    	} else if (trim($rep_data['party']) == "Democrat"){
    		$rep_party="D";
    	} else {
                $rep_party= "I";
        }
			$rep_name= $rep_data['title'] . " " .$rep_data["lastname"] . ", " . $rep_data['firstname'] . " " . $rep_middle . "(" .  $rep_data['state'] . $district . ", ".$rep_party.")"; 
			$active = $rep_row['active'];
			$party = $rep_row['party'];
			$sponsor_score=sponsor_count($rep_id,$period)*$points['sponsor_introduce'];					
			$pass_house_sponsor_score=pass_house_sponsor($rep_id,$period)*$points['sponsor_pass_house'];					
			$pass_senate_sponsor_score=pass_senate_sponsor($rep_id,$period)*$points['sponsor_pass_senate'];					
			$sponsor_enacted_score=sponsor_enacted($rep_id,$period)*$points['sponsor_enacted'];					
			$yea_vote_score=yea_vote($rep_id,$period)*$points['yea_vote'];					
			$cosponsor_score=cosponsor_count($rep_id,$period)*$points['cosponsor_introduce'];					
			$pass_house_cosponsor_score=pass_house_cosponsor($rep_id,$period)*$points['cosponsor_pass_house'];					
			$pass_senate_cosponsor_score=pass_senate_cosponsor($rep_id,$period)*$points['cosponsor_pass_senate'];					
			$cosponsor_enacted_score=cosponsor_enacted($rep_id,$period)*$points['cosponsor_enacted'];					
			$bipartisan_score=bipartisan($rep_id,$period,$party)*$points['bipartisan'];
			$member_points= array($sponsor_score,$pass_house_sponsor_score,$pass_senate_sponsor_score,$sponsor_enacted_score,$yea_vote_score,$cosponsor_score,$pass_house_cosponsor_score,$pass_senate_cosponsor_score,$cosponsor_enacted_score,$bipartisan_score);	
	
			?>				
 		<tr>
        	<td rowspan="2"><input type="checkbox" disabled="disabled" name="active" <?php if ($active ==1){echo 'checked="checked"';}?>></td>
            <th rowspan="2"><?php echo "<a href=\"member.php?member_id=".$rep_id."\">".$rep_name."</a>";?></th>
   			<td><?php echo $sponsor_score;if ($active ==1){$sponsor_count[] = $sponsor_score;}?></td>
   			<td><?php echo $pass_house_sponsor_score;if ($active ==1){$pass_house_sponsor[] = $pass_house_sponsor_score;}?></td>
   			<td><?php echo $pass_senate_sponsor_score;if ($active ==1){$pass_senate_sponsor[] = $pass_senate_sponsor_score;}?></td>
   			<td><?php echo $sponsor_enacted_score;if ($active ==1){$sponsor_enacted[] = $sponsor_enacted_score;}?></td>
   			<td><?php echo $yea_vote_score;if ($active ==1){$yea_vote[] = $yea_vote_score;}?></td>
            <td rowspan="2"><?php if ($active ==1){echo array_sum($member_points);}else{echo"--";}?> </td>           
       </tr>
       <tr>
   			<td><?php echo $cosponsor_score;if ($active ==1){$cosponsor_count[] = $cosponsor_score;}?></td>
   			<td><?php echo $pass_house_cosponsor_score;if ($active ==1){$pass_house_cosponsor[] = $pass_house_cosponsor_score;}?></td>
   			<td><?php echo $pass_senate_cosponsor_score;if ($active ==1){$pass_senate_cosponsor[] = $pass_senate_cosponsor_score;}?></td>
   			<td><?php echo $cosponsor_enacted_score;if ($active ==1){$cosponsor_enacted[] = $cosponsor_enacted_score;}?></td>
   			<td><?php echo $bipartisan_score;if ($active ==1){$bipartisan[] = $bipartisan_score;}?></td>
       </tr>
<?php       
}
?>
	</tbody>
    <tfoot>
    	<tr>
        	<th colspan="2" rowspan="2">Team Total</th>
            <td><?php if (empty($sponsor_count) !== true) {echo array_sum($sponsor_count);} else{echo '0';}?></td>
            <td><?php if (empty($pass_house_sponsor) !== true) {echo array_sum($pass_house_sponsor);} else{echo '0';}?></td>
            <td><?php if (empty($pass_senate_sponsor) !== true) {echo array_sum($pass_senate_sponsor);} else{echo '0';}?></td>
            <td><?php if (empty($sponsor_enacted) !== true) {echo array_sum($sponsor_enacted);} else{echo '0';}?></td>
            <td><?php if (empty($yea_vote) !== true) {echo array_sum($yea_vote);} else{echo '0';}?></td>
            <td rowspan="2"><?php if (empty($yea_vote) !== true) {echo array_sum(array(array_sum($sponsor_count),array_sum($pass_house_sponsor),array_sum($pass_senate_sponsor),array_sum($sponsor_enacted),array_sum($yea_vote),array_sum($cosponsor_count),array_sum($pass_house_cosponsor),array_sum($pass_senate_cosponsor),array_sum($cosponsor_enacted),array_sum($bipartisan)));} else{echo'0';}?></td>
        </tr>
		<tr>
            <td><?php if (empty($cosponsor_count) !== true) {echo array_sum($cosponsor_count);} else{echo '0';}?></td>
            <td><?php if (empty($pass_house_cosponsor) !== true) {echo array_sum($pass_house_cosponsor);} else{echo '0';}?></td>
            <td><?php if (empty($pass_senate_cosponsor) !== true) {echo array_sum($pass_senate_cosponsor);} else{echo '0';}?></td>
            <td><?php if (empty($cosponsor_enacted) !== true) {echo array_sum($cosponsor_enacted);} else{echo '0';}?></td>
            <td><?php if (empty($bipartisan) !== true) {echo array_sum($bipartisan);} else{echo '0';}?></td>
		</tr>
            
        </tr>
	</tfoot>
</table>
</div>
<?
}
}
}
} else {
header('Location: league.php?create');
}

include 'includes/overall/footer.php'?>	