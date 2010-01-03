<html>
<body>
<script type="text/javascript" src="js/prototype-1.6.0.3.js"> </script>
<script type="text/javascript" src="js/conta_JS.js"> </script>
<script type="text/javascript" src="contabilidad_js.js"> </script>

<?php 
include ("conexion.php");
$accion = $_GET["q"];

if ($accion =='producto'){
		$res = $usuario->consulta("SELECT * FROM producto");
		while($producto=$usuario->extraer_registro($res))
		echo '<input name="producto" type="radio" value='.$producto["nombre_producto"].' onclick="Venta(this.value)">'.$producto["nombre_producto"];
}
else if($accion =='activo'){
	echo '<tr><td><strong>En construccion...Prueba con Producto ;)</strong></td></tr>';
}
else
{
		echo '<form method ="post" action="ingresarMovimiento.php">
		  	<table width="200" border="0">
	  		<tr> 
				<td colspan="2" align="center"><strong>'.$accion.'</strong></td> 
			 </tr>
			 <tr>
				<td align="center">Cant.<input type"text" name="cant"></td>
				<td align="center">CU<input type"text" name="CU"></td> 
			  </tr> </table>';
			
		echo '<input type="submit" name="Enviar" value="Enviar">';
		echo '</form>';
}
?>

</body>
</html>