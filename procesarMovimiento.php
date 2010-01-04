<?php 
include ("conexion.php");
include ("funciones.php");
$operacion = $_POST['operacion'];
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
//creo el libro de diario para esta fecha!



}
else if ($operacion == "compraProducto"){
	
	
	}

?>