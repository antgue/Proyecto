<?php
// start a session
session_start();
?>
<!DOCTYPE html>
		<html>
		<head>
			<title>sistema de login</title>
			<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
			<!-- vinculo a bootstrap -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<!-- Temas-->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="estilo.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<!-- Libreria java scritp de bootstrap -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	
		</head>
		<body>
		
		 <div id="Contenedor">
		 <div class="Icon"><span class="glyphicon glyphicon-user"></span></div>
		 <?php
		 include "conexion.php";

		$claveErr=$correoErr=$resultado=$rol=NULL;
		 if(!empty($_POST['correo']) && !empty($_POST['contra'])){
		 	$sqlcorreo= "SELECT correo FROM Users WHERE Users.correo='" . $_POST['correo'] . "'";	
			$resultcorreo=mysqli_query($con, $sqlcorreo);
			$sqllogin= "SELECT rol, login FROM Users WHERE Users.correo='" . $_POST['correo'] . "' AND Users.clave='".$_POST['contra']. "'";
		  $resultlogin=mysqli_query($con, $sqllogin);		  
		  if (mysqli_num_rows($resultcorreo) != 0) { //Existe correo
			 if (mysqli_num_rows($resultlogin) != 0){ //Existe el login
			
				while($row= mysqli_fetch_assoc($resultlogin)){
				$rol=$row["rol"];
				$login=$row["login"];
				$_SESSION['rol'] = $rol;
				$_SESSION['login'] = $login;
                mysqli_close($con);
                  ?>
				<SCRIPT LANGUAGE="javascript">
            location.href = "admin/principal.php";
            
		</SCRIPT>
		<?php 
                }
			  }else{
				
				 $claveErr="Fallo en el login";
			  }
			}else {
			   $correoErr="FALLO EN EL CORREO";
		    }
			
		  }
		 
		 ?>

		 <div class="ContentForm">
		 	<form  method="post" name="FormEntrar" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
		 		<div class="input-group input-group-lg">
				  <span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-envelope"></i><?php echo $correoErr;?></span>
				  <input type="email" class="form-control" name="correo" placeholder="Correo" id="Correo" aria-describedby="sizing-addon1" required>
				</div>
				<br>
				<div class="input-group input-group-lg">
				  <span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-lock"></i><?php echo $claveErr;?></span>
				  <input type="password" name="contra" class="form-control" placeholder="******" aria-describedby="sizing-addon1" required>
				</div>
				<br>
				<button class="btn btn-lg btn-primary btn-block btn-signin" id="IngresoLog" type="submit">Entrar</button>
				<div class="opcioncontra"><a href="">Olvidaste tu contraseña?</a></div>
		 	</form>
		 </div>
			
		 </div>

		</body>
		<!-- vinculando a libreria Jquery-->
			</html>		