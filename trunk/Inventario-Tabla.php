<?php
include ("conexion.php");
include ("funciones.php");
$producto_nombre = $_GET['q'];	
$meses=array();
$años=array();
if ($producto_nombre != "null"){
	$res =  $usuario->consulta("SELECT * 
								FROM producto 
								WHERE nombre_producto='$producto_nombre'");
	$id = $usuario->extraer_registro($res);
	$id_prod = $id["id_producto"];
	
	$res1 =  $usuario->consulta("SELECT * 
								FROM ficha_inventario 
								WHERE id_producto_finventario= '$id_prod'");
/*echo '<table>
		<tr><td><strong>Seleccione mes del inventario que desea ver: </strong></td><td><td>';

		while ($inventario = $usuario->extraer_registro($res1)){
			$inventario_fecha = $inventario ["fecha_inventario"];
			list($año,$mes,$dia)=explode("-",$inventario_fecha);
			if (!(in_array($año,$años)))
				$años [] = $año;
			if (!(in_array($mes,$meses)))
				$meses[] = $mes;
		}
			echo '<tr><td>'.count($años).'</td></tr>';
			echo '<select name="year">';
			echo'<option value="-">0000</option>';
			for ( $i=0; $i<= count($años); $i++)
				echo'<option value='.$año[$i].'>'.$año[$i].'</option>';	
			echo '</select> /'; 
				
			echo '<select name="month">';
			echo'<option value="-">00</option>';
			for ( $i=0; $i<= count($meses) ;$i++)
				echo'<option value='.$meses[$i].'>'.$meses[$i].'</option>';	
			echo '</select>';
			echo '</td></tr></table>';*/
	
echo '<table width="200" border="1">
		<tr>
		<td align="center" width="25%">Descripcion</td>
		<td colspan="3" align="center" width="25%">Entrada</td>
		<td colspan="3" align="center" width="25%">Salida</td>
		<td colspan="3" align="center" width="25%">Existencia</td>
	 </tr>
	 <tr>
	 	<td></td> <td>Cant</td> <td>CU</td> <td>TOTAL</td>
				  <td>Cant</td> <td>CU</td> <td>TOTAL</td>
				  <td>Cant</td> <td>CU</td> <td>TOTAL</td>
	 </tr>';
	$str=""; // AQUI GUARDO TODA LA INFO QUE IMPRIMO LATER

 	while ($id1 = $usuario->extraer_registro($res1)){
		$id1_inv = $id1["id_finventario"];
						$str .= '<tr>';
						$str .= '<td>'.$id1["descripcion"].'</td>';
		
		$res2 =  $usuario->consulta("SELECT * 
									FROM transaccion 
									WHERE id_finventario_transaccion= '$id1_inv'");
		
				while ($id2 = $usuario->extraer_registro($res2)){
					$id_transc = $id2["id_transaccion"];
					
					$res3=$usuario->consulta("SELECT * 
											  FROM transaccion
											  WHERE id_transaccion = '$id_transc'");
					$transaccion = $usuario->extraer_registro($res3);
					$total = $transaccion["unidades_transaccion"] * $transaccion["precio_unidad"];
					
					if ($transaccion["tipo_transaccion"] == "Entrada"){
						$str .= '<td>'.$transaccion["unidades_transaccion"].'</td>';
						$str .= '<td>'.$transaccion["precio_unidad"].'</td>';
						$str .= '<td>'.$total.'</td>';
						$str .= '<td></td> <td></td> <td></td>';
					}
					else if ($transaccion["tipo_transaccion"] == "Salida"){
						$str .= '<td></td> <td></td> <td></td>';
						$str .= '<td>'.$transaccion["unidades_transaccion"].'</td>';
						$str .= '<td>'.$transaccion["precio_unidad"].'</td>';
						$str .= '<td>'.$total.'</td>';
					}
					if ($transaccion["tipo_transaccion"] == "Existencia"){
						if ($id1["descripcion"]=='Inventario Inicial'){
							$str .= '<td></td> <td></td> <td></td>';
							$str .= '<td></td> <td></td> <td></td>';
						}
						$str .= '<td>'.$transaccion["unidades_transaccion"].'</td>';
						$str .= '<td>'.$transaccion["precio_unidad"].'</td>';
						$str .= '<td>'.$total.'</td>';
						$str .= '</tr>';
					}						
				}	
	}
	echo $str;
echo '</table>';
}
?>