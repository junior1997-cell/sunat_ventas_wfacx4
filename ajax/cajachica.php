<?php
if (strlen(session_id()) < 1) {
  session_start();
}
require_once "../modelos/Cajachica.php";

$cajachica = new Cajachica();

$idsaldoini = isset($_POST["idsaldoini"]) ? limpiarCadena($_POST["idsaldoini"]) : "";
$total_venta = isset($_POST["total_venta"]) ? limpiarCadena($_POST["total_venta"]) : "";
$saldo_inicial = isset($_POST["saldo_inicial"]) ? limpiarCadena($_POST["saldo_inicial"]) : "";

if (isset($_GET['action'])) {
  $action = $_GET['action'];
} else {
  $action = '';
}

if ($action == 'TotalVentas') {

  $rspta = $cajachica->TotalVentas();
  
  $data = empty($rspta['total_venta'])?'0': floatval($rspta['total_venta']);

  echo json_encode($data, true);
}

if ($action == 'TotalCaja') {

  $rspta = $cajachica->TotalCaja();
  $data =  empty($rspta['total_caja'])?'0': floatval($rspta['total_caja']);
  echo json_encode($data, true);
}

if ($action == 'TotalGastos') {
  $rspta = $cajachica->verEgresos();
  $data = empty($rspta['total_gasto'])?'0': floatval($rspta['total_gasto']);
  echo json_encode($data, true);
}

if ($action == 'TotalIngresos') {
  $rspta = $cajachica->verIngresos();

  $data =  empty($rspta['total_ingreso'])?'0': floatval($rspta['total_ingreso']);

  echo json_encode($data, true);
}

if ($action == 'SaldoInicial') {
  $rspta = $cajachica->verSaldoini();
  $data = $rspta['montoi'];

  echo json_encode($data, true);
}

if ($action == 'comprobantes') {

  $rspta = $cajachica->comprobantes();
  $data = array();

  foreach ($rspta as $key => $reg) {
    $data[] = array(

      'id_doc'           => $reg['id_doc'],
      'fecha_emision_01' => $reg['fecha_emision_01'],
      'nun_doc'          => $reg['nun_doc'],
      'idcliente'        => $reg['idcliente'],
      'rucCliente'       => $reg['rucCliente'],
      'RazonSocial'      => $reg['RazonSocial'],
      'importe_total'    => $reg['importe_total'],
      'descripcion_ley'  => $reg['descripcion_ley'],
      'tipoDoc'          => $reg['tipoDoc']
    );
  }
  $results = array(
    "aaData" => $data
  );

  header('Content-type: application/json');
  echo json_encode($results);
}

if ($action == 'tblInsumos') {
  $tipo = $_GET['tipo'];
  $rspta = $cajachica->tblInsumos($tipo);
  $data = array();

  foreach ($rspta as $key => $reg) {
    $data[] = array(
      'fecharegistro' => $reg['fecharegistro'],
      'descripcion'   => $reg['descripcion'],
      'descripcionc'  => $reg['descripcionc'],
      'total'         => $reg['total'],
      'acredor'       => $reg['acredor']
    );
  }
  $results = array(
    "aaData" => $data
  );

  header('Content-type: application/json');
  echo json_encode($results);
}

switch ($_GET["op"]) {

  case 'guardaryeditar':

      $resultado = $cajachica->insertarSaldoInicial($saldo_inicial);
      echo $resultado ? "Saldo registrado" : "Saldo no se pudo registrar";
    
  break;


  case 'cerrarcaja':  
    $resultado = $cajachica->cerrarCaja($_SESSION['tipo_doc_user'], $_SESSION['num_doc_user']);
    echo $resultado ? "Caja cerrada" : "No se pudo cerrar la caja";
  break;
}
