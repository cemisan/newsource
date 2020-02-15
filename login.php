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
      <a class="navbar-brand" href="index.php">Hello Guest</a>
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
		session_start();
		
		if(isset($_POST['username'])){
			// remove backslashes
			$username = stripslashes($_REQUEST['username']);
			// escapes special characters in string
			$username = mysqli_real_escape_string($con, $username);
			$password = stripslashes($_REQUEST['password']);
			$password = mysqli_real_escape_string($con, $password);
			
			// checking is user existing in the database or not
			$query = "SELECT * FROM users WHERE username='$username' AND password='".md5($password)."'";
			
			$result = mysqli_query($con, $query) or die(mysql_error());
			$rows = mysqli_num_rows($result);
			
			if($rows == 1){
				$_SESSION['username'] = $username;
				// Redirect user to index.php
				header("Location: index.php");
			} else {
					echo "<div class='container'>
					<h3>Username/Password is incorrect.</h3>
					<br>Click here to <a href='login.php'>Login</a>
					</div>
					";
			}
		} else {
	?>	
    <!-- Main content -->
		<div class="container">
		  <div class="panel panel-default panel-margin">
	    <div class="panel-heading">Authentication</div>
	    <div class="panel-body">
			
			<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" name="login">
			  <div class="input-group">
			  	<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
			    <input type="text" class="form-control" name="username" placeholder="Username" required>
			  </div>
			  <br>
			  <div class="input-group" id ="show_hide_password">
			  	<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
			    <input type="password" id="password" class="form-control" name="password" placeholder="Password" required>
			    <a href="#" class="input-group-addon"><i class="glyphicon glyphicon-eye-close"></i></a>
			 </div>
			  <br>
			  <input type="submit" class="btn btn-default" name="submit" value="Login">
			</form>
		</div>
		  </div>
		</div>	
	<?php
    } ?>

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



