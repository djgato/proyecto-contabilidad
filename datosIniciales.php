<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script type="text/javascript" src="js/prototype-1.6.0.3.js"> </script>
<script type="text/javascript" src="js/conta_JS.js"> </script>
</head>

<body onload="redireccionarLD()">
<?php 
include("conexion.php");
//creo el movimiento 1 de la empresa
$fecha ="2010-01-01";
$usuario->consulta("INSERT INTO libro_diario (id_ldiario, fecha_ldiario) VALUES ('','$fecha')");
$id = $usuario->extraer_registro($usuario->consulta("SELECT id_ldiario FROM libro_diario ORDER BY  id_ldiario DESC Limit 1"));
$id_ldiario = $id['id_ldiario'];

$res = $usuario->consulta("SELECT id_cuenta FROM cuenta WHERE nombre_cuenta LIKE 'capital'");
$res = $usuario->extraer_registro($res);
$id_capital = $res['id_cuenta'];
$monto = $_POST['monto']; 
$id_banco = $_POST['id'];

$usuario->consulta("INSERT INTO movimiento (id_cuenta_movimiento,id_ldiario_movimiento,monto_movimiento,columna_movimiento) VALUES 	('$id_banco','$id_ldiario','$monto','d');");
$usuario->consulta("INSERT INTO movimiento (id_cuenta_movimiento,id_ldiario_movimiento,monto_movimiento,columna_movimiento) VALUES 	('$id_capital','$id_ldiario','$monto','h');");
$_SESSION['iniciado']="iniciado";
?>
</body>
</html>