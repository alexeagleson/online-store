<!DOCTYPE html>
<html lang="en">
<head>
<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
<script>
	// Uses AJAX to dynamically popular the dropdown of products based on the category selection
	function getproduct(val) {
		$.ajax({
		type: "POST",
		url: "get_product.php",
		data:'category_id='+val,
		success: function(data){
			$("#product-list").html(data);
		}
		});
	}
	
	// Makes the purchase confirmation button only appear when a product is selected
	function show_hide_purchase_section(val) {
		if (val > 0) {
			document.getElementById('this_stuff').style.visibility = "visible";
		} else {
			document.getElementById('this_stuff').style.visibility = "hidden";
		}
	}
</script>

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

// If user made a purchase, add the item to the user's cart
if(!empty($_POST["make_purchase"])) {
	$customer_id = $_SESSION["current_user"];
	$product_purchased = (int)$_POST["product"];
	$quantity_purchased = (int)$_POST["purchase_quantity"];
	
	$query_text = "INSERT INTO CART (customer_id, product_id, quantity) VALUES (?, ?, ?)";
	$query = $link->prepare($query_text);
	$query->bind_param("sii", $customer_id, $product_purchased, $quantity_purchased);
	if(!$query->execute()) {
		query_error($query_text); return False;
	}
}

display_nav_bar();

if(!empty($_POST["make_purchase"])) {
	?>
	<div align = "center">
		<label>Product has been added to your cart.</label>
	</div>
	<br>
	<?php
}

?>

</head>
<body>

<?php 

// Get a list of all different categories in the database to create the first drop menu
$query_text = "SELECT DISTINCT category FROM PRODUCTS ORDER BY category";
$query = $link->prepare($query_text);
if(!$query->execute()) {
	query_error($query_text); return False;
}
$results = get_all_results_2d_array($query, 'both');

if (!$results) { query_error($query); return False; }

?>

<!-- This form includes the drop menus for category, product, number of product to purchase, and the "Purchase" button -->
<form method="post" action="products.php" class="form-inline" role="form">
	<div class="container">
		<div class="row">
			<div class="col-xs-9">
				<div class="row">
					<div class="col-xs-6 control-label" align = "right">
						<label for="category">category</label>
					</div>
					<div class="col-xs-6">
						<select name="country" id="country-list" class="demoInputBox" onChange="getproduct(this.value);">
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
				<div class="row">
					<div class="col-xs-6 control-label" align = "right">
						<label for="product">product</label>
					</div>
					<div class="col-xs-6">
						<!-- Products are populated dynamically through AJAX call to get_product.php -->
						<select name="product" id="product-list" class="demoInputBox" onChange="show_hide_purchase_section(this.value);">
							<option value="">Select Product</option>
						</select>
					</div>
				</div>
				<div id = "this_stuff">
					<?php
					if (isset($_SESSION["current_user"])) { ?>
						<div class="col-xs-6 control-label" align = "right">
							<label for="quantity">quantity</label>
						</div>
						<div class="col-xs-6">
							<?php
							$options = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
							basic_drop_menu('purchase_quantity', 'purchase_quantity_id', $options, $options);
							?>
						</div>
						<div class="col-xs-6 control-label" align = "center">
							<label for="purchase"></label>
							<div align = "left">
								<?php basic_button('make_purchase', 'make_purchase_id', 'Purchase', $return_to_page = 'products.php'); ?>
							</div>
						</div>
						<div class="col-xs-6">
							<br>
						</div>
					<?php } else { 
						?>
						<div class="col-xs-6 control-label" align = "right">
						</div>
						<div class="col-xs-6 control-label" align = "left">
							<label for="log_in">Please log in to purchase.</label>
						</div>
						<div class="col-xs-6 control-label" align = "center">
						</div>
						<div class="col-xs-6">
							<br>
						</div>
						<?php
					} ?>
				</div>
			</div>
			<div class="col-xs-3">
			</div>
		</div>
		<div class="row" align = "center">
			<div class="col-xs-3">
			</div>
			<div class="col-xs-6">
				<div class="panel panel-primary">
					<div class="panel-body">
						<!-- Displays the generic produce photo by default - displays the item photo if available -->
						<img src="<?php echo '/photos/sample_photo.jpg'; ?>" class="img-responsive" style="width:100%" onerror="if (this.src != '/photos/image-not-found.png') this.src = '/photos/image-not-found.png';">
					</div>
				</div>
			</div>
			<div class="col-xs-3">
			</div>
		</div>
	</div>
</form>

<script>
	// Dynamically displays the photo of the item selected in the product drop menu
	document.getElementById('this_stuff').style.visibility = "hidden";
	$('#product-list').change(function () {
		var val = parseInt($('#product-list').val());
		val = String(val);
		var image_path = val.concat(".jpg");
		image_path = '/photos/'.concat(image_path);
		$('img').attr("src", image_path);
	});
</script>
   
<?php
   
display_bottom_section();

mysqli_close($link);

?>

</body>
</html>

