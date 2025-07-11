<? include_once('../includes/config.php');
$tipoc="Camion";
/*
if($_GET["tipo"]==1):
$tipoc="Auto /Camion";

endif; */
// &Tip=".$_GET["tipo"]."
//username and password of account
$username = trim("yantissimo@gmail.com");
$password = trim("secreto2020");


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
if(!empty($_GET["buscar"]) && $_GET["buscar"]=="borrar"){

	$ac=$GLOBALS['pdo']->prepare('DELETE FROM productos WHERE proveedorp = ?');
 $ac->execute(array("grupoloyga"));
 
}else{

$cookieFile = getcwd()."/cookiesgrupoga.txt";

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://ave.grupoloyga.mx/login');
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
$token= extstr($answer,'<meta name="csrf-token" content="','"');

echo $token;


$cabeza = ['Host: ave.grupoloyga.mx',
    'Origin: https://ave.grupoloyga.mx',
    'Referer: https://ave.grupoloyga.mx/login',
    'X-CSRF-TOKEN: '.$token.''

];

//$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://ave.grupoloyga.mx/login');
curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
    'email' => $username,
    'password' => $password
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

$cabeza = ['Host: ave.grupoloyga.mx',
    'Referer: https://ave.grupoloyga.mx/dashboard',
    'X-CSRF-TOKEN: '.$token.'',
	'Query: '.$_GET["buscar"].''

];
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://ave.grupoloyga.mx/search?_='.time());
curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_COOKIESESSION, true);

curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEFILE,$cookieFile); 
curl_setopt($ch, CURLOPT_HTTPHEADER,$cabeza);

$answer1 = curl_exec($ch);

if (curl_error($ch)) {
    echo curl_error($ch);
}
$v = json_decode($answer1,true);



if(!empty($v)) {

	try{
	//$ac=$GLOBALS['pdo']->prepare('DELETE FROM productos WHERE proveedorp = ?');
 //$ac->execute(array("grupoloyga"));
 foreach($v["data"]["items"] as $obj){
	  


	$id= "ave-".$obj['id'];
	 	$stmt=$GLOBALS['pdo']->prepare('SELECT claveproveedor,cantidad FROM productos WHERE claveproveedor=?');
			$stmt->execute(array($id));
	$pros = $stmt->fetch(PDO::FETCH_ASSOC);
		 $preciomx= 0;

	if($obj['price']>10):
	 $preciomx= (float)$obj['price'];
endif;



if(!empty($pros["claveproveedor"])):

				$ac=$GLOBALS['pdo']->prepare('UPDATE productos SET cantidad=?,precio=?,rin=?,estadociudad=? WHERE claveproveedor=?');
 $ac->execute(array($obj['stock']['total'],$preciomx,$obj['features'][4]['value'],"1",$pros["claveproveedor"]));
				 $actu=  $ac->rowCount();



else:

		$insertar=$GLOBALS['pdo']->prepare('INSERT INTO productos (claveproveedor,productos,cantidad,proveedorp,categoria,idunicoinvetariado,precio,creado,rin,estadociudad,size) VALUES (?,?,?,?,?,?,?,?,?,?,?)');
		 	  $insertar->execute(array($id,$obj['name'],$obj['stock']['total']??0,"grupoloyga",strtolower($obj['brand']),uniqid($obj['brand']."-"),"".$preciomx."",date("Y-m-d H:i:s"),$obj['features'][4]['value'],"1",$obj['features'][1]['value'].''.$obj['features'][0]['value']));
			 $GLOBALS['pdo']->lastInsertId();


   
  endif; 


  usleep(1);
} echo "</br>ok";

}catch (PDOException $e) {
				 	throw new RuntimeException("[".$e->getCode()."] : ". $e->getMessage());
			 }



}
}
?>



   <h1>Lista de Tamaños de Llantas</h1>
    <ul>
        <li><a href="https://distribuidor.yantissimo.com/dlago/grupoloyga.php?buscar=155/70">155/70</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/grupoloyga.php?buscar=165/70">165/70</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/grupoloyga.php?buscar=175/65">175/65</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/grupoloyga.php?buscar=185/60">185/60</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/grupoloyga.php?buscar=195/55">195/55</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/grupoloyga.php?buscar=205/50">205/50</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/grupoloyga.php?buscar=205/55">205/55</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/grupoloyga.php?buscar=215/45">215/45</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/grupoloyga.php?buscar=215/55">215/55</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/grupoloyga.php?buscar=225/40">225/40</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/grupoloyga.php?buscar=225/50">225/50</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/grupoloyga.php?buscar=235/35">235/35</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/grupoloyga.php?buscar=235/45">235/45</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/grupoloyga.php?buscar=245/30">245/30</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/grupoloyga.php?buscar=245/40">245/40</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/grupoloyga.php?buscar=255/55">255/55</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/grupoloyga.php?buscar=255/60">255/60</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/grupoloyga.php?buscar=265/70">265/70</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/grupoloyga.php?buscar=265/75">265/75</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/grupoloyga.php?buscar=275/65">275/65</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/grupoloyga.php?buscar=275/70">275/70</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/grupoloyga.php?buscar=285/60">285/60</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/grupoloyga.php?buscar=285/65">285/65</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/grupoloyga.php?buscar=295/55">295/55</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/grupoloyga.php?buscar=295/60">295/60</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/grupoloyga.php?buscar=305/50">305/50</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/grupoloyga.php?buscar=305/55">305/55</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/grupoloyga.php?buscar=315/45">315/45</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/grupoloyga.php?buscar=315/50">315/50</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/grupoloyga.php?buscar=325/40">325/40</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/grupoloyga.php?buscar=325/45">325/45</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/grupoloyga.php?buscar=335/35">335/35</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/grupoloyga.php?buscar=335/40">335/40</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/grupoloyga.php?buscar=345/30">345/30</a></li>
        <li><a href="https://distribuidor.yantissimo.com/dlago/grupoloyga.php?buscar=345/35">345/35</a></li>
    </ul>
