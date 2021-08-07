<?php
session_start();
if (!isset($_SESSION["cus_username"])) {
    header("Location: login.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Customer Detail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link href="general.css" rel="stylesheet">

    <style>
        html, body {
        font-family: 'Poppins', sans-serif;
        }
        .cus_image {
            width:100px; 
            height:100px'
        }
    </style>
</head>

<body>
    <div class="container">
        <?php
        include 'navigation.php';
        ?>
        <div class="page-header">
            <h1>Read Customer</h1>
        </div>

        <?php
        $cus_username = isset($_GET['cus_username']) ? $_GET['cus_username'] : die('ERROR: Customer record not found.');

        include 'config/database.php';

        try {
            $query = "SELECT * FROM customers WHERE cus_username = :cus_username";
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
        ?>

        <table class='table table-hover table-responsive table-bordered'>
            <tr>
                <td class="col-4">Profile Picture</td>
                <td>
                    <?php
                    echo "<div class='img-block'> ";
                    if ($profile_pic != "") {
                        echo "<img src= $profile_pic alt='' class='cus_image'/> ";
                    } else {
                        echo "No picture uploaded.";
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td>Username</td>
                <td><?php echo htmlspecialchars($cus_username, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>password</td>
                <td><?php echo htmlspecialchars($password, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Confirm password</td>
                <td><?php echo htmlspecialchars($confirmPassword, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>First Name</td>
                <td><?php echo htmlspecialchars($firstName, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Last Name</td>
                <td><?php echo htmlspecialchars($lastName, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Gender</td>
                <td><?php echo htmlspecialchars($gender, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Date Of Birth</td>
                <td><?php echo htmlspecialchars($dateOfBirth, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Registration Date and Time</td>
                <td><?php echo htmlspecialchars($regdDateNTime, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Account Status</td>
                <td><?php echo htmlspecialchars($accountStatus, ENT_QUOTES);  ?></td>
            </tr>
        </table>
        <div class="d-flex justify-content-center">
            <?php
            echo "<a href='customer_update.php?cus_username={$cus_username}' class='actionBtn updateBtn btn mb-3 mx-2'>Update Customer</a>";
            ?>
            <a href='customer_list.php' class='viewBtn btn mb-3 mx-2'>Back to customer list</a>
        </div>
        <div class="footer bg-dark">
            <?php
            include 'footer.php';
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>