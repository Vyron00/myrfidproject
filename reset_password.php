

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
  </head>
    <body>
        <div class="container">
        <div class="logo"> <img src="images/poli.png" alt="pkk" width="300px" height="100px"></div>
        <div class="wrapper">
            <div class="title"><span>Reset Password</span></div>
            <form action="reset_password.php" method="post">
            <div class="row">
                <i class="fas fa-address-card"></i>
                <input type="text" name="Student_ID" placeholder="Student ID" required>
            </div>
            <div class="row">
                <i class="fas fa-user"></i>
                <input type="text" name="Username" placeholder="Username" required>
            </div>
            <div class="row">
                <i class="fas fa-lock"></i>
                <input type="password" name="New_Password" placeholder="New Password" required>
            </div>
            <div class="row button">
                <input type="submit" value="Submit">
            </div>
            <div class="link"><a href="login.php">Login Now</a></div>
            </form>
        </div>
        </div>
    </body>
</html>

<?php
session_start();

include("connection.php");

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and new password from the form
  $username = $_POST["Username"];
  $studentid = $_POST["Student_ID"];
  $password = $_POST["New_Password"];

  // Password validation: must contain at least 1 uppercase, 1 lowercase, 1 number, and 1 special character, and should be at least 8 characters long
  if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&.,])[A-Za-z\d@$!%*?&.,]{8,}$/", $password)) {
    echo "<script>
      setTimeout(function() {
        Swal.fire({
          title: 'Error!',
          text: 'Password must contain at least 1 uppercase, 1 lowercase, 1 number, and 1 special character, and should be at least 8 characters long',
          icon: 'error',
          timer: 3000,
          showConfirmButton: false
        }).then(function() {
          window.location.href = 'reset_password.php';
        })
      }, 100);
    </script>";
    exit();
  }

  // Check if the username exists in the database
  $sql = "SELECT * FROM tb_users_registered WHERE Username = '$username' AND Student_ID = '$studentid'";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) == 1) {
      // Update the user's password
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);
      $sql = "UPDATE tb_users_registered SET password = '$hashed_password' WHERE Username = '$username' AND Student_ID = '$studentid'";
      if(mysqli_query($conn, $sql)) {
          // Redirect the user to the login page with a success message
          echo "<script>
            setTimeout(function() {
              Swal.fire({
                title: 'Success!',
                text: 'Password reset successful',
                icon: 'success',
                timer: 3000,
                showConfirmButton: false
              }).then(function() {
                window.location.href = 'login.php';
              })
            }, 100);
          </script>";
          exit();
      } else {
          // Redirect the user to the reset password page with an error message
          echo "<script>
            setTimeout(function() {
              Swal.fire({
                title: 'Error!',
                text: 'Failed to update password',
                icon: 'error',
                timer: 3000,
                showConfirmButton: false
              }).then(function() {
                window.location.href = 'reset_password.php';
              })
            }, 100);
          </script>";
          exit();
      }
  } else {
    // Redirect the user to the reset password page with an error message
    echo "<script>
        setTimeout(function() {
          Swal.fire({
            title: 'Error!',
            text: 'User not found',
            icon: 'error',
            timer: 3000,
            showConfirmButton: false
          }).then(function() {
            window.location.href = 'reset_password.php';
          })
        }, 100);
      </script>";
      exit();
  }
}

mysqli_close($conn);
?>