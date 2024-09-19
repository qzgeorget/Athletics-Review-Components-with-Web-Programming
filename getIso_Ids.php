<?php
$countries = array($_GET['country_1'], $_GET['country_2']);
include "database_connection_info.php";
$conn = mysqli_connect($servername, $username, $password, $dbname);

$sql = "SELECT name, gender, dob FROM cyclist WHERE iso_id = '$countries[$x]' ORDER BY gender, name";
$result = mysqli_query($conn, $sql);
?>