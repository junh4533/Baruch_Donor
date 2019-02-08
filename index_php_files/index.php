<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Index</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Assets/CSS/index.css" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
</head>

<body>

  <?php 
    if(isset($_POST['submit_login'])){
      require_once('../assets/php/connection.php'); 
      session_start();
      $username = $_POST["username"];
      $password = $_POST["password"];
      $_SESSION['user'] = $username;

      //query the database for username and password
      $query_user = "select username FROM credentials WHERE username = '$username' ";
      $query_password = "select password FROM credentials WHERE password = '$password' ";
      $user_result = mysqli_query($mysql, $query_user);
      $password_result = mysqli_query($mysql, $query_password);

      //log user action
      $log_file = fopen("../assets/log.txt", "a") or die("Unable to open log file!");
      $log_user = "$username";
      $log_message = "Log in";

      //check if there is a matching result
      if (mysqli_num_rows($password_result)>0 && mysqli_num_rows($user_result)>0) {
        require_once('../assets/php/log.php'); //log user action
        
        $_SESSION['login'] = "user";
        header("Location:user.php"); //redirect to user page
      
      } elseif ($username == "bctcproject" && $password == "B@ruch123"){
        require_once('../assets/php/log.php'); //log user action
        
        $_SESSION['login'] = "admin";
        header("Location:admin.php"); //redirect to admin page

      } else {
        ?>
        <div class="alert alert-danger" id="failed">
          Incorrect username or password. Please try again.
        </div>
        <?php
      }
    }
  ?> 

<div class="row">
  <div class="container col-2 col-lg-4"></div>

  <div class="container col-8 col-lg-4 box">
    <div class="jumbotron">
      <h1>Baruch Donor App</h1>
      <br>
      <form method="post">
        <div class="form-group">
            <input type="text" class="form-control" placeholder="Username" name="username" required>
        </div>
        <div class="form-group">
            <input type="password" class="form-control" placeholder="Password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary" name="submit_login">Login</button>
      </form>
    </div>
  </div>

  <div class="container col-2 col-lg-4"></div>
</div>
</body>

</html>
