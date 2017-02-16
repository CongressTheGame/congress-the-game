<?php 
include 'core/init.php';
include 'includes/overall/header.php';	

if (isset($_GET['bill_id']) === true){
$bill_id = $_GET['bill_id'];
$bill_data = bill_data($bill_id, 'title', 'sponsor','introduced','enacted','type');
if ($bill_data['type']==='s'){
echo '<h1><a href="http://www.govtrack.us/congress/bills/115/'.$bill_id.'" style=color:black;>'.stripslashes($bill_data['title']).' ('.$bill_id.')</a></h1><br>';	
}
else if($bill_data['type']==='h'){
$hr = substr($bill_id,1);
echo '<h1><a href="http://www.govtrack.us/congress/bills/115/hr'.$hr.'" style=color:black;>'.stripslashes($bill_data['title']).' ('.$bill_id.')</a></h1><br>';	
}			

$introduced = new DateTime($bill_data['introduced']);
$introduced = date_format($introduced,'m/d/Y');
if($bill_data['enacted'] !== '0000-00-00'){
$enacted = new DateTime($bill_data['enacted']);
$enacted = date_format($enacted,'m/d/Y');
} else {
	$enacted = "...";
}
echo '<table class="results"><thead><tr>';
echo '<th>Action</th><th>Date</th></tr></thead>';
echo '<tbody><tr><td>Introduced</td>';
echo '<td align="center">'.$introduced.'</td></tr>';
echo '<tr><td>Passed House</td>';
echo '<td align="center">'.date_pass_house($bill_id).'</td></tr>';
echo '<tr><td>Passed Senate</td>';
echo '<td align="center">'.date_pass_senate($bill_id).'</td></tr>';
echo '<tr><td>Enacted</td>';
echo '<td align="center">'.$enacted.'</td></tr></tbody>';
echo '</table>';

?>
<br>
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
 <h2>Sponsor</h2>
 <ul>
 <?php 
 $member_data = member_data($bill_data['sponsor'],'name');
 echo '<li>&bull;<a href=member.php?member_id='.$bill_data['sponsor'].'>'.$member_data['name'].'</a></li>';
 ?>
 </ul>
<h2>Cosponsors</h2>
<ul>
 <?php 
$query="SELECT cosponsor_id, joined FROM cosponsors WHERE bill_id = '$bill_id'";
$result=mysql_query($query) or die(mysql_error());
 while($row = mysql_fetch_array($result)){
 $member_data = member_data($row['cosponsor_id'],'name');
 echo '<li class="double">&bull;<a href=member.php?member_id='.$row['cosponsor_id'].'>'.$member_data['name'].'</a></li>';
 }	
 echo'</ul>';
} else {
	     $query="SELECT DISTINCT `type` FROM `bills`"; 
        $result=mysql_query($query); 
	while ($row=mysql_fetch_array($result)) {
		if ($row['type'] == "h") {
			$type = 'House Bills';	
		}
		if ($row['type'] == "s") {
			$type = 'Senate Bills';	
		}
        echo "<h2>".$type."</h2>";
		$t = $row['type'];
		?>
        <form action="" method="get">
<ul>
   <li>
      <SELECT NAME=bill_id size="15" style="width:600px; overflow:auto;"> 
        <?php
		$query2="SELECT `bill_id`, `title` FROM `bills` WHERE type='$t' ORDER BY SUBSTR(bill_id FROM 1 FOR 1) , CAST(SUBSTR(bill_id FROM 2) AS UNSIGNED)"; 
        $result2=mysql_query($query2); 
		while ($row2=mysql_fetch_array($result2)) {
					$bills = $row2['bill_id'];
			    	echo '<OPTION VALUE='.$bills.'>'.$bills.' - '.stripslashes($row2['title']); 

		}
	
		?>
     
      </SELECT>
 </li>
 <li>
      <input type="submit" value="Select Bill">
   </li>
</ul>
</form>
        <?php
	}
}
 ?>
<?php include 'includes/overall/footer.php'; ?>