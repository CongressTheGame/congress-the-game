<?php 
include 'core/init.php';

if (logged_in() !== true) {

include 'includes/head.php';?>
<body id="index">
<div id="nav">
<ul>
<img id="logo" src="images/logo.jpg" />
<li><a href="#" class="login_button">Login</a></li>
<li><a href="#" class="register_button">Register</a></li>
<li><a href="#" class="overview_button">Overview</a></li>
<li><a href="index.php">Home</a></li>
</ul>
</div>

<div id="body">
<h1>Welcome to Congress</h1>
<h3>A fantasy-sports style approach to politics</h3>
<input type="button" class="register_button" value="Sign Up" /> 
<input type="button" class="login_button" value="Log In" />
</div>
<div id="popupRegister">
		<a id="popupRegisterClose">x</a>
		<h1>Register</h1>
		<form id="registerArea">
			<ul>
			<li>Username: <input type="text" id="username"></li>
			<li>Password: <input type="password" id="password"></li>
			<li>Confirm Password: <input type="password" id="username"></li>
			<li>First Name: <input type="text" id="firstname"></li>
			<li>Last Name: <input type="text" id="lastname"></li>
			<li>Email: <input type="email" id="email"></li>
			<li><br><input type="submit" id="submit" value='Register'></li>
		</form>
	</div>
<div id="popupLogin">
		<a id="popupLoginClose">x</a>
		<h1>Login</h1>
		<form id="loginArea">
			<ul>
			<li>Username: <input type="text" id="username"></li>
			<li>Password: <input type="password" id="password"></li>
			<li><br><input type="submit" id="submit" value='Log In'></li>
		</form>
	</div>
<div id="popupOverview">
		<a id="popupOverviewClose">x</a>
		<h1>Overview</h1>
		<p id="overviewArea">
			<ul>
			<li><strong>What is Congress: The Game?</strong><br>Congress: The Game is a fantasy-sports style approach to politics. Players draft members of congress to their teams and earn points based on their legislators' actual performance in Washington.</li>
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
<h1>Congress: The Game</h1>
<p>Welcome to Congress:</p>
Have you:
<ul>
<li>&bull;<a href="/<?php echo $user_data['username'];?>"> Added a profile picture?</a></li>
<li>&bull;<a href="league.php?create">Joined or Created a League?</a></li>
<li>&bull;<a href="member.php">Checked out the members of congress?</a></li>
<li>&bull;<a href="bills.php">Browsed the bills from this session?</a></li>

</ul>
<div id="results" >
<form action="league.php"><input id="register" type="submit" value="View Your Leagues" ></form>
<form action="team.php"><input id="register" type="submit" value="View Your Teams" ></form>
</div>
<?php } else {?>
<h1>Congress: The Game</h1>
<p>Welcome to Congress: The Game.  Sign up and let's play.</p>
<?php 
}
include 'includes/overall/footer.php';	
}?>