<?php


include($_SERVER['DOCUMENT_ROOT'].'/sql_connect.php');
include($_SERVER['DOCUMENT_ROOT'].'/build_database_tables.php');
include($_SERVER['DOCUMENT_ROOT'].'/buttons_and_menus.php');


global $link;

session_start();
sql_connect();




?>
<html>
<head>
<TITLE>jQuery Dependent DropDown List - Countries and States</TITLE>
<head>


<style>
body {
	width:610px;
}
.frmDronpDown {
	border: 1px solid #F0F0F0;
	background-color:#C8EEFD;
	margin: 2px 0px;padding:40px;
}
.demoInputBox {
	padding: 10px;
	border: #F0F0F0 1px solid;
	border-radius: 4px;
	background-color: #FFF;
	width: 50%;
}
.row {
	padding-bottom:15px;
}
</style>


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
</head>
<body>



<?

if(!empty($_POST["make_purchase"])) {
	
	var_dump($_POST); line_break(5);
	
	$customer_id = 0;
	$product_purchased = (int)$_POST["state"];
	$quantity_purchased = (int)$_POST["purchase_quantity"];
	
	$query_text = "INSERT INTO CART (customer_id, product_id, quantity) VALUES (?, ?, ?)";
	$query = $link->prepare($query_text);
	$query->bind_param("iii", $customer_id, $product_purchased, $quantity_purchased);
	if(!$query->execute()) {
		query_error($query_text); return False;
	}
	echo "Product added to cart";
	
	
}






create_store_table();
create_products_table();
create_cart_table();



// Get all relevant data about this object
$query_text = "SELECT product_name, SUM(quantity) AS total_quantity, (cost * SUM(quantity)) AS total_cost FROM CART INNER JOIN PRODUCTS ON CART.product_id = PRODUCTS.product_id GROUP BY product_name";
$query = $link->prepare($query_text);
if(!$query->execute()) {
	query_error($query_text); return False;
}
$results = get_all_results_2d_array($query, 'both');

if (!$results) { 
	query_error($query); return False;
} else {
	$grand_total = 0;
	foreach($results as $item_in_cart) {
		$grand_total += $item_in_cart['total_cost'];
		echo $item_in_cart['product_name'] . ' x ' . $item_in_cart['total_quantity'] . ': Total $' . $item_in_cart['total_cost']; line_break(1);
	}
	line_break(2);
	echo "Grand Total: $" . $grand_total;
	line_break(2);
}










// Get all relevant data about this object
$query_text = "SELECT DISTINCT aisle FROM PRODUCTS";
$query = $link->prepare($query_text);
if(!$query->execute()) {
	query_error($query_text); return False;
}
$results = get_all_results_2d_array($query, 'both');

if (!$results) { query_error($query); return False; }







?>



<form action="index.php" method="post">
<div class="frmDronpDown">
	<div class="row">
		<label>Aisle:</label><br/>
		<select name="country" id="country-list" class="demoInputBox" onChange="getState(this.value);">
			<option value="">Select Aisle</option>
			<?php
			$i = 1;
			foreach($results as $country) {
				?>
				<option value="<?php echo $country["aisle"]; ?>"><?php echo 'Aisle ' . $i; ?></option>
				<?php
				$i++;
			}
			?>
		</select>
	</div>
	<div class="row">
		<label>Product:</label><br/>
		<select name="state" id="state-list" class="demoInputBox" onChange="getProduct(this.value);">
			<option value="">Select Product</option>
		</select>
	</div>
	<div class="row">
		<label name="state" id="product-info">
	</div>
	
	<?php
	line_break(2);
	
	?>
	<label>Quantity:</label><br/><br/>
	<?php
	$options = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
	basic_drop_menu('purchase_quantity', 'purchase_quantity_id', $options, $options);
	basic_button('make_purchase', 'make_purchase_id', 'Purchase');
	?>
	

</div>
</form>
		
		
<?php mysqli_close($link); ?>


</body>
</html>