<?php

if (strlen(session_id()) < 1) {	session_start(); }//Validamos si existe o no la sesión

require_once "../modelos/Transferencia_stock.php";

$transferencias = new Transferencia_stock();

$idalmacen1 			  = isset($_POST["idalmacen1"]) ? limpiarCadena($_POST["idalmacen1"]) : "";
$idalmacen2 			  = isset($_POST["idalmacen2"]) ? limpiarCadena($_POST["idalmacen2"]) : "";
$idarticulos1 			= isset($_POST["idarticulos1"]) ? limpiarCadena($_POST["idarticulos1"]) : "";
$idarticulos2 			= isset($_POST["idarticulos2"]) ? limpiarCadena($_POST["idarticulos2"]) : "";
$cantidad 			    = isset($_POST["cantidad"]) ? limpiarCadena($_POST["cantidad"]) : "";



switch ($_GET["op"]) {

  case 'mostrar_tranferencia':
		$rspta = $transferencias->listar();
		$data = [];
    $count = 1;

    if($rspta['status']){
      foreach($rspta['data'] as $key => $value){
        $data[]=[
          "0" => $count++,
          "1" => ($value['fecha']),
          "2" => ($value['almacen']),
          "3" => ($value['articulo']),
          "4" => ($value['cantidad']),
          "5" => ($value['estado'] == '1') ? '<span class="badge bg-success-transparent"><i class="ri-check-fill align-middle me-1"></i>Registrado</span>' : '<span class="badge bg-danger-transparent"><i class="ri-close-fill align-middle me-1"></i>Invalido</span>'
        ];
      }
    }
    $results = array(
      "sEcho" => 1,                           //Información para el datatables
      "iTotalRecords" => count($data),        //enviamos el total registros al datatable
      "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
      "aaData" => $data
    );
    echo json_encode($results, true);
	break;

	case "selectAlmacen1":		
    $rspta = $transferencias->select1();
    echo '<option value="">Seleccione almacén de origen</option>'; // opción predeterminada al principio
    foreach ($rspta['data'] as $key => $value){
      echo '<option value=' . ($value['idalmacen']) . '>' . ($value['nombre']). '</option>';
    }
  break;

  case "selectAlmacen2":
    // Obtener el id del almacén seleccionado en el primer select
    $idalmacen1 = $_POST['idalmacen1'];
    $rspta = $transferencias->select2($idalmacen1);

    echo '<option value="">Seleccione almacén de destino</option>'; // opción predeterminada al principio
    foreach ($rspta['data'] as $key => $value){
        echo '<option value=' . ($value['idalmacen']) . '>' . ($value['nombre']). '</option>';
    }
  break;

  case "selectArticulos1":
    // Obtener el id del almacén seleccionado en el primer select
    $idalmacen1 = $_POST['idalmacen1'];
    $rspta = $transferencias->selectArt1($idalmacen1);
    echo '<option value="">Seleccione un atículo </option>'; // opción predeterminada al principio

    foreach ($rspta['data'] as $key => $value){
        echo '<option value=' . ($value['idarticulo']) . '>' . ($value['articulo']) . '</option>';
    }
  break;

  case "selectArticulos2":
    // Obtener el id del almacén seleccionado en el primer select
    $idalmacen2 = $_POST['idalmacen2'];
    $rspta = $transferencias->selectArt2($idalmacen2);
    echo '<option value="">Seleccione un atículo </option>'; // opción predeterminada al principio

    foreach ($rspta['data'] as $key => $value){
        echo '<option value=' . ($value['idarticulo']) . '>' . ($value['articulo']) . '</option>';
    }
  break;

  case "verStock":
    $idarticulo1 = $_POST['idarticulo1'];
    $respta = $transferencias->verStock($idarticulo1);
    echo json_encode($respta, true);
  break;

  case "guardar_transferencia":		
    $rspta = $transferencias->insertar($idalmacen1, $idalmacen2, $idarticulos1, $idarticulos2, $cantidad);
    echo json_encode($rspta, true);
  break;


	
}
?>