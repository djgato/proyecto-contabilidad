<?php 
include ("conexion.php");
$res = $usuario->consulta("SELECT * FROM producto");
$nuevo = "n";
$existente = "e";
echo "<br>Productos Disponibles: <select name='product' id='productos' onchange='seleccionProducto()'>";
echo"Productos Existentes: <option value='-'>---Seleccione una Opcion---</option>";	
while($producto=$usuario->extraer_registro($res)){
	echo"<option value=".$producto['id_producto'].">".$producto['nombre_producto']."</option>";		
}
echo"<option value='n'>---Producto Nuevo---</option>";
echo "</select>";
?>