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
	$debe[] = "";
	$haber[] = "";
	$resultado[] = "";
	$totalTipoCuenta = 0; //monto total de activo o de pasivo
	while($cuenta=$usuario->extraer_registro($res)){
	unset($debe);
	unset($haber);
	$debe[] = "";
	$haber[] = "";
	$resultado[] = "";
	$id_cuenta = $cuenta["id_cuenta"];
	$nombre_cuenta = $cuenta["nombre_cuenta"];
	$res1 =  $usuario2->consulta("SELECT * FROM movimiento WHERE id_cuenta_movimiento = $id_cuenta");
		while($mov = $usuario2->extraer_registro($res1)){
			if ($mov["columna_movimiento"] == 'd'){
				$debe[]= $mov["monto_movimiento"];
				}
			else {
				$haber[]= $mov["monto_movimiento"];
				}
		}
		$total = 0;
		$total = sumarColumnas ($debe,$haber,$tipoCuenta);
		$resultado[] = $cuenta['nombre_cuenta'].'      '.$total;
		$totalTipoCuenta = $totalTipoCuenta + $total;
	}
	$resultado[] = '............TOTAL...........';
	$resultado[] = $totalTipoCuenta;
	return $resultado;
}

function sumarColumnas ($debe,$haber,$tipoCuenta){
	$totalDebe =0;
	$totalHaber =0;
	while( list($posicion,$valor) = each($debe)){
		$totalDebe = $totalDebe + $valor;
		}
	while( list($posicion1,$valor1) = each($haber)){
		$totalHaber = $totalHaber + $valor1;
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

function calcularISLR ($usuario, $porcentaje, $usuario2){
	$res = $usuario->consulta("SELECT * FROM cuenta WHERE tipo_cuenta = 'Ingreso'");
	$resultado = 0;
	while($cuenta=$usuario->extraer_registro($res)){
	unset($debe);
	unset($haber);
	$debe[] = "";
	$haber[] = "";
	$id_cuenta = $cuenta["id_cuenta"];
	$nombre_cuenta = $cuenta["nombre_cuenta"];
	$res1 =  $usuario2->consulta("SELECT * FROM movimiento WHERE id_cuenta_movimiento = $id_cuenta");
	while($mov = $usuario2->extraer_registro($res1)){
			if ($mov["columna_movimiento"] == 'd'){
				$debe[]= $mov["monto_movimiento"];
				}
			else {
				$haber[]= $mov["monto_movimiento"];
				}
		}
		$total = 0;
		$total = sumarColumnas ($debe,$haber,'Pasivo');
		$resultado = $resultado + $total;
	}
	$res = $usuario->consulta("SELECT * FROM cuenta WHERE tipo_cuenta = 'Egreso'");
	while($cuenta=$usuario->extraer_registro($res)){
	unset($debe);
	unset($haber);
	$debe[] = "";
	$haber[] = "";
	$id_cuenta = $cuenta["id_cuenta"];
	$nombre_cuenta = $cuenta["nombre_cuenta"];
	$res1 =  $usuario2->consulta("SELECT * FROM movimiento WHERE id_cuenta_movimiento = $id_cuenta");
	while($mov = $usuario2->extraer_registro($res1)){
			if ($mov["columna_movimiento"] == 'd'){
				$debe[]= $mov["monto_movimiento"];
				}
			else {
				$haber[]= $mov["monto_movimiento"];
				}
		}
		$total = 0;
		$total = sumarColumnas ($debe,$haber,'Activo');
		$resultado = $resultado - $total;
	}
	$porcentaje = (int)$porcentaje;
	$porcentaje = ($porcentaje/100);
	$resultado = $resultado * $porcentaje;
	return $resultado;
}

function calcularUND ($usuario, $usuario2){
	$usuario->consulta("INSERT INTO libro_diario (id_ldiario, fecha_ldiario) VALUES ('','$fecha')");
$id = $usuario->extraer_registro($usuario->consulta("SELECT id_ldiario FROM libro_diario ORDER BY  id_ldiario DESC Limit 1"));
$id_ldiario = $id['id_ldiario'];
	
	$res = $usuario->consulta("SELECT * FROM cuenta WHERE tipo_cuenta = 'Ingreso'");
	$debe[] = "";
	$haber[] = "";
	$resultado = 0;
	$totalTipoCuenta = 0; //monto total de activo o de pasivo
	while($cuenta=$usuario->extraer_registro($res)){
	unset($debe);
	unset($haber);
	$debe[] = "";
	$haber[] = "";
	$id_cuenta = $cuenta["id_cuenta"];
	
	$res1 =  $usuario2->consulta("SELECT * FROM movimiento WHERE id_cuenta_movimiento = $id_cuenta");
		while($mov = $usuario2->extraer_registro($res1)){
			if ($mov["columna_movimiento"] == 'd'){
				$debe[]= $mov["monto_movimiento"];
				}
			else {
				$haber[]= $mov["monto_movimiento"];
				}
		}
		$total = 0;
		$total = sumarColumnas ($debe,$haber,'Activo');
		$resultado = $resultado + $total;
	$usuario->consulta("INSERT INTO movimiento (id_cuenta_movimiento,id_ldiario_movimiento,monto_movimiento,columna_movimiento) VALUES 	('$id_cuenta','$id_ldiario','$total','d');");
	}
	
	$res = $usuario->consulta("SELECT * FROM cuenta WHERE tipo_cuenta = 'Egreso'");
	$debe[] = "";
	$haber[] = "";
	$resultado = 0;
	$totalTipoCuenta = 0; //monto total de activo o de pasivo
	while($cuenta=$usuario->extraer_registro($res)){
	unset($debe);
	unset($haber);
	$debe[] = "";
	$haber[] = "";
	$id_cuenta = $cuenta["id_cuenta"];
	
	$res1 =  $usuario2->consulta("SELECT * FROM movimiento WHERE id_cuenta_movimiento = $id_cuenta");
		while($mov = $usuario2->extraer_registro($res1)){
			if ($mov["columna_movimiento"] == 'd'){
				$debe[]= $mov["monto_movimiento"];
				}
			else {
				$haber[]= $mov["monto_movimiento"];
				}
		}
		$total = 0;
		$total = sumarColumnas ($debe,$haber,'Pasivo');
		$resultado = $resultado - $total;
	$usuario->consulta("INSERT INTO movimiento (id_cuenta_movimiento,id_ldiario_movimiento,monto_movimiento,columna_movimiento) VALUES 	('$id_cuenta','$id_ldiario','$total','h');");
	}
	
	$idGanPer = 0;
		$res = $usuario->consulta("SELECT id_cuenta FROM cuenta WHERE nombre_cuenta LIKE 'Ganancias y Perdidas'");
	if ($res = $usuario->extraer_registro($res))
		$idGanPer = $res['id_cuenta'];
	else {
		$usuario->consulta("INSERT INTO cuenta (id_cuenta, nombre_cuenta, tipo_cuenta) VALUES ('','Ganancias y Perdidas','Pasivo')");
		$res = $usuario->consulta("SELECT id_cuenta FROM cuenta WHERE nombre_cuenta LIKE 'Ganancias y Perdidas'");
		$res = $usuario->extraer_registro($res);
		$idGanPer = $res['id_cuenta'];
		}
		$idUND =0;
$res = $usuario->consulta("SELECT id_cuenta FROM cuenta WHERE nombre_cuenta LIKE 'UND'");
	if ($res = $usuario->extraer_registro($res))
		$idGanPer = $res['id_cuenta'];
	else {
		$usuario->consulta("INSERT INTO cuenta (id_cuenta, nombre_cuenta, tipo_cuenta) VALUES ('','UND','Pasivo')");
		$res = $usuario->consulta("SELECT id_cuenta FROM cuenta WHERE nombre_cuenta LIKE 'UND'");
		$res = $usuario->extraer_registro($res);
		$idGanPer = $res['id_cuenta'];
		}
}


?>
