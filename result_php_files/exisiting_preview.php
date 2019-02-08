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

        <title>Existing Preview</title>
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
            if(isset($_POST['confirm_add_pc'])){
                require_once('../assets/php/connection.php'); //establishes connection to the database
                $full_name = $_POST['full_name'];
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
                        <br> 
                        <form method="post" action="add_donor_result.php">
                            <input id="input_img" name="img" type="hidden">
                            <input name="similar_donor" type="hidden" value="<?php echo $full_name ?>">
                            <button type="submit" class="btn btn-primary" name="confirm_add_pc">Confirm</button>  
                        </form>

                        <?php require_once('../assets/php/return_button.php'); ?>
                    </div>  <!-- closing for jumbotron -->
                </div> <!-- closing for wrapper -->

        <?php }//closing for ifset ?>
        
        <script>
            $("#name_here").html("<?php echo $full_name; ?>");
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