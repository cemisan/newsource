<?php
	$con = mysqli_connect("localhost","root","gqkNQW7Aot7^Cg1r","iot_register");
	
	// check connection_aborted
	if(mysqli_connect_error()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
 	}
?>
