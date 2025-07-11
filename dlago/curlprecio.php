<? include_once('../includes/config.php');
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://delago1.dyndns.org/INTERNA2/PreciosCteExterno.asp?clv='.$_GET["cvl"].'&usr=YANTISSIMO');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_VERBOSE, true);

curl_setopt($ch, CURLOPT_COOKIESESSION, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'tmp/cookies.txt'); //
$answer = curl_exec($ch);

if (curl_error($ch)) {
    echo curl_error($ch);
}
$output  = strstr($answer, '<table width="660" border="1" cellspacing="0" cellpadding="0">'); 
$output  = strstr($output, '<tr>'); 
$output  = strstr($output, '</font></div></td>-->'); 
$output  = strstr($output, '</tr>'); 

$output='<table id="hellos">'.$output;


$dom = new DOMDocument();
libxml_use_internal_errors(true);
$dom->loadHTML($output);
libxml_clear_errors();
$xpath = new DOMXpath($dom);
$table_rows = $xpath->query('//table[@id="hellos"]/tr');
foreach($table_rows as $row => $tr) {
	
    foreach($tr->childNodes as $td) {
				if(trim($td->nodeValue) != ""):

        $data[$row][] = preg_replace('~[\r\n]+~', '', trim($td->nodeValue));
		
		endif;
    }
    $data[$row] = array_values($data[$row]);
}

			echo "<table>";

	foreach($data as $ver){
			echo "<tr>";
	try{
$textoRemplazado =  preg_replace("/[^0-9.]/", "", $ver[1]);

	echo "<td>".$ver[0]." </td> <td>".$textoRemplazado."---".$ver[1]."</td>";
		$ac=$GLOBALS['pdo']->prepare('UPDATE productos SET precio=? WHERE claveproveedor=?');
		 	 $ac->execute(array($textoRemplazado,$_GET["cvl"]));
			 			 $actu=  $ac->rowCount();
			echo "</tr>";
		 }catch (PDOException $e) {
				 	throw new RuntimeException("[".$e->getCode()."] : ". $e->getMessage());
			 }
	}
	
	echo"</table>";
?><script>
setTimeout(function(){ 

   window.open('','_parent',''); 
   window.close(); 
},1000);
</script>