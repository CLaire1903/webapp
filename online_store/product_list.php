<?php
session_start();
if (!isset($_SESSION["cus_username"])) {
    header("Location: login.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Product List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <?php
        include 'navigation.php';
        ?>
        <div class="page-header">
            <h1>Product List</h1>
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
        ?>

        <div>
            <a href='product.php' class='btn btn-primary mx-2'>Create New Product</a>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <table class='table table-hover table-responsive table-bordered' style="border:none;">
                    <tr class='searchProduct' style="border:none;">
                        <td class="col-11" style="border:none;"><input type='text' name='search' id="search" onkeyup="myFunction()" placeholder='Search products' class='form-control'></td>
                        <td style="border:none;"><input type='submit' value='Search' class='btn btn-primary' /></td>
                    </tr>
                </table>
            </form>
        </div>

        <?php
        $where = "";
        if($_POST){
                if (empty($_POST['search'])) {
                    throw new Exception("Make sure all fields are not empty!");
                }
            
            $search = "%" . $_POST['search'] . "%";
            $where = "WHERE name LIKE :search";
        }
        $query = "SELECT productID, name, description, price FROM products $where ORDER BY productID DESC";
        $stmt = $con->prepare($query);
        if ($_POST) $stmt->bindParam(':search', $search);
        $stmt->execute();
        $num = $stmt->rowCount();

        if ($num > 0) {
            echo "<table id='myTable' class='table table-hover table-responsive table-bordered'>";

            echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>Name</th>";
            echo "<th class='col-7'>Description</th>";
            echo "<th>Price</th>";
            echo "<th>Action</th>";
            echo "</tr>";

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                echo "<tr>";
                echo "<td>{$productID}</td>";
                echo "<td>{$name}</td>";
                echo "<td>{$description}</td>";
                echo "<td>{$price}</td>";
                echo "<td>";
                echo "<a href='product_detail.php?productID={$productID}' class='btn btn-info me-2'>Detail</a>";
                echo "<a href='product_update.php?productID={$productID}' class='btn btn-primary me-2'>Edit</a>";
                echo "<a href='#' onclick='delete_product({$productID});'  class='btn btn-danger'>Delete</a>";
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
        function delete_product(productID) {
            if (confirm('Are you sure?')) {
                window.location = 'product_delete.php?productID=' + productID;
            }
        }

        /*function myFunction() {
            var input, filter, table, tr, td, i, txtValue;
            var input = document.getElementById("search");
            filter = input.value.toUpperCase();
            table = document.getElementById("myTable");
            tr = table.getElementsByTagName("tr");
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[1];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }*/
    </script>
</body>

</html>