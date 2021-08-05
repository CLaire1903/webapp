<?php
session_start();
if (!isset($_SESSION["cus_username"])) {
    header("Location: login.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Homework - Customer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>
<style>
</style>

<body>
    <div class="container">
        <?php
        include 'navigation.php';
        ?>
        <div class="page-header">
            <h4 class="p-1">Create Customer</h4>
            <h6 class="text-danger"> Please complete the form below with * completely. </h6>
        </div>
        <?php
        if ($_POST) {
            include 'config/database.php';
            $filename = $_FILES["profile_pic"]["name"];
            $tempname = $_FILES["profile_pic"]["tmp_name"];
            $folder = "image/customer_pic/" . $filename;
            $default = "image/product_pic/default.png";
            $changePhotoName = explode(".", $_FILES["profile_pic"]["name"]);
            $newfilename = $_POST['cus_username'] . '.' . end($changePhotoName);
            $latest_file = "image/customer_pic/" . $newfilename;
            $isUploadOK = 1;
            try {
                if (empty($_POST['cus_username']) || empty($_POST['password']) ||  empty($_POST['confirmPassword']) ||  empty($_POST['firstName']) ||  empty($_POST['lastName']) ||  empty($_POST['gender']) || empty($_POST['dateOfBirth']) ||  empty($_POST['accountStatus'])) {
                    throw new Exception("Make sure all fields are not empty");
                }
                if (15 < strlen($_POST['cus_username']) || strlen($_POST['cus_username']) < 6 || (strpos($_POST['cus_username'], ' ') !== false)) {
                    throw new Exception("Username must be 6 - 15 characters and no space included.");
                }
                if ($_POST['password'] != $_POST['confirmPassword']) {
                    throw new Exception("Password and confirm password are not the same.");
                }
                if (15 < strlen($_POST['password']) || strlen($_POST['password']) < 8 || !preg_match("@[0-9]@", $_POST['password']) || !preg_match("@[a-z]@", $_POST['password']) || !preg_match("@[A-Z]@", $_POST['password']) || !preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST["password"])) {
                    throw new Exception("Password should be 8 - 15 character, contain at least a number, a special character, a <strong>SMALL</strong> letter, a<strong> CAPITAL </strong>letter");
                }
                $today = date('Y-M-D');
                if ($today - $_POST['dateOfBirth'] < 18) {
                    throw new Exception("User must be 18 years old and above.");
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

                    if ($_FILES["profile_pic"]["size"] > 512000) {
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
                    $profilePic = "profile_pic=:profile_pic";
                }

                $query = "INSERT INTO customers SET $profilePic, cus_username=:cus_username, password=:password,confirmPassword=:confirmPassword, firstName=:firstName, lastName=:lastName, gender=:gender, dateOfBirth=:dateOfBirth, accountStatus=:accountStatus";
                $stmt = $con->prepare($query);
                $cus_username = $_POST['cus_username'];
                $password = $_POST['password'];
                $confirmPassword = $_POST['confirmPassword'];
                $firstName = $_POST['firstName'];
                $lastName = $_POST['lastName'];
                $gender = $_POST['gender'];
                $dateOfBirth = $_POST['dateOfBirth'];
                $accountStatus = $_POST['accountStatus'];
                if ($filename != ""){
                    $stmt->bindParam(':profile_pic', $latest_file);
                }else {
                    $stmt->bindParam(':profile_pic', $default);
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
                    if ($folder != "") {
                        if ($isUploadOK == 0) {
                            echo "<div class='alert alert-success'>Sorry, your file was not uploaded.</div>";
                        } else {
                            if (move_uploaded_file($tempname, "image/customer_pic/" . $newfilename)) {
                                echo "<div class='alert alert-success'>The file " . basename($_FILES["profile_pic"]["name"]) . " has been uploaded.</div>";
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
                echo "<div class='alert alert-danger'>" . $exception->getMessage() . "</div>";
            } catch (Exception $exception) {
                echo "<div class='alert alert-danger'>" . $exception->getMessage() . "</div>";
            }
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validation()" method="post" enctype="multipart/form-data">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Profile Picture</td>
                    <td><input type='file' name='profile_pic' id="profile_pic" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Username <span class="text-danger">*</span></td>
                    <td><input type='text' name='cus_username' id="cus_username" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Password <span class="text-danger">*</span></td>
                    <td><input type='text' name='password' id="password" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Confirm Password <span class="text-danger">*</span></td>
                    <td><input type='text' name='confirmPassword' id="confirmPassword" class='form-control' /></td>
                </tr>
                <tr>
                    <td>First Name <span class="text-danger">*</span></td>
                    <td><input type='text' name='firstName' id="firstName" class='form-control'></td>
                </tr>
                <tr>
                    <td>Last Name <span class="text-danger">*</span></td>
                    <td><input type='text' name='lastName' id="lastName" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Gender <span class="text-danger">*</span></td>
                    <td>
                        <div class="form-check">
                            <label>
                                <input type="radio" name="gender" value="male">
                                Male
                                <span class="select"></span>
                            </label>
                        </div>
                        <div class="form-check">
                            <label>
                                <input type="radio" name="gender" value="female">
                                Female
                                <span class="select"></span>
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Date Of Birth <span class="text-danger">*</span></td>
                    <td><input type='date' name='dateOfBirth' id="dateOfBirth" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Account Status <span class="text-danger">*</span></td>
                    <td>
                        <div class="form-check">
                            <label>
                                <input type="radio" name="accountStatus" value="active">
                                Active
                                <span class="select"></span>
                            </label>
                        </div>
                        <div class="form-check">
                            <label>
                                <input type="radio" name="accountStatus" value="inactive">
                                Inactive
                                <span class="select"></span>
                            </label>
                        </div>
                    </td>
                </tr>
            </table>
            <div class="d-flex justify-content-center">
                <input type='submit' value='Save' class='btn btn-primary mx-1' />
                <a href='customer_list.php' class='btn btn-danger mx-1'>View Customer</a>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script>
        function validation() {
            var cus_username = document.getElementById("cus_username").value;
            var password = document.getElementById("password").value;
            var passwordValidation = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,15}$/;
            var confirmPassword = document.getElementById("confirmPassword").value;
            var firstName = document.getElementById("firstName").value;
            var lastName = document.getElementById("lastName").value;
            var gender = document.querySelectorAll("input[type=radio][name=gender]:checked");
            var dateOfBirth = document.getElementById("dateOfBirth").value;
            var accountStatus = document.querySelectorAll("input[type=radio][name=accountStatus]:checked");
            var flag = false;
            var msg = "";
            if (cus_username == "" || password == "" || confirmPassword == "" || firstName == "" || lastName == "" || gender.length == 0 || dateOfBirth == "" || accountStatus.length == 0) {
                flag = true;
                msg = msg + "Please make sure all fields except profile picture are not empty!\r\n";
            }
            if (cus_username.length < 6 || cus_username.length > 15 || cus_username.indexOf(' ') >= 0) {
                flag = true;
                msg = msg + "Username must be 6 - 15 characters and no space included!\r\n";
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
                msg = msg + "Password should contain at least a number, a special character, a SMALL letter and a CAPITAL letter!\r\n";
            }
            var birthDate = new Date(dateOfBirth);
            var difference = Date.now() - birthDate.getTime();
            var ageDate = new Date(difference);
            var calculatedAge = Math.abs(ageDate.getUTCFullYear() - 1970);
            if (calculatedAge < 18) {
                flag = true;
                msg = msg + "User must be 18 years old.\r\n";
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