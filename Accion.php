<html>
<body>
<script type="text/javascript" src="js/prototype-1.6.0.3.js"> </script>
<script type="text/javascript" src="js/conta_JS.js"> </script>
<script type="text/javascript" src="contabilidad_js.js"> </script>

<?php
include ("conexion.php");
$accion = $_GET["q"];

if ($accion =='compra'){
		   echo"<strong>Que desea Comprar? </strong>
				<select name='compra' id='compra' onchange='Compra(this.value)' >
				<option value='-' >---Seleccione una Opcion---</option>
				<option value='activo' >Activo</option>
				<option value='producto' >Producto</option>
            </select>";
				
}
else if ($accion == 'venta'){
		   echo"<strong>Que desea Comprar? </strong>
				<select name='compra' id='compra' onchange='Venta(this.value)' >
				<option value='-' >---Seleccione una Opcion---</option>
				<option value='activo' >Activo</option>
				<option value='producto' >Producto</option>
            </select>";
}
else if ($accion == 'prestamo'){
}
else if ($accion == 'cobro'){
}
else if ($accion == 'pago'){
}


?>

</body>
</html>
