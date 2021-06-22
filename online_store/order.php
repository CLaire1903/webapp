<!DOCTYPE HTML>
<html>

<head>
    <title>Homework - Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
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
                if (empty($_POST['cus_username']) || empty($_POST['productID']) || empty($_POST['quantity'])) {
                    throw new Exception("Make sure all fields are not empty");
                }
                if ($_POST['quantity'] <= 0) {
                    throw new Exception("Quantity cannot be zero.");
                }
                $query = "INSERT INTO orders SET cus_username=:cus_username, productID=:productID, quantity=:quantity";
                $stmt = $con->prepare($query);
                $cus_username = $_POST['cus_username'];
                $productID = $_POST['productID'];
                $quantity = $_POST['quantity'];
                $stmt->bindParam(':cus_username', $cus_username);
                $stmt->bindParam(':productID', $productID);
                $stmt->bindParam(':quantity', $quantity);
                if ($stmt->execute()) {
                    echo "<div class='alert alert-success'>Record was saved.</div>";
                } else {
                    throw new Exception("Unable to save record.");
                }
            } catch (PDOException $exception) {
                //for databae 'PDO'
                echo "<div class='alert alert-danger'>" . $exception->getMessage() . "</div>";
            } catch (Exception $exception) {
                echo "<div class='alert alert-danger'>" . $exception->getMessage() . "</div>";
            }
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Customer Username</td>
                    <td>
                        <div>
                            <select class="form-select" id="autoSizingSelect" name="cus_username">
                                <option selected>-- Select User --</option>
                                <?php
                                include 'config/database.php';
                                $select_user_query = "SELECT cus_username FROM customers";
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
                <tr>
                    <td>Product ID</td>
                    <td>
                        <div>
                            <select class="form-select" id="autoSizingSelect" name="productID">
                                <option selected>-- Select Product --</option>
                                <?php
                                include 'config/database.php';
                                $select_product_query = "SELECT productID FROM products";
                                $select_product_stmt = $con->prepare($select_product_query);
                                $select_product_stmt->execute();
                                while($productID = $select_product_stmt->fetch(PDO::FETCH_ASSOC)){
                                    echo "<option value = '$productID[productID]'> $productID[productID] </option>";
                                }
                                ?>
                            </select>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Quantity</td>
                    <td><input type='text' name='quantity' class='form-control' /></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <a href='order_list.php' class='btn btn-danger'>View Order</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>