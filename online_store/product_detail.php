<?php
session_start();
if (!isset($_SESSION["cus_username"])) {
    header("Location: login.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Product Detail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">

</head>

<body>
    <div class="container">
        <?php
        include 'navigation.php';
        ?>
        <div class="page-header">
            <h1>Read Product</h1>
        </div>

        <?php
        $productId = isset($_GET['productId']) ? $_GET['productId'] : die('ERROR: Product record not found.');

        include 'config/database.php';

        try {
            $query = "SELECT productId, name, name_malay, description, price, promotion_price FROM products WHERE productId = :productId ";
            $stmt = $con->prepare($query);
            $stmt->bindParam(":productId", $productId);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $productId = $row['productId'];
            $name = $row['name'];
            $name_malay = $row['name_malay'];
            $description = $row['description'];
            $price = $row['price'];
            $promotion_price = $row['promotion_price'];
        } catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>

        <table class='table table-hover table-responsive table-bordered'>
            <tr>
                <td>Product ID</td>
                <td><?php echo htmlspecialchars($productId, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Name</td>
                <td><?php echo htmlspecialchars($name, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Malay Name</td>
                <td><?php echo htmlspecialchars($name_malay, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Description</td>
                <td><?php echo htmlspecialchars($description, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Price</td>
                <td><?php echo htmlspecialchars($price, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Promotion Price</td>
                <td><?php echo htmlspecialchars($promotion_price, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <a href='product_list.php' class='btn btn-danger'>Back to product list</a>
                </td>
            </tr>
        </table>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>