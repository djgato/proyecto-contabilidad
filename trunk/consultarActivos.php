<?php
echo '
<table width="200" border="0">
<tr>
    <td>';

include ("conexion.php");
$res =  $usuario->consulta("SELECT * FROM cuenta WHERE tipo_cuenta = 'Activo'] ORDER BY id_ldiario_movimiento");
while($cuenta = $usuario->extraer_registro($res)){
	$id_cuenta = $cuenta["id_cuenta"];
	$nombre_cuenta = $cuenta["nombre_cuenta"];
	$res1 =  $usuario->consulta("SELECT * FROM movimiento WHERE id_cuenta_movimiento = $id_cuenta]");
	$mov = $usuario->extraer_registro($res1);
	echo '<table width="200" border="0">
			<tr>
			  <td align="center"><u>$nombre_cuenta</u>
			  <table width="200" border="0">
			  	<tr>';
    while( list($posicion,$valor) = each($mov)){
		
		}
	}
	
	
echo '   
      <td align="right">200 |</td>
      <td>1333</td>
    </tr>
    <tr>
      <td align="right">344 |</td>
      <td>1345</td>
    </tr>
    <tr>
      <td align="right">200 |</td>
      <td>399</td>
    </tr>
  </table>
  
      </td>
    </tr>
  </table>
</td>
  </tr>
</table>
';
?>