<?php
// include auth.php file on all secure pages
  include('auth.php');
?>

<?php

$servername = "localhost";
$username = "root";
$password = "gqkNQW7Aot7^Cg1r";
$dbname = "iot";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if(isset($_GET['id'])) {
    $sql = "SELECT * FROM main_table WHERE id = {$_GET['id']}";
}else {
	$sql = "SELECT * FROM main_table";
}

//echo $sql;

$result = $conn->query($sql);
$rows = array();

if ($result->num_rows > 0) {
    // output data of each row
    while($r = $result->fetch_assoc()) {
        $rows[] = $r;
    }
    print json_encode($rows);

} else {
    echo "404";
}
$conn->close();
?>
