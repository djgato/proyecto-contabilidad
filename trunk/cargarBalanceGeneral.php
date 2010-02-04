<div align="right"><a href="#" onclick="cargarEstadoResultados()">Estado de Resuldatos</a></div>
         <?php  
		        include ("conexion.php");
				include ("funciones.php");
				$usuario2 = new Servidor_Base_Datos($servidor,"root",$pass,$base_datos);
                echo '<table width ="100%" align="center">
						<tr>
						<td>
							<table align="center">
							<tr>
							<td align="center">ACTIVOS</td>
							</tr>';
							$act = balanceo ("Activo", $usuario, $usuario2);
							while( list($posicion1,$valor1) = each($act)){
								echo '<tr><td>'.$valor1.'</td></tr>';
							}
							echo '</table>
						</td>
						<td>
							<table align="center">
							<tr>
							<td align="center">PASIVOS</td></tr>';
							$pas = balanceo ("Pasivo", $usuario, $usuario2);	
							while( list($posicion1,$valor1) = each($pas)){
								echo '<tr><td>'.$valor1.'</td></tr>';
							}
							echo '</table>
						</td>
						</tr>
						</table>';
          ?>