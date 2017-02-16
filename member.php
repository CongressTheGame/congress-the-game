<?php 
	include 'core/init.php';
	
if(isset($_GET['member_id']) === true && empty($_GET['member_id']) === false) {
        $member_id 		= (int)$_GET['member_id'];
   if(member_exists($member_id) !== true){
   	 header('Location: member.php');
}}
	include 'includes/overall/header.php';

//MEMBER DROPDOWN 
	$query="SELECT `member_id` FROM `members` WHERE `current`=1 ORDER BY `lastname`"; 
    $result=mysql_query($query); 
	$options=""; 
 
while ($row=mysql_fetch_array($result)) { 
    $member_id=$row['member_id'];
	$member_data 	= member_data($member_id, 'type', 'district', 'middlename', 'lastname','firstname','title','state');
    if (trim($member_data['type']) === "rep"){
    	$district = " - " . $member_data['district'];
    } else{
    	$district ="";
    }
    if (trim($member_data['middlename']) <> "") {
    	$middle = $member_data['middlename'] . " ";
    } else{
    	$middle ="";
    }
    	$name= $member_data["lastname"] . ", " . $member_data['firstname'] . " " . $middle . "(" . $member_data['title'] . ", " . $member_data['state'] . $district . ")"; 
    	$member.="<OPTION VALUE=\"$member_id\">".$name; 
}
?> 
<form action="" method="get">
<ul>
   <li>
      <h3>Select a Member of Congress:</h3>
   </li>
   <li>
      <SELECT NAME=member_id> 
      <OPTION VALUE=0>Choose a Member...
      <?=$member?> </OPTION>
      </SELECT>
 
      <input type="submit" value="Select Member">
   </li>
</ul>
</form>
 
<?php 

if(isset($_GET['member_id']) === true && empty($_GET['member_id']) === false) {
        $member_id 		= (int)$_GET['member_id'];
   if(member_exists($member_id) === true){
	$member_data 	= member_data($member_id, 'firstname','middlename','lastname','title','party','state','district','startdate','enddate');
	
$termstart= new DateTime($member_data['startdate']);
 $termstart= date_format($termstart,'m/d/y');
 $termend= new DateTime($member_data['enddate']);
 $termend= date_format($termend,'m/d/y');	
	if(empty($member_data['district']) === true){
		$state ="<h3>State: ".$member_data['state']."</h3>";
	}else{
		$state ="<h3>State-District: ".$member_data['state']." - ".$member_data['district']."</h3>";
	}
	?>
    
<div id="results">
	<div id="member">
    	<div id="picture">
			<img src="http://www.govtrack.us/data/photos/<?php echo $member_id;?>-200px.jpeg" width="200" height="244" alt="http://www.gpoaccess.gov/pictorial/ Congressional Pictorial Directory">
        </div>
		<div id='info'>
        	<h2><?php echo $member_data['title']." ".$member_data['firstname']." ".$member_data['middlename']." ".$member_data['lastname'];?></h2>
            <h3>Party: <?php echo $member_data['party'];?></h3>
            <?php echo $state;?>
            <h3>Term: <?php echo $termstart." - ".$termend;?></h3>
            
        </div>
    </div>
            
  <?php           
   } 
   
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
<h2>Performance</h2>
<table class="results">
<thead>
<tr> <th>Period</th>  <th STYLE="word-wrap: break-word">Spons./ Conspons.</th> <th>House</th> <th>Senate</th> <th>Enacted</th> <th>Votes / Bipartisan</th> </tr>
</thead>
<tbody>
<?php
$query="SELECT period_id, startdate, enddate FROM period WHERE current=0 ORDER BY period_id ASC";
$result=mysql_query($query) or die(mysql_error());
while ($row = mysql_fetch_array($result)){
 $period= $row['period_id'];
 $startdate= new DateTime($row['startdate']);
 $startdate= date_format($startdate,'m/d/y');
 $enddate= new DateTime($row['enddate']);
 $enddate= date_format($enddate,'m/d/y');
 echo '<tr><th rowspan="2">';
 echo $startdate." - ".$enddate;
 echo '</th><td>';
 echo sponsor_count($member_id, $period);
 $sponsor_count[]= sponsor_count($member_id, $period);
 echo '</td><td>';
 echo pass_house_sponsor($member_id, $period);
 $pass_house_sponsor[]= pass_house_sponsor($member_id, $period);
 echo '</td><td>';
 echo pass_senate_sponsor($member_id, $period);
 $pass_senate_sponsor[]= pass_senate_sponsor($member_id, $period);
echo '</td><td>';
 echo sponsor_enacted($member_id, $period);
 $sponsor_enacted[]= sponsor_enacted($member_id, $period);
 echo '</td><td>';
 echo yea_vote($member_id,$period);
 $yea_vote[]= yea_vote($member_id,$period);
 echo '</td></tr>';
 echo '<tr><td>';
 echo cosponsor_count($member_id,$period);  
 $cosponsor_count[]= cosponsor_count($member_id,$period);  
 echo '</td><td>';
 echo pass_house_cosponsor($member_id, $period);
 $pass_house_cosponsor[]= pass_house_cosponsor($member_id, $period);
 echo '</td><td>';
 echo pass_senate_cosponsor($member_id, $period);
 $pass_senate_cosponsor[]= pass_senate_cosponsor($member_id, $period);
 echo '</td><td>';
 echo cosponsor_enacted($member_id,$period);
 $cosponsor_enacted[]= cosponsor_enacted($member_id,$period);
 echo '</td><td>';
 echo bipartisan($member_id,$period,$member_data['party']);
 $bipartisan[]= bipartisan($member_id,$period,$member_data['party']);
 echo '</td></tr>';
}
 echo '</tbody><tfoot><tr><th rowspan="2">';
 echo 'Total';
 echo '</th><td>';
 

if (empty($sponsor_count) !== true) {echo array_sum($sponsor_count);} else{echo '0';}
 echo '</td><td>';
if (empty($pass_house_sponsor) !== true) { echo array_sum($pass_house_sponsor);} else{echo '0';}
 echo '</td><td>';
if (empty($pass_senate_sponsor) !== true) { echo array_sum($pass_senate_sponsor);} else{echo '0';}
 echo '</td><td>';
if (empty($sponsor_enacted) !== true) { echo array_sum($sponsor_enacted);} else{echo '0';}
 echo '</td><td>';
if (empty($yea_vote) !== true) { echo array_sum($yea_vote);} else{echo '0';}
 echo '</td></tr>';
 echo '<tr><td>';
if (empty($cosponsor_count) !== true) { echo array_sum($cosponsor_count);} else{echo '0';}
 echo '</td><td>';
if (empty($pass_house_cosponsor) !== true) { echo array_sum($pass_house_cosponsor);} else{echo '0';}
 echo '</td><td>';
if (empty($pass_senate_cosponsor) !== true) { echo array_sum($pass_senate_cosponsor);} else{echo '0';}
 echo '</td><td>';
if (empty($cosponsor_enacted) !== true) { echo array_sum($cosponsor_enacted);} else{echo '0';}
 echo '</td><td>';
if (empty($bipartisan) !== true) { echo array_sum($bipartisan);} else{echo '0';}
 echo '</td></tr>';

?> 
</tfoot>
</table>
</div>
<br>
<h2>Bills</h2>
<h3>Enacted</h3>
<?php
	$query = "SELECT bill_id, title FROM bills WHERE sponsor='$member_id' AND enacted<>'0000-00-00' ORDER BY SUBSTR(bill_id FROM 1 FOR 1) , CAST(SUBSTR(bill_id FROM 2) AS UNSIGNED)";
	$result = mysql_query($query) or die(mysql_error());
$num_rows = mysql_num_rows($result);
 
 if($num_rows > 0) {
    echo '<div class="memberbill">';
while($row = mysql_fetch_array($result)){
    echo "&bull;<a href=\"bills.php?bill_id=".$row['bill_id']."\">" .$row['bill_id'] . " - " . stripslashes($row['title']) . "</a><br>";
}
	echo '</div>';
 }else {
	echo 'No Bills Enacted.';	
	}?>
<br>
<br>
<h3>Sponsored</h3>
<?php
	$query = "SELECT bill_id, title FROM bills WHERE sponsor='$member_id' ORDER BY SUBSTR(bill_id FROM 1 FOR 1) , CAST(SUBSTR(bill_id FROM 2) AS UNSIGNED)";
	$result = mysql_query($query) or die(mysql_error());
$num_rows = mysql_num_rows($result);
 
 if($num_rows > 0) {
    echo '<div class="memberbill">';
while($row = mysql_fetch_array($result)){
    echo "&bull;<a href=\"bills.php?bill_id=".$row['bill_id']."\">" .$row['bill_id'] . " - " . stripslashes($row['title']) . "</a><br>";
}
	echo '</div>';
 }else {
	echo 'No Bills Sponsored.';	
	}?>
<br>
<h3>Cosponsored</h3>
<?php
	$query = "SELECT bills.bill_id, title, joined FROM bills, cosponsors WHERE bills.bill_id=cosponsors.bill_id AND cosponsor_id='$member_id' ORDER BY SUBSTR(bills.bill_id FROM 1 FOR 1) , CAST(SUBSTR(bills.bill_id FROM 2) AS UNSIGNED)";
	$result = mysql_query($query) or die(mysql_error());
	$num_rows = mysql_num_rows($result);
 	
	if($num_rows > 0) {
    echo '<div class="memberbill">';
while($row = mysql_fetch_array($result)){
$joined= new DateTime($row['joined']);
 	$joined= date_format($joined,'m/d/y');
    echo "&bull;<a href=\"bills.php?bill_id=".$row['bill_id']."\">" .$row['bill_id'] . " - " . stripslashes($row['title']) . "</a> (Joined ". $joined . ") <br>";
}
	echo '</div>';
	}else{
	echo 'No Bills Cosponsored.';	
	}?>
	<br>
<h2>Committees</h2>
<p><i><?php echo $member_data['title']. " " . $member_data['lastname'];?> serves on the following <strong>committees</strong> and has a leadership role in the listed </i>subcommittees<i>:  </i></p>
<?php
 
$query="SELECT committees_members.committee_id, committees_members.role, committees.name, committees.code FROM `committees_members`, `committees` WHERE type<>'subcommittee' AND committees_members.member_id = '$member_id' AND committees_members.committee_id = committees.code";
$result = mysql_query($query) or die(mysql_error());
 echo'<dl>';
while($row = mysql_fetch_array($result)){
	//$code = $row['code'].'%';
    echo "<dt><strong>&bull;" .stripslashes($row['name']) . " - " . $row['role'] . "</strong></dt>";
	}
$query2="SELECT committees_members.committee_id, committees_members.role, committees.name, committees.code FROM `committees_members`, `committees` WHERE type='subcommittee' AND role<>'Member' AND committees_members.member_id = '$member_id' AND committees_members.committee_id = committees.code";
 	$result2 = mysql_query($query2) or die(mysql_error());
 
	while($row2 = mysql_fetch_array($result2)){
    echo "<dd>&bull;" .stripslashes($row2['name']) . " - " . $row2['role'] . "</dd>";
	}

echo'</dl>';
?>
 
<?php 
} else{
        $query="SELECT DISTINCT `state` FROM `members` WHERE `title` IN ('Sen.','Rep.') ORDER BY `state`"; 
        $result=mysql_query($query); 
	while ($row=mysql_fetch_array($result)) {
      $state= $row['state'];  
        echo "<h2>".$row['state']."</h2><p>";
        $query2="SELECT `member_id`, `name` FROM `members` WHERE `state`='$state' AND `current`=1 ORDER BY LENGTH(`district`), `district`"; 
        $result2=mysql_query($query2); 
    		echo '<ul>';
		while ($row2=mysql_fetch_array($result2)) {
 			echo "<li class=\"double\"><a href=\"member.php?member_id=".$row2['member_id']."\">".$row2['name']."</a></li> ";
		}
		$n = mysql_num_rows($result2);
		if ($n % 2 !== 0){
		echo '<li class="double">&nbsp;</li>';
		}
		echo '</ul></p>';
	}		

}
 include 'includes/overall/footer.php'; ?>