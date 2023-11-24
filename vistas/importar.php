<?php

require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;  
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;

if (strlen(session_id()) < 1) {	session_start(); }//Validamos si existe o no la sesión

ob_start(); //Activamos el almacenamiento del Buffer


if (!isset($_SESSION["nombre"])) {
  header("Location: ../vistas/login.php");
} else {

  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["dataArticulo"])) {

    $tmpName = $_FILES["dataArticulo"]["tmp_name"];
    $error = $_FILES["dataArticulo"]["error"];

    if ($error !== UPLOAD_ERR_OK) {
      echo json_encode(['success' => false, 'message' => 'Error al subir el archivo.']);
      exit;
    }

    // require_once "../PHPExcel/Classes/PHPExcel.php";
    require_once "../modelos/Consultas.php";
    require_once "../config/Conexion.php";

    $consultaObj = new Consultas();

    // $excel = PHPExcel_IOFactory::load($tmpName);
    // $excel->setActiveSheetIndex(0);
    // $numerofila = $excel->setActiveSheetIndex(0)->getHighestRow();

    $excel = \PhpOffice\PhpSpreadsheet\IOFactory::load($tmpName);
    $excel->setActiveSheetIndex(0);
    $numerofila = $excel->setActiveSheetIndex(0)->getHighestRow();

    for ($i = 2; $i <= $numerofila; $i++) {
      $codigo = $excel->getActiveSheet()->getCell('A' . $i)->getCalculatedValue();
      if ($codigo != "") {
        $nombre         = $excel->getActiveSheet()->getCell('B' . $i)->getCalculatedValue();
        $alias          = $excel->getActiveSheet()->getCell('C' . $i)->getCalculatedValue();
        $descrip        = $excel->getActiveSheet()->getCell('D' . $i)->getCalculatedValue();
        $familia        = $excel->getActiveSheet()->getCell('E' . $i)->getCalculatedValue();
        $marca          = $excel->getActiveSheet()->getCell('F' . $i)->getCalculatedValue();
        $costo_compra   = $excel->getActiveSheet()->getCell('G' . $i)->getCalculatedValue();
        $precio_venta   = $excel->getActiveSheet()->getCell('H' . $i)->getCalculatedValue();
        $precio_mayor   = $excel->getActiveSheet()->getCell('I' . $i)->getCalculatedValue();
        $stock          = $excel->getActiveSheet()->getCell('J' . $i)->getCalculatedValue();
        $tipoitem       = $excel->getActiveSheet()->getCell('K' . $i)->getCalculatedValue();
        $nombre_almacen = $excel->getActiveSheet()->getCell('L' . $i)->getCalculatedValue();
        // $saldo_iniu  = $excel->getActiveSheet()->getCell('I' . $i)->getCalculatedValue();
        // $valor_iniu  = $excel->getActiveSheet()->getCell('J' . $i)->getCalculatedValue();
        
        // $codigott    = $excel->getActiveSheet()->getCell('L' . $i)->getCalculatedValue();
        // $desctt      = $excel->getActiveSheet()->getCell('M' . $i)->getCalculatedValue();
        // $codigointtt = $excel->getActiveSheet()->getCell('N' . $i)->getCalculatedValue();
        // $nombrett    = $excel->getActiveSheet()->getCell('O' . $i)->getCalculatedValue();
        

        $consultaObj->insertarArticulosMasivo(
          $codigo, $nombre, $alias, $descrip, $familia,  $marca,  $costo_compra, $precio_venta, $precio_mayor, $stock, $tipoitem, $nombre_almacen
        );
      }
    }

    // Al final, envía una respuesta en formato JSON
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
  } else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'No se ha enviado un archivo válido.']);
  }
}

ob_end_flush();
