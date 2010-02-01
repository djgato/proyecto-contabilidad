<?php 
include ("funciones.php");
include ("conexion.php");
$imp = $_POST['imp'];

calcularISLR ($usuario, $imp);

?>