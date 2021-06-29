<?php
session_start();
if (!isset($_SESSION["cus_username"])) {
    header("Location: login.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Homework - contact</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <?php
        include 'navigation.php';
        ?>
        <div class="contain d-flex justify-content-center" style="background-image: url('image/background.jpg'); background-size:cover; height:500px;">
            <div>
                <h1 class="text-light p-5 text-center">
                    Feel free to contact us.
                </h1>
                <div id="myContact" class="text-light d-flex">
                    <div id="phone" class="col-3 m-2 p-2 bg-dark rounded">
                        <b>Phone Number :</b>
                        <br>
                        016 - 537 6154
                        <br>
                        011 - 5762 3721
                    </div>
                    <div id="email" class="col-4 m-2 p-2 bg-dark rounded">
                        <b>Email :</b>
                        <br>
                        <a href="mailto:tanghuey@hotmail.com" class="text-light">tanghuey@hotmail.com</a>
                        <br>
                        <a href="mailto:clairetang1903@gmail.com" class="text-light">clairetang1903@gmail.com</a>
                    </div>
                    <div id="address" class="col-4 m-2 p-2 bg-dark rounded">
                        <b>Address :</b>
                        <br>
                        63, Jalan Unta Kawasan Rumah Hijau 34000 Taiping, Perak.
                    </div>
                </div>
            </div>
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