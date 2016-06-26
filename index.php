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
<body>






<?

include($_SERVER['DOCUMENT_ROOT'].'/sql_connect.php');
include($_SERVER['DOCUMENT_ROOT'].'/build_database_tables.php');
include($_SERVER['DOCUMENT_ROOT'].'/buttons_and_menus.php');

global $link;
session_start();
sql_connect();


create_store_table(False);
create_products_table(False);
create_cart_table(False);
create_customers_table(False);
create_descriptions_table(False);


if(!empty($_POST["make_purchase"])) {
	
	
	$customer_id = $_SESSION["current_user"];
	
	
	
	$product_purchased = (int)$_POST["state"];
	$quantity_purchased = (int)$_POST["purchase_quantity"];
	
	$query_text = "INSERT INTO CART (customer_id, product_id, quantity) VALUES (?, ?, ?)";
	$query = $link->prepare($query_text);
	$query->bind_param("sii", $customer_id, $product_purchased, $quantity_purchased);
	if(!$query->execute()) {
		query_error($query_text); return False;
	}
	echo "Product added to cart"; line_break(5);
	
	
}

$query_text = "SELECT * FROM PRODUCTS WHERE photo <> 'Null'";
$query = $link->prepare($query_text);
if(!$query->execute()) {
	query_error($query_text); return False;
}
$results = get_all_results_2d_array($query, 'both');

$list_of_items_with_photo = [];

if ($results) {
	foreach($results as $photo_result) {
		array_push($list_of_items_with_photo, $photo_result);
	}
}

$number_of_tiers = (int)(count($list_of_items_with_photo) / 3);


?>






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
        <li class="active"><a href="index.php">Home</a></li>
        <li><a href="products.php">Products</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
	  
        <li><a href="login.php"><span class="glyphicon glyphicon-user"></span><?php if(isset($_SESSION["current_user"])) { echo " My Account (" . $_SESSION["current_user"] . ")"; } else { echo " Login"; } ?></a></li>
        <li><a href="cart.php"><span class="glyphicon glyphicon-shopping-cart"></span> Cart</a></li>
      </ul>
    </div>
  </div>
</nav>
  
   
<?php 
$current_image_index = 0;

for ($i = 0; $i < $number_of_tiers; $i++) { ?>
	<div class="container">
	  <div class="row">
	  <?php for ($j = 0; $j < 3; $j++) {
		  if (isset($list_of_items_with_photo[$current_image_index])) { ?>
			<div class="col-sm-4">
			  <div class="panel panel-primary">
				<div class="panel-heading"><?php echo $list_of_items_with_photo[$current_image_index]['product_name'] . "... only $" . $list_of_items_with_photo[$current_image_index]['retail'] . "!"; ?></div>
				<div class="panel-body"><img src="<?php echo '/photos/' . $list_of_items_with_photo[$current_image_index]['product_id'] . ".jpg"; ?>" class="img-responsive" style="width:100%"></div>
				<div class="panel-footer"><?php echo generate_random_description(); ?></div>
			  </div>
			</div>
		<?php 
			$current_image_index++;
		} ?>
	<?php } ?>
	</div><br>
</div>
<?php } ?>




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

