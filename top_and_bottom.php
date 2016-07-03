<?php

global $store_name;
global $store_tagline;

$store_name = "Super Grocery World";
$store_tagline = "Come Share our Love for Delicious Food or Whatever";

// Code for displaying the large header at the top of the screen including the store name and tagline
function display_top_section() {
	
	global $store_name;
	global $store_tagline;
	?>
	<title><?php echo $store_name; ?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	<style>
		/* Remove the navbar's default rounded borders and increase the bottom margin */
		.navbar {
			margin-bottom: 30px;
			border-radius: 0;
		}

		/* Remove the jumbotron's default bottom margin */
			.jumbotron {
			margin-bottom: 0;
			padding: 10px;
		}

		/* Add a gray background color and some padding to the footer */
		footer {
			background-color: #f2f2f2;
			padding: 25px;
		}
	</style>
	
	</head>	
	
	<div class="jumbotron">
		<div class="container text-center">
			<h1 style ="font-family: Georgia, sans-serif;"><?php echo $store_name; ?></h1>
			<h3 style ="font-family: Tahoma, Geneva, sans-serif; font-style: italic;"><?php echo $store_tagline; ?></h2>
		</div>
	</div>
	<?php
}

// Displays the main nav bar including the home, products, login in and cart buttons.  Is collapsable when shrinking for smaller screens
function display_nav_bar() {
	?>
	<nav class="navbar navbar-inverse">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>
			<div class="collapse navbar-collapse" id="myNavbar">
				<ul class="nav navbar-nav">
					<li><a href="index.php">Home</a></li>
					<li><a href="products.php">Products</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li><a href="login.php"><span class="glyphicon glyphicon-user"></span><?php if(isset($_SESSION["current_user"])) { echo " My Account (" . $_SESSION["current_user"] . ")"; } else { echo " Log in"; } ?></a></li>
					<?php $cart_count = number_of_items_in_cart(); ?>
					<li><a href="cart.php"><span class="glyphicon glyphicon-shopping-cart"></span><?php if($cart_count) { echo " Cart(" . $cart_count . ")"; } else { echo " Cart"; } ?></a></li>
				</ul>
			</div>
		</div>
	</nav>
	<?php
}

// Displays the section prompting user to enter their email address for a weekly flyer
function display_bottom_section() {
	global $store_name;
	global $store_tagline;
	?>
	<footer class="container-fluid text-center">
	<label>Get Our Weekly Flyer::</label>
	<br>
	<form class="form-inline" method="post">
		<input type="email" class="form-control" size="50" placeholder="Email Address (nothing will actually be mailed)">
		<input type="submit" value="Sign Up" class="btn btn-danger">
		<br>
	</form>
	</footer>
	<?php
}

?>