<?php 
include ("conexion.php");
$usuario->consulta("DELETE FROM transaccion");
$usuario->consulta("DELETE FROM ficha_inventario");
$usuario->consulta("DELETE FROM gasto_cuenta");
$usuario->consulta("DELETE FROM gasto_asociado");
$usuario->consulta("DELETE FROM movimiento");
$usuario->consulta("DELETE FROM libro_diario");
$usuario->consulta("DELETE FROM producto");
$usuario->consulta("DELETE FROM cuenta WHERE nombre_cuenta NOT LIKE 'banco' AND nombre_cuenta NOT LIKE 'capital'");
?>