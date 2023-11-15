<?php 
if (strlen(session_id()) < 1) {	session_start(); }//Validamos si existe o no la sesión

require_once "../modelos/Marca.php";

$marca = new Marca();

$idmarca		= isset($_POST["idmarca"])? limpiarCadena($_POST["idmarca"]):"";
$nombre_marca	= isset($_POST["nombre_marca"])? limpiarCadena($_POST["nombre_marca"]):"";

switch ($_GET["op"]){

	case 'guardar_y_editar_marca':
		$val_marca = $marca->validar_marca($nombre_marca); #echo json_encode($val_marca); die;
		if ($val_marca) {
			echo "Marca: <b>$nombre_marca</b> ya registrada";
		} else {
			if (empty($idmarca)){
				$rspta = $marca->crear_marca($nombre_marca);
				echo $rspta ;
			}	else {
				$rspta = $marca->editar_marca($idmarca,$nombre_marca);
				echo $rspta ;
			}
		}
	break;


	case 'desactivar':
		$rspta = $marca->desactivar_marca($idmarca);
 		echo $rspta ? "Mesa Desactivada" : "Mesa no se puede desactivar";
 		break;
	break;

	case 'activar':
		$rspta = $marca->activar_marca($idmarca);
 		echo $rspta ? "Mesa activada" : "Mesa no se puede activar";
 		break;
	break;

	case 'mostrar_editar':
		$rspta = $marca->mostrar_editar($idmarca);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
 		break;
	break;

	case 'listar_tabla_marca':
		$rspta = $marca->listar_tabla_principal();
 		//Vamos a declarar un array
 		$data= Array(); $cont = 1;

 		while ($reg=$rspta->fetch_object()){
			$data[] = array(
				"0" => $cont++,
				"1" => ($reg->estado) ? '<button class="btn btn-icon btn-sm btn-info" onclick="mostrar_editar_marca(' . $reg->idmarca . ')"><i class="ri-edit-line"></i></button>' .
					' <button class="btn btn-icon btn-sm btn-danger" onclick="desactivar_marca(' . $reg->idmarca . ')"><i class="ri-delete-bin-line"></i></button>' :
					'<button class="btn btn-icon btn-sm btn-info" onclick="mostrar_marca(' . $reg->idmarca . ')"><i class="ri-edit-line"></i></button>' .
					' <button class="btn btn-icon btn-sm btn-success" onclick="activar_marca(' . $reg->idmarca . ')"><i class="ri-check-double-line"></i></button>',
				"2" => $reg->descripcion,
				"3" => ($reg->estado) ? '<span class="badge bg-success-transparent"><i class="ri-check-fill align-middle me-1"></i>Activo</span>' :
					'<span class="badge bg-danger-transparent"><i class="ri-close-fill align-middle me-1"></i>Inhabilitado</span>'
			);
 		}
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
 		echo json_encode($results);

	break;

	default: 
		$rspta = 'Te has confundido en escribir en el <b>swich.</b>'; echo $rspta; 
	break;
}
?>