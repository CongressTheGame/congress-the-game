<?php include 'core/init.php';
protect_page();

if (isset($_GET['disable']) && empty($_GET['disable'])){	
	$league_id= $_SESSION['league_id'];
	if(is_commissioner($session_user_id, $league_id) === true){

	  mysql_query("UPDATE leagues SET disable='1' WHERE league_id='$league_id'");
	  	unset($_SESSION['league_id']);

	  	header('Location: league.php');

}else{ 
	header('Location: league.php');
	}
	
}
//CREATE
if (isset($_GET['create']) && empty($_GET['create'])){	
   
   if (empty($_POST) === false){        
        $required_fields = array('league_name','league_password','team_name');
	foreach($_POST as $key=>$value){
		if(empty($value) && in_array($key,$required_fields) === true){
			$errors[]='Fields marked with an asterisk are required.';
			break 1;
		}
	}
 
	if (empty($errors) === true){
		if(league_exists($_POST['league_name']) === true){
			$errors[]='Sorry the league \'' . $_POST['league_name'] . '\' already exists.';
		}
                if(team_exists($_POST['team_name'], $_POST['league_name']) === true){
			$errors[]='Sorry the team \'' . $_POST['team_name'] . '\' already exists in \'' . $_POST['league_name'] . '\'.';
		}
		if(preg_match("/\W/",$_POST['league_name']) == true){
			$errors[]='Your league name can only contain letters, numbers, or underscores.';
		}
                if(preg_match("/\W/",$_POST['team_name']) == true){
			$errors[]='Your team name can only contain letters, numbers, or underscores.';
		}
		if (strlen($_POST['league_password']) < 6) {
			$errors[]='Password must be at least 6 characters long';
		}
	}
   }
   if (empty($_POST) === false && empty($errors) === true) {
                $league_password = $_POST['league_password'];
                $league_name = $_POST['league_name'];
		$league_data = array(
			'league_name' 		=> $league_name,
			'league_password' 	=> $league_password,
			'commissioner_id'	=> $session_user_id,
			'created'			=> date('Y-m-d',time()),
			);
                create_league($league_data);
                $query= mysql_query("SELECT league_id FROM leagues WHERE league_name = '$league_name'");
                $league_id = mysql_result($query,0);
                league_points($league_id);
                $team_data = array(
                        'league_id' => $league_id,
                        'user_id'   => $session_user_id,
                        'team_name'  => $_POST['team_name'],
                        );
                join_league($team_data,$league_password);
		header('Location: league.php?success');
		exit();
	}
}
//JOIN///
if (isset($_GET['join'])){
       $league_id = $_GET['join'];
	   $league_data = league_data($league_id, 'league_name');
      
       if(league_exists($league_data['league_name']) === true) {
} else {
       header ('Location:league.php?create');
       }

if (empty($_POST) === false){        
        $required_fields = array('league_name','league_password','team_name');
	foreach($_POST as $key=>$value){
		if(empty($value) && in_array($key,$required_fields) === true){
			$errors[]='Fields marked with an asterisk are required.';
			break 1;
		}
	}
 
 
	if (empty($errors) === true){
		if(has_team_in_league($session_user_id,$league_id) === true){
			$errors[]='You already have a team in this league.';
		}
		if(max_teams_in_league($league_id) === true){
			$errors[]='This league is full.';
		}
                if(team_exists($_POST['team_name'], $league_id) === true){
			$errors[]='Sorry the team \'' . $_POST['team_name'] . '\' already exists in \'' . $_POST['league_name'] . '\'.';
		}
		if(preg_match("/\W/",$_POST['team_name']) == true){
			$errors[]='Your team name can only contain letters, numbers, or underscores.';
		}
 
	}
        }
	if (empty($_POST) === false && empty($errors) === true) {
                $league_password= $_POST['league_password'];
                $team_data = array(
                        'league_id' => $league_id,
                        'user_id'   => $session_user_id,
                        'team_name'  => $_POST['team_name'],
                        );
                if (league_password($league_id,$league_password) === true) {
				join_league($team_data);
		header('Location: team.php');
		exit();
                } else {
                $errors[] = 'You entered the wrong password for the league.';
                echo output_errors($errors);
                }
        }
        }       
//LEAGUE//	
if(isset($_POST['league_id']) === true) {
	unset($_SESSION['league_id']);
	$_SESSION['league_id'] = $_POST['league_id'];
	header ('Location:league.php');

}

if(empty($_SESSION['league_id'])!== true){
	$league_id= $_SESSION['league_id'];
 	$league_data = league_data($league_id, 'league_name');
 	$leaguename=$league_data['league_name'];
	$user_id = $user_data['user_id'];
	$teamname=mysql_result(mysql_query("SELECT team_name FROM teams WHERE league_id = '$league_id' AND user_id = '$user_id'"),0);
	$user=user_data($user_id,'username');
	$username = $user['username'];
}

//COMMENTS///
if(isset($_POST['delete'])) { 
 $id = $_POST['id'];
  mysql_query("DELETE FROM comments where id = '$id'");
	header ('Location: league.php');

}

if(isset($_POST['submit'])) { 
  if(empty($_POST['comment'])){  
  $errors[]='Must enter a comment to post.';
  } 
}
if(isset($_POST['submit']) && empty($errors)){
//add comment 
	$q = "INSERT INTO `comments` (league_id, date, time, teamname, username, ip, comment)  
VALUES ('".$league_id."', '".$_POST['date']."','".$_POST['time']."', '".$teamname."', '".$username."',  
'".$_SERVER['REMOTE_ADDR']."', '".addslashes(htmlspecialchars(nl2br($_POST['comment'])))."')"; 
	$q2 = mysql_query($q) or die('Comments insert: '.mysql_error());
	header ('Location: league.php');
	exit();
	}
	include 'includes/overall/header.php';
	
		if(empty($errors) === false){
			echo output_errors($errors);
		}			
if (isset($_GET['create']) && empty($_GET['create'])){	
?>
<h2>Create a League</h2>

   <form action="" id="register" method="post">
      <ul>
         <li>League Name:* <input type="text" name="league_name" value="<?php echo $_POST['league_name']; ?>" id="register"></li>
         <li>League Password:* <input type="password" name="league_password" value="<?php echo $_POST['league_password']; ?>" id="register"></li>
         <li>Create Team Name:* <input type="text" name="team_name" value="<?php echo $_POST['team_name']; ?>" id="register"></li>
         <li><input type="submit" value="Create League" id="register"></li>
      </ul>
   </form>
 
<h2>Join a League</h2>
 
<?php
      $result = mysql_query("SELECT leagues.league_id, leagues.league_name, leagues.commissioner_id, users.username FROM `leagues`, `users` WHERE users.user_id = leagues.commissioner_id and leagues.disable = 0") or die('League Join: '.mysql_error());
      ?>
   <table class='results'>
   	<thead>
   		<tr>
   			<th><h2><a href='open.php'>Open League</a></h2></th>
   		</tr>
   	</thead>
   </table>
   <br>
   <table class='results'>
   	<thead>
   	<tr>
  	 <th colspan='4'><h2>Private Leagues</h2></th>
  	</tr>
  	</thead>
   	<thead>
   	<tr>
   	  <th style='width:45%'>League</th>
   	  <th style='width:30%'>Commissioner</th>
   	  <th style='width:15%'># of Teams</th>
   	  <th style='width:10%'>Join</th>
   	</tr>
   	</thead>
   	</table>
   	
   	
   	 <input type="text" id="leagueSearch" onkeyup="searchLeague()" placeholder="Search by league name..." style="width:100%;">

   		<script>
			function searchLeague() {
  			// Declare variables 
 			 var input, filter, table, tr, td, i;
 			 input = document.getElementById("leagueSearch");
  			filter = input.value.toUpperCase();
  			table = document.getElementById("leaguesList");
  			tr = table.getElementsByTagName("tr");

  			// Loop through all table rows, and hide those who don't match the search query
  			for (i = 0; i < tr.length; i++) {
    			td = tr[i].getElementsByTagName("td")[0];
    			if (td) {
      			if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
        		tr[i].style.display = "";
      			} else {
        		tr[i].style.display = "none";
      			}
    			} 
  			}
			}
		</script>
   <div style="height: 500px; overflow: auto;">
   	<table id="leaguesList" class='results'>
   	<tbody>
   <?php
   while($row = mysql_fetch_array($result)) {
	echo "<tr><td style='width:45%'>"; 
	echo $row['league_name'];
        echo "</td><td style='width:30%'>"; 
	echo $row['username'];
	echo "</td><td style='width:15%'>"; 
	echo team_count($row['league_id'])."/16";
	echo "</td><td style='width:10%'>"; 
	echo "<a href=\"league.php?join=" . $row['league_id'] . "\">Join</a>";
	
	echo "</td></tr>"; 
   }
   echo "</tbody></table></div>";    
 
}else {
 
//Success
if (isset($_GET['success']) && empty($_GET['success'])){
    include 'includes/newleague.php'; 
   } else {
 
//Joining League

if (isset($_GET['join'])){
if(empty($errors) === false){
			echo output_errors($errors);
		}
?>
   <form action="" method="post" id="register">
      <ul>
         <li>League Name:* <input type="text" readonly name="league_name" value="<?php echo $league_data['league_name']; ?>" id="register"></li>
         <li>League Password:* <input type="password" name="league_password" value="<?php echo $_POST['league_password']; ?>" id="register"></li>
         <li>Create Team Name:* <input type="text" name="team_name" value="<?php echo $_POST['team_name']; ?>" id="register"></li>
         <li><input type="submit" value="Join League" id="register"></li>
         </ul>
   </form>
<?php
}

//To check for leagues and set current league
$user_id = $user_data['user_id'];
if (has_access($user_id,1) === true){
  $result = mysql_query("SELECT distinct leagues.league_id, leagues.league_name FROM `leagues`, `teams` WHERE leagues.league_id = teams.league_id AND leagues.disable = 0") or die('Leagues: '.mysql_error());
} else {
  $result = mysql_query("SELECT leagues.league_id, leagues.league_name FROM `leagues`, `teams` WHERE teams.user_id = '$user_id' AND leagues.league_id = teams.league_id AND leagues.disable = 0") or die('Leagues: '.mysql_error());
  }
  $num_rows = mysql_num_rows($result);
 
if($num_rows <> 0) {
 
   while ($row=mysql_fetch_array($result)) { 
 
    $league_id=$row["league_id"]; 
    $league_name=$row["league_name"]; 
    $leagues.="<OPTION VALUE=\"$league_id\">".$league_name; 
   }
?>
   <center>
   <form action="" method="post">
   <ul>
      <li>
         <h3>My Leagues:
  
         <SELECT NAME=league_id> 
         <OPTION VALUE=0>Choose League...
         <?=$leagues?> </OPTION>
         </SELECT>
      
         <input type="submit" value="View League"><button class="open" formaction="open.php">Open League</button>

      </h3></li>
   </ul>
   </form>
  
   </center>
<?php 
if(empty($_SESSION['league_id'])!== true){
	$league_id= $_SESSION['league_id'];
 	$league_data = league_data($league_id, 'league_name');
 	$leaguename=$league_data['league_name'];
	$user_id = $user_data['user_id'];
	$teamname=mysql_result(mysql_query("SELECT team_name FROM teams WHERE league_id = '$league_id' AND user_id = '$user_id'"),0);
	$user=user_data($user_id,'username');
	$username = $user['username'];

echo "<h1><strong><center>".$leaguename."</center></strong></h1><br>";
 

include 'includes/widgets/points.php';
?>
<br>
<div style="width:95%;">
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
<br>
</div>
<?php

include 'includes/widgets/leaderboard.php';
echo '<br>';
//////COMMENTS///////
?>
<table class="results">
	<thead>
    	<tr>
        	<th colspan="3">League Message Board</th>
        </tr>
<?php  
//query comments for this page of this article 
$inf = "SELECT * FROM `comments` WHERE league_id = '$league_id' ORDER BY time ASC"; 
 $info = mysql_query($inf) or die(mysql_error());  
   $info_rows = mysql_num_rows($info); 
if($info_rows > 0) { 
 while($info2 = mysql_fetch_object($info)) {     
echo '</thead><tbody><tr>';    
echo '<td><form name="delete" action="" method="post"><a href="/'.$info2->username.'">'.stripslashes($info2->username).'</a></td><td><div align="right">';
echo date('h:i:s a', $info2->time).' on '.$info2->date;
if(is_commissioner($session_user_id, $league_id) === true){
     echo ' - <input type="hidden" name="id" value="'.$info2->id.'"><input type="submit" name="delete" class="delete_comment" title="Delete"  value=" X " >';
}
echo '</div></form></td>'; 
echo '</tr><tr>'; 
echo '<td colspan="3"> '.stripslashes($info2->comment).' </td>'; 
echo '</tr>'; 
  }//end while 
echo '</tbody>'; 
} else { echo '<tr><td colspan="3">No comments for this league. Feel free to be the first!</td></tr></thead>'; 
}

?>
<tfoot>  
<form name="comments" action="" method="post"> 
<input type="hidden" name="date" value="<? echo(date("F j, Y.")); ?>"> 
<input type="hidden" name="time" value="<? echo(time()); ?>"> 

    <tr> 
      <th colspan="3"><textarea name="comment" rows="3" style="width:100%; height:50px;" wrap="VIRTUAL"></textarea></th> 
    </tr> 
    <tr>  
      <th colspan="3" style="padding:5px 5px 5px 5px;"><input type="reset" value="Reset Fields">      
        <input type="submit" name="submit" value="Add Comment"></th> 
    </tr> 
    
</form> 
</tfoot>
</table> 
<?php
echo output_errors($errors);
?>
<br />        
<?php  
//Commissioner
if(is_commissioner($session_user_id, $league_id) === true){?>
<div id="results" >
<ul>
<h2>Commissioner Options</h2>
<li class="double">
<form action="draft.php">
<input value="Draft" id="register" type="submit">
</form>
</li>
<li class="double">
<form action="points.php">
<input value="Set Points Values" id="register" type="submit">
</form>
</li>
<a href="league.php?disable" style="color: red;">Disable League</a>

</ul>
</div>

<?php
}
}
}
}
}


 
?>
<br />
<center><a href="league.php?create" >Create or Join a New League</a></center>
 
 


<?php 

include 'includes/overall/footer.php';?>