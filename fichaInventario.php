<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Contable-X</title>
<link href="css/estilo.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/prototype-1.6.0.3.js"> </script>
<script type="text/javascript" src="js/conta_JS.js"> </script>
<script type="text/javascript" src="contabilidad_js.js"> </script>
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="shadow_left">&nbsp;</td>
    <td class="header_column">
	<table width="100%" border="0" cellspacing="10" cellpadding="0">
      <tr>
        <td height="80" align="center" class="logo_area">Aqui ponemos un logo o un titulo</td>
      </tr>
    </table></td>
    <td class="shadow_right"></td>
  </tr>
  <tr>
    <td class="horizontal_column">&nbsp;</td>
    <td class="horizontal_center"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="linkcontainer">
      <tr>
        <td><div class="navigation"><a href="libroDiario.php" class="main_link">Libro de Diario</a></div></td>
        <td><div class="navigation"><a href="libroMayor.php" class="main_link">Libro Mayor</a></div></td>
        <td><div class="navigation"><a href="balanceGeneral.php" class="main_link">Balance General</a></div></td>
        <td><div class="navigation"><a href="fichaInventario.php" class="current">Ficha de Inventario</a></div></td>
        <td><div class="navigation"><a href="ingresarMovimiento.php" class="main_link">Ingresar Movimiento</a></div></td>
      </tr>
    </table></td>
    <td class="horizontal_column">&nbsp;</td>
  </tr>
  
  <tr>
    <td class="shadow_left">&nbsp;</td>
    <td class="main_content_box"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>


        <td class="body_content" align="center"><strong>Seleccione un Producto a Consultar<br><br>
	<table width="200" border="0">
    
    <select name="productos" size="1" onchange="Inventario(this.value)">
    <option selected="selected" value="null">---producto---</option>
		  <?php  

//----------------------------------------CODIGO INVENTARIO-----------------------------------------------

		    include ("conexion.php");
			$res = $usuario->consulta("SELECT * FROM producto");
				while($inventario=$usuario->extraer_registro($res))
				echo'<option value ='.$inventario["nombre_producto"].'>'.$inventario["nombre_producto"].'</option>';
			
//----------------------------------------CODIGO INVENTARIO-----------------------------------------------
		?>
        </select>
          </table>
		<div id="libroInventario"><b>Inventario</b></div>


	</strong> <br />
          <br /></td>
      </tr>
    </table></td>
    <td class="shadow_right">&nbsp;</td>
  </tr>
  <tr>
    <td class="shadow_left">&nbsp;</td>
    <td class="middle_spacer"></td>
    <td class="shadow_right">&nbsp;</td>
  </tr>
  <tr>
    <td class="shadow_left">&nbsp;</td>
    <td class="bottom_link_container"><p>All Right Reserved &copy; 2009 by NanoFox<br />
    </p></td>
    <td class="shadow_right">&nbsp;</td>
  </tr>
</table>
</body>
</html>
