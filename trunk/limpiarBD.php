<?php 
include ("conexion.php");
$usuario->consulta("DELETE FROM transaccion");
$usuario->consulta("ALTER TABLE transaccion AUTO_INCREMENT=1");
$usuario->consulta("DELETE FROM ficha_inventario");
$usuario->consulta("ALTER TABLE ficha_inventario AUTO_INCREMENT=1");
$usuario->consulta("DELETE FROM gasto_cuenta");
$usuario->consulta("ALTER TABLE gasto_cuenta AUTO_INCREMENT=1");
$usuario->consulta("DELETE FROM gasto_asociado");
$usuario->consulta("ALTER TABLE gasto_asociado AUTO_INCREMENT=1");
$usuario->consulta("DELETE FROM movimiento");
$usuario->consulta("ALTER TABLE movimiento AUTO_INCREMENT=1");
$usuario->consulta("DELETE FROM libro_diario");
$usuario->consulta("ALTER TABLE libro_diario AUTO_INCREMENT=1");
$usuario->consulta("DELETE FROM producto");
$usuario->consulta("ALTER TABLE producto AUTO_INCREMENT=1");
$usuario->consulta("DELETE FROM cuenta WHERE nombre_cuenta NOT LIKE 'banco' AND nombre_cuenta NOT LIKE 'capital'");
$usuario->consulta("ALTER TABLE cuenta AUTO_INCREMENT=3");

?>