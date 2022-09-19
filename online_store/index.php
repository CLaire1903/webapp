<!DOCTYPE HTML>
<html>

<head>
    <title>Homework - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>
<style>
    html, body {
    font-family: 'Poppins', sans-serif;
    }
    #logo img {
        width: 150px;
        height: 150px;
    }
    .login {
        height:100%;
        width:100%;
        position:fixed;
        background-image: url('image/logo/background.png'); 
        background-size: contain; 
    }
    .loginForm {
        background-color: white;
    }
    .loginBtn {
        background-color: rgb(225, 127, 147);
    }
</style>

<body>
    <div class="login container-flex d-flex justify-content-center">
        <div class="loginForm d-flex justify-content-center flex-column m-5 border-3 col-8 col-md-5 col-lg-4 rounded-3">
            <?php
            session_start();
            include 'config/database.php';
            if (isset($_GET['error']) && $_GET['error'] == "restrictedAccess") {
                $errorMessage = "Please login for further proceed!";
            }
            if ($_POST) {
                try {
                    $cus_username = strtolower($_POST['cus_username']);
                    $query = "SELECT * FROM customers WHERE cus_username= :cus_username";
                    $stmt = $con->prepare($query);
                    $password = $_POST['password'];
                    $stmt->bindParam(':cus_username', $cus_username);
                    $stmt->execute();
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    $db_cus_username = $row['cus_username'];
                    if (empty($_POST['cus_username']) || empty($_POST['password'])) {
                        throw new Exception("Make sure all fields are not empty");
                    }
                    if ($db_cus_username != $cus_username) {
                        throw new Exception("Username does not exist!");
                    }
                    if ($row['password'] != $password) {
                        throw new exception("Password incorrect!");
                    }
                    if ($row['accountStatus'] != 'active') {
                        throw new Exception("Sorry, your account is inactive!");
                    }
                    $_SESSION['cus_username'] = $row['cus_username'];
                    header("Location: home.php");
                } catch (PDOException $exception) {
                    //for database 'PDO'
                    $errorMessage = $exception->getMessage();
                } catch (Exception $exception) {
                    $errorMessage = $exception->getMessage();
                }
            }
            ?>
            <div class="p-2 mx-auto">
                <div id="logo" class="d-flex justify-content-center ">
                    <img src="image/logo/online-store-logoB.png">
                </div>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validation()" method="post">
                    <h4 class="instruction mt-3 text-center">Please sign in</h4>
                    <?php
                    if (isset($errorMessage)) { ?>
                        <div class='alert alert-danger m-2'><?php echo $errorMessage ?></div>
                    <?php } ?>
                    <div class="username mt-3 input-group-lg">
                        <input type="text" class="form-control" id="cus_username" name="cus_username" placeholder="Username" value="<?php echo (isset($_POST['cus_username'])) ? $_POST['cus_username'] : ''; ?>">
                    </div>
                    <div class="password mb-3 input-group-lg">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" value="<?php echo (isset($_POST['password'])) ? $_POST['password'] : ''; ?>">
                    </div>

                    <div class="button d-grid">
                        <button type='submit' class='loginBtn btn btn-large'>Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script>
        function validation() {
            var cus_username = document.getElementById("cus_username").value;
            var password = document.getElementById("password").value;
            var flag = false;
            var msg = "";
            if (cus_username == '') {
                flag = true;
                msg = msg + "Please enter your username!\r\n";
            }
            if (password == '') {
                flag = true;
                msg = msg + "Please enter your password!\r\n";
            }
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