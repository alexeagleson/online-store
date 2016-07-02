<?php 

function line_break($number_of_breaks) {
	// Displays number of HTML line breaks equal to value of argument
	for ($i = 1; $i <= $number_of_breaks; $i++) {
		?><br><?php
	}
}

function query_error($query) {
	// Displays the query to the screen that didn't work
	line_break(2);
	if (is_string($query)) {
		echo "THIS QUERY FAILED: " . $query;
	} else {
		echo "PROBLEM WITH SQL QUERY in sql_connect - can't print it out!";
	}
	
	line_break(2);
}

function sql_connect() {
	global $link;
	
	// SQL INITIALIZATION
	$link = mysqli_connect("50.62.209.43", "sales_tool", "nqN6v7^4", "aeagleso_");

	// check connections
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}
}

function run_basic_query($query_text) {
	global $link;
	
	$query = $link->prepare($query_text);
	if(!$query->execute()) {
		query_error($query_text); return False;
	}
	return $query;
}

// Returns a 2d array with all results usable numerically or associatively
function get_all_results_2d_array($query, $fetch_method = 'num') {
	$result = $query->get_result();
	
	$new_array = array();
	while ($row = $result->fetch_array(MYSQLI_BOTH)) {
		if ($fetch_method == 'both') {
			$new_array[] = $row;
		} else if ($fetch_method == 'num') {
			$new_array[] = $row[0];
		}
		
	}
	return $new_array;
}

// Returns True if a given table name already exists, False if not
function check_if_table_exists($table_name) {
	global $link;

	$query_text = "SELECT COUNT(*) FROM information_schema.tables WHERE table_name = ?";
	$query = $link->prepare($query_text);
	$query->bind_param("s", $table_name);
	if(!$query->execute()) {
		query_error($query_text); return False;
	}
	$results = get_all_results_2d_array($query);
	if (count($results) > 0) {
		return True;
	}
	return False;
}

// Generate a random product description
function generate_random_description() {
	global $link;

	$query_text = "SELECT * FROM DESCRIPTIONS";
	$query = $link->prepare($query_text);
	if(!$query->execute()) {
		query_error($query_text); return False;
	}
	$results = get_all_results_2d_array($query);
	

	
	$finish_options = ['taste sensation', 'adventure', 'sensory explosion', 'flavour burst', "experience that you won't forget", 'meal for a great price', 'ingredient that fits any recipe', 'beloved family favourite', 'dish for people on-the-go'];
	$pairs_options = ['pairs great with', 'try it with', 'a wonderful complement to', 'enhances the subtle flavour of', 'best tried in combination with'];
	$vowels = ['a', 'e', 'i', 'o', 'u'];
	
	
	$random_adjective_1 = rand(0, (count($results) - 1));
	$random_adjective_2 = rand(0, (count($results) - 1));
	$random_adjective_3 = rand(0, (count($results) - 1));
	$random_finisher = rand(0, (count($finish_options) - 1));
	$random_pairs = rand(0, (count($pairs_options) - 1));
	
	
	$a_or_an = "A";
	foreach($vowels as $this_vowel) {
		if ($results[$random_adjective_1][0] == $this_vowel) {
			$a_or_an = "An";
			break;
		}
	}
	
	
	if (rand(0,2) == 0) {
		$a_or_an = "Try this";
	}
	
	
	$pairing = '';
	if (rand(0,2) == 0) {
		
		$query_text = "SELECT product_name FROM PRODUCTS ORDER BY RAND() LIMIT 1";
		$query = $link->prepare($query_text);
		if(!$query->execute()) {
			query_error($query_text); return False;
		}
		$random_product = get_all_results_2d_array($query);
		
		
		$pairing = ", " . $pairs_options[$random_pairs] . " " . $random_product[0];
	}
	
	$period_or_mark = '.';
	if (rand(0,2) == 0) {
		$period_or_mark = '!';
	}
	
	
	$description = $a_or_an . " " . $results[$random_adjective_1] . " and " . $results[$random_adjective_2] . " " . $finish_options[$random_finisher] . $pairing . ".";

	return $description;
	
		
}


function number_of_items_in_cart() {
	global $link;
	
	if (isset($_SESSION["current_user"])) {
		
		$customer_id = $_SESSION["current_user"];
		
		// Get all relevant data about this object
		$query_text = "SELECT SUM(quantity) AS total_quantity FROM CART WHERE customer_id = ?";
		$query = $link->prepare($query_text);
		$query->bind_param("s", $customer_id);
		if(!$query->execute()) {
			query_error($query_text); return False;
		}
		if (!$query) { return False; }
		
		$results = get_all_results_2d_array($query);
		if (!$results) { return False; }
		
		return $results[0];
		
	} else {
		return False;
	}
	
	
}





?>