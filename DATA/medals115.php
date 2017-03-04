<?php
include 'connect.php';
require '../public_html/core/functions/data.php';

function medals(){
$date=date('Y-m-d',time('Y-m-d'));
$previous=date('Y-m-d',strtotime("-7 days",time('Y-m-d')));
$r=mysql_query("SELECT period_id FROM period WHERE startdate='$previous'");
$periods=mysql_fetch_array($r);
$period_id=$periods['period_id'];

$query="SELECT * FROM points WHERE league_id=1";
$result=mysql_query($query);
$points=mysql_fetch_array($result);
	$player="SELECT DISTINCT open.user_id FROM users, `open` WHERE open.user_id=users.user_id AND open.period_id='$period_id'";
    	$player=mysql_query($player) or die(mysql_error('USERS: ')); 
			
	mysql_query("CREATE TEMPORARY TABLE OpenLeaderBoard (`user_id` int(15) NOT NULL,`pts` int(15))") or die(mysql_error('CREATE: '));

while ($player_row=mysql_fetch_array($player)) { 
		$player_id= $player_row['user_id'];
			$sen="SELECT members.member_id, party FROM `open`,`members` WHERE open.user_id='$player_id' AND members.member_id=open.member_id AND `period_id`='$period_id'"; 
    		        $sen=mysql_query($sen); 
                        $num_rows = mysql_num_rows($sen);
	                $period = $period_id;
                       if($num_rows !== 0) {
			   while ($sen_row=mysql_fetch_array($sen)) {
    			        $senator_id=$sen_row['member_id']; 
				$party = $sen_row['party'];	
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
						$players[$player_id][]=$member_points;
                           } //END of each Member
			
                           if (empty($players[$player_id]) !== true) {
		               $total[$player_id]=array_sum($players[$player_id]);	
		           } else {
		               $total[$player_id]=0;
                           }
                      } //END of team exists in period	             
           
		$pid=$player_row['user_id'];
                if (empty($total[$player_id]) !== true) {
		$pts = ($total[$player_id]);
		} else {
		$pts='0';
		}

/////This query puts the scores into the leaderboard./////
mysql_query("INSERT INTO OpenLeaderBoard(`user_id`, `pts`) VALUES('$pid', '$pts')") or die(mysql_error('INSERT: '));

}  //END of each team in league

/////This query pulls information from leaderboard/////
$lead = "SELECT `user_id`, `pts` FROM OpenLeaderBoard WHERE `pts` > 0 ORDER BY `pts` DESC limit 1 ";
$lead = mysql_query($lead) or die(mysql_error('LEADERBOARD: '));
$num_rows = mysql_num_rows($lead);
if($num_rows === 0) {

}else{
while ($leader=mysql_fetch_array($lead)) {
                $user_id=$leader['user_id'];
	        mysql_query("INSERT INTO `medals`(user_id,type,period_id,league_id) VALUES('$user_id','o','$period_id',1)");
         }   //END of display leaderboard
   }
   }
   
   medals();
	?>