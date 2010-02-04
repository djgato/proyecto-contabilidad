<?php 
require_once('conexion.php');


$resAux =  $usuario->consulta("SELECT * FROM cuenta WHERE nombre_cuenta LIKE 'Compra%' ");
if ($hayCompra = $usuario->extraer_registro($resAux)){
	
	
	//CREO UN NUEVO ASIENTO EN EL LIBRO DE DIARIO CON SU FECHA RESPECTIVA----
	$res5 = $usuario->consulta("SELECT * FROM libro_diario ORDER BY id_ldiario DESC LIMIT 1");
				$ultimoRegistro = $usuario->extraer_registro($res5);
				
				$ultimoAsiento = $ultimoRegistro ['id_ldiario'];
				$ultimoAsiento = (int) $ultimoAsiento;
				$ultimoAsiento = ($ultimoAsiento + 1);
				
				$fechaUltimoAsiento = $ultimoRegistro ['fecha_ldiario'];
				list($año,$mes,$dia)=explode("-",$fechaUltimoAsiento);
				$fecha = $año."-".$mes."-30";
				
	$res6 = $usuario->consulta("INSERT INTO libro_diario (id_ldiario, fecha_ldiario) VALUES ('', '$fecha')");
	//----------------------------------------------------------------------------
	
	
	//------OBTENGO TODOS LOS PRODUCTOS-------------------------------------
	$res = $usuario->consulta("SELECT id_producto, nombre_producto FROM producto");
	while ($productos = $usuario->extraer_registro($res)){	
		$idProducto = $productos["id_producto"];
		$nombreProducto = $productos["nombre_producto"];
	//-----------------------------------------------------------------------
	
	
	
	//-----------------OBTENGO INVENTARIO FINAL-------------------------
		$res2 = $usuario->consulta("SELECT total_transaccion
		FROM transaccion
		WHERE id_finventario_transaccion = (SELECT id_finventario 
											FROM ficha_inventario 
											WHERE id_producto_finventario = '$idProducto' 
											ORDER BY id_finventario DESC LIMIT 1)
		
		ORDER BY id_transaccion DESC 
		LIMIT 1");
		$inventarioFinal = $usuario->extraer_registro($res2);
		$inventarioFinal = $inventarioFinal['total_transaccion'];
	//-----------------------------------------------------------------
	
	//-----------------OBTENGO INVENTARIO INICIAL---------------
		$res3 = $usuario->consulta("SELECT total_transaccion
		FROM ficha_inventario f, transaccion t
		WHERE t.id_finventario_transaccion = f.id_finventario
		AND f.descripcion =  'Inventario Inicial'
		AND f.id_producto_finventario =  '$idProducto'");
		$inventarioInicial = $usuario->extraer_registro($res3);
		$inventarioInicial = $inventarioInicial['total_transaccion'];
	//-------------------------------------------------------------
	
	//------------------OBTENGO LAS COMPRAS DE DICHO PRODUCTO---------
		$productName = "Compra ".$nombreProducto;
		$res4 = $usuario->consulta("SELECT SUM( m.monto_movimiento ) AS total, c.id_cuenta 
									FROM movimiento m, cuenta c
									WHERE m.id_cuenta_movimiento = c.id_cuenta
									AND c.nombre_cuenta LIKE  '$productName' ");
		$compra = $usuario->extraer_registro($res4);
		$compras = $compra['total'];
		$idCompras = $compra['id_cuenta'];
	//-------------------------------------------------------------------
	
	
	//------------------------CALCULATING...-------------------------------
		$inventarioInicial = (int) $inventarioInicial;
		$inventarioFinal = (int) $inventarioFinal;
		$compras = (int) $compras;
		
		$costoDeVenta = ($inventarioInicial + $compras - $inventarioFinal);
		if ($costoDeVenta >= 0) $column = 'd';
		else {
			$costoDeVenta = ($costoDeVenta * (-1));
			$column = 'h';
		}
	//----------------------------------------------------------------------
		
	
	
	//---ASEGURO QUE LA CUENTA INVENTARIO PRODUCTO 'X' NO EXISTA PA NO CREARLA TWICE----------
	$inventarioProducto = "Inventario ".$nombreProducto;
	
	$resAux2 = $usuario->consulta("SELECT * FROM cuenta WHERE nombre_cuenta = '$inventarioProducto' ");
	if (!($InventarioProductoX = $usuario->extraer_registro($resAux2)))
		$res5 = $usuario->consulta("INSERT INTO cuenta (id_cuenta, nombre_cuenta, tipo_cuenta) VALUES ('', '$inventarioProducto' , 'Activo')");
	//----------------------------------------------------------------------------------
	
	
	//----------------OBTENGO EL ID DE DICHA CUENTA----------------------------------
		$res7 = $usuario->consulta("SELECT * FROM cuenta WHERE nombre_cuenta = '$inventarioProducto'");
		$idCuentaInventario = $usuario->extraer_registro($res7);
		$idCuentaInventario = $idCuentaInventario['id_cuenta'];
	//-----------------------------------------------------------------------------	
		
		
		
	//-ASEGURO QUE LA CUENTA COSTO DE VENTA DE PRODUCTO 'X' NO EXISTA PA NO CREARLA TWICE-
		$costoVentaProducto = "Costo Venta ".$nombreProducto;
		
		$resAux2 = $usuario->consulta("SELECT * FROM cuenta WHERE nombre_cuenta = '$costoVentaProducto' ");
	if (!($CostoDeVentaDeProductoX = $usuario->extraer_registro($resAux2)))
		$res8 = $usuario->consulta("INSERT INTO cuenta (id_cuenta, nombre_cuenta, tipo_cuenta) VALUES ('', '$costoVentaProducto' , 'Egreso')");
	//-----------------------------------------------------------------------------
	
	
	
	//----------------OBTENGO EL ID DE DICHA CUENTA---------------------------------
		$res8 = $usuario->consulta("SELECT * FROM cuenta WHERE nombre_cuenta = '$costoVentaProducto'");
		$idCuentaCostoVenta = $usuario->extraer_registro($res8);
		$idCuentaCostoVenta = $idCuentaCostoVenta['id_cuenta'];
	//-------------------------------------------------------------------------------	
		
	
	
	//----INSERTO TODOS LOS MOVIMIENTOS Y LISTOOOOOO!--------------------------------
		$res9 = $usuario->consulta("INSERT INTO movimiento (id_cuenta_movimiento, id_ldiario_movimiento, monto_movimiento, columna_movimiento) VALUES ('$idCuentaInventario', '$ultimoAsiento', '$inventarioFinal', 'd')");
		$res9 = $usuario->consulta("INSERT INTO movimiento (id_cuenta_movimiento, id_ldiario_movimiento, monto_movimiento, columna_movimiento) VALUES ('$idCuentaCostoVenta', '$ultimoAsiento', '$costoDeVenta', '$column')");
		$res9 = $usuario->consulta("INSERT INTO movimiento (id_cuenta_movimiento, id_ldiario_movimiento, monto_movimiento, columna_movimiento) VALUES ('$idCompras', '$ultimoAsiento', '$compras', 'h')");
		$res9 = $usuario->consulta("INSERT INTO movimiento (id_cuenta_movimiento, id_ldiario_movimiento, monto_movimiento, columna_movimiento) VALUES ('$idCuentaInventario', '$ultimoAsiento', '$inventarioInicial', 'h')");
	//------------------------------------------------------------------------------	
	}
}
?>