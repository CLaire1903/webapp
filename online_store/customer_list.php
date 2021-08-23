<?php
session_start();
if (!isset($_SESSION["cus_username"])) {
    header("Location: index.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Customer list</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link href="css/general.css" rel="stylesheet">
    <link href="css/customer.css" rel="stylesheet">
    <link href="css/list.css" rel="stylesheet">

    <style>
        /*can be found at navigation page */
        #cusList {
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
            <h1>Customer List</h1>
        </div>

        <div>
            <a href='customer.php' id="create" class='btn btn-primary mx-2'>Create New Customer</a>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validation()" method="post">
                <table class='search table table-hover table-responsive table-bordered'>
                    <tr class='search'>
                        <td class="search col-11"><input type='text' name='search' id="search" placeholder="Customer's Username" class='form-control'></td>
                        <td class="search"><input type='submit' value='Search' id="searchBtn" class='btn btn-primary' /></td>
                    </tr>
                </table>
            </form>
        </div>

        <?php
        include 'config/database.php';

        $action = isset($_GET['action']) ? $_GET['action'] : "";
        if ($action == 'activeCustomer') {
            echo "<div class='alert alert-danger'>Customer could not be deleted as he/she has make an order.</div>";
        }
        if ($action == 'deleted') {
            echo "<div class='alert alert-success'>Customer was deleted.</div>";
        }

        $where = "";
        if ($_POST) {
            try {
                if (empty($_POST['search'])) {
                    throw new Exception("Please input customer username to search!");
                }
                $search = "%" . $_POST['search'] . "%";
                $where = "WHERE cus_username LIKE :search";
            } catch (PDOException $exception) {
                //for databae 'PDO'
                echo "<div class='alert alert-danger'>" . $exception->getMessage() . "</div>";
            } catch (Exception $exception) {
                echo "<div class='alert alert-danger'>" . $exception->getMessage() . "</div>";
            }
        }
        $query = "SELECT profile_pic, cus_username, firstName, lastName, gender, dateOfBirth FROM customers $where";
        $stmt = $con->prepare($query);
        if ($_POST) $stmt->bindParam(':search', $search);
        $stmt->execute();
        $num = $stmt->rowCount();

        if ($num > 0) {
            echo "<table class='table table-hover table-responsive table-bordered'>";

            echo "<tr class='tableHeader'>";
            echo "<th class='col-1 text-center'>Profile Picture</th>";
            echo "<th class='text-center'>Username</th>";
            echo "<th class='text-center d-none d-lg-table-cell'>First Name</th>";
            echo "<th class='text-center d-none d-lg-table-cell'>Last Name</th>";
            echo "<th class='text-center d-lg-none'>Name</th>";
            echo "<th class='text-center d-none d-md-table-cell'>Date Of Birth</th>";
            echo "<th class='col-lg-3 text-center'>Action</th>";
            echo "</tr>";

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                echo "<tr>";
                $img_src = $row['profile_pic'];
                echo "<td>";
                echo "<div class='img-block'> ";
                if ($img_src != "") {
                    echo "<img src= $img_src alt='' class='image-responsive' style='width:100px; height:100px'/> ";
                } else {
                    echo "No picture uploaded.";
                }
                echo "</div> ";
                echo "</td>";
                echo "<td class='text-center'>{$cus_username}</td>";
                echo "<td class='text-center d-none d-lg-table-cell'>{$firstName}</td>";
                echo "<td class='text-center d-none d-lg-table-cell'>{$lastName}</td>";
                echo "<td class='text-center d-lg-none'>$firstName $lastName</td>";
                echo "<td class='text-center d-none d-md-table-cell'>{$dateOfBirth}</td>";
                echo "<td>";
                echo "<div class='d-lg-flex justify-content-sm-center'>";
                echo "<a href='customer_detail.php?cus_username={$cus_username}' id='detail' class='actionBtn btn m-1 m-lg-2'>Detail</a>";
                echo "<a href='customer_update.php?cus_username={$cus_username}' id='update' class='actionBtn btn m-1 m-lg-2'>Update</a>";
                echo "<a href='#' onclick='delete_customer(&#39;$cus_username&#39;)' id='delete' class='actionBtn btn m-1 m-lg-2'>Delete</a>";
                echo "</div>";
                echo "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<div class='alert alert-danger'>No records found.</div>";
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
        function delete_customer(cus_username) {
            if (confirm('Are you sure?')) {
                window.location = 'customer_delete.php?cus_username=' + cus_username;
            }
        }

        function validation() {
            var search = document.getElementById("search").value;
            var flag = false;
            var msg = "";
            if (search == "") {
                flag = true;
                msg = msg + "Please input customer username to search!\r\n";
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