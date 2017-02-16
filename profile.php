<?php 
include 'core/init.php';
protect_page();
include 'includes/overall/header.php';

if(isset($_GET['username']) === true && empty($_GET['username']) === false) {
        $username 		= $_GET['username'];
 
   if(user_exists($username) === true){
	$user_id 		= user_id_from_username($username);
	$profile_data 	= user_data($user_id, 'first_name', 'last_name', 'email', 'profile');
	
	if(isset($_FILES['profile']) === true) {
            if(empty($_FILES['profile']['name']) === true){
                   $errors[] = 'Please choose a file...';
            }  else {
                   $allowed = array('jpg', 'jpeg', 'gif', 'png');
                   $file_name = $_FILES['profile']['name'];
                   $file_extn = strtolower(end(explode('.', $file_name)));
                   $file_temp = $_FILES['profile']['tmp_name'];
                   $maxsize    = 524288;
 		if(($_FILES['profile']['size'] >= $maxsize) || ($_FILES['profile']['size'] == 0)) {
 		   $errors[] = 'File too large. File must be less than 512 kilobytes.';
 		   } else {
 		   if(in_array($file_extn, $allowed) === true){
                   change_profile_image($session_user_id, $file_temp, $file_extn);
                   header ('Location: '.$username);
               } else{
                   $errors[] = 'Incorrect file type. Please use: .' . implode(', .', $allowed);
               }}
           }
        }
	

?>	
<!--HEADER-->	
<h1><?php echo $profile_data['first_name']."'s Profile</h1>";?>
    <div class="profile">
    <center>
    <?php if (empty($profile_data['profile']) === false) {
               		echo '<img src="', $profile_data['profile'], '"alt="', $profile_data['first_name'], '\'s Profile Image">';
		} else {
		       	echo '<img src="images/default.jpg" alt="', $profile_data['first_name'], '\'s Profile Image">';
		}
    ?>
    </center>
<?php
      if ($user_data['user_id'] === $user_id){          
?>
         <form action="" method="post" enctype="multipart/form-data"> 
            <input type="file" name="profile"><input type="submit" value="Change Profile Picture">
         </form>
            <p><?php if(empty($errors) === false){echo output_errors($errors);}?></p>

         
<?php 
      } ?>
</div>
<h2>Medals</h2>
<?php
$result=mysql_query("select type, startdate, enddate from medals, period where period.period_id=medals.period_id AND medals.user_id='$user_id' ORDER BY startdate DESC");
$num_rows=mysql_num_rows($result);
if($num_rows > 0) {
echo '<table width="100%"><tr>';
        $c=0;
	while ($row=mysql_fetch_array($result)) { 
        $c++; 
	$start= new DateTime($row['startdate']);
	$start= date_format($start,'m/d/Y');
	$end= new DateTime($row['enddate']);
	$end= date_format($end,'m/d/Y');
	if ($row['type']==='o'){
	$league='Open League';
	} else {
	$league='Private League';
	}
echo '<td><div align="center"><img src="images/medal.png" width="50px" height="70px"><br><p>'.$league.'<br>'.$start.'-'.$end.'</div></td>';
if($c % 5 == 0) { echo '</tr><tr>';
}
}
echo '</tr></table>';
} else {
echo 'No Medals Yet!';
}
?><p>
<h2>Teams</h2>
<p>
<?php
$result = mysql_query("SELECT teams.team_id, teams.team_name,leagues.league_name FROM `leagues`,`teams` WHERE teams.user_id = '$user_id' and leagues.league_id=teams.league_id") or die(mysql_error());
$num_rows = mysql_num_rows($result);
if($num_rows > 0) {
	while ($row=mysql_fetch_array($result)) { 
    	$team_id=$row["team_id"];
    	$league_name=$row["league_name"]; 
    	$team_name=$row["team_name"]; 
    	echo "&bull;".$team_name.' ('.$league_name.')<br>'; 
	}
	echo '</p>';
   } ?>
<br>
<?php 
if ($user_data['user_id'] === $user_id){ 
?>
<div id="results" >
<ul>
<li class="double">
<form action="settings.php">
<input value="Change Settings" id="register" type="submit">
</form>
</li>
<li class="double">
<form action="changepassword.php">
<input value="Change Password" id="register" type="submit">
</form>
</li>
</ul>
</div>
<?php
   }}else{header("Location: 404.php");}
	
}else {header("Location: index.php");}
include 'includes/overall/footer.php';?>