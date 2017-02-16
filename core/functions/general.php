<?php
function email($to, $subject, $body) {
	mail($to, $subject, $body, 'From: philip@congress-game.com');
}

function protect_page() {
	if(logged_in()===false){
		header('location:protected.php');
		exit();
	}
}

function admin_protect() {
	global $user_data;
	if(has_access($user_data['user_id'], 1) === false) {
	header('location: index.php');
	exit();
	}
}

function logged_in_redirect() {
	if(logged_in() === true){
		header('location: index.php');
		exit();
	}
}

function sanitize($data) {
    return mysql_real_escape_string($data);
}

function array_sanitize(&$item) {
	$item = mysql_real_escape_string($item);
}

function output_errors($errors){
	return '<ul><li><font color="red">' . implode('</li><li>', $errors) . '</font></li></ul>';
}
?>