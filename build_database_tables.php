<?php

// Create the STORE table in the database from scratch
function create_store_table($delete_existing = False)  {
	
	// Will delete the existing table if "True" is passed as an argument
	if ($delete_existing){
		$query_text = "DROP TABLE STORE";
		if (!run_basic_query($query_text)) {
			echo "Deleted existing table failed."; line_break(2);
		} else {
			echo "Table deleted."; line_break(2);
		}
	}
	
	// Create the table
	$query_text = "CREATE TABLE STORE
				(store_id int,
				store_name varchar(255))";
		
	// Run the query, if it fails to make the table, print an error to the screen
	$results = run_basic_query($query_text);
	if ($results) { return True; } else { return False; }
}

// Create the PRODUCTS table in the database from scratch
function create_products_table($delete_existing = False)  {
	
	// Will delete the existing table if "True" is passed as an argument
	if ($delete_existing){
		$query_text = "DROP TABLE PRODUCTS";
		if (!run_basic_query($query_text)) {
			echo "Deleted existing table failed."; line_break(2);
		} else {
			echo "Table deleted."; line_break(2);
		}
	}
	
	// Create the table
	$query_text = "CREATE TABLE PRODUCTS
				(product_id int,
				product_name varchar(255),
				cost decimal(4,2),
				retail decimal(4,2),
				aisle int)";
		
	// Run the query, if it fails to make the table, print an error to the screen
	$results = run_basic_query($query_text);
	if ($results) { 
	
		// Enter the product information into the table
		if (populate_products_from_csv()) {
			return True;
		}
	}
	
	return False;
}

// Fill in the PRODUCTS table with values form the CSV
function populate_products_from_csv() {
	global $link;
	
	$all_product_info = array_map('str_getcsv', file('product_assortment.csv'));
	//var_dump($all_product_info);
	
	// Loops through all of the info in the CSV and enter it into database.  First line (i = 0) contains header info
	for ($i = 1; $i < count($all_product_info); $i++) {
		$product_id = (int)$all_product_info[$i][0];
		$product_name = $all_product_info[$i][1];
		$cost =(float)$all_product_info[$i][2];
		$retail = (float)$all_product_info[$i][3];
		$aisle = (int)$all_product_info[$i][4];
			
		// Get all relevant data about this object
		$query_text = "INSERT INTO PRODUCTS (product_id, product_name, cost, retail, aisle) VALUES (?, ?, ?, ?, ?)";
		$query = $link->prepare($query_text);
		$query->bind_param("isddi", $product_id, $product_name, $cost, $retail, $aisle);
		if(!$query->execute()) {
			query_error($query_text); return False;
		}
	}
	
	// If we've reached this point, everything worked
	return True;
}



	
?>