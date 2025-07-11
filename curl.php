<?
//username and password of account
$username = trim("YANTISSIMO");
$password = trim("2057013");


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
curl_setopt($ch, CURLOPT_URL, 'http://delago1.dyndns.org/INTERNA2/SisInv.asp')~;
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, "usr=".$username."&pssw=".$password);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_VERBOSE, true);

curl_setopt($ch, CURLOPT_COOKIESESSION, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'tmp/cookies.txt'); //
$answer = curl_exec($ch);

if (curl_error($ch)) {
    echo curl_error($ch);
}

//another request preserving the session

curl_setopt($ch, CURLOPT_URL, 'http://delago1.dyndns.org/INTERNA2/CteExtJ.asp');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, "usr=".$username."&pssw=".$password."&Marca=".$_GET["Marca"]."&Gama=&Busca=&Ancho=&Alto=-1&Rin=");
curl_setopt($ch, CURLOPT_COOKIEFILE, 'tmp/cookies.txt'); // set cookie file to given file
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
$answer = curl_exec($ch);
if (curl_error($ch)) {
    echo curl_error($ch);
}

$vera=array(0,1);



$output = extstr($answer,'e="1">+</font></div></td> -->',' </table><FONT SIZE="1"><BR></FONT></td>');
$output  = strstr($output , '<tr>'); 

$output='<table id="hello">'.$output;

$dom = new DOMDocument();
libxml_use_internal_errors(true);
$dom->loadHTML($output);
libxml_clear_errors();
$xpath = new DOMXpath($dom);
$table_rows = $xpath->query('//table[@id="hello"]/tr');

foreach($table_rows as $row => $tr) {
	
    foreach($tr->childNodes as $td) {
		if(trim($td->nodeValue) != ""):
        $data[$row][] = preg_replace('~[\r\n]+~', '', trim($td->nodeValue));
		endif;
    }
    $data[$row] = array_values($data[$row]);
}



if($data):
	echo '<table>   <tr>
		
		<td width="5%" bgcolor="#05437c"><div align="center" class="style2"><font face="Verdana" size="1">CLAVE</font></div></td>
        <td width="27%" bgcolor="#05437c"><div align="center" class="style2"><font size="1" face="Verdana">DESCRIPCIÓN</font></div></td>
        
        <td width="4%" bgcolor="#05437c"><div align="center" class="style2"><font face="Verdana" size="1">RINCON</font></div></td>
        
		<td width="4%" bgcolor="#05437c"><div align="center" class="style2"><font face="Verdana" size="1">CALVILLO</font></div></td>
        
		<td width="4%" bgcolor="#05437c"><div align="center" class="style2"><font face="Verdana" size="1">TOTAL</font></div></td>
        
        <td width="4%" bgcolor="#05437c"><div align="center" class="style2"><font face="Verdana" size="1">$</font></div></td>
		
		<td width="4%" bgcolor="#05437c"><div align="center" class="style2"><font face="Verdana" size="1">CEDIS JALISCO</font></div></td>
		
	
	
		
      <!--   <td width="3%" bgcolor="#05437c"><div align="center" class="style2"><font face="Verdana" size="1">+</font></div></td> -->
      </tr>';
	foreach($data as $ver){
			echo "<tr>";
$idq = extstr($ver[5],'<td><div align="center" class="style5"><font face="Verdana" color="#000000"><a href="SisInv.asp?','"><img src="imagenes/Mas.gif" border="0"/></a></font></div></td>'); 

	echo "<td>".$ver[0]." </td> <td>".$ver[1]."</td> <td>".$ver[2]."</td> <td>".$ver[3]."</td> <td>".$ver[4]."</td> <td><a href='https://distribuidor.yantissimo.com/dlago/curlprecio.php".$idq."'> Precio de lista</a></td> <td>".$ver[6]."</td>";
		
			echo "</tr>";

	}endif;
	
	echo"</table>";

//echo $output;

/*
$curl = extstr($output,'ace="Verdana" size="1">+</font></div></td>',' </table><FONT SIZE="1"><BR></FONT></td>');


if($curl):


	$i=1;
	while($i <= 4000):


$curl = strstr($curl, '<tr>'); 

$color = extstr($curl,'td bgcolor="','"'); 

$idq = extstr($curl,'<td bgcolor="'.$color.'"><div align="center" class="style5"><font face="Verdana" color="#000000">','</font></div></td>'); 

$tituloq = extstr($curl,'td bgcolor="'.$color.'"><span class="style5"><font face="Verdana" color="#000000">','</font></span></td>'); 


$rincon = extstr($curl,'<div align="center" class="style5"><font face="Verdana" color="#000000">','</font></div></td>'); 


$curl = strstr($curl, '</tr>');



	if($tituloq == "" && $idq == ""):
	break;
	else:
	
		 $array[] = array('id'=>$idq,'titulo'=>$tituloq,'rincon'=>$rincon,'calvillo'=>$calvillo);

	
	++$i;
	endif;
	endwhile;
	else: echo '';
	endif;
			echo $a;

	if($array):
	print_r($a);
	echo "<table>";
	foreach($array as $ver){
			echo "<tr>";

	echo "<td>".$ver["id"]." </td> <td>".$ver["titulo"]."</td> <td>".$ver["rincon"]."</td> <td>".$ver["calvillo"]."</td>";
		
			echo "</tr>";

	}endif;
	
	echo"	</table>";

	echo $curla;*/ ?>