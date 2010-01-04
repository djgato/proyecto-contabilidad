<?php 
include ("conexion.php");
include ("funciones.php");
$operacion = $_POST['operacion'];
//creo el libro de diario para esta fecha!
$fecha = $_POST['year']."-".$_POST['month']."-".$_POST['day'];
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
	else {
		
		}

}
else if ($operacion == "compraProducto"){
	
	
	}

?>