<!DOCTYPE html>
<html lang="en">
<head>

<?php

include($_SERVER['DOCUMENT_ROOT'].'/sql_connect.php');
include($_SERVER['DOCUMENT_ROOT'].'/general_functions.php');
include($_SERVER['DOCUMENT_ROOT'].'/build_database_tables.php');
include($_SERVER['DOCUMENT_ROOT'].'/buttons_and_menus.php');
include($_SERVER['DOCUMENT_ROOT'].'/top_and_bottom.php');

global $link;
session_start();
sql_connect();

// Creates all the tables in the SQL database from scratch - if False only creates if they don't exist, if True deletes and re-creates
build_all_database_tables(False);

display_top_section();
display_nav_bar();

?>

</head>
<body> 
   
<?php 

$current_image_index = 0;
$list_of_items_with_photo = [];

// Display on the main page, all items in the database with a photo, rounded to the nearest 3
$query_text = "SELECT * FROM PRODUCTS WHERE photo <> 'Null'";
$query = $link->prepare($query_text);
if(!$query->execute()) {
	query_error($query_text); return False;
}
$results = get_all_results_2d_array($query, 'both');

if ($results) {
	foreach($results as $photo_result) {
		array_push($list_of_items_with_photo, $photo_result);
	}
}

$number_of_tiers = (int)(count($list_of_items_with_photo) / 3);

// Displays the featured items on the home page in groups of three
for ($i = 0; $i < $number_of_tiers; $i++) { 
	?>
	<div class="container">
		<div class="row">
		<?php 
		for ($j = 0; $j < 3; $j++) {
			if (isset($list_of_items_with_photo[$current_image_index])) {
				?>
				<div class="col-sm-4">
					<div class="panel panel-primary">
						<div class="panel-heading"><?php echo $list_of_items_with_photo[$current_image_index]['product_name'] . "... only $" . $list_of_items_with_photo[$current_image_index]['retail'] . "!"; ?></div>
						<div class="panel-body"><img src="<?php echo '/photos/' . $list_of_items_with_photo[$current_image_index]['product_id'] . ".jpg"; ?>" class="img-responsive" style="width:100%"></div>
						<div class="panel-footer"><?php echo generate_random_description(); ?></div>
					</div>
				</div>
				<?php 
				$current_image_index++;
			} 
			?>
			<?php
		}
		?>
		</div>
		<br>
	</div>
	<?php
}

display_bottom_section();

mysqli_close($link);

?>

</body>
</html>

