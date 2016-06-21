
<?php

include($_SERVER['DOCUMENT_ROOT'].'/sql_connect.php');
include($_SERVER['DOCUMENT_ROOT'].'/build_database_tables.php');
include($_SERVER['DOCUMENT_ROOT'].'/buttons_and_menus.php');

global $link;
session_start();
sql_connect();


create_store_table();
create_products_table();
create_cart_table();

$errusername = '';
$errPassword = '';
$username = '';
$password = '';
$from = '';
$to = '';
$subject = '';
$result = '';


if (isset($_POST["logout"])) {
	unset($_SESSION["current_user"]);

} else if (isset($_POST["login"])) {

	// Check if customer exists
	
	$username_entered = strtolower($_POST['username']);
	
	$query_text = "SELECT * FROM CUSTOMERS WHERE customer_id = ?";
	$query = $link->prepare($query_text);
	$query->bind_param("s", $username_entered);
	if(!$query->execute()) {
		query_error($query_text); return False;
	}
	$results = get_all_results_2d_array($query, 'both');

	if (!$results) { 
		echo "CUSTOMER DOESn'T EXIST!!!";
	} else {
		$results = $results[0];
		
		if ($results['password'] == md5($_POST['password'])) {
			echo "LOGIN SUCCESS";
			$_SESSION["current_user"] = $username_entered;
		} else {
			echo "PASSWORD INCORRECT";
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

	// Check if customer exists
	
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
		
		echo "REGISTER SUCCESS!";
		
		$_SESSION["current_user"] = $username_entered;
	
	
	
	
	
	
	} else {
		echo "USERNAME ALREADY TAKNE!!";
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
	
	.form-actions {
		margin: 0;
		background-color: transparent;
		text-align: center;
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
        <li><a href="login.php"><span class="glyphicon glyphicon-user"></span><?php if(isset($_SESSION["current_user"])) { echo " My Account (" . $_SESSION["current_user"] . ")"; } else { echo " Login"; } ?></a></li>
        <li><a href="cart.php"><span class="glyphicon glyphicon-shopping-cart"></span> Cart</a></li>
      </ul>
    </div>
  </div>
</nav>






  	<div class="container">
  		<div class="row">
		
			<?php if(isset($_SESSION["current_user"])) { ?>
					<h1 class="page-header text-center"><? echo "Welcome " . $_SESSION["current_user"]; ?></h1>
					<form class="form-horizontal" role="form" method="post" action="login.php">
						<div class="form-actions">
							<div class="col-lg-12">
								<input id="submit" name="logout" type="submit" value="Log Out" class="btn btn-primary">
								<br><br>
							</div>
						</div>
					</form>
			<?php } else { ?>

			
			
			
				<div class="col-md-6 col-md-offset-3">
					<h1 class="page-header text-center">Contact Form Example</h1>
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
			<?php } ?>
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

