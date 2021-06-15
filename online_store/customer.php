<!DOCTYPE HTML>
<html>

<head>
    <title>Homework - Customer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <?php
        include 'navigation.php';
        ?>
        <div class="page-header">
            <h4 class="p-1">Create Customer</h4>
        </div>
        <?php
        if ($_POST) {
            if ($_POST['username'] != "" &&  $_POST['password'] != "" &&  $_POST['confirmPassword'] != "" &&  $_POST['firstName'] != "" &&  $_POST['lastName'] != "" &&  $_POST['gender'] != "" &&  $_POST['dateOfBirth'] != "" &&  $_POST['accountStatus']) {
                if (strlen($_POST['username']) >= 6 && (strrpos($_POST['username'], " ") == false)) {
                    if ($_POST['password'] == $_POST['confirmPassword']) {
                        if (strlen($_POST['password']) >= 8) {
                            if (preg_match("@[0-9]@", $_POST['password'])) {
                                if (preg_match("@[a-z]@", $_POST['password'])) {
                                    if (preg_match("@[A-Z]@", $_POST['password'])) {
                                        $today = date('Y-M-D');
                                        if ($today - $_POST['dateOfBirth'] >= 18) {
                                            include 'config/database.php';
                                            try {
                                                $query = "INSERT INTO customers SET username=:username, password=:password,confirmPassword=:confirmPassword, firstName=:firstName, lastName=:lastName, gender=:gender, dateOfBirth=:dateOfBirth, accountStatus=:accountStatus";
                                                $stmt = $con->prepare($query);
                                                $username = $_POST['username'];
                                                $password = $_POST['password'];
                                                $confirmPassword = $_POST['confirmPassword'];
                                                $firstName = $_POST['firstName'];
                                                $lastName = $_POST['lastName'];
                                                $gender = $_POST['gender'];
                                                $dateOfBirth = $_POST['dateOfBirth'];
                                                $accountStatus = $_POST['accountStatus'];
                                                $stmt->bindParam(':username', $username);
                                                $stmt->bindParam(':password', $password);
                                                $stmt->bindParam(':confirmPassword', $confirmPassword);
                                                $stmt->bindParam(':firstName', $firstName);
                                                $stmt->bindParam(':lastName', $lastName);
                                                $stmt->bindParam(':gender', $gender);
                                                $stmt->bindParam(':dateOfBirth', $dateOfBirth);
                                                $stmt->bindParam(':accountStatus', $accountStatus);
                                                if ($stmt->execute()) {
                                                    echo "<div class='alert alert-success'>Record was saved.</div>";
                                                } else {
                                                    echo "<div class='alert alert-danger'>Unable to save record.</div>";
                                                }
                                            } catch (PDOException $exception) {
                                                die('ERROR: ' . $exception->getMessage());
                                            }
                                        } else {
                                            echo "<div class='alert alert-danger'> User must be 18 years old and above. </div>";
                                        }
                                    } else {
                                        echo "<div class='alert alert-danger'> Passowrd must 
                            contain at least a<strong> CAPITAL </strong>letter. </div>";
                                    }
                                } else {
                                    echo "<div class='alert alert-danger'> Passowrd must 
                        contain at least a <strong>SMALL</strong> letter. </div>";
                                }
                            } else {
                                echo "<div class='alert alert-danger'> Passowrd must contain at least a number. </div>";
                            }
                        } else {
                            echo "<div class='alert alert-danger'> Password should be at least 8 character.</div>";
                        }
                    } else {
                        echo "<div class='alert alert-danger'> Password and confirm password are not the same.</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>Username must be at least 6 characters and no space included.</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Make sure all fields are not empty</div>";
            }
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Username</td>
                    <td><input type='text' name='username' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input type='password' name='password' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Confirm Password</td>
                    <td><input type='password' name='confirmPassword' class='form-control' /></td>
                </tr>
                <tr>
                    <td>First Name</td>
                    <td><input type='text' name='firstName' class='form-control'></td>
                </tr>
                <tr>
                    <td>Last Name</td>
                    <td><input type='text' name='lastName' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gender" id="maleButton" value="male">
                            <label class="form-check-label" for="maleButton">
                                Male
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gender" id="femaleButton" value="female">
                            <label class="form-check-label" for="femaleButton">
                                Female
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Date Of Birth</td>
                    <td><input type='date' name='dateOfBirth' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Account Status</td>
                    <td>
                        <div class="col-auto">
                            <label class="visually-hidden" for="autoSizingSelect">Preference</label>
                            <select class="form-select" id="autoSizingSelect" name="accountStatus">
                                <option selected>Choose...</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <a href='index.php' class='btn btn-danger'>Back</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>