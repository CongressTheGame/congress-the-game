<? 
$player_id=$_GET['id'];

if(isset($_GET['id']) === true && $player_id !== $user_id){

$player_data = user_data($player_id, 'username');
?>
<div id='results'>
<h2><? echo $player_data['username']."'s Team"; ?></h2>
<table class="results">
	<thead>
		<tr> 
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
$query="SELECT * FROM points WHERE league_id=1";
$result=mysql_query($query);
$points=mysql_fetch_array($result);
?> 
        <tr>
        	<td rowspan=2>Open League Point Values</td>
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
 	$sen="SELECT open.member_id, party FROM `open`,`members` WHERE `period_id`='$period_id' AND open.user_id='$player_id' and open.member_id=members.member_id AND members.type='sen'"; 
    $sen=mysql_query($sen); 
$num_rows = mysql_num_rows($sen);
if($num_rows == 0) { 
	echo '<tr><td colspan="7">No Senators Picked.</td></tr>';
}else{
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
            <th rowspan="2"><?php echo "<a href=\"member.php?member_id=".$senator_id."\">".$senator_name."</a>";?></th>
   			<td><?php echo $sponsor_score;$sponsor_count[] = $sponsor_score;?></td>
   			<td><?php echo $pass_house_sponsor_score;$pass_house_sponsor[] = $pass_house_sponsor_score;?></td>
   			<td><?php echo $pass_senate_sponsor_score;$pass_senate_sponsor[] = $pass_senate_sponsor_score;?></td>
   			<td><?php echo $sponsor_enacted_score;$sponsor_enacted[] = $sponsor_enacted_score;?></td>
   			<td><?php echo $yea_vote_score;$yea_vote[] = $yea_vote_score;?></td>
            <td rowspan="2"><?php echo array_sum($member_points);?></td>            
       </tr>
       <tr>
   			<td><?php echo $cosponsor_score;$cosponsor_count[] = $cosponsor_score;?></td>
   			<td><?php echo $pass_house_cosponsor_score;$pass_house_cosponsor[] = $pass_house_cosponsor_score;?></td>
   			<td><?php echo $pass_senate_cosponsor_score;$pass_senate_cosponsor[] = $pass_senate_cosponsor_score;?></td>
   			<td><?php echo $cosponsor_enacted_score;$cosponsor_enacted[] = $cosponsor_enacted_score;?></td>
   			<td><?php echo $bipartisan_score;$bipartisan[] = $bipartisan_score;?></td>
       </tr>       
<?php
}}
?>
	</tbody>
	<thead>
    	<tr>
        	<th colspan=8>Representatives</th>
        </tr>
    </thead>
    <tbody>
<?php
$rep="SELECT open.member_id, party FROM `open`,`members` WHERE `period_id`='$period_id' AND open.user_id='$player_id' and open.member_id=members.member_id AND members.type='rep'"; 
    $rep=mysql_query($rep); 
$num_rows = mysql_num_rows($rep);

if($num_rows == 0) { 
	echo '<tr><td colspan="8">No Representatives Picked.</td></tr>';

} else {
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
            <th rowspan="2"><?php echo "<a href=\"member.php?member_id=".$rep_id."\">".$rep_name."</a>";?></th>
   			<td><?php echo $sponsor_score;$sponsor_count[] = $sponsor_score;?></td>
   			<td><?php echo $pass_house_sponsor_score;$pass_house_sponsor[] = $pass_house_sponsor_score;?></td>
   			<td><?php echo $pass_senate_sponsor_score;$pass_senate_sponsor[] = $pass_senate_sponsor_score;?></td>
   			<td><?php echo $sponsor_enacted_score;$sponsor_enacted[] = $sponsor_enacted_score;?></td>
   			<td><?php echo $yea_vote_score;$yea_vote[] = $yea_vote_score;?></td>
            <td rowspan="2"><?php echo array_sum($member_points);?> </td>           
       </tr>
       <tr>
   			<td><?php echo $cosponsor_score;$cosponsor_count[] = $cosponsor_score;?></td>
   			<td><?php echo $pass_house_cosponsor_score;$pass_house_cosponsor[] = $pass_house_cosponsor_score;?></td>
   			<td><?php echo $pass_senate_cosponsor_score;$pass_senate_cosponsor[] = $pass_senate_cosponsor_score;?></td>
   			<td><?php echo $cosponsor_enacted_score;$cosponsor_enacted[] = $cosponsor_enacted_score;?></td>
   			<td><?php echo $bipartisan_score;$bipartisan[] = $bipartisan_score;?></td>
       </tr>
<?php       
}}
?>
	</tbody>
    <tfoot>
    	<tr>
        	<th rowspan="2">Team Total</th>
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
</br>
<?        
        
} else {
//////////YOUR OWN TEAM///////////
?>
<div id='results'>
<h2>Your Team</h2>
<table class="results">
	<thead>
		<tr> 
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
$query="SELECT * FROM points WHERE league_id=1";
$result=mysql_query($query);
$points=mysql_fetch_array($result);
?> 
        <tr>
        	<td rowspan=2>Open League Point Values</td>
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
 	$sen="SELECT open.member_id, party FROM `open`,`members` WHERE `period_id`='$period_id' AND open.user_id='$user_id' and open.member_id=members.member_id AND members.type='sen'"; 
    $sen=mysql_query($sen); 
$num_rows = mysql_num_rows($sen);
if($num_rows == 0) { 
	echo '<tr><td colspan="7">No Senators Picked.</td></tr>';
}else{
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
            <th rowspan="2"><?php echo "<a href=\"member.php?member_id=".$senator_id."\">".$senator_name."</a>";?></th>
   			<td><?php echo $sponsor_score;$sponsor_count[] = $sponsor_score;?></td>
   			<td><?php echo $pass_house_sponsor_score;$pass_house_sponsor[] = $pass_house_sponsor_score;?></td>
   			<td><?php echo $pass_senate_sponsor_score;$pass_senate_sponsor[] = $pass_senate_sponsor_score;?></td>
   			<td><?php echo $sponsor_enacted_score;$sponsor_enacted[] = $sponsor_enacted_score;?></td>
   			<td><?php echo $yea_vote_score;$yea_vote[] = $yea_vote_score;?></td>
            <td rowspan="2"><?php echo array_sum($member_points);?></td>            
       </tr>
       <tr>
   			<td><?php echo $cosponsor_score;$cosponsor_count[] = $cosponsor_score;?></td>
   			<td><?php echo $pass_house_cosponsor_score;$pass_house_cosponsor[] = $pass_house_cosponsor_score;?></td>
   			<td><?php echo $pass_senate_cosponsor_score;$pass_senate_cosponsor[] = $pass_senate_cosponsor_score;?></td>
   			<td><?php echo $cosponsor_enacted_score;$cosponsor_enacted[] = $cosponsor_enacted_score;?></td>
   			<td><?php echo $bipartisan_score;$bipartisan[] = $bipartisan_score;?></td>
       </tr>       
<?php
}}
?>
	</tbody>
	<thead>
    	<tr>
        	<th colspan=8>Representatives</th>
        </tr>
    </thead>
    <tbody>
<?php
$rep="SELECT open.member_id, party FROM `open`,`members` WHERE `period_id`='$period_id' AND open.user_id='$user_id' and open.member_id=members.member_id AND members.type='rep'"; 
    $rep=mysql_query($rep); 
$num_rows = mysql_num_rows($rep);

if($num_rows == 0) { 
	echo '<tr><td colspan="8">No Representatives Picked.</td></tr>';

} else {
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
            <th rowspan="2"><?php echo "<a href=\"member.php?member_id=".$rep_id."\">".$rep_name."</a>";?></th>
   			<td><?php echo $sponsor_score;$sponsor_count[] = $sponsor_score;?></td>
   			<td><?php echo $pass_house_sponsor_score;$pass_house_sponsor[] = $pass_house_sponsor_score;?></td>
   			<td><?php echo $pass_senate_sponsor_score;$pass_senate_sponsor[] = $pass_senate_sponsor_score;?></td>
   			<td><?php echo $sponsor_enacted_score;$sponsor_enacted[] = $sponsor_enacted_score;?></td>
   			<td><?php echo $yea_vote_score;$yea_vote[] = $yea_vote_score;?></td>
            <td rowspan="2"><?php echo array_sum($member_points);?> </td>           
       </tr>
       <tr>
   			<td><?php echo $cosponsor_score;$cosponsor_count[] = $cosponsor_score;?></td>
   			<td><?php echo $pass_house_cosponsor_score;$pass_house_cosponsor[] = $pass_house_cosponsor_score;?></td>
   			<td><?php echo $pass_senate_cosponsor_score;$pass_senate_cosponsor[] = $pass_senate_cosponsor_score;?></td>
   			<td><?php echo $cosponsor_enacted_score;$cosponsor_enacted[] = $cosponsor_enacted_score;?></td>
   			<td><?php echo $bipartisan_score;$bipartisan[] = $bipartisan_score;?></td>
       </tr>
<?php       
}}
?>
	</tbody>
    <tfoot>
    	<tr>
        	<th rowspan="2">Team Total</th>
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

<?php
if($current == 1) {

//SELECT NEW MEMBERS

$query1="SELECT `member_id` FROM `members` WHERE `type`='sen' AND `current`=1 ORDER BY `lastname`"; 
    $result1=mysql_query($query1); 
	$options1=""; 
 
while ($row1=mysql_fetch_array($result1)) { 
    $member_id1=$row1['member_id'];
	$member_data1 	= member_data($member_id1, 'type', 'middlename', 'lastname','firstname','title','state','party');
	 
		
    
    if (trim($member_data1['middlename']) <> "") {
    	$middle1 = $member_data1['middlename'] . " ";
    } else{
    	$middle1 ="";
    }
    if (trim($member_data1['party']) == "Republican") {
    	$party1= "R";
    } else if(trim($member_data1['party']) == "Democrat"){
    	$party1="D";
    } else {
        $party1="I";
    }
    $name1= $member_data1["lastname"] . ", " . $member_data1['firstname'] . " " . $middle1 . "(" . $member_data1['title'] . ", " . $member_data1['state'] . ", " . $party1 . ")";
    	$member1.="<OPTION VALUE=\"$member_id1\">".$name1;
}

$query2="SELECT `member_id` FROM `members` WHERE `type`='rep' AND `current`=1 ORDER BY `lastname`"; 
    $result2=mysql_query($query2); 
	$options2=""; 
 
while ($row2=mysql_fetch_array($result2)) { 
    $member_id2=$row2['member_id'];
	$member_data2 	= member_data($member_id2, 'type', 'district', 'middlename', 'lastname','firstname','title','state','party');
	
		
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
    if (trim($member_data2['party']) == "Republican") {
    	$party2= "R";
    } else if(trim($member_data2['party']) == "Democrat"){
    	$party2="D";
    } else {
        $party2="I";
    }
    $name2= $member_data2["lastname"] . ", " . $member_data2['firstname'] . " " . $middle2 . "(" . $member_data2['title'] . ", " . $member_data2['state'] . $district2 . ", " . $party2 . ")"; 
    	$member2.="<OPTION VALUE=\"$member_id2\">".$name2;	 
}
?>
<div class="blueBox">
<h2>Select Team Members</h2>

<form action="" method="post">
<ul>
   <li>
      <h3>Senators</h3>
   </li>
   <?php for ($num=1; $num <= 2; $num++){?>
   <li class='doubleNoLine'>
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
   <?php for ($num=1; $num <= 5; $num++){?>
   <li class='doubleNoLine'>
      <SELECT NAME=member_id[] class="draft"> 
      <OPTION VALUE=0 class="draft">Choose a Representative...
      <?php echo $member2;?> </OPTION>
      </SELECT>
    </li>
<?php }?>
   <li class='doubleNoLine'>&nbsp</li>
</ul>

<input type="submit" id="register" value="Select Team">

</form>
</div>
<?php
if(isset($_GET['success'])){
echo '<h2 align="center">Members Drafted Succesfully!</h2>';
}
 echo output_errors($errors);


}?>
</div>
<br>
<? } ?>