<?php
session_start();
if (!isset($_SESSION["cus_username"])) {
    header("Location: login.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Cus List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">

<body>
    <div class="container">
        <?php
        include 'navigation.php';
        ?>
        <div class="page-header">
            <h1>Cus List</h1>
        </div>

        <?php
        include 'config/database.php';

        $action = isset($_GET['action']) ? $_GET['action'] : "";
        if ($action == 'productInStock') {
            echo "<div class='alert alert-danger'>Product could not be deleted as it involved in order.</div>";
        }
        if ($action == 'deleted') {
            echo "<div class='alert alert-success'>Record was deleted.</div>";
        }

        $query = "SELECT * FROM customers";
        $stmt = $con->prepare($query);
        $stmt->execute();
        $num = $stmt->rowCount();
        echo "<a href='customer.php' class='btn btn-primary mb-2'>Create New c</a>";
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
                echo "<td>{$cus_username}</td>";
                echo "<td>{$firstName}</td>";
                echo "<td>{$gender}</td>";
                echo "<td>{$dateOfBirth}</td>";
                echo "<td>";
                echo "<a href='product_detail.php?productID={$cus_username}' class='btn btn-info me-2'>Detail</a>";
                echo "<a href='product_update.php?productID={$cus_username}' class='btn btn-primary me-2'>Edit</a>";
                echo "<a href='#' onclick='delete_product({$cus_username});'  class='btn btn-danger'>Delete</a>";
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
    <script type='text/javascript'>
        function delete_product(cus_username) {

            if (confirm('Are you sure?')) {
                window.location = 'customer_delete.php?cus_username=' + cus_username;
            }
        }
    </script>

</body>

</html>