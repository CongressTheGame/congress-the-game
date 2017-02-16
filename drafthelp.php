<?php include 'core/init.php';?>
<?php include 'includes/overall/header.php';
if (logged_in() === true) {
?>
<h1>Draft Tips</h1>
<br>
<a href="/forum/viewtopic.php?f=6&t=4">Check out the Draft Help Thread on our Forums for some more information.</a>
<br>
<br>
<h2>Draft Methods:</h2>
<br>
<h3>Silent Draft</h3>
<p>Every player sends the commissioner a ranked list of their top choices with plenty of extra members in case of other teams drafting members a player wants.</p>
<p>The Commissioner will then assign a random order to the teams and assign members to the teams based on the lists and the order.</p>
<h3>Live Draft</h3>
<p>Players get together in a single location or a google hangout or some other method and draft players in person based on a random draft order determined by the commissioner.<p>
<br>
<h2>Other Tips:</h2>
<p>Some leagues prefer to draft in draft order (1, 2, 3, 1, 2, 3) while others serpentine through the order (1, 2, 3, 3, 2, 1).</p>
<p>When all of the teams have drafted <strong>6 senators and 12 representatives</strong>, The commissioner inputs them on the league page by following the draft link in the Commissioner Options area.</p>
<p>Once the teams are entered into the system, player can choose the members they want to be active for the week.</p>
<p>Two Senators and Five Representatives can be activated each week.</p>


<?php 
}
include 'includes/overall/footer.php';?>