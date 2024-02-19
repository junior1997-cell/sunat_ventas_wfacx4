<?php
ob_start();

if (strlen(session_id()) < 1) {
  session_start();  //Validamos si existe o no la sesión
}

require_once "../modelos/Almacen.php";

$almacen = new Almacen();

date_default_timezone_set('America/Lima');
$date_now = date("d_m_Y__h_i_s_A");

$idalmacen     = isset($_POST["idalmacen"]) ? limpiarCadena($_POST["idalmacen"]) : "";
$nombrea      = isset($_POST["nombrea"]) ? limpiarCadena($_POST["nombrea"]) : "";
$descripcion   = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";
$estado       = isset($_POST["estado"]) ? limpiarCadena($_POST["estado"]) : "";
$direccion     = isset($_POST["direccion"]) ? limpiarCadena($_POST["direccion"]) : "";


switch ($_GET["op"]) {

  case 'guardaryeditar':
    $validarAlmacen = $almacen->validarAlmacen($nombrea);

    if (empty($validarAlmacen["data"])) {

      if (empty($idalmacen)) {
        $rspta = $almacen->insertaralmacen($nombrea, $direccion, '1');
        echo json_encode(['status' => 'registrado', 'data' => $rspta]);
      } else {
        $rspta = $almacen->editar($idalmacen, $nombrea, $direccion);
        echo json_encode(['status' => 'modificado', 'data' => $rspta]);
      }
    } else {
      $info_repetida = '';

      foreach ($validarAlmacen["data"] as $key => $value) {
        $info_repetida .= '
				<div class="row">
          <div class="col-md-12 text-left">
            <span class="font-size-15px text-danger"><b>Categoría: </b>' . $value['nombre'] .  '</span>
            ' . ($value['estado'] == 1 ? '<span class="badge bg-success-transparent"><i class="ri-check-fill align-middle me-1"></i>Activo</span>' : '<span class="badge bg-danger-transparent"><i class="ri-close-fill align-middle me-1"></i>Inhabilitado').'</span><br>
          </div>
          <div class="col-md-12 text-left">
            <b>Papelera: </b>' . ($value['estado'] == 0 ? '<i class="fas fa-check text-success"></i> SI' : '<i class="fas fa-times text-danger"></i> NO') . ' <b>|</b>
            <b>Eliminado: </b>' . ($value['estado_delete'] == 0 ? '<i class="fas fa-check text-success"></i> SI' : '<i class="fas fa-times text-danger"></i> NO') . '<br>
            
          </div>
        </div>';
      }
      echo json_encode(['status' => 'duplicado', 'message' => 'duplicado', 'data' => '<ul>' . $info_repetida . '</ul>', 'id_tabla' => '']);
    }



    break;

  case 'desactivar':
    $rspta = $almacen->desactivar($idalmacen);
    echo $rspta ? "almacen Desactivado" : "almacen no se puede desactivar";
    break;

  case 'activar':
    $rspta = $almacen->activar($idalmacen);
    echo $rspta ? "almacen habilitado" : "almacen no se puede activar";
    break;

  case 'mostrar':
    $rspta = $almacen->mostrar($idalmacen);
    //Codificar el resultado utilizando json
    echo json_encode($rspta, true);
    break;

  case 'listar':
    $rspta = $almacen->listar();
    //Vamos a declarar un array
    $data = [];
    // $value['idalmacen']
    if ($rspta['status']) {
      foreach ($rspta['data'] as $key => $value) {
        $data[] = [
          "0" => $value['nombre'],
          "1" => $value['direccion'],
          "2" => ($value['estado']) ? '<span class="badge bg-success-transparent"><i class="ri-check-fill align-middle me-1"></i>Activo</span>' : '<span class="badge bg-danger-transparent"><i class="ri-close-fill align-middle me-1"></i>Inhabilitado</span>',
          "3" => ($value['estado']) ? '<button class="btn btn-icon btn-sm btn-info" onclick="mostrar(' . $value['idalmacen'] . ')"><i class="ri-edit-line"></i></button>' .
            ' <button class="btn btn-icon btn-sm btn-danger" onclick="desactivar(' . $value['idalmacen'] . ')"><i class="ri-delete-bin-line"></i></button>' :
            '<button class="btn btn-icon btn-sm btn-info" onclick="mostrar(' . $value['idalmacen'] . ')"><i class="ri-edit-line"></i></button>' .
            ' <button class="btn btn-icon btn-sm btn-success" onclick="activar(' . $value['idalmacen'] . ')"><i class="ri-check-double-line"></i></button>'
        ];
      }
      $results = [
        "sEcho" => 1,
        //Información para el datatables
        "iTotalRecords" => count($data),
        //enviamos el total registros al datatable
        "iTotalDisplayRecords" => 1,
        //enviamos el total registros a visualizar
        "data" => $data
      ];
      echo json_encode($results, true);
    } else {
      echo $rspta['code_error'] . ' - ' . $rspta['message'] . ' ' . $rspta['data'];
    }

    break;
}
ob_end_flush();
