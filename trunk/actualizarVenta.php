<?php 
$tipo = $_POST['opt'];
include ("conexion.php");
$res = $usuario->consulta("SELECT * FROM producto");
if ($tipo == '-'){
		echo "<br>Productos Disponibles: <select name='productos' id='productos' onchange='seleccionProducto_2()'>";
		echo"Productos Existentes: <option value='-'>---Seleccione una Opcion---</option>";	
		while($producto=$usuario->extraer_registro($res))
			echo"<option value=".$producto['nombre_producto'].">".$producto['nombre_producto']."</option>";		
}
else { echo '
		  	<table width="200" border="0">
			 <tr>
				<td align="center">Cant.<input type"text" name="cant"></td>
				<td align="center">CU<input type"text" name="CU"></td>
			  </tr> </table>
	  	      <br>Venta a credito?: <input name="credito" id="cre" type="checkbox" onchange="activarCred()"/> <div id="cred"></div>';
			
		echo '<input name="operacion" id="operacion" type="hidden" value="ventaProducto" />
			  <input name="nombreProd" id="nombreProd" type="hidden" value='.$tipo.' />
			  <input type="submit" name="Enviar" value="Enviar">';
}
?>