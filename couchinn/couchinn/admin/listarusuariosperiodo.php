<!doctype html>
<?php
	require_once("../funciones/sesion.class.php");
	
	$sesion = new sesion();
	$idusuario = $sesion->get("id");
	
	if( $idusuario == false )
	{	
		header("Location: ../login.php");		
	}
	else{
		//SQL
		include('../funciones/config.php');
		$consulta="SELECT * FROM usuario WHERE Id_Usuario='$idusuario' and Visible=1";
		$consulta_execute = $conexion->query($consulta);
		if ($consulta_execute->num_rows==0){
			header("location: ../funciones/cerrar_sesion.php");
		}
		$resultado=$consulta_execute->fetch_assoc();
		$tipo=$resultado['Id_TipoDeUsuario'];
		if ($tipo == 2){
		$nombreusuario=$resultado["Nombre"].' '.$resultado["Apellido"];
		$premiumusuario=$resultado["Premium"];
		//Verificacion de variables de busqueda
		if((empty($_POST['fechainicio']))||(empty($_POST['fechafin']))){
			if ((empty($_GET['fechainicio']))||(empty($_GET['fechafin']))){
				header("Location: administracion.php");
			}else{
				$fechaini=$_GET['fechainicio'];
				$fechainis=date('d-m-Y',strtotime($fechaini));
				$fechafinaux=$_GET['fechafin'];
				$fechafins=date('d-m-Y',strtotime($fechafinaux));
				$fechafin=date('Y-m-d-H-i-s', strtotime($fechafinaux) + 86399);
			}
		}else{
			$fechaini=$_POST['fechainicio'];
			$fechainis=date('d-m-Y',strtotime($fechaini));
			$fechafinaux=$_POST['fechafin'];
			$fechafins=date('d-m-Y',strtotime($fechafinaux));
			$fechafin=date('Y-m-d-H-i-s', strtotime($fechafinaux) + 86399);
		}
		//Conteo de paginado de resultado.
		$TAMANO_PAGINA=10;
		if(!isset($_GET['pagina'])) {
			$pagina=1;
			$inicio=0;
		}else{
			$pagina = $_GET["pagina"];
			$inicio = ($pagina - 1) * $TAMANO_PAGINA;
		}
		//Consultas SQL
		$consulta = "SELECT * FROM usuario WHERE (FechaAlta >= '$fechaini' and FechaAlta <= '$fechafin') ORDER BY Id_Usuario ASC";
		$consulta_execute = $conexion->query($consulta);
		$total_resultados=$consulta_execute->num_rows;
		$total_paginas=ceil($total_resultados/$TAMANO_PAGINA);
		$consulta = "SELECT u.Id_Usuario, u.Nombre, u.Apellido, u.Email, u.FechaNac, u.Telefono, u.Premium, u.Visible, u.FechaAlta, t.Nombre AS NombreTipo FROM usuario u inner JOIN tipodeusuario t ON u.Id_TipoDeUsuario = t.Id_Tipo WHERE (FechaAlta >= '$fechaini' and FechaAlta <= '$fechafin') ORDER BY FechaAlta ASC LIMIT ".$inicio.",".$TAMANO_PAGINA."";
		$consulta_execute = $conexion->query($consulta);
?>
<html>
	<head>
		<meta charset="utf-8">
		<title>CouchInn - Listar Registrados en Período</title>
		<!-- Importacion Iconos de Google -->
 	 	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<!--Importacion de materialize css-->
		<link type="text/css" rel="stylesheet" href="../css/materialize.css"  media="screen,projection"/>
		<link type="text/css" rel="stylesheet" href="../css/tooltip.css"  media="screen,projection"/>
		<!--Sitio optimizado para moviles-->
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	</head>
	
	<body>
		<a href="../altacouch.php" accesskey="c"></a>
		<a href="../miscouchs.php" accesskey="m"></a>
		<a href="../misreservas.php" accesskey="r"></a>
		<a href="../miperfil.php" accesskey="p"></a>
		<a href="../ayuda.php" accesskey="a"></a>
		<!-- Estructuras del menu deslizables -->
		<ul class="dropdown-content" id="desplegable_couchs">
			<li><a class="light-green-text" href="../miscouchs.php">Mis Couchs</a></li>
			<li class="divider"></li>
			<li><a class="light-green-text" href="../misreservas.php">Mis Reservas</a></li>
		</ul>
		<ul class="dropdown-content" id="desplegable_admin">
			<li><a class="light-green-text" href="administracion.php">Administración</a></li>
			<li class="divider"></li>
			<li><a class="light-green-text" href="tiposdecouch.php">Tipos de Couchs</a></li>
			<li class="divider"></li>
			<li><a class="light-green-text" href="listarusuarios.php">Usuarios</a></li>
		</ul>
		<ul class="dropdown-content" id="desplegable_cuenta">
			<li><a class="light-green-text" href="../miperfil.php">Mi Perfil</a></li>
			<li class="divider"></li>
			<li><a class="light-green-text" href="../modificarperfil.php">Modificar Perfil</a></li>
			<li class="divider"></li>
			<li><a class="light-green-text" href="../eliminarcuenta.php">Eliminar Cuenta</a></li>
		</ul>
		<ul class="dropdown-content" id="desplegable_lateral_couchs">
			<li><a class="light-green-text" href="../miscouchs.php">Mis Couchs</a></li>
			<li class="divider"></li>
			<li><a class="light-green-text" href="../misreservas.php">Mis Reservas</a></li>
		</ul>
		<ul class="dropdown-content" id="desplegable_lateral_admin">
			<li><a class="light-green-text" href="administracion.php">Administración</a></li>
			<li class="divider"></li>
			<li><a class="light-green-text" href="tiposdecouch.php">Tipos de Couchs</a></li>
			<li class="divider"></li>
			<li><a class="light-green-text" href="listarusuarios.php">Usuarios</a></li>
		</ul>
		<ul class="dropdown-content" id="desplegable_lateral_cuenta">
			<li><a class="light-green-text" href="../miperfil.php">Mi Perfil</a></li>
			<li class="divider"></li>
			<li><a class="light-green-text" href="../modificarperfil.php">Modificar Perfil</a></li>
			<li class="divider"></li>
			<li><a class="light-green-text" href="../eliminarcuenta.php">Eliminar Cuenta</a></li>
		</ul>
		<!-- Encabezado fijo -->
		<div class="navbar-fixed">
			<!-- Barra de navagacion -->
			<nav>
				<div class="nav-wrapper white z-depth-3">
					<!-- Logo -->
					<a href="../index.php" class="brand-logo"><img src="../imagenes/Logo.png" alt="CouchInn" width="270" class="responsive-img" id="logo"/></a>
                    <a href="#" data-activates="menulateral" class="button-collapse"><i class="material-icons light-green">menu</i></a>
					<!-- Opciones -->
					<ul class="right hide-on-med-and-down">
						<li><a href="../miperfil.php"  class="grey-text text-darken-2">Bienvenido, <?php echo $nombreusuario;?>!!!</a></li>
						<?php if ($premiumusuario==1) echo'
						<li><a href="#" class="light-green">Cuenta Premium</a></li>
						<li><a href="#" class="light-green"><i class="large material-icons">star</i></a></li>
						'?>
						<li><a href="../index_login.php"  class="light-green-text">Inicio</a></li>
						<li><a class="dropdown-button light-green-text" href="#" data-activates="desplegable_couchs">Couchs y Reservas</a></li>
						<li><a class="dropdown-button light-green-text" href="#" data-activates="desplegable_admin">Panel Administrador</a></li>
						<li><a class="dropdown-button light-green-text" href="#" data-activates="desplegable_cuenta">Mi cuenta</a></li>
						<li><a href="../funciones/cerrar_sesion.php" class="light-green-text">Cerrar Sesión</a></li>
						<li><a href="../ayuda.php#listarusuarios" class="light-green"><i class="large material-icons">help_outline</i></a></li>
					</ul>
					<!-- Opciones  de menu lateral-->
					<ul class="side-nav" id="menulateral">
						<li><a href="../index_login.php"  class="light-green-text">Inicio</a></li>
						<li><a href="#"  class="dropdown-button light-green-text" data-activates="desplegable_lateral_couchs">Couchs y Reservas</a></li>
						<li><a class="dropdown-button light-green-text" href="#" data-activates="desplegable_lateral_admin">Panel Administrador</a></li>
						<li><a href="#"  class="dropdown-button light-green-text" data-activates="desplegable_lateral_cuenta">Mi cuenta</a></li>
						<li><a href="../funciones/cerrar_sesion.php" class="light-green-text">Cerrar Sesión</a></li>
						<li><a href="../ayuda.php#listarusuarios" class="light-green"><i class="large material-icons">help_outline</i></a></li>
					</ul>
			  </div>		
			</nav>
		</div>
		
		<!-- Comienzo del modal eliminacion de usuario-->
		<div id="modal_eli" class="modal">
    		<div class="modal-content">
				<br>
      			<h4>Eliminar Usuario</h4>
				<br>
      			<p>Atención!, estas a punto de eliminar un usuario. Este procedimiento no puede deshacerse y eliminará también todos sus datos asociados.</p>
				<div class="grey-text" > Nombre: </div>
					<input disabled type="text" name="nombre" id="nombre" value="">
					<br>
				<form name="eliminar" method="post" action="funciones/baja_usuario.php">
					<input type="hidden" name="idusuario" id="idusuario">
					<?php echo '<input type="hidden" name="idadmin" value="'.$idusuario.'">
					<input type="hidden" name="fechainicio" value="'.$fechaini.'">
					<input type="hidden" name="fechafin" value="'.$fechafinaux.'">
					'?>
					<br>
					<div class="divider"></div>
					<input class="waves-effect waves-light btn-flat light-green-text" type="submit" value="Eliminar Usuario">
					<a class="right waves-effect waves-light btn-flat light-green-text modal-action modal-close">Cancelar</a>
				</form>
    		</div>
  		</div>
		<!-- Fin del modal eliminacion de usuario-->
		
		<!-- Comienzo del modal para listar usuarios registrados-->
		<div id="modal_ureg" class="modal">
    		<div class="modal-content">
				<br>
				<br>
      			<h4>Listar Usuarios Registrados en un Período</h4>
				<br>
				<br>
      			<p>Seleccione fecha de inicio y fin y presione Visualizar.</p>
				<br>
				<br>
				<br>
				<form name="calculo" method="post" onSubmit="return validarReserva()" action="listarusuariosperiodo.php">
					<div class="input-field">
						<div class="grey-text">Fecha Inicio</div>
						<input name="fechainicio" type="date" class="datepicker" id="fechainicio" title="Fecha de Inicio">
	                </div>
					<br>
					<div class="center">
						Hasta
					</div>
					<br>
					<div class="input-field">
						<div class="grey-text">Fecha Fin</div>
						<input name="fechafin" type="date" class="datepicker" id="fechafin" title="Fecha de Fin">
	                </div>
					<br>
					<br>
					<br>
					<div class="divider"></div>
					<input class="waves-effect waves-light btn-flat light-green-text" type="submit" value="Visualizar">
					<a class="right waves-effect waves-light btn-flat light-green-text modal-action modal-close">Cancelar</a>
				</form>
    		</div>
  		</div>
		<!-- Fin del modal para listar usuarios registrados-->
		
		<!-- Contenido de pagina--> 
        <div class="parallax-container-mio  z-depth-3">
        	<div class="parallax fondo-registro"></div>
        	<!--<div class="container">-->
			<br>
			<div class="center grey-text text-darken-2">
				<h1>Usuarios Registrados en Período</h1>
			</div>
			<div class="divider"></div>
			<div class="center grey-text text-darken-2">
				<?php
				echo '<h5>Período: '.$fechainis.' al '.$fechafins.'</h5>';
				?>
			</div>
			<div class="divider"></div>
			<br>
			<div class="row">
				<div class="col s6 center">
					<a class="waves-effect waves-light btn yellow darken-3 z-depth-2 modal-trigger" href="#modal_ureg">Nuevo Período</a>
				</div>
				<div class="col s6 center">
					<a class="waves-effect waves-light btn yellow darken-3 z-depth-2" href="listarusuarios.php">Listar Todos los Usuarios</a>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="center grey-text text-darken-2">
				<?php
				switch ($total_resultados){
					case 0:
						echo '<h5>No se han encontrado resultados.</h5>
								<div class="divider"></div>';
						break;
					case 1:
						echo '<h5>Se ha encontrado: '.$total_resultados.' resultado.</h5>';
						break;
					default:
						echo '<h5>Se han encontrado: '.$total_resultados.' resultados.</h5>';	
				}
				?>
				</div>
			</div>
			<div class="section">
				<!-- Tabla-->
				<div class="row">
				<?php if($consulta_execute->num_rows) { ?>
					<table class="col s12 highlight responsive-table">
						<thead>
							<tr>
								<th class="center" data-field="name">Nombre</th>
								<th class="center" data-field="name">Correo</th>
								<th class="center" data-field="name">Permiso de Usuario</th>
								<th class="center" data-field="name">Fecha de Nacimiento</th>
								<th class="center" data-field="name">Teléfono</th>
								<th class="center" data-field="name">Premium</th>
								<th class="center" data-field="name">Fecha de Alta</th>
							</tr>
						</thead>
						<?php 
						while($query_result = $consulta_execute->fetch_array()) {
							$id=$query_result['Id_Usuario'];
							$nombre = $query_result["Nombre"] . " " . $query_result["Apellido"];
							$email = $query_result['Email'];
							$permisos=$query_result['NombreTipo'];
							$fechanac = $query_result['FechaNac'];
							$telefono = $query_result['Telefono'];
							$premium= $query_result['Premium'];
							$fechaalta = $query_result['FechaAlta'];
							$visible= $query_result['Visible'];
							$fechanac=strtotime($fechanac);
							$fechaalta=strtotime($fechaalta);
				
						echo'
						<tbody>
							<tr>
								<td class="center" >'.$nombre.'</td>
								<td class="center" >'.$email.'</td>
								<td class="center" >'.ucwords($permisos).'</td>								
								<td class="center" >'; echo date('d-m-Y',$fechanac);echo '</td>
								<td class="center" >'.$telefono.'</td>';
								if($premium==1){
									echo '<td class="center" >Premium</td>';
								}else{
									echo '<td class="center" >Normal</td>';
								}
								echo '
								<td class="center" >'; echo date('d-m-Y H:i:s',$fechaalta);echo '</td>';
								if ($visible==1){
									echo '
									<td class="center">
										<form action="modificarperfilusuario.php" method="post">
											<input type="hidden" name="id" value="'.$id.'">
											<input class="waves-effect waves-light btn yellow darken-3 z-depth-2" type="submit" value="Perfil">
										</form>
									</td>
									<td class="center">
										<a class="waves-effect waves-light btn red z-depth-2 modal-trigger" data-idusuario="'.$id.'" data-nombre="'.$nombre.'" href="#modal_eli">Eliminar</a>
									</td>';
								}else{
									echo '
									<td class="center" colspan="2">
										<a class="disabled waves-effect waves-light btn red z-depth-2">Usuario Eliminado</a>
									</td>';
								}
							echo '
							</tr>';
						}
						echo '</tbody>
					</table>';
						} else{
						echo '<br>
							<div class="center grey-text text-darken-2">';
								echo '<h5>No existen usuarios registrados en ese período.</h5>
							</div>
							<br>
							<br>
							<br>';
						}
						?>
					<div class="center">
						<br>
						<br>
						<input class="waves-effect waves-light btn light-green z-depth-2" type="button" value="Volver" onClick="location.href='administracion.php'">
					</div>
				</div>
			</div>
			<div class="section">
				<ul class="pagination center">
					<?php
						if ($pagina==1){
							if ($total_paginas==1){
								echo '<li class="disabled"><a href="#!"><i class="material-icons">chevron_left</i></a></li>';
								echo '<li class="disabled"><a href="#">1</a></li>';
								echo '<li class="disabled"><a href="#!"><i class="material-icons">chevron_right</i></a></li>';
							}
						}else{
							$paginaant=$pagina-1;
							echo '<li class="waves-effect"><a href="listarusuariosperiodo.php?pagina='.$paginaant.'&fechainicio='.$fechainicio.'&fechafin='.$fechafin.'"><i class="material-icons">chevron_left</i></a></li>';
						}
						if ($total_paginas > 1){
							for ($i=1;$i<=$total_paginas;$i++){ 
								if ($pagina == $i){
									//si muestro el índice de la página actual, no coloco enlace 
									echo '<li class="active light-green"><a href="#!">'.$pagina.'</a></li>';
								}else{
									echo '<li class="waves-effect"><a href="listarusuariosperiodo.php?pagina='.$i.'&fechainicio='.$fechainicio.'&fechafin='.$fechafin.'">'.$i.'</a></li>';
								}
							}
							if ($pagina==$total_paginas){
								//echo '<li class="disabled"><a href="#!"><i class="material-icons">chevron_right</i></a></li>';
							}else{
								$paginapos=$pagina+1;
								echo '<li class="waves-effect"><a href="listarusuariosperiodo.php?pagina='.$paginapos.'&fechainicio='.$fechainicio.'&fechafin='.$fechafin.'"><i class="material-icons">chevron_right</i></a></li>';
							}
							
						}
					?>
				</ul>
			</div>
	        <!--</div>-->
    	</div>
        <!-- Fin Contenido de pagina-->
        
        <!-- Pie de pagina-->
		<footer class="page-footer light-green">
          <div class="container">
            <div class="row">
              <div class="col s2 l1 right">
                <img src="../imagenes/data_fiscal.jpg" class="responsive-img" alt=""/>
                <img src="../imagenes/todo_pago.jpg" class="responsive-img" alt=""/>
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
		<script type="text/javascript" src="../js/jquery.min.js"></script>
  		<script type="text/javascript" src="../js/materialize.js"></script>
		<script type="text/javascript" src="../js/funciones.js"></script>
  		<!-- Inicializacion de JS -->
  		<script type="text/javascript">
  			$(document).ready(function(){
				$(".parallax").parallax();
				$(".dropdown-button").dropdown();
				$(".button-collapse").sideNav();
				$('.modal-trigger').leanModal();
				$('.datepicker').pickadate({
					min:[2013,1,1],
					max:'Today',
					selectYears: true,
					selectMonths: true,
					formatSubmit: 'yyyy-mm-dd',
					hiddenName: true
				});
				$(document).on("click", ".modal-trigger", function () {
					var idusuario = $(this).data('idusuario');
					var nombre = $(this).data('nombre');
					$(".modal-content #idusuario").val( idusuario );
					$(".modal-content #nombre").val( nombre );
				});
  			});
  		</script>
	</body>

</html>
<?php 
		
		}else{
			header("Location: ../index_login.php");
		}
	}
?>