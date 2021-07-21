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
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validation()" method="post">
                <table class='table table-hover table-responsive table-bordered' style="border:none;">
                    <tr class='searchProduct' style="border:none;">
                        <td class="col-9" style="border:none;"><input type='text' name='search' id="search" placeholder='Search products' class='form-control'></td>
                        <td style="border:none;"><input type='submit' value='Search' class='btn btn-secondary '></td>
                        <td style="border:none;"><a href='product.php' class='btn btn-secondary'>Create New Product</a></td>
                    </tr>
                </table>
            </form>
        </div>

        <?php
        if (isset($_POST['search'])) {
            try {
                if (empty($_POST['search'])) {
                    throw new Exception("Please enter the keywords!");
                }
                $searchq = $_POST['search'];
                $searchq = preg_replace("#[^0-9a-z]#i", "", $searchq);
                $searchProductQuery = "SELECT * FROM products WHERE name LIKE '%$searchq%' OR name_malay LIKE '%$searchq%'";
                $searchProductStmt = $con->prepare($searchProductQuery);
                $searchProductStmt->execute();
                $count = $searchProductStmt->rowCount();;
                if ($count == 0) {
                    echo "<h5>There was no search results!</h5>";
                    $query = "SELECT productID, name, description, price FROM products ORDER BY productID DESC";
                    $stmt = $con->prepare($query);
                    $stmt->execute();
                    $num = $stmt->rowCount();

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
                } else {
                    echo "<table class='table table-hover table-responsive table-bordered'>";
                    echo "<tr>";
                    echo "<th>ID</th>";
                    echo "<th>Name</th>";
                    echo "<th>Description</th>";
                    echo "<th>Price</th>";
                    echo "<th>Action</th>";
                    echo "</tr>";
                    while ($row = $searchProductStmt->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        echo "<tr>";
                        echo "<td>{$productID}</td>";
                        echo "<td>{$name}</td>";
                        echo "<td class='col-7'>{$description}</td>";
                        echo "<td>{$price}</td>";
                        echo "<td>";
                        echo "<a href='product_detail.php?productID={$productID}' class='btn btn-info me-2'>Detail</a>";
                        echo "<a href='product_update.php?productID={$productID}' class='btn btn-primary me-2'>Edit</a>";
                        echo "<a href='#' onclick='delete_product({$productID});'  class='btn btn-danger'>Delete</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                }
            } catch (PDOException $exception) {
                //for databae 'PDO'
                echo "<div class='alert alert-danger'>" . $exception->getMessage() . "</div>";
            } catch (Exception $exception) {
                echo "<div class='alert alert-danger'>" . $exception->getMessage() . "</div>";
            }
        }
        ?>

        <?php
        $query = "SELECT productID, name, description, price FROM products ORDER BY productID DESC";
        $stmt = $con->prepare($query);
        $stmt->execute();
        $num = $stmt->rowCount();

        if ($num > 0) {
            echo "<table id='oriTable' class='table table-hover table-responsive table-bordered'>";

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

        function validation() {
            var oriTable = document.getElementById("oriTable");
            var search = document.getElementById("search").value;
            var flag = false;
            var msg = "";
            if (search == '') {
                flag = true;
                msg = msg + "Please enter the keywords!\r\n";
            }else{ 
                oriTable.style.display="none";
            }
            if (flag == true) {
                alert(msg);
                return false;
            } else {
                return true;
            }
        }
    </script>
</body>

</html>