<div class="widget">
<h2>Hello, <?php echo $user_data['first_name'];?>!</h2>
<div class="inner">
 <div class="thumb">
            <?php
        	if (empty($user_data['profile']) === false) {
               		echo '<a href="'.$user_data['username'].'"><img src="', $user_data['profile'], '"alt="', $user_data['first_name'], '\'s Profile Image"></a>';
		} else {
		       	echo '<a href="'.$user_data['username'].'"><img src="images/default.jpg"alt="', $user_data['first_name'], '\'s Profile Image"></a>';
		}
            ?>
        </div>       
	
   <center> <a href="/<?php echo $user_data['username']?>">Profile</a> &bull; <a href="/forum/viewtopic.php?f=6&t=3">Help</a> &bull; <a href="logout.php">Log out</a></center>
    
</div>
</div>