<?php
echo'
	<!DOCTYPE html> 
	<html>
	<head>
	<meta charset="utf-8">
	<title>gestion RGPD Comunidades de Propietarios. Bienvenidoss</title>
	<meta http-equiv="content-type" content="text/html; utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<link href="'.$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].'/css/escritorio.css"  rel="stylesheet" type="text/css" media="screen and (min-width: 481px)" />
	<link href="'.$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].'/css/movil.css" rel="stylesheet" type="text/css" media="handheld, only screen and (max-device-width: 480px)"/>
	<link rel="shortcut icon" href="http://favicon.ico">
	<link href=\'https://fonts.googleapis.com/css?family=Roboto\' rel=\'stylesheet\'>
	<!--<script type="text/javascript" src="'.$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].'/scripts/gmap.js"></script>-->
	<script type="text/javascript" src="'.$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].'/funciones/base.js"></script>
	<script type="text/javascript" src="'.$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].'/funciones/ajax.js"></script>
	<link rel="shortcut icon" href="http://favicon.png">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="'.$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].'/funciones/base.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	'.$reenvio.'
	</head>
	<body>
		<div class="full_content">
		
				<div class="center_index" >
					<a href="'.$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].'/index.php">
						<img class="logo_index" src="'.$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].'/imagenes/logo_adeccuo_index.png"/>
					</a>
				</div>';
				
	echo'
	<div class="index">';
	//include("templates/cabecera.tpl");
	//echo'<h1>Sistema de gestión RGPD</h1>';
	
	echo'<div class="logo_index" >
			<!--<img style="width: 300px;" src="imagenes/logo_adeccuo_index.png"/>-->';
	ECHO '	<h1>FALLAS Y COFRADIAS</h1>';		
	echo'
			<div class="formulario">
			
				<form action="operaciones/accesoUsuariosRegistrados.php" method="post">
					Nombre de Usuario:<br/><input maxlength="25" size="30" name="username" />
					<br><br>
					Password:<br/><input type="password" maxlength="20" size="30" name="password" />
					</p>
				
					<p style="text-align: center;">
					<input type="submit" value="Acceder"/>
					</p>
				</form>
				
			</div>
		';
			
			
	echo'		
		</div>';
	
	
	
	
	echo'
	</div>
	';
	include("templates/pie.tpl");
  ?>
