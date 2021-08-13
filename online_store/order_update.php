<?php
session_start();
if (!isset($_SESSION["cus_username"])) {
    header("Location: login.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Homework - Update Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link href="general.css" rel="stylesheet">

    <style>
        html,
        body {
            font-family: 'Poppins', sans-serif;
        }

        #deleteBtn {
            background-color: rgba(238, 149, 158);
        }

        #deleteBtn:hover {
            font-weight: bold;
            color: white;
        }
    </style>
</head>

<body>
    <div class="container">
        <?php
        include 'navigation.php';
        ?>
        <div class="page-header">
            <h1>Update Order</h1>
        </div>
        <?php
        $orderID = isset($_GET['orderID']) ? $_GET['orderID'] : die('ERROR: Order record not found.');

        include 'config/database.php';

        $action = isset($_GET['action']) ? $_GET['action'] : "";
        if ($action == 'onlyOneProduct') {
            echo "<div class='alert alert-danger'>Product could not be deleted.</div>";
        }
        if ($action == 'deleted') {
            echo "<div class='alert alert-success'>Product was deleted.</div>";
        }

        try {
            $query = "SELECT * FROM orders WHERE orderID = :orderID ";
            $stmt = $con->prepare($query);
            $stmt->bindParam(":orderID", $orderID);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $orderDateNTime = $row['orderDateNTime'];
            $cus_username = $row['cus_username'];
            $total_amount = $row['total_amount'];
        } catch (PDOException $exception) {
            //for databae 'PDO'
            echo "<div class='alert alert-danger'>" . $exception->getMessage() . "</div>";
        }

        if ($_POST) {
            try {
                $con->beginTransaction();
                for ($i = 0; $i < count($_POST['productID']); $i++) {
                    if (!isset($_POST['quantity'][$i])) {
                        throw new Exception("Please make sure the product and quantity is selected.");
                    }
                    $checkQuantity = htmlspecialchars(strip_tags($_POST['quantity'][$i]));
                    if (count($_POST['productID']) == 1 && $checkQuantity == 0) {
                        throw new Exception("Sorry! The product cannot be deleted!");
                    }
                }
                //update the selected order into orders table in database
                $updateTotalAmountQuery = "UPDATE orders SET total_amount=:setTotal_amount WHERE orderID=:orderID";
                $updateTotalAmountStmt = $con->prepare($updateTotalAmountQuery);
                $setTotal_amount = 0;
                for ($i = 0; $i < count($_POST['productID']); $i++) {
                    $productPrice = htmlspecialchars(strip_tags($_POST['productID'][$i]));
                    //get the price of all product in the order and count the total amount of the order
                    $selectPriceQuery = "SELECT price FROM products WHERE productID=:productID";
                    $selectPriceStmt = $con->prepare($selectPriceQuery);
                    $selectPriceStmt->bindParam(':productID', $productPrice);
                    $selectPriceStmt->execute();
                    while ($selectPriceRow = $selectPriceStmt->fetch(PDO::FETCH_ASSOC)) {
                        $productPrice = $selectPriceRow['price'];
                        $quantityCOTA = htmlspecialchars(strip_tags($_POST['quantity'][$i]));
                        $product_total = $productPrice * $quantityCOTA;
                        $setTotal_amount += $product_total;
                    }
                }
                $updateTotalAmountStmt->bindParam(':orderID', $orderID);
                $updateTotalAmountStmt->bindParam(':setTotal_amount', $setTotal_amount);
                $updateTotalAmountStmt->execute();

                //delete all order detail with the selected orderID
                $delete_query = "DELETE FROM order_detail WHERE orderID = :orderID";
                $delete_stmt = $con->prepare($delete_query);
                $delete_stmt->bindParam(':orderID', $orderID);

                if ($delete_stmt->execute()) {
                    for ($i = 0; $i < count($_POST['productID']); $i++) {
                        $getPrice = htmlspecialchars(strip_tags($_POST['productID'][$i]));
                        //get the price of the product then count the total amount of the product
                        $getPriceQuery = "SELECT price FROM products WHERE productID=:productID";
                        $getPriceStmt = $con->prepare($getPriceQuery);
                        $getPriceStmt->bindParam(':productID', $getPrice);
                        $getPriceStmt->execute();
                        while ($getPriceRow = $getPriceStmt->fetch(PDO::FETCH_ASSOC)) {
                            $productPrice = $getPriceRow['price'];
                            $quantityCPTA = htmlspecialchars(strip_tags($_POST['quantity'][$i]));
                            $product_TA = $productPrice * $quantityCPTA;
                        }
                        $input_product = htmlspecialchars(strip_tags($_POST['productID'][$i]));
                        $input_quant = htmlspecialchars(strip_tags($_POST['quantity'][$i]));
                        if ($input_product != '' && $input_quant != '') {
                            //re-insert the order detail of the selected orderID we deleted before
                            $insertodQuery = "INSERT INTO order_detail SET orderID=:orderID, productID=:productID, quantity=:quantity, product_TA=:product_TA";
                            $insertodStmt = $con->prepare($insertodQuery);
                            $insertodStmt->bindParam(':orderID', $orderID);
                            $insertodStmt->bindParam(':productID', $input_product);
                            $insertodStmt->bindParam(':quantity', $input_quant);
                            $insertodStmt->bindParam(':product_TA', $product_TA);
                            $insertodStmt->execute();

                            if ($input_quant == 0) {
                                //delete the product id the quantity is set into '0'
                                $delete_productQuery = "DELETE FROM order_detail WHERE quantity = :quantity";
                                $delete_productStmt = $con->prepare($delete_productQuery);
                                $delete_productStmt->bindParam(':quantity', $input_quant);
                                $delete_productStmt->execute();
                            }
                        } else {
                            throw new Exception("Please make sure the product and quantity is selected.");
                        }
                    }
                    echo "<div class='alert alert-success'>Order $orderID was updated.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Unable to update order $orderID. Please try again.</div>";
                }
                $con->commit();
            } catch (PDOException $exception) {
                if ($con->inTransaction()) {
                    $con->rollback();
                    echo "<div class='alert alert-danger'>Please make sure no duplicate product chosen!</div>";
                } else {
                    echo "<div class='alert alert-danger'>" . $exception->getMessage() . "</div>";
                }
            } catch (Exception $exception) {
                echo "<div class='alert alert-danger'>" . $exception->getMessage() . "</div>";
            }
        } ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?orderID={$orderID}"); ?>" onsubmit="return validation()" method="post">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Order ID</td>
                    <td><?php echo htmlspecialchars($orderID, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Order Date & Time</td>
                    <td><?php echo htmlspecialchars($orderDateNTime, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Customer Username</td>
                    <td><?php echo htmlspecialchars($cus_username, ENT_QUOTES);  ?></td>
                </tr>
            </table>

            <h6 class="text-danger">* Set quantity = 0 if you wish to delete the product.</h6>

            <table class='table table-hover table-responsive table-bordered'>
                <th class='col-3 text-center'>Product</th>
                <th class='col-1 text-center'>Quantity</th>
                <th class='col-2 text-center'>Price per Piece</th>
                <th class='col-2 text-center'>Total</th>
                <th class='col-1'></th>

                <?php
                $od_query = "SELECT orderID, p.productID, name, quantity, price, product_TA
                        FROM order_detail od
                        INNER JOIN products p ON od.productID = p.productID
                        WHERE orderID = :orderID";
                $od_stmt = $con->prepare($od_query);
                $od_stmt->bindParam(":orderID", $orderID);
                $od_stmt->execute();

                while ($od_row = $od_stmt->fetch(PDO::FETCH_ASSOC)) {
                    $productID = $od_row['productID'];
                    $name = $od_row['name'];
                    $quantity = $od_row['quantity'];
                    $price = $od_row['price'];
                    $productID = htmlspecialchars($productID, ENT_QUOTES);
                    $productName = htmlspecialchars($name, ENT_QUOTES);
                    $productQuantity = htmlspecialchars($quantity, ENT_QUOTES);
                    echo "<tr  class='product'>";
                    echo "<td>";
                    echo "<select class='form-select' name='productID[]'> ";
                    echo "<option value='' disabled selected>-- Select Product --</option> ";
                    $select_product_query = "SELECT productID, name FROM products";
                    $select_product_stmt = $con->prepare($select_product_query);
                    $select_product_stmt->execute();
                    while ($get_product = $select_product_stmt->fetch(PDO::FETCH_ASSOC)) {
                        $result = $productID == $get_product['productID'] ? 'selected' : '';
                        echo "<option value = '$get_product[productID]' $result> $get_product[name] </option>";
                    }
                    echo "</select>";
                    echo "</td>";
                    echo "<td>";
                    echo "<select class='quantity form-select' name='quantity[]'>";
                    echo "<option value='' disabled selected> -- Select Quantity -- </option>";
                    for ($i = 0; $i <= 20; $i++) {
                        $result = $productQuantity == $i ? 'selected' : '';
                        echo "<option value='$i' $result> $i </option>";
                    }
                    echo "</select>";
                    echo "</td>";
                    $productPrice = sprintf('%.2f', $od_row['price']);
                    echo "<td class = 'text-end'>RM $productPrice</td>";
                    $productTotal = sprintf('%.2f', $od_row['product_TA']);
                    echo "<td class = 'text-end'>RM $productTotal</td>";
                    echo "<td>";
                    echo "<div class='d-flex justify-content-center'>";
                    echo "<a href='#' onclick='delete_product({$productID},{$orderID});'  id='deleteBtn' class='btn'>Delete</a>";
                    echo "</div>";
                    echo "</td>";
                    echo "</tr>";
                } ?>
                <tr>
                    <td colspan="3" class='text-end'>You need to pay:</td>
                    <?php
                    $query = "SELECT * FROM orders WHERE orderID = :orderID ";
                    $stmt = $con->prepare($query);
                    $stmt->bindParam(":orderID", $orderID);
                    $stmt->execute();
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    $total_amount = sprintf('%.2f', $row['total_amount']);
                    echo "<td class = 'text-end'>RM $total_amount</td>"; ?>
                </tr>

            </table>
            <table class='table table-hover table-responsive table-bordered'>
                <tr class='productQuantity'>
                    <td>Add more product (*optional) :</td>
                    <td>
                        <select class='productID form-select' id='autoSizingSelect' name='productID[]'>
                            <option value='' disabled selected>-- Select Product --</option>
                            <?php
                            include 'config/database.php';
                            $select_product_query = "SELECT productID, name FROM products";
                            $select_product_stmt = $con->prepare($select_product_query);
                            $select_product_stmt->execute();
                            while ($productID = $select_product_stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value = '$productID[productID]'> $productID[name] </option>";
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <select class='quantity form-select' id='autoSizingSelect' name='quantity[]'>
                            <option value='' disabled selected>-- Select Quantity --</option>
                            <?php
                            for ($i = 1; $i <= 20; $i++) {
                                echo "<option value='$i'> $i </option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
            </table>

            <div class="d-flex justify-content-center flex-column flex-lg-row">
                <div class="d-flex justify-content-center">
                    <button type="button" class="add_one btn mb-3 mx-2">Add More Product</button>
                    <button type="button" class="delete_one btn mb-3 mx-2">Delete Last Product</button>
                </div>
                <div class="d-flex justify-content-center">
                    <input type='submit' value='Save Changes' class='saveBtn btn mb-3 mx-2' />
                    <a href='order_list.php' class='viewBtn btn mb-3 mx-2'>Back to order list</a>
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
                    element.remove(element);
                }
            }
        }, false);

        function delete_product(productID, orderID) {
            if (confirm('Are you sure?')) {
                window.location = "order_detail_deleteProduct.php?productID=" + productID + "&orderID=" + orderID;
            }
        }

        function validation() {
            var product_input = document.querySelector('.productID').value;
            var quantity_input = document.querySelector('.quantity').value;
            var product_delete = document.querySelectorAll('.product').length;
            var quantity_delete = document.querySelector('.quantity').value;
            var flag = false;
            var msg = "";
            if (product_delete == 1) {
                if (quantity_delete == 0) {
                    flag = true;
                    msg = msg + "Product cannot be deleted!\r\n";
                    msg = msg + "An order must buy at least one product!\r\n";
                    msg = msg + "Please re-enter the quantity other then zero!\r\n";
                }
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