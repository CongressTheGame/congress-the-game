<?php 
//MEMBERS
function update_members(){
        $member_data= array(); 
	$xml = simplexml_load_file('http://www.govtrack.us/data/us/113/people.xml');
	foreach($xml->person as $person) {
		$role = $person->role;
        $member_data = array(
			'member_id'    	=> $person['id'],
			'firstname' 	=> $person['firstname'],
			'middlename' 	=> $person['middlename'],
			'lastname' 		=> $person['lastname'],
			'name'  		=> $person['name'],
			'title' 		=> $person['title'],
			'state' 		=> $person['state'],
			'district' 		=> $person['district'],
			'type'  		=> $role['type'],
			'startdate' 	=> $role['startdate'],
			'enddate' 		=> $role['enddate'],
			'party' 		=> $role['party'],
			'current' 		=> $role['current'],
			'class' 		=> $role['class'],
		);
 
        $update = array();
	array_walk($member_data, 'array_sanitize');
 
	$fields = '`' . implode('`, `', array_keys($member_data)) . '`';
     $data = '\'' . implode('\', \'', $member_data) . '\'';
     foreach($member_data as $fields_update=>$data_update){
             $update[] = '`' . $fields_update . '` = \'' . $data_update. '\'';}
      mysql_query("INSERT INTO `members`($fields) VALUES ($data) ON DUPLICATE KEY UPDATE " . implode(", ", $update)) or die(mysql_error());
}
}

function update_committees(){
        $committees_data= array(); 
	$xml = simplexml_load_file('http://www.govtrack.us/data/us/113/committees.xml');
	foreach($xml->committee as $committee) {
		$code  = $committee['code'];
                $name  = mysql_real_escape_string($committee['displayname']);
        $committees_data= array(
			'code'    	=> $committee['code'],
			'type'    	=> $committee['type'],
			'name'    	=> $name,
			);

	
        $update = array();
	array_walk($committees_data, 'array_sanitize');
     $fields = '`' . implode('`, `', array_keys($committees_data)) . '`';
     $data = '\'' . implode('\', \'', $committees_data) . '\'';
	foreach($committees_data as $fields_update=>$data_update){
		$update[] = '`' . $fields_update . '` = \'' . $data_update . '\'';
	}
      mysql_query("INSERT INTO `committees`($fields) VALUES ($data) ON DUPLICATE KEY UPDATE " . implode(", ", $update)) or die(mysql_error());
}
}

function update_committees_members(){
        $committees_members_data= array(); 
	$xml = simplexml_load_file('http://www.govtrack.us/data/us/113/committees.xml');
	foreach($xml->committee as $committee) {
        foreach($committee->member as $member) {
		$code  = $committee['code'];
                $member_id = $member['id'];
                $role= $member['role'];
                if ($member['role'] == "") {$role = 'Member';}
        
                $committees_members_data= array(
			'committee_id'  => $committee['code'],
			'member_id'    	=> $member_id,
			'role'    	=> $role,
			);

        $update = array();
	array_walk($committees_members_data, 'array_sanitize');
	 $fields = '`' . implode('`, `', array_keys($committees_members_data)) . '`';
     $data = '\'' . implode('\', \'', $committees_members_data) . '\'';
	foreach($committees_members_data as $fields_update=>$data_update){
		$update[] = '`' . $fields_update . '` = \'' . $data_update . '\'';
	}
      mysql_query("INSERT INTO `committees_members`($fields) VALUES ($data) ON DUPLICATE KEY UPDATE " . implode(", ", $update)) or die(mysql_error());
}
}
}

function update_subcommittees(){
        $subcommittees_data= array(); 
	$xml = simplexml_load_file('http://www.govtrack.us/data/us/113/committees.xml');
	foreach($xml->committee as $committee) {
		foreach($committee->subcommittee as $subcommittee){
		$code  = $committee['code'].$subcommittee['code'];
                $name  = mysql_real_escape_string(trim($subcommittee['displayname']));
        $subcommittees_data= array(
			'code'    	=> $code,
			'type'		=> 'subcommittee',
			'name'    	=> $name,
			);

	
        $update = array();
	array_walk($subcommittees_data, 'array_sanitize');
		
     $fields = '`' . implode('`, `', array_keys($subcommittees_data)) . '`';
    $data = '\'' . implode('\', \'', $subcommittees_data) . '\'';
	foreach($subcommittees_data as $fields_update=>$data_update){
		$update[] = '`' . $fields_update . '` = \'' . $data_update . '\'';
	}
      mysql_query("INSERT INTO `committees`($fields) VALUES ($data) ON DUPLICATE KEY UPDATE " . implode(", ", $update)) or die(mysql_error());
}
}
}

function update_subcommittees_members(){
        $subcommittees_members_data= array(); 
	$xml = simplexml_load_file('http://www.govtrack.us/data/us/113/committees.xml');
	foreach($xml->committee as $committee) {
        foreach($committee->subcommittee as $subcommittee) {
			foreach($subcommittee->member as $member){
		        $code  = $committee['code'].$subcommittee['code'];
                $member_id = $member['id'];
                $role= $member['role'];
                if ($member['role'] == "") {$role = 'Member';}
        
                $subcommittees_members_data= array(
			'committee_id'  => $code,
			'member_id'    	=> $member_id,
			'role'    	=> $role,
			);

        $update = array();
	array_walk($subcommittees_members_data, 'array_sanitize');
	 $fields = '`' . implode('`, `', array_keys($subcommittees_members_data)) . '`';
    $data = '\'' . implode('\', \'', $subcommittees_members_data) . '\'';
	foreach($subcommittees_members_data as $fields_update=>$data_update){
		$update[] = '`' . $fields_update . '` = \'' . $data_update . '\'';
	}
			
      mysql_query("INSERT INTO `committees_members`($fields) VALUES ($data) ON DUPLICATE KEY UPDATE " . implode(", ", $update)) or die(mysql_error());
}
}
}
}

function member_exists($member_id){
	$query="SELECT COUNT('member_id') FROM members WHERE member_id='$member_id'";
	$result=mysql_query($query) or die(mysql_error());
	return (mysql_result($result,0) == 1) ? true : false;
}

function member_data($member_id){
	$member_data=array();
	$member_id = (int)$member_id;
	
	$func_num_args = func_num_args();
	
	$func_get_args = func_get_args();
	if($func_num_args > 1){
	unset($func_get_args[0]);}
	
	$fields ='`' . implode('`, `', $func_get_args) . '`';
	$member_data = mysql_fetch_assoc(mysql_query("SELECT $fields FROM members WHERE member_id = '$member_id'"));
	
	return $member_data;
}



//SCORING
function sponsor_count($member_id, $period){
      $query= "SELECT COUNT(bill_id) FROM bills, period WHERE DATE(bills.introduced) BETWEEN DATE(period.startdate) AND DATE(period.enddate) AND (type='s' OR type='h') AND bills.sponsor ='$member_id' AND period.period_id='$period'";
	$result=mysql_query($query) or die(mysql_error());
	
    while($row=mysql_fetch_array($result)){
		$count = $row['COUNT(bill_id)'];
}
return $count;
}

function cosponsor_count($member_id, $period){
    $query= "SELECT COUNT(bill_id) FROM cosponsors, period WHERE DATE(cosponsors.joined) BETWEEN DATE(period.startdate) AND DATE(period.enddate) AND (type='s' OR type='h') AND cosponsors.cosponsor_id ='$member_id' AND period.period_id='$period'";
	$result=mysql_query($query) or die(mysql_error());
	
    while($row=mysql_fetch_array($result)){
		$count = $row['COUNT(bill_id)'];
}
return $count;
}

function pass_house_sponsor ($member_id, $period){
      $query= "SELECT COUNT(1) FROM votes, bills, period WHERE DATE(votes.datetime) BETWEEN DATE(period.startdate) AND DATE(period.enddate) AND bills.sponsor ='$member_id' AND votes.bill_id=bills.bill_id AND period.period_id='$period' AND votes.where='h' AND (votes.state='PASS_BACK:HOUSE' OR votes.state='PASS_OVER:HOUSE' OR votes.state='PASSED:BILL')";
	$result=mysql_query($query) or die(mysql_error());
	
    while($row=mysql_fetch_array($result)){
		$count = $row['COUNT(1)'];
}
return $count;
}

function pass_house_cosponsor ($member_id, $period){
      $query= "SELECT COUNT(votes.bill_id) FROM votes, cosponsors, period WHERE DATE(votes.datetime) BETWEEN DATE(period.startdate) AND DATE(period.enddate) AND cosponsors.cosponsor_id ='$member_id' AND votes.bill_id=cosponsors.bill_id AND period.period_id='$period' AND votes.where='h' AND (votes.state='PASS_BACK:HOUSE' OR votes.state='PASS_OVER:HOUSE' OR votes.state='PASSED:BILL')";
	$result=mysql_query($query) or die(mysql_error());
	
    while($row=mysql_fetch_array($result)){
		$count = $row['COUNT(votes.bill_id)'];
    }
return $count;
}

function pass_senate_sponsor ($member_id, $period){
      $query= "SELECT COUNT(votes.bill_id) FROM votes, bills, period WHERE DATE(votes.datetime) BETWEEN DATE(period.startdate) AND DATE(period.enddate) AND bills.sponsor ='$member_id' AND votes.bill_id=bills.bill_id AND period.period_id='$period' AND votes.where='s' AND (votes.state='PASS_BACK:SENATE' OR votes.state='PASS_OVER:SENATE' OR votes.state='PASSED:BILL')";
	$result=mysql_query($query) or die(mysql_error());
	
    while($row=mysql_fetch_array($result)){
		$count = $row['COUNT(votes.bill_id)'];
    }
return $count;
}

function pass_senate_cosponsor ($member_id, $period){
      $query= "SELECT COUNT(votes.bill_id) FROM votes, cosponsors, period WHERE DATE(votes.datetime) BETWEEN DATE(period.startdate) AND DATE(period.enddate) AND cosponsors.cosponsor_id ='$member_id' AND votes.bill_id=cosponsors.bill_id AND period.period_id='$period' AND votes.where='s' AND (votes.state='PASS_BACK:SENATE' OR votes.state='PASS_OVER:SENATE' OR votes.state='PASSED:BILL')";
	$result=mysql_query($query) or die(mysql_error());
	
    while($row=mysql_fetch_array($result)){
		$count = $row['COUNT(votes.bill_id)'];
    }
return $count;
}

function sponsor_enacted($member_id, $period){
      $query= "SELECT COUNT(bill_id) FROM bills, period WHERE DATE(bills.enacted) BETWEEN DATE(period.startdate) AND DATE(period.enddate) AND (type='s' OR type='h') AND bills.sponsor ='$member_id' AND period.period_id='$period'";
	$result=mysql_query($query) or die(mysql_error());
	
    while($row=mysql_fetch_array($result)){
		$count = $row['COUNT(bill_id)'];
    }
return $count;
}

function cosponsor_enacted($member_id, $period){
    $query= "SELECT COUNT(bill_id) FROM cosponsors, period WHERE DATE(cosponsors.enacted) BETWEEN DATE(period.startdate) AND DATE(period.enddate) AND (type='s' OR type='h') AND cosponsors.cosponsor_id ='$member_id' AND period.period_id='$period'";
	$result=mysql_query($query) or die(mysql_error());
	
    while($row=mysql_fetch_array($result)){
		$count = $row['COUNT(bill_id)'];
    }
return $count;
}

function yea_vote($member_id, $period){
    $query= "SELECT COUNT(bill_id) FROM rolls, period WHERE DATE(rolls.datetime) BETWEEN DATE(period.startdate) AND DATE(period.enddate) AND (rolls.result='Passed' OR rolls.result='Bill Passed') AND rolls.vote='+' AND rolls.member_id='$member_id' AND period.period_id='$period'";
	$result=mysql_query($query) or die(mysql_error());
	
    while($row=mysql_fetch_array($result)){
		$count = $row['COUNT(bill_id)'];
    }
return $count;
}

function bipartisan($member_id, $period, $party){
	
    $query= "SELECT COUNT(rolls.bill_id) FROM rolls, bills, members, period WHERE DATE(rolls.datetime) BETWEEN DATE(period.startdate) AND DATE(period.enddate) AND rolls.bill_id=bills.bill_id AND members.member_id=bills.sponsor AND members.party<>'$party' AND (rolls.result='Passed' OR rolls.result='Bill Passed') AND rolls.vote='+' AND rolls.member_id='$member_id' AND period.period_id='$period'";
	$result=mysql_query($query) or die(mysql_error());
	
    while($row=mysql_fetch_array($result)){
		$count = $row['COUNT(rolls.bill_id)'];
    }
return $count;
}

function date_pass_house ($bill_id){
      $query= "SELECT datetime FROM votes WHERE bill_id= '$bill_id' AND `where`='h' AND (`type`='vote' OR `type`='vote2') AND (`state`='PASS_BACK:HOUSE' OR `state`='PASS_OVER:HOUSE' OR `state`='PASSED:BILL')";
	$result=mysql_query($query) or die(mysql_error());
	$num_rows = mysql_num_rows($result);
 
	if($num_rows > 0) {
	
    	while($row=mysql_fetch_array($result)){
			$house = new DateTime($row['datetime']);
			$house = date_format($house,'m/d/Y');;
		}
	}
		else{
			$house = '...';
		}
	
return $house;
}
function date_pass_senate ($bill_id){
      $query= "SELECT datetime FROM votes WHERE bill_id= '$bill_id' AND `where`='s' AND (`type`='vote' OR `type`='vote2') AND (`state`='PASS_BACK:SENATE' OR `state`='PASS_OVER:SENATE' OR `state`='PASSED:BILL')";
	$result=mysql_query($query) or die(mysql_error());
	$num_rows = mysql_num_rows($result);
 
	if($num_rows > 0) {
	
    	while($row=mysql_fetch_array($result)){
			$senate = new DateTime($row['datetime']);
			$senate = date_format($senate,'m/d/Y');;
		}
	}
		else{
			$senate = '...';
		}
	
return $senate;
}

//BILLS
function bill_data($bill_id){
	$bill_data=array();
	$bill_id = sanitize($bill_id);
	
	$func_num_args = func_num_args();
	
	$func_get_args = func_get_args();
	if($func_num_args > 1){
	unset($func_get_args[0]);}
	
	$fields ='`' . implode('`, `', $func_get_args) . '`';
	$bill_data = mysql_fetch_assoc(mysql_query("SELECT $fields FROM bills WHERE bill_id = '$bill_id'"));
	
	return $bill_data;
}


//LEAGUE
function is_commissioner($user_id, $league_id){
	$query="SELECT COUNT('league_id') FROM leagues WHERE commissioner_id='$user_id' AND league_id=$league_id";
	$result=mysql_query($query) or die(mysql_error());
	return (mysql_result($result,0) == 1 || has_access($user_id,1) === true) ? true : false;
}

function create_league($league_data){
   $league_data['league_password'] = md5($league_data['league_password']);
	array_walk($league_data, 'array_sanitize');
     $fields = '`' . implode('`, `', array_keys($league_data)) . '`';
     $data = '\'' . implode('\', \'', $league_data) . '\'';
	
	  mysql_query("INSERT INTO `leagues`($fields) VALUES ($data)") or die(mysql_error('Create League: '));
}

function league_points($league_id){
$fields = 'league_id,sponsor_introduce,cosponsor_introduce,sponsor_pass_house, cosponsor_pass_house, sponsor_pass_senate, cosponsor_pass_senate, sponsor_enacted, cosponsor_enacted, yea_vote, bipartisan';
$data = $league_id.',2,1,5,2,5,2,10,5,1,1';	  
      mysql_query("INSERT INTO `points`($fields) VALUES ($data)") or die(mysql_error());

}
function join_league($team_data){
	array_walk($team_data, 'array_sanitize');
     $fields = '`' . implode('`, `', array_keys($team_data)) . '`';
     $data = '\'' . implode('\', \'', $team_data) . '\'';
	
	  mysql_query("INSERT INTO `teams`($fields) VALUES ($data)") or die(mysql_error('Create League: '));
	
}
function league_data($league_id){
	$league_id = (int)$league_id;
	$league_data=array();
	
	$func_num_args = func_num_args();
	$func_get_args = func_get_args();
	if($func_num_args > 1){
	unset($func_get_args[0]);}
	
	$fields ='`' . implode('`, `', $func_get_args) . '`';
	$league_data = mysql_fetch_assoc(mysql_query("SELECT $fields FROM leagues WHERE league_id = '$league_id'"));
	
	return $league_data;	
}

function league_exists($league_name){
	$league_name= sanitize($league_name);
	$query="SELECT COUNT('league_id') FROM leagues WHERE league_name='$league_name'";
	$result=mysql_query($query) or die(mysql_error());
	return (mysql_result($result,0) == 1) ? true : false;
}

function league_password($league_id, $league_password){
		$league_password = md5($league_password);
        $query="SELECT COUNT('league_id') FROM leagues WHERE league_id='$league_id' AND league_password = '$league_password'";
	$result=mysql_query($query) or die(mysql_error());
	return (mysql_result($result,0) == 1) ? true : false;
}

function update_points($points_data){
	    $update = array();
	array_walk($points_data, 'array_sanitize');
     $fields = '`' . implode('`, `', array_keys($points_data)) . '`';
     $data = '\'' . implode('\', \'', $points_data) . '\'';
	foreach($points_data as $fields_update=>$data_update){
		$update[] = '`' . $fields_update . '` = \'' . $data_update . '\'';
	}
      mysql_query("INSERT INTO `points`($fields) VALUES ($data) ON DUPLICATE KEY UPDATE " . implode(", ", $update)) or die(mysql_error());
}

function team_count($league_id){
	$query="SELECT COUNT('team_id') FROM teams WHERE league_id='$league_id'";
	$result=mysql_query($query) or die(mysql_error());
	return (mysql_result($result,0));
}
//TEAMS
function has_team_in_league($user_id, $league_id){
	$query="SELECT COUNT('team_id') FROM teams WHERE league_id='$league_id' AND user_id = '$user_id'";
	$result=mysql_query($query) or die(mysql_error());
	return (mysql_result($result,0) == 1) ? true : false;
}

function max_teams_in_league($league_id){
	$query="SELECT COUNT('team_id') FROM teams WHERE league_id='$league_id'";
	$result=mysql_query($query) or die(mysql_error());
	return (mysql_result($result,0) >= 16) ? true : false;
}

function team_exists($team_name, $league_id){
	$team_name = sanitize($team_name);
	$query="SELECT COUNT('team_id') FROM teams WHERE league_id='$league_id' and team_name='$team_name'";
	$result=mysql_query($query) or die(mysql_error());
	return (mysql_result($result,0) == 1) ? true : false;
}



function team_data($team_id){
	$team_id = (int)$team_id;
	$team_data=array();
	
	$func_num_args = func_num_args();
	$func_get_args = func_get_args();
	if($func_num_args > 1){
	unset($func_get_args[0]);}
	
	$fields ='`' . implode('`, `', $func_get_args) . '`';
	$team_data = mysql_fetch_assoc(mysql_query("SELECT $fields FROM teams WHERE team_id = '$team_id'"));
	
	return $team_data;	
}

function draft_team($roster_data){
   $update = array();
	array_walk($roster_data, 'array_sanitize');
     $fields = '`' . implode('`, `', array_keys($roster_data)) . '`';
     $data = '\'' . implode('\', \'', $roster_data) . '\'';
	foreach($roster_data as $fields_update=>$data_update){
		$update[] = '`' . $fields_update . '` = \'' . $data_update . '\'';
	}
	$team_id = $roster_data['team_id'];
	  mysql_query("INSERT INTO `rosters`($fields) VALUES ($data) ON DUPLICATE KEY UPDATE " . implode(", ", $update)) or die(mysql_error('Roster: '));
	
}
function open_draft($open_data){
   $update = array();
	array_walk($open_data, 'array_sanitize');
     $fields = '`' . implode('`, `', array_keys($open_data)) . '`';
     $data = '\'' . implode('\', \'', $open_data) . '\'';
	foreach($open_data as $fields_update=>$data_update){
		$update[] = '`' . $fields_update . '` = \'' . $data_update . '\'';
	}
	  mysql_query("INSERT INTO `open`($fields) VALUES ($data) ON DUPLICATE KEY UPDATE " . implode(", ", $update)) or die(mysql_error('Open Draft: '));
	
}
function update_roster($roster_data){
   $update = array();
	array_walk($roster_data, 'array_sanitize');
     $fields = '`' . implode('`, `', array_keys($roster_data)) . '`';
     $data = '\'' . implode('\', \'', $roster_data) . '\'';
	foreach($roster_data as $fields_update=>$data_update){
		$update[] = '`' . $fields_update . '` = \'' . $data_update . '\'';
	}
	$team_id = $roster_data['team_id'];
	  mysql_query("INSERT INTO `rosters`($fields) VALUES ($data) ON DUPLICATE KEY UPDATE " . implode(", ", $update)) or die(mysql_error('Roster: '));
	
}
function member_in_league($member_id, $league_id, $team_id){
	$query = "SELECT COUNT('rosters.member_id') FROM rosters, teams, leagues WHERE rosters.period_id = 0 AND rosters.member_id = '$member_id' AND rosters.team_id <> '$team_id' AND rosters.team_id=teams.team_id and leagues.league_id=teams.league_id and leagues.league_id='$league_id'";
	$result=mysql_query($query) or die(mysql_error());
	return (mysql_result($result,0) == 1) ? true : false;
}

function ownteam($team_id, $user_id){
       $query = "SELECT COUNT('team_id') FROM teams WHERE  team_id='$team_id' AND user_id='$user_id'";
       $result=mysql_query($query) or die(mysql_error());
	return (mysql_result($result,0) == 1) ? true : false;
}
//LEADERBOARD
function lsponsor_count($member_id){
      $query= "SELECT COUNT(bill_id) FROM bills, period WHERE DATE(bills.introduced) BETWEEN DATE(period.startdate) AND DATE(period.enddate) AND type=('s'|'h') AND bills.sponsor ='$member_id'";
	$result=mysql_query($query) or die(mysql_error());
	
    while($row=mysql_fetch_array($result)){
		$count = $row['COUNT(bill_id)'];
}
return $count;
}

function lcosponsor_count($member_id){
    $query= "SELECT COUNT(bill_id) FROM cosponsors, period WHERE DATE(cosponsors.joined) BETWEEN DATE(period.startdate) AND DATE(period.enddate) AND type=('s'|'h') AND cosponsors.cosponsor_id ='$member_id'";
	$result=mysql_query($query) or die(mysql_error());
	
    while($row=mysql_fetch_array($result)){
		$count = $row['COUNT(bill_id)'];
}
return $count;
}

function lpass_house_sponsor ($member_id){
      $query= "SELECT COUNT(1) FROM votes, bills, period WHERE DATE(votes.datetime) BETWEEN DATE(period.startdate) AND DATE(period.enddate) AND bills.sponsor ='$member_id' AND votes.bill_id=bills.bill_id AND votes.where='h' AND votes.state=('PASS_OVER:HOUSE'|'PASSED:BILL')";
	$result=mysql_query($query) or die(mysql_error());
	
    while($row=mysql_fetch_array($result)){
		$count = $row['COUNT(1)'];
}
return $count;
}

function lpass_house_cosponsor ($member_id){
      $query= "SELECT COUNT(votes.bill_id) FROM votes, cosponsors, period WHERE DATE(votes.datetime) BETWEEN DATE(period.startdate) AND DATE(period.enddate) AND cosponsors.cosponsor_id ='$member_id' AND votes.bill_id=cosponsors.bill_id AND votes.where='h' AND votes.state=('PASS_OVER:HOUSE'|'PASSED:BILL')";
	$result=mysql_query($query) or die(mysql_error());
	
    while($row=mysql_fetch_array($result)){
		$count = $row['COUNT(votes.bill_id)'];
    }
return $count;
}

function lpass_senate_sponsor ($member_id){
      $query= "SELECT COUNT(votes.bill_id) FROM votes, bills, period WHERE DATE(votes.datetime) BETWEEN DATE(period.startdate) AND DATE(period.enddate) AND bills.sponsor ='$member_id' AND votes.bill_id=bills.bill_id AND votes.where='s' AND votes.state=('PASS_OVER:SENATE'|'PASSED:BILL')";
	$result=mysql_query($query) or die(mysql_error());
	
    while($row=mysql_fetch_array($result)){
		$count = $row['COUNT(votes.bill_id)'];
    }
return $count;
}

function lpass_senate_cosponsor ($member_id){
      $query= "SELECT COUNT(votes.bill_id) FROM votes, cosponsors, period WHERE DATE(votes.datetime) BETWEEN DATE(period.startdate) AND DATE(period.enddate) AND cosponsors.cosponsor_id ='$member_id' AND votes.bill_id=cosponsors.bill_id AND votes.where='s' AND votes.state=('PASS_OVER:SENATE'|'PASSED:BILL')";
	$result=mysql_query($query) or die(mysql_error());
	
    while($row=mysql_fetch_array($result)){
		$count = $row['COUNT(votes.bill_id)'];
    }
return $count;
}

function lsponsor_enacted($member_id){
      $query= "SELECT COUNT(bill_id) FROM bills, period WHERE DATE(bills.enacted) BETWEEN DATE(period.startdate) AND DATE(period.enddate) AND type=('s'|'h') AND bills.sponsor ='$member_id'";
	$result=mysql_query($query) or die(mysql_error());
	
    while($row=mysql_fetch_array($result)){
		$count = $row['COUNT(bill_id)'];
    }
return $count;
}

function lcosponsor_enacted($member_id){
    $query= "SELECT COUNT(bill_id) FROM cosponsors, period WHERE DATE(cosponsors.enacted) BETWEEN DATE(period.startdate) AND DATE(period.enddate) AND type=('s'|'h') AND cosponsors.cosponsor_id ='$member_id'";
	$result=mysql_query($query) or die(mysql_error());
	
    while($row=mysql_fetch_array($result)){
		$count = $row['COUNT(bill_id)'];
    }
return $count;
}

function lyea_vote($member_id){
    $query= "SELECT COUNT(bill_id) FROM rolls, period WHERE DATE(rolls.datetime) BETWEEN DATE(period.startdate) AND DATE(period.enddate) AND rolls.result='Passed' AND rolls.vote='+' AND rolls.member_id='$member_id'";
	$result=mysql_query($query) or die(mysql_error());
	
    while($row=mysql_fetch_array($result)){
		$count = $row['COUNT(bill_id)'];
    }
return $count;
}

function lbipartisan($member_id, $party){
	
    $query= "SELECT COUNT(rolls.bill_id) FROM rolls, bills, members, period WHERE DATE(rolls.datetime) BETWEEN DATE(period.startdate) AND DATE(period.enddate) AND rolls.bill_id=bills.bill_id AND members.member_id=bills.sponsor AND members.party<>'$party' AND rolls.result='Passed' AND rolls.vote='+' AND rolls.member_id='$member_id'";
	$result=mysql_query($query) or die(mysql_error());
	
    while($row=mysql_fetch_array($result)){
		$count = $row['COUNT(rolls.bill_id)'];
    }
return $count;
}
?>