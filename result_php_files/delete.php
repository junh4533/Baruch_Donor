<?php  
    session_start();
    $login = $_SESSION['login'];
    $user = $_SESSION['user'];
    if(isset($login) && isset($_POST['delete_button'])){
        require_once('../assets/php/connection.php'); //establishes connection to the database
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Delete</title>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
        <link rel="stylesheet" href="../Assets/CSS/index.css" type="text/css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    </head>

    <body>

    <?php
        $delete_pc = $_POST['delete_button'];
        $donor_query = "SELECT * FROM donor INNER JOIN computer ON computer.donor_id_f = donor.donor_id AND computer.pc_id = '$delete_pc'";
        $donor_result = mysqli_query($mysql, $donor_query);

        while($donor_row = mysqli_fetch_assoc($donor_result)){
            $prefix = $donor_row['prefix'];
            $first_name = $donor_row['first_name'];
            $last_name = $donor_row['last_name'];
            $suffix = $donor_row['suffix'];
        
            //deletes specified PC from database
            $delete_query = "DELETE FROM computer WHERE pc_id = '$delete_pc' ";
            mysqli_query($mysql, $delete_query);

            //deletes local background and folder
            $delete_folder = "../donor_backgrounds/" . $delete_pc;
            $delete_file = $delete_folder . "/Baruch-College-Background-Wallpaper.png";
            unlink($delete_file);
            rmdir($delete_folder);

            //check if folder was deleted
            if(file_exists($delete_folder)){
                die("File did not delete sucessfully.");
            }

            require_once('../Assets/php/query_error.php');//checks if query ran successfully

            //logs user action
            $log_file = fopen("../assets/log.txt", "a") or die("Unable to open log file!");
            $log_user = "$user";
            $log_message = "Delete: '$delete_pc' from Donor: $prefix $first_name $last_name $suffix";
            require_once('../assets/php/log.php'); 

            //refreshes the search results
            $donor_query = $_SESSION['donor_query'];
            $donor_result = mysqli_query($mysql, $donor_query);
            require('holder.php'); 
        }
    ?>
    </body>

    </html>
<?php
    }else{
        header("Location:../index_php_files/index.php");
    }
?>