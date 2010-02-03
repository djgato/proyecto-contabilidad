<?php 
include ("conexion.php");

$resAux =  $usuario->consulta("SELECT id_cuenta FROM cuenta WHERE nombre_cuenta LIKE 'IVA%' ");
if ($hayAlgunTipoDeIva = $usuario->extraer_registro($resAux)){
	
	//CREO UN NUEVO ASIENTO EN EL LIBRO DE DIARIO CON SU FECHA RESPECTIVA----
	$res5 = $usuario->consulta("SELECT * FROM libro_diario ORDER BY id_ldiario DESC LIMIT 1");
				$ultimoRegistro = $usuario->extraer_registro($res5);
				
				$ultimoAsiento = $ultimoRegistro ['id_ldiario'];
				$ultimoAsiento = (int) $ultimoAsiento;
				
				$penultimoAsiento = ($ultimoAsiento + 1);
				$ultimoAsiento = ($penultimoAsiento + 1);
				
				$fechaUltimoAsiento = $ultimoRegistro ['fecha_ldiario'];
				list($año,$mes,$dia)=explode("-",$fechaUltimoAsiento);
				$fecha = $año."-".$mes."-30";
				
	$res6 = $usuario->consulta("INSERT INTO libro_diario (id_ldiario, fecha_ldiario) VALUES ('', '$fecha')");
	//--------------------------------------------------------------------------

	
	//-----------------OBTENGO IVA DE VENTA----------------------------------------
	$res = $usuario->consulta("SELECT SUM( m.monto_movimiento ) AS iva_venta, c.id_cuenta
	FROM movimiento m, cuenta c
	WHERE m.id_cuenta_movimiento = c.id_cuenta
	AND c.nombre_cuenta LIKE  'IVA Venta'");
	
	$ivas = $usuario->extraer_registro($res);
	$ivaDeVenta = $ivas["iva_venta"]; $ivaDeVenta = (int) $ivaDeVenta; 
	$idIvaDeVenta = $ivas ['id_cuenta'];
	//-------------------------------------------------------------------------------
	
	
	//------------------OBTENGO IVA DE COMPRA----------------------------------------
	$res2 = $usuario->consulta("SELECT SUM( m.monto_movimiento ) AS iva_compra, c.id_cuenta
	FROM movimiento m, cuenta c
	WHERE m.id_cuenta_movimiento = c.id_cuenta
	AND c.nombre_cuenta LIKE  'IVA de Compra'");

	$ivas = $usuario->extraer_registro($res2);
	$ivaDeCompra = $ivas["iva_compra"]; $ivaDeCompra = (int) $ivaDeCompra;
	$idIvaDeCompra = $ivas ['id_cuenta'];
	//-------------------------------------------------------------------------------
	
	
	//-------------------CALCULATING...--------------------------------------------
	$ivaPorPagar = ($ivaDeVenta - $ivaDeCompra);
	if ($ivaDeVenta >= $ivaDeCompra) $column = 'h';
	else {
		$ivaPorPagar = ($ivaPorPagar * (-1));
		$column = 'd';		
	}
	//------------------------------------------------------------------------------
	
	
	//---ASEGURO QUE LA CUENTA IVA POR PAGAR NO EXISTA PA NO CREARLA TWICE----------
	$resAux2 = $usuario->consulta("SELECT * FROM cuenta WHERE nombre_cuenta = 'IVA por Pagar' ");
	if (!($IvaPorPagar = $usuario->extraer_registro($resAux2)))
		$res3 = $usuario->consulta("INSERT INTO cuenta (id_cuenta, nombre_cuenta, tipo_cuenta) VALUES ('', 'IVA por Pagar', 'Pasivo')");
	//------------------------------------------------------------------------------	
	
	//--OBTENGO EL ID DE LAS CUENTAS IVA POR PAGAR Y BANCO--------------------------
	$res4 = $usuario->consulta("SELECT id_cuenta FROM cuenta WHERE nombre_cuenta = 'IVA por Pagar' ");
	$idIvaPorPagar = $usuario->extraer_registro($res4);
	$idIvaPorPagar = $idIvaPorPagar['id_cuenta'];
	
	$res4 = $usuario->consulta("SELECT id_cuenta FROM cuenta WHERE nombre_cuenta = 'Banco' ");
	$Banco = $usuario->extraer_registro($res4);
	$idBanco = $Banco['id_cuenta'];
	//-------------------------------------------------------------------------------
	
	
	//---INSERTA EN UN ASIENTO EL CALCULO DEL IVA POR PAGAR----------------
	$res3 = $usuario->consulta("INSERT INTO movimiento (id_cuenta_movimiento, id_ldiario_movimiento, monto_movimiento, columna_movimiento) VALUES ('$idIvaDeVenta', '$penultimoAsiento', '$ivaDeVenta', 'd')");
	
	$res3 = $usuario->consulta("INSERT INTO movimiento (id_cuenta_movimiento, id_ldiario_movimiento, monto_movimiento, columna_movimiento) VALUES ('$idIvaDeCompra', '$penultimoAsiento', '$ivaDeCompra', 'h')");
	
	$res3 = $usuario->consulta("INSERT INTO movimiento (id_cuenta_movimiento, id_ldiario_movimiento, monto_movimiento, columna_movimiento) VALUES ('$idIvaPorPagar', '$penultimoAsiento', '$ivaPorPagar', '$column')");
	//-----------------------------------------------------------------------
	
	//---INSERTA EN OTRO ASIENTO EL IVA POR PAGAR PAGADO!-------------------
	if ($column == 'h'){
		$res6 = $usuario->consulta("INSERT INTO libro_diario (id_ldiario, fecha_ldiario) VALUES ('', '$fecha')");
		
		$res3 = $usuario->consulta("INSERT INTO movimiento (id_cuenta_movimiento, id_ldiario_movimiento, monto_movimiento, columna_movimiento) VALUES ('$idIvaPorPagar', '$ultimoAsiento', '$ivaPorPagar', 'd')");
		$res3 = $usuario->consulta("INSERT INTO movimiento (id_cuenta_movimiento, id_ldiario_movimiento, monto_movimiento, columna_movimiento) VALUES ('$idBanco', '$ultimoAsiento', '$ivaPorPagar', 'h')");
	}
}
	

?>











