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
</head>

<?php

include($_SERVER['DOCUMENT_ROOT'].'/sql_connect.php');
include($_SERVER['DOCUMENT_ROOT'].'/build_database_tables.php');
include($_SERVER['DOCUMENT_ROOT'].'/buttons_and_menus.php');

global $link;
session_start();
sql_connect();

?>

<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
<script>
function getState(val) {
	$.ajax({
	type: "POST",
	url: "get_state.php",
	data:'country_id='+val,
	success: function(data){
		$("#state-list").html(data);
	}
	});
}

function getProduct(val) {
	$.ajax({
	type: "POST",
	url: "get_state.php",
	data:'product_id='+val,
	success: function(data){
		$("#product-info").html(data);
	}
	});
}

function selectCountry(val) {
	$("#search-box").val(val);
	$("#suggesstion-box").hide();
}
</script>


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
        <li class="active"><a href="products.php">Products</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="login.php"><span class="glyphicon glyphicon-user"></span><?php if(isset($_SESSION["current_user"])) { echo " My Account (" . $_SESSION["current_user"] . ")"; } else { echo " Login"; } ?></a></li>
        <li><a href="cart.php"><span class="glyphicon glyphicon-shopping-cart"></span> Cart</a></li>
      </ul>
    </div>
  </div>
</nav>


<?php 

// Get all relevant data about this object
$query_text = "SELECT DISTINCT category FROM PRODUCTS ORDER BY category";
$query = $link->prepare($query_text);
if(!$query->execute()) {
	query_error($query_text); return False;
}
$results = get_all_results_2d_array($query, 'both');

if (!$results) { query_error($query); return False; }

?>

  

   
<form class="form-horizontal" role="form" method="post" action="index.php">
	<div class="form-group">
		<label for="category" class="col-sm-2 control-label">Category</label>
		<div class="col-sm-10">
			<select name="country" id="country-list" class="demoInputBox" onChange="getState(this.value);">
				<option value="">Select Category</option>
				<?php
				foreach($results as $country) {
					?>
					<option value="<?php echo $country["category"]; ?>"><?php echo 'Category ' . $country["category"]; ?></option>
					<?php
				}
				?>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label for="product" class="col-sm-2 control-label">Product</label>
		<div class="col-sm-10">
			<select name="state" id="state-list" class="demoInputBox" onChange="getProduct(this.value);">
				<option value="">Select Product</option>
			</select>
		</div>
	</div>
	
	<?php if (isset($_SESSION["current_user"])) { ?>
		<div class="form-group">
			<label for="quantity" class="col-sm-2 control-label">Quantity</label>
			<div class="col-sm-10">
				<?php
				$options = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
				basic_drop_menu('purchase_quantity', 'purchase_quantity_id', $options, $options);
				?>
			</div>
		</div>
		
		<div class="form-group">
			<label for="purchase" class="col-sm-2 control-label"></label>
			<div class="col-sm-10">
				<?php
				basic_button('make_purchase', 'make_purchase_id', 'Purchase', $return_to_page = 'products.php');
				?>
			</div>
		</div>
	<?php } else { 
		echo "Please log in";
	} ?>
	
	
</form> 
   
   
  
   
   
   
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

