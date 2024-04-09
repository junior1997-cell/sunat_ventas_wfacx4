<?php
if (strlen(session_id()) < 1) { session_start(); } //Validamos si existe o no la sesión

require_once "../modelos/Compra.php";

$compra = new Compra();

$idcompra           = isset($_POST["idcompra"]) ? limpiarCadena($_POST["idcompra"]) : "";
$idusuario          = $_SESSION["idusuario"];
$idproveedor        = isset($_POST["idproveedor"]) ? limpiarCadena($_POST["idproveedor"]) : "";
$fecha_emision      = isset($_POST["fecha_emision"]) ? limpiarCadena($_POST["fecha_emision"]) : "";
$tipo_comprobante   = isset($_POST["tipo_comprobante"]) ? limpiarCadena($_POST["tipo_comprobante"]) : "";
$serie_comprobante  = isset($_POST["serie_comprobante"]) ? limpiarCadena($_POST["serie_comprobante"]) : "";
$num_comprobante    = isset($_POST["num_comprobante"]) ? limpiarCadena($_POST["num_comprobante"]) : "";
$guia               = isset($_POST["guia"]) ? limpiarCadena($_POST["guia"]) : "";
$idempresa          = isset($_POST["idempresa"]) ? limpiarCadena($_POST["idempresa"]) : "";

$subtotal_compra = isset($_POST["subtotal_compra"]) ? limpiarCadena($_POST["subtotal_compra"]) : "";
$total_igv       = isset($_POST["total_igv"]) ? limpiarCadena($_POST["total_igv"]) : "";
$total_compra    = isset($_POST["total_final"]) ? limpiarCadena($_POST["total_final"]) : "";

$tcambio = isset($_POST["tcambio"]) ? limpiarCadena($_POST["tcambio"]) : "";
$hora    = isset($_POST["hora"]) ? limpiarCadena($_POST["hora"]) : "";
$moneda  = isset($_POST["moneda"]) ? limpiarCadena($_POST["moneda"]) : "";

$subarticulo = isset($_POST["subarticulo"]) ? limpiarCadena($_POST["subarticulo"]) : "";

$idarticulonarti    = isset($_POST["idarticulonarti"]) ? limpiarCadena($_POST["idarticulonarti"]) : "";
$totalcantidad      = isset($_POST["totalcantidad"]) ? limpiarCadena($_POST["totalcantidad"]) : "";
$totalcostounitario = isset($_POST["totalcostounitario"]) ? limpiarCadena($_POST["totalcostounitario"]) : "";

$factorc   = isset($_POST["factorc"]) ? limpiarCadena($_POST["factorc"]) : "";

$vunitario = isset($_POST["vunitario"]) ? limpiarCadena($_POST["vunitario"]) : "";




switch ($_GET["op"]) {
  case 'guardaryeditar':

    if ($subarticulo == '0') {

      if (empty($idcompra)) {

        $rspta = $compra->insertar( $idusuario, $idproveedor, $fecha_emision, $tipo_comprobante, $serie_comprobante, $num_comprobante, $guia, $subtotal_compra, $total_igv, 
        $total_compra, $_POST["idarticulo"], $_POST["valor_unitario"],  $_POST["cantidad"],  $_POST["subtotalBD"],  $_POST["codigo"],
          $_POST["unidad_medida"], $tcambio, $hora, $moneda, $idempresa
        );
        echo json_encode($rspta, true);
      }
    } else {

      if (empty($idcompra)) {

        $rspta = $compra->insertarsubarticulo( $idusuario, $idproveedor, $fecha_emision, $tipo_comprobante, $serie_comprobante, $num_comprobante, $guia,
          $subtotal_compra, $total_igv, $total_compra, $_POST["idarticulo"], $_POST["valor_unitario"], $_POST["cantidad"], $_POST["subtotalBD"], $_POST["codigo"], $_POST["unidad_medida"], $tcambio,
          $hora, $moneda, $idempresa, $_POST["codigobarra"], $idarticulonarti,  $totalcantidad,  $totalcostounitario,  $vunitario,  $factorc
        );
        echo $rspta ? "Compra registrada con subarticulos" : "Problema al registrar la compra, revise con el la base de datos";
      }
    }
  break;

  // case 'anular':
  //     $rspta=$compra->anular($idcompra);
  //     echo $rspta ? "Ingreso anulado" : "Ingreso no se puede anular";
  // break;

  case 'mostrar':
    $rspta = $compra->mostrar($idcompra);
    //Codificar el resultado utilizando json
    echo json_encode($rspta);
  break;

  case 'eliminarcompra':
    date_default_timezone_set('America/Lima');
    //$hoy=date('Y/m/d');
    $hoy = date("Y-m-d");
    $rspta = $compra->AnularCompra($idcompra, $hoy, $_SESSION['idempresa']);
    echo json_encode($rspta, true);
  break;

  case 'listarDetalle':
    //Recibimos el idingreso
    $id = $_GET['id'];

    $rspta = $compra->listarDetalle($id);
    $subt = 0;
    $igv = 0;
    $total = 0;
    echo '<thead style="background-color:#A9D0F5">
      <th>ARTÍCULO</th>
      <th>CANTIDAD</th>
      <th>PRECIO COMPRA</th>
      <th>Subtotal</th>
    </thead>';

    while ($reg = $rspta->fetch_object()) {
      echo '<tr class="filass"> <td>' . $reg->nombre . '</td><td>' . $reg->cantidad . '</td><td>' . $reg->costo_compra . '</td><td>' . $reg->subtotal . '</td></tr>';

      $subt = $subt + ($reg->subtotal);
      $igv = $igv + ($reg->subtotal * 0.18);
      $total = $subt + $igv;
    }
    echo ' <tfoot style="vertical-align: center;">
      <!--SUBTOTAL-->
      <tr>
        <td>
          <td></td> <td></td> <td></td> <td></td> <td><td>

          <th style="font-weight: bold; vertical-align: center; background-color:#A5E393;">SUBTOTAL (S/.)</th>
          <th style="font-weight: bold; background-color:#A5E393;">
            <h4 id="subtotal" style="font-weight: bold; vertical-align: center; background-color:#A5E393;">' . $subt . '</h4>
          </th>
        </td>
      </tr>

      <!--IGV-->
      <tr><td><td></td><td></td><td></td><td></td><td><td>
          <th  style="font-weight: bold; vertical-align: center; background-color:#A5E393;"> IGV  18% (S/.)</th>
          <th style="font-weight: bold; background-color:#A5E393; vertical-align: center;">
            <h4 id="igv_" style="vertical-align: right; font-weight: bold; background-color:#A5E393;">' . $igv . '</h4>
          </th>
          </td>
      </tr>
      <tr><td><td></td><td></td><td></td><td></td><td><td>
          <th style="font-weight: bold; vertical-align: center; background-color:#FFB887;">TOTAL (S/.)</th> <!--Datos de impuestos-->  <!--IGV-->
          <th style="font-weight: bold; background-color:#FFB887;">
            <h4 id="total" style="font-weight: bold; vertical-align: center; background-color:#FFB887;">' . $total . '</h4>
          </th><!--Datos de impuestos-->  <!--TOTAL-->
          </td>
      </tr>
    </tfoot>';
  break;

  case 'listar':
    $rspta = $compra->listar($_SESSION['idempresa']);
    //Vamos a declarar un array
    $data = [];

    if ($rspta['status']) {
      foreach ($rspta['data'] as $key => $value){
        $data[]=[
          "0" => ' <div class="dropdown-center"> 
            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownCenterBtn" data-bs-toggle="dropdown" aria-expanded="false"> Acciones </button> 
            <ul class="dropdown-menu" aria-labelledby="dropdownCenterBtn" style=""> 
              <li><a class="dropdown-item" href="../reportes/compraReporte.php?idcompra=' . ($value['idcompra']) . '" target="_blanck">Imprimir</a></li> 
              <li><a class="dropdown-item" onclick="eliminarcompra(' . ($value['idcompra']) . ')">Anular compra</a></li>  
            </ul>
          </div> ',
          "1" => ($value['fecha']),
          "2" => ($value['proveedor']),
          "3" => ($value['usuario']),
          "4" => ($value['descripcion']),
          "5" => ($value['serie']). '-' . ($value['numero']),
          "6" => ($value['total']),
          "7" => ($value['estado'] == '1') ? '<span class="badge bg-success-transparent"><i class="ri-check-fill align-middle me-1"></i>Ingresado</span>' : '<span class="badge bg-danger-transparent"><i class="ri-close-fill align-middle me-1"></i>Anualdo</span>'
        ];
      }

      $results = array(
        "sEcho" => 1,                           //Información para el datatables
        "iTotalRecords" => count($data),        //enviamos el total registros al datatable
        "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
        "aaData" => $data
      );
      echo json_encode($results, true);
    } else { echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data']; }

  break;

  case 'selectProveedor':
    require_once "../modelos/Persona.php";
    $persona = new Persona();
    $rspta = $persona->listarp();
    foreach ($rspta['data'] as $key => $value){
      echo '<option value=' . ($value['idpersona']) . '>' . ($value['razon_social']) . '</option>';
    }
  break;

  case 'listarArticulos':
    $subarticu = $_GET['subarti'];
    require_once "../modelos/Articulo.php";
    $articulo = new Articulo($_SESSION['idusuario'], $_SESSION['idempresa']);

    $rspta = $articulo->listarActivos($_SESSION['idempresa']);
    $data = [];

    if($rspta['status']){
      foreach($rspta['data'] as $kay => $value){
        $data[] = [
          "0" => ($value['codigo']),
          "1" => ($value['codigo_proveedor']),
          "2" => ($value['nombre']),        
          "3" => ($value['nombreum']),
          "4" => ($value['stock']),
          "5" => ($value['precio_final_kardex']),
          "6" => '<button class="btn btn-primary btn-sm btn-wave waves-effect waves-light" onclick="agregarDetalle(' . ($value['idarticulo']) . ',\'' .($value['nombre']) . '\',\'' . ($value['codigo_proveedor']) . '\',\'' . ($value['codigo']) . '\',\'' . ($value['nombre']) . '\',\'' . ($value['precio_venta']) . '\',\'' . ($value['stock']). '\',\'' . ($value['abre']). '\' ,\'' . ($value['precio_unitario']). '\', \'' . ($value['costo_compra']) . '\', \'' . ($value['factorc']) . '\' , \'' . ($value['nombreum']) . '\')">Agregar</button>'
        ];
      }

      $results = array(
        "sEcho" => 1,                           //Información para el datatables
        "iTotalRecords" => count($data),        //enviamos el total registros al datatable
        "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
        "aaData" => $data
      );

      echo json_encode($results, true);
    }else {
      echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
      }
  break;

  case 'mostrarumventa':
    $ida = $_GET['idarti'];
    require_once "../modelos/Articulo.php";
    $articulo = new Articulo($_SESSION['idusuario'], $_SESSION['idempresa']);
    $rspta2 = $articulo->listarActivosumventa($ida);
    echo json_encode($rspta2);
  break;

  case 'listarArticuloscompraxcodigo':
    require_once "../modelos/Articulo.php";
    $articulo = new Articulo($_SESSION['idusuario'], $_SESSION['idempresa']);
    $codigob = $_GET['codigob'];
    $rspta = $articulo->listarActivosVentaxCodigo($codigob, $_SESSION['idempresa']);
    echo json_encode($rspta);
  break;
}
