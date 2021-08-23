<?php
include 'config/database.php';
try {     
    $productID = isset($_GET['productID']) ? $_GET['productID'] :  die('ERROR: Product ID not found.');
    $orderID = isset($_GET['orderID']) ? $_GET['orderID'] :  die('ERROR: Order ID not found.');
    $product_TA = isset($_GET['amount']) ? $_GET['amount'] :  die('ERROR: Amount not found.');

    $checkQuery = "SELECT * FROM order_detail WHERE orderID = :orderID";
    $checkStmt = $con->prepare($checkQuery);
    $checkStmt->bindParam(':orderID', $orderID);
    $checkStmt->execute();
    $num = $checkStmt->rowCount();
    if($num == 1){
        //selected product cannot be deleted because an order must has at least one product
        echo "<script>window.location.href='order_update.php?orderID=' + $orderID + '&action=onlyOneProduct';</script>";    }else {
    $query = "DELETE FROM order_detail WHERE productID = :productID AND orderID = :orderID";
    $stmt = $con->prepare($query);
    $stmt->bindParam(":productID", $productID);
    $stmt->bindParam(':orderID', $orderID);
    if($stmt->execute()){
        //delete the selected product
        $getAmountQuery = "SELECT total_amount FROM orders WHERE orderID = :orderID";
        $getAmountStmt = $con->prepare($getAmountQuery);
        $getAmountStmt->bindParam(':orderID', $orderID);
        $getAmountStmt->execute();
        if($row = $getAmountStmt->fetch(PDO::FETCH_ASSOC)){
            $updateTotalAmountQuery = "UPDATE orders SET total_amount=:setTotal_amount WHERE orderID=:orderID";
            $total_amount = $row['total_amount'] - $product_TA ;
            $updateTotalAmountStmt = $con->prepare($updateTotalAmountQuery);
            $updateTotalAmountStmt->bindParam(':orderID', $orderID);
            $updateTotalAmountStmt->bindParam(':setTotal_amount', $total_amount);
            $updateTotalAmountStmt->execute();
            
        }
        echo "<script>window.location.href='order_update.php?orderID=' + $orderID + '&action=deleted';</script>";
    }else{
        die('Unable to delete record.');
    }
}
} catch(PDOException $exception){
    die('ERROR: ' . $exception->getMessage());
}