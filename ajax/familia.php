<?php
if (strlen(session_id()) < 1) {	session_start(); }//Validamos si existe o no la sesión

require_once "../modelos/Familia.php";

$familia = new Familia();

$idfamilia = isset($_POST["idfamilia"]) ? limpiarCadena($_POST["idfamilia"]) : "";
$idalmacen = isset($_POST["idalmacen"]) ? limpiarCadena($_POST["idalmacen"]) : "";
$nombrea = isset($_POST["nombrea"]) ? limpiarCadena($_POST["nombrea"]) : "";
$nombrec = isset($_POST["nombrec"]) ? limpiarCadena($_POST["nombrec"]) : "";
$estado = isset($_POST["estado"]) ? limpiarCadena($_POST["estado"]) : "";
$direc = isset($_POST["direc"]) ? limpiarCadena($_POST["direc"]) : "";
$idempresa = isset($_POST["idempresa2"]) ? limpiarCadena($_POST["idempresa2"]) : "";


$nombreu = isset($_POST["nombreu"]) ? limpiarCadena($_POST["nombreu"]) : "";
$abre = isset($_POST["abre"]) ? limpiarCadena($_POST["abre"]) : "";
$equivalencia = isset($_POST["equivalencia2"]) ? limpiarCadena($_POST["equivalencia2"]) : "";



switch ($_GET["op"]) {

	case 'guardar_y_editar_familia':
		$validarCategoria = $familia->validarCategoria($nombrec); #echo json_encode($validarCategoria); die;
		if ($validarCategoria) {
			echo "Categoría; <b>$nombrec</b> ya registrada";
		} else {
			if (empty($idfamilia)) {
				$rspta = $familia->insertarCategoria($nombrec);
				echo $rspta ;
			} else {
				$rspta = $familia->editar($idfamilia, $nombrec);
				echo $rspta ;
			}
		}
	break;

	case 'guardaryeditaralmacen':
		if (empty($idalmacen)) {
			$rspta = $familia->insertaralmacen($nombrea, $direc, $idempresa);
			echo $rspta ? "Almacen registrado" : "Almacen no se pudo registrar";
		} else {
			$rspta = $familia->editar($idalmacen, $nombrea);
			echo $rspta ? "Familia actualizada" : "Familia no se pudo actualizar";
		}
	break;

	case 'guardaryeditarUmedida':
		if (empty($idfamilia)) {
			$rspta = $familia->insertaraunidad($nombreu, $abre, $equivalencia);
			echo $rspta ? "Unidad registrada" : "Unidad no se pudo registrar";
		} else {
			$rspta = $familia->editar($idfamilia, $nombre);
			echo $rspta ? "Unidad actualizada" : "Unidad no se pudo actualizar";
		}
	break;

	case 'desactivar':
		$rspta = $familia->desactivar($idfamilia);
		echo $rspta ? "Categoria Desactivada" : "Categoria no se puede desactivar";
	break;

	case 'activar':
		$rspta = $familia->activar($idfamilia);
		echo $rspta ? "Categoria activada" : "Categoria no se puede activar";
	break;

	case 'mostrar':
		$rspta = $familia->mostrar($idfamilia);
		//Codificar el resultado utilizando json
		echo json_encode($rspta);
	break;

	case 'listar_tabla_familia':
		$rspta = $familia->listar_tabla_familia();
		//Vamos a declarar un array
		$data = array(); $cont = 1;

		while ($reg = $rspta->fetch_object()) {
			$data[] = array(
				"0" => $cont++,
				"1" => ($reg->estado) ? '<button class="btn btn-icon btn-sm btn-info" onclick="mostrar_editar_familia(' . $reg->idfamilia . ')"><i class="ri-edit-line"></i></button>' .
					' <button class="btn btn-icon btn-sm btn-danger" onclick="desactivar_familia(' . $reg->idfamilia . ')"><i class="ri-delete-bin-line"></i></button>' :
					'<button class="btn btn-icon btn-sm btn-info" onclick="mostrar_familia(' . $reg->idfamilia . ')"><i class="ri-edit-line"></i></button>' .
					' <button class="btn btn-icon btn-sm btn-success" onclick="activar_familia(' . $reg->idfamilia . ')"><i class="ri-check-double-line"></i></button>',
				"2" => $reg->descripcion,
				"3" => ($reg->estado) ? '<span class="badge bg-success-transparent"><i class="ri-check-fill align-middle me-1"></i>Activo</span>' :
					'<span class="badge bg-danger-transparent"><i class="ri-close-fill align-middle me-1"></i>Inhabilitado</span>'
			);
		}
		$results = array(
			"sEcho" => 1,	//Información para el datatables
			"iTotalRecords" => count($data),	//enviamos el total registros al datatable
			"iTotalDisplayRecords" => count($data),	//enviamos el total registros a visualizar
			"aaData" => $data
		);
		echo json_encode($results);

	break;

	default: 
		$rspta = 'Te has confundido en escribir en el <b>swich.</b>'; echo $rspta; 
	break;
}
?>