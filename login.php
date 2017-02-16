<?php
include 'core/init.php';
logged_in_redirect();

if (empty($_POST) === false) {
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	if (empty($username) || empty($password) ) {
	$errors[] = 'Enter a Username and Password.';
	} else if(user_exists($username) === false){
		$errors[] = "The Username or Password you entered is not valid.";
	} else if(user_active($username) === false){
		$errors[] = 'Have you activated your account yet?<br><a href="resend.php">Click here to resend activation email.</a>';
	} else {
$login= login($username, $password);
  if ($login === false){
   		$errors[] = 'The Username or Password you entered is not valid.';

   } else{
		$_SESSION['user_id']= $login;
		header("location: index.php");
		exit();
		  }
	}
	
}
else {
	$errors[] = 'No Data Received';
	}
include 'includes/overall/header.php';
if(empty($errors) === false){
?>
<h2>We couldn't log you in...</h2>
<?php
echo output_errors($errors);
}
include 'includes/overall/footer.php';
?>