<?php
include 'core/init.php';
logged_in_redirect();
 
if(empty($_POST) === false){
		$required_fields = array('username','password','confirm_password','first_name','email');
	foreach($_POST as $key=>$value){
		if(empty($value) && in_array($key,$required_fields) === true){
			$errors[]='Fields marked with an asterisk are required.';
			break 1;
		}
	}
 
	if (empty($errors) === true){
		if(user_exists($_POST['username']) === true){
			$errors[]='Sorry the username \'' . $_POST['username'] . '\' already exists.';
		}
		if(preg_match("/\W/",$_POST['username']) == true){
			$errors[]='Your username can only contain letters, numbers, or underscores.';
		}
		if (strlen($_POST['password']) < 8) {
			$errors[]='Password must be at least 8 characters long';
		}
		if($_POST['password'] !== $_POST['confirm_password']){
			$errors[]='Passwords must match.';
		}
		if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false){
			$errors[]='A valid email address is required.';
		}
		if(email_exists($_POST['email']) === true){
			$errors[]='Sorry the email \'' . $_POST['email'] . '\' already in use.';
		}
	}
}
if (empty($_POST) === false && empty($errors) === true) {
		$register_data = array(
			'username' 		=> $_POST['username'],
			'password' 		=> $_POST['password'],
			'first_name'	=> $_POST['first_name'],
			'last_name' 	=> $_POST['last_name'],
			'email' 		=> $_POST['email'],
			'email_code'    => md5($_POST['username'] + microtime()),
		);
		register_user($register_data);
		header('Location: register.php?success');
		exit();
} else {

include 'includes/overall/header.php';
if(empty($errors) === false){
?>
<h2>We couldn't sign you up...</h2>
<?php
echo output_errors($errors);
}
include 'includes/overall/footer.php';}
?>