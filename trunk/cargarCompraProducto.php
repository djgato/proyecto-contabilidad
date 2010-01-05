<?php 
$tipo = $_POST['op'];
if ($tipo == 'n'){
	echo 'Nombre Del Nuevo Producto: <input type="text" name="new"><br>';
}
echo 'Cantidad: <input type"text" name="cant"><br>';
echo 'Costo Unitario: <input type"text" name="cu"><br>'; 
echo "Compra a credio?: <input name='credito' id='cre' type='checkbox' onchange='activarCred()'/> <div id='cred'></div>";
echo '<input type="submit" name="Enviar" value="Procesar">';
if ($tipo == 'n'){
echo '<input name="producto" id="producto" type="hidden" value="nuevo">';
}
else{
echo '<input name="producto" id="producto" type="hidden" value="existente">';	
	}
echo "<input name='operacion' id='operacion' type='hidden' value='compraProducto' />";
?>