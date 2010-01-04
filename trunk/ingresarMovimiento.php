<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Contable-X</title>
<link href="css/estilo.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/prototype-1.6.0.3.js"> </script>
<script type="text/javascript" src="js/conta_JS.js"> </script>
<script type="text/javascript" src="contabilidad_js.js"> </script>
<SCRIPT type="text/javascript" src="js/dhtmlgoodies_calendar.js?random=20060118"></script>
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
        <td><div class="navigation"><a href="fichaInventario.php" class="main_link">Ficha de Inventario</a></div></td>
        <td><div class="navigation"><a href="ingresarMovimiento.php" class="current">Ingresar Movimiento</a></div></td>
      </tr>
    </table></td>
    <td class="horizontal_column">&nbsp;</td>
  </tr>
  
  <tr>
    <td class="shadow_left">&nbsp;</td>
    <td class="main_content_box"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="body_content" align="left">
        <form>
        <table>
		<tr><td><strong>Fecha del movimiento: </strong></td><td><td>
				<select name="year">
					<option value="1999">1999</option>
					<option value="2000">2000</option>
					<option value="2001">2001</option>
					<option value="2002">2002</option>
					<option value="2003">2003</option>
					<option value="2004">2004</option>
					<option value="2005">2005</option>
					<option value="2006">2006</option>
					<option value="2007" selected>2007</option>
					<option value="2008">2008</option>
					<option value="2009">2009</option>
					<option value="2010">2010</option>
					<option value="2011">2011</option>
					<option value="2012">2012</option>
					<option value="2013">2013</option>
					<option value="2014">2014</option>
					<option value="2015">2015</option>
				</select> / 
				<select name="month">
					<option value="01">Jan</option>
					<option value="02">Feb</option>
					<option value="03">Mar</option>
					<option value="04">Apr</option>
					<option value="05">May</option>
					<option value="06">Jun</option>
					<option value="07">Jul</option>
					<option value="08">Aug</option>
					<option value="09" selected>Sep</option>
					<option value="10">Oct</option>
					<option value="11">Nov</option>
					<option value="12">Dec</option>
				</select> / 
				<select name="day">
					<option value="01">01</option>
					<option value="02">02</option>
					<option value="03">03</option>
					<option value="04">04</option>
					<option value="05">05</option>
					<option value="06">06</option>
					<option value="07">07</option>
					<option value="08">08</option>
					<option value="09">09</option>
					<option value="10">10</option>
					<option value="11" selected>11</option>
					<option value="12">12</option>
					<option value="13">13</option>
					<option value="14">14</option>
					<option value="15">15</option>
					<option value="16">16</option>
					<option value="17">17</option>
					<option value="18">18</option>
					<option value="19">19</option>
					<option value="20">20</option>
					<option value="21">21</option>
					<option value="22">22</option>
					<option value="23">23</option>
					<option value="24">24</option>
					<option value="25">25</option>
					<option value="26">26</option>
					<option value="27">27</option>
					<option value="28">28</option>
					<option value="29">29</option>
					<option value="30">30</option>
					<option value="31">31</option>
				</select>			
	</td></tr></table></form>
        <br /><strong>Acciones Disponibles: 
        <select name="accion" id="accion" onchange="consultarAccion()" >
        	<option value='-' >---Seleccione una Accion---</option>
            <option value='compra' >Compra</option>
            <option value='venta' >Venta</option>
            <option value='prestamo' >Solicitud de Prestamo</option>
            <option value='cobro' >Cobro de Cuenta</option>
            <option value='pago' >Pago de Cuenta</option>
            <option value='pago' >Cierre de Mes</option>
        </select></strong><br />
          <br />
          <div id="Seleccion"><strong></strong></div>
          <div id="primeraSeleccion"></div>
          <div id="segundaSeleccion"></div>
          <div id="terceraSeleccion"></div>
          </td>
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
