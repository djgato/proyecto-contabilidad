<?php
include ("conexion.php");
include ("funciones.php");
$operacion = $_POST['operacion'];
//creo el libro de diario para esta fecha!
$fecha = $_POST['year']."-".$_POST['month']."-".$_POST['day'];
$usuario->consulta("INSERT INTO libro_diario (id_ldiario, fecha_ldiario) VALUES ('','$fecha')");
$id = $usuario->extraer_registro($usuario->consulta("SELECT id_ldiario FROM libro_diario ORDER BY  id_ldiario DESC Limit 1"));
$id_ldiario = $id['id_ldiario'];
if ($operacion == "compraActivo"){
//Si el carajo compro un activo entramos aqui ...
	$nomAc = $_POST['nombre'];
	$nombre = $_POST['nombre'];
	$cont = 2;
	$val = 0;
//Ahora valido que no haya una cuenta con el mismo nombre, si la hay le pongo un numero al lado ... ej vehiculo 2 o 3 dependiendo la cantidad	
	while ($val == 0){
		$res = $usuario->consulta("SELECT nombre_cuenta FROM cuenta");
		while($nom = $usuario->extraer_registro($res)){
			$no = $nom['nombre_cuenta'];
			if ($no == $nombre){
				$nombre = $nomAc." ".$cont;
				$cont = $cont + 1;
				$val = - 1;
				}		
		}
		$val = $val + 1;
	}
//inserto mi nueva cuenta ...
	$res = $usuario->consulta("INSERT INTO cuenta (id_cuenta,nombre_cuenta,tipo_cuenta) VALUES 	('','$nombre','Activo');");
	$res = $usuario->consulta("SELECT id_cuenta FROM cuenta WHERE nombre_cuenta = '$nombre'");
	$res = $usuario->extraer_registro($res);
	$id_cuenta = $res['id_cuenta'];
//saco el id del banco
	$res = $usuario->consulta("SELECT id_cuenta FROM cuenta WHERE nombre_cuenta LIKE 'banco'");
	$res = $usuario->extraer_registro($res);
	$id_banco = $res['id_cuenta'];
//Saco el monto
    $monto = $_POST['precio'];
//Veo si es a credito o de contado
	$credito = false;
	if (isset($_POST['credito']))
	$credito = true;
	$usuario->consulta("INSERT INTO movimiento (id_cuenta_movimiento,id_ldiario_movimiento,monto_movimiento,columna_movimiento) VALUES 		('$id_cuenta','$id_ldiario','$monto','d');");
	if (!($credito)){ //la compra fue de contado	
		$usuario->consulta("INSERT INTO movimiento (id_cuenta_movimiento,id_ldiario_movimiento,monto_movimiento,columna_movimiento) VALUES 	('$id_banco','$id_ldiario','$monto','h');");
	}
	else { //compra a credito
		$por = $_POST['porcen'];
		$monto_credito = ($monto * $por)/100;
		$monto_banco = $monto - $monto_credito;
		//creo la cuenta "otras cuentas por pagar"
		$nc_porpagar = "Otras Cuentas Por Pagar (".$nombre.")";
		$res = $usuario->consulta("INSERT INTO cuenta (id_cuenta,nombre_cuenta,tipo_cuenta) VALUES 	('','$nc_porpagar','Pasivo');");
		$res = $usuario->consulta("SELECT id_cuenta FROM cuenta WHERE nombre_cuenta = '$nc_porpagar'");
		$res = $usuario->extraer_registro($res);
		$id_cporpagar = $res['id_cuenta']; //id de la cuenta por pagar!
		$usuario->consulta("INSERT INTO movimiento (id_cuenta_movimiento,id_ldiario_movimiento,monto_movimiento,columna_movimiento) VALUES 	('$id_banco','$id_ldiario','$monto_banco','h');");
		$usuario->consulta("INSERT INTO movimiento (id_cuenta_movimiento,id_ldiario_movimiento,monto_movimiento,columna_movimiento) VALUES 	('$id_cporpagar','$id_ldiario','$monto_credito','h');");
		}
// veo si es depreciable o no depreciable
	$depre = false;
	if (isset($_POST['dep']))
	$depre = true;
	if ($depre){
		$tiempo_dep_men = $_POST['tiempo'] * 12;
		$dep_mensual = $monto/$tiempo_dep_men;
		//creo la cuenta de dep ac
		$nc_depAc = "Dep. Ac. ".$nombre;
		$res = $usuario->consulta("INSERT INTO cuenta (id_cuenta,nombre_cuenta,tipo_cuenta) VALUES 	('','$nc_depAc','Activo');");
		$res = $usuario->consulta("SELECT id_cuenta FROM cuenta WHERE nombre_cuenta = '$nc_depAc'");
		$res = $usuario->extraer_registro($res);
		$id_cdepAc = $res['id_cuenta']; //id de la cuenta de dep ac!
		//creo la cuenta gastos por depreciacion
		$nc_gDep = "Gastos por Dep. de ".$nombre;
		$res = $usuario->consulta("INSERT INTO cuenta (id_cuenta,nombre_cuenta,tipo_cuenta) VALUES 	('','$nc_gDep','Egreso');");
		$res = $usuario->consulta("SELECT id_cuenta FROM cuenta WHERE nombre_cuenta = '$nc_gDep'");
		$res = $usuario->extraer_registro($res);
		$id_cgDep = $res['id_cuenta'];
		//genero el gasto asociado
		$res = $usuario->consulta("INSERT INTO gasto_asociado (id_gasto_asociado,monto_gasto,pagos_restantes) VALUES 	('','$dep_mensual','$tiempo_dep_men');");
		$id_gastoAsociado =$usuario->extraer_registro($usuario->consulta("SELECT id_gasto_asociado FROM gasto_asociado ORDER BY  id_gasto_asociado DESC Limit 1"));
		$id_gastoAsociado = $id_gastoAsociado['id_gasto_asociado'];
		// Hago las asociaciones!
		//primero la de Dep Ac.
		$res = $usuario->consulta("INSERT INTO gasto_cuenta (columna_gasto,id_cuenta_gasto,id_gasto_asociado) VALUES 	('h','$id_cdepAc','$id_gastoAsociado');");
		//ahora con gasto pro dep 
		$res = $usuario->consulta("INSERT INTO gasto_cuenta (columna_gasto,id_cuenta_gasto,id_gasto_asociado) VALUES 	('d','$id_cgDep','$id_gastoAsociado');");	
		}
}
else if ($operacion == "compraProducto"){
	$id_producto = 0;
	$nombre_prod = "";
	$nom_compra = "";
	$id_iva =0;
	if ($_POST['product'] == "n"){
		//si es producto nuevo lo creo
		$nombre_prod = $_POST['new'];
		$res = $usuario->consulta("INSERT INTO producto (id_producto,nombre_producto) VALUES  ('','$nombre_prod');");
		$res = $usuario->consulta("SELECT id_producto FROM producto WHERE nombre_producto = '$nombre_prod'");
		$res = $usuario->extraer_registro($res);
		$id_producto = $res['id_producto'];
		//Creo la cuenta Compra del producto
		$nom_compra = "Compra ".$nombre_prod;
		$res = $usuario->consulta("INSERT INTO cuenta (id_cuenta,nombre_cuenta,tipo_cuenta) VALUES 	('','$nom_compra','Egreso');");		
		}
		
	else{
		//si el producto ya existe tomo su id
		$id_producto = $_POST['product'];
		$nombre_prod = $usuario->extraer_registro($usuario->consulta("SELECT nombre_producto FROM producto WHERE id_producto = '$id_producto'"));
		$nombre_prod = $nombre_prod['nombre_producto'];
		//busco la cuenta Compra de ese producto
		$nom_compra = "Compra ".$nombre_prod;
		}
	//saco el id de la cuetna Compra		
	$res = $usuario->consulta("SELECT id_cuenta FROM cuenta WHERE nombre_cuenta LIKE '$nom_compra'");
	$res = $usuario->extraer_registro($res);
	$id_ccompra = $res['id_cuenta']; //id de la cuentaCompra!
	//saco el id del banco
	$res = $usuario->consulta("SELECT id_cuenta FROM cuenta WHERE nombre_cuenta LIKE 'banco'");
	$res = $usuario->extraer_registro($res);
	$id_banco = $res['id_cuenta'];
	//saco id de IVA de Compra si existe y si no la creo ...
	$nombre_iva = "IVA de Compra";
	$res = $usuario->consulta("SELECT id_cuenta FROM cuenta WHERE nombre_cuenta LIKE 'IVA de Compra'");
	$res = $usuario->extraer_registro($res);
	if ($res){
	$id_iva = $res['id_cuenta'];
	}
	else{	
		$res = $usuario->consulta("INSERT INTO cuenta (id_cuenta,nombre_cuenta,tipo_cuenta) VALUES 	('','$nombre_iva','Activo');");
		$res = $usuario->consulta("SELECT id_cuenta FROM cuenta WHERE nombre_cuenta LIKE '$nombre_iva'");
		$res = $usuario->extraer_registro($res);
		$id_iva = $res['id_cuenta'];
		}
	//Reviso la parte de la compra si fue a credito o de contado
	$credito = false;
	if (isset($_POST['credito']))
	$credito = true;
	$monto = $_POST['cant'] * $_POST['cu'];
	$monto_iva = ($monto * 12)/100;
	$monto_banco = $monto + $monto_iva;
	$usuario->consulta("INSERT INTO movimiento (id_cuenta_movimiento,id_ldiario_movimiento,monto_movimiento,columna_movimiento) VALUES 		('$id_ccompra','$id_ldiario','$monto','d');");
	$usuario->consulta("INSERT INTO movimiento (id_cuenta_movimiento,id_ldiario_movimiento,monto_movimiento,columna_movimiento) VALUES 		('$id_iva','$id_ldiario','$monto_iva','d');");
	if (!($credito)){ //la compra fue de contado	
		$usuario->consulta("INSERT INTO movimiento (id_cuenta_movimiento,id_ldiario_movimiento,monto_movimiento,columna_movimiento) VALUES 	('$id_banco','$id_ldiario','$monto_banco','h');");
	}
	else { //compra a credito
		$por = $_POST['porcen'];
		$id_cporpagar =0;
		$monto_credito = ($monto_banco * $por)/100;
		$monto_banco = $monto_banco - $monto_credito;
		//creo la cuenta "cuentas por pagar" si es que no existe
		$nomporpagar = "Cuentas Por Pagar";
		$res = $usuario->consulta("SELECT id_cuenta FROM cuenta WHERE nombre_cuenta = '$nomporpagar'");
		$res = $usuario->extraer_registro($res);
		if ($res){
			$id_cporpagar = $res['id_cuenta'];
		}
		else{	
		$usuario->consulta("INSERT INTO cuenta (id_cuenta,nombre_cuenta,tipo_cuenta) VALUES ('','$nomporpagar','Pasivo');");
		$res = $usuario->consulta("SELECT id_cuenta FROM cuenta WHERE nombre_cuenta = '$nomporpagar'");
		$res = $usuario->extraer_registro($res);
		echo $res;
		$id_cporpagar = $res['id_cuenta']; //id de la cuenta por pagar!	
		echo $id_cporpagar."Aqui la cuenta no existe";
	}

		$usuario->consulta("INSERT INTO movimiento (id_cuenta_movimiento,id_ldiario_movimiento,monto_movimiento,columna_movimiento) VALUES 	('$id_banco','$id_ldiario','$monto_banco','h');");
		$usuario->consulta("INSERT INTO movimiento (id_cuenta_movimiento,id_ldiario_movimiento,monto_movimiento,columna_movimiento) VALUES 	('$id_cporpagar','$id_ldiario','$monto_credito','h');");
		}
		
	//---------------------------------------------------------------------------------------------------------------------------------------------------------	
	//Ahora paso a registrar la compra en la ficha de inventario!
	//creo la ficha de inventario
	$usuario->consulta("INSERT INTO ficha_inventario (id_finventario,descripcion,fecha_inventario,id_producto_finventario) VALUES 	('','Compra','$fecha','$id_producto');");
	$id_finventario = $usuario->extraer_registro($usuario->consulta("SELECT id_finventario FROM ficha_inventario ORDER BY  id_finventario DESC Limit 1"));
	$id_finventario = $id_finventario['id_finventario'];
	$exist = $usuario->consulta("SELECT total_transaccion,unidades_transaccion FROM transaccion WHERE tipo_transaccion = 'Existencia' ORDER BY id_transaccion DESC Limit 1");
	$unidades = $_POST['cant'];
	$costo_unitario = $_POST['cu'];
	$total = $_POST['cant'] * $_POST['cu'];
	//ingreso la transaccion de compra
	if ($_POST['product'] == "n"){//si el producto es nuevo le creo II
			$usuario->consulta("INSERT INTO ficha_inventario (id_finventario,descripcion,fecha_inventario,id_producto_finventario) VALUES 	('','Existencia','$fecha','$id_producto');");
	$id_finventario2 = $usuario->extraer_registro($usuario->consulta("SELECT id_finventario FROM ficha_inventario ORDER BY  id_finventario DESC Limit 1"));
	$id_finventario2 = $id_finventario2['id_finventario'];
		$usuario->consulta("INSERT INTO transaccion (id_transaccion,id_finventario_transaccion,tipo_transaccion,unidades_transaccion,total_transaccion,precio_unidad) VALUES 	('','$id_finventario2','II','0','0','0');");
		}
	$usuario->consulta("INSERT INTO transaccion (id_transaccion,id_finventario_transaccion,tipo_transaccion,unidades_transaccion,total_transaccion,precio_unidad) VALUES 	('','$id_finventario','Compra','$unidades','$total','$costo_unitario');");
	if ($exist){
		$exist = $usuario->extraer_registro($exist);
		$unidades = $unidades + $exist['unidades_transaccion'];
		$total = $total + $exist['total_transaccion'];
		$costo_unitario = $total/$unidades;
		$costo_unitario = redondeado($costo_unitario,3);
		}
		$usuario->consulta("INSERT INTO transaccion (id_transaccion,id_finventario_transaccion,tipo_transaccion,unidades_transaccion,total_transaccion,precio_unidad) VALUES 	('','$id_finventario','Existencia','$unidades','$total','$costo_unitario');");
	}
else if ($operacion == "solicitudPrestamo"){
	$monto = $_POST['monto'];
	$intereses = $_POST['intereses'];
	$mensualidad = $_POST['pagos'];
	$intereses = ($intereses * $monto)/100; //calculo cual va a ser el interes mensual
	$num_pagos = $monto/$mensualidad;
	$id_iteres = 0;
	$id_ppp = 0;
	//creo o verifico existencia de cuenta intereses gastos sobre prestamos
	$nom_intereses = "Intereses Gastos S/P";
	$res = $usuario->consulta("SELECT id_cuenta FROM cuenta WHERE nombre_cuenta LIKE '$nom_intereses'");
	$res = $usuario->extraer_registro($res);
	if ($res){
	$id_iteres = $res['id_cuenta'];
	}
	else{	
		$res = $usuario->consulta("INSERT INTO cuenta (id_cuenta,nombre_cuenta,tipo_cuenta) VALUES 	('','$nom_intereses','Egreso');");
		$res = $usuario->consulta("SELECT id_cuenta FROM cuenta WHERE nombre_cuenta LIKE '$nom_intereses'");
		$res = $usuario->extraer_registro($res);
		$id_iteres = $res['id_cuenta'];
		}
	//genero el gasto asociado
		$res = $usuario->consulta("INSERT INTO gasto_asociado (id_gasto_asociado,monto_gasto,pagos_restantes) VALUES 	('','$intereses','$num_pagos');");
		$id_gastoAsociado =$usuario->extraer_registro($usuario->consulta("SELECT id_gasto_asociado FROM gasto_asociado ORDER BY  id_gasto_asociado DESC Limit 1"));
		$id_gastoAsociado = $id_gastoAsociado['id_gasto_asociado'];
		// Hago las asociaciones!
		$res = $usuario->consulta("INSERT INTO gasto_cuenta (columna_gasto,id_cuenta_gasto,id_gasto_asociado) VALUES 	('d','$id_iteres','$id_gastoAsociado');");
		//Creo la cuenta Prestamos por pagar 
		$nom_prest = "Prestamos por Pagar";
	$res = $usuario->consulta("SELECT id_cuenta FROM cuenta WHERE nombre_cuenta LIKE '$nom_prest'");
	$res = $usuario->extraer_registro($res);
	if ($res){
	$id_ppp = $res['id_cuenta'];
	}
	else{	
		$res = $usuario->consulta("INSERT INTO cuenta (id_cuenta,nombre_cuenta,tipo_cuenta) VALUES 	('','$nom_prest','Pasivo');");
		$res = $usuario->consulta("SELECT id_cuenta FROM cuenta WHERE nombre_cuenta LIKE '$nom_prest'");
		$res = $usuario->extraer_registro($res);
		$id_ppp = $res['id_cuenta'];
		}
	//genero el gasto asociado
	$res = $usuario->consulta("INSERT INTO gasto_asociado (id_gasto_asociado,monto_gasto,pagos_restantes) VALUES 	('','$mensualidad','$num_pagos');");
	$id_gastoAsociado =$usuario->extraer_registro($usuario->consulta("SELECT id_gasto_asociado FROM gasto_asociado ORDER BY  id_gasto_asociado DESC Limit 1"));
	$id_gastoAsociado = $id_gastoAsociado['id_gasto_asociado'];
	// Hago las asociaciones!
	$res = $usuario->consulta("INSERT INTO gasto_cuenta (columna_gasto,id_cuenta_gasto,id_gasto_asociado) VALUES 	('d','$id_ppp','$id_gastoAsociado');");
	//saco el id del banco
	$res = $usuario->consulta("SELECT id_cuenta FROM cuenta WHERE nombre_cuenta LIKE 'banco'");
	$res = $usuario->extraer_registro($res);
	$id_banco = $res['id_cuenta'];
	//ingreso los movimientos del banco y del PPP
	$usuario->consulta("INSERT INTO movimiento (id_cuenta_movimiento,id_ldiario_movimiento,monto_movimiento,columna_movimiento) VALUES 	('$id_banco','$id_ldiario','$monto','d');");
	$usuario->consulta("INSERT INTO movimiento (id_cuenta_movimiento,id_ldiario_movimiento,monto_movimiento,columna_movimiento) VALUES 	('$id_ppp','$id_ldiario','$monto','h');");
	}
	else if ($operacion == "cobro"){
		//saco el id del banco
		$res = $usuario->consulta("SELECT id_cuenta FROM cuenta WHERE nombre_cuenta LIKE 'banco'");
		$res = $usuario->extraer_registro($res);
		$id_banco = $res['id_cuenta'];
		$monto = $_POST['cant'];//saco el monto a cobrar
		$id_cuenta = $_POST['cuenta']; //el id de la cuenta que estoy cobrando
		//genero los movimientos!
		$usuario->consulta("INSERT INTO movimiento (id_cuenta_movimiento,id_ldiario_movimiento,monto_movimiento,columna_movimiento) VALUES 	('$id_banco','$id_ldiario','$monto','d');");
	$usuario->consulta("INSERT INTO movimiento (id_cuenta_movimiento,id_ldiario_movimiento,monto_movimiento,columna_movimiento) VALUES 	('$id_cuenta','$id_ldiario','$monto','h');");
		}
	else if ($operacion == "pago"){
		//saco el id del banco
	$res = $usuario->consulta("SELECT id_cuenta FROM cuenta WHERE nombre_cuenta LIKE 'banco'");
	$res = $usuario->extraer_registro($res);
	$id_banco = $res['id_cuenta'];
	$monto = $_POST['cant'];//saco el monto a cobrar
	$id_cuenta = $_POST['cuenta']; //el id de la cuenta que estoy cobrando
	//genero los movimientos!
	$usuario->consulta("INSERT INTO movimiento (id_cuenta_movimiento,id_ldiario_movimiento,monto_movimiento,columna_movimiento) VALUES 	('$id_banco','$id_ldiario','$monto','h');");
	$usuario->consulta("INSERT INTO movimiento (id_cuenta_movimiento,id_ldiario_movimiento,monto_movimiento,columna_movimiento) VALUES 	('$id_cuenta','$id_ldiario','$monto','d');");		
		}	
else if ($operacion == "ventaProducto"){
	$cant=$_POST['cant'];
	$cu=$_POST['CU'];
	$total = $cant*$cu;
	$iva= $total*(0.12);
	$nombreProd=$_POST['nombreProd'];
	$nombreCuenta='Venta '.$nombreProd;
	//inserto mi nueva cuenta Venta Producto ...
	$res=$usuario->consulta("SELECT id_cuenta FROM cuenta WHERE nombre_cuenta LIKE '$nombreCuenta'");
	$res = $usuario->extraer_registro($res);
	if (!($res)){
	$res = $usuario->consulta("INSERT INTO cuenta (id_cuenta,nombre_cuenta,tipo_cuenta) VALUES ('','$nombreCuenta','Ingreso')");
	$res=$usuario->consulta("SELECT id_cuenta FROM cuenta WHERE nombre_cuenta LIKE '$nombreCuenta'");
	$res = $usuario->extraer_registro($res);
	}
	$id_cuenta = $res['id_cuenta'];
	
	//inserto mi nueva cuenta IVA Venta ...
	$res= $usuario->consulta("SELECT id_cuenta FROM cuenta WHERE nombre_cuenta LIKE 'IVA Venta'");
	$res= $usuario->extraer_registro($res);
	if (!($res)) {
		$usuario->consulta ("INSERT INTO cuenta (id_cuenta,nombre_cuenta,tipo_cuenta) VALUES ('','IVA Venta','Pasivo')");
		$res=$usuario->consulta("SELECT id_cuenta FROM cuenta WHERE nombre_cuenta='IVA Venta'");
		$res = $usuario->extraer_registro($res);
		$id_ivaVenta= $res['id_cuenta'];
		}
	else {	
		$id_ivaVenta= $res['id_cuenta'];
	}
	
	//saco el id del banco
	$res =$usuario->consulta("SELECT id_cuenta FROM cuenta WHERE nombre_cuenta LIKE 'banco'");
	$res = $usuario->extraer_registro($res);
	$id_banco = $res['id_cuenta'];
	//Saco el monto
	$monto = $total + $iva;
	//Veo si es a credito o de contado
	$credito = false;
	if (isset($_POST['credito']))
		$credito = true;
	$usuario->consulta("INSERT INTO movimiento (id_cuenta_movimiento,id_ldiario_movimiento,monto_movimiento,columna_movimiento) VALUES ('$id_cuenta','$id_ldiario','$total','h');");
	$usuario->consulta("INSERT INTO movimiento (id_cuenta_movimiento,id_ldiario_movimiento,monto_movimiento,columna_movimiento) VALUES ('$id_ivaVenta','$id_ldiario','$iva','h');");
	if (!($credito)){ //la compra fue de contado	
		$usuario->consulta("INSERT INTO movimiento (id_cuenta_movimiento,id_ldiario_movimiento,monto_movimiento,columna_movimiento) VALUES ('$id_banco','$id_ldiario','$monto','d');");
	}
	else { //compra a credito
		$por = $_POST['porcen'];
		$monto_credito = ($monto * $por)/100;
		$monto_banco = $monto - $monto_credito;
	
	//creo la cuenta "cuentas por cobrar"
		$res= $usuario->consulta("SELECT id_cuenta FROM cuenta WHERE nombre_cuenta LIKE 'Cuenta por Cobrar'");
		$res = $usuario->extraer_registro($res);
		if (!($res)) {
			$res = $usuario->consulta("INSERT INTO cuenta (id_cuenta,nombre_cuenta,tipo_cuenta) VALUES('','Cuenta por Cobrar','Activo')");
			$res=$usuario->consulta("SELECT id_cuenta FROM cuenta WHERE nombre_cuenta LIKE 'Cuenta por Cobrar'");
			$res = $usuario->extraer_registro($res);
			$id_porCobrar = $res['id_cuenta'];
		}		
		$id_porCobrar = $res['id_cuenta']; //id de la cuenta por cobrar!	
		$usuario->consulta("INSERT INTO movimiento (id_cuenta_movimiento,id_ldiario_movimiento,monto_movimiento,columna_movimiento) VALUES ('$id_banco','$id_ldiario','$monto_banco','d');");
			$usuario->consulta("INSERT INTO movimiento (id_cuenta_movimiento,id_ldiario_movimiento,monto_movimiento,columna_movimiento) VALUES ('$id_porCobrar','$id_ldiario','$monto_credito','d');");
	}
	//Inventario
	$res=$usuario->consulta("SELECT * from producto WHERE nombre_producto='$nombreProd'");
	$producto = $usuario->extraer_registro($res);
	$id_prod = $producto["id_producto"];
	$res =  $usuario->consulta("SELECT * FROM ficha_inventario WHERE id_producto_finventario= '$id_prod'");
	$id_inv =$usuario->extraer_registro($usuario->consulta("SELECT id_finventario FROM ficha_inventario ORDER BY  id_finventario DESC Limit 1"));
	$id_inv = $id_inv['id_finventario'];
	$ultima_existencia = $usuario->extraer_registro($usuario->consulta("SELECT * FROM transaccion WHERE tipo_transaccion='Existencia' AND id_finventario_transaccion= '$id_inv' ORDER BY  id_transaccion DESC Limit 1"));
	$unidades_restantes = $ultima_existencia["unidades_transaccion"] - $cant; //cantidad en existencia
	$precio_unitario = $ultima_existencia["precio_unidad"]; //costo tanto para la salida como para la existencia
	$total_transaccion = $cant * $precio_unitario; // total de la salida
	$total_exist = $unidades_restantes * $precio_unitario; //total de la existencia
	$usuario->consulta("INSERT INTO ficha_inventario (id_finventario,descripcion,fecha_inventario,id_producto_finventario) VALUES 	('','Venta','$fecha','$id_prod');");
	$id_finventario = $usuario->extraer_registro($usuario->consulta("SELECT id_finventario FROM ficha_inventario ORDER BY  id_finventario DESC Limit 1"));
	$id_finventario = $id_finventario['id_finventario'];
	$usuario->consulta ("INSERT INTO transaccion (id_transaccion,tipo_transaccion,unidades_transaccion,total_transaccion,precio_unidad,id_finventario_transaccion) VALUES ('','Salida','$cant','$total_transaccion','$precio_unitario','$id_finventario'");
	$usuario->consulta ("INSERT INTO transaccion (id_transaccion,tipo_transaccion,unidades_transaccion,total_transaccion,precio_unidad,id_finventario_transaccion) VALUES ('','Existencia','$unidades_restantes','$total_exist','$precio_unitario,'$id_finventario'");
	}
?>