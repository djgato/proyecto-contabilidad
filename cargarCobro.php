<?php 
include ("conexion.php");
$suma_debe =0;
$suma_haber =0;
$id_cuenta = $_POST['id'];
$res =  $usuario->consulta("SELECT * FROM movimiento WHERE id_cuenta_movimiento = '$id_cuenta'");
while($mov = $usuario->extraer_registro($res)){
	if ($mov['columna_movimiento']=="d"){
		$suma_debe = $suma_debe + $mov['monto_movimiento'];
		}
	else {
		$suma_haber = $suma_haber + $mov['monto_movimiento'];
		}
}
$deuda_por_cobrar = $suma_debe - $suma_haber;
echo "Monto restante por cobrar: ".$deuda_por_cobrar."<br>";
echo 'Monto a Cobrar: <input type"text" name="cant"><br>';
echo '<input type="submit" name="Enviar" value="Procesar">';
echo "<input name='cuenta' id='cuenta' type='hidden' value='$id_cuenta' />";
echo "<input name='operacion' id='operacion' type='hidden' value='cobro' />";
?>