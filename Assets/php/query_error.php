<?php
    //checks if query ran successfully
    if (mysqli_affected_rows($mysql) != 1){ 
        die("ERROR: Information cannot be added to the database.");
    }
?>