<!DOCTYPE HTML>
<html>

<head>
    <title>Order Detail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="page-header">
            <h1>Read Order</h1>
        </div>

        <?php
        $orderID = isset($_GET['orderID']) ? $_GET['orderID'] : die('ERROR: Order record not found.');

        include 'config/database.php'; 

        try {
            $query = "SELECT * FROM orders WHERE orderID = :orderID";
            $stmt = $con->prepare($query);
            $stmt->bindParam(":orderID", $orderID);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $orderID = $row['orderID'];
            $orderDateNTime = $row['orderDateNTime'];
            $cus_username = $row['cus_username'];
            $productID = $row['productID'];
            $quantity = $row['quantity'];
        } catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>

        <table class='table table-hover table-responsive table-bordered'>
            <tr>
                <td>Order ID</td>
                <td><?php echo htmlspecialchars($orderID, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Order Date & Time</td>
                <td><?php echo htmlspecialchars($orderDateNTime, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Customer Username</td>
                <td><?php echo htmlspecialchars($cus_username, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Product ID</td>
                <td><?php echo htmlspecialchars($productID, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Quantity</td>
                <td><?php echo htmlspecialchars($quantity, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <a href='order_list.php' class='btn btn-danger'>Back to order list</a>
                </td>
            </tr>
        </table>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>