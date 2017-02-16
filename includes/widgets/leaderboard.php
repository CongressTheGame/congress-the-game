<table class="results">
	<thead>
    	<tr>
        	<th colspan="4">League Leaderboard</th>
        </tr>
		<tr> 
            <td>Rank</td>
            <td width="100%">Team</td>  
            <td>Username</td> 
            <td>Points</td> 
            
        </tr>
	</thead>
	
	<tbody>
  
<?php

 //GET TEAM ROSTER
	$team="SELECT team_id, team_name, username FROM teams, users WHERE teams.user_id=users.user_id AND league_id='$league_id'";
    $team=mysql_query($team); 
			
	$i=1;
	mysql_query("CREATE TEMPORARY TABLE LeaderBoard (`team_name` varchar(100) NOT NULL,`username` varchar(100) NOT NULL,`pts` int(15), `team_id` int(15))") or die(mysql_error('CREATE: '));

while ($team_row=mysql_fetch_array($team)) { 
		$team_id = $team_row['team_id'];
		$per="SELECT period_id FROM period WHERE current <> 1";
		$per=mysql_query($per);
               	while ($per_row=mysql_fetch_array($per)){
			$period = $per_row['period_id'];
			$sen="SELECT members.member_id, active, party FROM `rosters`,`members` WHERE rosters.team_id='$team_id' AND members.member_id=rosters.member_id AND `period_id`='$period'"; 
    		        $sen=mysql_query($sen); 
                        $num_rows = mysql_num_rows($sen);
	                $operiod = $period_id;
                       if($num_rows !== 0) {
			   while ($sen_row=mysql_fetch_array($sen)) {
    			        $senator_id=$sen_row['member_id']; 
				$active = $sen_row['active'];
				$party = $sen_row['party'];	
				if ($active == 1){	
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
					$member_points= array_sum(array($sponsor_score,$pass_house_sponsor_score,$pass_senate_sponsor_score,$sponsor_enacted_score,$yea_vote_score,$cosponsor_score,$pass_house_cosponsor_score,$pass_senate_cosponsor_score,$cosponsor_enacted_score,$bipartisan_score));
						$teams[$team_id][]=$member_points;
				} //END if active
                           } //END of each Member
			
                           if (empty($teams[$team_id]) !== true) {
		               $total[$team_id]=array_sum($teams[$team_id]);	
		           } else {
		               $total[$team_id]=0;
                           }
                      } //END of active exist in period	             
           } //END of each period
                $teamname=$team_row['team_name'];
		$user_name=$team_row['username'];

                if (empty($total[$team_id]) !== true) {
		$pts = ($total[$team_id]);
		} else {
		$pts='0';
		}

/////This query puts the scores into the leaderboard./////
mysql_query("INSERT INTO LeaderBoard(`team_name`, `username`, `pts`, `team_id`) VALUES('$teamname', '$user_name', '$pts', '$team_id')") or die(mysql_error('INSERT: '));

}  //END of each team in league

/////This query pulls information from leaderboard/////
$lead = "SELECT `team_name`, `username`, `pts`, `team_id` FROM LeaderBoard ORDER BY `pts` DESC";
$lead = mysql_query($lead) or die(mysql_error('LEADERBOARD: '));

while ($leader=mysql_fetch_array($lead)) {
		echo'<tr><td>'.$i++.'</td>';
		echo'<td><a href="team.php?team_id='.$leader['team_id'].'">'.$leader['team_name'].'</a></td>';
		echo'<td><a href="/'.$leader['username'].'">'.$leader['username'].'</a></td>';
		echo '<td>'.$leader['pts'].'</td></tr>';   
         }   //END of display leaderboard
   
	?>	
   	
</tbody>
    </table>	