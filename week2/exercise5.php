<!DOCTYPE html>
<html>

<head>
    <title>Exercise 5 - Compare between two random numbers.</title>
</head>

<body>
    <?php
    $number1 = rand(10, 100);
    $number2 = rand(10, 100);

    echo "Number 1 = " .$number1. "<br>";
    echo "Number 2 = " .$number2. "<br>";

    if ($number1 > $number2) {  // print out “Have a good day!” only when t bigger than 5
        echo "Number 1 is bigger.";
    } else {
        echo "Number 2 is bigger.                                        ";
    }

    ?>
</body>

</html>