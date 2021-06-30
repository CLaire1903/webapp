<?php
session_start();
if (!isset($_SESSION["cus_username"])) {
    header("Location: login.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Homework - Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <?php
        include 'navigation.php';
        ?>
        <div class="page-header">
            <h4 class="p-1">Create Product</h4>
        </div>
        <?php
        if ($_POST) {
            include 'config/database.php';
            try {
                if (empty($_POST['name']) || empty($_POST['description']) || empty($_POST['price']) || empty($_POST['manufacture_date']) || empty($_POST['expired_date'])) {
                    throw new Exception("Make sure all fields are not empty");
                }
                if (!is_numeric($_POST['price']) || !is_numeric($_POST['promotion_price'])) {
                    throw new Exception("Please make sure the price is a number"); 
                }        
                if ($_POST['price'] < 0 || $_POST['promotion_price'] < 0) {
                    throw new Exception("Please make sure the price must be not a negative value");
                }
                if ($_POST['price'] > 1000 || $_POST['promotion_price'] > 1000) {
                    throw new Exception("Please make sure the price is not bigger than RM 1000.");
                }
                if ($_POST['price'] < $_POST['promotion_price']) {
                    throw new Exception("Promotion price cannot bigger than normal price.");
                }
                if ($_POST['manufacture_date'] > $_POST['expired_date']) {
                    throw new Exception("Please make sure expired date is late than the manufacture date.");
                }

                $query = "INSERT INTO products SET name=:name, name_malay=:name_malay, description=:description, price=:price, promotion_price=:promotion_price, manufacture_date=:manufacture_date, expired_date=:expired_date, created=:created";
                $stmt = $con->prepare($query);
                $name = $_POST['name'];
                $name_malay = $_POST['name_malay'];
                $description = $_POST['description'];
                $price = $_POST['price'];
                $promotion_price = $_POST['promotion_price'];
                $manufacture_date = $_POST['manufacture_date'];
                $expired_date = $_POST['expired_date'];
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':name_malay', $name_malay);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':price', $price);
                $stmt->bindParam(':promotion_price', $promotion_price);
                $stmt->bindParam(':manufacture_date', $manufacture_date);
                $stmt->bindParam(':expired_date', $expired_date);
                $created = date('Y-m-d H:i:s');
                $stmt->bindParam(':created', $created);
                if ($stmt->execute()) {
                    echo "<div class='alert alert-success'>Record was saved.</div>";
                } else {
                    throw new Exception("Unable to save record.");
                }
            } catch (PDOException $exception) {
                //for databae 'PDO'
                echo "<div class='alert alert-danger'>".$exception->getMessage()."</div>";
            } catch (Exception $exception) {
                echo "<div class='alert alert-danger'>".$exception->getMessage()."</div>";
            }
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Name</td>
                    <td><input type='text' name='name' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Name_Malay</td>
                    <td><input type='text' name='name_malay' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><textarea type='text' name='description' class='form-control' rows="3"></textarea></td>
                </tr>
                <tr>
                    <td>Price</td>
                    <td><input type='text' name='price' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Promotion Price</td>
                    <td><input type='text' name='promotion_price' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Manufacture Date</td>
                    <td><input type='date' name='manufacture_date' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Expired Date</td>
                    <td><input type='date' name='expired_date' class='form-control' /></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <a href='product_list.php' class='btn btn-danger'>View Products</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>