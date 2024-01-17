<?php
require '../vendor/autoload.php';
use Luecano\NumeroALetras\NumeroALetras;
//Activamos el almacenamiento en el buffer
ob_start();
if (strlen(session_id()) < 1){session_start();}  

if (!isset($_SESSION["nombre"])) {
  echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
} else {
  if ($_SESSION['Ventas'] == 1) {
  ?>
    <html>

    <head>
      <meta http-equiv="content-type" content="text/html; charset=utf-8" />
      <link href="../public/css/ticket.css" rel="stylesheet" type="text/css"> 
    </head>

    <body onload="window.print();">
      <?php

      //Incluímos la clase Venta
      require_once "../modelos/Factura.php";
      require_once "../modelos/Notacd.php";
      $factura = new Factura();
      $notacd = new Notacd();
      $numero_a_letra = new NumeroALetras();

      $datos = $factura->datosemp($_SESSION['idempresa']);
      //En el objeto $rspta Obtenemos los valores devueltos del método ventacabecera del modelo
      $rspta = $notacd->cabecerancreditoBol($_GET["id"], $_SESSION['idempresa']);

      $tipodoc = $_GET['tipodoc'];
      if ($tipodoc == "01") {
        $rspta = $notacd->cabecerancreditoFac($_GET["id"], $_SESSION['idempresa']);
      } else {
        $rspta = $notacd->cabecerancreditoBol($_GET["id"], $_SESSION['idempresa']);
      }

      //Recorremos todos los valores obtenidos
      $reg = $rspta->fetch_object();
      $datose = $datos->fetch_object();

      $logo = "../files\logo\\" . $datose->logo

      ?>
      <!-- <div class="zona_impresion"> -->
      <!-- codigo imprimir -->
      <br>
      <!-- Detalle de empresa -->
      <table border="0" align="center" width="230px">
        <tbody>
          <tr><td align="center"><img src="<?php echo $logo; ?>" width="<?php echo ($datose->logo_c_r == 0 ? 150 : 100);?>"></td></tr>
          <tr align="center"><td style="font-size: 14px">.::<strong> <?php echo utf8_decode(htmlspecialchars_decode($datose->nombre_comercial)) ?> </strong>::.</td></tr>
          <tr align="center"><td style="font-size: 10px"> <?php echo $datose->nombre_razon_social; ?> </td></tr>
          <tr align="center"><td style="font-size: 14px"> <strong> R.U.C. <?php echo $datose->numero_ruc; ?> </strong> </td></tr>
          <tr align="center"><td style="font-size: 10px"> <?php echo utf8_decode($datose->domicilio_fiscal) . ' <br> ' . $datose->telefono1 . "-" . $datose->telefono2; ?> </td></tr>
          <tr align="center"><td style="font-size: 10px"> <?php echo utf8_decode(strtolower($datose->correo)); ?> </td></tr>
          <tr align="center"><td style="font-size: 10px"> <?php echo utf8_decode(strtolower($datose->web)); ?> </td></tr>
          <tr><td style="text-align: center;">--------------------------------------------------------</td></tr>
          <tr><td align="center"> <strong> NOTA DE CRÉDITO ELECTRÓNICA </strong> <br> <b style="font-size: 14px"><?php echo $reg->numerncd; ?> </b></td></tr>
          <tr><td style="text-align: center;">--------------------------------------------------------</td></tr>
        </tbody>
      </table>

      <!-- Datos cliente -->
      <table border="0" align="center" width="230px">
        <tbody>
          <tr align="left"><td><strong>Cliente:</strong> <?php echo $reg->cliente; ?> </td> </tr>
          <tr align="left"><td><strong>Documento:</strong> DNI - <?php echo $reg->numero_documento; ?></td> </tr>
          <tr align="left"><td><strong>Dirección:</strong> <?php echo $reg->domicilio; ?></td></tr>
          <tr align="left"><td><strong>Comprobante original:</strong> <?php echo $reg->nboleta; ?></td></tr>
          <tr align="left"><td><strong>Fecha de emisión:</strong> <?php echo $reg->femision; ?> </td></tr>          
          <tr align="left"><td><strong>Fecha comprobante que se modifica: </strong><?php echo $reg->femisionbol; ?> </td></tr>          
          <tr align="left"><td><strong>Moneda:</strong> SOLES</td> </tr>          
          <tr align="left"><td><strong>Atención:</strong> <?php echo $reg->vendedorsitio; ?> </td></tr>
          
        </tbody>
      </table>     

      <br>

      <!-- Mostramos los detalles de la venta en el documento HTML -->
      <table border="0" width="230px" align="center" style="font-size: 14px;">
        <tr><td colspan="5">--------------------------------------------------------</td> </tr>
        <tr><th>Cant.</th> <th align="left">Producto</th> <th>P.u.</th> <th>Importe</th> </tr>
        <tr><td colspan="5">--------------------------------------------------------</td></tr>

        <?php

        //======= PARA EXTRAER EL HASH DEL DOCUMENTO FIRMADO ========================================
        require_once "../modelos/Rutas.php";
        $rutas = new Rutas();
        $Rrutas = $rutas->mostrar2($_SESSION['idempresa']);
        $Prutas = $Rrutas->fetch_object();
        $rutafirma = $Prutas->rutafirma; // ruta de la carpeta FIRMA
        $data[0] = "";

        //===========PARA EXTRAER EL CODIGO HASH =============================
        if ($reg->estado == '5') {
          $notaFirm = $reg->numero_ruc . "-" . $reg->codigo_nota . "-" . $reg->numerncd;
          $sxe = new SimpleXMLElement($rutafirma . $notaFirm . '.xml', null, true);
          $urn = $sxe->getNamespaces(true);
          $sxe->registerXPathNamespace('ds', $urn['ds']);
          $data = $sxe->xpath('//ds:DigestValue');
        } else {
          $data[0] = "";
        }
        //==================== PARA IMAGEN DEL CODIGO HASH ================================================
        //set it to writable location, a place for temp generated PNG files
        $PNG_TEMP_DIR = dirname(__FILE__) . DIRECTORY_SEPARATOR . '/generador-qr/temp' . DIRECTORY_SEPARATOR;
        //html PNG location prefix
        $PNG_WEB_DIR = 'temp/';
        include 'generador-qr/phpqrcode.php';

        //ofcourse we need rights to create temp dir
        if (!file_exists($PNG_TEMP_DIR)) {
          mkdir($PNG_TEMP_DIR);
        }
        $filename = $PNG_TEMP_DIR . 'test.png';
        $dataTxt = $reg->numero_ruc . "|" . $reg->codigo_nota . "|" . $reg->serie . "|" . $reg->numeronota . "|" . $reg->igv . "|" . $reg->total . "|" . $reg->femision . "|" . $reg->femision . "|" . $reg->numerncd . "|";

        $errorCorrectionLevel = 'H';
        $matrixPointSize = '2';
        $filename = 'generador-qr/temp/test' . md5($dataTxt . '|' . $errorCorrectionLevel . '|' . $matrixPointSize) . '.png';
        QRcode::png($dataTxt, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
        //default data
        //QRcode::png('PHP QR Code :)', $filename, $errorCorrectionLevel, $matrixPointSize, 2);    
        //display generated file
        $PNG_WEB_DIR . basename($filename);
        // //==================== PARA IMAGEN  ================================================
        $logoQr = $filename;
        $ext_logoQr = substr($filename, strpos($filename, '.'), -4);
        //$pdf->ImgQrN($logoQr, $ext_logoQr);

        $tipodoc = $_GET['tipodoc'];

        if ($tipodoc == "03") {
          $rsptad = $notacd->detalleNotacreditoBol($_GET["id"], $_SESSION['idempresa'], $reg->nboleta);
          //$rsptad = $notacd->detalleNotacredito($_GET["id"], $_SESSION['idempresa']);
          $cantidad = 0;
          while ($regd = $rsptad->fetch_object()) {
            echo "<tr>";
            echo "<td>" . $regd->cantidad . "</td>";
            echo "<td>" . strtolower($regd->articulo) . "</td>";
            echo "<td>" . $regd->precio_venta . "</td>";
            echo "<td align='right'>" . $regd->subtotal . "</td>";
            echo "</tr>";
            $cantidad += $regd->cantidad;
          }
        }

        if ($tipodoc == "01") {
          $rsptad = $notacd->detalleNotacredito($_GET["id"], $_SESSION['idempresa'], $reg->nboleta);
          //$rsptad = $notacd->detalleNotacredito($_GET["id"], $_SESSION['idempresa']);
          $cantidad = 0;
          while ($regd = $rsptad->fetch_object()) {
            echo "<tr>";
            echo "<td>" . $regd->cantidad . "</td>";
            echo "<td>" . strtolower($regd->articulo) . "</td>";
            echo "<td>" . $regd->precio_venta . "</td>";
            echo "<td align='right'>" . $regd->subtotal . "</td>";
            echo "</tr>";
            $cantidad += $regd->cantidad;
          }
        }

        ?>
      </table>

      <!-- Division -->
      <table border='0'  align='center' width='230px' style='font-size: 12px' >
        <tr><td>--------------------------------------------------------</td></tr>
        <tr></tr>
      </table>

      <table border='0' width='230px' align="center">
        <tr><td colspan='5'><strong>Total descuento</strong></td><td>:</td> <td style="text-align: right;">0.00</td></tr>
        <tr><td colspan='5'><strong>OP. gravada</strong></td>    <td>:</td> <td style="text-align: right;"><?php echo $reg->subtotal ?></td></tr>
        <tr><td colspan='5'><strong>OP. exonerado</strong></td>  <td>:</td> <td style="text-align: right;">0.00</td></tr>
        <tr><td colspan='5'><strong>OP. inafecto</strong></td>   <td>:</td> <td style="text-align: right;">0.00</td></tr>
        <tr><td colspan='5'><strong>I.G.V. 18.00</strong></td>   <td>:</td> <td style="text-align: right;"><?php echo $reg->igv ?></td></tr>
      </table>

      <?php $num_total = $numero_a_letra->toInvoice( $reg->total, 2, " SOLES" ); ?>

      <!-- Mostramos los totales de la venta en el documento HTML -->
      <table border='0' align="center" width='230px' style='font-size: 12px' >
        <tr><td><strong>Importe a pagar </strong></td> <td>:</td> <td style="text-align: right;"><strong> <?php echo $reg->total ?> </strong></td></tr>        
        <tr><td colspan="3">--------------------------------------------------------</td></tr>
        <tr><td colspan="3"><strong>Son: </strong> <?php echo $num_total; ?> </td></tr>
        <tr><td colspan="3">--------------------------------------------------------</td></tr>
      </table>

      <div style="text-align: center;">
        <img src=<?php echo $logoQr; ?> width="90" height="90"><br>
        <label>  <?php echo $data[0] ?> </label>
        <br>
        <br>
        <label>Representación impresa de la Nota de<br>Credito Electrónica puede ser consultada<br>en
          <?php echo utf8_decode(htmlspecialchars_decode($datose->webconsul)) ?>
        </label>
        <br>
        <br>
        <label><strong>::.GRACIAS POR SU PREFERENCIA.::</strong></label>
      </div>
            
      <br>      

      <!-- </div> -->
      <p>&nbsp;</p>

    </body>

    </html>
<?php
  } else {
    echo 'No tiene permiso para visualizar el reporte';
  }
}
ob_end_flush();
?>