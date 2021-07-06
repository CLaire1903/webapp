<?php
session_start();
if (!isset($_SESSION["cus_username"])) {
    header("Location: login.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Order Detail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <?php
        include 'navigation.php';
        ?>
        <div class="page-header">
            <h1>Read Order</h1>
        </div>

        <?php
        $orderID = isset($_GET['orderID']) ? $_GET['orderID'] : die('ERROR: Order record not found.');

        include 'config/database.php';

        try {
            $o_query = "SELECT * FROM orders WHERE orderID = :orderID";
            $o_stmt = $con->prepare($o_query);
            $o_stmt->bindParam(":orderID", $orderID);
            $o_stmt->execute();
            $o_row = $o_stmt->fetch(PDO::FETCH_ASSOC);

            $orderID = $o_row['orderID'];
            $orderDateNTime = $o_row['orderDateNTime'];
            $cus_username = $o_row['cus_username'];
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
            <?php
            $od_query = "SELECT p.productID, name, quantity, price
                        FROM order_detail od
                        INNER JOIN products p ON od.productID = p.productID
                        WHERE orderID = :orderID";
            $od_stmt = $con->prepare($od_query);
            $od_stmt->bindParam(":orderID", $orderID);
            $od_stmt->execute();
            echo "<th class='col-3'>Product</th>";
            echo "<th class='col-3'>Quantity</th>";
            echo "<th class='col-3'>Price per piece</th>";
            echo "<th class='col-3'>Total Price</th>";
            $totalAmount = 0;
            while ($od_row = $od_stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>$od_row[name]</td>";
                echo "<td>$od_row[quantity]</td>";
                $productPrice = sprintf('%.2f', $od_row['price']);
                echo "<td>RM $productPrice</td>";
                $productTotal = sprintf('%.2f', $productPrice * $od_row['quantity']);
                echo "<td>RM $productTotal</td>";
                $totalAmount += $productTotal;
                echo "</tr>";
             }
                echo "<tr>";
                echo "<td></td>";
                echo "<td></td>";
                echo "<td>You need to pay:</td>";
                echo "<td>RM $totalAmount</td>";
                echo "</tr>";
            ?>
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