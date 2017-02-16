<?php
include 'core/init.php';
logged_in_redirect();

if (empty($_POST) === false) {
	$username = $_POST['username'];
	$password = $_POST['password'];
	$email = $_POST['email'];
	if (empty($username) === true || empty($password) === true|| empty($email) === true) {
	       $errors[] = 'Please complete all fields.';
	} else if(user_exists($username) === false){
		$errors[] = "The Username or Password you entered is not valid.";                
	} else if (email_exists($_POST['email']) === false) {
                $errors[] = 'We can\'t find that email address.';
        } else { 
               $login= login($username, $password);
               if ($login === false){
   		    $errors[] = 'The Username or Password you entered is not valid.';
               } else{
                 $user_data=user_data($login,'first_name','email','email_code');
                    if ($_POST['email'] !== $user_data['email']){
                        $errors[] = 'The Username, Password, or Email you entered is not valid.';
                    } else {
                      email($user_data['email'], 'Activate your account.', "Hello " . $user_data['first_name'] . ",\n\nWelcome to Congress: the Game! Click on the following link to activate your account.\n\n http://www.congressthegame.com/activate.php?email=" . $user_data['email'] . "&email_code=" . $user_data['email_code'] ."\n\n- Philip");                    
		      header ('Location: resend.php?success');
                    } 
              }
       }
}

include 'includes/overall/header.php';
?>
<h1>Resend Activation Email</h1>
<?php
if (isset($_GET['success']) === true && empty($_GET['success']) === true){
	?>
    <p>Thanks, we've emailed you. Checked your spam folder and make sure you allow emails from 'philip@congress-game.com'.</p>
    <?php
} else{


?>
	<form action="" method="post">
    	<ul>
            <li>
            	Username:<br />
                <input type="text" value="<?php echo $_POST['username'];?>" name="username" />
            </li>
            <li>
            	Password:<br />
                <input type="password" value="<?php echo $_POST['password'];?>" name="password" />
            </li>
            <li>
            	Email address:<br />
                <input type="text" value="<?php echo $_POST['email'];?>" name="email" />
            </li>
            <li><input type="submit" value="Resend Activation" /></li>
            </form>
            <?php echo output_errors($errors);?>
        </ul>
<?php	
}
?>
<?php include 'includes/overall/footer.php';?>	