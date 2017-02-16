<?php if (logged_in() === true) {
?><section id="intro">

<header>
<h2>Hello, <?php echo $user_data['first_name'];?>!</h2>
</header>

<p>Welcome back.  Figure out how to code the rest of this site please.</p>
</section>
<?php
}else {
?>
<section id="intro">

<header>
<h2>Words go here!</h2>
</header>

<p>More words go here! Eventually I will try to convince you to sign up with a snazzy button.</p>

<form action="register.php">
<input id="signup" type="submit" value="Sign up now!" >
</form>

</section>
<?php } ?>