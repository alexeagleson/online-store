<?php


include($_SERVER['DOCUMENT_ROOT'].'/sql_connect.php');
include($_SERVER['DOCUMENT_ROOT'].'/build_database_tables.php');
include($_SERVER['DOCUMENT_ROOT'].'/buttons_and_menus.php');


global $link;

session_start();
sql_connect();


// Get all relevant data about this object
$query_text = "SELECT DISTINCT aisle FROM PRODUCTS";
$query = $link->prepare($query_text);
if(!$query->execute()) {
	query_error($query_text); return False;
}
$results = get_all_results_2d_array($query, 'both');

if (!$results) { query_error($query); return False; }

mysqli_close($link);

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
		<label>Cost:</label><br/><br/>
		<label name="state" id="product-info">
	</div>
		<?php basic_button('a', 'b', 'Purchase'); ?>
	</div>
</div>



</body>
</html>