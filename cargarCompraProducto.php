<?php 
$tipo = $_POST['op'];
echo '<form method ="post" action="procesarMovimiento.php">';
if ($tipo == 'n'){
	echo 'Nombre Del Nuevo Producto<br><input type="texto" name="new"><br>';
}
echo 'Cantidad: <input type"text" name="cant"><br>';
echo 'Costo Unitario: <input type"text" name="cu"><br>'; 
echo '<input type="submit" name="Enviar" value="Procesar">';
if ($tipo == 'n'){
echo '<input name="producto" id="producto" type="hidden" value="nuevo">';
}
else{
echo '<input name="producto" id="producto" type="hidden" value="existente">';	
	}
echo "<input name='operacion' id='operacion' type='hidden' value='compraProducto' />";
echo '</form>';
?>