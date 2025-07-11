<?php //animenoseichi.com hecho por toño el manco 2024 todos los derechos reservados
 use clases\formcontrol\accion as agregarform;
use clases\controles\general as g;
use clases\controles\borrar;
use clases\controles\retornar as r;
use clases\controles\like;
use clases\controles\editar as e;
use clases\controles\calculadora as cal;
use clases\controles\ordenin as o;
use ajax\iniciarsesion as login;
 use clases\formcontrol\limpiar as l;
 use ajax\datatable;
use clases\imagen as up;
use clases\controles\ordenes as orden;
use clases\controles\datos;
use clases\controles\actualizard;
use clases\controles\transacciones;
use clases\controles\tracerrar;
use clases\controles\contar;
use clases\controles\consultasjoin;
use Dompdf\Dompdf;
use clases\controles\agregar;
//use clases\controles\perfil_imagen;

 //$img= new perfil_imagen($_SESSION["genero"],"".$_SESSION["img"]."");
     // $imgs= $img->imagenget();


 ini_set('opcache.enable', 0);

 include_once('includes/config.php');


 //clases globales
	include_once('clases/formcontrol/accion.php');
	include_once('clases/formcontrol/limpiar.php');
include_once('clases/controles/general.php');
//aca data table

	include_once('ajax/data.php');
//img clase
include('clases/src/class.upload.php');

include_once('clases/upload.php');

// Include router class
include('clases/rutas.php');

//session_destroy();


Route::add(panel,function(){
	
	include_once('clases/c.index.php');


 include_once('contenido/dash/index.php');

});



  Route::add(''.panel.'usuarios/([0-9]*)',function($id){

 
  include_once('contenido/dash/usuarios/index.php');

  },'get');
  
  Route::add(''.panel.'usuarios/agregar',function(){
$stmt=$GLOBALS['pdo']->prepare('SELECT * FROM sucursales order by id asc');
				$stmt->execute();
		$r = $stmt->fetchAll(PDO::FETCH_ASSOC);
  include_once('contenido/dash/usuarios/agregar.php');

  },'get');


  Route::add(''.panel.'distribuidor/([0-9]*)',function($id){

 
  include_once('contenido/dash/distribuidor/index.php');

  },'get');
  
  
 Route::add(''.panel.'distribuidor/ajax/buscar/',function(){
		 
 include_once('clases/c.buscar.distribuidor.ajax.php');


 },'post'); 
  
    
  Route::add(''.panel.'distribuidor/ver/([0-9]*)/',function(){
 
 
 include_once('clases/c.control.verusuario.php');
  include_once('contenido/dash/distribuidor/ver.php');

  },'get');
  
    Route::add(''.panel.'ajax/distribuidor/visible/',function(){

include_once('clases/c.verificacionUsuarios.php');

  },'post');
  
  
  
  
Route::add(''.panel.'login/',function(){
	$_SESSION["site"]["url"]="/../../panel/";

 include_once(__DIR__.'/contenido/login/login.php');

});

Route::add('/salir/',function(){
 include_once('logout.php');
	
});


Route::add('/login/ajax/',function(){
 require(__DIR__.'/ajax/iniciarsesion.php');

  	include_once('clases/c.login.php');

},'post');





Route::add(panel.'cotizaciones/',function(){
include_once('contenido/dash/cotizaciones/index.php');
});

Route::add(panel.'cotizaciones/editar/',function(){
  include_once('contenido/dash/cotizaciones/editar.php');
  });




Route::add(panel.'cotizaciones/crear/',function(){
	$time=uniqid();
	
include_once('contenido/dash/cotizaciones/ver.php');

});



Route::add(panel.'cotizaciones/ajax/',function(){
include_once('clases/c.cotizaciones.ajax.php');
},'post');


Route::add(panel.'cotizaciones/ajax/buscar/',function(){
include_once('clases/c.panel.cotizaciones.buscar.ajax.php');
},'post');


Route::add(panel.'cotizaciones/ajax/calculadora/',function(){
include_once('clases/cotizaciones/calculos.php');
},'post');


Route::add(panel.'ajax/cotizaciones/borrar/',function(){
include_once('clases/c.panel.cotizacion.borrar.php');
},'post');

Route::add(panel.'cotizaciones/pdf/([0-9]*)',function($id){

include_once('contenido/dash/cotizaciones/pdf.php');
},'get');


Route::add(panel.'cotizaciones/ajax/form/agregar/',function(){
include_once('clases/c.cotizaciones.ajax.form.agregar.php');
},'post');

Route::add(panel.'usuarios/ajax/form/',function(){
include_once('clases/c.usuario.ajax.form.agregar.php');
},'post');

Route::add(panel.'usuarios/ajax/borrar/',function(){
include_once('clases/c.panel.usuario.borrar.php');
},'post');


Route::add('/curl',function(){
 include_once('curl.php');


},'post');


//formula
Route::add(panel.'configuracion/formula',function(){


include_once('contenido/dash/configuracion/index.php');
},'get');

Route::add(panel.'configuracion/formula/ver/',function(){

	include_once('clases/c.m.extraerporcentaje.php');

},'post');

Route::add(panel.'configuracion/formula/addform/',function(){
include_once('contenido/dash/configuracion/addform.php');
},'post');

Route::add(panel.'configuracion/formula/guardar/',function(){
	include_once('clases/c.panel.formula.guardar.php');
},'post');

Route::add(panel.'configuracion/formula/borrar/',function(){
include_once('clases/c.panel.formula.borrar.php');
},'post');

//rines

Route::add(panel.'configuracion/rines',function(){
include_once('contenido/dash/configuracion/rines.php');
},'get');

Route::add(panel.'configuracion/rines/ver/',function(){
include_once('clases/c.m.extraerrines.php');
},'post');

Route::add(panel.'configuracion/rines/addform/',function(){
include_once('contenido/dash/configuracion/addrines.php');
},'post');

Route::add(panel.'configuracion/rines/guardar/',function(){
	include_once('clases/configuraciones/c.panel.rines.guardar.php');
},'post');

Route::add(panel.'configuracion/rines/borrar/',function(){
include_once('clases/configuraciones/c.panel.rines.borrar.php');
},'post');

Route::add(panel.'configuracion/comisiones',function(){
include_once('contenido/dash/configuracion/comisiones.php');
},'get');

Route::add(panel.'configuracion/comisiones/ver/',function(){
include_once('clases/c.m.extraercomisiones.php');
},'post');

Route::add(panel.'configuracion/comisiones/guardar/',function(){
	include_once('clases/c.panel.comisiones.guardar.php');
},'post');

Route::add(panel.'configuracion/datos',function(){
include_once('contenido/dash/configuracion/datos.php');
},'get');
Route::add(''.panel.'configuracion/datos/subir/ajax/',function(){
   include_once('clases/c.panel.datosbd.php');
},'post');
Route::add(''.panel.'configuracion/datosexcel/ajax/',function(){
   include_once('clases/c.datos.excel.php');
},'post');


Route::add(panel.'configuracion/promociones',function(){
	   include_once('clases/returnconfiguracion.php');

include_once('contenido/dash/configuracion/promociones.php');
},'get');


Route::add(panel.'configuracion/promociones/ajax/',function(){
	   include_once('clases/configuraciones/returnconfiguracionajax.php');

},'post');

Route::add(panel.'configuracion/promociones/ver/',function(){
include_once('clases/configuraciones/c.m.extrerpromociones.php');
},'post');

Route::add(panel.'configuracion/promociones/guardar/',function(){

	include_once('clases/configuraciones/c.panel.promociones.guardar.php');

},'post');

Route::add(panel.'configuracion/promociones/borrar/',function(){
include_once('clases/configuraciones/c.panel.promociones.borrar.php');
},'post');



Route::add(panel.'configuracion/proveedores',function(){

include_once('contenido/dash/configuracion/proveedores.php');
},'get');


Route::add(panel.'configuracion/proveedores/ver/',function(){
include_once('clases/configuraciones/c.m.proveedores.php');
},'post');

Route::add(panel.'configuracion/proveedores/guardar/',function(){ 
	include_once('clases/configuraciones/c.panel.proveedores.guardar.php');
},'post');


// productos

Route::add(panel.'productos/([0-9]*)',function($id){

include_once('contenido/dash/productos/index.php');
},'get');

Route::add(panel.'productos/agregar',function(){
   include_once('clases/returnprod.php');

include_once('contenido/dash/productos/agregar.php');
},'get');
Route::add(panel.'productos/ajax/search/',function(){
include_once('clases/datatableproductos.php');
},'post');


Route::add(panel.'productos/ajax/form/',function(){
include_once('clases/c.productos.ajax.form.agregar.php');
},'post');

Route::add(panel.'productos/editar/([0-9]*)',function($id){
   include_once('clases/returnprod.php');

include_once('contenido/dash/productos/agregar.php');
},'get');

Route::add(panel.'salir/',function(){
 include_once('logout.php');
	
});

//perfil
Route::add(''.panel.'perfil/',function(){
		define("user",'tb_user');
include_once('clases/c.perfil.php');

 include_once('contenido/dash/perfil/perfil.php');

});

//distribuidor


  Route::add(''.distribuidor.'',function(){
 
 
  include_once('contenido/dash/distweb/index.php');

  },'get');


Route::pathNotFound(function($path) {

  header('HTTP/1.0 404 Not Found');
  echo 'Error 404 :-(<br>';
  echo 'The requested path "'.$path.'" was not found!';
});


Route::methodNotAllowed(function($path, $method) {

  header('HTTP/1.0 405 Method Not Allowed');
  echo 'Error 405 :-(<br>';
  echo 'The requested path "'.$path.'" exists. But the request method "'.$method.'" is not allowed on this path!';
});
Route::run('/');


 
?> 