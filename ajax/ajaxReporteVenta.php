<?php
ob_start(); //Activamos el almacenamiento del Buffer

if (strlen(session_id()) < 1) {
  session_start();
} //Validamos si existe o no la sesión

require_once "../modelos/Venta.php";

$venta = new Venta();

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action == 'ListarReporteMesyDia') {

  // Obteniendo los parámetros desde la URL  fechadel, fechaal, tcomprobante,idempresa
  $fechadel = isset($_GET['fechadel']) ? $_GET['fechadel'] : '';
  $fechaal = isset($_GET['fechaal']) ? $_GET['fechaal'] : '';
  $tcomprobante = isset($_GET['tcomprobante']) ? $_GET['tcomprobante'] : '';
  $idempresa = isset($_GET['idempresa']) ? $_GET['idempresa'] : '';
  $tipo_moneda = isset($_GET['tipo_moneda']) ? $_GET['tipo_moneda'] : '';


  $rspta = $venta->regventareporte($fechadel, $fechaal, $idempresa, $tcomprobante, $tipo_moneda);
//echo $rspta ; die();
  $data = array();
  foreach ($rspta as $key => $reg) {
    // Aquí puedes mapear los datos del objeto $reg a la estructura que desees
    $total = 
    $data[] = array(
      "id" => $reg['id'],
      "tipodocu" => $reg['tipodocu'],
      "fecha" => $reg['fecha'],
      "documento" => $reg['documento'],
      "subtotal" => $reg['subtotal'],
      "igv" => $reg['igv'],
      "total" => $reg['total'],
      "estado" => $reg['estado'],
      "numero_documento" => $reg['numero_documento'],
      "razon_social" => $reg['razon_social'],
      "icbper" => $reg['icbper'],
      "tipofactura" => $reg['tipofactura'],
      "tipomoneda" => $reg['tipomoneda'],
      "tcambio" => $reg['tcambio'],
      "efectivo" => $reg['efectivo'],
      "visa" => $reg['visa'],
      "yape" => $reg['yape'],
      "plin" => $reg['plin'],
      "mastercard" => $reg['mastercard'],
      "deposito" => $reg['deposito'],
      "productos_adquiridos" =>'<div  style="overflow: auto; resize: vertical; height: 55px;" >'. $reg['productos_adquiridos'] .'</div>'
       
    );
  }

  $results = array("aaData" => $data);
  echo json_encode($results, true);
}


ob_end_flush();

// if ($action == 'ListarReporteMes') {
    
//     // Obteniendo los parámetros desde la URL
//     $ano = isset($_GET['ano']) ? $_GET['ano'] : date('Y'); // Si no se proporciona, tomamos el año actual
//     $mes = isset($_GET['mes']) ? $_GET['mes'] : date('m'); // Si no se proporciona, tomamos el mes actual
//     $dia = isset($_GET['dia']) ? $_GET['dia'] : date('d');
//     $idempresa = isset($_GET['idempresa']) ? $_GET['idempresa'] : '';
//     $tmon = isset($_GET['tmon']) ? $_GET['tmon'] : '';

//     $rspta = $venta->regventaagruxdia($ano, $mes, $dia, $idempresa, $tmon);
//     $data = array();

//     while ($reg = $rspta->fetch_object()) {
//         // Aquí puedes mapear los datos del objeto $reg a la estructura que desees
//         $data[] = array(
//         "id" => $reg->id,
//         "tipodocu" => $reg->tipodocu,
//         "fecha" => $reg->fecha,
//         "documento" => $reg->documento,
//         "subtotal" => $reg->subtotal,
//         "igv" => $reg->igv,
//         "total" => $reg->total,
//         "estado" => $reg->estado,
//         "numero_documento" => $reg->numero_documento,
//         "razon_social" => $reg->razon_social,
//         "icbper" => $reg->icbper,
//         "tipofactura" => $reg->tipofactura,
//         "tipomoneda" => $reg->tipomoneda,
//         "tcambio" => $reg->tcambio,
//         "efectivo" => $reg->efectivo,
//         "visa" => $reg->visa,
//         "yape" => $reg->yape,
//         "plin" => $reg->plin,
//         "mastercard" => $reg->mastercard,
//         "deposito" => $reg->deposito,
//         "productos_adquiridos" => $reg->productos_adquiridos
//         );
//     }

//     $results = array("aaData" => $data);

//     header('Content-type: application/json');
//     echo json_encode($results);
// }
