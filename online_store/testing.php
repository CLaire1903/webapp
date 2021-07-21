<?php
session_start();
if (!isset($_SESSION["cus_username"])) {
    header("Location: login.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Order list</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">

<body>
    <div class="container">
        <?php
        include 'navigation.php';
        ?>
        <div class="page-header">
            <h1>Order List</h1>
        </div>

        <div>
            <table class='table table-hover table-responsive table-bordered' style="border:none;">
                <tr class='searchProduct' style="border:none;">
                    <td class="col-10" style="border:none;"><input type='text' name='search' id="search" onkeyup="myFunction()" placeholder='Search orders' class='form-control'></td>
                    <td style="border:none;"><a href='order.php' class='btn btn-primary'>Create New Order</a></td>
                </tr>
            </table>
        </div>


        <?php
        include 'config/database.php';
        $action = isset($_GET['action']) ? $_GET['action'] : "";
        if ($action == 'deleted') {
            echo "<div class='alert alert-success'>Record was deleted.</div>";
        }
        $query = "SELECT * FROM orders ORDER BY orderId DESC";
        $stmt = $con->prepare($query);
        $stmt->execute();
        $num = $stmt->rowCount();
        if ($num > 0) {
            echo "<table class='table table-hover table-responsive table-bordered' id='myTable'>";

            echo "<tr>";
            echo "<th>Order ID</th>";
            echo "<th>Order Date and Time</th>";
            echo "<th>Customer Username</th>";
            echo "<th>Total Amount</th>";
            echo "</tr>";

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                echo "<tr>";
                echo "<td>{$orderID}</td>";
                echo "<td>{$orderDateNTime}</td>";
                echo "<td>{$cus_username}</td>";
                echo "<td>{$total_amount}</td>";
                echo "<td>";
                echo "<a href='order_detail.php?orderID={$orderID}' class='btn btn-info me-2'>Detail</a>";
                echo "<a href='order_update.php?orderID={$orderID}' class='btn btn-primary me-2'>Edit</a>";
                echo "<a href='#' onclick='delete_order({$orderID});'  class='btn btn-danger'>Delete</a>";
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
        function delete_order(orderID) {
            if (confirm('Are you sure?')) {
                window.location = 'order_delete.php?orderID=' + orderID;
            }
        }

        function myFunction() {
            var input, filter, table, tr, td, i, txtValue;
            var input = document.getElementById("search");
            filter = input.value.toUpperCase();
            table = document.getElementById("myTable");
            tr = table.getElementsByTagName("tr");
            for (i = 0; i < tr.length; i++) {
                username = tr[i].getElementsByTagName("td")[2];
                id = tr[i].getElementsByTagName("td")[0];
                if (username || id) {
                    usernameCol = username.textContent || username.innerText;
                    idCol = id.textContent || id.innerText;
                    if (usernameCol.toUpperCase().indexOf(filter) > -1 || idCol.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>
</body>

</html>