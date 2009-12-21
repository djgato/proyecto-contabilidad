<?php
include ("conexion.php");
$cont = 0;
echo '<table width="200" border="0"> 
<tr>';
$res =  $usuario->consulta("SELECT * FROM cuenta WHERE tipo_cuenta = 'Ingreso'");
while($cuenta = $usuario->extraer_registro($res)){		
        $debe[] = "";
		$haber[] = "";
	$id_cuenta = $cuenta["id_cuenta"];
	$nombre_cuenta = $cuenta["nombre_cuenta"];
	$res1 =  $usuario->consulta("SELECT * FROM movimiento WHERE id_cuenta_movimiento = $id_cuenta");
	while($mov = $usuario->extraer_registro($res1)){
		if ($mov["columna_movimiento"]=="d"){
			$debe[]= "( ".$mov["id_ldiario_movimiento"]." ) ".$mov["monto_movimiento"]." |";
			}
		else {
			$haber[]= " |".$mov["monto_movimiento"]." ( ".$mov["id_ldiario_movimiento"]." )";
			}
		}
	if ($cont < 1)
		echo '<tr>';
	echo '
			  <td align="center"><u><strong>______'.$nombre_cuenta.'______</strong></u>
			  <table width="200" border="0">
			  	<tr>
				  <td>
				  	<table width="200" border="0" align="center">';
			  		while( list($posicion2,$valor2) = each($debe)){	
						echo '
						<tr>
				  			<td align="right">'.$valor2.'</td>
						</tr>';
					}
					echo '
					</table>
				   </td>';
				  echo '
				   <td>
				  	<table width="200" border="0" align="center">';
			  		while( list($posicion3,$valor3) = each($haber)){	
						echo '
						<tr>
				  			<td align="left">'.$valor3.'</td>
						</tr>';
					}
					echo '</table>
					</td>
				</tr>
				</table>
			</td>';
			if ($cont == 1){
			echo'</tr>';
			$cont = -1;
			}
			$cont = $cont++;
	}
echo '
</tr>
</table>';
?>