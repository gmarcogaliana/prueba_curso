<?php
include('funcionesPhp.php');
$string = $_GET['valor'];
$id = $_GET['id']; 
$rol = $_GET['rol']; 
$nombreAdmin = $_GET['nombreAdmin']; 
$idAdministrador = $_GET['idAdministrador']; 



//include("./templates/selectorVisibilidad.tpl");

if($_SESSION['s_role'] == 9){
		// es SuperUser  llega $idAdministrador  via get?
		$iconouser = "root.png";
		$visibleRoot="visibleSuperUser";
		$noVisibleRoot="noVisibleSuperUser";
		$visibleUser = "noVisibleUser";
		//echo '$_GET[idAdministrador] via GET = '.$_GET['idAdministrador'];
		$idAdministrador = 0;
		
		$es = "DIOS";
		
	}else{
		//ECHO "NO ES SUPERUSER!!". $_SESSION['s_AdminId'].'<BR>';
		//No es superuser
		$iconouser = "usuarios.png";
		$visibleRoot="noVisibleSuperUser";
		$visibleUser = "visibleUser";
		
		//$noVisibleRoot="";
		if($_GET['idAdministrador'] == $_SESSION['s_AdminId']){
			
				$idAdministrador = $_GET['idAdministrador'];
			}
			else{
				//trampa
				ECHO "traaampa!!";
				$idAdministrador = $_SESSION['s_AdminId'];
			}
		
	}
	//echo '$idAdministrador es '.$idAdministrador;

	// Busca un admin!!
	$sql = 'SELECT `idFallas_Cofradias`, `razonSocial`, `direccion`, `poblacion`, `provincia`, `cp`, `cif`, `Presidente`, `dniPresidente`, `fechaAltaPresidente`, `iban`, `mail`, `telefono`, `fechaAlta`, `fechaRenovacion`, `idJunta`, `precioAlta`, `precioRenovacion`, `fechaAltaPlataforma`, `tipo` FROM `fallas_cofradias` WHERE `razonSocial`  like \'%'.$string.'%\'';


//echo 'rol -> '.$_SESSION['s_role'].' es '.$es ;

$resultados ='<div class="full_screen">
			<table>
				<tr>
					<th></th>
					<th>Razon Social </th>
					<th>Cif Cdad.</th>
					<th>Fecha Alta</th>';
					
	
	
	$resultados .='				
				
					<th class="" colspan="4" style="text-align: center;">Acciones</th>
					
					<th class="" colspan="1" style="text-align: center;">LOPD</th>
					<th class="" style="text-align: center;">Crear Factura</th>
					<th class="" style="text-align: center;">Ver Factura</th>
					';
					
					
	
	/*	
	$resultados .='				
					
					<th class="'.$noVisibleRoot.'" colspan="1" style="text-align: center;">Ver Detalle</th>
					<th class="'.$noVisibleRoot.'" colspan="1" style="text-align: center;">LOPD</th>
					<th class="'.$noVisibleRoot.'" style="text-align: center;">Presidente</th>
					<th class="'.$noVisibleRoot.'"style="text-align: center;">Seguro</th>
					<th class="'.$noVisibleRoot.'" colspan="1" style="text-align: center;">FACTURA</th>';
	*/
	$resultados .='				
				</tr>
					';
	
	
	$i=1;
	$color = "style='background-color: whitesmoke;'";
	$resultado = consultaMulti($sql);
	foreach($resultado as $salidaComunidades)
	{
	//$statusPresidente = statusPresidente($salidaComunidades['idFallas_Cofradias']);
	//$statusSeguro = statusSeguro($salidaComunidades['idFallas_Cofradias']);
			
	$resultados .= '
					<tr '.$color.'>
						<td style="width: 30px;">'.$i.' </td>
						<td>'.$salidaComunidades['razonSocial'].' </td>
						
						<td>'.$salidaComunidades['cif'].'</td>
						<td>'.$salidaComunidades['fechaAlta'].'</td>';
	if($_SESSION['s_role'] == 9)
	{				
	
	if( $salidaComunidades['idAdministrador']== 0){
					$nombreAdmin = "NO ASIGNADO";
					$imagesAsignar = "logostablas/Addecuo-ico-asignar-administrador-300px.png";
					$textoAlt="Asignar Administrador a esta Comunidad";
					$rutaScript = './asignarAdministrador.php?idFallas_Cofradias='.$salidaComunidades['idFallas_Cofradias'].'&razonSocial='.$salidaComunidades['razonSocial'].'';
				}else {
					$nombreAdmin = $salidaComunidades['NombreAdmin'];
					$imagesAsignar = "logostablas/Addecuo-ico-desasignar-administrador-300px.png";
					$textoAlt="Desasignar Administrador de esta Comunidad";
					$rutaScript = './desasignarAdministrador.php?idFallas_Cofradias='.$salidaComunidades['idFallas_Cofradias'].'&idAdministrador='.$salidaComunidades['idAdministrador'].'&razonSocial='.$salidaComunidades['razonSocial'].'';
					}
	$resultados.='<td style="font-size: 0.8em;">'.$nombreAdmin.'</td>';
	
	}else{
	//$resultados.='<td colspan="2" style="text-align: center;">Acciones</td>';
		
	}
	
			
	$resultados.='
						<td>
							<a href = "./verDetalleFalla_Cofradia.php?idFallas_Cofradias='.$salidaComunidades['idFallas_Cofradias'].'&idAdministrador='.$id.'">
								<img src="../../imagenes/logostablas/Addecuo-ico-ver-300px.png" style ="width: 25px;" alt="Ver Detalle" title="Ver"/></a>
							</a>
						</td>
						
			';
	
					// Solo borra y  desasigna el administrador 
		$resultados .= '	
					<td>
						<a href = "./editarComunidades.php?idFallas_Cofradias='.$salidaComunidades['idFallas_Cofradias'].'&idAdministrador='.$id.'"> 
							<img src="../../imagenes/logostablas/Addecuo-ico-editar-300px.png" style ="width: 25px;" alt="Editar" title="Editar"/>
						</a>
					</td>
						
					<td '.$celdaVisible.'>
						<a href = "./borrarComunidades.php?idFallas_Cofradias='.$salidaComunidades['idFallas_Cofradias'].'" onclick="confirm(\'¿Estas seguro de querer borrar este registro?\');">
							<img src="../../imagenes/logostablas/Addecuo-ico-eliminar-300px.png" style ="width: 25px;" alt="Borrar Registro" title="Borrar"/>
						</a>
						</a>
					</td>
					
						<td '.$celdaVisible.'>
							<a href = "'.$rutaScript.'" onclick="confirm(\'¿Estas seguro de Desasignar el Administrador de esta Comunidad?\');">
							<img src="../../imagenes/'.$imagesAsignar.'" style ="width: 30px;" alt="'.$textoAlt.'" title="'.$textoAlt.'"/>
							</a>
						</td>
					
					
				
				';	
		
				
				
			$resultados .= '	
			
					<td>
						<a href = "../rgpd/verRgpd.php?idFallas_Cofradias='.$salidaComunidades['idFallas_Cofradias'].'&idJunta='.$id.'&razonSocial='.$salidaComunidades['razonSocial'].'&idAdministrador='.$id.'&nombreAdmin='.$nombreAdmin.'&fechaAlta='.$salidaComunidades['fechaAlta'].'">
						 <img src="../../imagenes/logostablas/Addecuo-ico-entrar-300px.png" style ="width: 25px;" alt="Entrar en su LOPD" title="Entrar en su LOPD"/>
						</a>
					</td>
					';
			/*		
			$resultados .= '
					<td class="'.$noVisibleRoot.'" style="text-align: center;">'.$statusPresidente.'</td>
					<td class="'.$noVisibleRoot.'" style="text-align: center;">'.$statusSeguro.'</td>';
			*/
			$resultados .= '
					<td class="'.$visibleAdmin.'" style="text-align: center; border: 0px;">
						
							<a class="'.$claseEnlace.'" href = "../../fpdf/crearFactura.php?
							idFallas_Cofradias='.$salidaComunidades['idFallas_Cofradias`'].'
							&idAdministrador='.$salidaComunidades['idJunta'].'
							&razonSocial='.$salidaComunidades['razonSocial'].'
							&nombreAdmin='.$_GET["razonSocial"].'
							&fechaAlta='.$salidaComunidades['fechaAlta'].'">
							 <img src="../../imagenes/logostablas/Addecuo-ico-crear-factura-300px.png" style ="width: 25px;" alt="factura" title="factura"/>
							</a>
					</td>
					
					<td>
						<a href = "../../fpdf/verFactura.php?idFallas_Cofradias='.$salidaComunidades['idFallas_Cofradias'].'&idJunta='.$id.'">
							<img src="../../imagenes/logostablas/Addecuo-ico-ver-factura-300px.png" style ="width: 25px;" alt="Ver Ultima factura" title="Ver Ultima factura"/></a>
						</a>
						</td>

					
				
				
					
					
				</tr>
				';
				$i++;
				if($color == "style='background-color: whitesmoke;'"){ $color = "style='background-color: white;'";} else {$color = "style='background-color: whitesmoke;'";}
	}
	
$resultados.= '</table>';


echo $resultados;
?>