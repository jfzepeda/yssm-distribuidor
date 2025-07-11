<?php
require_once 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$inputFileName =  __DIR__.'/fs.xlsx';
$inputFileType = 'Xlsx';

// Crear un lector de PhpSpreadsheet
$reader = IOFactory::createReader($inputFileType);

// Establecer que solo se carguen los datos de las celdas
$reader->setReadDataOnly(true);

// Cargar la hoja de cálculo una vez
$spreadsheet = $reader->load($inputFileName);

// Especificar los rangos de datos que se necesitan
$range1 = 'A13:M1000';

// Extraer los datos de los rangos especificados
$data = $spreadsheet->getActiveSheet()->rangeToArray(
    $range1,
    NULL, // Value that should be returned for empty cells (null para mantener los valores predeterminados)
    FALSE, // Should formulas be calculated
    true, // Should values be formatted
    TRUE  // Should the array be indexed by cell row and cell column
);



function espacios($ver){
	
$ret=trim($ver??"");	
 return $ret;
}

$data1=[];
foreach($data as $key =>$ver){
	if(!empty($ver["A"])):
if (str_contains($ver["A"], 'MICHEL') or str_contains($ver["A"], 'BFG') or str_contains($ver["A"], 'BF G')) {
	


	$porciones = explode("(", $ver["A"]);
		$porciones1 = explode(")", $porciones[1]??"");
if(is_numeric($porciones1[0])):
$data1["michelin"]["".espacios($porciones1[0]).""]["id"]="yantissimo-M-".espacios($porciones1[0]);
$data1["michelin"]["".espacios($porciones1[0]).""]["nombre"]=espacios($ver["A"]);
$data1["michelin"]["".espacios($porciones1[0]).""]["costo"]=espacios($ver["B"]);
$data1["michelin"]["".espacios($porciones1[0]).""]["CEDIS"]=espacios($ver["D"]);//
$data1["michelin"]["".espacios($porciones1[0]).""]["manzanillo"]=espacios($ver["E"]);
$data1["michelin"]["".espacios($porciones1[0]).""]["tec"]=espacios($ver["F"]);
$data1["michelin"]["".espacios($porciones1[0]).""]["benitoj"]=espacios($ver["G"]);
$data1["michelin"]["".espacios($porciones1[0]).""]["Constitucion"]=espacios($ver["H"]);
$data1["michelin"]["".espacios($porciones1[0]).""]["ninosheroes"]=espacios($ver["I"]);
$data1["michelin"]["".espacios($porciones1[0]).""]["consignallantrac"]=espacios($ver["J"]);
$data1["michelin"]["".espacios($porciones1[0]).""]["consignatersa"]=espacios($ver["K"]);
$data1["michelin"]["".espacios($porciones1[0]).""]["consignamorales"]=espacios($ver["L"]);
$data1["michelin"]["".espacios($porciones1[0]).""]["manzanilloblv"]=espacios($ver["M"]);
if(is_numeric($ver["D"]) && is_numeric($ver["E"])
&& is_numeric($ver["F"]) && is_numeric($ver["G"])
&& is_numeric($ver["H"]) && is_numeric($ver["I"])
&& is_numeric($ver["J"]) && is_numeric($ver["K"]) 
&& is_numeric($ver["L"]) && is_numeric($ver["M"]))
{  $data1["michelin"]["".espacios($porciones1[0]).""]["totales"]=$ver["D"]+$ver["E"]+$ver["F"]+$ver["G"]+$ver["H"]+$ver["I"]+$ver["J"]+$ver["K"]+$ver["L"]+$ver["M"]; }
else{ $data1["michelin"]["".espacios($porciones1[0]).""]["totales"]=espacios($ver["C"]);}

endif;

}else{
		$id= "yantissimo-".uniqid();

$data1["otras"]["".espacios($key).""]["id"]=espacios($id);
$data1["otras"]["".espacios($key).""]["nombre"]=espacios($ver["A"]);
$data1["otras"]["".espacios($key).""]["costo"]=espacios($ver["B"]);
$data1["otras"]["".espacios($key).""]["CEDIS"]=espacios($ver["D"]);
$data1["otras"]["".espacios($key).""]["manzanillo"]=espacios($ver["E"]);
$data1["otras"]["".espacios($key).""]["tec"]=espacios($ver["F"]);
$data1["otras"]["".espacios($key).""]["benitoj"]=espacios($ver["G"]);
$data1["otras"]["".espacios($key).""]["Constitucion"]=espacios($ver["H"]);
$data1["otras"]["".espacios($key).""]["ninosheroes"]=espacios($ver["I"]);
$data1["otras"]["".espacios($key).""]["consignallantrac"]=espacios($ver["J"]);
$data1["otras"]["".espacios($key).""]["consignatersa"]=espacios($ver["K"]);
$data1["otras"]["".espacios($key).""]["consignamorales"]=espacios($ver["L"]);
$data1["otras"]["".espacios($key).""]["manzanilloblv"]=espacios($ver["M"]);	
if(is_numeric($ver["D"]) && is_numeric($ver["E"])
&& is_numeric($ver["F"]) && is_numeric($ver["G"])
&& is_numeric($ver["H"]) && is_numeric($ver["I"])
&& is_numeric($ver["J"]) && is_numeric($ver["K"]) 
&& is_numeric($ver["L"]) && is_numeric($ver["M"]))
{  $data1["otras"]["".espacios($key).""]["totales"]=$ver["D"]+$ver["E"]+$ver["F"]+$ver["G"]+$ver["H"]+$ver["I"]+$ver["J"]+$ver["K"]+$ver["L"]+$ver["M"]; }
else{ $data1["otras"]["".espacios($key).""]["totales"]=espacios($ver["C"]);}

}

endif;	
}


//$data=$spreadsheet->getActiveSheet()->getCell('AO17')->getCalculatedValue();
$range2 = 'B13:J2000';

$spreadsheet->setActiveSheetIndex(1);
$data2 = $spreadsheet->getActiveSheet()->rangeToArray(
    $range2,
   FALSE, // Value that should be returned for empty cells (null para mantener los valores predeterminados)
    FALSE, // Should formulas be calculated
    true, // Should values be formatted
    TRUE  // Should the array be indexed by cell row and cell column
);



foreach($data2 as $key =>$ver2){
	

if(is_numeric($ver2["B"])&& !empty($data1["michelin"]["".espacios($ver2["B"]).""]["id"]) && $data1["michelin"]["".espacios($ver2["B"]).""]["id"]== "yantissimo-M-".$ver2["B"] ):
$data2["michelin1"]["".espacios($ver2["B"]).""]=$data1["michelin"]["".espacios($ver2["B"]).""];
$data2["michelin1"]["".espacios($ver2["B"]).""]["preciolista"]=espacios($ver2["J"]);

endif;


	
}
$spreadsheet->disconnectWorksheets();



$result = array_merge($data1["otras"], $data2["michelin1"]);
print_R($result);

// Usar los datos obtenidos
// $data1 y $data2 contienen los datos extraídos de los rangos especificados

// Liberar recursos (opcional, pero recomendado para optimizar el uso de memoria)
?>