<?php
include 'config/database.php';
try {
    $orderID = isset($_GET['orderID']) ? $_GET['orderID'] :  die('ERROR: Order ID not found.');
    
    $od_query = "DELETE FROM order_detail WHERE orderID = ?";
    $od_stmt = $con->prepare($od_query);
    $od_stmt->bindParam(1, $orderID);

    if ($od_stmt->execute()) {
        $o_query = "DELETE FROM orders WHERE orderID = ?";
        $o_stmt = $con->prepare($o_query);
        $o_stmt->bindParam(1, $orderID);
        if ($o_stmt->execute()) {
        header('Location: order_list.php?action=deleted');
        }
    } else {
        die('Unable to delete record.');
    }
} catch (PDOException $exception) {
    die('ERROR: ' . $exception->getMessage());
}
