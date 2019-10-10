<?php
$conexion = mysqli_connect("localhost","uahm93","******", "XMLhistoricos", 3707) or die("Error en la conexión a MySql");

require_once 'php/ext/PHPExcel-1.7.7/Classes/PHPExcel/IOFactory.php'; //Importa Librerias para leer excel
$objPHPExcel = PHPExcel_IOFactory::load('vendor/indici.xlsx'); //Carga excel donde estan los Id de los archvivos que necesitamos recuperar
$objHoja=$objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
$empresa = "Archivos"; //Carpeta donde se guardaran los archvios recuperados

foreach ($objHoja as $iIndice=>$objCelda){

    $uuid = trim($objCelda['A']);			

    echo "=============================INICIO PROCESO===========================================\n";
    echo "\n". $consultar="SELECT * FROM tabla where id = '$id'"; 

	$result = mysqli_query($conexion,$consultar);
	if(mysqli_num_rows($result)>0){

	  $fila = mysqli_fetch_array($result);
$nombre_archivo = $fila['uuid'];

echo "\n".$ruta = "/mnt/".$fila['xml'];
$url_xml = $ruta;
$existsxml = is_file($url_xml);
$fr_xml = 0;


if($existsxml){
$fichero = $url_xml; 

	echo "\n".$nuevo_fichero = '/var/www/html/RecuperarXMLTimbrado/'.$empresa.'/'.$nombre_archivo.'.xml';     	
	$fr_xml = copy($fichero, $nuevo_fichero);

	echo "\n".$nombre_archivo;
				echo "\nHECHO\n";

			}else{

				echo "\n".$nombre_archivo;
				echo "\nPROCESO: FALLIDO\n";

			}

	}          

	echo "=============================FIN PROCESO==============================================\n";
}
?>


