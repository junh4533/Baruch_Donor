<?php
    session_start();
    $login = $_SESSION['login'];
    if(isset($login)){
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <title>Preview</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
        <link rel="stylesheet" href="../Assets/CSS/index.css" type="text/css">
        <link rel="stylesheet" href="../Assets/CSS/preview.css" type="text/css">

        <script src="../assets/JS/html2canvas.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    </head>

    <body>  
        <?php
            //on click of add_donor button
            if(isset($_POST['submit_add_donor'])){
                require_once('../assets/php/connection.php'); //establishes connection to the database
                
        ?>     
                <div id="cover"></div>
                
                <div id="wrapper">

                    <div class="row" id="background_pic">      
                        <div class="col-2"></div>

                        <div class="col-8">
                            <div id="start"></div>

                            <div id="center"><strong><h2 id="name_here"></h2></strong></div>

                            <div id="end"></div>
                        </div>

                        <div class="col-2"></div>
                    </div>

                    <div class="jumbotron">

                        <canvas id="preview_canvas" width="950px" height="550px"></canvas> <!-- actual preview -->
                        <?php
                            //sets the form inputs as variables
                            $prefix = $_POST["prefix"];
                            $first_name = $_POST["first_name"];
                            $last_name = $_POST["last_name"];
                            $suffix = $_POST["suffix"];
                            $pc_name = $_POST["pc_name"];

                            //creates sessions for input variables
                            $_SESSION['prefix'] = $prefix;
                            $_SESSION['first_name'] = $first_name;
                            $_SESSION['last_name'] = $last_name;
                            $_SESSION['suffix'] = $suffix;
                            $_SESSION['pc_name'] = $pc_name;

                            //builds the queries
                            $existing_donor = "SELECT prefix, first_name, last_name, suffix FROM donor WHERE prefix = '$prefix' AND first_name = '$first_name' AND last_name = '$last_name' AND suffix = '$suffix'";
                            $existing_pc = "SELECT pc_id FROM computer WHERE '$pc_name' = pc_id";
                            $similar_donor = "SELECT prefix, first_name, last_name, suffix FROM donor WHERE first_name = '$first_name' AND last_name = '$last_name'"; 
                            $existing_donor_result = mysqli_query($mysql, $existing_donor);
                            $existing_pc_result = mysqli_query($mysql, $existing_pc);
                            $similar_donor_result = mysqli_query($mysql, $similar_donor);

                            //donor and pc already exists
                            if (mysqli_num_rows($existing_donor_result) > 0 && mysqli_num_rows($existing_pc_result) > 0){ 
                        ?>
                                <div class="alert alert-danger">
                                    <strong> <?php echo $pc_name; ?> </strong> is already assigned to
                                    <strong> <?php echo $prefix." ".$first_name." ".$last_name." ".$suffix; ?> </strong>.
                                </div>

                                <!-- only used to display background preview, not actually a form to submit -->
                                <form method="post" action="add_donor_result.php">
                                    <input id="input_img" name="img" type="hidden" value="">
                                </form>
                        <?php
                            }
                            //donor already exists
                            elseif (mysqli_num_rows(mysqli_query($mysql, $existing_donor)) > 0){ 
                        ?> 
                                <div class="alert alert-danger">
                                    <strong> <?php echo $prefix." ".$first_name." ".$last_name." ".$suffix; ?> </strong>
                                    is already an existing donor. Do you want to add a PC to the existing donor?
                                </div>
                                <form method="post" action="add_donor_result.php">
                                    <input id="input_img" name="img" type="hidden">
                                    <button type="submit" class="btn btn-primary" name="confirm_add_pc">Confirm</button>
                                </form>
                        <?php
                            }
                            //PC is already occupied
                            elseif (mysqli_num_rows(mysqli_query($mysql, $existing_pc)) > 0){
                        ?>
                                <div class="alert alert-danger">
                                    Another donor is already assigned to PC: 
                                    <strong> <?php echo $pc_name; ?> </strong>.
                                    Please remove all donors from the selected PC before adding a new donor.
                                </div>
                                <!-- only used to display background preview, not actually a form to submit -->
                                <form method="post" action="add_donor_result.php">
                                    <input id="input_img" name="img" type="hidden">
                                </form>  
                        <?php
                            }
                            //a similar donor exists
                            elseif(mysqli_num_rows($similar_donor_result) > 0){
                        ?>
                                <div class="alert alert-danger">
                                    A very similar donor exists with different prefix and/or suffix:
                                    <strong>
                                        <?php 
                                            $donor_row = mysqli_fetch_assoc($similar_donor_result);
                                            $full_name = ""; //variable for similar donor preview

                                            foreach($donor_row as $donor_attribute) {
                                                echo $donor_attribute, " ";
                                                $full_name .= $donor_attribute;
                                                $full_name .= " ";
                                            }
                                        ?>
                                    </strong>.
                                    <br>
                                    Would you like to add PC:<strong><?php echo $pc_name; ?></strong> to the existing donor?
                                </div>
                                <form method="post" action="add_donor_result.php">
                                    <input id="input_img" name="img" type="hidden">
                                    <button type="submit" class="btn btn-primary" name="confirm_new_donor">No, this is a different donor</button>   
                                </form>  

                                <form method="post" action="exisiting_preview.php">
                                    <input name="full_name" type="hidden" value="<?php echo $full_name ?>">
                                    <button type="submit" class="btn btn-primary" name="confirm_add_pc">Yes, add this PC to the existing donor</button>  
                                </form>
                        <?php      
                            }
                            //There are NO matching results. User can add this new donor.
                            else{ 
                        ?>
                                <br>
                                <form method="post" action="add_donor_result.php">
                                    <input id="input_img" name="img" type="hidden" value="">
                                    <button type="submit" class="btn btn-primary" name="confirm_new_donor">Confirm</button>
                                </form>  

                            <?php } //closing for else ?>
                        <button type="submit" class="btn btn-danger" onclick="window.history.back();">Return</button>
                    </div>  <!-- closing for jumbotron -->

                </div> <!-- closing for wrapper -->

        <?php }//closing for ifset ?>
        
        <script>
            $("#name_here").html("<?php echo $prefix." ".$first_name." ".$last_name." ".$suffix; ?>");
            html2canvas(document.querySelector("#background_pic")).then(canvas => {
                document.body.appendChild(canvas);
                document.getElementById('input_img').value = canvas.toDataURL();
                canvas.style.display="none";

                //draw smaller canvas
                var preview_canvas = document.getElementById("preview_canvas");
                var ctx = preview_canvas.getContext("2d");
                ctx.scale(.5, .5);
                ctx.drawImage(canvas, 0, 0);
            });
        </script>
    </body>

    </html>
<?php
    }else{
        header("Location:../index_php_files/index.php");
    }
?>