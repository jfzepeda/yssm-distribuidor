<?ini_set('max_execution_time', 1200);
ini_set('memory_limit', '-1');

 include_once('../includes/config.php');
$pagina=$_GET["pag"];
echo $pagina." pagina- ";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://yantissimo.ddns.net:9091/exsim/servicios/metodo/ARTICULOS/75Cv04CyLxV6x5QP8j9CQnCp9d9M3d/1000/'.$pagina.'/0/0/');
curl_setopt($ch, CURLOPT_COOKIESESSION, true);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0); // Usar HTTP/2
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Seguir redirecciones
curl_setopt($ch, CURLOPT_VERBOSE, true);

$exs = curl_exec($ch);
if (curl_error($ch)) {
    echo curl_error($ch);
}
$reparadoarray=array();
$ex = str_replace('\\\"', '', $exs);  // Escapar correctamente

$out= json_decode($ex,true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo 'Error de decodificación JSON: ' . json_last_error_msg();
}
print_r($out);

/*
if(!empty($out)){
foreach($out as $ver1):
$medidas=explode(" ",$ver1["nombre"]);
$etiqueta=explode("#",$ver1["nombre"]);
$linea=trim($ver1["linea"]);
$etiqueta[0] = count($etiqueta) > 1 ? $etiqueta[0] : '';


if (!str_contains($ver1["nombre"], 'MICHEL') or str_contains(!$ver1["nombre"], 'BFG') or str_contains($ver1["nombre"], 'BF G')) {

if(!empty($ver1["precios"][0]["precio"]) && $linea=="AUTO Y CAMIONETA"){
	$total=0;
$total += $general = floatval($ver1["existencia"][10]["existencia"]);
$total += $manzanillo = floatval($ver1["existencia"][8]["existencia"]);
$total += $tecnolico = floatval($ver1["existencia"][0]["existencia"]);
$total += $benitoj = floatval($ver1["existencia"][1]["existencia"]);
$total += $constitucion = floatval($ver1["existencia"][7]["existencia"]);
$total += $ninosheroes = floatval($ver1["existencia"][6]["existencia"]);
$total += $consignallantrac = floatval(0);
$total += $consignatersa = floatval(0);
$total += $consignamoralez = floatval(0);
$total += $manzanillobulevard = floatval($ver1["existencia"][19]["existencia"]);

$reparadoarray[]=array("id"=>$ver1["id"],
"clave"=>$ver1["clave"],
"sku"=>$ver1["sku"],
"nombre"=>$ver1["nombre"],
"linea"=>$ver1["linea"],
"general"=>$general,
"manzanillo"=>$manzanillo,
"tecnologico"=>$tecnolico,
"benitoj"=>$benitoj,
"constitucion"=>$constitucion,
"ninosheroes"=>$ninosheroes,
"consignallantrac"=>$consignallantrac,
"consignatersa"=>$consignatersa,
"consignamoralez"=>$consignamoralez,
"manzanillobulevard"=>$manzanillobulevard,
"rin"=>str_replace("R","",$medidas[1]),
"medidas"=>$medidas[0],
"categoria"=>$medidas[2],
"precio"=>(float)$ver1["precios"][0]["precio"],
"cantidad"=>$total,
"etiqueta"=>$etiqueta[0]
);} }

endforeach;
}

print_R($reparadoarray);


try{
if(!empty($reparadoarray)){
foreach($reparadoarray as $arrasend):

	$stmt=$GLOBALS['pdo']->prepare('SELECT claveproveedor,cantidad FROM productos WHERE claveproveedor=? and idunicoinvetariado=?');
			$stmt->execute(array($arrasend["id"],$arrasend["sku"]));
	$pros = $stmt->fetch(PDO::FETCH_ASSOC);
	
	if(!empty($pros["claveproveedor"])):


		$ac=$GLOBALS['pdo']->prepare('UPDATE productos SET 
		cantidad=?,
		precio=?,
		estadociudad=?,
		CEDIS=?,
		MANZANILLO=?,
		TECNOLOGICO=?,
		BENITOJ=?,
		CONSTITUCION=?,
		NINOH=?,
		LLANTRAC=?,
		TERSA=?,
		CONSIGNAMORALEZ=?,
		MANZANILLOBLVD=?
		WHERE claveproveedor=?');
 $ac->execute(array(
 $arrasend["cantidad"],
 $arrasend["precio"],
 "1",
 $arrasend["general"],
 $arrasend["manzanillo"],
 $arrasend["tecnologico"],
 $arrasend["benitoj"],
 $arrasend["constitucion"],
 $arrasend["ninosheroes"],
 $arrasend["consignallantrac"],
 $arrasend["consignatersa"],
 $arrasend["consignamoralez"],
 $arrasend["manzanillobulevard"],
  $pros["claveproveedor"]));
			
else:
		$insertar=$GLOBALS['pdo']->prepare('INSERT INTO productos (
		claveproveedor,
		productos,
		cantidad,
		proveedorp,
		categoria,
		idunicoinvetariado,
		precio,
		creado,
		rin,
		estadociudad,
		size,
		CEDIS,
		MANZANILLO,
		TECNOLOGICO,
		BENITOJ,
		CONSTITUCION,
		NINOH,
		LLANTRAC,
		TERSA,
		CONSIGNAMORALEZ,
		MANZANILLOBLVD,
		etiqueta) VALUES (?,?,?,?,?,?,?,?,?,?,?
		,?,?,?,?,?,?,?,?,?,?,?)');
		 	  $insertar->execute(array($arrasend["id"],
			  $arrasend["nombre"],
			  $arrasend["cantidad"]??0,
			  "Yantissimo",
			  strtolower($arrasend["categoria"]),
			  $arrasend["sku"],
			 $arrasend["precio"],
			 date("Y-m-d H:i:s"),
			$arrasend["rin"],
			"1",
			$arrasend["medidas"],
			 $arrasend["general"],
 $arrasend["manzanillo"],
 $arrasend["tecnologico"],
 $arrasend["benitoj"],
 $arrasend["constitucion"],
 $arrasend["ninosheroes"],
 $arrasend["consignallantrac"],
 $arrasend["consignatersa"],
 $arrasend["consignamoralez"],
 $arrasend["manzanillobulevard"],
	 $arrasend["etiqueta"]));
			 $GLOBALS['pdo']->lastInsertId();

endif;	

	
			usleep(2);
	
	
endforeach;
}


 }catch (PDOException $e) {
				 	throw new RuntimeException("[".$e->getCode()."] : ". $e->getMessage());
			 }

 */