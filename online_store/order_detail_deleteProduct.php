<?php
include 'config/database.php';
try {     
    $orderID = isset($_GET['orderID']) ? $_GET['orderID'] :  die('ERROR: Record ID not found.');
    $productID = isset($_GET['productID']) ? $_GET['productID'] :  die('ERROR: Product ID not found.');

    $checkQuery = "SELECT * FROM order_detail WHERE orderID = ?";
    $checkStmt = $con->prepare($checkQuery);
    $checkStmt->bindParam(1, $orderID);
    $checkStmt->execute();
    $num = $checkStmt->rowCount();
    if($num == 1){
        header('Location: order_update.php?orderID={$orderID}action=onlyOneProduct');
    }else {
    $query = "DELETE FROM order_detail WHERE productID = ?";
    $stmt = $con->prepare($query);
    $stmt->bindParam(1, $productID);
    if($stmt->execute()){
        header('Location: order_update.php?orderID={$orderID}action=deleted');
    }else{
        die('Unable to delete record.');
    }
}
} catch(PDOException $exception){
    die('ERROR: ' . $exception->getMessage());
}