<?php
include 'config/database.php';
try {     
    $productID = isset($_GET['productID']) ? $_GET['productID'] :  die('ERROR: Record ID not found.');

    $checkQuery = "SELECT * FROM order_detail WHERE productID = ?";
    $checkStmt = $con->prepare($checkQuery);
    $checkStmt->bindParam(1, $productID);
    $checkStmt->execute();
    $num = $checkStmt->rowCount();
    if($num != 0){
        header('Location: product_list.php?action=productInStock');
    }else {
    $query = "DELETE FROM products WHERE productID = ?";
    $stmt = $con->prepare($query);
    $stmt->bindParam(1, $productID);
    if($stmt->execute()){
        header('Location: product_list.php?action=deleted');
    }else{
        die('Unable to delete record.');
    }
}
} catch(PDOException $exception){
    die('ERROR: ' . $exception->getMessage());
}
