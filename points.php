<?php include 'core/init.php';
protect_page();
 
if(isset($_GET['success'])) {
   include 'includes/overall/header.php'; 
         echo '<h2>Success!</h2><p>Points updated successfully<p>';
         echo '<form action="league.php">';
         echo '<input id="signup" type="submit" value="Back To Your Leagues" >';
         echo '</form>';
}else{
 
$league_id = $_SESSION['league_id'];
if(is_commissioner($session_user_id,$league_id) === false){
  header ("Location: league.php");
} else {
 
 
       $query = mysql_query("SELECT league_name FROM leagues WHERE league_id = '$league_id'");
       $league_name = mysql_result($query,0);
 
       $query="SELECT * FROM points WHERE league_id='$league_id'";
       $result=mysql_query($query);
       $points=mysql_fetch_array($result);
 
   if(empty($_POST) === false) {
         $required_fields = array('sponsor_introduce','cosponsor_introduce','sponsor_pass_senate','cosponsor_pass_senate','sponsor_pass_house','cosponsor_pass_house','sponsor_enacted','cosponsor_enacted','yea_vote','bipartisan');
     foreach($_POST as $key=>$value){
         if(empty($value) && in_array($key,$required_fields) === true){
             $errors[]='All categories are required.';
             break 1;
         }
     }
   }
   if (empty($_POST) === false && empty($errors) === true) {
        $points_data = array(
             'league_id'  => $league_id,
             'sponsor_introduce' => $_POST['sponsor_introduce'],
             'cosponsor_introduce' => $_POST['cosponsor_introduce'],
             'sponsor_pass_senate' => $_POST['sponsor_pass_senate'],
             'cosponsor_pass_senate' => $_POST['cosponsor_pass_senate'],
             'sponsor_pass_house' => $_POST['sponsor_pass_house'],
             'cosponsor_pass_house' => $_POST['cosponsor_pass_house'],
             'sponsor_enacted' => $_POST['sponsor_enacted'],
             'cosponsor_enacted' => $_POST['cosponsor_enacted'],
             'yea_vote' => $_POST['yea_vote'],
			 'bipartisan' => $_POST['bipartisan']
         );
         update_points($points_data);
         header('Location: points.php?success');
    } 
    include 'includes/overall/header.php'; 

   if (empty($errors) === false) {
      echo output_errors($errors);
     }
?>
 
<form method="post" action="">
<table class="results">
<thead
<tr>
   <th colspan="3" align="center"><?php echo $league_name;?>'s Points</th>
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
   <td><input type="number" name="sponsor_introduce" size="4" min="0" max="100" value="<?php echo $points['sponsor_introduce'];?>"></td>
   <td><input type="number" name="cosponsor_introduce" size="4" min="0" max="100" value="<?php echo $points['cosponsor_introduce'];?>"></td>
</tr>
<tr>
   <td>Pass House</td>
   <td><input type="number" name="sponsor_pass_house" size="4" min="0" max="100" value="<?php echo $points['sponsor_pass_house'];?>"></td>
   <td><input type="number" name="cosponsor_pass_house" size="4" min="0" max="100" value="<?php echo $points['cosponsor_pass_house'];?>"></td>
</tr>
<tr>
   <td>Pass Senate</td>
   <td><input type="number" name="sponsor_pass_senate" size="4" min="0" max="100" value="<?php echo $points['sponsor_pass_senate'];?>"></td>
   <td><input type="number" name="cosponsor_pass_senate" size="4" min="0" max="100" value="<?php echo $points['cosponsor_pass_senate'];?>"></td>
</tr>
<tr>
   <td>Enacted</td>
   <td><input type="number" name="sponsor_enacted" size="4" min="0" max="100" value="<?php echo $points['sponsor_enacted'];?>"></td>
   <td><input type="number" name="cosponsor_enacted" size="4" min="0" max="100" value="<?php echo $points['cosponsor_enacted'];?>"></td>
</tr>
<tr>
   <td colspan="2">Yea Vote on Passing Bill</td>
   <td><input type="number" name="yea_vote" size="4" min="0" max="100" value="<?php echo $points['yea_vote'];?>"></td>
</tr>
<tr>
   <td colspan="2">Bipartisan Bonus</td>
   <td><input type="number" name="bipartisan" size="4" min="0" max="100" value="<?php echo $points['bipartisan'];?>"></td>
</tr>
</tbody>
</table>
<input name="points" type="submit" value="Set Points" id="register" /><br>
</form>
 
<?php }}
include 'includes/overall/footer.php';?>
