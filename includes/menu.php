<div id="nav">
<ul>
<a href="index.php"><img id="logo" src="images/logo.jpg" /></a>
<?php if (logged_in() === true){ ?>
<li class="last"><a href="logout.php" id="logout_btn">Logout</a></li>
<li><a href="forum">Forum</a></li>
<li><a href="bills.php">Bills</a></li>
<li><a href="member.php">Members</a></li>
<li><a href="team.php">Teams</a></li>
<li><a href="league.php">Leagues</a></li>
<li><a href="index.php">Home</a></li>
<? }else { ?>
<li class="last"><a href="register.php" id="logout_btn">Register</a></li>

<li><a href="bills.php">Bills</a></li>
<li><a href="member.php">Members</a></li>
<li><a href="index.php">Home</a></li>
<?php } ?>
</ul>
</div>