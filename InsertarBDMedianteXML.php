<?php
	set_time_limit(0);
	$dir = 'CFDIS';  //Directorio  donde se localizan los xml 
		if (is_dir($dir)) {
			if ($gd = opendir($dir)) {
				while ($archivo = readdir($gd)) {
					if($archivo!="." && $archivo!="..")	{
							$ruta_xml = $dir.DIRECTORY_SEPARATOR.$archivo;
							$xmlAddenda = file_get_contents($ruta_xml);
							$document = new DOMDocument();
							$document->loadXML($xmlAddenda);
                            
                            //Descompone el XML para poder acceder a cada nodo
							$nodoComprobante = $document->getElementsByTagName("Comprobante");
							
							$FORMADEPAGO = utf8_decode($nodoComprobante->item(0)->getAttribute('FormaPago'));
							$SUBTOTAL = $nodoComprobante->item(0)->getAttribute('SubTotal');
							$TOTAL = $nodoComprobante->item(0)->getAttribute('Total');
							//TIMBRE FISCAL
							$nodoTimbreFiscalDigital=$document->getElementsByTagName("TimbreFiscalDigital");
							$FECHATIMBRADO = $nodoTimbreFiscalDigital->item(0)->getAttribute('FechaTimbrado'); //echo "<p>";
							$UUID_FOLIO = $nodoTimbreFiscalDigital->item(0)->getAttribute('UUID'); //echo "<p>";
							//EMISOR
							$nodoEmisor = $document->getElementsByTagName("Emisor");
							$RFC_EMISOR = $nodoEmisor->item(0)->getAttribute('Rfc');//echo "<p>";
							//RECEPTOR
							$nodoReceptor = $document->getElementsByTagName("Receptor");
							$RFC_RECEPTOR = $nodoReceptor->item(0)->getAttribute('Rfc');//echo "<p>";
							$RAZON_SOCIAL_RECEPTOR = utf8_decode($nodoReceptor->item(0)->getAttribute('Nombre'));
							//RETENCIONES
							$nodoComplementos=$document->getElementsByTagName("Complemento");
							$nodoImpuestos=$document->getElementsByTagName("Impuestos");
							
							if($nodoImpuestos->length!=0){
								$ISR_importe = $nodoImpuestos->item(0)->getAttribute('TotalImpuestosRetenidos');
								}else{
								$ISR_importe = "0";		
								}	


															
							$OBTENER_MES = $FECHATIMBRADO; 
							$anio = substr($OBTENER_MES, 0, -15); 
							$mes_anterior = substr($OBTENER_MES, 5, -12); 
                            date_default_timezone_set('America/Mexico_City');
							$todays_date = date("Y-m-d");
							$mes_hoy = substr($todays_date, 5, -3);


                           //Verifica a que BD se van ingresar los datos
							if ($mes_anterior < $mes_hoy) {
								$tabla_portal="cfd_emitido_".$anio."_".$mes_anterior;//echo "<p>";
								//HISTORICOS CFDI PORTAL
								$link_timbrado = new mysqli("****", "*****", "*****", "****", '****') or die("Error en la conexión a MySql");
							}else{
								//PORTAL
								$link_timbrado1 = new mysqli("****", "*****", "*****", "****", '****') or die("Error en la conexión a MySql");
								$tabla_portal="cfd_emitido";
								}
							$SQL="SELECT tenant_id, rfcReceptor, uuid FROM $tabla_portal WHERE uuid = '$UUID_FOLIO'";
							if($tabla_portal=="cfd_emitido"){
							$consulta = mysqli_query($link_timbrado1, $SQL);
							}else{
							$consulta = mysqli_query($link_timbrado, $SQL);
							}

							if(mysqli_num_rows($consulta)>0){

								$row=['uuid'];
								echo "UUID: $UUID_FOLIO  |  201 - Registrado Previamente.....";echo "<p>";
								echo "<hr>";//Linea final del registro previamente

							}else {
								echo "UUID: $UUID_FOLIO  |  200 - Nuevo Registro agregado";echo "<p>";
									
							    $queryInsert = "insert into $tabla_portal (eliminado, enviado, estatus, formaDePago,iva, rfcReceptor,semaforo, subTotal, tipoComprobante, total, ultimaActualizacion, fechaDate, tenant_id, fechaRegistro, razonSocialReceptor, json, uuid, isr, anio) values ('0', '0', 'emitido', '$FORMADEPAGO', '0', '$RFC_RECEPTOR', '0', '$SUBTOTAL', 'N', '$TOTAL', now(), '$FECHATIMBRADO', '$RFC_EMISOR', '$FECHATIMBRADO', '$RAZON_SOCIAL_RECEPTOR', 'EXCEL', '$UUID_FOLIO', '0', '$anio')";
								echo $imprimirquery = $queryInsert; echo "<p>";
									
									if ($tabla_portal=='cfd_emitido') {
										mysqli_query($link_timbrado1, $queryInsert);
									echo "Tabla de cfd_emitido";echo "<p>";
									}else{
										mysqli_query($link_timbrado, $queryInsert);
									echo "Tabla de historicos";echo "<p>";
									}
										echo "<hr>";//Linea final del registro nuevo
								}
						}
					}
				closedir($gd);
			}
	}

?>