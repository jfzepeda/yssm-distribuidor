<? include_once('../includes/config.php');
$tipoc="Camion";
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
/*
if($_GET["tipo"]==1):
$tipoc="Auto /Camion";

endif; */
// &Tip=".$_GET["tipo"]."
//username and password of account
if(!empty($_GET["buscar"]) && $_GET["buscar"]=="borrar"){
	$ac=$GLOBALS['pdo']->prepare('DELETE FROM productos WHERE proveedorp = ?');
 $ac->execute(array("hacsallantas"));
 echo "borrar";
}else{
$username = trim("compras@yantissimo.com");
$password = trim("llanta33");


$cookieFile = getcwd()."/cookieshacsallantas.txt";
$cabeza = ['Host: ventas.hacsallantas.mx',
    'Origin: https://ventas.hacsallantas.mx',
    'Referer: https://ventas.hacsallantas.mx/auth/signin',

];
$buscar=urlencode($_GET["buscar"]);

if(!empty($buscar)){
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://ventas.hacsallantas.mx');
curl_setopt($ch, CURLOPT_COOKIESESSION, true);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0); // Usar HTTP/2

curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEFILE,$cookieFile); 
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Seguir redirecciones
curl_setopt($ch, CURLOPT_VERBOSE, true);

$answer = curl_exec($ch);

if (curl_error($ch)) {
    echo curl_error($ch);
}
$token= extstr($answer,'type="hidden" name="_token" value="','"');

echo $token."-token";

if($token!=""){



//$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://ventas.hacsallantas.mx/auth/signin');
curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
    'email' => $username,
    'password' => $password,
	'_token'=>$token
))); curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_COOKIESESSION, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEFILE,$cookieFile); 
curl_setopt($ch, CURLOPT_HTTPHEADER,$cabeza);

$answer0 = curl_exec($ch);

if (curl_error($ch)) {
    echo curl_error($ch);
}
$token= extstr($answer0,'<meta name="csrf-token" content="','"');


}

//$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://ventas.hacsallantas.mx/search-articulos?q='.$buscar.'&status=1&catalogo%5B%5D=AUTO+Y+CAMIONETA');
curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_COOKIESESSION, true);

curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEFILE,$cookieFile); 
//curl_setopt($ch, CURLOPT_HTTPHEADER,$cabeza);

$answer1 = curl_exec($ch);

if (curl_error($ch)) {
    echo curl_error($ch);
}
$v = json_decode($answer1,true);



if(!empty($v)) {

	try{
	
 foreach($v["data"] as $k=> $obj){
	 $total=0;
	 foreach($obj["existencias"] as $key=>$exist){
$total +=floatval($exist["existencia"]);
	 }

	$id= "hacsallantas-".$obj['id'];
$pattern = '/\b\d{3}\/\d{2}[A-Z]\d{2}\b/';

preg_match($pattern, $obj["name"], $matches); 

if(!empty($matches[0])){
	$rin = explode("R", $matches[0]);
	$titc = explode(" ", $obj["name"]);

	 	$stmt=$GLOBALS['pdo']->prepare('SELECT claveproveedor,cantidad FROM productos WHERE claveproveedor=?');
			$stmt->execute(array($id));
	$pros = $stmt->fetch(PDO::FETCH_ASSOC);
		 $preciomx= 0;

	if($obj['price_iva']>0):
	 $preciomx= (float)$obj['price_iva'];
endif;



if(!empty($pros["claveproveedor"])):

				$ac=$GLOBALS['pdo']->prepare('UPDATE productos SET cantidad=?,precio=?,estadociudad=? WHERE claveproveedor=?');
 $ac->execute(array($total,$preciomx,"1",$id));
				 $actu=  $ac->rowCount();



else:

		$insertar=$GLOBALS['pdo']->prepare('INSERT INTO productos (claveproveedor,productos,cantidad,proveedorp,categoria,idunicoinvetariado,precio,creado,rin,estadociudad,size) VALUES (?,?,?,?,?,?,?,?,?,?,?)');
		 	  $insertar->execute(array($id,$obj["name"],$total??0,"hacsallantas",strtolower($titc[1]),uniqid($titc[1]."-"),"".$preciomx."",date("Y-m-d H:i:s"),$rin[1],"1",$rin[0]));
			 $GLOBALS['pdo']->lastInsertId();


   
  endif; 
}

  usleep(1);
 }echo "</br>ok";
	
}catch (PDOException $e) {
				 	throw new RuntimeException("[".$e->getCode()."] : ". $e->getMessage());
			 }


}
}
//
}
?>



   <h1>Lista de Tamaños de Llantas</h1>
    <ul>
        <li><a href="https://distribuidor.yantissimo.com/dlago/hacsallantas.php?buscar=155/70">155/70</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/hacsallantas.php?buscar=165/70">165/70</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/hacsallantas.php?buscar=175/65">175/65</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/hacsallantas.php?buscar=185/60">185/60</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/hacsallantas.php?buscar=195/55">195/55</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/hacsallantas.php?buscar=205/50">205/50</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/hacsallantas.php?buscar=205/55">205/55</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/hacsallantas.php?buscar=215/45">215/45</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/hacsallantas.php?buscar=215/55">215/55</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/hacsallantas.php?buscar=225/40">225/40</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/hacsallantas.php?buscar=225/50">225/50</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/hacsallantas.php?buscar=235/35">235/35</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/hacsallantas.php?buscar=235/45">235/45</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/hacsallantas.php?buscar=245/30">245/30</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/hacsallantas.php?buscar=245/40">245/40</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/hacsallantas.php?buscar=255/55">255/55</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/hacsallantas.php?buscar=255/60">255/60</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/hacsallantas.php?buscar=265/70">265/70</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/hacsallantas.php?buscar=265/75">265/75</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/hacsallantas.php?buscar=275/65">275/65</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/hacsallantas.php?buscar=275/70">275/70</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/hacsallantas.php?buscar=285/60">285/60</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/hacsallantas.php?buscar=285/65">285/65</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/hacsallantas.php?buscar=295/55">295/55</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/hacsallantas.php?buscar=295/60">295/60</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/hacsallantas.php?buscar=305/50">305/50</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/hacsallantas.php?buscar=305/55">305/55</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/hacsallantas.php?buscar=315/45">315/45</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/hacsallantas.php?buscar=315/50">315/50</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/hacsallantas.php?buscar=325/40">325/40</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/hacsallantas.php?buscar=325/45">325/45</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/hacsallantas.php?buscar=335/35">335/35</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/hacsallantas.php?buscar=335/40">335/40</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/hacsallantas.php?buscar=345/30">345/30</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/hacsallantas.php?buscar=345/35">345/35</a></li>
    </ul>
<? print_r($v["data"]);
?>