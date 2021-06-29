<!DOCTYPE HTML>
<html>

<head>
    <title>Read Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">

<body>
    <div class="container">
        <?php
        session_start();
        if (!isset($_SESSION["cus_username"])) {
            header("login.php");
        }
        include 'navigation.php';
        ?>
        <div class="page-header">
            <h1>Product List</h1>
        </div>

        <?php
        include 'config/database.php';
        $query = "SELECT productId, name, description, price FROM products ORDER BY productId DESC";
        $stmt = $con->prepare($query);
        $stmt->execute();
        $num = $stmt->rowCount();
        echo "<a href='product.php' class='btn btn-primary mb-2'>Create New Product</a>";
        if ($num > 0) {

            echo "<table class='table table-hover table-responsive table-bordered'>";

            echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>Name</th>";
            echo "<th>Description</th>";
            echo "<th>Price</th>";
            echo "<th>Action</th>";
            echo "</tr>";

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                echo "<tr>";
                echo "<td>{$productId}</td>";
                echo "<td>{$name}</td>";
                echo "<td>{$description}</td>";
                echo "<td>{$price}</td>";
                echo "<td>";
                echo "<a href='product_detail.php?productId={$productId}' class='btn btn-info me-2'>Detail</a>";
                echo "<a href='product_update.php?id={$productId}' class='btn btn-primary me-2'>Edit</a>";
                echo "<a href='#' onclick='delete_user({$productId});'  class='btn btn-danger'>Delete</a>";
                echo "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<div class='alert alert-danger'>No records found.</div>";
        }
        ?>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>