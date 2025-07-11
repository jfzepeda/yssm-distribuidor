<? include_once('../includes/config.php');
$tipoc="Camion";
/*
if($_GET["tipo"]==1):
$tipoc="Auto /Camion";

endif; */
// &Tip=".$_GET["tipo"]."
//username and password of account
$username = trim("colima");
$password = trim("colima#1971");


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
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://m.radialllantas.com/login');
curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
    'user' => $username,
    'password' => $password
))); curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_COOKIESESSION, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies1.txt');
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'X-Requested-With: XMLHttpRequest',
	'Origin: https://m.radialllantas.com', 
    'Referer: https://m.radialllantas.com/', 
));
$answer0 = curl_exec($ch);

if (curl_error($ch)) {
    echo curl_error($ch);
}

//another request preserving the session

curl_setopt($ch, CURLOPT_URL, 'https://m.radialllantas.com/buscar');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, "marca=&linea=&rin=&serie=&ancho=&rango=&codigo=&buscar=1");
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies1.txt'); 
$answer = curl_exec($ch);
if (curl_error($ch)) {
    echo curl_error($ch);
}




//echo $answer;


$output=$answer;

$dom = new DOMDocument();
libxml_use_internal_errors(true);
$dom->loadHTML($output);
libxml_clear_errors();
$xpath = new DOMXpath($dom);
$table_rows = $xpath->query('//table[@id="tabla-archivos"]/tbody/tr');
foreach($table_rows as $row => $tr) {
	
    foreach($tr->childNodes as $td) {
		if(trim($td->nodeValue) != ""):
        $data[$row][] = preg_replace('~[\r\n]+~', '', trim($td->nodeValue));
		endif;
    }
    $data[$row] = array_values($data[$row]);
}


if(!empty($data)):
	echo '<table>   <tr>
		
		<th>Línea</th>
								<th>Código</th>
								<th>Descripción</th>
								<th>Marca</th><th>colima</th><th>Precio</th>
								<th>Ancho</th>
								<th>Serie</th>
								<th>Rin</th>
								<th>Rango</th>
      </tr>';
	  $count=1;
	foreach($data as $ver){
		$versum=$count*2000;
			echo "<tr>";
    // Obtener la última palabra sacar categoria

 $palabras = explode(' ', $ver[0]);
        $ultima_palabra = end($palabras);
	 $preciomx= 0;
	 //
	 
$cadena_limpia = preg_replace('/[^a-zA-Z0-9\/\(\)\s]/', '', $ver[2]);


$valor_sin_simbolo = str_replace(array('$', ','), '', $ver[5]);

	if($valor_sin_simbolo>10):
	 $preciomx= (float)$valor_sin_simbolo;
endif;

	$id= "rd-".$ver[1];

	echo "<td>".$ultima_palabra." </td> <td>".$ver[1]." </td> <td>".$cadena_limpia."</td> <td>".$ver[3]."</td> <td>".$ver[4]."</td>
	<td>".$valor_sin_simbolo."</td> <td>".$ver[6]."</td><td>".$ver[7]."</td><td>".$ver[8]."</td><td>".$ver[9]."</td>";

	
  			echo "</tr>";
	try{
		
		$stmt=$GLOBALS['pdo']->prepare('SELECT claveproveedor,cantidad FROM productos WHERE claveproveedor=?');
			$stmt->execute(array($id));
	$pros = $stmt->fetch(PDO::FETCH_ASSOC);




if(!empty($pros["claveproveedor"])):


		$ac=$GLOBALS['pdo']->prepare('UPDATE productos SET cantidad=?,precio=?,rin=?,estadociudad=? WHERE claveproveedor=?');
 $ac->execute(array($ver[4],$preciomx,$ver[8]??0,"1",$pros["claveproveedor"]));
			
else:
		$insertar=$GLOBALS['pdo']->prepare('INSERT INTO productos (claveproveedor,productos,cantidad,proveedorp,categoria,idunicoinvetariado,precio,creado,rin,estadociudad,size) VALUES (?,?,?,?,?,?,?,?,?,?,?)');
		 	  $insertar->execute(array($id,$cadena_limpia,$ver[4]??0,"Radial_llanta",strtolower($ultima_palabra),uniqid($ultima_palabra."-"),"".$preciomx."",date("Y-m-d H:i:s"),$ver[8],"1","".$ver[6]."".$ver[7].""));
			 $GLOBALS['pdo']->lastInsertId();

endif;	

	
			usleep(2);
			
			
			
			 
			 }catch (PDOException $e) {
				 	throw new RuntimeException("[".$e->getCode()."] : ". $e->getMessage());
			 }
$count++;
	}endif;
	

