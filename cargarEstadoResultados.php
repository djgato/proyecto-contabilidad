<div align="right"><a href="#" onclick="cargarBalanceGeneral()">Balance General</a></div>

<?php 
include ("conexion.php");
include ("funciones.php");
if (isset($_COOKIE['imp'])){
$porcentaje = $_COOKIE['imp'];
$usuario2 = new Servidor_Base_Datos($servidor,"root",$pass,$base_datos);
echo "<table>";
$res = $usuario->consulta("SELECT * FROM cuenta WHERE tipo_cuenta = 'Ingreso'");
	$resultado = 0;
	$resultado2 = 0;
	$resultado3 = 0;
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
				$debe[]= 0;
				}
			else {
				$haber[]= $mov["monto_movimiento"];
				}
		}
		$total = 0;
		$total = sumarColumnas ($debe,$haber,'Pasivo');
		$resultado = $resultado + $total;
	}
	echo "<tr>";
	echo "<td>Ventas </td><td></td><td>".$resultado."</td>";
	echo "</tr>";
	$res = $usuario->consulta("SELECT * FROM cuenta WHERE tipo_cuenta = 'Egreso' AND nombre_cuenta LIKE 'Costo Venta%'");
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
				$haber[]= 0;
				}
		}
		$total = 0;
		$total = sumarColumnas ($debe,$haber,'Activo');
		$resultado2 = $resultado2 + $total;
		$resultado = $resultado - $total;
	}
	echo"<tr>";
	echo "<td>Costo de Venta </td><td></td><td>(".$resultado2.")</td>";
	echo"</tr><tr>";
	echo "<td>Utilidad Bruta en Venta </td><td></td><td>".$resultado."</td>";
	echo "</tr><tr>";
	echo "<td>Gastos Ventas y Administrativos</td><td></td><td></td>";
	echo "</tr>";
	$res = $usuario->consulta("SELECT * FROM cuenta WHERE tipo_cuenta = 'Egreso' AND nombre_cuenta NOT LIKE 'Costo Venta%'  AND nombre_cuenta NOT LIKE 'ISLR'  AND nombre_cuenta NOT LIKE 'Compra%'");
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
				$haber[]= 0;
				}
		}
		$total = 0;
		$total = sumarColumnas ($debe,$haber,'Activo');
		$resultado3 = $resultado3 + $total;
		$resultado = $resultado - $total;
		echo "<tr><td>".$nombre_cuenta."</td><td>".$total."</td><td></td></tr>";
	}
	echo "<tr><td></td><td></td><td>(".$resultado3.")</td></tr>";
	echo "<tr><td>Utilidad neta antes ISLR </td><td></td><td>".$resultado."</td></tr>";
	$porcentaje2 = (int)$porcentaje;
	$porcentaje2 = ($porcentaje2/100);
	$resultado4 = $resultado * $porcentaje2;
	$resultado5 = (int)$resultado4 ;
	$isl = $resultado - $resultado4;
	$isl = (int)$isl;
	echo "<tr><td>ISLR ".$porcentaje." %</td><td></td><td>(".$resultado4.")</td></tr>";
	echo "<tr><td>Utilidad neta despues ISLR </td><td></td><td>".$isl."</td></tr>";
	echo "</table>";
				   }
?>