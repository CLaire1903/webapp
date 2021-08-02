<?php
session_start();
if (!isset($_SESSION["cus_username"])) {
    header("Location: login.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Homework - Update Customer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <?php
        include 'navigation.php';
        ?>
        <div class="page-header">
            <h1>Update Customer</h1>
        </div>
        <?php
        $cus_username = isset($_GET['cus_username']) ? $_GET['cus_username'] : die('ERROR: Customer record not found.');

        include 'config/database.php';
        try {
            $query = "SELECT * FROM customers WHERE cus_username = :cus_username ";
            $stmt = $con->prepare($query);
            $stmt->bindParam(":cus_username", $cus_username);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $profile_pic = $row['profile_pic'];
            $cus_username = $row['cus_username'];
            $password = $row['password'];
            $confirmPassword = $row['confirmPassword'];
            $firstName = $row['firstName'];
            $lastName = $row['lastName'];
            $gender = $row['gender'];
            $dateOfBirth = $row['dateOfBirth'];
            $regdDateNTime = $row['regdDateNTime'];
            $accountStatus = $row['accountStatus'];
        } catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }

        if ($_POST) {

            $new_filename = $_FILES["new_profile_pic"]["name"];
            $new_tempname = $_FILES["new_profile_pic"]["tmp_name"];
            $new_folder = "image/customer_pic/" . $new_filename;
            $default = "image/product_pic/default.png"; 
            $isUploadOK = 1;

            try {
                if (empty($_POST['password']) ||  empty($_POST['confirmPassword']) ||  empty($_POST['firstName']) ||  empty($_POST['lastName']) ||  empty($_POST['gender']) || empty($_POST['dateOfBirth']) ||  empty($_POST['accountStatus'])) {
                    throw new Exception("Make sure all fields are not empty");
                }
                if ($_POST['password'] != $_POST['confirmPassword']) {
                    throw new Exception("Password and confirm password are not the same.");
                }
                if (strlen($_POST['password']) < 8 || !preg_match("@[0-9]@", $_POST['password']) || !preg_match("@[a-z]@", $_POST['password']) || !preg_match("@[A-Z]@", $_POST['password']) || !preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST["password"])) {
                    throw new Exception("Password should be 8 - 15 character, contain at least a number, a special character, a <strong>SMALL</strong> letter, a <strong>CAPITAL</strong> letter");
                }
                $today = date('Y-M-D');
                if ($today - $_POST['dateOfBirth'] < 18) {
                    throw new Exception("User must be 18 years old and above.");
                }
                if ($new_filename != "") {

                    $imageFileType = strtolower(pathinfo($new_folder, PATHINFO_EXTENSION));
                    $check = getimagesize($new_tempname);
                    if ($check == 0) {
                        $isUploadOK = 0;
                        throw new Exception("File is not an image.");
                    }

                    list($width, $height, $type, $attr) = getimagesize($new_tempname);
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

                if (isset($_POST['delete_pic'])) {
                    //unlink($product_picture);
                    if (!unlink($profile_pic)) { 
                        echo ("$profile_pic cannot be deleted due to an error"); 
                    } 
                    else { 
                        echo ("$profile_pic has been deleted"); 
                        $profile_pic = $default;
                    } 
                }

                if ($new_folder != "") {
                    $new_profilePic = "profile_pic=:new_profile_pic";
                }

                $query = "UPDATE customers SET $new_profilePic, password=:password, confirmPassword=:confirmPassword, firstName=:firstName, lastName=:lastName,
                         gender=:gender, dateOfBirth=:dateOfBirth, accountStatus=:accountStatus WHERE cus_username = :cus_username";
                $stmt = $con->prepare($query);
                $password = htmlspecialchars(strip_tags($_POST['password']));
                $confirmPassword = htmlspecialchars(strip_tags($_POST['confirmPassword']));
                $firstName = htmlspecialchars(strip_tags($_POST['firstName']));
                $lastName = htmlspecialchars(strip_tags($_POST['lastName']));
                $gender = htmlspecialchars(strip_tags($_POST['gender']));
                $dateOfBirth = htmlspecialchars(strip_tags($_POST['dateOfBirth']));
                $accountStatus = htmlspecialchars(strip_tags($_POST['accountStatus']));

                if ($new_filename != "") {
                    $stmt->bindParam(':new_profile_pic', $new_folder);
                } else {
                    $stmt->bindParam(':new_profile_pic', $profile_pic);
                }
                $stmt->bindParam(':cus_username', $cus_username);
                $stmt->bindParam(':password', $password);
                $stmt->bindParam(':confirmPassword', $confirmPassword);
                $stmt->bindParam(':firstName', $firstName);
                $stmt->bindParam(':lastName', $lastName);
                $stmt->bindParam(':gender', $gender);
                $stmt->bindParam(':dateOfBirth', $dateOfBirth);
                $stmt->bindParam(':accountStatus', $accountStatus);
                if ($stmt->execute()) {
                    if ($new_folder != "") {
                        if ($isUploadOK == 0) {
                            echo "<div class='alert alert-success'>Sorry, your file was not uploaded.</div>";
                        } else {
                            if (move_uploaded_file($new_tempname, $new_folder)) {
                                echo "<div class='alert alert-success'>The file " . basename($_FILES["new_profile_pic"]["name"]) . " has been uploaded.</div>";
                            } else {
                                echo "<div class='alert alert-success'>No picture is uploaded.</div>";
                            }
                        }
                    }
                    echo "<div class='alert alert-success'>Record was updated.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
                }
            } catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            } catch (Exception $exception) {
                echo "<div class='alert alert-danger'>" . $exception->getMessage() . "</div>";
            }
        } ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?cus_username={$cus_username}"); ?>" onsubmit="return validation()" method="post" enctype="multipart/form-data">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Profile Picture</td>
                    <td>
                        <div>
                            <div>
                                <?php
                                echo "<div class='img-block m-2'> ";
                                if ($profile_pic != "") {
                                    echo "<img src=$profile_pic alt='' class='image-responsive' style='width:100px; height:100px' /> ";
                                } else {
                                    echo "No picture uploaded.";
                                }
                                ?>
                                <button type="submit" name="delete_pic">Delete Picture</button>
                            </div>
                            <input type='file' name='new_profile_pic' id="new_profile_pic" value=" <?php $profile_pic ?>" class='form-control' />
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="col-5">Username</td>
                    <td><?php echo htmlspecialchars($cus_username, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input type='text' name='password' id="password" value="<?php echo htmlspecialchars($password, ENT_QUOTES); ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Confirm Password</td>
                    <td><input type='text' name='confirmPassword' id="confirmPassword" value="<?php echo htmlspecialchars($confirmPassword, ENT_QUOTES); ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>First Name</td>
                    <td><input type='text' name='firstName' id="firstName" value="<?php echo htmlspecialchars($firstName, ENT_QUOTES); ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Last Name</td>
                    <td><input type='text' name='lastName' id="lastName" value="<?php echo htmlspecialchars($lastName, ENT_QUOTES); ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td>
                        <div class="form-check">
                            <label>
                                <input type="radio" name="gender" class="gender" value="male" <?php echo ($gender == 'male') ? 'checked' : '' ?>>
                                Male
                                <span class="select"></span>
                            </label>
                        </div>
                        <div class="form-check">
                            <label>
                                <input type="radio" name="gender" class="gender" value="female" <?php echo ($gender == 'female') ? 'checked' : '' ?>>
                                Female
                                <span class="select"></span>
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Date Of Birth</td>
                    <td><input type='date' name='dateOfBirth' id="dateOfBirth" value="<?php echo htmlspecialchars($dateOfBirth, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Registration Date and Time</td>
                    <td><?php echo htmlspecialchars($regdDateNTime, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Account Status</td>
                    <td>
                        <div class="form-check">
                            <label>
                                <input type="radio" name="accountStatus" class="accountStatus" value="active" <?php echo ($accountStatus == 'active') ? 'checked' : '' ?>>
                                Active
                                <span class="select"></span>
                            </label>
                        </div>
                        <div class="form-check">
                            <label>
                                <input type="radio" name="accountStatus" class="accountStatus" value="inactive" <?php echo ($accountStatus == 'inactive') ? 'checked' : '' ?>>
                                Inactive
                                <span class="select"></span>
                            </label>
                        </div>
                    </td>
                </tr>
            </table>
            <div class="d-flex justify-content-center">
                <input type='submit' value='Save Changes' class='btn btn-primary m-2 ' />
                <a href='customer_list.php' class='btn btn-danger m-2'>Back to customer list</a>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script>
        function validation() {
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirmPassword").value;
            var passwordValidation = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,15}$/;
            var firstName = document.getElementById("firstName").value;
            var lastName = document.getElementById("lastName").value;
            var gender = document.querySelectorAll("input[type=radio][name=gender]:checked");
            var dateOfBirth = document.getElementById("dateOfBirth").value;
            var accountStatus = document.querySelectorAll("input[type=radio][name=accountStatus]:checked");
            var flag = false;
            var msg = "";
            if (password == "" || confirmPassword == "" || firstName == "" || lastName == "" || gender.length == 0 || dateOfBirth == "" || accountStatus.length == 0) {
                flag = true;
                msg = msg + "Please make sure all fields are not empty!\r\n";
            }
            if (password != confirmPassword) {
                flag = true;
                msg = msg + "Password and confirm password are not the same!\r\n";
            }
            if (password.length < 8 || password.length > 15) {
                flag = true;
                msg = msg + "Password should be 8 - 15 character!\r\n";
            }

            if (password.match(passwordValidation)) {} else {
                flag = true;
                msg = msg + "Password should contain at least a number, a special character, a SMALL letter, a CAPITAL letter!\r\n";
            }
            var birthDate = new Date(dateOfBirth);
            var difference = Date.now() - birthDate.getFullYear();
            var ageDate = new Date(difference);
            var calculatedAge = Math.abs(ageDate.getUTCFullYear() - 1970);
            if (calculatedAge < 18) {
                flag = true;
                msg = msg + "User must be 18 years old and above!\r\n";
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