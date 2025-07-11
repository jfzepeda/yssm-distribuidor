<? include_once('../includes/config.php');

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










function LeerHtml($srch)
	{
	$ch = curl_init($srch);
	curl_setopt($ch, CURLOPT_URL,$srch);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
curl_setopt($ch, CURLOPT_TIMEOUT, 4);
	$data=curl_exec ($ch);
	curl_close ($ch);
	return $data;
	}





$ver=LeerHtml("https://distribuidores.carmotion.com.mx/wp-admin/admin-ajax.php?action=wp_ajax_ninja_tables_public_action&table_id=177&target_action=get-all-data&default_sorting=old_first&ninja_table_public_nonce=7eaf5b9566&chunk_number=0%27");
$v = json_decode($ver,true);

$ver=LeerHtml("https://distribuidores.carmotion.com.mx/wp-admin/admin-ajax.php?action=wp_ajax_ninja_tables_public_action&table_id=177&target_action=get-all-data&default_sorting=old_first&skip_rows=0&limit_rows=0&chunk_number=1&ninja_table_public_nonce=a7195de6da");
$v2 = json_decode($ver,true);


$combinedArray = array_merge($v, $v2);



function separarUltimosDosDigitosRegex($numero,$id) {
	$numero = str_replace(array("r","R"), "", $numero);

    // Utilizar regex para separar el número en grupos
    preg_match('/^(\d+)(\d{2})/', $numero, $matches);
    
    // Si hay coincidencias
    if (count($matches) == 3) {
        // Devolver el primer grupo (resto del número) y el segundo grupo (últimos dos dígitos)
        return array($matches[1], $matches[2],$id);
    } else {
        return "Número inválido";
    }
}

if(!empty($combinedArray)) {

	try{
		
			$ac=$GLOBALS['pdo']->prepare('DELETE FROM productos WHERE proveedorp = ?');
 $ac->execute(array("Carmotion"));
 foreach($combinedArray as $obj){
	$vermarca=explode(" ",$obj["value"]["b"]);

	 
$valor_sin_simbolo = str_replace(array('$', ','), '', $obj["value"]["d"]);

	$id= "cm-".$obj["value"]["___id___"];
	
$extra=separarUltimosDosDigitosRegex($obj["value"]["a"],$id);	


		 
		 $preciomx= 0;
	if($valor_sin_simbolo>10):
	 $preciomx= (float)$valor_sin_simbolo;
endif;


		$insertar=$GLOBALS['pdo']->prepare('INSERT INTO productos (claveproveedor,productos,cantidad,proveedorp,categoria,idunicoinvetariado,precio,creado,rin,estadociudad,size) VALUES (?,?,?,?,?,?,?,?,?,?,?)');
		 	  $insertar->execute(array($id,$obj["value"]["b"],$obj["value"]["c"]??0,"Carmotion",strtolower($vermarca[1]),uniqid(),"".$preciomx."",date("Y-m-d H:i:s"),$extra[1],"1",$extra[0]));
			 $GLOBALS['pdo']->lastInsertId();

  usleep(1);
} echo "ok";

}catch (PDOException $e) {
				 	throw new RuntimeException("[".$e->getCode()."] : ". $e->getMessage());
			 }



}
?>



