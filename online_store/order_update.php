<?php
session_start();
if (!isset($_SESSION["cus_username"])) {
    header("Location: index.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Homework - Update Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link href="css/general.css" rel="stylesheet">
    <link href="css/order.css" rel="stylesheet">

    <style>
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
                for ($i = 0; $i < count($_POST['currentproductID']); $i++) {
                    $checkQuantity = htmlspecialchars(strip_tags($_POST['currentquantity'][$i]));
                    if (count($_POST['currentproductID']) == 1 && $checkQuantity == 0) {
                        throw new Exception("Sorry! The product cannot be deleted!");
                    }
                }

                if (!isset($_POST['newproductID']) && isset($_POST['newquantity'])) {
                    throw new Exception("Please make sure the product is selected!");
                }

                if (isset($_POST['newproductID']) && !isset($_POST['newquantity'])) {
                    throw new Exception("Please make sure the quantity is selected!");
                }

                //update the selected order into orders table in database
                $updateTotalAmountQuery = "UPDATE orders SET total_amount=:setTotal_amount WHERE orderID=:orderID";
                $updateTotalAmountStmt = $con->prepare($updateTotalAmountQuery);
                
                $currentTotal_amount = 0;
                $newTotal_amount = 0;
                $total_amount = 0;
                for ($i = 0; $i < count($_POST['currentproductID']); $i++) {
                    $currentproductPrice = htmlspecialchars(strip_tags($_POST['currentproductID'][$i]));
                    //get the price of the product inserted before
                    $selectcurrentPriceQuery = "SELECT price FROM products WHERE productID=:productID";
                    $selectcurrentPriceStmt = $con->prepare($selectcurrentPriceQuery);
                    $selectcurrentPriceStmt->bindParam(':productID', $currentproductPrice);
                    $selectcurrentPriceStmt->execute();
                    while ($selectcurrentPriceRow = $selectcurrentPriceStmt->fetch(PDO::FETCH_ASSOC)) {
                        $currentproductPrice = $selectcurrentPriceRow['price'];
                        $currentquantityCOTA = htmlspecialchars(strip_tags($_POST['currentquantity'][$i]));
                        $currentproduct_total = $currentproductPrice * $currentquantityCOTA;
                        $currentTotal_amount += $currentproduct_total;
                    }
                }
                if (isset($_POST['newproductID']) && isset($_POST['newquantity'])) {
                    for ($i = 0; $i < count($_POST['newproductID']); $i++) {
                        $newproductPrice = $_POST['newproductID'][$i];
                        //get the price for new product inserted
                        $selectnewPriceQuery = "SELECT price FROM products WHERE productID=:productID";
                        $selectnewPriceStmt = $con->prepare($selectnewPriceQuery);
                        $selectnewPriceStmt->bindParam(':productID', $newproductPrice);
                        $selectnewPriceStmt->execute();
                        while ($selectnewPriceRow = $selectnewPriceStmt->fetch(PDO::FETCH_ASSOC)) {
                            $newproductPrice = $selectnewPriceRow['price'];
                            $newquantityCOTA = $_POST['newquantity'][$i];
                            $newproduct_total = $newproductPrice * $newquantityCOTA;
                            $newTotal_amount += $newproduct_total;
                        }
                    }
                }
                $total_amount = $currentTotal_amount + $newTotal_amount;
                $updateTotalAmountStmt->bindParam(':orderID', $orderID);
                $updateTotalAmountStmt->bindParam(':setTotal_amount', $total_amount);
                $updateTotalAmountStmt->execute();

                //delete all order detail with the selected orderID
                $delete_query = "DELETE FROM order_detail WHERE orderID = :orderID";
                $delete_stmt = $con->prepare($delete_query);
                $delete_stmt->bindParam(':orderID', $orderID);

                if ($delete_stmt->execute()) {
                    //deal with the current / already uploaded product
                    for ($i = 0; $i < count($_POST['currentproductID']); $i++) {
                        $getcurrentPrice = htmlspecialchars(strip_tags($_POST['currentproductID'][$i]));
                        //get the price of the product then count the total amount of the product
                        $getcurrentPriceQuery = "SELECT price FROM products WHERE productID=:productID";
                        $getcurrentPriceStmt = $con->prepare($getcurrentPriceQuery);
                        $getcurrentPriceStmt->bindParam(':productID', $getcurrentPrice);
                        $getcurrentPriceStmt->execute();
                        while ($getcurrentPriceRow = $getcurrentPriceStmt->fetch(PDO::FETCH_ASSOC)) {
                            $currentproductPrice = $getcurrentPriceRow['price'];
                            $currentquantityCPTA = htmlspecialchars(strip_tags($_POST['currentquantity'][$i]));
                            $currentproduct_TA = $currentproductPrice * $currentquantityCPTA;
                        }
                        $current_product = htmlspecialchars(strip_tags($_POST['currentproductID'][$i]));
                        $current_quant = htmlspecialchars(strip_tags($_POST['currentquantity'][$i]));
                        //re-insert product ordered before into the order detail
                        $current_insertodQuery = "INSERT INTO order_detail SET orderID=:orderID, productID=:productID, quantity=:quantity, product_TA=:product_TA";
                        $current_insertodStmt = $con->prepare($current_insertodQuery);
                        $current_insertodStmt->bindParam(':orderID', $orderID);
                        $current_insertodStmt->bindParam(':productID', $current_product);
                        $current_insertodStmt->bindParam(':quantity', $current_quant);
                        $current_insertodStmt->bindParam(':product_TA', $currentproduct_TA);
                        $current_insertodStmt->execute();

                        if ($current_quant == 0) {
                            //delete the product id when the quantity is set into '0'
                            $delete_productQuery = "DELETE FROM order_detail WHERE quantity = :quantity";
                            $delete_productStmt = $con->prepare($delete_productQuery);
                            $delete_productStmt->bindParam(':quantity', $current_quant);
                            $delete_productStmt->execute();
                        }
                    }

                    if (isset($_POST['newproductID']) && isset($_POST['newquantity'])) {
                        for ($i = 0; $i < count($_POST['newproductID']); $i++) {
                            //deal with the new inserted product
                            $getnewPrice = $_POST['newproductID'][$i];
                            //get the price of the product then count the total amount of the new product
                            $getnewPriceQuery = "SELECT price FROM products WHERE productID=:productID";
                            $getnewPriceStmt = $con->prepare($getnewPriceQuery);
                            $getnewPriceStmt->bindParam(':productID', $getnewPrice);
                            $getnewPriceStmt->execute();
                            while ($getnewPriceRow = $getnewPriceStmt->fetch(PDO::FETCH_ASSOC)) {
                                $newproductPrice = $getnewPriceRow['price'];
                                $newquantityCPTA = $_POST['newquantity'][$i];
                                $newproduct_TA = $newproductPrice * $newquantityCPTA;
                            }
                            $new_product = $_POST['newproductID'][$i];
                            $new_quant = $_POST['newquantity'][$i];
                            //insert new product order into order detail table
                            $new_insertodQuery = "INSERT INTO order_detail SET orderID=:orderID, productID=:productID, quantity=:quantity, product_TA=:product_TA";
                            $new_insertodStmt = $con->prepare($new_insertodQuery);
                            $new_insertodStmt->bindParam(':orderID', $orderID);
                            $new_insertodStmt->bindParam(':productID', $new_product);
                            $new_insertodStmt->bindParam(':quantity', $new_quant);
                            $new_insertodStmt->bindParam(':product_TA', $newproduct_TA);
                            $new_insertodStmt->execute();
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
                    echo "<div  class='alert alert-danger'>Please make sure no duplicate product chosen!</div>";
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
                    echo "<select class='form-select' name='currentproductID[]'> ";
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
                    echo "<select class='quantity form-select' name='currentquantity[]'>";
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
                        <select class='new_productID form-select' id='autoSizingSelect' name='newproductID[]'>
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
                        <select class='new_quantity form-select' id='autoSizingSelect' name='newquantity[]'>
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
            var product_delete = document.querySelectorAll('.product').length;
            var quantity_delete = document.querySelector('.quantity').value;
            var new_productID = document.querySelector('.new_productID').value;
            var new_quantity = document.querySelector('.new_quantity').value;
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
            if(new_productID == "" && new_quantity != ""){
                flag = true;
                msg = msg + "Please make sure the product is selected!\r\n";
            }
            if(new_productID != "" && new_quantity == ""){
                flag = true;
                msg = msg + "Please make sure the quantity is selected!\r\n";
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