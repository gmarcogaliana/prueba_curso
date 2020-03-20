<?php
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

function conectar(){
	
	$host ='db5000265803.hosting-data.io';
	$dbname='dbs259392';
	$usuario = 'dbu336728'; 
	$contrasenia = 'Prainsa23+075207';
	
	try {	// datos 1and1
	$conn = new PDO ('mysql:host='.$host.';dbname='.$dbname.'',$usuario, $contrasenia, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES  \'UTF8\''));
	return $conn;
		} catch (PDOException $e) {
			print $e->getCode();
			print $e->getMessage();
			exit();
		}
		echo 'conectado';
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

function borraRegistro($sql){	
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

function userLogin($username,$password)
{
	try{
		$db = conectar();
		// fase II kash password
		//$hash_password= hash('sha256', $password); //Password encryption 
		$stmt = $db->prepare("SELECT * FROM usuarios WHERE username=:username AND password=:password"); 
		$stmt->bindParam("username", $username,PDO::PARAM_STR) ;
		$stmt->bindParam("password", $password,PDO::PARAM_STR) ;
		$stmt->execute();
		$count=$stmt->rowCount();
		$data=$stmt->fetch(PDO::FETCH_OBJ);
		$db = null;
		if($count)
		{	
			$_SESSION['s_id']=$data->id; // Storing user id value
			$_SESSION['s_username']=$data->username; // Storing user name value
			$_SESSION['s_password']=$data->password; // Storing user pasword value
			$_SESSION['s_role']=$data->SuperUser; // Storing user role value
			//$_SESSION['s_SuperUser']=$data->SuperUser; // Storing ROLE. VALUE 9 ARE GOOD
			
			//$_SESSION['s_idAdmin']=$data->idAdministrador; // Storing  value 0 if the user is a system admin or 1 if the user is Administrador F. Default 0
			$_SESSION['s_AdminId']=$data->adminId; // Storing user admin Id value. Only if user isn´t system admin
			$_SESSION['s_AdminRazonSocial'] = ""; // Storing user admin adress. only if user isn´t system admin
			$_SESSION['s_CdadRazonSocial'] = ""; // Storing user cdad adress. only if user isn´t system admin
			$_SESSION['s_CdadId'] = ""; // Storing user cdad adress. only if user isn´t system admin
			
			
		return true;}
		else{return false;} 
	}
	catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
}


function consultaMulti($consulta){
	$bd = conectar();
	$result = $bd->query($consulta);
	if (!$result){
		print "<p>Error en la consulta $consulta en addecuo.</p>\n";
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


function consultaUnica($consulta){
	$bd = conectar();
	$result = $bd->query($consulta); 
	$datos = $result->fetchColumn();
	return $datos;
	$bd = NULL;
}



function devuelveDatos($consulta){
	$bd = conectar();
	$result = $bd->query($consulta);
	if ($result->fetchColumn() == 0){ $contienedatos = 0;}
	else{$contienedatos = 1;}
	return $contienedatos;
}

function nombreEmpresa($id){
	$sql ='SELECT `razonSocial` FROM `empresas` WHERE `id` = '.$id.'';
	$nombre = consultaMulti($sql);
	foreach($nombre as $nombre){$nombreEmpresa = $nombre['razonSocial'];}
	return $nombreEmpresa;
}

function VisibleAdmin($role){
		switch ($role)
		{
			// Tooodo tablas..
			case 1:
			$visibleAdmin = 'visible-celda';
			break;
			case 9:
			$visibleAdmin = 'visible-celda';
			break;
			default:
			$visibleAdmin = "no-visible";
			break;
		}
		//$style = 'style="display: '.$visibleAdmin.';"';
		//echo $style;
		return $visibleAdmin;
}


//pass y user
function generarUser($razon_social){
	$nombre = explode (" ",$razon_social);
	$numTramos = count($nombre);
	if($numTramos > 2){$numTramos = 2;}
	$user = ""; 
	for($i=0;$i<$numTramos;$i++){
		$user.= substr($nombre[$i],0,3); 	
	}
	return $user;
}
function generarPass($user){	
	$pass =  rand ( 0000 , 9999 );
	$letras = array("Q","W","E","R","C","H","J","M","A","P","K","L","X","Y","S","D","F");
	$numLetra = rand ( 0 , 17 );
	$pass = $user.$pass.$letras[$numLetra];
	return $pass;
	}
	
function statusPresidente($id){
	
	$sql = 'SELECT `razonSocial`, `fechaAltaPresidente` FROM `comunidades` WHERE `idComunidad` ='.$id ;
	$datosCdad = consultaMulti($sql);
	$i=1;
	foreach($datosCdad as $datosCdad){}
	$fechaPresidente = $datosCdad['fechaAltaPresidente'];
	//Todo timestamp
	$timestampFechaPresidente = strtotime($fechaPresidente); 
	$timestampHoy = strtotime( date("Y-m-d")); 
	// Añadimos 1 año mñas a la fecha del presidente
	$timestampFechaPresidente = $timestampFechaPresidente + (365*24*60*60);
	$diferenciaFechas = $timestampFechaPresidente  -   $timestampHoy;
	$diasDiferencia = $diferenciaFechas/(24*60*60);
	
	//echo $diasDiferencia;

	switch ($diasDiferencia){
		case $diasDiferencia < 0:
		$diasVencida = round($diasDiferencia*-1, 0);
		$estado = "<img src='../../imagenes/ko.png'/ style ='width: 25px;' alt='Presidencia vencida $diasVencida días' title='Presidencia vencida $diasVencida días' />";
		break;
		case 1 < $diasDiferencia  && $diasDiferencia < 30:
		$estado =  "<img src='../../imagenes/warning.png'/ style ='width: 25px;' alt='Faltan menos de 1 mes' title='Faltan menos de 1 mes' />";
		break;
		
		case 31 < $diasDiferencia && $diasDiferencia < 60:
		$estado =  "<img src='../../imagenes/warning.png'/ style ='width: 25px;' alt='Faltan menos de 2 meses' title='Faltan menos de 2 meses'/>";
		break;
		
		
		default:
		$estado = "<img src='../../imagenes/ok.png'/ style ='width: 25px;' alt='Correcto. La presidencia esta vigente' title='Correcto. La presidencia esta vigente'/>";
		break;
		
	}
	
	return $estado;
}
	
function statusSeguro($id){
	
	$sql = 'SELECT `razonSocial`, `fechaVtoSeguro`, `seguro` FROM `comunidades` WHERE `idComunidad` ='.$id ;
	$datosSeguroCdad = consultaMulti($sql);
	$i=1;
	foreach($datosSeguroCdad as $datosSeguroCdad){}
	$fechaVtoSeguro = $datosSeguroCdad['fechaVtoSeguro'];
	
	//Todo timestamp
	$timestampfechaVtoSeguro = strtotime($fechaVtoSeguro); 
	$timestampHoy = strtotime( date("Y-m-d")); 
	// Añadimos 1 año mñas a la fecha del presidente
	//$timestampFechaPresidente = $timestampFechaPresidente + (365*24*60*60);
	$diferenciaFechas = $timestampfechaVtoSeguro  -   $timestampHoy;
	$diasDiferencia = $diferenciaFechas/(24*60*60);

	switch ($diasDiferencia){
		case $diasDiferencia < 0: 
		$diasVencida = round($diasDiferencia*-1, 0);
		$diasVencida = fmod($diasVencida,365);
		
		$estado = "<img src='../../imagenes/ok.png'/ style ='width: 25px;' alt='Seguro de ".$datosSeguroCdad['seguro']." vencido $diasVencida días. Renovado con la compañoa anterior' title='Seguro vencido $diasVencida días. Renovado con la compañoa anterior' />";
		break;
		case 1 < $diasDiferencia  && $diasDiferencia < 30:
		$estado =  "<img src='../../imagenes/warning.png'/ style ='width: 25px;' alt='Vencimiento Seguro de ".$datosSeguroCdad['seguro']." en menos de 1 mes. Fecha ".$fechaVtoSeguro."' title='Vencimiento Seguro de ".$datosSeguroCdad['seguro']." en menos de 1 mes. Fecha ".$fechaVtoSeguro."' />";
		break;
		
		case 31 < $diasDiferencia && $diasDiferencia < 60:
		$estado =  "<img src='../../imagenes/warning.png'/ style ='width: 25px;' alt='Vencimiento Seguro de ".$datosSeguroCdad['seguro']." en menos de 2 meses. Fecha ".$fechaVtoSeguro."' title='Vencimiento Seguro de ".$datosSeguroCdad['seguro']." en menos de 2 meses. Fecha ".$fechaVtoSeguro."'/>";
		break;
		
		
		default:
		$estado = "<img src='../../imagenes/ok.png'/ style ='width: 25px;' alt='Correcto.Seguro de ".$datosSeguroCdad['seguro']." vigente' title='Correcto. Seguro vigente'/>";
		break;
		
	}
	
	return $estado;
}

function tieneTratamientos($id){
		$sql = 'SELECT COUNT(*) FROM `rel_fallas_cofradias_Tratamientos` WHERE `idFallas_Cofradias` = '.$id ;
		$numTrats = consultaContar($sql);
		return $numTrats ;
	
}

function tienefacturas($id){
	
		$sqlhayFras = "SELECT COUNT(*) as numFras FROM `facturas` WHERE `idFallas_Cofradias` = $id ";
		$hayFras = consultaMulti($sqlhayFras);
		foreach($hayFras as $hayFras){}
		//echo  'Hay fras? '.$hayFras['numFras'].' ->'. $sqlhayFras; 
		if($hayFras == 0)
		{
			$facturar = 1;
		
		} else{
			$sqlFacturasExistentes = "SELECT * FROM `facturas` WHERE `idFallas_Cofradias` = $id ";
			//echo $sqlFacturasExistentes ;
			//$bd =conectarObj();
			$datosFrasExistentes = consultaMulti($sqlFacturasExistentes);
			foreach ($datosFrasExistentes as $datosFrasExistentes){
				//Buscamos fechas... Y comparamos las fechas con la de hoy en timespamp
			
				$hoy = date('Y-m-d');
				$timestampHoy =  strtotime($hoy);
				$timestampFactura =  strtotime($datosFrasExistentes['fechaFactura']);
				
				$diferenciaDias = ($timestampHoy - $timestampFactura)/(356*24*60*60);
				
				if($diferenciaDias < 365 )
				{
					$facturar = 0;
				}else {
					$facturar = 1;
					}
			}
		
		}
		
	return $facturar;	
	
}
function fechaUltimaFactura($id)
{
		$sqlFecha = 'SELECT max(`fechaFactura`) as fechaUltima FROM `facturas` WHERE `idFallas_Cofradias` = '.$id;
		//echo 	$sqlFecha ;	
		$fechaUltimaFra = consultaMulti($sqlFecha);
		foreach($fechaUltimaFra as $fechaUltimaFra){}
		//		echo fechaUsAEsp($fechaUltimaFra['fechaUltimaFra']);
							
		return fechaUsAEsp($fechaUltimaFra['fechaUltima']) ;
	
}


function fechaUsAEsp($fecha){
	$anio = substr($fecha,0,4);
	$mes = substr($fecha,5,2);
	$dia = substr($fecha,8,2);
	
	$fechaEsp = $dia.'-'.$mes.'-'.$anio;
	return $fechaEsp;
	
}

function encodeParams($string){
	$params = base64_encode ( $string);
	return $params;
	
}

function decodeParams($params){
	$string = base64_decode ( $params);
	return $string;
}

	
function insertaRegUltimaId($sql){	
	$bd = conectar();
	$bd->query($sql);
	$lastId = $bd->lastInsertId();
	return $lastId;
	$bd = NULL;
}
	  
	
function olvideVariables(){
	
	if(!$_POST['idFallas_Cofradias']){
		$idFallas_Cofradias = $_SESSION['s_idFallas_Cofradias'] ;
		}
	if(!$_POST['razonSocial']){
		$razonSocial = $_SESSION['s_razonSocial'];
	
		}
}


function obtenerFechaEnLetra($fecha){
	// Courtesy of Cesar Manuel
    $num = date("j", strtotime($fecha));
    $anno = date("Y", strtotime($fecha));
    $mes = array('enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');
    $mes = $mes[(date('m', strtotime($fecha))*1)-1];
	$mes = ucfirst ($mes);
    return $num.' de '.$mes.' de '.$anno;

}


function alerta($texto){
	echo '
	<div style="width: 200px; height: 250px; margin: 10% auto;">
		<img src="../../imagenes/loader.gif"/>
	</div>
	<script type="text/javascript">
		alert("'.$texto.'");
		history.go(-1);
	</script>
	';
	
}

function sanear_string($string)
{
    $string = trim($string);
	
    $string = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $string
    );
    $string = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $string
    );
    $string = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $string
    );
    $string = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $string
    );
    $string = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $string
    );
    $string = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C',),
        $string
    );
    //Esta parte se encarga de eliminar cualquier caracter extraño
    $string = str_replace(
        array("\\", "¨", "º", "~","'",
             "#", "@", "|", "!", "\"",
             "·", "$", "%", "&",
             "(", ")", "?", "'", "¡",
             "¿", "[", "^", "`", "]",
             "+", "}", "{", "¨", "´",
             ">", "< ", ";", ",", ":","_",
             "."),
        ' ', $string);
	//Por si creamos un ' ' al final :)
	$string = trim($string);
    return $string;
}

function seleccionTipo($tipo){
	switch ($tipo)
	{
		case 'Falla':
			$completar = "Falla";
		break;
		case 'Cofradia':
			$completar = "Cofradía";
		break;
		case 'juntaLocal':
			$completar = "Fallas";
		break;
		
	}
	return $completar;
}

function checkIBAN($iban)
{
	if(strlen($iban)==24)
	{

		$digitoControl=getCodigoControl_IBAN(strtoupper(substr($iban,0,2)), substr($iban,4));

		if($digitoControl==substr($iban,2,2))

			return true;

	}

	return false;

}

function getCodigoControl_IBAN($codigoPais,$cc)
{
	// cada letra de pais tiene un valor
	$valoresPaises = array(
		'A' => '10',
		'B' => '11',

		'C' => '12',

		'D' => '13',

		'E' => '14',

		'F' => '15',

		'G' => '16',

		'H' => '17',

		'I' => '18',

		'J' => '19',

		'K' => '20',

		'L' => '21',

		'M' => '22',

		'N' => '23',

		'O' => '24',

		'P' => '25',

		'Q' => '26',

		'R' => '27',

		'S' => '28',

		'T' => '29',

		'U' => '30',

		'V' => '31',

		'W' => '32',

		'X' => '33',

		'Y' => '34',

		'Z' => '35'
	);

 	// reemplazamos cada letra por su valor numerico y ponemos los valores mas dos ceros al final de la cuenta
	$dividendo = $cc.$valoresPaises[substr($codigoPais,0,1)].$valoresPaises[substr($codigoPais,1,1)].'00';
	// Calculamos el modulo 97 sobre el valor numerico y lo restamos de 98
	// Utilizamos bcmod para poder realizar el calculo, ya que un int sobre 32 bits no puede gestionar tantos numeros
	$digitoControl = 98 - bcmod($dividendo, '97');
	// Si el digito de control es un solo numero, añadimos un cero al delante
	if(strlen($digitoControl)==1)
	{
		$digitoControl = '0'.$digitoControl;
	}
	return $digitoControl;
}	

?>