<!DOCTYPE html>
<html>

<head>
    <title>Exercise 6 - Compare between two random numbers by using css.</title>
</head>
<style>
    .container {
        display: flex;
    }

    .bigger {
        font-weight: bold;
        color: red;
        padding: 10px;
    }

    .smaller {
        color: blue;
        padding: 10px;
    }
</style>

<body>
    <?php
    $number1 = rand(1, 100);
    $number2 = rand(1, 100);

    echo "Number 1 = " . $number1 . "<br>";
    echo "Number 2 = " . $number2 . "<br>";

    if ($number1 > $number2) {
        echo " <div class = container>";
        echo " <div class = bigger> $number1 </div>";
        echo " <div class = smaller> $number2 </div>";
        echo " </div>";
    } else {
        echo " <div class = container>";
        echo " <div class = smaller> $number1 </div>";
        echo " <div class = bigger> $number2 </div>";
        echo " </div>";
    }

    ?>
</body>

</html>