<?php
include 'config/database.php';
try {     
    $productID = isset($_GET['productID']) ? $_GET['productID'] :  die('ERROR: Record ID not found.');

    //check whether the product involved in any order
    $checkQuery = "SELECT * FROM order_detail WHERE productID = ?";
    $checkStmt = $con->prepare($checkQuery);
    $checkStmt->bindParam(1, $productID);
    $checkStmt->execute();
    $num = $checkStmt->rowCount();
    if($num != 0){
        //selected product cannot be deleted because involved in at least a order
        header('Location: product_list.php?action=productInStock');
    }else {
    $query = "DELETE FROM products WHERE productID = ?";
    $stmt = $con->prepare($query);
    $stmt->bindParam(1, $productID);
    if($stmt->execute()){
        //selected product is deleted
        header('Location: product_list.php?action=deleted');
    }else{
        die('Unable to delete record.');
    }
}
} catch(PDOException $exception){
    die('ERROR: ' . $exception->getMessage());
}
