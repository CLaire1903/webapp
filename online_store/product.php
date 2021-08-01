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
            <h6 class="text-danger"> Please complete the form below with * completely. </h6>
        </div>
        <?php
        if ($_POST) {
            include 'config/database.php';
            //$productPic = "";
            /*$target_dir = "image/product_pic/";
            $target_file = "";*/
            $filename = $_FILES["product_pic"]["name"];
            $tempname = $_FILES["product_pic"]["tmp_name"];
            $folder = "image/product_pic/" . $filename;
            $isUploadOK = 1;

            try {
                if (empty($_POST['name']) || empty($_POST['description']) || empty($_POST['price']) || empty($_POST['manufacture_date']) || empty($_POST['expired_date'])) {
                    throw new Exception("Make sure all fields are not empty!");
                }
                if (!is_numeric($_POST['price']) || !is_numeric($_POST['promotion_price'])) {
                    throw new Exception("Please make sure the price is a number!");
                }
                if ($_POST['price'] <= 0 || $_POST['promotion_price'] <= 0) {
                    throw new Exception("Please make sure the price must not be a negative value or zero!");
                }
                if ($_POST['price'] > 1000 || $_POST['promotion_price'] > 1000) {
                    throw new Exception("Please make sure the price is not bigger than RM 1000!");
                }
                if ($_POST['price'] < $_POST['promotion_price']) {
                    throw new Exception("Promotion price cannot bigger than normal price!");
                }
                if ($_POST['manufacture_date'] > $_POST['expired_date']) {
                    throw new Exception("Please make sure expired date is late than the manufacture date!");
                }

                if ($filename != "") {

                    $imageFileType = strtolower(pathinfo($folder, PATHINFO_EXTENSION));
                    $check = getimagesize($tempname);
                    if ($check == 0) {
                        $isUploadOK = 0;
                        throw new Exception("File is not an image.");
                    }

                    list($width, $height, $type, $attr) = getimagesize($tempname);
                    if ($width != $height) {
                        $isUploadOK = 0;
                        throw new Exception("Please make sure the ratio of the photo is 1:1.");
                    }

                    if ($_FILES["product_pic"]["size"] > 512000) {
                        $isUploadOK = 0;
                        throw new Exception("Sorry, your file is too large. Only 512KB is allowed!");
                    }

                    if (
                        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                        && $imageFileType != "gif"
                    ) {
                        $isUploadOK = 0;
                        throw new Exception("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
                    }
                }

                if ($folder != "") {
                    $productPic = "product_pic=:product_pic";
                }

                $query = "INSERT INTO products SET $productPic, name=:name, name_malay=:name_malay, description=:description, price=:price, promotion_price=:promotion_price, manufacture_date=:manufacture_date, expired_date=:expired_date, created=:created";
                $stmt = $con->prepare($query);
                $name = $_POST['name'];
                $name_malay = $_POST['name_malay'];
                $description = $_POST['description'];
                $price = $_POST['price'];
                $promotion_price = $_POST['promotion_price'];
                $manufacture_date = $_POST['manufacture_date'];
                $expired_date = $_POST['expired_date'];
                if ($filename != ""){
                    $stmt->bindParam(':product_pic', $folder);
                }else {
                    $stmt->bindParam(':product_pic', $filename);
                }
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
                    /*if ($isUploadOK == 0) {
                        echo "<div class='alert alert-success'>Sorry, your file was not uploaded.</div>"; // if everything is ok, try to upload file
                    } else {
                        if (move_uploaded_file($_FILES["product_pic"]["tmp_name"], $target_file)) {
                            echo "<div class='alert alert-success'>The file " . basename($_FILES["product_pic"]["name"]) . " has been uploaded.</div>";
                        } else {
                            throw new Exception("Sorry, there was an error uploading your file.");
                        }
                    }*/
                    if ($folder != "") {
                        if ($isUploadOK == 0) {
                            echo "<div class='alert alert-success'>Sorry, your file was not uploaded.</div>";
                        } else {
                            if (move_uploaded_file($tempname, $folder)) {
                                echo "<div class='alert alert-success'>The file " . basename($_FILES["product_pic"]["name"]) . " has been uploaded.</div>";
                            } else {
                                echo "<div class='alert alert-success'>No picture is uploaded.</div>";
                            }
                        }
                    }
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
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validation()" method="post" enctype="multipart/form-data">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Product Picture</td>
                    <td><input type='file' name='product_pic' id="product_pic" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Name <span class="text-danger">*</span></td>
                    <td><input type='text' name='name' id="name" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Name_Malay <span class="text-danger">*</span></td>
                    <td><input type='text' name='name_malay' id="name_malay" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Description <span class="text-danger">*</span></td>
                    <td><textarea type='text' name='description' id="description" class='form-control' rows="3"></textarea></td>
                </tr>
                <tr>
                    <td>Price <span class="text-danger">*</span></td>
                    <td><input type='text' name='price' id="price" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Promotion Price <span class="text-danger">*</span></td>
                    <td><input type='text' name='promotion_price' id="promotion_price" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Manufacture Date <span class="text-danger">*</span></td>
                    <td><input type='date' name='manufacture_date' id="manufacture_date" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Expired Date <span class="text-danger">*</span></td>
                    <td><input type='date' name='expired_date' id="expired_date" class='form-control' /></td>
                </tr>
            </table>
            <div class="d-flex justify-content-center">
                <input type='submit' value='Save' class='btn btn-primary mx-1' />
                <a href='product_list.php' class='btn btn-danger mx-1'>View Products</a>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script>
        function validation() {
            var name = document.getElementById("name").value;
            var name_malay = document.getElementById("name_malay").value;
            var description = document.getElementById("description").value;
            var price = document.getElementById("price").value;
            var promotion_price = document.getElementById("promotion_price").value;
            var priceValidation = /^[0-9]*[.]?[0-9]*$/;
            var manufacture_date = document.getElementById("manufacture_date").value;
            var expired_date = document.getElementById("expired_date").value;
            var flag = false;
            var msg = "";
            if (name == "" || name_malay == "" || description == "" || price == "" || promotion_price == "" || manufacture_date == "" || expired_date == "") {
                flag = true;
                msg = msg + "Please make sure all fields are not empty!\r\n";
            }
            if (price.match(priceValidation)) {} else {
                flag = true;
                msg = msg + "Please make sure the price is a number!\r\n";
            }
            if (promotion_price.match(priceValidation)) {} else {
                flag = true;
                msg = msg + "Please make sure the promotion price is a number!\r\n";
            }
            if (price <= 0 || promotion_price <= 0) {
                flag = true;
                msg = msg + "Please make sure the price must not be a negative value or zero!\r\n";
            }
            if (price > 1000 || promotion_price > 1000) {
                flag = true;
                msg = msg + "Please make sure the price is not bigger than RM 1000!\r\n";
            }
            if (promotion_price > price) {
                flag = true;
                msg = msg + "Promotion price cannot bigger than normal price!\r\n";
            }
            if (manufacture_date > expired_date) {
                flag = true;
                msg = msg + "Please make sure expired date is late than the manufacture date!\r\n";
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