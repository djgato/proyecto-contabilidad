<?php 
require_once ("conexion.php");


$resAux =  $usuario->consulta("SELECT id_cuenta FROM cuenta WHERE nombre_cuenta LIKE 'Dep.%' ");
if ($hayAlgoQueDrepeciar = $usuario->extraer_registro($resAux)){
	
	//CREO UN NUEVO ASIENTO EN EL LIBRO DE DIARIO CON SU FECHA RESPECTIVA----
	$res5 = $usuario->consulta("SELECT * FROM libro_diario ORDER BY id_ldiario DESC LIMIT 1");
				$ultimoRegistro = $usuario->extraer_registro($res5);
				
				$ultimoAsiento = $ultimoRegistro ['id_ldiario'];
				$ultimoAsiento = (int) $ultimoAsiento;
				$ultimoAsiento = ($ultimoAsiento + 1);
				
				$fechaUltimoAsiento = $ultimoRegistro ['fecha_ldiario'];
				list($año,$mes,$dia)=explode("-",$fechaUltimoAsiento);
				$fecha = $año."-".$mes."-30";
				
	$res6 = $usuario->consulta("INSERT INTO libro_diario (id_ldiario, fecha_ldiario) VALUES ('', '$fecha')");
	//------------------------------------------------------------------------------------------
	
	
	$res =  $usuario->consulta("SELECT id_cuenta FROM cuenta WHERE nombre_cuenta LIKE 'Dep.%' ");
	while ($cuentasDep = $usuario->extraer_registro($res)){
		$idCuentaDep = $cuentasDep["id_cuenta"];
		
		$res2 = $usuario->consulta("SELECT id_gasto_asociado FROM gasto_cuenta WHERE id_cuenta_gasto = '$idCuentaDep' ");
		while ($gastoAsociado = $usuario->extraer_registro($res2)){
			$idGastoAsociado = $gastoAsociado["id_gasto_asociado"];
			
			$res3 = $usuario->consulta("SELECT monto_gasto, pagos_restantes FROM gasto_asociado WHERE id_gasto_asociado = '$idGastoAsociado' ");
			while ($datosGastosAsociado = $usuario->extraer_registro($res3)){
				$montoGasto = $datosGastosAsociado["monto_gasto"];
				$restantesGasto = $datosGastosAsociado["pagos_restantes"];
				
				$restantesGasto = (int) $restantesGasto;
				$restantesGasto = $restantesGasto - 1;
				
				$res4 = $usuario->consulta("UPDATE gasto_asociado SET pagos_restantes = '$restantesGasto' WHERE  id_gasto_asociado ='$idGastoAsociado'");
							
				$idCuentaDep = (int) $idCuentaDep; // ejm Dep. Ac. Vehiculo
				$idCuentaDepNext = $idCuentaDep + 1; // Gastos por Dep. Vehiculo
				
				$res5 = $usuario->consulta("INSERT INTO movimiento (id_cuenta_movimiento, id_ldiario_movimiento, monto_movimiento, columna_movimiento) VALUES ('$idCuentaDep', '$ultimoAsiento', '$montoGasto', 'h')");
				$res5 = $usuario->consulta("INSERT INTO movimiento (id_cuenta_movimiento, id_ldiario_movimiento, monto_movimiento, columna_movimiento) VALUES ('$idCuentaDepNext', '$ultimoAsiento', '$montoGasto', 'd')");
			}
		}
	}
}


?>