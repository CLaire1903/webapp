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
            try {
                if (empty($_POST['password']) ||  empty($_POST['confirmPassword']) ||  empty($_POST['firstName']) ||  empty($_POST['lastName']) ||  empty($_POST['gender']) || empty($_POST['dateOfBirth']) ||  empty($_POST['accountStatus'])) {
                    throw new Exception("Make sure all fields are not empty");
                }
                if ($_POST['password'] != $_POST['confirmPassword']) {
                    throw new Exception("Password and confirm password are not the same.");
                }
                if (strlen($_POST['password']) < 8 || !preg_match("@[0-9]@", $_POST['password']) || !preg_match("@[a-z]@", $_POST['password']) ||!preg_match("@[A-Z]@", $_POST['password'])) {
                    throw new Exception("Password should be at least 8 character, contain at least a number, a <strong>SMALL</strong> letter, a<strong> CAPITAL </strong>letter");
                }
                $today = date('Y-M-D');
                if ($today - $_POST['dateOfBirth'] < 18) {
                    throw new Exception("User must be 18 years old and above.");
                }
                $query = "UPDATE customers SET password=:password, confirmPassword=:confirmPassword, firstName=:firstName, lastName=:lastName,
                         gender=:gender, dateOfBirth=:dateOfBirth, accountStatus=:accountStatus WHERE cus_username = :cus_username";
                $stmt = $con->prepare($query);
                $password = htmlspecialchars(strip_tags($_POST['password']));
                $confirmPassword = htmlspecialchars(strip_tags($_POST['confirmPassword']));
                $firstName = htmlspecialchars(strip_tags($_POST['firstName']));
                $lastName = htmlspecialchars(strip_tags($_POST['lastName']));
                $gender = htmlspecialchars(strip_tags($_POST['gender']));
                $dateOfBirth = htmlspecialchars(strip_tags($_POST['dateOfBirth']));
                $accountStatus = htmlspecialchars(strip_tags($_POST['accountStatus']));
                $stmt->bindParam(':cus_username', $cus_username);
                $stmt->bindParam(':password', $password);
                $stmt->bindParam(':confirmPassword', $confirmPassword);
                $stmt->bindParam(':firstName', $firstName);
                $stmt->bindParam(':lastName', $lastName);
                $stmt->bindParam(':gender', $gender);
                $stmt->bindParam(':dateOfBirth', $dateOfBirth);
                $stmt->bindParam(':accountStatus', $accountStatus);
                if ($stmt->execute()) {
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

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?cus_username={$cus_username}"); ?>" onsubmit="return validation()" method="post">
            <table class='table table-hover table-responsive table-bordered'>
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
            var passwordValidation = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,15}$/;
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
            
            if (password.match(passwordValidation)) {
                flag = false;
            } else{
                flag = true;
                msg = msg + "Password should contain at least a number, a SMALL letter, a CAPITAL letter!\r\n";
            }
            /*var birthDate = new Date(dateOfBirth);
            var difference=Date.now() - birthDate.getFullYear(); 
	 	    var  ageDate = new Date(difference); 
            var calculatedAge = Math.abs(ageDate.getUTCFullYear() - 1970);
            if (calculatedAge < 18){
                flag = true;
                msg = msg + "User must be 18 years old and above!\r\n";
            }*/
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