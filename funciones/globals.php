<?php

//$user = "cermeval@cermeval.com";
//$pass = "29mayo";
//Sacamos la Ip del visitanteeeeee
function sacarIp(){
	if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip=$_SERVER['HTTP_CLIENT_IP'];
	} elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip=$_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}

function comprobarPdo(){
	// Se muestra la ruta del fichero de configuración
	echo 'Fichero de configuración: ', get_cfg_var('cfg_file_path'), '<hr />';
	// Recuperamos y mostramos el valor la directiva extension_dir
	$dir_ext = ini_get('extension_dir');
	if (empty($dir_ext))
	die("La directiva extension_dir no tiene valor asignado");
	else
	echo "Valor de la directiva extension dir: ", $dir_ext, "<br /><hr />\n";
	// Se comprueba si está cargada la extensión MySQL
	if (!extension_loaded('pdo_mysql'))
	echo "La extensión MySQL no está cargada";
	else
	echo "Extensión MySQL cargada correctamente";
}	

function conectar($host ='localhost',$dbname='cermevalcitasweb',$usuario = 'cermevalcitas',$contrasenia = 'Prainsa23'){
	try {	// datos 1and1
	/*
			$usuario = 'dbo569840772';  
			$contrasenia = 'CermeVal+12';
			$conn = new PDO ('mysql:host=db569840772.db.1and1.com;dbname=db569840772',$usuario, $contrasenia, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES  \'UTF8\''));
			*/
			//datos dinahosting
			
			$usuario = 'cermevalcitas';  
			$contrasenia = 'Prainsa23';
			$host ='localhost';
			$dbname='cermevalcitasweb';
			
			$conn = new PDO ('mysql:host='.$host.';dbname='.$dbname.'',$usuario, $contrasenia, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES  \'UTF8\''));
			
			return $conn;
			} catch (PDOException $e) {
				print $e->getCode();
				print $e->getMessage();
				exit();
			}
}

function insertaReg($sql){	
	$bd = conectar();
	$resultado = $bd->query($sql);
		if(!$resultado){
			//echo'Ho se ha insertado NADA DE NADA MONADA'; 
			$exito = "no";
		}else{
			//echo 'Registro insertado con EXITO!!!';
			$exito = "si";
		}
		return $exito;
		$bd = NULL;
}
function actualizaReg($sql){	
	//Conectamos
	
	$bd = conectar();
		$resultado = $bd->query($sql);
		if(!$resultado){
			$exito = "no";
		}else{
			$exito = "si";
		}
		return $exito;
		$bd = NULL;
}

function consultaMulti($consulta){
	$bd = conectar();
	$result = $bd->query($consulta);
	if (!$result){
		print "<p>Error en la consulta.</p>\n";
		}
	else{
		return $result;
		}
	$bd = NULL;
}

function consultaContar($consulta){
	$bd = conectar();
	$result = $bd->prepare($consulta); 
	$result->execute(); 
	$number_of_rows = $result->fetchColumn(); 
	return $number_of_rows;
	$bd = NULL;
}



function devuelveDatos($consulta){
	$bd = conectar();
	$result = $bd->query($consulta);
	if ($result->fetchColumn() == 0){ $contienedatos = 0;}
	else{$contienedatos = 1;}
	return $contienedatos;
}



function verCartera($fecha){
	
	$total = 0;
	$devDatos = devuelveDatos("SELECT COUNT('id') FROM `citas` WHERE DATE(fechayhora) = '$fecha' AND anula = 0 ");
	if($devDatos == 0){
		$mensaje .= '<h2>No hay citas para hoy, día '.$fecha.'</h2>';
	}else{
		$cartera = consultaContar("SELECT count(*) FROM `citas` WHERE date(fechayhora) > curdate() AND anula = 0 ");
		$cartera = $cartera - $devDatos ;
		
		$mensaje = '
					<h3>Hoy '.$fecha.' a las '.date("H:i:s").' hay '.$cartera.' citas en cartera y hoy se han creado '.verCarteraCreadaHoy($fecha).' </h3>';
	}
	return $mensaje;
}

function verCarteraCreadaHoy($fecha){
	$total = 0;
	$devDatos = consultaContar("SELECT COUNT('id') FROM `citas` WHERE DATE(fechapeticion) = '$fecha' AND anula = 0 ");
	return $devDatos;
}



function verCitas($fecha){
	$cartera = consultaContar("SELECT count(*) FROM `citas` WHERE date(fechayhora) = curdate() AND anula = 0 ");
	if($cartera != 0){
		$sql = "SELECT * FROM `citas` WHERE DATE(fechayhora) = '$fecha' AND anula = 0 ORDER BY fechayhora ASC";
		$datos = consultaMulti($sql);
		//Componemos el mensaje 
		$mensaje = '
					<h1>CITAS WEB DIA '.$fecha.' a las '.date("H:i:s").'</h1>
					<table id="pantalla">';
		$mensaje .= '<tr>
							<th>Origen</th>
							<th>Nombre</th>
							<th>Apellidos</th>
							<th>Teléfono</th>
							<th>Correo</th>
							<th>Certificado</th>
							<th>Tramite</th>
							<th>Hora</th>
							<th>Contactar por</th>
							<th>Oferta</th>
							<th> € </th>
							
					
							<th colspan="2">¿Acude?</th>
							<th>Sms</th>
							<th>Editar</th>
						<tr>';
		$color1="whitesmoke";
		$totalCertificados =0;
		while ($fila = $datos->fetch())
		{
			$hora = explode(" ", $fila['fechayhora']);
			$email = $fila['correo'];
			
			if($color1 =="whitesmoke"){	$color1 ="#fff";}else{$color1 ="whitesmoke";}
			if($fila['origencita'] == 'cermeval@cermeval.com'){$fila['origencita'] = 'cermeval';}
			
			$mensaje.= '<tr>
							<td style="background-color: '.$color1.';">'.$fila['origencita'].'</td>
							<td style="background-color: '.$color1.';">'.$fila['nombre'].'</td>
							<td style="background-color: '.$color1.';">'.$fila['apellidos'].'</td>
							<td style="background-color: '.$color1.';">'.$fila['telefono'].'</td>';
			if($email == 'notengoelcorreo@cermeval.com')
				{$lineaCorreo = '<a href="mailto: No disponible">No disponible<a>';} 
			else
			{$lineaCorreo = '<a href="mailto: '.$email.'">Enviar eMail<a>';}
			$mensaje.='
							<td style="background-color: '.$color1.';">'.$lineaCorreo.'</td>
							<td style="background-color: '.$color1.';">'.$fila['certificado'].' '.$fila['tipoCarnet'].'</td>
							<td style="background-color: '.$color1.';">'.$fila['tramite'].'</td>
							<td style="background-color: '.$color1.';">'.$hora[1].'</td>
							<td style="background-color: '.$color1.';">'.$fila['medio'].'</td>';
							
			if($fila['oferta'] == 0){$oferta = "seguro";} else{$oferta = $fila['oferta'].'% Dto.';}
			
			//Quitamos el % si es precio fijo
			if($fila['oferta'] == '55' || $fila['oferta'] == '53.50'|| $fila['oferta'] == 'Manual' ){$oferta = 'Oferta '.$fila['oferta'].'';}else{$oferta = $fila['oferta'].'% Dto.';}
			
			$totalLinea = $fila['importe'] + $tasa;
			if($fila ['acude'] == 1){$desactivado = 'disabled = disabled';}  
			if($fila ['acude'] == 2){$desactivado = 'disabled = disabled';}  
			//Pintamos la tabla
			$mensaje.='				
							<td style="background-color: '.$color1.'; text-align: right;">'.$oferta.'</td>
							<td style="background-color: '.$color1.'; text-align: right;">'.$fila['importe'].'€</td>
							<td style="text-align: center;">
								<a href="../funciones/citaConfirmada.php?img=1&id='.$fila['id'].'&nombre='.$fila['nombre'].'&mail='.$email.'&certificado='.$fila['certificado'].'&id='.$fila['id'].'&confirm=si">
								<input type="button" '.$desactivado .'  id="si'.$fila['id'].'" value="SI" onclick="desactivarBoton(this);"/></a>
							</td>
							<td style="text-align: center;">		
								<a href="../funciones/citaConfirmada.php?img=1&id='.$fila['id'].'&nombre='.$fila['nombre'].'&mail='.$email.'&certificado='.$fila['certificado'].'&id='.$fila['id'].'&confirm=no&hora='.$hora[1].'">
								<input type="button" '.$desactivado .' id="no'.$fila['id'].'" value="NO" onclick = "desactivarBoton(this)" /></a>
							</td>
							
							<td style="text-align: center;">
								<a href="funciones/enviarsms.php?nombre='.$fila['nombre'].'&movil='.$fila['telefono'].'&certificado='.$fila['certificado'].'&hora='.$hora[1].'">
								<input type="image" '.$desactivado .' src="../imagenes/Sms.ico" style="width: 25px; padding-left: 3px; padding-right: 3px;" onclick = "desactivarBoton(this)">
								</a>
								
							</td>
							
							<td style="text-align: center;">		
								<a href="../gestionarCita.php?img=1&id=CT-0000'.$fila['id'].'9999">
								<img src="imagenes/editar_logo.png" style="width: 25px; padding-left: 3px; padding-right: 3px;"/></a>
								
							</td>
						<tr>';
			$totalCertificados = $totalCertificados + $fila['importe'];
			$total = $total+$totalLinea;
			$desactivado = '';
		}
		//echo $totalCertificados;
		$mensaje .= '
					<tr><td colspan="15" style="border: 0px solid #fff;"></td></tr>
					<tr>
							
							<td class="resultado" style="border: 0px solid #fff;" colspan="9"><b>TOTAL</b></td>
							<td colspan="4" id="totalresultado" style="border: 0px solid #fff;">'.number_format($total,2, ",", ".").' €</td>
					<tr>';

		$mensaje .= '</table>';
	}else{
		$mensaje='';
	}

return $mensaje;
}
function verColectivos(){
	$fecha = date('Y-m-d', strtotime(' -1 day '));
	$sql = "SELECT * FROM `colectivos` WHERE DATE(fecharegistro) = '$fecha' ORDER BY fecharegistro ASC";
	
	$devDatos = devuelveDatos($sql);

	if($devDatos == 0){
	$mensaje .= '<h2>No se ha registrado ningun colectivo el dia '.$fecha.'</h2>';
	}
	else{
	$datos = consultaMulti($sql);
	//Componemos el mensaje 
	$mensaje .= '
				<h1>COLECTIVOS REGISTRADOS EL DIA '.$fecha.'</h1>
				<table id="pantalla">';
	$mensaje .= '<tr>
						<th>Id</th>
						<th>Nombre Colectivo</th>
						<th>Descripcion</th>
						<th>persona de Contacto</th>
						<th>telefono Colectivo</th>
						<th>Email</th>
						<th>Nombre Prescriptor</th>
						<th>telefono Presciptor</th>
					<tr>';
	$color1="whitesmoke";
	while ($fila = $datos->fetch())
	 {
		if($color1 =="whitesmoke"){	$color1 ="#fff";}else{$color1 ="whitesmoke";}
		$email = $fila['email'];
		$mensaje.= '<tr>
						<td style="background-color: '.$color1.';">'.$fila['id'].'</td>
						<td style="background-color: '.$color1.';">'.$fila['nombreColectivo'].'</td>
						<td style="background-color: '.$color1.';">'.$fila['descripcion'].'</td>
						<td style="background-color: '.$color1.';">'.$fila['personaContacto'].'</td>
						<td style="background-color: '.$color1.';">'.$fila['telefonoColectivo'].'</td>
						<td style="background-color: '.$color1.';"><a href=\'mailto:'.$email.'\'>'.$email.'<a></td>
						<td style="background-color: '.$color1.';">'.$fila['nombre'].'</td>
						<td style="background-color: '.$color1.';">'.$hora['telefono'].'</td>
						
					<tr>';
		
	}
	$mensaje .= '</table>';
	}
	return $mensaje;
}
function verAvisame(){
	$fechaInicial = date('Y-m-d', strtotime(' +3 month '));
	$fechaFinal = date('Y-m-d', strtotime(' +1 day '));
	
	//echo 'Fecha inicial: '.$fechaFinal.' Fecha final: '.$fechaInicial;
	$sql = "SELECT * FROM `servicio-avisame` WHERE fechaRenovacion BETWEEN '$fechaFinal' AND '$fechaInicial' ORDER BY fechaRenovacion ASC";
	
	$devDatos = devuelveDatos($sql);

	if($devDatos == 0){
	$mensaje .= '<h2>No hay avisos de Renovación en los proximos 3 meses.</h2>';
	}
	else{
	
	$datos = consultaMulti($sql);
	
	//Componemos el mensaje 
	$mensaje .= '
				<h1>RENOVACIONES PROXIMOS 3 MESES '.$fecha.' AVISAME</h1>
				<table id="pantalla">';
	$mensaje .= '<tr>
						<th>Id</th>
						<th>Nombre</th>
						<th>Apellidos</th>
						<th>Telefono</th>
						<th>Movil</th>
						<th>Email</th>
						<th>Certificado</th>
						<th>Contactar por</th>
						<th>Fecha Renovacion</th>
					<tr>';
	$color1="whitesmoke";
	while ($fila = $datos->fetch())
	 {
		$email = $fila['correo'];
		if($color1 =="whitesmoke"){	$color1 ="#fff";}else{$color1 ="whitesmoke";}
		$mensaje.= '<tr>
						<td style="background-color: '.$color1.';">'.$fila['id'].'</td>
						<td style="background-color: '.$color1.';">'.$fila['nombre'].'</td>
						<td style="background-color: '.$color1.';">'.$fila['apellidos'].'</td>
						<td style="background-color: '.$color1.';">'.$fila['telefono'].'</td>
						<td style="background-color: '.$color1.';">'.$fila['movil'].'</td>
						<td style="background-color: '.$color1.';"><a href=\'mailto:'.$email.'\'>'.$email.'<a></td>
						<td style="background-color: '.$color1.';">'.$fila['certificado'].'</td>
						<td style="background-color: '.$color1.';">'.$fila['medio'].'</td>
						<td style="background-color: '.$color1.';">'.$fila['fechaRenovacion'].'</td>
						
					<tr>';
	}
	$mensaje .= '</table>';
	}
	return $mensaje;
}
//************************   CONTACTOS   **************************************************
function verContactos($fecha){

	$fecha = date('Y-m-d', strtotime(' -1 day '));
	//echo 'Fecha inicial: '.$fechaFinal.' Fecha final: '.$fechaInicial;
	$sql = "SELECT * FROM `contactos_web` WHERE  DATE(fechaPeticion) = '$fecha'  ORDER BY fechaPeticion ASC";

	$devDatos = devuelveDatos($sql);

	if($devDatos == 0){
	$mensaje .= '<h2>No hay contactos via web.</h2>';
	}
	else{
	
	$datos = consultaMulti($sql);
	//Componemos el mensaje 
	$mensaje .= '
				<h1>CONTACTOS WEB DEL DIA '.$fecha.'</h1>
				<table id="pantalla">';
	$mensaje .= '<tr>
						<th>Id</th>
						<th>Nombre</th>
						<th>Apellidos</th>
						<th>Telefono</th>
						<th>Movil</th>
						<th>Email</th>
						<th>Motivo Consulta</th>
						<th>Contactar por</th>
					<tr>';
	$color1="whitesmoke";
	while ($fila = $datos->fetch())
	 {
	 
		$email = $fila['correo'];
		if($color1 =="whitesmoke"){	$color1 ="#fff";}else{$color1 ="whitesmoke";}
		$mensaje.= '<tr>
						<td style="background-color: '.$color1.';">'.$fila['id'].'</td>
						<td style="background-color: '.$color1.';">'.$fila['nombre'].'</td>
						<td style="background-color: '.$color1.';">'.$fila['apellidos'].'</td>
						<td style="background-color: '.$color1.';">'.$fila['telefono'].'</td>
						<td style="background-color: '.$color1.';">'.$fila['movil'].'</td>
						<td style="background-color: '.$color1.';"><a href=\'mailto:'.$email.'\'>'.$email.'<a></td>
						<td style="background-color: '.$color1.';">'.$fila['motivoConsulta'].'</td>
						<td style="background-color: '.$color1.';">'.$fila['medio'].'</td>
						
						
					<tr>';
		
	}
	$mensaje .= '</table>';
	}
	return $mensaje;
}


function errorCaptcha($nombre){

include ('templates/header.tpl');
			include ('templates/cabecera.tpl');
			include ('templates/menu.tpl');
			echo '
				<div id="contenido">
					<div class="tercio_vertical">
				';	
			echo'
					<img src="imagenes/cermeval_horario_normal.jpg">
				';
				include ('templates/rrss.tpl');
				echo'
					</div>
				';
			echo'
				<div id="contenido">
				<div class="Dos_tercios_vertical">
					<img style="width: 30%; float: left; margin-right: 20px;" src="imagenes/ko_icono.jpg"/>
					<h2>El codigo de seguridad introducido no es correcto.</h2>
					<p>
					'.$nombre.', vuelve <a href=\'javascript:history.go(-1)\'>atras</a> e intentalo de nuevo.
					<br>
					<br>
					Gracias por confiar en <a href="../index.php">CERMEVAL</a>.
					</p>
				</div>
				<div style="clear: both;"></div>
			</div>';
			include ('templates/pie.tpl');
			include ('templates/final.tpl');
}
function mostrarPaginarespuesta($textoHtml){
		include ('templates/header.tpl');
		include ('templates/cabecera.tpl');
		include ('templates/menu.tpl');
		echo '
			<div id="contenido">
				<div class="tercio_vertical">
			';	
		echo'
				<img src="imagenes/cermeval_horario_normal.jpg">
			';
			include ('templates/rrss.tpl');
			echo'
				</div>
			';
			echo $textoHtml;
			
		include ('templates/pie.tpl');
		include ('templates/final.tpl');
	} 

function montaCodigo($id){
	
	$aleatorio1 = rand(1111,9999);
	$aleatorio2 = rand(1111,9999);
	$codigo = 'CT-'.$aleatorio1.$id.$aleatorio2;
	return $codigo;

}

function desmontaCodigo($codigo){
	$codigo = substr($codigo, 0, -4);
	$codigo = substr($codigo, 7);
	return $codigo;
	
}

function calculaPrecio($oferta,$certificadoN,$tipoCarnet){
	$tasa10 = 23.50;
	$tasa4 = 18.80;
	$tasa3 = 14.10;
	$tasa2 = 9.40;
	$tasa1 = 4.70;
	$descuento = (int)$oferta;
	if($tipoCarnet == 'Seleccione una opción') {$tipoCarnet = 'AB';}
	switch($oferta)
	{
		// Si es un precio especial requiere un case. Si es descuento es en el default
			case '55':
					if($tipoCarnet == 'AB'){
						$precioNeto = $oferta - $tasa10;
					}else{
						$precioNeto = $oferta+5 - $tasa10;
					}
			break;
			
			case '53.50':
					if($tipoCarnet == 'AB'){
						$precioNeto = $oferta - $tasa10;
					}else{
						$precioNeto = $oferta+5 - $tasa10;
					}
			break;
			case 'FND':
					//echo "FND!!";
					$precioNeto = $certificadoN * 0.50;
			break;
			case 'seguro':
					//echo "entra en SEGURO!!";
					$precioNeto =  $certificadoN;
			break;
			default:
					//echo "resto!!";
					$precioNeto =  $certificadoN*((100-$descuento)/100);
			break;
	}
	return $precioNeto;
}

function tratarNombre($nombre){
	$nombre = ucwords(strtolower($nombre));
	return $nombre;
}

function tratarApellidos($apellidos){
	$apellidos = ucwords(strtolower($apellidos));
	return $apellidos;
}



function verificarUsuarios ($usuario,$contrasenya){
	$verificado = 0;
	$user = array('cermeval@cermeval.com','Sol');
	$pass = array('29mayo','Sol34');
	
	//Aqui metemos lo que sea para recorrer los arrays y demas...
	for ($i = 0; $i <= count($user)-1; $i++) {
	
	if($user[$i] == $usuario && $pass[$i] == $contrasenya)
		{
		$verificado = 1;
		}
	}
	//echo $verificado;
	return $verificado;
	
}

function numeroSorteo($numero){
	//3 cifras para un mes. 4 para más duraderos
	//$numero = substr($numero, -3);
	$numero = substr($numero, -4);
	return $numero;
}


function enviarSMSUnico($movil,$mensaje){
	// Con estos datos funciona con NRSGATEWAY
	$username = 'cermeval';
	$password = '29mayo';
	$origen = 'Cermeval';
		
	$longitudMensaje = strlen($mensaje);
		
	$file="https://gateway.plusmms.net/send.php?username=$username&password=$password&to=$movil&text=$mensaje&from=$origen&coding=0&parts=2";
	
	$page = file_get_contents("$file"); 
	
	return $page;
}

function getGCalendarUrl($event){
	$titulo = urlencode($event['titulo']);
	$descripcion = urlencode($event['descripcion']);
	$localizacion = urlencode($event['localizacion']);
	$start=new DateTime($event['fecha_inicio'].' '.$event['hora_inicio'].' '.date_default_timezone_get());
	$end=new DateTime($event['fecha_fin'].' '.$event['hora_fin'].' '.date_default_timezone_get());
	$dates = urlencode($start->format("Ymd\THis")) . "/" . urlencode($end->format("Ymd\THis"));
	$name = urlencode($event['nombre']); $url = urlencode($event['url']);
	$gCalUrl = "http://www.google.com/calendar/event?action=TEMPLATE&amp;text=$titulo&amp;dates=$dates&amp;details=$descripcion&amp;location=$localizacion&amp;trp=false&amp;sprop=$url&amp;sprop=name:$name";
	return ($gCalUrl);
	}

	function mesaDigito($mes){
		switch($mes)
		{
			case 'Enero':
				$digito = 1;
			break;
			case 'Febrero':
				$digito = 2;
			break;
			case 'Marzo':
				$digito = 3;
			break;
			case 'Abril':
				$digito = 4;
			break;
			case 'Mayo':
				$digito = 5;
			break;
			case 'Junio':
				$digito = 6;
			break;
			case 'Julio':
				$digito = 7;
			break;
			case 'Agosto':
				$digito = 8;
			break;
			case 'Septiembre':
				$digito = 9;
			break;
			case 'Octubre':
				$digito = 10;
			break;
			case 'Noviembre':
				$digito = 11;
			break;
			case 'Diciembre':
				$digito = 12;
			break;
		}
		return $digito;
	}
?>