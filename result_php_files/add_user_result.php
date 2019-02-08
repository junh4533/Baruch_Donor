<?php
    session_start();
    $login = $_SESSION['login'];
    $user = $_SESSION['user'];
    if(isset($login) && $login == "admin"){
        require_once('../assets/php/connection.php');
?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <title>Add User Result</title>
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">   
            <link rel="stylesheet" href="../Assets/CSS/index.css" type="text/css">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
        </head>

        <body>
            <div class="container box">
                <div class="jumbotron">
                    <?php
                        //when user submits request to add new user
                        if(isset($_POST['submit_add_user'])){
                            
                            $username = $_POST["username"];
                            $password = $_POST["password"];

                            $existing_user = "SELECT * FROM credentials WHERE username = '$username'";
                            //if user doesn't exist
                            if (mysqli_num_rows(mysqli_query($mysql, $existing_user)) == 0){ 
                                $add_user_query = "INSERT INTO credentials(username, password) VALUES('$username', '$password')"; //builds the query for adding a user
                                mysqli_query($mysql, $add_user_query); //runs the query for adding a user

                                require_once('../Assets/php/query_error.php');//checks if query ran successfully

                                //log user action
                                $log_file = fopen("../assets/log.txt", "a") or die("Unable to open log file!");
                                $log_user = "$user";
                                $log_message = "Add new user: '$username'";
                                require_once('../assets/php/log.php'); 
                    ?>
                                <div class="alert alert-success">
                                    Sucessfully added <strong> <?php echo $_POST["username"]; ?> </strong> as a user
                                </div>
                        <?php
                            }
                            //if user already exists
                            elseif(mysqli_num_rows(mysqli_query($mysql, $existing_user)) == 1){          
                        ?>
                                <div class="alert alert-danger">
                                    <?php echo $_POST["username"]; ?> <strong>already exists.</strong>
                                </div> 
                    <?php
                                }
                        }
                    ?>
                    <div class="center_button"> <button type="submit" class="btn btn-primary" onclick="window.location.href='../index_php_files/admin.php'">Return</button> </div>
                </div>
            <div>
        </body>

        </html>
<?php
    }else{
        header("Location:../index_php_files/index.php");
    }
?>