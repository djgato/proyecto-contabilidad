<?php
session_start();
include("conexion.php");
$res = $usuario->consulta("SELECT id_cuenta FROM cuenta WHERE nombre_cuenta LIKE 'banco'");
$res = $usuario->extraer_registro($res);
$id_banco = $res['id_cuenta'];
$res = $usuario->consulta("SELECT * FROM movimiento WHERE id_cuenta_movimiento='$id_banco'");
$res = $usuario->extraer_registro($res);
if ($res){
	$_SESSION['iniciado']="iniciado";
	header("location: libroDiario.php");
	}
?>
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
        <td><div class="navigation"><a href="libroDiario.php" class="main_link">Libro de Diario</a></div></td>
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
        <td class="body_content" align="center"><strong>BIENVENIDO</strong> <br />
         El programa precisa valores bases para su optimo funcionameinto.<br />
         Por favor, introduzca el monto de banco con el cual desea iniciar su contabilidad:<br />
         <form action="datosIniciales.php" method="post">
         Monto: <input name="monto" id="monto" type="text" /> | <input name="enviar" type="submit" value="Aceptar" />
         <input name="id" id="id" type="hidden" value="<?php echo $id_banco?>"/>
         </form>
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
