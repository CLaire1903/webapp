<?php
include 'config/database.php';
try {     
    $cus_username = isset($_GET['cus_username']) ? $_GET['cus_username'] :  die('ERROR: Record ID not found.');

    $checkQuery = "SELECT * FROM orders WHERE cus_username = ?";
    $checkStmt = $con->prepare($checkQuery);
    $checkStmt->bindParam(1, $cus_username);
    $checkStmt->execute();
    $num = $checkStmt->rowCount();
    if($num != 0){
        //customer record cannot be deleted because he/she has make an order before.
        header('Location: customer_list.php?action=activeCustomer');
    }else {
    $query = "DELETE FROM customers WHERE cus_username = ?";
    $stmt = $con->prepare($query);
    $stmt->bindParam(1, $cus_username);
    unlink($profile_pic);
    if($stmt->execute()){
        //delete the customer record when the customer does not make any order
        header('Location: customer_list.php?action=deleted');
    }else{
        die('Unable to delete record.');
    }
}
} catch(PDOException $exception){
    die('ERROR: ' . $exception->getMessage());
}
