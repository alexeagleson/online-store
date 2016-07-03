<?php 

// Displays number of HTML line breaks equal to value of argument
function line_break($number_of_breaks) {
	for ($i = 1; $i <= $number_of_breaks; $i++) {
		?><br><?php
	}
}

// Return an integer indicating the number of items in the session user's shopping cart
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

// Generate a random product description - kind of a joke feature, so won't bother describing every step
function generate_random_description() {
	global $link;

	$query_text = "SELECT * FROM DESCRIPTIONS";
	$query = $link->prepare($query_text);
	if(!$query->execute()) {
		query_error($query_text); return False;
	}
	$results = get_all_results_2d_array($query);
	
	$finish_options = ['savory indulgence', 'delight', 'delicacy', 'taste sensation', 'adventure', 'sensory explosion', 'flavour burst', "experience that you won't forget", 'meal for a great price', 'ingredient that fits any recipe', 'beloved family favourite', 'dish for people on-the-go'];
	$pairs_options = ['pairs great with', 'try it with', 'a wonderful complement to', 'enhances the subtle flavour of', 'best tried in combination with', 'try sprinkling it on', 'layer it with some'];
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

?>