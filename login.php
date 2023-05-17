

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.13/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.13/dist/sweetalert2.all.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
	<script src="jquery.min.js"></script>
  </head>
  <body>
    <div class="container">
	<div class="logo"> <img src="images/poli.png" alt="pkk" width="300px" height="100px"></div>
      <div class="wrapper">
        <div class="title"><span>Login Form</span></div>
        <form action="login.php" method="post">
          <div class="row">
            <i class="fas fa-user"></i>
            <input type="text" name="Username" placeholder="Username" required>
          </div>
          <div class="row">
            <i class="fas fa-lock"></i>
            <input type="password" name="Password" placeholder="Password" required>
          </div>
		  <div class="reset"><a href="reset_password.php" style="color: #fbff00; text-decoration: none;">Forgot password?</a></div>
          <div class="row button">
            <input type="submit" value="Login">
          </div>
          <div class="link">Not registered? <a href="register.php">Signup now</a></div>
        </form>
      </div>
    </div>
  </body>
</html>

<?php 

session_start();

include("connection.php");
include("functions.php");

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    //something was posted
    $username = $_POST["Username"];
    $password = $_POST["Password"];

    if(!empty($username) && !empty($password))
    {
        //read from database
        $query = "SELECT * FROM tb_users_registered WHERE Username = '$username' limit 1";
        $result = mysqli_query($conn, $query);

        if($result)
        {
            if(mysqli_num_rows($result) > 0)
            {
                $user_data = mysqli_fetch_assoc($result);
                $hashed_password = $user_data["Password"];

                // Hash the entered password using the same algorithm used for hashing when resetting the password
                $hashed_entered_password = password_hash($password, PASSWORD_DEFAULT);

                // Compare the hashed entered password with the hashed password stored in the database
                if(password_verify($password, $hashed_password))
                {
                    $_SESSION['serial_num'] = $user_data['serial_num'];
                    header("Location: home.php");
                    die;
                } else {
                    echo "<script>
                            swal({
                                title: 'Unsuccessful!',
                                text: 'Wrong username or password!',
                                icon: 'error',
                                timer: 3000,
                                button: false,
                            });
                        </script>";
                    die;
                }
            } else {
                echo "<script>
                        swal({
                            title: 'Unsuccessful!',
                            text: 'Username not found!',
                            icon: 'error',
                            timer: 3000,
                            button: false,
                        });
                    </script>";
                die;
            }
        }
    } else {
        echo "<script>
                swal({
                    title: 'Unsuccessful!',
                    text: 'Wrong username or password!',
                    icon: 'error',
                    timer: 3000,
                    button: false,
                });
            </script>";
        die;
    }
}


?>



