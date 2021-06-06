<!DOCTYPE HTML>
<html>

<head>
    <title>Homework - Customer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <nav class="navbar navbar-expand-lg sticky-top navbar-dark bg-dark">
            <div class="container-fluid">
                <h3 class="text-light">Claire_Store</h3>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggle" aria-controls="navbarToggle" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-around" id="navbarToggle">
                    <div>
                        <ul class="navbar-nav">
                            <li class="nav-item ">
                                <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="product.php">Create Product</a>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="customer.php">Create Customer</a>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="contact.php">Contact Us</a>
                            </li>
                        </ul>
                    </div>
                </div>
        </nav>
        <div class="page-header">
            <h4 class="p-1">Create Customer</h4>
        </div>
        <?php
        if ($_POST) {
            include 'config/database.php';
            try {
                $query = "INSERT INTO customers SET username=:username, password=:password, firstName=:firstName, lastName=:lastName, gender=:gender, dateOfBirth=:dateOfBirth, accountStatus=:accountStatus";
                $stmt = $con->prepare($query);
                $username = $_POST['username'];
                $password = $_POST['password'];
                $firstName = $_POST['firstName'];
                $lastName = $_POST['lastName'];
                $gender = $_POST['gender'];
                $dateOfBirth = $_POST['dateOfBirth'];
                $accountStatus = $_POST['accountStatus'];
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':password', $password);
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
                        <a href='index.php' class='btn btn-danger'>Back to read products</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>