<?php
include('funcionesPhp.php');
$string = $_GET['valor'];
$sql = 'SELECT * FROM `encargadosTratamiento` WHERE `cif` like \'%'.$string.'%\'';

$resultado = consultaMulti($sql);
$resultados = '
			<br>	
			<table >	
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
					<td><input type="checkbox" name="id"  value="'.$salidaProveedores['id'].'" onclick=\'capturarCheck(this);\'/></td>
					</td>
					
				</tr>
		';
	}
$resultados.= '</table>';
echo $resultados;
?>