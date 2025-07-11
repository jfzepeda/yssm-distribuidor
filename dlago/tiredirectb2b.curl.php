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

$datos=LeerHtml("https://tiredirectb2b.com.mx/wbsTDApp/General/datas?UserID=2373&Password=AJU15019830GJ%7");


$objs = json_decode($datos,true);

if(!empty($objs)) {
	print_r($objs['objects']['ResponseRow']);


	$ac=$GLOBALS['pdo']->prepare('DELETE FROM productos WHERE proveedorp = ?');
 $ac->execute(array("Tire Direct"));

	try{
 foreach($objs['objects']['ResponseRow'] as $obj){
	 
	 	$stmt=$GLOBALS['pdo']->prepare('SELECT claveproveedor,cantidad FROM productos WHERE claveproveedor=?');
			$stmt->execute(array($obj['SKU']));
	$pros = $stmt->fetch(PDO::FETCH_ASSOC);
	 $preciomx= (float) $obj['FS']* (float)$obj['TC'];

//if(!empty($pros["claveproveedor"])):
//$ac=$GLOBALS['pdo']->prepare('UPDATE productos SET cantidad=?,precio=? WHERE claveproveedor=? and (cantidad<>?  or precio<>?)');
	//	 	 $ac->execute(array($obj['Existencia'],$preciomx,$obj['SKU'],$obj['Existencia'],$preciomx));
			 	//		 $actu=  $ac->rowCount();
				
		/*		$ac=$GLOBALS['pdo']->prepare('UPDATE productos SET cantidad=?,precio=?,rin=?,estadociudad=? WHERE claveproveedor=?');
 $ac->execute(array($obj['Existencia'],round($preciomx),$obj['Rin'],"1",$pros["claveproveedor"]));
				 $actu=  $ac->rowCount();*/



//else:

		$insertar=$GLOBALS['pdo']->prepare('INSERT INTO productos (claveproveedor,productos,cantidad,proveedorp,categoria,idunicoinvetariado,precio,creado,rin,estadociudad) VALUES (?,?,?,?,?,?,?,?,?,?)');
		 	  $insertar->execute(array($obj['SKU'],''.$obj['Marca'].' '.$obj['Modelo'].' '.$obj['Descripcion'].'',$obj['Existencia'],"Tire Direct",strtolower($obj['Marca']),uniqid($obj['Marca']."-"),"".round($preciomx)."",date("Y-m-d H:i:s"),$obj['Rin'],"1"));
			 $GLOBALS['pdo']->lastInsertId();


   
  //endif; 
  usleep(1);
}
}catch (PDOException $e) {
				 	throw new RuntimeException("[".$e->getCode()."] : ". $e->getMessage());
			 }



}
		

/*
	try{
		
			$stmt=$GLOBALS['pdo']->prepare('SELECT claveproveedor,cantidad FROM productos WHERE claveproveedor=?');
			$stmt->execute(array($ver[0]));
	$pros = $stmt->fetch(PDO::FETCH_ASSOC);


		$ac=$GLOBALS['pdo']->prepare('UPDATE productos SET rincondlago=?,calvillodlago=?,cantidad=?,cedisjaliscodlago=? WHERE claveproveedor=? and cantidad<>?');
		 	 $ac->execute(array($ver[2],$ver[3],$ver[4],$ver[6],$ver[0],$ver[4]));
			 			 $actu=  $ac->rowCount();

		$insertar=$GLOBALS['pdo']->prepare('INSERT INTO productos (claveproveedor,productos,rincondlago,calvillodlago,cantidad,cedisjaliscodlago,proveedorp,categoria,idunicoinvetariado,creado) VALUES (?,?,?,?,?,?,?,?,?,?)');
		 	  $insertar->execute(array($ver[0],$ver[1],$ver[2],$ver[3],$ver[4],$ver[6],"D'LAGO",strtolower($_GET["Marca"]),uniqid($_GET["Marca"]."-"),date("Y-m-d H:i:s")));
			 $GLOBALS['pdo']->lastInsertId();
	






	
			
			
			
			 
			 }catch (PDOException $e) {
				 	throw new RuntimeException("[".$e->getCode()."] : ". $e->getMessage());
			 }

*/
 ?>
	

