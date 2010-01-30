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

function balanceo ($tipoCuenta, $usuario, $usuario2){
	$res = $usuario->consulta("SELECT * FROM cuenta WHERE tipo_cuenta = '".$tipoCuenta."'");
	$cuentas[] = NULL;
	while($cuenta=$usuario->extraer_registro($res)){
	$debe[] = list();
	$haber[] = list();
	$resultado[] = array();
	$id_cuenta = $cuenta["id_cuenta"];
	$nombre_cuenta = $cuenta["nombre_cuenta"];
	$res1 =  $usuario2->consulta("SELECT * FROM movimiento WHERE id_cuenta_movimiento = $id_cuenta");
		while($mov = $usuario2->extraer_registro($res1)){
			if ($mov["columna_movimiento"]=="d"){
				$debe[]= $mov["monto_movimiento"];
				}
			else {
				$haber[]= $mov["monto_movimiento"];
				}
		}
		$total = 0;
		$total = sumarColumnas ($debe,$haber,$tipoCuenta);
		$resultado[] =$cuenta['nombre_cuenta'].'  '.$total;
	}
	return $resultado;
}

function sumarColumnas ($debe,$haber,$tipoCuenta){
	$totalDebe =0;
	$totalHaber =0;
	while( list($posicion,$valor) = each($debe)){
		$totalDebe = $totalDebe + $valor;
		}
	while( list($posicion1,$valor1) = each($haber)){
		$totalDebe = $totalDebe + $valor1;
	}
	if ($tipoCuenta == 'Activo'){
		return $totalDebe - $totalHaber;
		}
	else {
		return $totalHaber - $totalDebe;
		}
	}

function redondeado ($numero, $decimales) {
$factor = pow(10, $decimales);
return (round($numero*$factor)/$factor); }

?>