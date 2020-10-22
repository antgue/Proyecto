
<?php 

  include "conexion.php";


//cambiar el nombre las variables que se recogeran al activar el link
if (isset($_GET['id'])) {

$idval=$_GET['id'];
$token=$_GET['tok'];  
$clave=$_GET['cla']; 
}
//
if (password_verify($clave, $token)) {
    echo '¡La contraseña es válida!';

   
$query = "UPDATE Users
            SET bloqueado = '0' WHERE   login = '$idval' AND token ='$token' " ;
$resultcorreo=mysqli_query($con, $query);
mysqli_close($con);
?>
            <SCRIPT LANGUAGE="javascript">
            location.href = "/login.php";
        </SCRIPT>
<?php 
        } else {
    echo 'La contraseña no es válida.';
}
?>