<?php 
include 'core/init.php';

if (logged_in() !== true) {

include 'includes/head.php';?>
<body id="index">
<?php include_once("includes/analyticstracking.php") ?>
<div id="nav">
<ul>
<a href="index.php"><img id="logo" src="images/logo.jpg" /></a>
<li class="last"><a href="#" class="login_button">Login</a></li>
<li><a href="#" class="register_button">Register</a></li>
<li><a href="#" class="overview_button">Overview</a></li>
<li><a href="index.php">Home</a></li>
</ul>
</div>

<div id="body">
<h1>Welcome to Congress: The Game</h1>
<h3>A fantasy-sports style approach to politics</h3>
<input type="button" class="register_button" value="Sign Up" /> 
<input type="button" class="login_button" value="Log In" />
</div>
<div id="popupRegister">
		<a id="popupRegisterClose">x</a>
		<h1>Register</h1>
		<form id="registerArea" action="registration.php" method="post">
			<ul>
			<li>Username*: <input type="text" name="username" class="formField"></li>
			<li>Password*: <input type="password" name="password" class="formField"></li>
			<li>Confirm Password*: <input type="password" name="confirm_password" class="formField"></li>
			<li>First Name*: <input type="text" name="first_name" class="formField"></li>
			<li>Last Name: <input type="text" name="last_name" class="formField"></li>
			<li>Email*: <input type="email" name="email" class="formField"></li>
			<li><br><input type="submit" name="submit" value='Register' class="formField"></li>
			</ul>
		</form>
	</div>
<div id="popupLogin">
		<a id="popupLoginClose">x</a>
		<h1>Login</h1>
		<form id="loginArea" action="login.php" method="post">
			<ul>
			<li>Username: <input type="text" name="username" class="formField"></li>
			<li>Password: <input type="password" name="password" class="formField"></li>
			<li><br><input type="submit" id="submit" value='Log In' class="formField"></li>
			</ul>
		</form>
	</div>
<div id="popupOverview">
		<a id="popupOverviewClose">x</a>
		<h1>Overview</h1>
		<p id="overviewArea">
			<ul>
			<li><p>Congress: The Game is a fantasy sports style approach to politics. Players draft members of congress to their teams and earn points based on their legislators' actual performance in Washington.</p>
            <p>Players will join a league where they draft their team of 6 senators and 12 representatives.  Every week players will select 2 senators and 5 representatives to represent their team.</p>
            <br><p>Points will be awarded for several legislative acts.  These acts include:
            <ul style="list-style:square;">
            <li>Sponsoring/Cosponsoring a bill</li>
            <li>Having a sponsored/cosponsored bill pass the House of Representatives</li>
            <li>Having a sponsored/cosponsored bill pass the Senate</li>
            <li>Having a sponsored/cosponsored bill signed into law by the President</li>
            <li>Voting for a passing bill</li>
            <li>Voting for a passing bill sponsored by the opposing party</li>
            </ul>
            </p>
            <p>The points accumulated each week are added to the teams' running totals and are displayed on the league leaderboard.
            </p><br>
            <p>It's free to play so sign up and start playing today!</p>
            </li>
		</p>
	</div>	
	<div id="backgroundPopup"></div>

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/register.js"></script>
</div>
</body>
</html>
<?php } else {
include 'includes/overall/header.php';
if (logged_in() === true) {
?>
<div id="indexContent">
<div id="results" class="indexButton">
<div id="member">
<table>
<tr>
<td width="70%">
<h2 style="color:navy;">Play in the Open League</h2>
<ul type="circle"><li>&bull;Pick members weekly and play against everyone on Congress: The Game</li>
<li>&bull;No league required. Play now!</li></ul>
</td>
<td>
<input id="register" type="submit" onclick="location.href='open.php';" value="Open League" />
</td>
</tr>
</table>
</div>

<div id="member">
<table>
<tr>
<td width="70%">
<h2 style="color:navy;">Create a Free Private League</h2>
<ul type="circle"><li>&bull;Create a free league and invite your friends</li>
<li>&bull;Customize scoring and draft teams</li></ul>
</td>
<td>
<input id="register" type="submit" onclick="location.href='league.php?create';" value="Create a League" />
</td>
</tr>
</table>
</div>

<div id="member">
<table>
<tr>
<td width="70%">
<h2 style="color:navy;">Join a Free Private League</h2>
<ul type="circle"><li>&bull;Join a private league</li></ul>
</td>
<td>
<input id="register" type="submit" onclick="location.href='league.php?create';" value="Join a League" />
</td>
</tr>
</table>
</div>
</div>
<div style="width: 600px;">
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
<div id="results">
<?
$query="SELECT title, bill_id, enacted FROM bills WHERE enacted <>  '0000-00-00' ORDER BY enacted DESC LIMIT 5";
$result=mysql_query($query);
?>
<table class="results">
<thead><tr><th colspan="3">Recently Enacted Bills</th></tr>
<tr><td>Bill</td><td>Title</td><td>Enacted</td></tr></thead><tbody>
<?
while ($row=mysql_fetch_array($result)) { 
$title=stripslashes($row['title']);
$enacted = new DateTime($row['enacted']);
$enacted = date_format($enacted,'m/d/Y');
$id=$row['bill_id'];
echo '<tr><td style="width:10%;"><a href="bills.php?bill_id='.$id.'">'.$id.'</a></td><td style="width:75%;"><a href="bills.php?bill_id='.$id.'">'.$title.'</a></td><td>'.$enacted.'</td></tr>';
}
?>
</tbody></table>
<br>
<?
$query="SELECT title, bill_id, introduced FROM bills WHERE type='s' ORDER BY introduced DESC, bill_id DESC LIMIT 5";
$result=mysql_query($query);
?>
<table class="results">
<thead><tr><th colspan="3">Recently Introduced Senate Bills</th></tr>
<tr><td>Bill</td><td>Title</td><td>Introduced</td></tr></thead><tbody>
<?
while ($row=mysql_fetch_array($result)) { 
$title=stripslashes($row['title']);
$introduced= new DateTime($row['introduced']);
$introduced= date_format($introduced,'m/d/Y');
$id=$row['bill_id'];
echo '<tr><td style="width:10%;"><a href="bills.php?bill_id='.$id.'">'.$id.'</a></td><td style="width:75%;"><a href="bills.php?bill_id='.$id.'">'.$title.'</a></td><td>'.$introduced.'</td></tr>';
}
?>
</tbody></table>
<br><?
$query="SELECT title, bill_id, introduced FROM bills WHERE type='h' ORDER BY introduced DESC, bill_id DESC LIMIT 5";
$result=mysql_query($query);
?>
<table class="results">
<thead><tr><th colspan="3">Recently Introduced House Bills</th></tr>
<tr><td>Bill</td><td>Title</td><td>Introduced</td></tr></thead><tbody>
<?
while ($row=mysql_fetch_array($result)) { 
$title=stripslashes($row['title']);
$introduced= new DateTime($row['introduced']);
$introduced= date_format($introduced,'m/d/Y');
$id=$row['bill_id'];
echo '<tr><td style="width:10%;"><a href="bills.php?bill_id='.$id.'">'.$id.'</a></td><td style="width:75%;"><a href="bills.php?bill_id='.$id.'">'.$title.'</a></td><td>'.$introduced.'</td></tr>';
}
?>
</tbody></table>
<br>
<?
$query="select members.name, bills.sponsor, count(*)from bills, members where members.member_id=bills.sponsor and bills.enacted<>'0000-00-00' group by bills.sponsor order by count(*) desc limit 5";
$result=mysql_query($query);
?>
<table class="results">
<thead><tr><th colspan="2">Most Enacted Bills</th></tr>
<tr><td>Name</td><td>Count</td></tr></thead><tbody>
<?
while ($row=mysql_fetch_array($result)) { 
$name=$row['name'];
$count=$row['count(*)'];
$id=$row['sponsor'];
echo '<tr><td style="width:75%;"><a href="member.php?member_id='.$id.'">'.$name.'</a></td><td>'.$count.'</td></tr>';
}
?>
</tbody></table>
<br>
<?
$query="select members.name, bills.sponsor, count(*)from bills, members where members.member_id=bills.sponsor group by bills.sponsor order by count(*) desc limit 5";
$result=mysql_query($query);
?>
<table class="results">
<thead><tr><th colspan="2">Most Sponsored Bills</th></tr>
<tr><td>Name</td><td>Count</td></tr></thead><tbody>
<?
while ($row=mysql_fetch_array($result)) { 
$name=$row['name'];
$count=$row['count(*)'];
$id=$row['sponsor'];
echo '<tr><td style="width:75%;"><a href="member.php?member_id='.$id.'">'.$name.'</a></td><td>'.$count.'</td></tr>';}
?>
</tbody></table>
<br>
<?
$query="select members.name, cosponsors.cosponsor_id, count(*)from cosponsors, members where members.member_id=cosponsors.cosponsor_id group by cosponsors.cosponsor_id order by count(*) desc limit 5";
$result=mysql_query($query);
?>
<table class="results">
<thead><tr><th colspan="2">Most Cosponsored Bills</th></tr>
<tr><td>Name</td><td>Count</td></tr></thead><tbody>
<?
while ($row=mysql_fetch_array($result)) { 
$name=$row['name'];
$count=$row['count(*)'];
$id=$row['cosponsor_id'];
echo '<tr><td style="width:75%;"><a href="member.php?member_id='.$id.'">'.$name.'</a></td><td>'.$count.'</td></tr>';}
?>
</tbody></table>
</div>
</div>
<?php } else {?>
<h1>Congress: The Game</h1>
<p>Welcome to Congress: The Game.  Sign up and let's play.</p>
<?php 
}
include 'includes/overall/footer.php';	
}?>