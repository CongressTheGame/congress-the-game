<table class="results">
	<thead>
    	<tr>
        	<th colspan="2">League Message Board</th>
        </tr>
<?php  
//query comments for this page of this article 
$inf = "SELECT * FROM `comments` WHERE league_id = '$league_id' ORDER BY time ASC"; 
 $info = mysql_query($inf) or die(mysql_error());  
   $info_rows = mysql_num_rows($info); 
if($info_rows > 0) { 
 while($info2 = mysql_fetch_object($info)) {     
echo '</thead><tbody><tr>';    
echo '<td><a href="/'.$info2->username.'">'.stripslashes($info2->username).'</a> 
</td> <td><div align="right">'.date('h:i:s a', $info2->time).' on '.$info2->date.'</div></td>'; 
echo '</tr><tr>'; 
echo '<td colspan="2"> '.stripslashes($info2->comment).' </td>'; 
echo '</tr>'; 
  }//end while 
echo '</tbody>'; 
} else { echo '<tr><td colspan="2">No comments for this league. Feel free to be the first!</td></tr></thead>'; 
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
$q2 = mysql_query($q) or die(mysql_error());
header ('Location: league.php');
?>
<tfoot>  
<form name="comments" action="" method="post"> 
<input type="hidden" name="date" value="<? echo(date("F j, Y.")); ?>"> 
<input type="hidden" name="time" value="<? echo(time()); ?>"> 

    <tr> 
      <th colspan="2"><textarea name="comment" rows="2" style="width:100%; height:50px;" wrap="VIRTUAL"></textarea></th> 
    </tr> 
    <tr>  
      <th colspan="2"><input type="reset" value="Reset Fields">      
        <input type="submit" name="submit" value="Add Comment"></th> 
    </tr> 
    
</form> 
</tfoot>
</table>
<?php  
} else {
?>
<tfoot>  
<form name="comments" action="" method="post"> 
<input type="hidden" name="date" value="<? echo(date("F j, Y.")); ?>"> 
<input type="hidden" name="time" value="<? echo(time()); ?>"> 

    <tr> 
      <th colspan="2"><textarea name="comment" rows="2" style="width:100%; height:50px;" wrap="VIRTUAL"></textarea></th> 
    </tr> 
    <tr>  
      <th colspan="2"><input type="reset" value="Reset Fields">      
        <input type="submit" name="submit" value="Add Comment"></th> 
    </tr> 
    
</form> 
</tfoot>
</table> 
<?php
echo output_errors($errors);}
?>