<?php
include 'config/database.php';
try {     
    $productID = isset($_GET['productID']) ? $_GET['productID'] :  die('ERROR: Product ID not found.');
    $orderID = isset($_GET['orderID']) ? $_GET['orderID'] :  die('ERROR: Order ID not found.');

    $checkQuery = "SELECT * FROM order_detail WHERE orderID = :orderID";
    $checkStmt = $con->prepare($checkQuery);
    $checkStmt->bindParam(':orderID', $orderID);
    $checkStmt->execute();
    $num = $checkStmt->rowCount();
    var_dump($checkQuery);
    if($num == 1){
        echo "<script>window.location.href='order_update.php?orderID=' + $orderID + '&action=onlyOneProduct';</script>";    }else {
    $query = "DELETE FROM order_detail WHERE productID = :productID";
    $stmt = $con->prepare($query);
    $stmt->bindParam(":productID", $productID);
    if($stmt->execute()){
        echo "<script>window.location.href='order_update.php?orderID=' + $orderID + '&action=deleted';</script>";
    }else{
        die('Unable to delete record.');
    }
}
} catch(PDOException $exception){
    die('ERROR: ' . $exception->getMessage());
}