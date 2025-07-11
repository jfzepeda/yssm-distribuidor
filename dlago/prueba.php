<? include_once('../includes/config.php');
print_r($_SESSION["porcentajes"]);
	$preciobd="400,94";
$iva= "1.16";
$calculo= $preciobd*$iva;
$precioacondicion= round($calculo, 2);
$porcentaje=0;
if( !function_exists('ceiling') )
{
    function ceiling($number, $significance = 1)
    {
        return ( is_numeric($number) && is_numeric($significance) ) ? (ceil($number/$significance)*$significance) : false;
    }
}


	foreach($_SESSION["porcentajes"] as $key => $v){
	
if($precioacondicion >$v["inicio"] and $precioacondicion<$v["fin"]):
$precio=$precioacondicion*$v["porcentaje"];
break; endif;			
			
		}
		
		$precioredondeado = ceiling($precio,10);
		
		echo $precioredondeado;
		
		
		$rin="13";
			foreach($_SESSION["rines"] as $key => $r){


if($rin===$r["rin"]):
$precio=$precioredondeado+$r["precio"];
break; endif;			
}
		echo "<br>";
		echo $precio;
