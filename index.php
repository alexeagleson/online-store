<!DOCTYPE html>
<html >
<?php


include($_SERVER['DOCUMENT_ROOT'].'/sql_connect.php');
include($_SERVER['DOCUMENT_ROOT'].'/build_database_tables.php');


?>
<?php 

global $link;

session_start();

sql_connect();

// Create the database for stores
create_store_table($delete_existing = True);
create_products_table($delete_existing = True);

mysqli_close($link);

?>

</body>
</html>
