<!doctype html>
<?php
	require_once("funciones/sesion.class.php");
	
	$sesion = new sesion();
	$idusuario = $sesion->get("id");
	
	if( $idusuario == false )
	{	
		header("Location: login.php");		
	}
	else 
	{
	include('funciones/config.php');
	if(empty($_GET['id'])){
		header("Location: index.php");
	}else{
		$idperfil=$_GET['id'];
	}
	//Consultas SQL
	//Consulta datos usuario
	$consulta = "SELECT * FROM usuario WHERE Id_Usuario='$idusuario' and Visible=1";
	$consulta_execute = $conexion->query($consulta);
	$resultado=$consulta_execute->fetch_assoc();
	$tipo= $resultado["Id_TipoDeUsuario"];
	$nombreusuario=$resultado["Nombre"].' '.$resultado["Apellido"];
	$premium=$resultado["Premium"];
	//Consulta datos usuario secundario
	$consulta = "SELECT * FROM usuario WHERE Id_Usuario='$idperfil' and Visible=1";
	$consulta_execute = $conexion->query($consulta);
	if($consulta_execute->num_rows==0){
		?>
		<script> alert('Usuario Inexistente.');
		location.href="misreservas.php";
		</script>
		<?php
	}else{
	$resultado=$consulta_execute->fetch_assoc();
	$nombre=$resultado["Nombre"];
	$apellido=$resultado["Apellido"];
	$nombreyapellido=$nombre.' '.$apellido;
	$fechanac=$resultado["FechaNac"];
	$telefono=$resultado["Telefono"];
	$premiumsecundario=$resultado["Premium"];
	$fechaalta=$resultado["FechaAlta"];
	$email=$resultado["Email"];
	$puntaje=5;
	//Calculo edad
		$fecha = time() - strtotime($fechanac);
		$edad = floor($fecha /31556926);
		
	$fechanac=strtotime($fechanac);
?>
<html>
	<head>
		<meta charset="utf-8">
		<title>CouchInn - <?php echo $nombreyapellido?></title>
		<!-- Importacion Iconos de Google -->
 	 	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<!--Importacion de materialize css-->
		<link type="text/css" rel="stylesheet" href="css/materialize.css"  media="screen,projection"/>
		<link type="text/css" rel="stylesheet" href="css/tooltip.css"  media="screen,projection"/>
		<!--Sitio optimizado para moviles-->
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	</head>
	
	<body>
		<!-- Estructuras del menu deslizables -->
		<ul class="dropdown-content" id="desplegable_couchs">
			<li><a class="light-green-text" href="miscouchs.php">Mis Couchs</a></li>
			<li class="divider"></li>
			<li><a class="light-green-text" href="misreservas.php">Mis Reservas</a></li>
		</ul>
			<ul class="dropdown-content" id="desplegable_admin">
			<li><a class="light-green-text" href="admin/administracion.php">Administración</a></li>
			<li class="divider"></li>
			<li><a class="light-green-text" href="admin/tiposdecouch.php">Tipos de Couchs</a></li>
			<li class="divider"></li>
			<li><a class="light-green-text" href="admin/listarusuarios.php">Usuarios</a></li>
		</ul>
		<ul class="dropdown-content" id="desplegable_cuenta">
			<li><a class="light-green-text" href="miperfil.php">Mi Perfil</a></li>
			<li class="divider"></li>
			<li><a class="light-green-text" href="modificarperfil.php">Modificar Perfil</a></li>
			<li class="divider"></li>
			<li><a class="light-green-text" href="eliminarcuenta.php">Eliminar Cuenta</a></li>
		</ul>
		<ul class="dropdown-content" id="desplegable_lateral_couchs">
			<li><a class="light-green-text" href="miscouchs.php">Mis Couchs</a></li>
			<li class="divider"></li>
			<li><a class="light-green-text" href="misreservas.php">Mis Reservas</a></li>
		</ul>
		<ul class="dropdown-content" id="desplegable_lateral_admin">
			<li><a class="light-green-text" href="admin/administracion.php">Administración</a></li>
			<li class="divider"></li>
			<li><a class="light-green-text" href="admin/tiposdecouch.php">Tipos de Couchs</a></li>
			<li class="divider"></li>
			<li><a class="light-green-text" href="admin/listarusuarios.php">Usuarios</a></li>
		</ul>
		<ul class="dropdown-content" id="desplegable_lateral_cuenta">
			<li><a class="light-green-text" href="miperfil.php">Mi Perfil</a></li>
			<li class="divider"></li>
			<li><a class="light-green-text" href="modificarperfil.php">Modificar Perfil</a></li>
			<li class="divider"></li>
			<li><a class="light-green-text" href="eliminarcuenta.php">Eliminar Cuenta</a></li>
		</ul>
		<!-- Encabezado fijo -->
		<div class="navbar-fixed">
			<!-- Barra de navagacion -->
			<nav>
				<div class="nav-wrapper white z-depth-3">
					<!-- Logo -->
					<a href="index.php" class="brand-logo"><img src="imagenes/Logo.png" alt="CouchInn" width="270" class="responsive-img" id="logo"/></a>
                    <a href="#" data-activates="menulateral" class="button-collapse"><i class="material-icons light-green">menu</i></a>
					<!-- Opciones -->
					<ul class="right hide-on-med-and-down">
						<li><a href="miperfil.php"  class="grey-text text-darken-2">Bienvenido, <?php echo $nombreusuario;?>!!!</a></li>
						<?php if ($premium==1) echo'
						<li><a href="#" class="light-green">Cuenta Premium</a></li>
						<li><a href="#" class="light-green"><i class="large material-icons">star</i></a></li>
						'?>
						<li><a href="index_login.php"  class="light-green-text">Inicio</a></li>
						<li><a class="dropdown-button light-green-text" href="#" data-activates="desplegable_couchs">Couchs y Reservas</a></li>
						<?php
							if($tipo==2){
								echo '<li><a class="dropdown-button light-green-text" href="#" data-activates="desplegable_admin">Panel Administrador</a></li>';
							}
						?>
						<li><a class="dropdown-button light-green-text" href="#" data-activates="desplegable_cuenta">Mi cuenta</a></li>
						<li><a href="funciones/cerrar_sesion.php" class="light-green-text">Cerrar Sesión</a></li>
					</ul>
					<!-- Opciones  de menu lateral-->
					<ul class="side-nav" id="menulateral">
						<li><a href="index_login.php"  class="light-green-text">Inicio</a></li>
						<li><a href="#"  class="dropdown-button light-green-text" data-activates="desplegable_lateral_couchs">Couchs y Reservas</a></li>
						<?php	if($tipo==2){
								echo '<li><a class="dropdown-button light-green-text" href="#" data-activates="desplegable_lateral_admin">Panel Administrador</a></li>';
								}
						?>
						<li><a href="#"  class="dropdown-button light-green-text" data-activates="desplegable_lateral_cuenta">Mi cuenta</a></li>
						<li><a href="funciones/cerrar_sesion.php" class="light-green-text">Cerrar Sesión</a></li>
					</ul>
			  </div>		
			</nav>
		</div>
		<!-- Contenido de pagina--> 
        <div class="parallax-container-mio  z-depth-3">
        	<div class="parallax fondo-registro"></div>
        	<div class="container"> 
    	    	<div class="row">
                	<br>
        	    	<div class="col s12 center grey-text text-darken-2">
                        <h1> <?php echo $nombreyapellido?> </h1>
                    </div>
				</div>
				<!-- Inicio del Formulario-->
				<div class="row">
						<div class="col s4">
							<p class="left">Nombre </p>
						</div>
						<div class="col s8">
							<p class="left"><?php echo $nombre?></p>
						</div>
				</div>
				<div class="divider"></div>
				<div class="row">
						<div class="col s4">
							<p class="left">Apellido </p>
						</div>
						<div class="col s8">
							<p class="left"><?php echo $apellido?></p>
						</div>
				</div>
				<div class="divider"></div>
				<div class="row">
						<div class="col s4">
							<p class="left">Correo </p>
						</div>
						<div class="col s8">
							<p class="left"><?php echo $email?></p>
						</div>
				</div>
				<div class="divider"></div>
				<div class="row">
						<div class="col s4">
							<p class="left">Tipo de Cuenta </p>
						</div>
						<?php 	
						if ($premiumsecundario==1){
							echo 	'<div class="col s8">
										<p class="left"> Cuenta Premium </p> 
									</div>';
						}else{
							echo	'<div class="col s8">
										<p class="left"> Cuenta Normal </p> 
									</div>';
						}					
						?>
				</div>
				<div class="divider"></div>
				<div class="row">
						<div class="col s4">
							<p class="left">Puntaje de Usuario</p>
						</div>
						<div class="col s8">	
							<p class="left"><?php echo $puntaje?> </p>
						</div>
				</div>
				<div class="divider"></div>
				<div class="row">
						<div class="col s4">
							<p class="left">Edad </p>
						</div>
						<div class="col s8">
							<p class="left"><?php echo $edad?></p>
						</div>
				</div>
				<div class="divider"></div>
				<div class="row">
						<div class="col s4">
							<p class="left">Fecha de Nacimiento </p>
						</div>
						<div class="col s8">
							<p class="left"><?php echo date('d-m-Y',$fechanac);?></p>
						</div>
				</div>
				<div class="divider"></div>
				<div class="row">
						<div class="col s4">
							<p class="left">Teléfono </p>
						</div>
						<div class="col s8">
							<p class="left"><?php echo $telefono?></p>
						</div>
				</div>
				<div class="divider"></div>
				<div class="row">
						<div class="col s4">
							<p class="left">Usuario desde </p>
						</div>
						<div class="col s8">
							<p class="left"><?php echo date("d-m-Y",strtotime($fechaalta))?></p>
						</div>
				</div>
				<div class="divider"></div>
				<br>
				<br>
				<div class="row">
	        		<div class="col s12registro l12 center">
                       	<input class="waves-effect waves-light btn light-green z-depth-2" type="button" value="Volver" onClick="location.href='misreservas.php'">
                    </div>
                </div>
				<br>
				<!--Fin del Formulario-->
    	    </div>        
        </div>
        <!-- Contenido de pagina-->
        
        <!-- Pie de pagina-->
		<footer class="page-footer light-green">
          <div class="container">
            <div class="row">
              <div class="col s2 l1 right">
                <img src="imagenes/data_fiscal.jpg" class="responsive-img" alt=""/>
                <img src="imagenes/todo_pago.jpg" class="responsive-img" alt=""/>
              </div>
            </div>
          </div>
          <div class="footer-copyright">
            <div class="container">
            © 2016 CouchInn - Todos los derechos reservados.
            </div>
          </div>
        </footer>
        <!-- Pie de pagina-->
         
 		<!-- Adjuntando los archivos JQuery -->
		<script type="text/javascript" src="js/jquery.min.js"></script>
  		<script type="text/javascript" src="js/materialize.js"></script>
  		<!-- Inicializacion de JS -->
  		<script type="text/javascript">
  			$(document).ready(function(){
				$(".parallax").parallax();
				$(".dropdown-button").dropdown();
				$(".button-collapse").sideNav();
  			});
  		</script>
	</body>

</html>
<?php 
	}
	}	
?>