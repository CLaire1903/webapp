<!DOCTYPE HTML>
<html>

<head>
    <title>Homework - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>
    <div class="container-flex bg-secondary d-flex justify-content-center" style="height:577px">
        <div class="d-flex justify-content-center flex-column m-5 border-3 bg-light col-4 rounded-3">
            <?php
            include 'config/database.php';
            if ($_POST) {
                try {
                    $cus_username = $_POST['cus_username'];
                    $password = $_POST['password'];
                    $query = "SELECT * FROM customers WHERE cus_username='$cus_username'";
                    $stmt = $con->prepare($query);
                    $stmt->execute();
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                    if (empty($_POST['cus_username']) || empty($_POST['password'])) {
                        throw new Exception("Make sure all fields are not empty");
                    }
                    if ($row['cus_username'] != $cus_username) {
                        throw new Exception("Username does not exist!");
                    }
                    if($row['password'] != $password){
                        throw new exception("Password incorrect!");
                    } 
                    if($row['accountStatus'] != 'active'){
                        throw new Exception("Sorry, your account is inactive!");
                    } 
                    header("Location: index.php");
                } catch (PDOException $exception) {
                    //for database 'PDO'
                    echo "<div class='alert alert-danger m-2'>" . $exception->getMessage() . "</div>";
                } catch (Exception $exception) {
                    echo "<div class='alert alert-danger m-2'>" . $exception->getMessage() . "</div>";
                }
            }
            ?>
            <div class=" m-2 p-2 mx-auto">
                <h1 class="header text-center mb-4">Claire_Store</h1>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <h4 class="instruction text-center">Please sign in</h4>
                    <div class="username mt-3 input-group-lg">
                        <input type="text" class="form-control" name="cus_username" placeholder="Username">
                    </div>
                    <div class="password mb-3 input-group-lg">
                        <input type="password" class="form-control" name="password" placeholder="Password">
                    </div>
                    <div class="button d-grid">
                        <button type='submit' class='btn btn-primary btn-large'>Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>