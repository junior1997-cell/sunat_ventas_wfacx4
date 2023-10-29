<?php
require_once "../modelos/Venta.php";

$venta = new Venta();

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action == 'ListarReporteMes') {
    
    // Obteniendo los parámetros desde la URL
    $ano = isset($_GET['ano']) ? $_GET['ano'] : date('Y'); // Si no se proporciona, tomamos el año actual
    $mes = isset($_GET['mes']) ? $_GET['mes'] : date('m'); // Si no se proporciona, tomamos el mes actual
    $idempresa = isset($_GET['idempresa']) ? $_GET['idempresa'] : '';
    $tmon = isset($_GET['tmon']) ? $_GET['tmon'] : '';

    $rspta = $venta->regventareporte($ano, $mes, $idempresa, $tmon);
    $data = array();

    while ($reg = $rspta->fetch_object()) {
        // Aquí puedes mapear los datos del objeto $reg a la estructura que desees
        $data[] = array(
        "id" => $reg->id,
        "tipodocu" => $reg->tipodocu,
        "fecha" => $reg->fecha,
        "documento" => $reg->documento,
        "subtotal" => $reg->subtotal,
        "igv" => $reg->igv,
        "total" => $reg->total,
        "estado" => $reg->estado,
        "numero_documento" => $reg->numero_documento,
        "razon_social" => $reg->razon_social,
        "icbper" => $reg->icbper,
        "tipofactura" => $reg->tipofactura,
        "tipomoneda" => $reg->tipomoneda,
        "tcambio" => $reg->tcambio,
        "efectivo" => $reg->efectivo,
        "visa" => $reg->visa,
        "yape" => $reg->yape,
        "plin" => $reg->plin,
        "mastercard" => $reg->mastercard,
        "deposito" => $reg->deposito,
        "productos_adquiridos" => $reg->productos_adquiridos
        );
    }

    $results = array("aaData" => $data);

    header('Content-type: application/json');
    echo json_encode($results);
}


if ($action == 'ListarReporteMesyDia') {
    
    // Obteniendo los parámetros desde la URL
    $ano = isset($_GET['ano']) ? $_GET['ano'] : date('Y'); // Si no se proporciona, tomamos el año actual
    $mes = isset($_GET['mes']) ? $_GET['mes'] : date('m'); // Si no se proporciona, tomamos el mes actual
    $dia = isset($_GET['dia']) ? $_GET['dia'] : date('d');
    $idempresa = isset($_GET['idempresa']) ? $_GET['idempresa'] : '';
    $tmon = isset($_GET['tmon']) ? $_GET['tmon'] : '';

    $rspta = $venta->regventaagruxdia($ano, $mes, $dia, $idempresa, $tmon);
    $data = array();

    while ($reg = $rspta->fetch_object()) {
        // Aquí puedes mapear los datos del objeto $reg a la estructura que desees
        $data[] = array(
        "id" => $reg->id,
        "tipodocu" => $reg->tipodocu,
        "fecha" => $reg->fecha,
        "documento" => $reg->documento,
        "subtotal" => $reg->subtotal,
        "igv" => $reg->igv,
        "total" => $reg->total,
        "estado" => $reg->estado,
        "numero_documento" => $reg->numero_documento,
        "razon_social" => $reg->razon_social,
        "icbper" => $reg->icbper,
        "tipofactura" => $reg->tipofactura,
        "tipomoneda" => $reg->tipomoneda,
        "tcambio" => $reg->tcambio,
        "efectivo" => $reg->efectivo,
        "visa" => $reg->visa,
        "yape" => $reg->yape,
        "plin" => $reg->plin,
        "mastercard" => $reg->mastercard,
        "deposito" => $reg->deposito,
        "productos_adquiridos" => $reg->productos_adquiridos
        );
    }

    $results = array("aaData" => $data);

    header('Content-type: application/json');
    echo json_encode($results);
}
