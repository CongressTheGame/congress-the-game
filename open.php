<?php include 'core/init.php';
protect_page();

if(isset($_POST['period_id']) === true) {
	unset($_SESSION['period_id']);
	$_SESSION['period_id'] = $_POST['period_id'];
	header('Location: open.php?');

}

	$period_id = $_SESSION['period_id'];
	$user_id = $_SESSION['user_id'];
	
if(isset($_GET['id']) === true) {
	$id = $_GET['id'];
}

if(isset($_POST['member_id']) === true) {
	$required_fields = array('member_id');
	foreach($_POST as $key=>$value){
		if(in_array(0,$value) === true){
			$errors[]='Must fill out roster completely.';
			break 1;
		}
		$value_unique  = array_unique($value);
		if($value !== $value_unique){
			$errors[]='Cannot Duplicate Members.';
			break 1;	
		}
	}
	
}
if (isset($_POST['member_id']) === true && empty($errors) === true) {
		$open_data=array();
	    mysql_query("DELETE FROM `open` WHERE user_id='$user_id' AND period_id='$period_id'") or die(mysql_error('Delete: '));
	
	foreach($_POST['member_id'] as $key=>$value){
			$roster_data=array(
				'user_id'		=> $user_id,
				'period_id'		=> $period_id,
				'member_id'		=> $value,
			);
	open_draft($roster_data);
	}
		header('Location: open.php?success');
		exit();
}

include 'includes/overall/header.php';
?>
<h1>Open League</h1>
<div id="openrules">
<p>Pick a team to play for the week and compete against all of the players on Congress: The Game.  Pick 2 senators and 5 representatives for the coming week. Your team needs to be picked before midnight on the saturday preceding the week. The period with the asterisk (*) is the current period teams can be drafted in.  Select another period to view your team''s performance and the leaderboard for that week.</p>
</div>

<?php

//DISPLAY PERIOD INFORMATION
	$period_id = $_SESSION['period_id'];
	if (empty($period_id) !== true) {
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

    <h1 align="center">Week of <?php echo $start. " - " .$end;if($current == 1) {echo "*";}?></h1>

<?php    
}
//SELECT PERIOD DROPDOWN
	$q="SELECT period_id, startdate, enddate, current FROM period WHERE period.current <> 2 ORDER BY period_id DESC";
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
	$period_id = $_SESSION['period_id'];
	
	?>
<form action="" method="post"><h2>
<h4 align="center"> View week of...
<SELECT NAME=period_id> 

    <OPTION VALUE=0>Choose period...
    <?=$periods?> </OPTION>
    </SELECT>
    <input type="submit" value="Go">
</form>
</h4>

<?php 
	if (empty($period_id) !== true) {

$user_id=$_SESSION['user_id'];


include 'includes/open/openteam.php';
?>
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
</div>
<br>
<?php
include 'includes/open/openleaderboard.php';
}

include 'includes/overall/footer.php';?>