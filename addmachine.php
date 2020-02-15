<?php include('auth.php');?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="ie=egde">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add</title>
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
      <a class="navbar-brand" href="index.php">Hello ID: <?php echo $_SESSION['username']; ?></a>
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
		require('iot.php');
		// if form submitted, insert values into the database.
		
		if(isset($_REQUEST['MC_name'])){
			$MC_name = stripslashes($_REQUEST['MC_name']);
			$MC_name = mysqli_real_escape_string($con, $MC_name);
			$MC_no = stripslashes($_REQUEST['MC_no']);
			$MC_no = mysqli_real_escape_string($con, $MC_no);
			$trn_date = date("Y-m-d H:i:s");
			
			$query = "INSERT INTO MC_name_no (MC_name, MC_no, trn_date)
						VALUES ('$MC_name','$MC_no','$trn_date')";
			$result = mysqli_query($con, $query);
			
			$query = "INSERT INTO main_table (MC_name)
						VALUES ('$MC_name')";
			$result = mysqli_query($con, $query);
			
			$query = "CREATE TABLE IF NOT EXISTS $MC_name (
			id int(11) NOT NULL AUTO_INCREMENT,
			pcs int(11),
			st int(3),
			run_time datetime,
			loss_time datetime,
			trn_date datetime DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (id)
			);";
			$result = mysqli_query($con, $query);
			
			$query = "INSERT INTO $MC_name (st)
						VALUES ('0')";
			$result = mysqli_query($con, $query);
			
			if($result){
				echo "<div class='container'>
				 <h3>Successfully</h3>
				 <br>Click here to <a href='index.php'>Home</a>
				</div>";
			} else {
				echo "<div class='container'>
					 <h3>Error</h3>
					 <br>Click here to <a href='index.php'>Home</a>
					</div>";
				}
		} else {
	?>

	<!-- Main content -->
	<div class="container">
	  <div class="panel panel-default panel-margin">
	    <div class="panel-heading"><a href="index.php">Home</a>&gt;Add Machine</div>
	    <div class="panel-body">
	    <form name="addmachine" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
	      <div class="input-group">
	      	<span class="input-group-addon">Machine name&nbsp;<i class="glyphicon glyphicon-tags"></i></span>
            <input class="form-control" type="text" name="MC_name" placeholder="e.g. mmc007" require>
        </div>
        <br>
        <div class="input-group">
        	<span class="input-group-addon">&nbsp;&nbsp;Node number&nbsp;<i href="#" class="glyphicon glyphicon-question-sign" data-toggle="tooltip" title="Describe what's node number"></i></span>
            <input class="form-control" type="text" name="MC_no" placeholder="node#" require>
        </div>
        <br>
        <input class="btn btn-default" type="submit" name="submit" value="Update">
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
<script src="js/bootstrap.min.js"></script>
<script src="js/moment.js"></script>
<!-- Javascript 2 -->
<script>
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();   
});
</script>

</body>
</html>

