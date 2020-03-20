<?php
include('funcionesPhp.php');
$string = $_GET['valor'];
$sql = 'SELECT * FROM `encargadosTratamiento` WHERE `cif` like \'%'.$string.'%\'';

$resultado = consultaMulti($sql);
$resultados = '<div class="full_screen">
			<table>
				<tr>
					<th>Razon Social</th>
					<th>Cif.</th>
					<th>Gerente</th>
					<th colspan="4">Acciones</th>
					
				</tr>';
	
foreach($resultado as $salidaProveedores){
		
				$resultados .= '
				<tr>
					<td>'.$salidaProveedores['razonSocial'].' </td>
					
					<td>'.$salidaProveedores['cif'].'</td>
					<td>'.$salidaProveedores['gerente'].'</td>
					
					<td><a href = "./verDetalleEncargado.php?idEncargado='.$salidaProveedores['id'].'">Ver Detalle</a></td>
					
					<td><a href = "./editarEncargado.php?idEncargado='.$salidaProveedores['id'].'">Editar</a></td>
					<td><a '.$visibleAdmin.'href = "./borrarEncargado.php?idEncargado='.$salidaProveedores['id'].'" onclick="confirm(\'¿Estas seguro de querer borrar este registro?\');">Borrar</a></td>
					
				</tr>
		';
	}
$resultados.= '</table>';
echo $resultados;
?>