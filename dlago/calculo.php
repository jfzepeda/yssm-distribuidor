<?


if( !function_exists('ceiling') )
{
    function ceiling($number, $significance = 1)
    {
        return ( is_numeric($number) && is_numeric($significance) ) ? (ceil($number/$significance)*$significance) : false;
    }
}


$preciobd="1961.63";
$iva= "1.16";
$calculo= $preciobd*$iva;
$precioacondicion= round($calculo, 2);
$porcentaje=0;
$preciocondicionif = $precioacondicion;

//if
if($precioacondicion >0 and $precioacondicion<500):
$porcentaje="1.16";
$preciocondicionif=$preciocondicionif*$porcentaje;

elseif($precioacondicion >500 and $precioacondicion<700):
$porcentaje="1.26";
$preciocondicionif=$preciocondicionif*$porcentaje;

elseif($precioacondicion >700 and $precioacondicion<800):
$porcentaje="1.27";
$preciocondicionif=$preciocondicionif*$porcentaje;

elseif($precioacondicion >800 and $precioacondicion<1200):

$porcentaje="1.28";
$preciocondicionif=$preciocondicionif*$porcentaje;

elseif($precioacondicion >1200 and $precioacondicion<1700):

$porcentaje="1.28";
$preciocondicionif=$preciocondicionif*$porcentaje;

elseif($precioacondicion >1700 and $precioacondicion<1800):
$porcentaje="1.28";
$preciocondicionif=$preciocondicionif*$porcentaje;

elseif($precioacondicion >1800 and $precioacondicion<2000):
$porcentaje="1.28";
$preciocondicionif=$preciocondicionif*$porcentaje;

elseif($precioacondicion >2000 and $precioacondicion<2200):
$porcentaje="1.28";
$preciocondicionif=$preciocondicionif*$porcentaje;

elseif($precioacondicion >2200 and $precioacondicion<2500):
$porcentaje="1.27";

$preciocondicionif=$preciocondicionif*$porcentaje;

elseif($precioacondicion >2500 and $precioacondicion<2900):
$porcentaje="1.25";
$preciocondicionif=$preciocondicionif*$porcentaje;

elseif($precioacondicion >2900 and $precioacondicion<3600):
$porcentaje="1.23";
$preciocondicionif=$preciocondicionif*$porcentaje;

elseif($precioacondicion >3600 and $precioacondicion<4000):
$porcentaje="1.22";
$preciocondicionif=$preciocondicionif*$porcentaje;

elseif($precioacondicion >4000 and $precioacondicion<5000):
$porcentaje="1.21";
$preciocondicionif=$preciocondicionif*$porcentaje;

elseif($precioacondicion >5000 and $precioacondicion<6000):
$porcentaje="1.20";
$preciocondicionif=$preciocondicionif*$porcentaje;

else:
$porcentaje="1.17";
$preciocondicionif=$preciocondicionif*$porcentaje;

endif;

echo ceiling($preciocondicionif,10);