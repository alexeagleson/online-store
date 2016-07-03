<!DOCTYPE html>
<html lang="en">
<head>
	
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Bootstrap contact form with PHP example by BootstrapBay.com.">
<meta name="author" content="BootstrapBay.com">
<title>Bootstrap Contact Form With PHP Example</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">

<?php

include($_SERVER['DOCUMENT_ROOT'].'/sql_connect.php');
include($_SERVER['DOCUMENT_ROOT'].'/general_functions.php');
include($_SERVER['DOCUMENT_ROOT'].'/build_database_tables.php');
include($_SERVER['DOCUMENT_ROOT'].'/buttons_and_menus.php');
include($_SERVER['DOCUMENT_ROOT'].'/top_and_bottom.php');

global $link;
session_start();
sql_connect();

display_top_section();

$errusername = '';
$errPassword = '';
$username = '';
$password = '';
$result = '';

$message_to_display = False;

if (isset($_POST["logout"])) {
	// User has clicked the logout button, delete their session
	unset($_SESSION["current_user"]);
} else if (isset($_POST["login"])) {
	// User has clicked the login button, check credentials entered into the username/password fields
	$username_entered = strtolower($_POST['username']);
	
	$query_text = "SELECT * FROM CUSTOMERS WHERE customer_id = ?";
	$query = $link->prepare($query_text);
	$query->bind_param("s", $username_entered);
	if(!$query->execute()) {
		query_error($query_text); return False;
	}
	$results = get_all_results_2d_array($query, 'both');

	if (!$results) { 
		$message_to_display = "Username does not exist.";
	} else {
		$results = $results[0];
		
		if ($results['password'] == md5($_POST['password'])) {
			$_SESSION["current_user"] = $username_entered;
		} else {
			$message_to_display = "Your password is incorrect.";
		}
	}

	$username = $_POST['username'];
	$password = $_POST['password'];
	
	// Check if username has been entered
	if (!$_POST['username']) {
		$errusername = 'Please enter your username';
	}
	
	//Check if password has been entered
	if (!$_POST['password']) {
		$errPassword = 'Please enter your password';
	}

} else if (isset($_POST["create_new_account"])) {

	// User has clicked the register new acount button button, check credentials entered into the username/password fields against existing
	$username_entered = strtolower($_POST['username']);
	
	$query_text = "SELECT * FROM CUSTOMERS WHERE customer_id = ?";
	$query = $link->prepare($query_text);
	$query->bind_param("s", $username_entered);
	if(!$query->execute()) {
		query_error($query_text); return False;
	}
	$results = get_all_results_2d_array($query, 'both');

	if (!$results) {
		$hashed_password = md5($_POST['password']);

		$query_text = "INSERT INTO CUSTOMERS (customer_id, password) VALUES (?, ?)";
		$query = $link->prepare($query_text);
		$query->bind_param("ss", $username_entered, $hashed_password);
		if(!$query->execute()) {
			query_error($query_text); return False;
		}
		$message_to_display = "Account has been registered.";
		
		$_SESSION["current_user"] = $username_entered;
	
	} else {
		$message_to_display = "Username already in use.";
	}
}

display_nav_bar();

// Displays the various possible error/confirmation messages established above on the $message_to_display variable
if ($message_to_display) {
	?>
	<div align = "center">
		<label><?php echo $message_to_display; ?></label>
	</div>
	<?php
}

?>

</head>
<body>

<!-- This section displays the text fields to enter username/password information -->
<div class="container">
	<div class="row">
		<?php 
		if(isset($_SESSION["current_user"])) {
			?>
			<!-- If user is already logged in, simply display welcome info -->
			<h1 class="page-header text-center"><? echo "Welcome " . $_SESSION["current_user"]; ?></h1>
			<form class="form-horizontal" role="form" method="post" action="login.php">
				<div class="form-actions">
					<div class="col-lg-12" align="center">
						<input id="submit" name="logout" type="submit" value="Log Out" class="btn btn-primary">
						<br><br><br>
					</div>
				</div>
			</form>
			<?php 
		} else {
			?>
			<div class="col-md-6 col-md-offset-3">
				<h1 class="page-header text-center">Log in or Register Account</h1>
				<form class="form-horizontal" role="form" method="post" action="login.php">
					<div class="form-group">
						<label for="username" class="col-sm-2 control-label">username</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="username" name="username" placeholder="Enter Your Username" value="<?php if (isset($_POST['username'])) { echo htmlspecialchars($_POST['username']); } else { echo ''; } ?>">
							<?php echo "<p class='text-danger'>$errusername</p>";?>
						</div>
					</div>
					<div class="form-group">
						<label for="password" class="col-sm-2 control-label">password</label>
						<div class="col-sm-10">
							<input type="password" class="form-control" id="password" name="password" placeholder="Enter a Password" value="<?php if (isset($_POST['password'])) { echo htmlspecialchars($_POST['password']); } else { echo ''; } ?>">
							<?php echo "<p class='text-danger'>$errusername</p>";?>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-10 col-sm-offset-2">
							<input id="submit" name="login" type="submit" value="Login" class="btn btn-primary">
							<br><br>
							<input id="submit" name="create_new_account" type="submit" value="Register New Account" class="btn btn-primary">	
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-10 col-sm-offset-2">
							<?php echo $result; ?>	
						</div>
					</div>
				</form>
				<br>
			</div>	
		<?php 
		}
		?>
	</div>
</div>   
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>

<?php

display_bottom_section();

mysqli_close($link);

?>

</body>
</html>