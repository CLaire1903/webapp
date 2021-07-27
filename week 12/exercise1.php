<!DOCTYPE html>
<html>
    <body>
        <?php
        $file = fopen("test.txt", "r");
        while( ! feof($file)){
            $line = fgets($file);
            echo $line. "<br>";
        }
        fclose($file);
        ?>
        
    </body>
</html>