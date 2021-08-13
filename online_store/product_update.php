<?php
session_start();
if (!isset($_SESSION["cus_username"])) {
    header("Location: login.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Homework - Update Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link href="css/general.css" rel="stylesheet">
    <link href="css/update.css" rel="stylesheet">
    <link href="css/product.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <?php
        include 'navigation.php';
        ?>
        <div class="page-header">
            <h1>Update Product</h1>
            <h6 class="text-danger"> NOTE! Please refresh if you do not see any changes. </h6>
        </div>
        <?php
        $productID = isset($_GET['productID']) ? $_GET['productID'] : die('ERROR: Product record not found.');

        include 'config/database.php';
        try {
            //get the detail of the product from products table in database
            $query = "SELECT * FROM products WHERE productID = :productID ";
            $stmt = $con->prepare($query);
            $stmt->bindParam(":productID", $productID);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $productID = $row['productID'];
            $product_picture = $row['product_pic'];
            $name = $row['name'];
            $name_malay = $row['name_malay'];
            $description = $row['description'];
            $price = $row['price'];
            $promotion_price = $row['promotion_price'];
            $manufacture_date = $row['manufacture_date'];
            $expired_date = $row['expired_date'];
        } catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }

        if ($_POST) {

            $file = $_FILES["product_pic"]["name"];
            $temp = $_FILES["product_pic"]["tmp_name"];
            $folder = "image/product_pic/" . $file;
            $default = "image/product_pic/default.jpg";
            $changePhotoName = explode(".", $_FILES["product_pic"]["name"]);
            $newfilename = 'ID' . $productID . '_' . round(microtime(true)) . '.' . end($changePhotoName);
            $latest_file = "image/product_pic/" . $newfilename;
            $isUploadOK = 1;

            try {
                //check all input field is not empty except image field
                if (empty($_POST['name']) || empty($_POST['description']) || empty($_POST['price']) || empty($_POST['manufacture_date']) || empty($_POST['expired_date'])) {
                    throw new Exception("Make sure all fields are not empty");
                }
                //make sure the price and promo price is number
                if (!is_numeric($_POST['price']) || !is_numeric($_POST['promotion_price'])) {
                    throw new Exception("Please make sure the price is a number");
                }
                //make sure price and promo price is not zero or negative
                if ($_POST['price'] <= 0 || $_POST['promotion_price'] <= 0) {
                    throw new Exception("Please make sure the price must not be a negative value or zero!");
                }
                //make sure price and promo price is not bigger than 1000
                if ($_POST['price'] > 1000 || $_POST['promotion_price'] > 1000) {
                    throw new Exception("Please make sure the price is not bigger than RM 1000.");
                }
                //make sure promo price is always smaller than price
                if ($_POST['price'] < $_POST['promotion_price']) {
                    throw new Exception("Promotion price cannot bigger than normal price.");
                }
                //make sure expired date is not early than manufacture date
                if ($_POST['manufacture_date'] > $_POST['expired_date']) {
                    throw new Exception("Please make sure expired date is late than the manufacture date.");
                }

                if ($file != "") {

                    $imageFileType = strtolower(pathinfo($folder, PATHINFO_EXTENSION));
                    $check = getimagesize($temp);
                    //make sure user uploaded image only
                    if ($check == 0) {
                        $isUploadOK = 0;
                        throw new Exception("Please upload image only! (JPG, JPEG, PNG & GIF)");
                    }

                    //make sure the image is 1:1
                    list($width, $height, $type, $attr) = getimagesize($temp);
                    if ($width != $height) {
                        $isUploadOK = 0;
                        throw new Exception("Please make sure the ratio of the photo is 1:1.");
                    }

                    //make sure the size is lower than 512KB
                    if ($_FILES["product_pic"]["size"] > 512000) {
                        $isUploadOK = 0;
                        throw new Exception("Sorry, your file is too large. Only 512KB is allowed!");
                    }

                    //check image file type
                    if (
                        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                        && $imageFileType != "gif"
                    ) {
                        $isUploadOK = 0;
                        throw new Exception("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
                    }
                }

                //delete the previous uploaded img
                if (isset($_POST['delete_pic'])) {
                    if (unlink($product_picture)) {
                        $product_picture = $default;
                    }
                }

                if ($folder != "") {
                    if($product_picture == $default){
                        $productPic = "product_pic=:product_pic";
                    } else {
                        if(unlink($product_picture)){
                            $productPic = "product_pic=:product_pic";
                        }
                    }
                }

                //update the selected productID's detail
                $query = "UPDATE products SET $productPic, name=:name, name_malay=:name_malay, description=:description,
                         price=:price, promotion_price=:promotion_price, manufacture_date=:manufacture_date, expired_date=:expired_date WHERE productID = :productID";
                $stmt = $con->prepare($query);
                $name = htmlspecialchars(strip_tags($_POST['name']));
                $name_malay = htmlspecialchars(strip_tags($_POST['name_malay']));
                $description = htmlspecialchars(strip_tags($_POST['description']));
                $price = htmlspecialchars(strip_tags($_POST['price']));
                $promotion_price = htmlspecialchars(strip_tags($_POST['promotion_price']));
                $manufacture_date = htmlspecialchars(strip_tags($_POST['manufacture_date']));
                $expired_date = htmlspecialchars(strip_tags($_POST['expired_date']));

                $stmt->bindParam(':productID', $productID);
                if ($file != "") {
                    $product_picture = htmlspecialchars(strip_tags($latest_file));
                    $stmt->bindParam(':product_pic', $latest_file);
                } else {
                    $product_picture = htmlspecialchars(strip_tags($product_picture));
                    $stmt->bindParam(':product_pic', $product_picture);
                }
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':name_malay', $name_malay);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':price', $price);
                $stmt->bindParam(':promotion_price', $promotion_price);
                $stmt->bindParam(':manufacture_date', $manufacture_date);
                $stmt->bindParam(':expired_date', $expired_date);
                if ($stmt->execute()) {
                    if ($folder != "") {
                        if ($isUploadOK == 0) {
                            echo "<div class='alert alert-danger'>Sorry, your file was not uploaded.</div>";
                        } else {
                            move_uploaded_file($temp, "image/product_pic/" . $newfilename);
                        }
                    }
                    echo "<div class='alert alert-success'>Product $productID was updated.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Unable to update product $productID. Please try again.</div>";
                }
            } catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            } catch (Exception $exception) {
                echo "<div class='alert alert-danger'>" . $exception->getMessage() . "</div>";
            }
        } ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?productID={$productID}"); ?>" onsubmit="return validation()" method="post" enctype="multipart/form-data">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td class="col-5">Product ID</td>
                    <td><?php echo htmlspecialchars($productID, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Product Picture</td>
                    <td>
                        <div>
                            <div class='img-block m-2 d-flex'> 
                                <div id="productPicture">
                                    <img src=<?php echo htmlspecialchars($product_picture, ENT_QUOTES); ?> alt='' class='product_image'/>
                                </div>
                                <div  id="deletePic" class="d-flex flex-column justify-content-between">
                                    <button type="submit" class="deleteBtn btn mx-2 p-1" name="delete_pic" <?php if ($product_picture == "image/product_pic/default.jpg"){ echo("id = delImg_btn");} ?>>x</button>
                                </div>
                            </div>
                            
                            <?php if ($product_picture == "image/product_pic/default.jpg"){ 
                                echo '<button type="button" class="changePic btn m-2 p-1" onclick="openForm()">Add Picture</button>';
                            } else {
                                echo '<button type="button" class="changePic btn m-2 p-1" onclick="openForm()">Change Picture</button>';
                            }?>
                            
                            <div id='form-popup'>
                                <div class="d-flex">
                                    <input type='file' name='product_pic' id="product_pic" class='form-control' />
                                    <button type="button" class="cancelBtn btn mx-2 p-1" onclick="closeForm()">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Name <span class="text-danger">*</span></td>
                    <td><input type='text' name='name' id="name" value="<?php echo htmlspecialchars($name, ENT_QUOTES); ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Malay Name <span class="text-danger">*</span></td>
                    <td><input type='text' name='name_malay' id="name_malay" value="<?php echo htmlspecialchars($name_malay, ENT_QUOTES); ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Description <span class="text-danger">*</span></td>
                    <td><textarea name='description' id="description" class='form-control'><?php echo htmlspecialchars($description, ENT_QUOTES); ?></textarea></td>
                </tr>
                <tr>
                    <td>Price <span class="text-danger">*</span></td>
                    <td><input type='text' name='price' id="price" value="<?php echo htmlspecialchars($price, ENT_QUOTES); ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Promotion Price <span class="text-danger">*</span></td>
                    <td><input type='text' name='promotion_price' id="promotion_price" value="<?php echo htmlspecialchars($promotion_price, ENT_QUOTES); ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Manufacture Date <span class="text-danger">*</span></td>
                    <td><input type='date' name='manufacture_date' id="manufacture_date" value="<?php echo htmlspecialchars($manufacture_date, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Expired Date <span class="text-danger">*</span></td>
                    <td><input type='date' name='expired_date' id="expired_date" value="<?php echo htmlspecialchars($expired_date, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
            </table>
            <div class="d-flex justify-content-center">
                <input type='submit' value='Save Changes'class='saveBtn btn mb-3 mx-2' />
                <a href='product_list.php' class='viewBtn btn mb-3 mx-2'>Back to product list</a>
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
        function validation() {
            var name = document.getElementById("name").value;
            var name_malay = document.getElementById("name_malay").value;
            var description = document.getElementById("description").value;
            var price = parseFloat(document.getElementById("price").value());
            var promotion_price = parseFloat(document.getElementById("promotion_price").value());
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
            if (price < promotion_price) {
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

        function openForm() {
            document.getElementById("form-popup").style.display = "block";
        }

        function closeForm() {
            document.getElementById("form-popup").style.display = "none";
        }
    </script>

</body>

</html>