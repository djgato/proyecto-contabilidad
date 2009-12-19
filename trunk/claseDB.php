<?php
class Servidor_Base_Datos
{
	private $servidor;
	private $usuario;
	private $pass;
	private $base_datos;
	private $descriptor;
	private $resultado;
	function __construct($servidor,$usuario,$pass,$base_datos)
	{
		$this->servidor = $servidor;
		$this->usuario = $usuario;
		$this->pass = $pass;
		$this->base_datos = $base_datos;
		$this->conectar_base_datos();
	}
	private function conectar_base_datos()
	{
		$this->descriptor = mysql_connect($this->servidor,$this->usuario,$this->pass);
		mysql_select_db($this->base_datos,$this->descriptor);
	}
	public function consulta($consulta)
	{
		return mysql_query($consulta,$this->descriptor);
	}
	public function extraer_registro($resultado)
	{
		if ($fila = mysql_fetch_array($resultado,MYSQL_ASSOC)) {
			return $fila;
		} else {
			return false;
		}
	}
}

?>