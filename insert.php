<?php

include 'config.php';

$fname = $_POST["fname"];
$lname = $_POST["lname"];
$address = $_POST["address"];
$city = $_POST["city"];
$pin = $_POST["pin"];
$email = $_POST["email"];
$pwd = $_POST["pwd"];
$news = $_POST['news'];

if($mysqli->query("INSERT INTO users (fname, lname, address, city, pin, email, password, newsletter) VALUES('$fname', '$lname', '$address', '$city', $pin, '$email', '$pwd', '$news')")){
	echo 'Data inserted';
	echo '<br/>';
}

header ("location:login.php");

?>
