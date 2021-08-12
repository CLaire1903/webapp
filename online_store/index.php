<?php
session_start();
if (!isset($_SESSION["cus_username"])) {
    header("Location: login.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Homework - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link href="general.css" rel="stylesheet">

    <style>
        #home {
            font-weight: bold;
            font-size: large;
        }
        .summary{
            background-color:rgba(238, 149, 158);
            color: black;
        }
        .image{
            width: 75%;
            border: none;
        }
        .quickInfo {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        }
        .quickInfo:hover {
            box-shadow: 0 4px 8px 0 rgb(255, 255, 255), 0 6px 20px 0 rgb(255, 255, 255);
        }
        .count{
            text-decoration: none;
        }
        .count:hover{
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <?php
        include 'navigation.php';
        include 'config/database.php';
        ?>
        <div class="contain d-flex flex-column">
            <div class="wish d-flex flex-column align-items-center">
                <h1 class='text-center p-2'> Hi, <?php echo $_SESSION['cus_username'] ?></h1>
                <h1 class="instruction p-1 text-center">
                    Welcome to Claire's Online Store.
                </h1>
            </div>
            <div class="aboutUs p-3 text-center">
                <h3 class="summary p-2 fw-bold rounded-pill">SUMMARY</Summary>
                </h3>
            </div>
            <div class="d-flex justify-content-center">
                <div class="quickInfo card text-center col-3 col-lg-2 mx-4">
                    <div class="pic p-1">
                    <a href=customer_list.php><img class="image" src="image/logo/customer.png"></a>
                    </div>
                    <?php
                    $customerQuery = "SELECT * FROM customers";
                    $customerStmt = $con->prepare($customerQuery);
                    $customerStmt->execute();
                    $customerNum = $customerStmt->rowCount();
                    //display total customer who create an account
                    echo "<a class='count' href=customer_list.php> <h6 class='p-2 text-dark'>$customerNum customers</h6> </a>";
                    ?>
                </div>
                <div class="quickInfo card text-center col-3 col-lg-2 mx-4">
                    <div class="pic p-1">
                    <a href=product_list.php><img class="image" src="image/logo/product.png"></a>
                    </div>
                    <?php
                    $productQuery = "SELECT * FROM products";
                    $productStmt = $con->prepare($productQuery);
                    $productStmt->execute();
                    $productNum = $productStmt->rowCount();
                    //display total product sells in the system
                    echo "<a class='count' href=product_list.php> <h6 class='p-2 text-dark'>$productNum products</h6> </a>";
                    ?>
                </div>
                <div class="quickInfo card text-center col-3 col-lg-2 mx-4">
                    <div class="pic p-1">
                    <a href=order_list.php><img class="image" src="image/logo/order.png"></a>
                    </div>
                    <?php
                    $orderQuery = "SELECT * FROM orders";
                    $orderStmt = $con->prepare($orderQuery);
                    $orderStmt->execute();
                    $orderNum = $orderStmt->rowCount();
                    //display total order made
                    echo "<a class='count' href=order_list.php> <h6 class='p-2 text-dark'>$orderNum orders</h6> </a>";
                    ?>
                </div>
            </div>
            <div class="m-3">
                <h5>Latest Order Summary:</h5>
                <table class='table table-hover table-responsive table-bordered text-center'>
                    <tr class="tableHeader">
                        <th>Order ID</th>
                        <th>Order Date and Time</th>
                        <th>Customer Username </th>
                        <th>Total Amount</th>
                    </tr>
                    <?php
                    //display the latest order made
                    $latestOrderQuery = "SELECT * FROM orders ORDER BY orderID DESC LIMIT 1";
                    $latestOrderStmt = $con->prepare($latestOrderQuery);
                    $latestOrderStmt->execute();
                    $latestOrderRow = $latestOrderStmt->fetch(PDO::FETCH_ASSOC);
                    $orderID = $latestOrderRow['orderID'];
                    $orderDateNTime = $latestOrderRow['orderDateNTime'];
                    $cus_username = $latestOrderRow['cus_username'];
                    $total_amount = sprintf('%.2f', $latestOrderRow['total_amount']);
                    echo "<tr>";
                    echo "<td>{$orderID}</td>";
                    echo "<td>{$orderDateNTime}</td>";
                    echo "<td>{$cus_username}</td>";
                    echo "<td>RM {$total_amount}</td>";
                    echo "</tr>";
                    ?>
                </table>
            </div>
            <div class="m-3">
                <h5>Highest Purchase Amount Order Summary:</h5>
                <table class='table table-hover table-responsive table-bordered text-center'>
                    <tr class="tableHeader">
                        <th>Order ID</th>
                        <th>Order Date and Time</th>
                        <th>Customer Username </th>
                        <th>Total Amount</th>
                    </tr>
                    <?php
                    //display the order with highest total amount
                    $hpaQuery = "SELECT * FROM orders ORDER BY total_amount DESC LIMIT 1";
                    $hpaStmt = $con->prepare($hpaQuery);
                    $hpaStmt->execute();
                    $hpaRow = $hpaStmt->fetch(PDO::FETCH_ASSOC);
                    $orderID = $hpaRow['orderID'];
                    $orderDateNTime = $hpaRow['orderDateNTime'];
                    $cus_username = $hpaRow['cus_username'];
                    $total_amount = sprintf('%.2f', $hpaRow['total_amount']);
                    echo "<tr>";
                    echo "<td>{$orderID}</td>";
                    echo "<td>{$orderDateNTime}</td>";
                    echo "<td>{$cus_username}</td>";
                    echo "<td>RM {$total_amount}</td>";
                    echo "</tr>";
                    ?>
                </table>
            </div>
        </div>
        <div class="m-3">
            <h5> 5 Top selling products:</h5>
            <table class='table table-hover table-responsive table-bordered text-center'>
                <tr class="tableHeader">
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Product Description</th>
                    <th>Product Price</th>
                    <th>Total Sold Quantity</th>
                </tr>
                <?php
                //display 5 top selling product
                $topSellingQuery = "SELECT p.productID, p.name, p.description, p.price, SUM( od.quantity ) AS totalQuantity
                                        FROM order_detail od
                                        INNER JOIN products p
                                        WHERE od.productID = p.productID
                                        GROUP BY od.productID
                                        ORDER BY totalQuantity DESC
                                        LIMIT 5";
                $topSellingStmt = $con->prepare($topSellingQuery);
                $topSellingStmt->execute();
                $num = $topSellingStmt->rowCount();

                if ($num > 0) {
                    while ($topSellingRow = $topSellingStmt->fetch(PDO::FETCH_ASSOC)) {
                        extract($topSellingRow);
                        echo "<tr>";
                        echo "<td>{$productID}</td>";
                        echo "<td>{$name}</td>";
                        echo "<td>{$description}</td>";
                        $price = sprintf('%.2f', $topSellingRow['price']);
                        echo "<td>RM {$price}</td>";
                        $total_sold = $topSellingRow['totalQuantity'];
                        echo "<td>{$total_sold}</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </table>
        </div>
        <div class="footer bg-dark">
            <?php
            include 'footer.php';
            ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>