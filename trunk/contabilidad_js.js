var xmlhttp
//-------------------------------INVENTARIO--------------------------------
function Inventario(str)
{
xmlhttp=GetXmlHttpObject();
if (xmlhttp==null)
  {
  alert ("Your browser does not support AJAX!");
  return;
  }
var url="Inventario-Tabla.php";
url=url+"?q="+str;
url=url+"&sid="+Math.random();
xmlhttp.onreadystatechange=stateChangedInv; //NO SE PORQ COÑO DE LA MADRE SI CAMBIO LA FUNCION
xmlhttp.open("GET",url,true);			 //stateChanged Y LE PASO EL NOMBRE DEL DIV COMO UN
xmlhttp.send(null);						 //PARAMETRO LA VAINA SE MUERE, DEJA DE FUNCIONAR :S
}
function stateChangedInv()
{
if (xmlhttp.readyState==4)
  {
  document.getElementById("libroInventario").innerHTML=xmlhttp.responseText;
  }
}
//-------------------------------INVENTARIO--------------------------------

//---------------------------INGRESAR MOVIMIENTO---------------------------
function Accion(str)
{
xmlhttp=GetXmlHttpObject();
if (xmlhttp==null)
  {
  alert ("Your browser does not support AJAX!");
  return;
  }
var url="Accion.php";
url=url+"?q="+str;
url=url+"&sid="+Math.random();
xmlhttp.onreadystatechange=stateChangedMov; //NO SE PORQ COÑO DE LA MADRE SI CAMBIO LA FUNCION
xmlhttp.open("GET",url,true);			 //stateChanged Y LE PASO EL NOMBRE DEL DIV COMO UN
xmlhttp.send(null);						 //PARAMETRO LA VAINA SE MUERE, DEJA DE FUNCIONAR :S
}

function stateChangedMov()
{
if (xmlhttp.readyState==4)
  {
  document.getElementById("Seleccion").innerHTML=xmlhttp.responseText;
  }
}
//---------------------------INGRESAR MOVIMIENTO---------------------------
//--------------------------------COMPRA-----------------------------------
function Compra(str)
{
xmlhttp=GetXmlHttpObject();
if (xmlhttp==null)
  {
  alert ("Your browser does not support AJAX!");
  return;
  }
var url="actualizarCompra.php";
url=url+"?q="+str;
url=url+"&sid="+Math.random();
xmlhttp.onreadystatechange=stateChangedMov; //NO SE PORQ COÑO DE LA MADRE SI CAMBIO LA FUNCION
xmlhttp.open("GET",url,true);			 //stateChanged Y LE PASO EL NOMBRE DEL DIV COMO UN
xmlhttp.send(null);						 //PARAMETRO LA VAINA SE MUERE, DEJA DE FUNCIONAR :S
}

function stateChangedMov()
{
if (xmlhttp.readyState==4)
  {
  document.getElementById("Seleccion").innerHTML=xmlhttp.responseText;
  }
}
//--------------------------------COMPRA-----------------------------------
//---------------------------------VENTA-----------------------------------
function Venta(str)
{
xmlhttp=GetXmlHttpObject();
if (xmlhttp==null)
  {
  alert ("Your browser does not support AJAX!");
  return;
  }
var url="actualizarVenta.php";
url=url+"?q="+str;
url=url+"&sid="+Math.random();
xmlhttp.onreadystatechange=stateChangedMov; //NO SE PORQ COÑO DE LA MADRE SI CAMBIO LA FUNCION
xmlhttp.open("GET",url,true);			 //stateChanged Y LE PASO EL NOMBRE DEL DIV COMO UN
xmlhttp.send(null);						 //PARAMETRO LA VAINA SE MUERE, DEJA DE FUNCIONAR :S
}

function stateChangedMov()
{
if (xmlhttp.readyState==4)
  {
  document.getElementById("Seleccion").innerHTML=xmlhttp.responseText;
  }
}
//---------------------------------VENTA-----------------------------------
function GetXmlHttpObject()
{
if (window.XMLHttpRequest)
  {
  // code for IE7+, Firefox, Chrome, Opera, Safari
  return new XMLHttpRequest();
  }
if (window.ActiveXObject)
  {
  // code for IE6, IE5
  return new ActiveXObject("Microsoft.XMLHTTP");
  }
return null;
}


