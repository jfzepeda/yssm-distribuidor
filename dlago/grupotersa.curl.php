<? include_once('../includes/config.php');
$tipoc="Camion";
/*
if($_GET["tipo"]==1):
$tipoc="Auto /Camion";

endif; */
// &Tip=".$_GET["tipo"]."
//username and password of account
$username = trim("administracion@yantissimo.com");
$password = trim(")9Jx19PW");


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
$cookiesFile = getcwd()."/cookies.txt"; // <--- cookies are stored here

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://www.asociados.grupotersa.com.mx/yokozuna/auth/login');
curl_setopt($ch, CURLOPT_COOKIESESSION, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiesFile);
curl_setopt($ch, CURLOPT_COOKIEFILE,$cookiesFile); 

curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, "username=".$username."&password=".$password); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_VERBOSE, true);

$answer = curl_exec($ch);

if (curl_error($ch)) {
    echo curl_error($ch);
}
$verjson=json_decode($answer,true);

curl_setopt($ch, CURLOPT_URL, 'http://www.asociados.grupotersa.com.mx/api/inventory');
curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
curl_setopt($ch, CURLOPT_COOKIESESSION, true);
curl_setopt($ch, CURLOPT_POST, false);

curl_setopt($ch, CURLINFO_HEADER_OUT, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiesFile);

curl_setopt($ch, CURLOPT_COOKIEFILE,$cookiesFile); 
curl_setopt($ch, CURLOPT_ENCODING, "gzip");

   $authorization = "Authorization: Bearer ".$verjson["token"].""; 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: www.asociados.grupotersa.com.mx','Connection: Keep-Alive','accept: application/json','Content-Type: application/json' ,$authorization));
	curl_setopt($ch, CURLOPT_REFERER, "http://www.asociados.grupotersa.com.mx/");

curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
$v = curl_exec($ch);
$info = curl_getinfo($ch);

if (curl_error($ch)) {
    echo curl_error($ch);
}


$v = json_decode($v,true);


if(!empty($v)) {

print_r($v);

	try{
 foreach($v as $obj){
	$id= "gt-".$obj['id'];
	 	$stmt=$GLOBALS['pdo']->prepare('SELECT claveproveedor,cantidad FROM productos WHERE claveproveedor=?');
			$stmt->execute(array($id));
	$pros = $stmt->fetch(PDO::FETCH_ASSOC);
		 $preciomx= 0;

	if($obj['price']>10):
	 $preciomx= (float)$obj['price'];
endif;



if(!empty($pros["claveproveedor"])):

				$ac=$GLOBALS['pdo']->prepare('UPDATE productos SET cantidad=?,precio=?,rin=?,estadociudad=? WHERE claveproveedor=?');
 $ac->execute(array($obj['fullInventory'],$preciomx,$obj['rim'],"1",$pros["claveproveedor"]));
				 $actu=  $ac->rowCount();



else:

		$insertar=$GLOBALS['pdo']->prepare('INSERT INTO productos (claveproveedor,productos,cantidad,proveedorp,categoria,idunicoinvetariado,precio,creado,rin,estadociudad,size) VALUES (?,?,?,?,?,?,?,?,?,?,?)');
		 	  $insertar->execute(array($id,''.$obj['brand'].' '.$obj['series'].'',$obj['fullInventory']??0,"Grupo Tersa",strtolower($obj['brand']),uniqid($obj['brand']."-"),"".$preciomx."",date("Y-m-d H:i:s"),$obj['rim'],"1",$obj['size']));
			 $GLOBALS['pdo']->lastInsertId();


   
  endif; 
  usleep(1);
}
}catch (PDOException $e) {
				 	throw new RuntimeException("[".$e->getCode()."] : ". $e->getMessage());
			 }



}
?>



