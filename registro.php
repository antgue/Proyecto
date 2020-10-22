<!DOCTYPE html>
<html>
<style>
body {font-family: Arial, Helvetica, sans-serif;}
* {box-sizing: border-box}

/* Full-width input fields */
input[type=text], input[type=password] {
  width: 100%;
  padding: 15px;
  margin: 5px 0 22px 0;
  display: inline-block;
  border: none;
  background: #f1f1f1;
}
span{
	color:red;
	 font-size: 0.875em;
}
input[type=text]:focus, input[type=password]:focus {
  background-color: #ddd;
  outline: none;
}

hr {
  border: 1px solid #f1f1f1;
  margin-bottom: 25px;
}

/* Set a style for all buttons */
button {
  background-color: #4CAF50;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
  opacity: 0.9;
}

button:hover {
  opacity:1;
}

/* Extra styles for the cancel button */
.cancelbtn {
  padding: 14px 20px;
  background-color: #f44336;
}

/* Float cancel and signup buttons and add an equal width */
.cancelbtn, .signupbtn {
  float: left;
  width: 50%;
}

/* Add padding to container elements */
.container {
  padding: 16px;
}

/* Clear floats */
.clearfix::after {
  content: "";
  clear: both;
  display: table;
}
#acerca{
	width:30%;
	margin:0 0 0 40%;
	border: 3px solid orange;
}

/* Change styles for cancel button and signup button on extra small screens */
@media screen and (max-width: 300px) {
  .cancelbtn, .signupbtn {
     width: 100%;
  }
}
</style>
<body>
<?php


function test_input($data){
  $data1=$data;	
  $data = str_replace(" ","",$data);//Elimino espacios en blanco
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
    if (strlen($data)<3){
	  $data=NULL;
  return $data;
  } else{
  return $data1;
  }
}
//Inicializo variables
$loginErr=$correoErr=$claveErr=$correoconfErr=NULL;
$login= $correo= $clave= $correoconf=NULL; 
$fecha=date("Y-m-d H:i:s"); 

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
  
  if (empty($_POST["login"])) {
    $loginErr = "Se requiere el login";
  } else  {
    $login = test_input($_POST["login"]);
    if ((!preg_match("/^[0-9a-zA-Záéíóú ,.]*$/",$login))||(is_null($login))) {
      $loginErr = "Algún carácter no permitido"; 
    }
    if (strlen($login)>20){
      $loginErr = "Longitud excesiva";
    }
  }
   if (empty($_POST["clave"])) {
    $claveErr = "Se requiere la contraseña";
  } else {
    $clave=test_input($_POST["clave"]);
	
    if ((!preg_match("/^[0-9a-zA-Záéíóú ,.]*$/",$clave))||(is_null($clave))) {
      $claveErr = "Solo se permiten letras"; //revisa esto
    }
    if (strlen($clave)>20){
      $claveErr = "Longitud excesiva";
    }
	$token=password_hash($clave, PASSWORD_DEFAULT);
  }
  if (empty($_POST["correoconf"])) {
      $correoconfErr = "Se requiere la confirmacion del correo electrónico";
    }else {
      
      $correoconf=(filter_var($_POST["correoconf"], FILTER_VALIDATE_EMAIL));
    
      if (is_bool($correoconf)){
        $correoconfErr="Esto no es un correo válido";
      }
    if (strlen($correoconf)>70) {
      $correoconfErr="Longitud excesiva";
    }
	  if (strcmp($correo, $correoconf) !== 0) {
       $correoconfErr="Error en la confirmacion del correo";
}
  }

if (isset($login) && isset($correoconf) && isset($correo) && isset($clave)){
            if (is_null($loginErr) &&  is_null($correoconfErr) &&  is_null($correoErr) && is_null($claveErr)) {
				$sqlcorreo= "SELECT * FROM Users WHERE Users.correo='" . $correo . "'";	
				$resultcorreo=mysqli_query($con, $sqlcorreo);
				$sqllogin= "SELECT * FROM Users WHERE Users.login='" . $login . "'";
           // $row = mysqli_fetch_assoc($result);
		   $resultlogin=mysqli_query($con, $sqllogin);
          if (mysqli_num_rows($resultcorreo) == 0) {
             if (mysqli_num_rows($resultlogin) == 0){
					$sql1 = "INSERT INTO Users(login, correo, clave,token,bloqueado, fecha_registro) 
					VALUES ('$login','$correo','$clave','$token','1','$fecha')";
				if (mysqli_query($con, $sql1)) {
					$resultado= "Creado Nuevo registro";
                	          $path="http://www.antgue.info/"; //creamos nuestra direccion, con las carpetas que sean si hay
         //armamos nuestro link para enviar por mail en la variable $activateLink
$activateLink=$path."activa\activar_registro.php?id=".$login."&tok=".$token."&cla=".$clave."";
$nombre_origen    = "Proyecto DAWN";
$email_origen     = "admin@antgue.info";
					$subject = "Bienvio a Proyecto DAW '.$login.'";

	
                    $mensaje          = '<html>
<head>
    <title>Email con HTML</title>
</head>
<body>
    <table width="629" border="0" cellspacing="1" cellpadding="2">
        <tr>
            <td width="623" align="left"></td>
        </tr>
        <tr>
            <td bgcolor="#2EA354">
                <div
                    style="color:#FFFFFF; font-size:14; font-family: Arial, Helvetica, sans-serif; text-transform: capitalize; font-weight: bold;">
                    <strong>
                        Estos son sus datos de registro, '.$login.'</strong></div>
            </td>
        </tr>
        <tr>
            <td>
            <strong>SU CLAVE : </strong>'.$clave.'</strong><br><br><br>
            <strong>SU EMAIL : </strong>'.$correo.'</strong><br><br><br>
            <strong>SU LINK DE ACTIVACION:<br><a href="'.$activateLink.'">'.$activateLink.' </strong></a><br><br><br>
            <strong>POR FAVOR HAGA CLICK EN LINK DE ARRIBA PARA ACTIVAR SU CUENRA Y ACCEDER A LA PAGINA SIN
                RESTRICCIONES</strong><br><br><br>
            <strong>SI EL LINK NO FUNCIONA ALA PRIMERA INTENTELO UNA SEGUNDA, EL SERVIDOR A VECES TARDA EN PROCESAR LA
                PRIMERA ORDEN</strong><br><br><br>

            <strong>GRACIAS POR REGISTRARSE EN PROYECTO DAWN.</strong><br><br><br>
           
            </td>
        </tr>
    </table>
</body>

</html>';
					$headers = "MIME-Version: 1.0" . "\r\n";
					$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
             
//mail($email_destino, $asunto, $mensaje, $headers)) 
                    mail($correo, $subject, $mensaje, $headers);
    
            

				}else 
					$resultado= "Error: " . $sql1 . "<br>" . mysqli_error($con)."".$result ."";
			 }else
				 $resultado="Este login ya existe en la base de datos";
		  }else
            $resultado="Este correo ya existe en la base de datos";
          mysqli_close($con);        
        }
		}
   // $UserErr= $loginErr= $claveErr=$correoErr=NULL;
	$login= $correo= $clave= $correoconf=NULL; 
}
include 'conexion.php';
echo "<h3><?php echo $resultado;?></h3>";
mysqli_close($con);
?>


<div id="acerca">
<span class="text-danger"><?php echo $resultado;?><?php echo $resultado2;?></span>

<form id="miformulario" name="miformulario" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
  <div class="container">
    <h1>Registrate</h1>
    <p>Por favor rellena el formulario para tu registro.</p>
    <hr>
	<label for="text"><b>Login</b><span class="text-danger">* <?php echo $loginErr;?></span></label>
    <input type="text" placeholder="Entre un login" name="login" required value="<?php echo $login;?>">

    <label for="email"><b>Email</b><span class="text-danger">* <?php echo $correoErr;?></span></label>
    <input type="text" placeholder="Entre Correo Electronico" name="correo" required value="<?php echo $correo;?>">
	
    <label for="email-repeat"><b>Repite Email</b><span class="text-danger">* <?php echo $correoconfErr;?></span></label>
    <input type="text" placeholder="Repita Correo Electronico" name="correoconf" required value="<?php echo $correoconf;?>">

    <label for="psw"><b>Password</b><span class="text-danger">* <?php echo $claveErr;?></span></label>
    <input type="password" placeholder="Entra contraseña" name="clave" required value="<?php echo $clave;?>">
    
    <label>
      <input type="checkbox" checked="checked" name="remember" style="margin-bottom:15px"> Recuerdeme
    </label>
    
    <p>Para crear la cuenta agregue estos terminos <a href="#" style="color:dodgerblue">Terminos de privacidad</a>.</p>

    <div class="clearfix">
      <button type="button" class="cancelbtn">Cancelar</button>
      <button type="submit" class="signupbtn">Registrarse</button>
    </div>
  </div>
</form>
</div>
</body>
</html>


