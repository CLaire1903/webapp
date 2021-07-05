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
        try {
            $query = "SELECT * FROM orders WHERE orderID = :orderID ";
            $stmt = $con->prepare($query);
            $stmt->bindParam(":orderID", $orderID);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $orderDateNTime = $row['orderDateNTime'];
            $cus_username = $row['cus_username'];
        } catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }

        if ($_POST) {
            try {
                $con->beginTransaction();
                $query = "UPDATE orders SET cus_username=:cus_username WHERE orderID = :orderID";
                $stmt = $con->prepare($query);
                $cus_username = htmlspecialchars(strip_tags($_POST['cus_username']));
                $stmt->bindParam(':orderID', $orderID);
                $stmt->bindParam(':cus_username', $cus_username);

                if ($stmt->execute()) {

                    $delete_query = "DELETE FROM order_detail WHERE orderID = :orderID";
                    $stmt = $con->prepare($delete_query);
                    $stmt->bindParam(':orderID', $orderID);
                    $stmt->execute();

                    for ($i = 0; $i < count($_POST['productID']); $i++) {
                        $product = htmlspecialchars(strip_tags($_POST['productID'][$i]));
                        $quant = htmlspecialchars(strip_tags($_POST['quantity'][$i]));
                        if ($product != '' && $quant != '') {
                            $query = "INSERT INTO order_detail SET orderID=:orderID, productID=:productID, quantity=:quantity";
                            $stmt = $con->prepare($query);
                            $stmt->bindParam(':orderID', $orderID);
                            $stmt->bindParam(':productID', $product);
                            $stmt->bindParam(':quantity', $quant);
                            $stmt->execute();

                            if ($quant == 0) {
                                $delete_product = "DELETE FROM order_detail WHERE quantity = :quantity";
                                $stmt = $con->prepare($delete_product);
                                $stmt->bindParam(':quantity', $quant);
                                $stmt->execute();
                            }
                        } else {
                            throw new Exception("Please make sure the product and quantity is selected.");
                        }
                    }
                    echo "<div class='alert alert-success'>Record was updated.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
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
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?orderID={$orderID}"); ?>" method="post">
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
                    <td>
                        <div>
                            <select class="form-select" id="autoSizingSelect" name="cus_username">
                                <option value="<?php echo htmlspecialchars($cus_username, ENT_QUOTES);  ?>" selected><?php echo htmlspecialchars($cus_username, ENT_QUOTES);  ?></option>
                                <?php
                                include 'config/database.php';
                                $select_user_query = "SELECT cus_username FROM customers";
                                $select_user_stmt = $con->prepare($select_user_query);
                                $select_user_stmt->execute();
                                while ($get_cus_username = $select_user_stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value = '$get_cus_username[cus_username]'> $get_cus_username[cus_username] </option>";
                                }
                                ?>
                            </select>
                        </div>
                    </td>
                </tr>
                <th class='col-4'>Product</th>
                <th class='col-4'>Quantity</th>
                <th class='col-4'>Price</th>
                <?php
                $od_query = "SELECT orderID, p.productID, name, quantity, price
                        FROM order_detail od
                        INNER JOIN products p ON od.productID = p.productID
                        WHERE orderID = :orderID";
                $od_stmt = $con->prepare($od_query);
                $od_stmt->bindParam(":orderID", $orderID);
                $od_stmt->execute();

                while ($od_row = $od_stmt->fetch(PDO::FETCH_ASSOC)) {
                    $ori_productID = $od_row['productID'];
                    $productID = $od_row['productID'];
                    $name = $od_row['name'];
                    $quantity = $od_row['quantity'];
                    $price = $od_row['price'];
                    $productID = htmlspecialchars($productID, ENT_QUOTES);
                    $productName = htmlspecialchars($name, ENT_QUOTES);
                    $productQuantity = htmlspecialchars($quantity, ENT_QUOTES);
                    echo "<tr>";
                    echo "<td>";
                    echo "<select class='form-select' id='autoSizingSelect' name='productID[]'> ";
                    echo "<option value='$productID' selected>$productName</option> ";
                    $select_product_query = "SELECT productID, name FROM products";
                    $select_product_stmt = $con->prepare($select_product_query);
                    $select_product_stmt->execute();
                    while ($get_product = $select_product_stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value = '$get_product[productID]'> $get_product[name] </option>";
                    }
                    echo "</select>";
                    echo "</td>";
                    echo "<td>";
                    echo "<select class='form-select' id='autoSizingSelect' name='quantity[]'>";
                    echo "<option value='$quantity' selected> $quantity </option>";
                    $number = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20);
                    for ($i = 0; $i < count($number); $i++) {
                        echo "<option value='$number[$i]'> $number[$i] </option>";
                    }
                    echo "</select>";
                    echo "</td>";
                    echo "<td>$price</td>";
                    echo "</tr>";
                } ?>
                <tr class='productQuantity'>
                    <td>
                        <select class='form-select' id='autoSizingSelect' name='productID[]'>
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
                        <select class='form-select' id='autoSizingSelect' name='quantity[]'>
                            <option value='' disabled selected>-- Select Quantity --</option>
                            <?php
                            $number = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20);
                            for ($i = 0; $i < count($number); $i++) {
                                echo "<option value='$number[$i]'> $number[$i] </option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
            </table>

            <div class="d-flex justify-content-center">
                <button type="button" class="add_one btn btn-info text-light m-2">Add More Product</button>
                <button type="button" class="delete_one btn btn-warning text-light m-2">Delete Last Product</button>
                <input type='submit' value='Save Changes' class='btn btn-primary m-2' />
                <a href='order_list.php' class='btn btn-danger m-2'>Back to order list</a>
            </div>
        </form>
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
    </script>
</body>

</html>