<?php
require_once "../modelos/Insumos.php";

$insumos = new Insumos();

$idinsumo      = isset($_POST["idinsumo"]) ? limpiarCadena($_POST["idinsumo"]) : "";
$tipodato      = isset($_POST["tipodato"]) ? limpiarCadena($_POST["tipodato"]) : "";
$idutilidad    = isset($_POST["idutilidad"]) ? limpiarCadena($_POST["idutilidad"]) : "";
$categoriai    = isset($_POST["categoriai"]) ? limpiarCadena($_POST["categoriai"]) : "";
$fecharegistro = isset($_POST["fecharegistro"]) ? limpiarCadena($_POST["fecharegistro"]) : "";
$descripcion   = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";
$monto         = isset($_POST["monto"]) ? limpiarCadena($_POST["monto"]) : "";
$documnIDE     = isset($_POST["documnIDE"]) ? limpiarCadena($_POST["documnIDE"]) : "";
$numDOCIDE     = isset($_POST["numDOCIDE"]) ? limpiarCadena($_POST["numDOCIDE"]) : "";
$acredor       = isset($_POST["acredor"]) ? limpiarCadena($_POST["acredor"]) : "";


$idcategoria = isset($_POST["idcategoria"]) ? limpiarCadena($_POST["idcategoria"]) : "";
$descripcioncate = isset($_POST["descripcioncate"]) ? limpiarCadena($_POST["descripcioncate"]) : "";


switch ($_GET["op"]) {
  case 'guardaryeditar':
    if (empty($idinsumo)) {

      $rspta = $insumos->EstadoCaja();

      if (empty($rspta)) { echo 'caja_cerrada'; exit(); }elseif ($rspta['estado']=='0') { echo 'caja_cerrada'; exit(); }

      $rspta = $insumos->insertar($tipodato, $fecharegistro, $categoriai, $documnIDE, $numDOCIDE, $acredor, $descripcion, $monto);
      echo $rspta ? "Monto registrado" : "Monto no se pudo registrar";

    } else {
      $rspta = $insumos->editar($idinsumo,$tipodato, $fecharegistro, $categoriai, $documnIDE, $numDOCIDE, $acredor, $descripcion, $monto);
      echo $rspta ? "Insumo actualizado" : "Insumo no se pudo actualizar";
    }
    break;



  case 'mostrar':
    $rspta = $insumos->mostrar($_POST['id_insumo']);
    echo json_encode($rspta);
  break;


  case 'listar':
    $rspta = $insumos->listar($_GET['idcaja']);

    $data = array();
    // $rspta['data']
    foreach ($rspta as $key => $reg) {

      $estado= ($reg['estado_caja'])=='1' ? '<button class="btn btn-icon btn-sm btn-info" onclick="mostrar_editar('.$reg['idinsumo'].')"><i class="ri-edit-line"></i></button>' .
      ' <button class="btn btn-icon btn-sm btn-danger" onclick="eliminar('.$reg['idinsumo'].')"><i class="ri-delete-bin-line"></i></button>' : 
      '<button class="btn btn-icon btn-sm btn-info" disabled><i class="ri-edit-line"></i></button>' .
      ' <button class="btn btn-icon btn-sm btn-danger" disabled><i class="ri-delete-bin-line"></i></button>' ;

      $data[] = array(
        "0" => $reg['idinsumo'],
        "1" => $reg['tipodato'],
        "2" => $reg['fecharegistro'],
        "3" => $reg['documnIDE'].' : '.$reg['numDOCIDE'],
        "4" => $reg['acredor'],
        "5" => $reg['descripcionc'],
        "6" => $reg['descripcion'],
        "7" => $reg['gasto'],
        "8" => $reg['ingreso'],
        "9" => $estado
      );
    }
    $results = array(
      "sEcho" => 1, //Información para el datatables
      "iTotalRecords" => count($data), //enviamos el total registros al datatable
      "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
      "aaData" => $data
    );
    echo json_encode($results);

  break;

  case 'selectcate':
    require_once "../modelos/Insumos.php";
    $insumos = new Insumos();
    $rspta = $insumos->selectcategoria();
    while ($reg = $rspta->fetch_object()) {
      echo '<option value=' . $reg->idcategoriai . '>' . $reg->descripcionc . '</option>';
    }
  break;

  case 'guardaryeditarcate':
    if (empty($idcategoria)) {
      $rspta = $insumos->insertarcategoria($descripcioncate);
      echo $rspta ? "Categoría registrada" : "Categoría no se pudo registrar";
    }
  break;

  case 'eliminar':
    $rspta = $insumos->eliminar($idinsumo);
    echo $rspta ? "Insumo eliminado" : "Insumo no se puede eliminar";
  break;
  
  case 'select_cajas':
    $rspta = $insumos->select_cajas();
    echo '<option value="TODOS">TODOS</option>';
    foreach ($rspta as $key => $reg) {
      $selected = ($key==0) ? 'selected' : '' ;
    echo '<option value="' . $reg['idcaja'] . '"  fa="' . $reg['fecha_apertura'] . '" fc="' . $reg['fecha_cierre'] . '" '.$selected.' >' . $reg['codigo_caja'] . ' - '.$reg['estado_caja'] .'</option>';
    }
    
  break;

  case 'Estado_Caja':
    $rspta = $insumos->EstadoCaja();
    echo json_encode($rspta);
  break;

  //---------------------------
  case 'eliminarutilidad':
    $rspta = $insumos->eliminarutilidad($idutilidad);
    echo $rspta ? "Utilidad eliminada" : "Utilidad no se puede eliminar";
  break;




  case 'calcularutilidad':
    $ff1 = $_GET['f1'];
    $ff2 = $_GET['f2'];
    $rspta = $insumos->calcularuti($ff1, $ff2);
    $data = array();

    while ($reg = $rspta->fetch_object()) {
      $data[] = array(
        "0" => $reg->idutilidad,
        "1" => $reg->fecha1,
        "2" => $reg->fecha2,
        "3" => $reg->totalgastos,
        "4" => $reg->totalventas,
        "5" => $reg->utilidad,
        "6" => $reg->porcentaje,
        "7" => ($reg->estado == '0') ? '<span>No aprobado</span> <a onclick="aprobarutilidad(' . $reg->idutilidad . ')"><i class="fa fa-check" style="color:red;"></i></a>' : '<span>Aprobado</span>',
        "8" => '<a onclick="eliminarutilidad(' . $reg->idutilidad . ')"><i class="fa fa-close" style="color:red;">del</i></a>
 				<a onclick="recalcularutilidad(' . $reg->idutilidad . ')"><i class="fa fa-repeat" style="color:green;">reload</i></a>',
        "9" => '<a onclick="reporteutilidad(' . $reg->idutilidad . ')"><i class="fa fa-print" style="color:green;"></i></a>'


      );
    }
    $results = array(
      "sEcho" => 1, //Información para el datatables
      "iTotalRecords" => count($data), //enviamos el total registros al datatable
      "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
      "aaData" => $data
    );
    echo json_encode($results);
  break;

  case 'recalcularutilidad':
    $idduti = $_GET['iduti'];

    $rspta = $insumos->recalcularuti($idduti);
    $data = array();

    while ($reg = $rspta->fetch_object()) {
      $data[] = array(
        "0" => $reg->idutilidad,
        "1" => $reg->fecha1,
        "2" => $reg->fecha2,
        "3" => $reg->totalgastos,
        "4" => $reg->totalventas,
        "5" => $reg->utilidad,
        "6" => $reg->porcentaje,
        "7" => ($reg->estado == '0') ? '<span>No aprobado</span> <a onclick="aprobarutilidad(' . $reg->idutilidad . ')"><i class="fa fa-check" style="color:red;"></i></a>' : '<span>Aprobado</span>',
        "8" => '<a onclick="eliminarutilidad(' . $reg->idutilidad . ')"><i class="fa fa-close" style="color:red;">del</i></a>
 				<a onclick="recalcularutilidad(' . $reg->idutilidad . ')"><i class="fa fa-repeat" style="color:green;">reload</i></a>',
        "9" => '<a onclick="reporteutilidad(' . $reg->idutilidad . ')"><i class="fa fa-print" style="color:green;"></i></a>'

      );
    }
    $results = array(
      "sEcho" => 1, //Información para el datatables
      "iTotalRecords" => count($data), //enviamos el total registros al datatable
      "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
      "aaData" => $data
    );
    echo json_encode($results);
  break;



  case 'listarutilidad':
    $rspta = $insumos->listarutilidad();
    $data = array();

    while ($reg = $rspta->fetch_object()) {
      $data[] = array(
        "0" => $reg->idutilidad,
        "1" => $reg->fecha1,
        "2" => $reg->fecha2,
        "3" => $reg->totalgastos,
        "4" => $reg->totalventas,
        "5" => $reg->utilidad,
        "6" => $reg->porcentaje,
        "7" => ($reg->estado == '0') ? '<span>No aprobado</span> <a onclick="aprobarutilidad(' . $reg->idutilidad . ')"><i class="fa fa-check" style="color:red;"></i></a>' : '<span>Aprobado</span>',
        "8" => '<a onclick="eliminarutilidad(' . $reg->idutilidad . ')"><i class="fa fa-close" style="color:red;">del</i></a>   
 				<a onclick="recalcularutilidad(' . $reg->idutilidad . ')"><i class="fa fa-repeat" style="color:green;">reload</i></a>',
        "9" => '<a onclick="reporteutilidad(' . $reg->idutilidad . ')"><i class="fa fa-print" style="color:green;"></i></a>'


      );
    }
    $results = array(
      "sEcho" => 1, //Información para el datatables
      "iTotalRecords" => count($data), //enviamos el total registros al datatable
      "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
      "aaData" => $data
    );
    echo json_encode($results);

  break;


  case 'aprobarutilidad':
    $rspta = $insumos->aprobarutilidad($idutilidad);
    echo $rspta ? "Utilidad aprobada" : "Utilidad no se pudo aprobar";

  break;
}
