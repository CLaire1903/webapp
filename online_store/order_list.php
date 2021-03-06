<?php
session_start();
if (!isset($_SESSION["cus_username"])) {
    header("Location: index.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Order list</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link href="css/general.css" rel="stylesheet">
    <link href="css/order.css" rel="stylesheet">
    <link href="css/list.css" rel="stylesheet">

    <style>
        /*can be found at navigation page*/
        #orderList {
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
            <h1>Order List</h1>
        </div>

        <div>
            <a href='order.php' id="create" class='btn btn-primary mx-2'>Create New Order</a>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validation()" method="post">
                <table class='search table table-hover table-responsive'>
                    <tr class='search'>
                        <td class="search col-11"><input type='text' name='search' id="search" onkeyup="myFunction()" placeholder="Order ID or Customer's Username" class='form-control'></td>
                        <td class="search"><input type='submit' value='Search' id="searchBtn" class='btn btn-primary' /></td>
                    </tr>
                </table>
        </div>

        <?php
        include 'config/database.php';
        $action = isset($_GET['action']) ? $_GET['action'] : "";
        if ($action == 'deleted') {
            echo "<div class='alert alert-success'>Order was deleted.</div>";
        }

        $where = "";
        if ($_POST) {
            try {
                if (empty($_POST['search'])) {
                    throw new Exception("Please input order ID or customer username to search!");
                }
                $search = "%" . $_POST['search'] . "%";
                $where = "WHERE cus_username LIKE :search OR orderID LIKE :search";
            } catch (PDOException $exception) {
                //for databae 'PDO'
                echo "<div class='alert alert-danger'>" . $exception->getMessage() . "</div>";
            } catch (Exception $exception) {
                echo "<div class='alert alert-danger'>" . $exception->getMessage() . "</div>";
            }
        }
        $query = "SELECT orderID, orderDateNTime, cus_username, total_amount FROM orders $where ORDER BY orderID DESC";
        $stmt = $con->prepare($query);
        if ($_POST) $stmt->bindParam(':search', $search);
        $stmt->execute();
        $num = $stmt->rowCount();

        if ($num > 0) {
            echo "<table class='table table-hover table-responsive table-bordered text-center' id='myTable'>";

            echo "<tr class='tableHeader'>";
            echo "<th class='col-3'>ID</th>";
            echo "<th class='col-3'>Order Date and Time</th>";
            echo "<th class='col-3'>Customer Username</th>";
            echo "<th class='col-3'>Total Amount</th>";
            echo "<th class='col-3'>Action</th>";
            echo "</tr>";

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                echo "<tr>";
                echo "<td>{$orderID}</td>";
                echo "<td>{$orderDateNTime}</td>";
                echo "<td>{$cus_username}</td>";
                $totalAmount = sprintf('%.2f', $row['total_amount']);
                echo "<td>RM $totalAmount</td>";
                echo "<td>";
                echo "<div class='d-lg-flex justify-content-sm-center'>";
                echo "<a href='order_detail.php?orderID={$orderID}' id='detail' class='actionBtn btn m-1 m-lg-2'>Detail</a>";
                echo "<a href='order_update.php?orderID={$orderID}' id='update' class='actionBtn btn m-1 m-lg-2'>Update</a>";
                echo "<a href='#' onclick='delete_order({$orderID});' id='delete' class='actionBtn btn m-1 m-lg-2'>Delete</a>";
                echo "</div>";
                echo "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<div class='alert alert-danger'>No order found.</div>";
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
        function delete_order(orderID) {
            if (confirm('Are you sure?')) {
                window.location = 'order_delete.php?orderID=' + orderID;
            }
        }

        function validation() {
            var search = document.getElementById("search").value;
            var flag = false;
            var msg = "";
            if (search == "") {
                flag = true;
                msg = msg + "Please input order ID or customer username to search!\r\n";
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