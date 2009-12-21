<?php 
function cambiarFormatoFecha($fecha){ 
    list($anio,$mes,$dia)=explode("-",$fecha); 
    return $dia."-".$mes."-".$anio; 
}

function consultarMovimiento ($id_ldiario, $usuario){
	$res = $usuario->consulta("SELECT * FROM movimiento WHERE id_ldiario_movimiento = '$id_ldiario'"); 
	$datos[]="";
	$datosh[]="";
	while($mov=$usuario->extraer_registro($res)){
		$id_cuenta = $mov['id_cuenta_movimiento'];
		$nombre_cuenta = $usuario->extraer_registro($usuario->consulta("SELECT nombre_cuenta FROM cuenta WHERE id_cuenta = $id_cuenta"));
		$nombre_cuenta = $nombre_cuenta["nombre_cuenta"];
		$monto = $mov['monto_movimiento'];
		$columna = $mov['columna_movimiento'];
		if ($columna == "d"){
			$datos[]= "<tr><td>$nombre_cuenta</td><td></td><td>$monto</td><td></td></tr>";
			}
		else {
			$datosh[]= "<tr><td></td><td>$nombre_cuenta</td><td></td><td>$monto</td></tr>";
			}	
	}
	while( list($posicion,$valor) = each($datosh)){
		$datos[]= $valor;
		}
	$datos[]="";
	return $datos;
}
	
?>