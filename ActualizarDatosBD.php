<?php

	date_default_timezone_set('America/Mexico_City');
	//conexion a base de datos
	$conexion = mysqli_connect("****","****","****", "***", 3707) or die("Error en la conexión a MySql");			
	
	require_once 'php/ext/PHPExcel-1.7.7/Classes/PHPExcel/IOFactory.php'; //
	$objPHPExcel = PHPExcel_IOFactory::load('xls/actualizar.xlsx'); //Excel que contiene los id de los registros a actualizar
	$objHoja=$objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
	foreach ($objHoja as $iIndice=>$objCelda){
		
		$uuid_e = trim($objCelda['A']);
		$consultar="select id, eliminado from tabla where uuid='$uuid_e'";
		$result = mysqli_query($conexion,$consultar);

		if(mysqli_num_rows($result)>0){

				while ($fila = mysqli_fetch_array($result)) {
                $uuid_e = trim($objCelda['A']); 
				$actualizar="update cfd_emitido set eliminado='0' where uuid='$uuid_e' ";
                mysqli_query($conexion,$actualizar);				
				
			    $uuid_e = trim($objCelda['A']);
				$status = $fila["eliminado"];	
				echo "---HECHO---\n";
											
				}
		}else {
			
			echo "---Fallidos---\n";
		}
	}
			
?>
