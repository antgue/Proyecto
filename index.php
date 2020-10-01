<!DOCTYPE html>
<html lang="es">
<head>
  <title>Formulario de Registro</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</head>
<body>
<?php

function test_input($data){
  $data1=$data;	
  $data = str_replace(" ","",$data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
    if (strlen($data)<3){
	  $data=NULL;
  return $data;
  } else{
  return $data1;
  }
}

$UserErr=$loginErr= $claveErr=$correoErr=NULL;
$User=$login=$clave= $correo=NULL; 


$resultado="";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  include "conexion.php";
    
    if (empty($_POST["correo"])) {
      $correoErr = "Se requiere el correo electrónico";
    }else {
      
      $correo=(filter_var($_POST["correo"], FILTER_VALIDATE_EMAIL));
    
      if (is_bool($correo)){
        $correoErr="Esto no es un correo válido";
      }
    if (strlen($correo)>70) {
      $correoErr="Longitud excesiva";
    }
  }
  

  if (empty($_POST["User"])) {
    $UserErr = "Se requiere el nombre de usuarios";
  } else  {
    $User = test_input($_POST["User"]);
    if ((!preg_match("/^[a-zA-Záéíóú ,.]*$/",$User))||(is_null($User))) {
      $UserErr = "Solo se permiten letras"; 
    }
    if (strlen($User)>20){
      $UserErr = "Longitud excesiva";
    }
  }

  if (empty($_POST["login"])) {
    $loginErr = "Se requiere el login";
  } else {
    $login = test_input($_POST["login"]);
    if ((!preg_match("/^[a-zA-Záéíóú ,.]*$/",$login))||(is_null($login))) {
      $loginErr = "Solo se permiten letras"; 
    }
    if (strlen($login)>20){
      $loginErr = "Longitud excesiva";
    }
  }
/*Encriptar la clave introducida
echo password_hash("rasmuslerdorf", PASSWORD_DEFAULT)."\n";
password_verify ( string $password , string $hash ) : bool*/
  if (empty($_POST["clave"])) {
    $claveErr = "Se requiere la contraseña";
  } else {
    $clave=test_input($_POST["clave"]);
	
    if ((!preg_match("/^[a-zA-Záéíóú ,.]*$/",$clave))||(is_null($clave))) {
      $claveErr = "Solo se permiten letras"; 
    }
    if (strlen($password)>20){
      $claveErr = "Longitud excesiva";
    }
	$token=password_hash($clave, PASSWORD_DEFAULT);
  }



        if (isset($User) && isset($login) && isset($correo) && isset($clave)){
            if (is_null($UserErr) &&  is_null($loginErr) &&  is_null($correoErr) && is_null($claveErr)) {
            $sqlcorreo= "SELECT * FROM users WHERE correo='" . $correo . "'";
			
            $resultcorreo=mysqli_query($con, $sqlcorreo);
            $sqllogin= "SELECT * FROM users WHERE login='" . $login . "'";
           // $row = mysqli_fetch_assoc($result);
		   $resultlogin=mysqli_query($con, $sqllogin);
          if (mysqli_num_rows($resultcorreo) == 0) {
             if (mysqli_num_rows($resultlogin) == 0){
					$sql1 = "INSERT INTO users (User, login, correo, clave, token) 
					VALUES ('$User','$login','$correo','$clave','$token')";
				if (mysqli_query($con, $sql1)) {
					$resultado= "Creado Nuevo registro";
				EnviarCorreo($correo,$User);
				}else 
					$resultado= "Error: " . $sql1 . "<br>" . mysqli_error($con)."".$result ."";
             
			 }else
				 $resultado="Este login ya existe en la base de datos";
			
		  }else
            
            $resultado="Este correo ya existe en la base de datos";
           
            
          mysqli_close($con);        
        }
		}
		function	EnviarCorreo($correo,$user){
			$to = '$correo';
$subject = '$User';
$message = 'hello';
$headers = 'From: guerraguilar1972@gmail.com' . "\r\n" .
    'Reply-To: guerraguilar1972@gmail.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
			mail($to, $subject, $message, $headers);
		}
    $UserErr= $loginErr= $claveErr=$correoErr=NULL;
	$User=$login=$clave= $correo=NULL; 
}

include 'conexion.php';

echo "<div class='container text-white p-3'>";
echo "<div class='container text-white p-3'>";
echo "<div class='container w-75 bg-light text-primary  p-3 shadow-lg '>";
echo "<h2>Formulario de Registro</h2>";
echo "<h3><?php echo $resultado;?></h3>";
mysqli_close($con);
?>
 <span class="text-danger"><?php echo $resultado;?></span>
<div class="container-fluid">
<div class="row">
  <form id="miformulario" name="miformulario" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
  <div class="form-group">
 <p>Usuario: <span class="text-danger">* <?php echo $UserErr;?></span></p>
<input class="form-control   input-sm " type="text" name="User" value="<?php echo $User;?>">
<p>Login: <span class="text-danger">* <?php echo $loginErr;?></span></p>
<input class="form-control  input-sm  mr-3" type="text" name="login" value="<?php echo $login;?>">
<p>Contraseña: <span class="text-danger">* <?php echo $claveErr;?></span></p>
<input class="form-control  input-sm "  name="clave" value="<?php echo $clave;?>">

<p>Email: <span class="text-danger">* <?php echo $correoErr;?></span></p>
<input class="form-control   input-sm " type="text"  name="correo" value="<?php echo $correo;?>">
    
    <button type="submit" class="btn btn-primary mt-3">Añade Registro</button>
    <div class="form-group">
  </form>

  
</div>
</div>
</div>
</body>
</html>
