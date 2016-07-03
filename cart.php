<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">

<!-- Bootstrap Core CSS -->
<link href="../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- MetisMenu CSS -->
<link href="../bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

<!-- DataTables CSS -->
<link href="../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">

<!-- DataTables Responsive CSS -->
<link href="../bower_components/datatables-responsive/css/dataTables.responsive.css" rel="stylesheet">

<!-- Custom CSS -->
<link href="../dist/css/sb-admin-2.css" rel="stylesheet">

<!-- Custom Fonts -->
<link href="../bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

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

if (isset($_POST['product_id_to_remove'])) {
	// Get all relevant data about this object
	$query_text = "DELETE FROM CART WHERE product_id = ? AND customer_id = ?";
	$query = $link->prepare($query_text);
	$query->bind_param("is", $_POST['product_id_to_remove'], $_SESSION["current_user"]);
	if(!$query->execute()) {
		query_error($query_text); return False;
	}
}

if (isset($_POST['submit_your_order'])) {
	// Get all relevant data about this object
	$query_text = "DELETE FROM CART WHERE customer_id = ?";
	$query = $link->prepare($query_text);
	$query->bind_param("s", $_SESSION["current_user"]);
	if(!$query->execute()) {
		query_error($query_text); return False;
	}
}

display_nav_bar();
 
?>
  
</head>
<body>

<?php

if ((number_of_items_in_cart() == False) and (isset($_SESSION["current_user"])) and !(isset($_POST['submit_your_order']))) {
	?>
	<div align = "center">
		<label>Your cart is empty.</label>
	</div>
	<br>
	<?php
}

if (isset($_POST['submit_your_order'])) {
	?>
	<div align = "center">
		<label>Thank you for your order.</label>
	</div>
	<br>
	<?php
}

if (isset($_SESSION["current_user"])) {
	$customer_id = $_SESSION["current_user"];
	
	// Get all relevant data about this object
	$query_text = "SELECT CART.product_id, product_name, SUM(quantity) AS total_quantity, retail, (retail * SUM(quantity)) AS total_retail FROM CART INNER JOIN PRODUCTS ON CART.product_id = PRODUCTS.product_id WHERE customer_id = ? GROUP BY product_name";
	$query = $link->prepare($query_text);
	$query->bind_param("s", $customer_id);
	if(!$query->execute()) {
		query_error($query_text); return False;;
	}
	
	$results = get_all_results_2d_array($query, 'both');
	if (!$results) { 
		// Do nothing
	} else {
		$grand_total = 0;
		?>
		<div class="container">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h1 class="page-header">My Shopping Cart</h1>
					</div>
					<div class="panel-body">
						<div class="dataTable_wrapper">
							<table class="table table-striped table-bordered table-hover" id="dataTables-example">
								<thead>
									<tr>
										<th>Product Name</th>
										<th>Unit Price</th>
										<th>Quantity</th>
										<th>Total Price</th>
										<th>Remove</th>
									</tr>
								</thead>
								<tbody>
									<?php
									foreach($results as $item_in_cart) {
										$grand_total += $item_in_cart['total_retail'];
										?>
										<tr>
											<td class="center"><?php echo $item_in_cart['product_name'] ?></td>
											<td class="center"><?php echo "$" . $item_in_cart['retail'] ?></td>
											<td class="center"><?php echo $item_in_cart['total_quantity'] ?></td>
											<td class="center"><?php echo "$" . $item_in_cart['total_retail'] ?></td>
											<td>	
												<form action="cart.php" method="post">
													<input type="hidden" name="product_id_to_remove" value = "<?php echo $item_in_cart['product_id'] ?>">
													<input type="submit" id="remove_item" name="remove_item" value="Remove">
												</form>
											</td>
										</tr>
										<?php
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group" style="font-size:18px">
				<div class="col-sm-6">
					<?php $grand_total = number_format($grand_total, "2"); ?>
					<label for="submit_your_order" class="col-sm-6 control-label"><?php echo "Grand Total: $" . $grand_total; ?></label>
				</div>
				<div class="col-sm-6">
					<form action="cart.php" method="post">
						<input id="submit" name="submit_your_order" type="submit" value="Submit Your Order" class="btn btn-primary">
					</form>
				</div>
			</div>
		</div>
		<br>
		<?php	
	}
	?>

    <!-- jQuery -->
    <script src="../bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- DataTables JavaScript -->
    <script src="../bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
		$(document).ready(function() {
			$('#dataTables-example').DataTable({
					responsive: true
			});
		});
    </script>

	<?php
} else {
	?>
	<div align = "center">
		<label>Please log in to view your cart.</label>
	</div>
	<br><br>
	<?php
}

display_bottom_section();

mysqli_close($link);

?>

</body>
</html>