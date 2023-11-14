<?php 
if (strlen(session_id()) < 1) {	session_start(); }//Validamos si existe o no la sesión

require_once "../modelos/Articulo.php";

$articulo=new Articulo($_SESSION['idusuario'], $_SESSION['idempresa']);

$codigoInterno=isset($_POST["codigoInterno"])? limpiarCadena($_POST["codigoInterno"]):"";
$opcion1=isset($_POST["opcion1"])? limpiarCadena($_POST["opcion1"]):"";
$mes=isset($_POST["mes"])? limpiarCadena($_POST["mes"]):"";

switch ($_GET["op"]){	
	
	case 'kardexarticulo1':
		$rspta=$articulo->kardexArticulo($codigoInterno,$opcion1,$mes);
 		//Vamos a declarar un array
 		
	break;
	
}


?>