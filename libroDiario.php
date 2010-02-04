<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Contable-X</title>
<link href="css/estilo.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/prototype-1.6.0.3.js"> </script>
<script type="text/javascript" src="js/conta_JS.js"> </script>
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="shadow_left">&nbsp;</td>
    <td class="header_column">
	<table width="100%" border="0" cellspacing="10" cellpadding="0">
      <tr>
        <td height="80" align="center" class="logo_area"><br>Modelo Contable FoX C.A.</br></td>
      </tr>
    </table></td>
    <td class="shadow_right"></td>
  </tr>
  <tr>
    <td class="horizontal_column">&nbsp;</td>
    <td class="horizontal_center"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="linkcontainer">
      <tr>
        <td><div class="navigation"><a href="libroDiario.php" class="current">Libro de Diario</a></div></td>
        <td><div class="navigation"><a href="libroMayor.php" class="main_link">Libro Mayor</a></div></td>
        <td><div class="navigation"><a href="balanceGeneral.php" class="main_link">Balance General</a></div></td>
        <td><div class="navigation"><a href="fichaInventario.php" class="main_link">Ficha de Inventario</a></div></td>
        <td><div class="navigation"><a href="ingresarMovimiento.php" class="main_link">Ingresar Movimiento</a></div></td>
      </tr>
    </table></td>
    <td class="horizontal_column">&nbsp;</td>
  </tr>
  
  <tr>
    <td class="shadow_left">&nbsp;</td>
    <td class="main_content_box"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="body_content" align="left"><strong>

		<table width="100%" border="0">
		  <?php 
		        include ("conexion.php");
				include ("funciones.php");
                $res = $usuario->consulta("SELECT * FROM libro_diario");
				while($ldiario=$usuario->extraer_registro($res)){
					$fecha = cambiarFormatoFecha($ldiario["fecha_ldiario"]);
					echo "<tr>";
					echo "<td><U>".$fecha."</U></td><td align='center'><U>-".$ldiario["id_ldiario"]."-</U></td>";
					echo "</tr>";
					$datos = consultarMovimiento($ldiario["id_ldiario"],$usuario);
					while( list($posicion,$valor) = each($datos)){
					echo "<tr>";
					echo $valor;
					echo "</tr>";
					}
					echo "<tr><td COLSPAN=4><hr /></td></tr>";
				}
          /*
            <tr> <td> <input name="CalculoDep" type="button" value="Calculo de Depreciacion" onclick="consultaDepCierreMes()"/></td></tr>
            <tr> <td> <input name="CalculoDep" type="button" value="Calculo Costo de Venta" onclick="consultaCostoVentaCierreMes()"/></td></tr>
            <tr> <td> <input name="CalculoDep" type="button" value="Calculo Iva por Pagar" onclick="calculoIvaPorPagar()"/></td></tr>
          */?>
		  </table>
          <div id="depCierreMes"></div>
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
