<!DOCTYPE html>
<html>

<head>
    <title>Exercise 6 - Compare between two random numbers by using different colour.</title>
</head>

<body>
    <?php
    $number1 = rand(10, 100);
    $number2 = rand(10, 100);

    echo "Number 1 = " .$number1. "<br>";
    echo "Number 2 = " .$number2. "<br>";

    if ($number1 > $number2) {
        echo "<font color = 'red'> <strong> $number1 </strong> </font>";
        echo "<font color = 'blue'> $number2 </font>";
    } else {
        echo "<font color = 'blue'> $number1 </font>";
        echo "<font color = 'red'> <strong> $number2 </strong> </font>";
    }

    ?>
</body>

</html>