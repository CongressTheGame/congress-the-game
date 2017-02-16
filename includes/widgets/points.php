<?php
$query="SELECT * FROM points WHERE league_id='$league_id'";
$result=mysql_query($query);
$points=mysql_fetch_array($result);
 
?>

<table class="results">
<thead>
<tr>
   <th colspan="3" align="center"><?php echo $league_data['league_name'];?>'s Points</th>
</tr>
<tr>
   <td>Action</td>
   <td>Sponsor</td>
   <td>Co-Sponsor</td>
</tr>
</thead>
<tbody>
<tr>
   <td>Introduce</td>
   <td><?php echo $points['sponsor_introduce'];?></td>
   <td><?php echo $points['cosponsor_introduce'];?></td>
</tr>
<tr>
   <td>Pass House</td>
   <td><?php echo $points['sponsor_pass_house'];?></td>
   <td><?php echo $points['cosponsor_pass_house'];?></td>
</tr>
<tr>
   <td>Pass Senate</td>
   <td><?php echo $points['sponsor_pass_senate'];?></td>
   <td><?php echo $points['cosponsor_pass_senate'];?></td>
</tr>
<tr>
   <td>Enacted</td>
   <td><?php echo $points['sponsor_enacted'];?></td>
   <td><?php echo $points['cosponsor_enacted'];?></td>
</tr>
<tr>
   <td colspan="2">Yea Vote on Passing Bill</td>
   <td><?php echo $points['yea_vote'];?></td>
</tr>
<tr>
   <td colspan="2">Bipartisan Bonus</td>
   <td><?php echo $points['bipartisan'];?></td>
</tr>
</tbody>
</table>
 
 
