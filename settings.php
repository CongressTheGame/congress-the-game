<?php 
include 'core/init.php';
protect_page();

if (empty($_POST) === false) {
	$required_fields = array('first_name', 'email');
	foreach ($_POST as $key=>$value) {	
		if (empty($value) && in_array($key, $required_fields) === true) {
			$errors[]='Fields marked with an asterisk are required.';
			break 1;
		}
	}

	if (empty($errors) === true) {
		if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) {
			$errors[] = 'A valid email is required';
		} else if (email_exists($_POST['email']) === true && $user_data['email'] !== $_POST['email']){
			$errors[] = 'Email is already in use';
		}
	}
}
if (empty($_POST) === false && empty($errors) === true) {
	$allow_email = ($_POST['allow_email'] == 'on') ? 1 : 0;

	$update_data = array(
		'first_name' 	=> $_POST['first_name'],
		'last_name' 	=> $_POST['last_name'],
		'email' 	=> $_POST['email'],
		'allow_email' => $allow_email,
	);
	update_user($session_user_id, $update_data);
	exit(header ('Location: settings.php?success'));
	}
	
include 'includes/overall/header.php';
?>
<h1>Settings</h1>
<?php
if (isset($_GET['success']) && empty($_GET['success'])){
	echo '<strong>Your account details have been updated!</strong>';

} else if (empty($errors) === false) {
	echo output_errors($errors);
} 
?>
<form action="" method="post">
 
	<ul>
    	<li>
        	First Name*:<br>
            <input type="text" name="first_name" value="<?php echo $user_data['first_name']; ?>">
        </li>
        <li>
        	Last Name:<br>
            <input type="text" name="last_name" value="<?php echo $user_data['last_name']; ?>">
        </li>
        <li>
        	Email*:<br>
            <input type="text" name="email" value="<?php echo $user_data['email']; ?>">
        </li>
        <li>
        	Fields with asterisk(*) are required.
        </li>
        <li>
        	<input type="checkbox" name="allow_email" <?php if ($user_data['allow_email'] ==1){echo 'checked="checked"';}?>/> Would you like to receive emails?
        </li>
        <li>
        	<input type="submit" value="update">
        </li>
 
 
    </ul>
<?php 

include 'includes/overall/footer.php';?>