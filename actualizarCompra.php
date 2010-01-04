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
		echo '<input name="producto" type="radio" value='.$producto["nombre_producto"].' 		onclick="Compra(this.value)">'.$producto["nombre_producto"];
		echo '<input name="producto" type="radio" value="Producto Nuevo" 
			onclick="Compra(this.value)">Agregar Producto'; 
}
else if($accion =='activo'){
	echo "
 <form action='procesarMovimiento.php' method='post'>
	 Nombre del activo: <input name='nombre' type='text'/> | Precio: <input name='precio' type='text' /> <br> 
	 Depreciable?: <input name='dep' id='dep' type='checkbox' onchange='activarDep()'/> <div id='depre'></div>
	 Compra a credio?: <input name='cre' id='cre' type='checkbox' onchange='activarCred()'/> <div id='cred'></div>
	 <input name='sub' type='submit' value='Procesar' />
	 <input name='operacion' id='operacion' type='hidden' value='compraActivo' />
 </form>";
}
else {
	echo '<form method ="post" action="compraProducto.php">
		  	<table width="200" border="0">';
	if ($accion == 'Producto Nuevo'){
		echo 'Nombre Del Nuevo Producto<br><input type="texto" name="new"><br>';	
	}
	else{
		echo 
	  		'<tr> 
				<td colspan="2" align="center"><strong>'.$accion.'</strong></td> 
			 </tr>';
	}
		echo '<tr>
				<td align="center">Cant.<input type"text" name="cant"></td>
				<td align="center">CU<input type"text" name="CU"></td> 
			  </tr> </table>';
			
		echo '<input type="submit" name="Enviar" value="Enviar">';
		echo '</form>';
}
?>

</body>
</html>