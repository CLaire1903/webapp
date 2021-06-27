<!DOCTYPE HTML>
<html>

<head>
    <title>Homework - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>
    <div class="container d-flex justify-content-center">
        <div class="d-flex justify-content-center flex-column m-5 border-3">
            <?php
            if ($_POST) {
                include 'config/database.php';
                try {
                    $query = "SELECT * FROM customers WHERE cus_username=:cus_username";
                    $stmt = $con->prepare($query);
                    $stmt->bindParam(":cus_username", $cus_username);
                    $stmt->execute();
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        if (empty($_POST['cus_username']) || empty($_POST['password'])) {
                            throw new Exception("Make sure all fields are not empty");
                        }
                        if ($_POST['cus_username'] != $row['cus_username']) {
                            throw new Exception("Username does not exist.");
                        }
                    }
                } catch (PDOException $exception) {
                    //for databae 'PDO'
                    echo "<div class='alert alert-danger'>" . $exception->getMessage() . "</div>";
                } catch (Exception $exception) {
                    echo "<div class='alert alert-danger'>" . $exception->getMessage() . "</div>";
                }
            }
            ?>
            <div class="page-header m-2 p-2">
                <h1 class="header text-center m-5">Claire_Store</h1>
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