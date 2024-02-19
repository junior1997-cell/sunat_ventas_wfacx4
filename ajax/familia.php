<?php

require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;

ob_start(); //Activamos el almacenamiento del Buffer

if (strlen(session_id()) < 1) {
  session_start();
} //Validamos si existe o no la sesión

require_once "../modelos/Familia.php";

$familia = new Familia();

date_default_timezone_set('America/Lima');
$date_now = date("d_m_Y__h_i_s_A");

// ══════════════════════════════════════ FAMILIA ══════════════════════════════════════
$idfamilia   = isset($_POST["idfamilia"]) ? limpiarCadena($_POST["idfamilia"]) : "";
$nombrec     = isset($_POST["nombrec"]) ? limpiarCadena($_POST["nombrec"]) : "";

// ══════════════════════════════════════ IMPORTAR FAMILIA ══════════════════════════════════════

// ══════════════════════════════════════ ALMACEN ══════════════════════════════════════
$idalmacen   = isset($_POST["idalmacen"]) ? limpiarCadena($_POST["idalmacen"]) : "";
$nombrea     = isset($_POST["nombrea"]) ? limpiarCadena($_POST["nombrea"]) : "";
$estado     = isset($_POST["estado"]) ? limpiarCadena($_POST["estado"]) : "";
$direc       = isset($_POST["direc"]) ? limpiarCadena($_POST["direc"]) : "";
$idempresa   = isset($_POST["idempresa2"]) ? limpiarCadena($_POST["idempresa2"]) : "";

// ══════════════════════════════════════ UNIDAD MEDIDA ══════════════════════════════════════
$nombreu     = isset($_POST["nombreu"]) ? limpiarCadena($_POST["nombreu"]) : "";
$abre       = isset($_POST["abre"]) ? limpiarCadena($_POST["abre"]) : "";
$equivalencia = isset($_POST["equivalencia2"]) ? limpiarCadena($_POST["equivalencia2"]) : "";

switch ($_GET["op"]) {

  case 'guardar_y_editar_familia':
    $validar = $familia->validarCategoria($nombrec);

    if (empty($validar["data"])) {

      if (empty($idfamilia)) {
        $rspta = $familia->insertarCategoria($nombrec);
        echo json_encode(['status' => 'registrado', 'data' => $rspta]);
      } else {
        $rspta = $familia->editar($idfamilia, $nombrec);
        echo json_encode(['status' => 'modificado', 'data' => $rspta]);
      }
    } else {
      $info_repetida = '';

      foreach ($validar["data"] as $key => $value) {
        $info_repetida .= '
				<div class="row">
          <div class="col-md-12 text-left">
            <span class="font-size-15px text-danger"><b>Categoría: </b>' . $value['descripcion'] .  '</span>
            ' . ($value['estado'] == 1 ? '<span class="badge bg-success-transparent"><i class="ri-check-fill align-middle me-1"></i>Activo</span>' : '<span class="badge bg-danger-transparent"><i class="ri-close-fill align-middle me-1"></i>Inhabilitado') . '</span><br>
          </div>
          <div class="col-md-12 text-left">
            <b>Papelera: </b>' . ($value['estado'] == 0 ? '<i class="fas fa-check text-success"></i> SI' : '<i class="fas fa-times text-danger"></i> NO') . ' <b>|</b>
            <b>Eliminado: </b>' . ($value['estado_delete'] == 0 ? '<i class="fas fa-check text-success"></i> SI' : '<i class="fas fa-times text-danger"></i> NO') . '<br>
            
          </div>
        </div>';
      }
      echo json_encode(array('status' => 'duplicado', 'message' => 'duplicado', 'data' => '<ul>' . $info_repetida . '</ul>', 'id_tabla' => ''));
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
    $data = [];
    $cont = 1;

    if ($rspta['status']) {
      foreach ($rspta['data'] as $key => $value) {
        $data[] = [
          "0" => $cont++,
          "1" => ($value['estado']) ? '<button class="btn btn-icon btn-sm btn-info" onclick="mostrar_editar_familia(' . $value['idfamilia'] . ')"><i class="ri-edit-line"></i></button>' .
            ' <button class="btn btn-icon btn-sm btn-danger" onclick="desactivar_familia(' . $value['idfamilia'] . ')"><i class="ri-delete-bin-line"></i></button>' :
            '<button class="btn btn-icon btn-sm btn-info" onclick="mostrar_familia(' . $value['idfamilia'] . ')"><i class="ri-edit-line"></i></button>' .
            ' <button class="btn btn-icon btn-sm btn-success" onclick="activar_familia(' . $value['idfamilia'] . ')"><i class="ri-check-double-line"></i></button>',
          "2" => $value['descripcion'],
          "3" => ($value['estado']) ? '<span class="badge bg-success-transparent"><i class="ri-check-fill align-middle me-1"></i>Activo</span>' :
            '<span class="badge bg-danger-transparent"><i class="ri-close-fill align-middle me-1"></i>Inhabilitado</span>'
        ];
      }
      $results = array(
        "sEcho" => 1,  //Información para el datatables
        "iTotalRecords" => count($data),  //enviamos el total registros al datatable
        "iTotalDisplayRecords" => count($data),  //enviamos el total registros a visualizar
        "aaData" => $data
      );
      echo json_encode($results);
    } else {
      echo $rspta['code_error'] . ' - ' . $rspta['message'] . ' ' . $rspta['data'];
    }

    break;

  case 'importar_familia':

    $tmpName = $_FILES["upload_file_familia"]["tmp_name"];
    $error = $_FILES["upload_file_familia"]["error"];

    if ($error !== UPLOAD_ERR_OK) {
      echo json_encode(['success' => false, 'message' => 'Error al subir el archivo.'], true);
      exit;
    }

    $excel = \PhpOffice\PhpSpreadsheet\IOFactory::load($tmpName);
    $excel->setActiveSheetIndex(0);
    $numerofila = $excel->setActiveSheetIndex(0)->getHighestRow();

    for ($i = 2; $i <= $numerofila; $i++) {
      $nombre = $excel->getActiveSheet()->getCell('A' . $i)->getCalculatedValue();
      if ($nombre != "") {
        $estado  = $excel->getActiveSheet()->getCell('B' . $i)->getCalculatedValue();
        $familia->insertarCategoriaMasivo($nombre, $estado);
      }
    }

    //Codificar el resultado utilizando json
    $retorno = ["status" => true, "message" => 'todo oka', "data" => []];
    echo json_encode($retorno, true);
    break;

  default:
    $rspta = 'Te has confundido en escribir en el <b>swich.</b>';
    echo $rspta;
    break;
}

ob_end_flush();
