<html>
<body>
<script type="text/javascript" src="js/prototype-1.6.0.3.js"> </script>
<script type="text/javascript" src="js/conta_JS.js"> </script>
<script type="text/javascript" src="contabilidad_js.js"> </script>

<?php 
$tipo = $_POST['opt'];
include ("conexion.php");
$res = $usuario->consulta("SELECT * FROM producto");
if ($tipo == '-'){
		echo "<br>Productos Disponibles: <select name='productos' id='productos' onchange='seleccionProducto()'>";
		echo"Productos Existentes: <option value='-'>---Seleccione una Opcion---</option>";	
		while($producto=$usuario->extraer_registro($res))
			echo"<option value=".$producto['nombre_producto'].">".$producto['nombre_producto']."</option>";		
}
else {
		echo '<form method ="post" action="ingresarMovimiento.php">
		  	<table width="200" border="0">
	  		<tr> 
				<td colspan="2" align="center"><strong>'.$tipo.'</strong></td> 
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