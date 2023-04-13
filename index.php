<?php
//Connect To Database
require_once('database.php');
// Read the JSON file into a string
$json_string = file_get_contents('json/orders.json');
// Create the tables in the database
require_once('create_tables.php');
// decode the JSON string into an array
$orders = json_decode($json_string, true);
//Create the orders in from the JSON file in the database
require_once('create_orders.php');
// close connection
mysqli_close($conn);
//Make API calls
require_once('api.php');
?>