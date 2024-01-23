<?php

if (strlen(session_id()) < 1) {	session_start(); }//Validamos si existe o no la sesión

require_once "../modelos/Transferencia_stock.php";

$transferencias = new Transferencia_stock();

$almacen1 = isset($_POST["almacen1"]) ? limpiarCadena($_POST["almacen1"]) : "";
$articulos1 = isset($_POST["articulos1"]) ? limpiarCadena($_POST["articulos1"]) : "";
$cantidad1 = isset($_POST["cantidad1"]) ? limpiarCadena($_POST["cantidad1"]) : "";
$almacen2 = isset($_POST["almacen2"]) ? limpiarCadena($_POST["almacen2"]) : "";
$articulos2 = isset($_POST["articulos2"]) ? limpiarCadena($_POST["articulos2"]) : "";



switch ($_GET["op"]) {

    case 'mostrar_tranferencia':
		$rspta = $transferencias->listar_tranferencia();
		$data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
              "0" => ' <div class="dropdown-center"> 
                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownCenterBtn" data-bs-toggle="dropdown" aria-expanded="false"> Acciones </button> 
                <ul class="dropdown-menu" aria-labelledby="dropdownCenterBtn" style=""> 
                  <li><a class="dropdown-item" href="../reportes/compraReporte.php?idalmacen_transferencia=' . $reg->idalmacen_transferencia . '" target="_blanck">Imprimir</a></li> 
                  <li><a class="dropdown-item" onclick="eliminarcompra(' . $reg->idalmacen_transferencia . ')">Anular transferencia</a></li>  
                </ul>
              </div> ',
              "1" => $reg->fecha,
              "2" => $reg->almacen,
              "3" => $reg->articulo,
              "4" => $reg->cantidad,
              "5" => ($reg->estado == '1') ? '<span class="badge bg-success-transparent"><i class="ri-check-fill align-middle me-1"></i>Registrado</span>' : '<span class="badge bg-danger-transparent"><i class="ri-close-fill align-middle me-1"></i>Invalido</span>'
            );
          }
      
          $results = array(
            "sEcho" => 1,                           //Información para el datatables
            "iTotalRecords" => count($data),        //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data
          );
          echo json_encode($results, true);
	break;

	case 'mostrar_almacenes':
		$rspta = $transferencias->listar_almacen();
		//Codificar el resultado utilizando json
		echo json_encode($rspta);
	break;


	case 'articulos_x_almacen':
        $idalmacen = $_POST['idalmacen'];
        $rspta = $transferencias->listar_articulo_x_almacen($idalmacen);
        echo json_encode($rspta);
    break;

    case 'ver_stock':
        $idarticulo = $_POST['idarticulo'];
        $rspta = $transferencias->stock($idarticulo);
        echo json_encode($rspta);
    break;

    case 'guardar_stock_transferido':
        $rspta = $transferencias->insertar($almacen1, $articulos1, $cantidad1, $almacen2, $articulos2);
        echo $rspta ? "Transferencia Registrada" : "error";
    break;


	
}
?>