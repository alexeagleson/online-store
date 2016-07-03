<?php 

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
	
	// Get database info
	$ini_array = parse_ini_file("info.ini");
	
	// SQL INITIALIZATION
	$link = mysqli_connect($ini_array['ip'], $ini_array['database'], $ini_array['password'], $ini_array['user']);

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

?>