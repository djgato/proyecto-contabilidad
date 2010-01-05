<?php 
include ("conexion.php");
include ("funciones.php");
$operacion = $_POST['operacion'];
echo '<tr><td>'.$operacion.'</td></tr>';
//creo el libro de diario para esta fecha!
$fecha = $_POST["year"]."-".$_POST["month"]."-".$_POST["day"];
$usuario->consulta("INSERT INTO libro_diario (id_ldiario, fecha_ldiario) VALUES ('','$fecha')");
$id = $usuario->extraer_registro($usuario->consulta("SELECT id_ldiario FROM libro_diario ORDER BY  id_ldiario DESC Limit 1"));
$id_ldiario = $id['id_ldiario'];
echo $id_ldiario;
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
	echo $credito;
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
		// Hago las asociaciones!
		//primero la de Dep Ac.
		$res = $usuario->consulta("INSERT INTO gasto_cuenta (columna_gasto,id_cuenta_gasto,id_gasto_asociado) VALUES 	('h','$id_cdepAc','$id_gastoAsociado');");
		//ahora con gasto pro dep 
		$res = $usuario->consulta("INSERT INTO gasto_cuenta (columna_gasto,id_cuenta_gasto,id_gasto_asociado) VALUES 	('d','$id_cgDep','$id_gastoAsociado');");	
		}
}
else if ($operacion == "compraProducto"){
	}
	
	
	
	
	
else if ($operacion == "ventaProducto"){
	$cant=$_POST['cant'];
	$cu=$_POST['CU'];
	$total = $cant*$cu;
	$iva= $total*(0.12);
	$nombreProd=$_POST['nombreProd'];
	$nombreCuenta='Venta '.$nombreProd;
	//inserto mi nueva cuenta Venta Producto ...
	$res = $usuario->consulta("INSERT INTO cuenta (id_cuenta,nombre_cuenta,tipo_cuenta) VALUES 	('','$nombreCuenta','Ingreso')");
	$res=$usuario->consulta("SELECT id_cuenta FROM cuenta WHERE nombre_cuenta='$nombreCuenta'");
	$res = $usuario->extraer_registro($res);
	$id_cuenta = $res['id_cuenta'];
	
	//inserto mi nueva cuenta IVA Venta ...
	$res= $usuario->consulta("SELECT id_cuenta FROM cuenta WHERE nombre_cuenta LIKE 'IVA Venta'");
	if (!($res)) {
		$usuario->consulta ("INSERT INTO cuenta (id_cuenta,nombre_cuenta,tipo_cuenta) VALUES ('','IVA Venta','Pasivo')");
	}
	else {	
		$res=$usuario->consulta("SELECT id_cuenta FROM cuenta WHERE nombre_cuenta='IVA Venta'");
		$res = $usuario->extraer_registro($res);
		$id_ivaVenta= $res['id_cuenta'];
		$usuario->consulta("INSERT INTO movimiento (id_cuenta_movimiento,id_ldiario_movimiento,monto_movimiento,columna_movimiento) VALUES 	('$id_ivaVenta','$id_ldiario','$iva','h');");
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
		echo $credito;
		$usuario->consulta("INSERT INTO movimiento (id_cuenta_movimiento,id_ldiario_movimiento,monto_movimiento,columna_movimiento) VALUES 		('$id_cuenta','$id_ldiario','$total','h');");
		if (!($credito)){ //la compra fue de contado	
			$usuario->consulta("INSERT INTO movimiento (id_cuenta_movimiento,id_ldiario_movimiento,monto_movimiento,columna_movimiento) VALUES 	('$id_banco','$id_ldiario','$monto','d');");
		}
		else { //compra a credito
			$por = $_POST['porcen'];
			$monto_credito = ($monto * $por)/100;
			$monto_banco = $monto - $monto_credito;
			
			//creo la cuenta "cuentas por cobrar"
			$res= $usuario->consulta("SELECT id_cuenta FROM cuenta WHERE nombre_cuenta LIKE 'Cuenta por Cobrar'");
			if (!($res)) {
				$res = $usuario->consulta("INSERT INTO cuenta (id_cuenta,nombre_cuenta,tipo_cuenta) VALUES('','Cuenta por Cobrar','Activo')");
			}
			else {	
				$res=$usuario->consulta("SELECT id_cuenta FROM cuenta WHERE nombre_cuenta='Cuenta por Cobrar'");
				$res = $usuario->extraer_registro($res);
				$id_porCobrar= $res['id_cuenta']; //id de la cuenta por cobrar!
				$usuario->consulta("INSERT INTO movimiento (id_cuenta_movimiento,id_ldiario_movimiento,monto_movimiento,columna_movimiento) VALUES 	('$id_banco','$id_ldiario','$monto_banco','d');");
			$usuario->consulta("INSERT INTO movimiento (id_cuenta_movimiento,id_ldiario_movimiento,monto_movimiento,columna_movimiento) VALUES 	('$id_porCobrar','$id_ldiario','$monto_credito','d');");
			}
		}
		//Inventario
		$res=$usuario->consulta("SELECT * from producto WHERE nombre_producto='$nombreProd'");
		$producto = $usuario->extraer_registro($res);
		$id_prod = $producto["id_producto"];
		$res =  $usuario->consulta("SELECT * 
									FROM ficha_inventario 
									WHERE id_producto_finventario= '$id_prod'");
		$ficha = $usuario->extraer_registro($res);
		$id_inv = $ficha["id_finventario"];		
		$res=$usuario->consulta("SELECT MAX(id_transaccion) 
								FROM transaccion 
								WHERE tipo_transaccion='existencia' and
								      id_finventario_transaccion= '$id_inv'");
		$ultima_existencia = $usuario->extraer_registro($res);
		$unidades = $ultima_existencia["unidades_transaccion"] - $cant;
		$precio_uitario = $ultima_existencia["precio_unidad"] - $cu;
		$total_transaccion = $unidades / $precio_unitario;
		
		$usuario->consulta ("INSERT INTO transaccion (id_transaccion,tipo_transaccion,unidades_transaccion,total_transaccion,precio_unidad,id_finventario_transaccion) VALUES ('','Salida','$cant','$total','$cu,'$id_inv'");
		$usuario->consulta ("INSERT INTO transaccion (id_transaccion,tipo_transaccion,unidades_transaccion,total_transaccion,precio_unidad,id_finventario_transaccion) VALUES ('','Existencia','$unidades','$total_transaccion','$precio_unitario,'$id_inv'");
		
}
?>