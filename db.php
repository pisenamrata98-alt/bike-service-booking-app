<?php
$db_host = "sql101.infinityfree.com"; 
$db_user = "if0_42828647";            
$db_pass = "Namrata2006";           
$db_name = "if0_42828647_servicedb"; 

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}
?>