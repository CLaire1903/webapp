<?php
session_start();
if (!isset($_SESSION["cus_username"])) {
    header("Location: index.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Product List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link href="css/general.css" rel="stylesheet">
    <link href="css/product.css" rel="stylesheet">
    <link href="css/list.css" rel="stylesheet">

    <style>
        /*can be found at navigation.php */
        #productList {
            font-weight: bold;
            font-size: large;
        }
    </style>
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
            echo "<div class='alert alert-success'>Product was deleted.</div>";
        }
        ?>

        <div>
            <a href='product.php' id="create" class='btn mx-2'>Create New Product</a>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validation()" method="post">
                <table class='search table table-hover table-responsive'>
                    <tr class='search'>
                        <td class="search col-11"><input type='text' name='search' id="search" placeholder="Product's name" class='form-control'></td>
                        <td class='search'><input type='submit' value='Search' id="searchBtn" class='btn' /></td>
                    </tr>
                </table>
            </form>
        </div>

        <?php
        $where = "";
        if ($_POST) {
            try {
                if (empty($_POST['search'])) {
                    throw new Exception("Please input product name to search!");
                }

                $search = "%" . $_POST['search'] . "%";
                $where = "WHERE name LIKE :search";
            } catch (PDOException $exception) {
                //for databae 'PDO'
                echo "<div class='alert alert-danger'>" . $exception->getMessage() . "</div>";
            } catch (Exception $exception) {
                echo "<div class='alert alert-danger'>" . $exception->getMessage() . "</div>";
            }
        }
        $query = "SELECT productID, product_pic, name, description, price FROM products $where ORDER BY productID DESC";
        $stmt = $con->prepare($query);
        if ($_POST) $stmt->bindParam(':search', $search);
        $stmt->execute();
        $num = $stmt->rowCount();

        if ($num > 0) {
            echo "<table id='myTable' class='table table-hover table-responsive table-bordered'>";

            echo "<tr class='tableHeader'>";
            echo "<th class='col-1 text-center'>ID</th>";
            echo "<th class='col-1 text-center'>Picture</th>";
            echo "<th class='col-2 col-lg-1 text-center'>Name</th>";
            echo "<th class='text-center d-none d-md-table-cell'>Description</th>";
            echo "<th class='col-2 col-lg-2 text-center'>Price</th>";
            echo "<th class='col-lg-2 text-center'>Action</th>";
            echo "</tr>";

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                echo "<tr>";
                echo "<td class='text-center'>{$productID}</td>";
                $product_pic = $row['product_pic'];
                echo "<td>";
                echo "<div class='img-block'> ";
                if ($product_pic!= "") {
                    echo "<img src= $product_pic alt='' class='product_image'/> ";
                } else {
                    echo "No picture uploaded.";
                }
                echo "</div> ";
                echo "</td>";
                echo "<td class='text-center'>{$name}</td>";
                echo "<td class='d-none d-md-table-cell'>{$description}</td>";
                $price = sprintf('%.2f', $row['price']);
                echo "<td class='text-center'>RM $price</td>";
                echo "<td>";
                echo "<div class='d-lg-flex justify-content-sm-center'>";
                echo "<a href='product_detail.php?productID={$productID}' id='detail' class='actionBtn btn m-1 m-lg-2'>Detail</a>";
                echo "<a href='product_update.php?productID={$productID}' id='update' class='actionBtn btn m-1 m-lg-2'>Update</a>";
                echo "<a href='#' onclick='delete_product({$productID});' id='delete' class='actionBtn btn m-1 m-lg-2'>Delete</a>";
                echo "</div>";
                echo "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<div class='alert alert-danger'>No product found.</div>";
        }
        ?>
        <div class="footer bg-dark">
            <?php
            include 'footer.php';
            ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script type='text/javascript'>
        function delete_product(productID) {
            if (confirm('Are you sure?')) {
                window.location = 'product_delete.php?productID=' + productID;
            }
        }

        function validation() {
            var search = document.getElementById("search").value;
            var flag = false;
            var msg = "";
            if (search == "") {
                flag = true;
                msg = msg + "Please input product name to search!\r\n";
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