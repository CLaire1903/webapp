<!DOCTYPE html>
<html>

<head>
    <title>Homework 1 - Generate dropdown menu.</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous" />
</head>

<body>
    <div class="container-fluid">
        <div class="p-3 text-center">
            <h3>What is your date of birth?</h3>
        </div>
        <div>
            <div class="dropdown row justify-content-center">
            <button class="btn btn-secondary dropdown-toggle col-md-2 m-2" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    DAY
                </button>
                <ul class="dropdown-menu col-md-2" aria-labelledby="dropdownMenuButton1">
                    <?php
                    for ($day = 1; $day <= 31; $day++) {
                        echo "<li><a class=dropdown-item > $day </a></li>";
                    }
                    ?>
                </ul>
                <button class="btn btn-secondary dropdown-toggle col-md-2 m-2" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    MONTH
                </button>
                <ul class="dropdown-menu col-md-2" aria-labelledby="dropdownMenuButton1">
                    <?php
                    for ($num = 0; $num < 12; $num++) {
                        $month = array("January", "February","March","April","May","June","July","August","September","October","November","December");
                        echo "<li><a class=dropdown-item > $month[$num] </a></li>";
                    }
                    ?>
                </ul>
                <button class="btn btn-secondary dropdown-toggle col-md-2 m-2" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    YEAR
                </button>
                <ul class="dropdown-menu col-md-2" aria-labelledby="dropdownMenuButton1">
                    <?php
                    for ($year = 1900; $year <= 2021; $year++) {
                        echo "<li><a class=dropdown-item > $year </a></li>";
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>