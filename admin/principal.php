<?php
// start a session
session_start();
if ($_SESSION['rol']=="admin"){
  header ("Location: admin.php");
}else {
header ("Location: cliente.php");
}
?>