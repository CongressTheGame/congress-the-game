<?php include 'core/init.php';
protect_page();

if(empty($_POST) === false){
	$required_fields = array('current_password','password','confirm_password');
	foreach($_POST as $key=>$value){
		if(empty($value) && in_array($key, $required_fields) === true) {
			$errors[] = 'Fields marked with an asterisk are required';
			break 1;
		}
	}
	if(md5($_POST['current_password']) === $user_data['password']){
		if(trim($_POST['password']) !== trim($_POST['confirm_password'])){
			$errors[] = 'Your new passwords do not match.';
		} else if (strlen($_POST['password']) < 8) {
			$errors[] = 'Password must be at least 8 characters long';
		}
	} else {
		$errors[] = 'Your current password is incorrect.';
	}
}
if(empty($_POST) === false && empty($errors) === true){
	change_password($session_user_id, $_POST['password']);
	header('Location: changepassword.php?success');}
include 'includes/overall/header.php';?>
<h1>Change Password</h1>
<?php
if(isset($_GET['success']) === true && empty($_GET['success']) === true){
	echo 'Your password has been changed.';
} else {
	if(isset($_GET['force']) === true && empty($_GET['force']) === true) {
	?>
    	<p>You must change your password after recovery.</p>
    <?php
	}
	 
 else if (empty($errors) === false){
	echo output_errors($errors);
}
?>
<form action="" method="post">
	<ul>
    	<li>
        	Current password*:<br>
            <input type="password" name="current_password">	
        </li>
       	<li>
        	New password*:<br>
            <input type="password" name="password">	
        </li>
        <li>
        	Confirm New password*:<br>
            <input type="password" name="confirm_password">	
        </li>
        <li>
            <input type="submit" value="Change Password">	
        </li>
    </ul>
    
<?php 
}
include 'includes/overall/footer.php';?>