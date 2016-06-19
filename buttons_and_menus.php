<?php

function basic_label($text, $for_id) {
	// Creates a simple label
	?>
	<label for="<?php echo $for_id ?>"> <?php echo $text ?> </label>
	<?php 
}

function basic_button($button_name, $button_id, $button_text) {
	// Creates a simple button that posts to $button_id and displays $button_name on the button
	?>
	<form action="index.php" method="post">
	<input type="submit" id="<?php echo $button_id ?>" name="<?php echo $button_name ?>" value="<?php echo $button_text ?>">
	<?php 
}


function basic_text_field($field_name, $field_id, $field_text) {
	// Creates a simple text box for user input
	?>
	<form action="index.php" method="post">
	<input type="text" id="<?php echo $field_id ?>" name="<?php echo $field_name ?>" value="<?php echo $field_text ?>">
	<?php
}

function basic_text_field_hidden($field_name, $field_id, $field_text) {
	// Creates a simple text box for user input
	?>
	<form action="index.php" method="post">
	<input type="password" id="<?php echo $field_id ?>" name="<?php echo $field_name ?>" value="<?php echo $field_text ?>">
	<?php
}

function basic_drop_menu($menu_name, $menu_id, $menu_return_values, $menu_options, $default_option = 5, $function = '') {
	// Creates a simple drop down menu with choices based on array $menu_options
	?>
	<form action="index.php" method="post">
	<select id="<?php echo $menu_id ?>" name="<?php echo $menu_name ?>" onchange="<?php echo $function ?>">
		<?php
		$number_of_options = count($menu_return_values);
		for ($i = 0; $i < $number_of_options; $i++) {
			if ($default_option == $i) {
				?>
				<option selected="selected" value = "<?php echo $menu_return_values[$i] ?>" > <?php echo $menu_options[$i] ?> </option>
				<?php	
			} else {
				?>
				<option value = "<?php echo $menu_return_values[$i] ?>" > <?php echo $menu_options[$i] ?> </option>
				<?php
			}
		}
		?>
	</select>
	<?php
}

	
?>