<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/sql_connect.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/buttons_and_menus.php');

global $link;

sql_connect();

if(!empty($_POST["country_id"])) {
	
	$query_text = "SELECT * FROM PRODUCTS WHERE aisle = ?";
	$query = $link->prepare($query_text);
	$query->bind_param("i", $_POST["country_id"]);
	if(!$query->execute()) {
		query_error($query_text); return False;
	}
	$results = get_all_results_2d_array($query, 'both');

	if (!$results) { query_error($query); return False; }
	
	?>
	
	<option value="">Select Product</option>
	
	<?php
	foreach($results as $state) {
		?>
		<option value="<?php echo $state["product_id"]; ?>"><?php echo $state["product_name"]; ?></option>
		<?php
	}
} else if(!empty($_POST["product_id"])) {
	
	$query_text = "SELECT cost FROM PRODUCTS WHERE product_id = ?";
	$query = $link->prepare($query_text);
	$query->bind_param("i", $_POST["product_id"]);
	if(!$query->execute()) {
		query_error($query_text); return False;
	}
	$results = get_all_results_2d_array($query, 'both');

	if (!$results) { query_error($query); return False; }
	
	?>
	<label>Cost:</label><br/><br/>
	<?php
	
	foreach($results as $state) {
		echo $state["cost"];
	}
	

}

mysqli_close($link);

?>