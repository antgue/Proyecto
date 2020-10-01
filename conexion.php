<?php
$servername = "fdb28.awardspace.net";
$database = "3595186_usuarios";
$username = "3595186_usuarios";
$password = "Trebujena72";

$con = mysqli_connect($servername,$username,"$password","$database");
// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
}
?>