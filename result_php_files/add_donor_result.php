<?php
    session_start();
    $login = $_SESSION['login'];
    $user = $_SESSION['user'];
    if(isset($login)){
        require_once('../assets/php/connection.php'); //establishes connection to the databases
?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">

            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
            <link rel="stylesheet" href="../Assets/CSS/index.css" type="text/css">
            
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
            <script src="../assets/JS/html2canvas.js"></script>
        </head>

        <body>
        <div class="container box">
            <div class="jumbotron">
            <?php
                $prefix = $_SESSION['prefix'];
                $first_name = $_SESSION['first_name'];
                $last_name = $_SESSION['last_name'];
                $suffix = $_SESSION['suffix'];
                $pc_name = $_SESSION['pc_name'];

                $existing_donor = "SELECT prefix, first_name, last_name, suffix FROM donor WHERE prefix = '$prefix' AND first_name = '$first_name' AND last_name = '$last_name' AND suffix = '$suffix'"; 
                
                //create file and directory
                mkdir("../donor_backgrounds/$pc_name"); //create directory for generated background
                if (count($_POST) && (strpos($_POST['img'], 'data:image/png;base64') === 0)) {
                    
                    $img = $_POST['img'];
                    $img = str_replace('data:image/png;base64,', '', $img);
                    $img = str_replace(' ', '+', $img);
                    $data = base64_decode($img);
                    $file = "../donor_backgrounds/$pc_name/Baruch-College-Background-Wallpaper".".png";
                    
                    //checks if file is sucessfully saved
                    if (file_put_contents($file, $data)) {
            ?>
                    
                    <?php
                        //if user confirms to add PC to existing donor
                        if(isset($_POST['confirm_add_pc'])){ 
                            //add pc to existing donor
                            $id_result = "SELECT donor_id FROM donor WHERE first_name = '$first_name' AND last_name = '$last_name'";
                            $id_row = mysqli_fetch_assoc(mysqli_query($mysql, $id_result));
                            $id = $id_row['donor_id'];
                            $add_pc = "INSERT INTO computer(pc_id, donor_id_f) VALUES('$pc_name','$id')"; 
                            mysqli_query($mysql, $add_pc);
                            
                            require_once('../Assets/php/query_error.php');//checks if query ran successfully
                            
                            //logs user action
                            $log_file = fopen("../assets/log.txt", "a") or die("Unable to open log file!");
                            $log_user = "$user";
                            $log_message = "Add new PC: '$pc_name' to Donor: '$prefix $first_name $last_name $suffix'";
                            require_once('../assets/php/log.php'); 
                            if(isset($_POST['similar_donor'])){ 
                                $full_name = $_POST['similar_donor'];
                        ?>  
                                <div class="alert alert-success" id="success">
                                    Sucessfully added PC: <strong> <?php echo $full_name; ?> </strong>
                                    to Donor:<strong> <?php echo $pc_name; ?></strong>
                                </div>
                        <?php
                            }
                            else{
                        ?>
                                <div class="alert alert-success" id="success">
                                    Sucessfully added Donor: <strong> <?php echo $prefix." ".$first_name." ".$last_name." ".$suffix; ?></strong>
                                    to PC: <strong> <?php echo $pc_name; ?> </strong>
                                </div>
                        <?php
                            }
                        }
                        //user confirms to add new donor
                        elseif(isset($_POST['confirm_new_donor'])){
                            //add new donor to database
                            $add_donor_query = "INSERT INTO donor(prefix,first_name,last_name,suffix) VALUES('$prefix','$first_name','$last_name','$suffix')"; //builds the query for adding a donor
                            mysqli_query($mysql, $add_donor_query); //runs the add_donor query

                            //add new donor to PC
                            $latest_donor_id = "SELECT donor_id FROM donor ORDER BY donor_id DESC LIMIT 1"; //grab the most recent donor_id entry 
                            $id_row = mysqli_fetch_assoc(mysqli_query($mysql, $latest_donor_id));
                            $id_result = $id_row['donor_id'];
                            $add_pc_query = "INSERT INTO computer(pc_id, donor_id_f) VALUES('$pc_name','$id_result')"; //builds the query for adding a computer to the donor
                            mysqli_query($mysql, $add_pc_query); //runs the query

                            require_once('../Assets/php/query_error.php');//checks if query ran successfully
                        
                            //logs user action
                            $log_file = fopen("../assets/log.txt", "a") or die("Unable to open log file!");
                            $log_user = "$user";
                            $log_message = "Add new donor: '$prefix $first_name $last_name $suffix' to PC: '$pc_name'";
                            require_once('../assets/php/log.php'); 
                        ?>  
                            <div class="alert alert-success" id="success">
                                Sucessfully added Donor: <strong> <?php echo $prefix." ".$first_name." ".$last_name." ".$suffix; ?></strong>
                                to PC: <strong> <?php echo $pc_name; ?> </strong>
                            </div>
                        <?php
                        } 

                    } else { //file wasn't created 
                        die("The file could not be saved.");
                    }
                }   
                    ?>
                <?php require_once('../assets/php/return_button.php'); ?>
            </div>
        </div>
        <script>
         if ( window.history.replaceState ) {
            window.history.replaceState( {} , 'index', '../index_php_files/index.php' );
        }  
        </script>
        </body>
        </html>
<?php
    }else{
        header("Location:../index_php_files/index.php");
    }
?>