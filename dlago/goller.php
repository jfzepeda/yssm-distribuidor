<? include_once('../includes/config.php');
/*
if($_GET["tipo"]==1):
$tipoc="Auto /Camion";

endif; */
// &Tip=".$_GET["tipo"]."
//username and password of account
$username = trim("aldo.juarez@yantissimo.com");
$password = trim("AldoJ2024");


function extstr($content,$start,$end){ 
if($content && $start && $end) { 
$r = explode($start, $content); 
if (isset($r[1])){ 
$r = explode($end, $r[1]); 
return $r[0]; 
} 
return ''; 
} 
} 

if($_GET["buscar"]=="borrar"):
	$ac=$GLOBALS['pdo']->prepare('DELETE FROM productos WHERE proveedorp = ?');
 $ac->execute(array("Goller"));
 endif;
$cookieFile = getcwd()."/cookiesw22goller.txt";

//$cookieFile = tempnam(sys_get_temp_dir(), 'cookie');
$cabeza = ['Host: 7615759.app.netsuite.com',
    'Origin: https://7615759.app.netsuite.com',
    'Referer: https://7615759.app.netsuite.com/app/login/secure/enterpriselogin.nl',
];
if(!empty($_GET["buscar"])):

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://7615759.app.netsuite.com/app/login/secure/privatelogin.nl');
curl_setopt($ch, CURLOPT_COOKIESESSION, true);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0); // Usar HTTP/2

curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEFILE,$cookieFile); 
    curl_setopt($ch, CURLOPT_HTTPHEADER,$cabeza);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, "c=7615759&email=".$username."&password=".$password."&submitButton="); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Seguir redirecciones
curl_setopt($ch, CURLOPT_VERBOSE, true);

$answer = curl_exec($ch);

if (curl_error($ch)) {
    echo curl_error($ch);
}
$buscar=urlencode($_GET["buscar"]);
//$ch = curl_init();
echo $buscar;
curl_setopt($ch, CURLOPT_URL, 'https://7615759.app.netsuite.com/app/site/hosting/scriptlet.nl?script=1297&deploy=1&compid=7615759&whence=');
curl_setopt($ch, CURLOPT_COOKIESESSION, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER,$cabeza);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Seguir redirecciones
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0); // Usar HTTP/2

//curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
//curl_setopt($ch, CURLOPT_COOKIEFILE,$cookieFile); 

curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

curl_setopt($ch, CURLOPT_POSTFIELDS, "submitter=Buscar&custpage_nscs_sf_item_sales_description=".$buscar);
curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');

$v = curl_exec($ch);
$info = curl_getinfo($ch);

if (curl_error($ch)) {
    echo curl_error($ch);
}





$output=$v;

$dom = new DOMDocument();
libxml_use_internal_errors(true);
$dom->loadHTML($output);
libxml_clear_errors();
$xpath = new DOMXpath($dom);


$table_rows = $xpath->query('//table[@id="custpage_nscs_sl_search_results_splits"]/tr');

$data = [];
foreach ($table_rows as $rowIndex => $tr) {
    // Consultar todas las celdas en la fila actual
    $cells = $xpath->query('td', $tr);
    foreach ($cells as $cell) {
        $cellValue = trim($cell->nodeValue);
		
        if ($cellValue !== '') {
            $data[$rowIndex][] = preg_replace('~[\r\n]+~', '', $cellValue);
        } else {
            $data[$rowIndex][] = ''; // Mantener el formato de las celdas vacías
        }
		
		
    }
    // Reindexar el array para eliminar los índices desordenados
    $data[$rowIndex] = array_values($data[$rowIndex]);
}

if(!empty($data)) {
$i=0;
foreach($data as $key => $result){
	if($key>0){
	$total=0;
	$reparar[$i]["titulo"]=$result[2];
		$reparar[$i]["precio"]=$result[4];
$total += $reparar[$i]["COLIMA20DENOV"] = floatval($result[6]);
$total += $reparar[$i]["COLIMAAVTECOMAN"] = floatval($result[7]);
$total += $reparar[$i]["COLIMAPERIFERICO"] = floatval($result[8]);
$total += $reparar[$i]["COLIMASOTOGAMA"] = floatval($result[9]);
$total += $reparar[$i]["COLIMAFILARMONICOS"] = floatval($result[10]);
$total += $reparar[$i]["MANZANILLOTAPEIXTLES"] = floatval($result[11]);
$total += $reparar[$i]["MANZANILLOCANTAMAR"] = floatval($result[12]);
$total += $reparar[$i]["MANZANILLOBOULEVARD"] = floatval($result[13]);
$total += $reparar[$i]["MANZANILLOVAKHO"] = floatval($result[14]);
$total += $reparar[$i]["MANZANILLOMOVILACCESORIOS"] = floatval($result[15]);
$total += $reparar[$i]["JALISCOCDGUZMAN"] = floatval($result[16]);
$total += $reparar[$i]["MANZANILLOTALLERTAPEIXTLES"] = floatval($result[17]);

// Almacena el total en la clave 'cantidad'
$reparar[$i]["cantidad"] = $total;

$i++;
	}
}



	try{
		if(!empty($reparar)){
			echo "sihay";
 foreach($reparar as $key => $obj){
	$idunico= uniqid();

	if($obj['precio']>10):
	 $preciomx= (float)$obj['precio'];
endif;

$porciones = explode(" ", $obj["titulo"]);
$medidas= explode("R", $porciones[1]);


		$insertar=$GLOBALS['pdo']->prepare('INSERT INTO productos (claveproveedor,productos,cantidad,proveedorp,categoria,idunicoinvetariado,precio,creado,rin,estadociudad,size
		,CDENOVgoller,CTECOMANgoller,CPERIFERICOgoller,CSOTOGAMAgoller,
		CFILARMONICOSgoller,mTAPEIXTLESgoller,MCANTAMARgoller,MBOULEVARDgoller
		,MVAKHO,MOVILACCESORIOgoller,
		JCGUZMANgoller,MTALLERTAPEIXTLES) VALUES (?,?,?,?,?,?,?,?,?,?,?
		,?,?,?,?,?,?,?,?,?,?,?,?)');
		 	  $insertar->execute(array($idunico,$obj["titulo"],$obj['cantidad']??0,"Goller",strtolower($porciones[2]),$idunico,"".$preciomx."",date("Y-m-d H:i:s"),$medidas[1],"1",$medidas[0]
			  ,$obj["COLIMA20DENOV"],$obj["COLIMAAVTECOMAN"],$obj["COLIMAPERIFERICO"]
			  ,$obj["COLIMASOTOGAMA"],$obj["COLIMAFILARMONICOS"],$obj["MANZANILLOTAPEIXTLES"]
			  ,$obj["MANZANILLOCANTAMAR"],$obj["MANZANILLOBOULEVARD"],$obj["MANZANILLOVAKHO"]
			  ,$obj["MANZANILLOMOVILACCESORIOS"],$obj["JALISCOCDGUZMAN"],$obj["MANZANILLOTALLERTAPEIXTLES"]
			  ));
			 $GLOBALS['pdo']->lastInsertId();


   

  usleep(1);
		}}
}catch (PDOException $e) {
				 	throw new RuntimeException("[".$e->getCode()."] : ". $e->getMessage());
			 }



}
 endif;


?>



   <h1>Lista de Tamaños de Llantas</h1>
    <ul>
        <li><a href="https://distribuidor.yantissimo.com/dlago/goller.php?buscar=155/70">155/70</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/goller.php?buscar=165/70">165/70</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/goller.php?buscar=175/65">175/65</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/goller.php?buscar=185/60">185/60</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/goller.php?buscar=195/55">195/55</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/goller.php?buscar=205/50">205/50</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/goller.php?buscar=205/55">205/55</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/goller.php?buscar=215/45">215/45</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/goller.php?buscar=215/55">215/55</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/goller.php?buscar=225/40">225/40</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/goller.php?buscar=225/50">225/50</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/goller.php?buscar=235/35">235/35</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/goller.php?buscar=235/45">235/45</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/goller.php?buscar=245/30">245/30</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/goller.php?buscar=245/40">245/40</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/goller.php?buscar=255/55">255/55</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/goller.php?buscar=255/60">255/60</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/goller.php?buscar=265/70">265/70</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/goller.php?buscar=265/75">265/75</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/goller.php?buscar=275/65">275/65</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/goller.php?buscar=275/70">275/70</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/goller.php?buscar=285/60">285/60</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/goller.php?buscar=285/65">285/65</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/goller.php?buscar=295/55">295/55</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/goller.php?buscar=295/60">295/60</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/goller.php?buscar=305/50">305/50</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/goller.php?buscar=305/55">305/55</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/goller.php?buscar=315/45">315/45</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/goller.php?buscar=315/50">315/50</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/goller.php?buscar=325/40">325/40</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/goller.php?buscar=325/45">325/45</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/goller.php?buscar=335/35">335/35</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/goller.php?buscar=335/40">335/40</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/goller.php?buscar=345/30">345/30</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/goller.php?buscar=345/35">345/35</a></li>
    </ul>
<? echo $v;?>