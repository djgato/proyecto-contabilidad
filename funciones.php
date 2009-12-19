<?php 
function cambiarFormatoFecha($fecha){ 
    list($anio,$mes,$dia)=explode("-",$fecha); 
    return $dia."-".$mes."-".$anio; 
}

function consultarMovimiento ($id_ldiario, $usuario){
	$res = $usuario->consulta("SELECT * FROM movimiento WHERE id_ldiario_movimiento = '$id_ldiario'"); 
	while($mov=$usuario->extraer_registro($res)){
		$id_cuenta = $mov['id_cuenta_movimiento'];
		$nombre_cuenta = $usuario->consulta("SELECT nombre_cuenta FROM cuenta WHERE id_cuenta = '$id_cuenta'");
		$monto = $mov['monto_movimiento'];
		$columna = $mov['columna_movimiento'];
		if ($columna == "d"){
			$datos[]= "<tr><td>$nombre_cuenta</td><td></td><td>$monto</td><td></td></tr>";
			}
		else {
			$datos[]= "<tr><td></td><td>$nombre_cuenta</td><td></td><td>$monto</td></tr>";
			}
			return $datos;
	}
}
	
?>