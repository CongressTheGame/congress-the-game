<?php 
include 'core/init.php';
logged_in_redirect();
if(isset($_GET['email'], $_GET['email_code']) ===true){
	$email		=trim($_GET['email']);
	$email_code	=trim($_GET['email_code']);
	
	if(email_exists($email) === false){
		$errors[] = 'Email address couldn\'t be found';	
	} else if (activated($email, $email_code) === true) {
		$errors[] = 'Your account is already activated.';
	} else if (activate($email, $email_code) === false) {
		$errors[] = 'We had problems activating your account';
	}
}
if(isset($_GET['email'], $_GET['email_code']) ===true && empty($errors) === true){
	header('Location: activate.php?success');
	exit();
}
include 'includes/overall/header.php';

if (isset($_GET['success']) === true && empty($_GET['success']) === true) {
echo "<h2>Your account has been activated!</h2><p>You're free to log in!</p>";
} else if (empty($errors) === false) {
	echo"<h2>Oops...</h2>".output_errors($errors);
} else {
	header('Location: index.php');
	exit();
	}
	include 'includes/overall/footer.php';
?>