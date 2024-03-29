<?php
session_start();
if (!isset($_SESSION["cus_username"])) {
    header("Location: index.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Homework - Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link href="css/general.css" rel="stylesheet">
    <link href="css/order.css" rel="stylesheet">

    <style>
        /*can be found at navigation page*/
        #createOrder {
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
            <h4 class="p-1">Create Order</h4>
        </div>
        <?php
        if ($_POST) {
            include 'config/database.php';
            try {
                if (empty($_POST['cus_username'])) {
                    throw new Exception("Please choose the customer username!");
                }
                if (empty($_POST['productID'])) {
                    throw new Exception("Please select at least one product!");
                }
                if (empty($_POST['quantity'])) {
                    throw new Exception("Please select the quantity of the product you chose!");
                }
                $con->beginTransaction();
                $query = "INSERT INTO orders SET cus_username=:cus_username, total_amount=:total_amount";
                $stmt = $con->prepare($query);
                $cus_username = $_POST['cus_username'];
                $total_amount = 0;

                for ($i = 0; $i < count($_POST['productID']); $i++) {
                    $productPrice = $_POST['productID'][$i];
                    $selectPriceQuery = "SELECT price FROM products WHERE productID=:productID";
                    $selectPriceStmt = $con->prepare($selectPriceQuery);
                    $selectPriceStmt->bindParam(':productID', $productPrice);
                    $selectPriceStmt->execute();
                    while ($row = $selectPriceStmt->fetch(PDO::FETCH_ASSOC)) {
                        $productPrice = $row['price'];
                        $quant = $_POST['quantity'][$i];
                        $product_total = $productPrice * $quant;
                        $total_amount += $product_total;
                    }
                }
                $stmt->bindParam(':cus_username', $cus_username);
                $stmt->bindParam(':total_amount', $total_amount);

                if ($stmt->execute()) {
                    $lastID = $con->lastInsertId();
                    for ($i = 0; $i < count($_POST['productID']); $i++) {
                        $getPrice = $_POST['productID'][$i];
                        $getPriceQuery = "SELECT price FROM products WHERE productID=:productID";
                        $getPriceStmt = $con->prepare($getPriceQuery);
                        $getPriceStmt->bindParam(':productID', $getPrice);
                        $getPriceStmt->execute();
                        while ($row = $getPriceStmt->fetch(PDO::FETCH_ASSOC)) {
                            $productPrice = $row['price'];
                            $quant = $_POST['quantity'][$i];
                            $product_TA = $productPrice * $quant;
                        }
                        $product = $_POST['productID'][$i];
                        $quant = $_POST['quantity'][$i];
                        if ($product != '' && $quant != '') {
                            $insertodQuery = "INSERT INTO order_detail SET orderID=:orderID, productID=:productID, quantity=:quantity, product_TA=:product_TA";
                            $insertodStmt = $con->prepare($insertodQuery);
                            $insertodStmt->bindParam(':orderID', $lastID);
                            $insertodStmt->bindParam(':productID', $product);
                            $insertodStmt->bindParam(':quantity', $quant);
                            $insertodStmt->bindParam(':product_TA', $product_TA);
                            $insertodStmt->execute();
                        } else {
                            throw new Exception("Please make sure the product and quantity is selected.");
                        }
                    }
                    echo "<div class='alert alert-success'>Record was saved. Order ID is $lastID.</div>";
                } else {
                    throw new Exception("Unable to save record.");
                }
                $con->commit();
            } catch (PDOException $exception) {
                //for databae 'PDO'
                if ($con->inTransaction()) {
                    $con->rollback();
                    echo "<div class='alert alert-danger'>Please make sure no duplicate product chosen!</div>";
                } else {
                    echo "<div class='alert alert-danger'>" . $exception->getMessage() . "</div>";
                }
            } catch (Exception $exception) {
                echo "<div class='alert alert-danger'>" . $exception->getMessage() . "</div>";
            }
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validation()" method="post">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Customer Username</td>
                    <td>
                        <div>
                            <select class="form-select" name="cus_username" id="cus_username">
                                <option value='' disabled selected>-- Select User --</option>
                                <?php
                                include 'config/database.php';
                                $select_user_query = "SELECT cus_username FROM customers WHERE accountStatus = 'active'";
                                $select_user_stmt = $con->prepare($select_user_query);
                                $select_user_stmt->execute();
                                while ($cus_username = $select_user_stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value = '$cus_username[cus_username]'> $cus_username[cus_username] </option>";
                                }
                                ?>
                            </select>
                        </div>
                    </td>
                </tr>
                <?php
                echo "<tr class='productQuantity'>";
                echo "<td>Product</td>";
                echo "<td>";
                echo "<div>";
                echo "<select class='productID form-select' name='productID[]'>";
                echo "<option value='' disabled selected>-- Select Product --</option> ";
                include 'config/database.php';
                $select_product_query = "SELECT productID, name, price FROM products";
                $select_product_stmt = $con->prepare($select_product_query);
                $select_product_stmt->execute();
                while ($productID = $select_product_stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value = '$productID[productID]'> $productID[name] </option>";
                }
                echo "</select>";
                echo "<select class='quantity form-select' name='quantity[]'>";
                echo "<option value='' disabled selected>-- Select Quantity --</option>";
                for ($i = 1; $i <= 20; $i++) {
                    echo "<option value='$i'> $i </option>";
                }
                echo "</select>";
                echo "</div>";
                echo "</td>";
                ?>
            </table>
            <div class="d-flex justify-content-center flex-column flex-lg-row">
                <div class="d-flex justify-content-center">
                    <button type="button" class="add_one btn mb-3 mx-2">Add More Product</button>
                    <button type="button" class="delete_one btn mb-3 mx-2">Delete Last Product</button>
                </div>
                <div class="d-flex justify-content-center">
                    <input type='submit' value='Save Changes' class='saveBtn btn mb-3 mx-2' />
                    <a href='order_list.php' class='viewBtn btn mb-3 mx-2'>View Orders</a>
                </div>
            </div>
        </form>
        <div class="footer bg-dark">
            <?php
            include 'footer.php';
            ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('click', function(event) {
            if (event.target.matches('.add_one')) {
                var element = document.querySelector('.productQuantity');
                var clone = element.cloneNode(true);
                element.after(clone);
            }
            if (event.target.matches('.delete_one')) {
                var total = document.querySelectorAll('.productQuantity').length;
                if (total > 1) {
                    var element = document.querySelector('.productQuantity');
                    var clone = element.cloneNode(true);
                    element.remove(clone);
                }
            }
        }, false);

        function validation() {
            var cus_username = document.getElementById("cus_username").value;
            var productID = document.querySelector('.productID').value;
            var quantity = document.querySelector('.quantity').value;
            var flag = false;
            var msg = "";
            if (cus_username == "") {
                flag = true;
                msg = msg + "Please choose the customer username!\r\n";
            }
            if (productID == "") {
                flag = true;
                msg = msg + "Please select at least one product!\r\n";
            }
            if (quantity == "") {
                flag = true;
                msg = msg + "Please select the quantity of the product you chose!\r\n";
            }
            if (flag == true) {
                alert(msg);
                return false;
            }else{
                return true;
            }
        }
    </script>

</body>

</html>