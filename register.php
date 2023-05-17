

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
	<script>
	// Fetch the latest UIDresult value every 0.5 seconds
	setInterval(function() {
		$.get("UIDContainer.php", function(data) {
		$("#getUID").val(data);
		});
	}, 500); // 500 milliseconds = 5 seconds
	</script>
</head>
  <body>
    <div class="container">
	<div class="logo"> <img src="images/poli.png" alt="pkk" width="300px" height="100px"></div>
      <div class="wrapper">
        <div class="title"><span>Register Form</span></div>
        <form action="register.php" method="post">
		<div class="row">
            <i class="fas fa-id-card"></i>
            <input placeholder="Card UID" name="Card_UID" id="getUID" value="<?php include 'UIDContainer.php'; ?>" readonly>
          </div>
		  <div class="row">
            <i class="far fa-address-card"></i>
            <input type="text" name="Student_ID" placeholder="Student ID" required>
          </div>
		  <div class="row">
            <i class="fas fa-user"></i>
            <input type="text" name="Full_Name" placeholder="Full Name" required>
          </div>
          <div class="row">
            <i class="fas fa-at"></i>
            <input type="text" name="Username" placeholder="Username" required>
          </div>
          <div class="row">
            <i class="fas fa-lock"></i>
            <input type="password" name="Password" placeholder="Password" required>
          </div>
          <div class="row button">
            <input type="submit" value="Register">
          </div>
          <div class="link">Already registered? <a href="login.php">Login Now</a></div>
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
    $uid = $_POST['Card_UID'];
		$studentid = $_POST['Student_ID'];
		$fullname = $_POST['Full_Name'];
		$username = $_POST['Username'];
		$password = $_POST['Password'];

		// full name validation: must not contain number
    if(!preg_match("/^[a-zA-Z ]*$/",$fullname)) {
      echo "<script>

    	swal({
         title: 'Unsuccessful!',
         text: 'Full name must not contain number',
         icon: 'error',
         timer: 3000,
         button: false,
         });
    	</script>";
      die;
  }

  // password validation: must contain at least 1 uppercase, 1 lowercase, 1 number, and 1 special character
  if(!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&.,])[A-Za-z\d@$!%*?&.,]{8,}$/", $password)) {
      echo "<script>

    	swal({
         title: 'Unsuccessful!',
         text: 'Password must contain at least 1 uppercase, 1 lowercase, 1 number, and 1 special character, and should be at least 8 characters long!',
         icon: 'error',
         timer: 3000,
         button: false,
         });
    	</script>";
      die;
  }

  // check if card uid are already registered
  $query = "SELECT * FROM tb_users_registered WHERE Card_UID = '$uid'";
  $result = mysqli_query($conn, $query);

  if(mysqli_num_rows($result) > 0) {
      echo "<script>

    	swal({
         title: 'Unsuccessful!',
         text: 'Card UID already registered!',
         icon: 'error',
         timer: 3000,
         button: false,
         });
    	</script>";
      die;
  }

  //check if student id are already registered
  $query = "SELECT * FROM tb_users_registered WHERE Student_ID = '$studentid'";
  $result = mysqli_query($conn, $query);

  if(mysqli_num_rows($result) > 0) {
      echo "<script>

    	swal({
         title: 'Unsuccessful!',
         text: 'Student ID already registered!',
         icon: 'error',
         timer: 3000,
         button: false,
         });
    	</script>";
      die;
  }

  // if all validations pass, save to database
  if(!empty($uid) && !empty($studentid) && !empty($fullname) && !empty($username) && !empty($password))
  {
      //hash password
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);

      //save to database
      $serial_num = random_num(20);
      $query = "INSERT INTO tb_users_registered (serial_num,Card_UID,Student_ID,Full_Name,Username,Password) VALUES ('$serial_num','$uid','$studentid','$fullname','$username','$hashed_password')";

      mysqli_query($conn, $query);

      //insert card details into another table
      $card_query = "INSERT INTO tb_balance (Card_UID, Username, Balance) VALUES ('$uid','$username','$balance')";
      mysqli_query($conn, $card_query);

      echo "<script>
        setTimeout(function() {
          Swal.fire({
            title: 'Success!',
            text: 'You have successfully registered',
            icon: 'success',
            timer: 3000,
            showConfirmButton: false
          }).then(function() {
            window.location.href = 'login.php';
          })
        }, 100);
      </script>";

      die;
  } 
}
?>