<?php include('auth.php');?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=egde">
<title>Update</title>
<!-- CSS Must be in order -->
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/google.api.css" >
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-default">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="index.php">Hello ID: <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : "Guest"; ?></a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav navbar-right">
        <li><a href="update.php">Config</a></li>
        <li><a href="addmachine.php">Add Machine</a></li>
        <li><a href="registration.php">Register</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

	<?php
		require('db.php');
		// if form submitted, insert values into the database.
		
		if(isset($_REQUEST['username'])){
			$firstname = stripslashes($_REQUEST['firstname']);
			$firstname = mysqli_real_escape_string($con, $firstname);
			$lastname = stripslashes($_REQUEST['lastname']);
			$lastname = mysqli_real_escape_string($con, $lastname);
			$username = stripslashes($_REQUEST['username']);
			$username = mysqli_real_escape_string($con, $username);
			$email = stripslashes($_REQUEST['email']);
			$email = mysqli_real_escape_string($con, $email);
			$password = stripslashes($_REQUEST['password']);
			$password = mysqli_real_escape_string($con, $password);
			$confirmpassword = stripslashes($_REQUEST['confirmpassword']);
			$confirmpasswordpassword = mysqli_real_escape_string($con, $confirmpassword);
			$trn_date = date("Y-m-d H:i:s");
			
			if($password == $confirmpassword){
				$query = "INSERT INTO users (firstname, lastname, username, email, password, trn_date)
							VALUES ('$firstname','$lastname','$username','$email','".md5($password)."','$trn_date')";
				$result = mysqli_query($con, $query);
				if($result){
					echo "<div class='container'>
					 <h3>You are registered successfully</h3>
					 <br>Click here to <a href='index.php'>Home</a>
					</div>";
				}
			} else {
					echo "<div class='container'>
					 <h3>Password and Comfirm Password don't match.</h3>
					 <br>Click here to <a href='registration.php'>registration</a>
					</div>";
				}
		} else {
	?>
    <!-- Main content -->
		<div class="container">
		  <div class="panel panel-default panel-margin">
	    <div class="panel-heading"><a href="index.php">Home</a>&gt;Registration</div>
	    <div class="panel-body">
		   
		   <form name="registration" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
	         <div class="input-group">
	           <span class="input-group-addon">Your full name</span>
	           <input class="form-control" type="text" name="firstname" placeholder="First name" require>
	           <input class="form-control" type="text" name="lastname" placeholder="Last name" require style="border-top:none;">
	           <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
	         </div><br>
	         <div class="input-group">
	         	 <span class="input-group-addon">@</span>
	             <input class="form-control" type="text" name="username" placeholder="Username" require>
	         </div><br>
	         <div class="input-group">
	         	 <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
	             <input class="form-control" type="email" name="email" placeholder="E-mail" require>
	         </div><br>
	         <div class="input-group" id ="show_hide_password">
	         	 <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
	             <input class="form-control" type="password" name="password" placeholder="Password" require>
	             <input class="form-control" type="password" name="confirmpassword" placeholder="Confirm" require style="border-top:none;">
	             <a href="#" class="input-group-addon"><i class="glyphicon glyphicon-eye-close"></i></a>
             </div><br>
	         <input type="submit" class="btn btn-default" name="submit" value="Register">
		   </form>
		</div>
		  </div>
		</div>
		<?php
			
		}?>


<!-- Footer -->
<footer class="footer container-fluid bg-4 text-center">
  <p><center>NHK Spring (Thailand) Co.,LTD.</center></a></p> 
</footer>

<!-- Javascript 1 -->
<script src="js/jquery.min.js"></script>
<script src="js/moment.js"></script>
<script src="js/bootstrap.min.js"></script>
<!-- Javascript 2 -->
<script>
$(document).ready(function() {
    $("#show_hide_password a").on('click', function(event) {
        event.preventDefault();
        if($('#show_hide_password input').attr("type") == "text"){
            $('#show_hide_password input').attr('type', 'password');
            $('#show_hide_password a i').addClass( "glyphicon-eye-close" );
            $('#show_hide_password a i').removeClass( "glyphicon-eye-open" );
        }else if($('#show_hide_password input').attr("type") == "password"){
            $('#show_hide_password input').attr('type', 'text');
            $('#show_hide_password a i').removeClass( "glyphicon-eye-close" );
            $('#show_hide_password a i').addClass( "glyphicon-eye-open" );
        }
    });
});
</script>

</body>
</html>

