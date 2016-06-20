
<?php

$errusername = '';
$errEmail = 'Just enter a fake one, nothing actually gets emailed.';
$errPassword = '';
$username = '';
$email = '';
$password = '';
$from = '';
$to = '';
$subject = '';
$result = '';

	if (isset($_POST["login"])) {
		echo "hello!";
		$username = $_POST['username'];
		$email = $_POST['email'];
		$password = $_POST['password'];
		$from = 'Demo Contact Form'; 
		$to = $_POST['email'];
		$subject = 'Password from Contact Demo ';
		
		$body ="From: $username\n E-Mail: $email\n Password:\n $password";
		// Check if username has been entered
		if (!$_POST['username']) {
			$errusername = 'Please enter your username';
		}
		
		// Check if email has been entered and is valid
		if (!$_POST['email'] || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
			$errEmail = 'Please enter a valid fake email address';
		}
		
		//Check if password has been entered
		if (!$_POST['password']) {
			$errPassword = 'Please enter your password';
		}

		
		// If there are no errors, send the email
		if (!$errusername && !$errEmail && !$errPassword) {
			if (mail ($to, $subject, $body, $from)) {
				$result='<div class="alert alert-success">Thank You! I will be in touch</div>';
			} else {
				$result='<div class="alert alert-danger">Sorry there was an error sending your password. Please try again later.</div>';
			}
		}
	}
?>













<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <style>
    /* Remove the navbar's default rounded borders and increase the bottom margin */
    .navbar {
      margin-bottom: 50px;
      border-radius: 0;
    }
    
    /* Remove the jumbotron's default bottom margin */
     .jumbotron {
      margin-bottom: 0;
    }
   
    /* Add a gray background color and some padding to the footer */
    footer {
      background-color: #f2f2f2;
      padding: 25px;
    }
  </style>
  
  
  
  
     <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Bootstrap contact form with PHP example by BootstrapBay.com.">
    <meta name="author" content="BootstrapBay.com">
    <title>Bootstrap Contact Form With PHP Example</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
  
  
  
  
</head>

<?php

include($_SERVER['DOCUMENT_ROOT'].'/sql_connect.php');
include($_SERVER['DOCUMENT_ROOT'].'/build_database_tables.php');
include($_SERVER['DOCUMENT_ROOT'].'/buttons_and_menus.php');

global $link;
session_start();
sql_connect();

?>


<body>

<div class="jumbotron">
  <div class="container text-center">
    <h1>Online Store</h1>
    <p>Mission, Vission & Values</p>
  </div>
</div>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">Logo</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li><a href="index.php">Home</a></li>
        <li><a href="products.php">Products</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="login.php"><span class="glyphicon glyphicon-user"></span> Your Account</a></li>
        <li><a href="cart.php"><span class="glyphicon glyphicon-shopping-cart"></span> Cart</a></li>
      </ul>
    </div>
  </div>
</nav>






  	<div class="container">
  		<div class="row">
  			<div class="col-md-6 col-md-offset-3">
  				<h1 class="page-header text-center">Contact Form Example</h1>
				<form class="form-horizontal" role="form" method="post" action="login.php">
					<div class="form-group">
						<label for="username" class="col-sm-2 control-label">username</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="username" name="username" placeholder="Enter Your Desired Username" value="<?php if (isset($_POST['username'])) { echo htmlspecialchars($_POST['username']); } else { echo ''; } ?>">
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
						<label for="email" class="col-sm-2 control-label">Email</label>
						<div class="col-sm-10">
							<input type="email" class="form-control" id="email" name="email" placeholder="example@domain.com" value="<?php if (isset($_POST['email'])) { echo htmlspecialchars($_POST['email']); } else { echo ''; } ?>">
							<?php echo "<p class='text-danger'>$errEmail</p>";?>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-10 col-sm-offset-2">
							<input id="submit" name="login" type="submit" value="Login" class="btn btn-primary">
							<input id="submit" name="create_new_account" type="submit" value="Create New Account" class="btn btn-primary">
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-10 col-sm-offset-2">
							<?php echo $result; ?>	
						</div>
					</div>
				</form> 
			</div>
		</div>
	</div>   
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
 
   
   
<footer class="container-fluid text-center">
  <p>Online Store Copyright</p>
  <form class="form-inline">Get deals:
    <input type="email" class="form-control" size="50" placeholder="Email Address">
    <button type="button" class="btn btn-danger">Sign Up</button>
  </form>
</footer>

<?php mysqli_close($link); ?>

</body>
</html>

