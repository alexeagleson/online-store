<?php

function display_top_section() {
	?>
	<title>Grocery Store Sample</title>
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
	</head>	
	
	<div class="jumbotron">
		<div class="container text-center">
			<h1 style ="font-family:Curlz MT">Dogman's Grocery Doghouse</h1>
			<p style ="font-family:Freestyle Script; font-size:36px">Come Share our Love for Delicious Food or Whatever</p>
		</div>
	</div>
	<?php
}

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
					<li class="active"><a href="index.php">Home</a></li>
					<li><a href="products.php">Products</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li><a href="login.php"><span class="glyphicon glyphicon-user"></span><?php if(isset($_SESSION["current_user"])) { echo " My Account (" . $_SESSION["current_user"] . ")"; } else { echo " Login"; } ?></a></li>
					<?php $cart_count = number_of_items_in_cart(); ?>
					<li><a href="cart.php"><span class="glyphicon glyphicon-shopping-cart"></span><?php if($cart_count) { echo " Cart(" . $cart_count . ")"; } else { echo " Cart"; } ?></a></li>
				</ul>
			</div>
		</div>
	</nav>
	<?php
}

function display_bottom_section() {
	?>
	<footer class="container-fluid text-center">
	<p>Online Store Copyright</p>
	<form class="form-inline">Get deals:
		<input type="email" class="form-control" size="50" placeholder="Email Address">
		<button type="button" class="btn btn-danger">Sign Up</button>
	</form>
	</footer>
	<?php
}

?>