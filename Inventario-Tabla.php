<?php
include ("conexion.php");
$hola="hola";
echo '<table width="200" border="1">';
echo '<tr>
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
	 	
$producto_nombre = $_GET["q"];

	$res =  $usuario->consulta("SELECT * 
							   FROM producto 
							   WHERE nombre_producto='$producto_nombre'");
 	$id = $usuario->extraer_registro($res);
	$id_prod = $id["id_producto"];
	
	$res1 =  $usuario->consulta("SELECT * 
								FROM ficha_inventario 
								WHERE id_producto_finventario= '$id_prod'");
 	$id1 = $usuario->extraer_registro($res1);
	$id1_inv = $id1["id_finventario"];
	
	$res2 =  $usuario->consulta("SELECT * 
								FROM inventario_transaccion I 
								WHERE I.id_finventario= '$id1_inv'");
	
			while ($id2 = $usuario->extraer_registro($res2)){
				$id_transc = $id2["id_transaccion"];
				
		 		$res3=$usuario->consulta("SELECT * 
										  FROM transaccion
										  WHERE id_transaccion = '$id_transc'");
				$transaccion = $usuario->extraer_registro($res3);
				
echo '<tr>
		<td>'.$transaccion["tipo_transaccion"].'</td>';
		$total = $transaccion["unidades_transaccion"] * $transaccion["precio_unidad"];
				if ($transaccion["tipo_transaccion"] == "Compra"){
					echo '<td>'.$transaccion["unidades_transaccion"].'</td>';
					echo '<td>'.$transaccion["precio_unidad"].'</td>';
					echo '<td>'.$total.'</td>';
					echo '<td></td> <td></td> <td></td>';
				}
				else if ($transaccion["tipo_transaccion"] == "Venta"){
					echo '<td></td> <td></td> <td></td>';
					echo '<td>'.$transaccion["unidades_transaccion"].'</td>';
					echo '<td>'.$transaccion["precio_unidad"].'</td>';
					echo '<td>'.$total.'</td>';
				}					
				echo '<td>'.$id["cant_exist"].'</td>';
				echo '<td>'.$id["cu_exist"].'</td>';
				echo '<td>'.$id["cant_exist"]*$id["cu_exist"].'</td>';
echo '</tr>';						
			}
echo '</table>';
?>