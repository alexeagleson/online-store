<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/sql_connect.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/general_functions.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/buttons_and_menus.php');

global $link;

sql_connect();

// This function is called through AJAX from products.php - the selected category determines the list of products in the products drop menu
if(!empty($_POST["category_id"])) {
	
	$query_text = "SELECT * FROM PRODUCTS WHERE category = ? ORDER BY product_name";
	$query = $link->prepare($query_text);
	$query->bind_param("i", $_POST["category_id"]);
	if(!$query->execute()) {
		query_error($query_text); return False;
	}
	$results = get_all_results_2d_array($query, 'both');

	if (!$results) { query_error($query); return False; }

	?>

	<option value="">Select Product</option>
	
	<?php
	// Create the drop list of products based on the results of the query by product category
	foreach($results as $product) {
		?>
		<option value="<?php echo $product["product_id"]; ?>"><?php echo $product["product_name"] . " ($" . $product["retail"] . ")"; ?></option>
		<?php
	}
}

mysqli_close($link);

?>