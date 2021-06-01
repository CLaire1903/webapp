<?php
// used to connect to the database
$host = "localhost";
$db_name = "claire_store";
$username = "claire_store";
$password = "Claire19031999";
  
try {
    $con = new PDO("mysql:host={$host};dbname={$db_name}", $username, $password);
    echo "Connected successfully"; 
}  
// show error
catch(PDOException $exception){
    echo "Connection error: ".$exception->getMessage();
}
?>
