<?php include('config.php');
	//Variables
	if ((empty($_POST['idreserva']))||(empty($_POST['mensaje']))||(empty($_POST['puntaje']))||(empty($_POST['idusuario']))){
		header("Location: ../reservascouch.php");
	}
	else{	
	$idreserva = $_POST['idreserva'];
	$puntaje = $_POST['puntaje'];
	$mensaje = $_POST['mensaje'];
	$idusuario = $_POST['idusuario']; // Este id de usuario es del dueño del couch que emite el puntaje
	//Validar datos de reserva
	$consulta = "SELECT * FROM reserva WHERE Id_Reserva= '$idreserva' and Visible=1";
	$consulta_execute = $conexion->query($consulta);
	if($consulta_execute->num_rows){
		$query_result = $consulta_execute->fetch_array();
		$idhuesped=$query_result['Id_Usuario']; // Este id de huesped es el que recibe la calificacion del dueño del couch
		if (($query_result['Calif_Couch']==0)&&($query_result['Estado']=='finalizada')){
			//Actualizacion de Reserva
			$sql = "UPDATE `reserva` SET `Calif_Couch` = '1' WHERE `reserva`.`Id_Reserva` = '$idreserva'";
			if (!mysqli_query($conexion, $sql)) {
				echo "ERROR. " . mysqli_error($conexion);
			}
			//Actualizar puntaje de huesped
			$sql = "UPDATE `usuario` SET `Cant_Calif` =`Cant_Calif`+1, `Total_Calif` =`Total_Calif`+'$puntaje' WHERE `usuario`.`Id_Usuario` = '$idhuesped'";
			if (!mysqli_query($conexion, $sql)) {
				echo "ERROR. " . mysqli_error($conexion);
			}
			//Insercion de Puntaje en tabla puntajes
			$insertar = "INSERT INTO punt_usuario (`Id_Usuario`, `Id_Usuario_Punt`, `Mensaje`, `Puntaje`) VALUES ('$idhuesped', '$idusuario', '$mensaje', '$puntaje')";
			if (mysqli_query($conexion, $insertar)) {
				?>	<script> alert("Puntaje procesado correctamente, Muchas Gracias.");
					location.href='../reservascouch.php';
					</script>
				<?php
			} else {
				echo "ERROR. " . mysqli_error($conexion);
			}
		}else{
			echo 
				'<script> alert("No existe reserva valida para votacion.");
				location.href="../reservascouch.php";
				</script>';			
		
		}
	}else{
			echo 
				'<script> alert("No existe reserva.");
				location.href="../reservascouch.php";
				</script>';			
		
	}
	}
?>