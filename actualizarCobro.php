<?php 
include ("conexion.php");
$res = $usuario->consulta("SELECT nombre_cuenta,id_cuenta FROM cuenta WHERE nombre_cuenta LIKE '%Cuentas por Cobrar%'");
echo "<strong>Cuentas para Cobranza: </strong>
		<select name='cuent' id='cuent' onchange='consultarCobro()' >
		<option value='-' >---Seleccione una Opcion---</option>";
while($nom = $usuario->extraer_registro($res)){
	$no = $nom['nombre_cuenta'];
	$id = $nom['id_cuenta'];
	echo "<option value='$id' >$no</option>";
	}
	echo "</select>";
?>