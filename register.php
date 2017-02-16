<?php 
include 'core/init.php';

include 'includes/overall/header.php';
	?>
<h1>Register</h1>
 
<?php
if (isset($_GET['success']) && empty($_GET['success'])){
	echo 'You\'ve been registered successfully!<br><br>Please check your email to activate your account.';
	include 'includes/overall/footer.php';
	end;
} else if(empty($errors) === false){
	echo output_errors($errors);
  }			
?>
    <form action="registration.php" method="post">
        <ul>
            
            <li>
                Username*: <br>
                <input class="formField" type="text" name="username" value="<?php echo $_POST['username']; ?>" id="register">
            </li>
            <li>
                Password*:<br>
                <input class="formField" type="password" name="password" placeholder="Must be at least 8 characters" id="register">
            </li>
            <li>
                Confirm Password*: <br>
                <input class="formField" type="password" name="confirm_password" id="register">
            </li>
            <li>
                First Name*: <br>
                <input class="formField" type="text" name="first_name" value="<?php echo $_POST['first_name']; ?>" id="register">
            </li>
            <li>
                Last Name: <br>
                <input class="formField" type="text" name="last_name" value="<?php echo $_POST['last_name']; ?>" id="register">
            </li>
            <li>
                Email*: <br>
                <input class="formField" type="email" name="email" value="<?php echo $_POST['email']; ?>" id="register">
            </li>

            <li>
                <input class="formField" type="submit" value="Register" id="register">
            </li>
        </ul>
    </form>
<?php 
include 'includes/overall/footer.php';?>