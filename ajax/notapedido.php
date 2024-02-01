<?php
if (strlen(session_id()) < 1) {	session_start(); }//Validamos si existe o no la sesión

require_once "../modelos/Notapedido.php";
require_once "../modelos/Numeracion.php";
require_once "../modelos/Persona.php";

$notapedido = new Notapedido();
$persona    = new Persona();

$idboleta     = isset($_POST["idboleta"]) ? limpiarCadena($_POST["idboleta"]) : "";
$toltip       = '<script> $(function () { $(\'[data-bs-toggle="tooltip"]\').tooltip(); }); </script>';
$imagen_error = "this.src='../files/logo/simagen.jpg'";

date_default_timezone_set('America/Lima');  $date_now = date("d_m_Y__h_i_s_A");

// =============== DATOS PARA NOTA DE VENTA =====================
$idusuario           = $_SESSION["idusuario"];
$fecha_emision_01    = isset($_POST["fecha_emision_01"]) ? limpiarCadena($_POST["fecha_emision_01"]) : "";
$firma_digital_36    = isset($_POST["firma_digital_36"]) ? limpiarCadena($_POST["firma_digital_36"]) : "";
$idempresa           = isset($_POST["idempresa"]) ? limpiarCadena($_POST["idempresa"]) : "";
$tipo_documento_06   = isset($_POST["tipo_documento_06"]) ? limpiarCadena($_POST["tipo_documento_06"]) : "";
$numeracion_07       = isset($_POST["numeracion_07"]) ? limpiarCadena($_POST["numeracion_07"]) : "";
$idcliente           = isset($_POST["idcliente"]) ? limpiarCadena($_POST["idcliente"]) : "";
$codigo_tipo_15_1    = isset($_POST["codigo_tipo_15_1"]) ? limpiarCadena($_POST["codigo_tipo_15_1"]) : "";
$monto_15_2          = isset($_POST["subtotal_notapedido"]) ? limpiarCadena($_POST["subtotal_notapedido"]) : "";

$sumatoria_igv_18_1  = isset($_POST["total_igv"]) ? limpiarCadena($_POST["total_igv"]) : "";
$sumatoria_igv_18_2  = isset($_POST["total_igv"]) ? limpiarCadena($_POST["total_igv"]) : "";
$codigo_tributo_18_3 = isset($_POST["codigo_tributo_18_3"]) ? limpiarCadena($_POST["codigo_tributo_18_3"]) : "";
$nombre_tributo_18_4 = isset($_POST["nombre_tributo_18_4"]) ? limpiarCadena($_POST["nombre_tributo_18_4"]) : "";
$codigo_internacional_18_5 = isset($_POST["codigo_internacional_18_5"]) ? limpiarCadena($_POST["codigo_internacional_18_5"]) : "";
$importe_total_23    = isset($_POST["total_final"]) ? limpiarCadena($_POST["total_final"]) : "";
$codigo_leyenda_26_1 = isset($_POST["codigo_leyenda_26_1"]) ? limpiarCadena($_POST["codigo_leyenda_26_1"]) : "";
$descripcion_leyenda_26_2  = isset($_POST["descripcion_leyenda_26_2"]) ? limpiarCadena($_POST["descripcion_leyenda_26_2"]) : "";

$tipo_documento_25_1   = isset($_POST["tipo_documento_25_1"]) ? limpiarCadena($_POST["tipo_documento_25_1"]) : "";
$guia_remision_25      = isset($_POST["guia_remision_25"]) ? limpiarCadena($_POST["guia_remision_25"]) : "";
$version_ubl_37        = isset($_POST["version_ubl_37"]) ? limpiarCadena($_POST["version_ubl_37"]) : "";
$version_estructura_38 = isset($_POST["version_estructura_38"]) ? limpiarCadena($_POST["version_estructura_38"]) : "";
$tipo_moneda_24        = isset($_POST["tipo_moneda_24"]) ? limpiarCadena($_POST["tipo_moneda_24"]) : "";
$tasa_igv              = isset($_POST["tasa_igv"]) ? limpiarCadena($_POST["tasa_igv"]) : "";
$estado                = isset($_POST["estado"]) ? limpiarCadena($_POST["estado"]) : "";

$tipo_doc_ide     = isset($_POST["tipo_doc_ide"]) ? limpiarCadena($_POST["tipo_doc_ide"]) : "";
$rucCliente       = isset($_POST["numero_documento"]) ? limpiarCadena($_POST["numero_documento"]) : "";
$RazonSocial      = isset($_POST["razon_social"]) ? limpiarCadena($_POST["razon_social"]) : "";
$fechavenc        = isset($_POST["fechavenc"]) ? limpiarCadena($_POST["fechavenc"]) : "";

$domicilio_fiscal = isset($_POST["domicilio_fiscal"]) ? limpiarCadena($_POST["domicilio_fiscal"]) : "";
$montocuota        = isset($_POST["montocuota"]) ? limpiarCadena($_POST["montocuota"]) : "";
$fechavecredito    = isset($_POST["fechavecredito"]) ? limpiarCadena($_POST["fechavecredito"]) : "";
$ccuotas           = isset($_POST["ccuotas"]) ? limpiarCadena($_POST["ccuotas"]) : "";
$total_icbper      = isset($_POST["total_icbper"]) ? limpiarCadena($_POST["total_icbper"]) : ""; //NUEVO POR BOLSAS
$saldo             = isset($_POST["saldo_final"]) ? limpiarCadena($_POST["saldo_final"]) : "";
$ipagado           = isset($_POST["ipagado_final"]) ? limpiarCadena($_POST["ipagado_final"]) : "";
$nroreferencia     = isset($_POST["nroreferencia"]) ? limpiarCadena($_POST["nroreferencia"]) : "";
$tipopago          = isset($_POST["tipopago"]) ? limpiarCadena($_POST["tipopago"]) : "";
$tdescuento        = isset($_POST["total_dcto"]) ? limpiarCadena($_POST["total_dcto"]) : "";
$tcambio           = isset($_POST["tcambio"]) ? limpiarCadena($_POST["tcambio"]) : "";
$tadc              = isset($_POST["tadc"]) ? limpiarCadena($_POST["tadc"]) : "";
$transferencia     = isset($_POST["trans"]) ? limpiarCadena($_POST["trans"]) : "";
$idserie           = isset($_POST["serie"]) ? limpiarCadena($_POST["serie"]) : "";
$SerieReal         = isset($_POST["SerieReal"]) ? limpiarCadena($_POST["SerieReal"]) : "";
$numero_notapedido = isset($_POST["numero_notapedido"]) ? limpiarCadena($_POST["numero_notapedido"]) : "";
$idnumeracion      = isset($_POST["idnumeracion"]) ? limpiarCadena($_POST["idnumeracion"]) : "";

$codigo_precio = isset($_POST["codigo_precio"]) ? limpiarCadena($_POST["codigo_precio"]) : "";
$hora          = isset($_POST["hora"]) ? limpiarCadena($_POST["hora"]) : "";
$vendedorsitio = isset($_POST["vendedorsitio"]) ? limpiarCadena($_POST["vendedorsitio"]) : "";
$cestado1      = isset($_POST["chestado"]) ? limpiarCadena($_POST["chestado"]) : "";
$tiponota      = isset($_POST["tiponota"]) ? limpiarCadena($_POST["tiponota"]) : "";

$ncotizacion = isset($_POST["ncotizacion"]) ? limpiarCadena($_POST["ncotizacion"]) : "";
$ambtra      = isset($_POST["ambtra"]) ? limpiarCadena($_POST["ambtra"]) : "";
$adelanto    = isset($_POST["adelanto"]) ? limpiarCadena($_POST["adelanto"]) : "";
$faltante    = isset($_POST["faltante"]) ? limpiarCadena($_POST["faltante"]) : "";
$efectivo    = isset($_POST["efectivo"]) ? limpiarCadena($_POST["efectivo"]) : "";
$visa        = isset($_POST["visa"]) ? limpiarCadena($_POST["visa"]) : "";
$yape        = isset($_POST["yape"]) ? limpiarCadena($_POST["yape"]) : "";
$plin        = isset($_POST["plin"]) ? limpiarCadena($_POST["plin"]) : "";
$mastercard  = isset($_POST["mastercard"]) ? limpiarCadena($_POST["mastercard"]) : "";
$deposito    = isset($_POST["deposito"]) ? limpiarCadena($_POST["deposito"]) : "";


switch ($_GET["op"]) {

  case 'autonumeracion':

    $numeracion = new Numeracion();
    $Ser = $_GET['ser'];
    $rspta = $numeracion->llenarNumeroNpedido($Ser);
    while ($reg = $rspta->fetch_object()) {
      echo $reg->Nnumero;
    }
  break;

  case 'guardaryeditarCambioestado':
    $rspta = $notapedido->actualizarestados($_POST["idnota"], $cestado1);
    echo $rspta ? "Comprobantes actualizados" : "No se pudierón actualizar";
  break;

  case 'guardaryeditarNotaPedido':

    if (empty($idboleta)) {

      if ($importe_total_23 >= 700) {

        if ($idcliente == "N") {
          //$tipo_doc_ide="1";
          $rspta = $persona->insertardeBoleta($RazonSocial, $tipo_doc_ide, $rucCliente, $domicilio_fiscal);
          $IdC = $persona->mostrarId();
          //para ultimo registro de cliente
          while ($reg = $IdC->fetch_object()) {  $idcl = $reg->idpersona;  }
          $rspta = $notapedido->insertar($idusuario, $fecha_emision_01, $firma_digital_36, $idempresa, $tipo_documento_06, $numeracion_07, $idcl, $codigo_tipo_15_1, $monto_15_2, $sumatoria_igv_18_1, $sumatoria_igv_18_2, $codigo_tributo_18_3, $nombre_tributo_18_4, $codigo_internacional_18_5, $importe_total_23, $codigo_leyenda_26_1, $descripcion_leyenda_26_2, $tipo_documento_25_1, $guia_remision_25, $version_ubl_37, $version_estructura_38, $tipo_moneda_24, $tasa_igv, $_POST["idarticulo"], $_POST["numero_orden_item_29"], $_POST["cantidad_item_12"], $_POST["codigo_precio_14_1"], $_POST["precio_unitario"], $_POST["igvBD"], $_POST["igvBD"], $_POST["afectacionigv"], $_POST["codigotributo"], '', '', $_POST["igvBD2"], $_POST["vvu"], $_POST["subtotalBD"], $_POST["codigo"], $_POST["unidad_medida"], $idserie, $SerieReal, $numero_notapedido, $tipo_doc_ide, $rucCliente, html_entity_decode($RazonSocial, ENT_QUOTES | ENT_HTML401, 'UTF-8'), $hora, $_POST["sumadcto"], $vendedorsitio, $tcambio, $tdescuento, $domicilio_fiscal, $tipopago, $nroreferencia, $ipagado, $saldo, $_POST["descdet"], $total_icbper, $tiponota, $_POST["cantidadreal"], $ccuotas, $fechavecredito, $montocuota, $tadc, $transferencia, $_POST["ncuotahiden"], $_POST["montocuotacre"], $_POST["fechapago"], $fechavenc, $efectivo, $visa, $yape, $plin, $mastercard, $deposito);
          echo $rspta ? "Se guardo Nota Pedido correctamente" : "No se guardo Nota Pedido";
        } else {
          $rspta = $notapedido->insertar($idusuario, $fecha_emision_01, $firma_digital_36, $idempresa, $tipo_documento_06, $numeracion_07, $idcliente, $codigo_tipo_15_1, $monto_15_2, $sumatoria_igv_18_1, $sumatoria_igv_18_2, $codigo_tributo_18_3, $nombre_tributo_18_4, $codigo_internacional_18_5, $importe_total_23, $codigo_leyenda_26_1, $descripcion_leyenda_26_2, $tipo_documento_25_1, $guia_remision_25, $version_ubl_37, $version_estructura_38, $tipo_moneda_24, $tasa_igv, $_POST["idarticulo"], $_POST["numero_orden_item_29"], $_POST["cantidad_item_12"], $_POST["codigo_precio_14_1"], $_POST["precio_unitario"], $_POST["igvBD"], $_POST["igvBD"], $_POST["afectacionigv"], $_POST["codigotributo"], '', '', $_POST["igvBD2"], $_POST["vvu"], $_POST["subtotalBD"], $_POST["codigo"], $_POST["unidad_medida"], $idserie, $SerieReal, $numero_notapedido, $tipo_doc_ide, $rucCliente, html_entity_decode($RazonSocial, ENT_QUOTES | ENT_HTML401, 'UTF-8'), $hora, $_POST["sumadcto"], $vendedorsitio, $tcambio, $tdescuento, $domicilio_fiscal, $tipopago, $nroreferencia, $ipagado, $saldo, $_POST["descdet"], $total_icbper, $tiponota, $_POST["cantidadreal"], $ccuotas, $fechavecredito, $montocuota, $tadc, $transferencia, $_POST["ncuotahiden"], $_POST["montocuotacre"], $_POST["fechapago"], $fechavenc, $efectivo, $visa, $yape, $plin, $mastercard, $deposito);
          echo $rspta ? "Se guardo Nota Pedido correctamente" : "No se guardo Nota Pedido";
        } //FIN DE SEGUNDO IF

      } else {
        // SI EL TOTAL ES MENOR DE 700

        if ($idcliente == "N") {
          $rspta = $persona->insertardeBoleta($RazonSocial, $tipo_doc_ide, $rucCliente, $domicilio_fiscal);
          $IdC = $persona->mostrarId();
          while ($reg = $IdC->fetch_object()) { $idcl = $reg->idpersona;  }
          $rspta = $notapedido->insertar($idusuario, $fecha_emision_01, $firma_digital_36, $idempresa, $tipo_documento_06, $numeracion_07, $idcl, $codigo_tipo_15_1, 
          $monto_15_2, $sumatoria_igv_18_1, $sumatoria_igv_18_2, $codigo_tributo_18_3, $nombre_tributo_18_4, $codigo_internacional_18_5, $importe_total_23, 
          $codigo_leyenda_26_1, $descripcion_leyenda_26_2, $tipo_documento_25_1, $guia_remision_25, $version_ubl_37, $version_estructura_38, $tipo_moneda_24, $tasa_igv, 
          $_POST["idarticulo"], $_POST["numero_orden_item_29"], $_POST["cantidad_item_12"], $_POST["codigo_precio_14_1"], $_POST["precio_unitario"], $_POST["igvBD"], 
          $_POST["igvBD"], $_POST["afectacionigv"], $_POST["codigotributo"], '', '', $_POST["igvBD2"], $_POST["vvu"], $_POST["subtotalBD"], $_POST["codigo"], 
          $_POST["unidad_medida"], $idserie, $SerieReal, $numero_notapedido, $tipo_doc_ide, $rucCliente, html_entity_decode($RazonSocial, ENT_QUOTES | ENT_HTML401, 'UTF-8'), 
          $hora, $_POST["sumadcto"], $vendedorsitio, $tcambio, $tdescuento, $domicilio_fiscal, $tipopago, $nroreferencia, $ipagado, $saldo, $_POST["descdet"], $total_icbper, 
          $tiponota, $_POST["cantidadreal"], $ccuotas, $fechavecredito, $montocuota, $tadc, $transferencia, $_POST["ncuotahiden"], $_POST["montocuotacre"], 
          $_POST["fechapago"], $fechavenc, $efectivo, $visa, $yape, $plin, $mastercard, $deposito);
          echo $rspta ? "Se guardo boleta correctamente" : "No se guardo boleta";
        } else {

          $rspta = $notapedido->insertar( $idusuario, $fecha_emision_01, $firma_digital_36, $idempresa, $tipo_documento_06, $numeracion_07, $idcliente, $codigo_tipo_15_1,
            $monto_15_2, $sumatoria_igv_18_1, $sumatoria_igv_18_2, $codigo_tributo_18_3, $nombre_tributo_18_4, $codigo_internacional_18_5, $importe_total_23, 
            $codigo_leyenda_26_1, $descripcion_leyenda_26_2, $tipo_documento_25_1, $guia_remision_25, $version_ubl_37, $version_estructura_38, $tipo_moneda_24, $tasa_igv, 
            $_POST["idarticulo"], $_POST["numero_orden_item_29"], $_POST["cantidad_item_12"], $_POST["codigo_precio_14_1"], $_POST["precio_unitario"], $_POST["igvBD"], 
            $_POST["igvBD"], $_POST["afectacionigv"], $_POST["codigotributo"], '', '', $_POST["igvBD2"], $_POST["vvu"], $_POST["subtotalBD"], $_POST["codigo"], 
            $_POST["unidad_medida"], $idserie, $SerieReal, $numero_notapedido, $tipo_doc_ide, $rucCliente, html_entity_decode($RazonSocial, ENT_QUOTES | ENT_HTML401, 'UTF-8'),
            $hora, $_POST["sumadcto"], $vendedorsitio, $tcambio, $tdescuento, $domicilio_fiscal, $tipopago, $nroreferencia, $ipagado, $saldo, $_POST["descdet"], $total_icbper,
            $tiponota, $_POST["cantidadreal"], $ccuotas, $fechavecredito, $montocuota, $tadc, $transferencia, $_POST["ncuotahiden"], $_POST["montocuotacre"], $_POST["fechapago"],
            $fechavenc, $efectivo, $visa, $yape, $plin, $mastercard, $deposito);
          echo $rspta ? "Se guardo boleta correctamente" : "No se guardo boleta";
        }
      }
    } // $######################## FIN DE IF SI ES MAYOR O MENOR A 700
  break;

  case 'anular':
    $rspta = $notapedido->anular($idnotaped);
    echo $rspta ? "Nota de pedido anulada" : "Nota de pedido no se puede anular";
  break;


  case 'baja':
    $com = $_GET['comentario'];
    $hor = $_GET['hora'];
    $hoy = date("Y-m-d");
    $rspta = $notapedido->baja($idboleta, $hoy, $com, $hor);
    echo $rspta ? "Nota de pedido de baja" : "Nota de pedido no se puede dar de baja";
  break;

  case 'actualizarNumero':
    require_once "../Modelos/Numeracion.php";
    $numeracion = new Numeracion();

    $num = $_GET['Num'];
    $idnumeracion = $_GET['Idnumeracion'];
    $rspta = $numeracion->UpdateNumeracion($num, $idnumeracion);
  break;

  case 'mostrar':
    $rspta = $notapedido->mostrar($idnotaped);
    //Codificar el resultado utilizando json
    echo json_encode($rspta);
  break;

  case 'listarDetalle':
    //Recibimos el idingreso
    $id = $_GET['id'];

    $rspta = $notapedido->listarDetalle($id);
    $subt = 0;
    $igv = 0;
    $total = 0;
    echo '
        <thead style="background-color:#A9D0F9">
                                    <th>Artículo</th>
                                    <th>Cantidad</th>
                                    <th>Precio Venta</th>
                                    <th>Subtotal</th>
                                </thead>';

    while ($reg = $rspta->fetch_object()) {
      echo '<tr class="filas"><td>' . $reg->nombre . '</td><td>' . $reg->cantidad_item_12 . '</td><td>' . $reg->valor_uni_item_14 . '</td><td>' . $reg->valor_venta_item_21 . '</td></tr>';

      $subt = $subt + ($reg->valor_venta_item_21);
      $igv = $igv + ($reg->igv_item);
      $total = $subt + $igv;
    }
    echo ' <tfoot>
                                    <th>SUBTOTAL <h4 id="subtotal">S/.' . $subt . '</h4></th>
                                    <th></th>
                                    <th>IGV  <h4 id="subtotal">S/.' . $igv . '</h4></th>
                                    <th></th>
                                    <th>TOTAL  <h4 id="total">S/.' . $total . '</h4></th>
                                    <th></th>
                                    <th></th>
                               </tfoot>

        ';
  break;


  case 'selectCliente':
    require_once "../modelos/Persona.php";
    $persona = new Persona();

    $rspta = $persona->listarC();

    while ($reg = $rspta->fetch_object()) {
      echo '<option value=' . $reg->idpersona . '>' . $reg->nombre . '</option>';
    }
  break;

  case 'selectClienteDocumento':
    require_once "../modelos/Persona.php";
    $persona = new Persona();

    $rspta = $persona->listarC();

    while ($reg = $rspta->fetch_object()) {
      echo '<option value=' . $reg->idpersona . '>' . $reg->num_documento . '</option>';
    }
  break;

  case 'selectSerie':
    require_once "../modelos/Numeracion.php";
    $numeracion = new Numeracion();

    $rspta = $numeracion->llenarSerieNpedido($idusuario);

    while ($reg = $rspta->fetch_object()) {
      echo '<option value=' . $reg->idnumeracion . '>' . $reg->serie . '</option>';
    }
  break;

  case 'llenarNumeroFactura':
    $tipoC = $_GET['tipoC'];
    $serieC = $_GET['serieC'];
    $rspta = $venta->sumarC($tipoC, $serieC);
    while ($reg = $rspta->fetch_object()) {
      echo $reg->addnumero;
    }
  break;

  case 'llenarNumeroBoleta':
    $tipoC = $_GET['tipoC'];
    $serieC = $_GET['serieC'];
    $rspta = $venta->sumarC($tipoC, $serieC);
    while ($reg = $rspta->fetch_object()) {
      echo $reg->addnumero;
    }
  break;

  case 'llenarnombrecli':
    require_once "../modelos/Persona.php";
    $persona = new Persona();
    $numdocu = $_GET['numcli']; //Se recibe de venta.js el parametro-->
    $rspta = $persona->listarcnumdocu($numdocu);
    while ($reg = $rspta->fetch_object()) {
      echo '<option value=' . $reg->idpersona . '>' . $reg->nombre . '</option>';
    }
  break;

  case 'llenarnumdocucli':
    require_once "../modelos/Persona.php";
    $persona = new Persona();
    $nomcli = $_GET['nomcli']; //*-Se recibe de venta.js el parametro-*
    $rspta = $persona->listarcnom($nomcli);
    while ($reg = $rspta->fetch_object()) {
      echo '<option value=' . $reg->idpersona . '>' . $reg->num_documento . '</option>';
    }
  break;

  case 'llenarIdcliente1':
    require_once "../modelos/Persona.php";
    $persona = new Persona();
    $numdocu = $_GET['numcli']; //Se recibe de venta.js el parametro-->
    $rspta = $persona->listarcnumdocu($numdocu);
    while ($reg = $rspta->fetch_object()) {
      echo $reg->idpersona;
    }
  break;


  case 'llenarIdcliente2':
    require_once "../modelos/Persona.php";
    $persona = new Persona();
    $nomcli = $_GET['nomcli']; //Se recibe de venta.js el parametro-->
    $rspta = $persona->listarcnom($nomcli);
    while ($reg = $rspta->fetch_object()) {
      echo $reg->idpersona;
    }
  break;


  case 'listarClientesNota':
    require_once "../modelos/Persona.php";
    $persona = new Persona();

    $rspta = $persona->listarCliVenta();
    //Vamos a declarar un array
    $data = array();

    while ($reg = $rspta->fetch_object()) {
      $data[] = array(
        "0" => '<button class="btn btn-warning" onclick="agregarCliente(' . $reg->idpersona . ',\'' . $reg->razon_social . '\',\'' . $reg->numero_documento . '\',\'' . $reg->domicilio_fiscal . '\',\'' . $reg->tipo_documento . '\')"><span class="fa fa-plus"></span></button>',
        "1" => $reg->razon_social,
        "2" => $reg->numero_documento,
        "3" => $reg->domicilio_fiscal
      );
    }
    $results = array(
      "sEcho" => 1,
      //Información para el datatables
      "iTotalRecords" => count($data),
      //enviamos el total registros al datatable
      "iTotalDisplayRecords" => count($data),
      //enviamos el total registros a visualizar
      "aaData" => $data
    );

    echo json_encode($results);
  break;

  case 'listarArticulosnota':
    
    $tipob = $_GET['tb'];
    $tipoprecio = $_GET['tprecio'];
    $tmm = $_GET['itm'];

    $almacen = $_GET['alm'];

    require_once "../modelos/Articulo.php";
    $articulo = new Articulo($_SESSION['idusuario'], $_SESSION['idempresa']);

    if ($tmm == '0') {
      $rspta = $articulo->listarActivosVentaumventa($_SESSION['idempresa'], $tipob, $almacen, $tipoprecio);

      $data = array();
      while ($reg = $rspta->fetch_object()) {
        $data[] = array(
          "0" => ($reg->stock <= $reg->limitestock) ? '<label style="color: red;">Limite stock es: </label>' . '<label style="color: red;">' . $reg->limitestock . '</label>' :
          ' <button class="btn btn-warning btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Agregar continuo" onclick="agregarDetalle(0,' . $reg->idarticulo . ',\'' . $reg->familia . '\',\'' . $reg->codigo_proveedor . '\',\'' . $reg->codigo . '\',\'' . htmlspecialchars($reg->nombre) . '\',\'' . $reg->precio_venta . '\',\'' . $reg->stock . '\',\'' . $reg->abre . '\' ,\'' . $reg->precio_unitario . '\',\'' . $reg->cicbper . '\', \'' . $reg->mticbperu . '\' , \'' . $reg->factorconversion . '\' , \'' . $reg->factorc . '\' , \'' . str_replace("\r\n", " ", $reg->descrip) . '\',  \'' . $reg->tipoitem . '\')">
            <i class="fa fa-clone" >  </i> 
          </button>'  .
          ' <button class="btn btn-success btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Agregar separado" onclick="agregarDetalle(1,' . $reg->idarticulo . ',\'' . $reg->familia . '\',\'' . $reg->codigo_proveedor . '\',\'' . $reg->codigo . '\',\'' . htmlspecialchars($reg->nombre) . '\',\'' . $reg->precio_venta . '\',\'' . $reg->stock . '\',\'' . $reg->abre . '\' ,\'' . $reg->precio_unitario . '\',\'' . $reg->cicbper . '\', \'' . $reg->mticbperu . '\' , \'' . $reg->factorconversion . '\' , \'' . $reg->factorc . '\' , \'' . str_replace("\r\n", " ", $reg->descrip) . '\',  \'' . $reg->tipoitem . '\'); cerrarModal();">
            <i class="fa fa-outdent" > </i> 
          </button>' . $toltip ,
          "1" => $reg->nombre,
          "2" => $reg->codigo,
          "3" => $reg->nombreum,
          "4" => $reg->precio_venta,
          "5" => $reg->factorconversion,
          //($tipob=="productos")? $reg->factorconversion : $reg->stock,
          "6" => ($reg->imagen == "") ? "<img src='../files/articulos/simagen.png' height='60px' width='60px'>" :  "<img src='../files/articulos/" . $reg->imagen . "' height='60px' width='60px'>",
          "7" => ($reg->imagen == "") ? "../files/articulos/simagen.png" : "../files/articulos/" . $reg->imagen,
          "8" => $reg->idarticulo
        );
      }
    } else {

      $rspta = $articulo->listarActivosVentaumcompra($_SESSION['idempresa'], $tipob, $almacen, $tipoprecio);

      $data = array();
      while ($reg = $rspta->fetch_object()) {
        $data[] = array(
          "0" => ($reg->stock <= $reg->limitestock) ? '<label style="color: red;">Limite stock es: </label>' . '<label style="color: red;">' . $reg->limitestock . '</label>'
            :
            '<button class="btn btn-warning" onclick="agregarDetalleItem(' . $reg->idarticulo . ',\'' . $reg->familia . '\',\'' . $reg->codigo_proveedor . '\',\'' . $reg->codigo . '\',\'' . htmlspecialchars($reg->nombre) . '\',\'' . $reg->precio_venta . '\',\'' . $reg->stock . '\',\'' . $reg->abre . '\' ,\'' . $reg->precio_unitario . '\',\'' . $reg->cicbper . '\', \'' . $reg->mticbperu . '\', \'' . $reg->factorconversion . '\' , \'' . $reg->factorc . '\', \'' . str_replace("\r\n", " ", $reg->descrip) . '\',  \'' . $reg->factorconversion . '\')"><span class="fa fa-plus"></span></button>',
          "1" => $reg->nombre,
          "2" => $reg->codigo,
          "3" => $reg->nombreum,
          "4" => $reg->precio_venta,
          "5" => $reg->stock,
          "6" => ($reg->imagen == "") ? "<img src='../files/articulos/simagen.png' height='120px' width='120px'>" :
            "<img src='../files/articulos/" . $reg->imagen . "' height='120px' width='120px'>",
          "7" => ($reg->imagen == "") ? "../files/articulos/simagen.png" :
            "../files/articulos/" . $reg->imagen,
          "8" => $reg->idarticulo
        );
      }
    }

    $results = array(
      "sEcho" => 1,   //Información para el datatables
      "iTotalRecords" => count($data), //enviamos el total registros al datatable
      "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
      "aaData" => $data
    );
    echo json_encode($results);
  break;

  case 'listarArticulosboletaItem':
    require_once "../modelos/Articulo.php";
    $articulo = new Articulo($_SESSION['idusuario'], $_SESSION['idempresa']);
    $tipob = $_GET['tb'];

    $rspta = $articulo->listarActivosVentaumventa($_SESSION['idempresa'], "productos", '', '');

    $data = array();
    while ($reg = $rspta->fetch_object()) {
      $data[] = array(
        "0" => '<button class="btn btn-warning" onclick="agregarDetalleitem(' . $reg->idarticulo . ',\'' . $reg->familia . '\',\'' . $reg->codigo_proveedor . '\',\'' . $reg->codigo . '\',\'' . htmlspecialchars($reg->nombre) . '\',\'' . $reg->precio_venta . '\',\'' . $reg->stock . '\',\'' . $reg->abre . '\' ,\'' . $reg->precio_unitario . '\',\'' . $reg->cicbper . '\', \'' . $reg->mticbperu . '\')"><span class="fa fa-plus"></span></button>',
        "1" => $reg->nombre,
        "2" => $reg->codigo,
        "3" => $reg->unidad_medida,
        "4" => $reg->precio_venta,
        "5" => number_format($reg->stock, 2),
        "6" => "" //"<img src='../files/articulos/".$reg->imagen."' height='50px' width='50px' >"
      );
    }
    $results = array(
      "sEcho" => 1,
      //Información para el datatables
      "iTotalRecords" => count($data),
      //enviamos el total registros al datatable
      "iTotalDisplayRecords" => count($data),
      //enviamos el total registros a visualizar
      "aaData" => $data
    );
    echo json_encode($results);
  break;


  case 'listarArticulosnotaxcodigo':
    require_once "../modelos/Articulo.php";
    $articulo = new Articulo($_SESSION['idusuario'], $_SESSION['idempresa']);
    $idempresa = $_GET['idempresa'];
    $codigob = $_GET['codigob'];
    $rspta = $articulo->listarActivosVentaxCodigo($codigob, $idempresa);
    echo json_encode($rspta);
  break;


  case 'listarArticulosservicio':
    require_once "../modelos/Articulo.php";
    $bienservicio = new Articulo($_SESSION['idusuario'], $_SESSION['idempresa']);
    $rspta = $bienservicio->listarActivosVentaSoloServicio($_SESSION['idempresa']);
    //Vamos a declarar un array
    $data = array();
    while ($reg = $rspta->fetch_object()) {
      $data[] = array(
        "0" => '<button class="btn btn-warning" onclick="agregarDetalleItem(' . $reg->idarticulo . ',\'' . $reg->familia . '\',\'' . $reg->codigo_proveedor . '\',\'' . $reg->codigo . '\',\'' . htmlspecialchars($reg->nombre) . '\',\'' . $reg->precio_venta . '\',\'' . $reg->stock . '\',\'' . $reg->unidad_medida . '\' ,\'' . $reg->precio_unitario . '\',\'' . $reg->cicbper . '\', \'' . $reg->mticbperu . '\')"><span class="fa fa-plus"></span></button>',
        "1" => $reg->nombre,
        "2" => $reg->codigo,
        "3" => $reg->unidad_medida,
        "4" => $reg->precio_venta,
        "5" => number_format($reg->stock, 2),
        "6" => "" //"<img src='../files/articulos/".$reg->imagen."' height='50px' width='50px' >"
      );
    }
    $results = array(
      "sEcho" => 1,
      //Información para el datatables
      "iTotalRecords" => count($data),
      //enviamos el total registros al datatable
      "iTotalDisplayRecords" => count($data),
      //enviamos el total registros a visualizar
      "aaData" => $data
    );
    echo json_encode($results);
  break;



  case 'listar':
    $rspta = $notapedido->listar();
    //Vamos a declarar un array
    $data = array();

    while ($reg = $rspta->fetch_object()) {

      $urlT = '../reportes/exNotapedidoTicket.php?id=';
      $urlB = '../reportes/exNotapedido.php?id=';
      $urlC = '../reportes/exNotapedidocompleto.php?id=';
      $urlA4 = '../reportes/A4_nota_pedido.php?id=';

      if ($reg->tipo_documento_06 == 'Ticket') {
        $url = '../reportes/exNotapedidoTicket.php?id=';
      } else {
        $url = '../reportes/exNotapedido.php?id=';
      }     

      $stt = '';
      if ($reg->estado == '5' || $reg->estado == '4') { $stt = 'none'; } else { $send = 'none'; }
      if ($reg->estado == '3') { $stt = 'none';   }

      $data[] = array(
        "0" => '<div class="btn-group mb-1">
          <div class="dropdown">
            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Acciones</button>
            <div class="dropdown-menu" style="">                    
              <a  style="visible:' . $stt . ';" class="dropdown-item" onclick="baja(' . $reg->idboleta . ')"> Dar de baja</a>
              <a hidden class="dropdown-item" target="_blank" onclick="nota2Hojas(' . $reg->idboleta . ')"> Imprimir 2 Copias</a>
              <a class="dropdown-item"  onclick="preticket2(' . $reg->idboleta . ')">Imprimir Ticket</a>                    
              <a class="dropdown-item" target="_blank" href="'.$urlA4.$reg->idboleta.'&idboleta='.$reg->idboleta.'"  >Imprimir A4</a>                    
            </div>
          </div>
        </div>'
        ,
        "1" => $reg->fecha,
        "2" => $reg->nombres,
        "3" => $reg->vendedorsitio,
        "4" => $reg->numeracion_07,
        "5" => $reg->monto_15_2,
        "6" => $reg->adelanto,
        "7" => $reg->faltante,
        // "8" => $reg->importe_total_23,

        //Actualizado ===============================================
        "8" => ($reg->estado == '1') //si esta emitido
          ? '<span class="badge bg-success-transparent"><i class="ri-check-fill align-middle me-1"></i>Emitido</span>'
          : (($reg->estado == '4') ? '<i class="fa fa-thumbs-up" style="font-size: 18px; color:#239B56;"> <span>Firmado</span></i>' //si esta firmado

            : (($reg->estado == '3') ? '<i class="fa fa-dot-circle-o" style="font-family: arial; color:red;"> De baja</i> ' // si esta de baja

              : (($reg->estado == '0') ? '<i class="fa fa-dot-circle-o" style="font-size: 18px; color:#E59866;"> <span>Con Nota cd</span></i> ' // si esta de baja

                : (($reg->estado == '5') ? '<span>EMITIDO</span>' // Si esta aceptado por SUNAT

                  : '<i class="fa fa-thumbs-down" style="font-size: 18px; color:#922B21;"> <span>Anulado</span></i> ')))) //Si esta anulado
        //Actualizado ===============================================

      );
    }
    $results = array(
      "sEcho" => 1,
      //Información para el datatables
      "iTotalRecords" => count($data),
      //enviamos el total registros al datatable
      "iTotalDisplayRecords" => count($data),
      //enviamos el total registros a visualizar
      "aaData" => $data
    );
    echo json_encode($results);

  break;

  case 'listarClientesNotaxDoc':
    require_once "../modelos/Persona.php";
    $persona = new Persona();
    $doc = $_GET['doc'];
    $rspta = $persona->buscarClientexDocBoleta($doc);

    echo json_encode($rspta);

  break;

  case 'consultarcdr':
    $rspta = $boleta->reconsultarcdr($idboleta, $_SESSION['idempresa']);
    echo $rspta;
  break;

  case 'tcambiog':
    $tcf = $_GET['feccf'];
    $rspta = $boleta->mostrartipocambio($tcf);
    echo json_encode($rspta);
  break;


  case 'consultaDniSunat':
    $token = 'apis-token-1.aTSI1U7KEuT-6bbbCguH-4Y8TI6KS73N';
    $nndnii = $_GET['nrodni'];

    // Iniciar llamada a API
    $curl = curl_init();

    curl_setopt_array(
      $curl,
      array(
        CURLOPT_URL => 'https://api.apis.net.pe/v1/dni?numero=' . $nndnii,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 2,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
          'Referer: https://apis.net.pe/consulta-dni-api',
          'Authorization: Bearer' . $token
        ),
      )
    );

    $response = curl_exec($curl);

    curl_close($curl);
    // Datos listos para usar
    $datosDniCli = json_decode($response);
    echo json_encode($datosDniCli);
  break;

  case 'cambiartransferencia':
    $opc = $_GET['opcion'];
    $rspta = $boleta->cambiartransferencia($idboleta, $opc);
    echo $rspta ? "Cambio realizado correctamente" : "Problemas al cambiar";
  break;

  /* case 'enviarcorreo':
    $rspta = $notapedido->enviarcorreo($idnotaped);
    echo $rspta;
  break; */

  case 'listarcomprobantesclientesEstado':
    $dnicliente = $_GET['dnicliente'];
    $rspta = $notapedido->listarcomprobantes($dnicliente);
    $data = array();
    while ($reg = $rspta->fetch_object()) {
      $data[] = array(
        "0" => '<a onclick="agregarClientesComprobantes(' . $reg->idboleta . ',\'' . $reg->fecha . '\',\'' . $reg->nombres . '\',\'' . $reg->numeroserie . '\',\'' . $reg->total . '\')" data-toggle="tooltip" title="Agregar">+</a>',
        "1" => $reg->fecha,
        "2" => $reg->nombres,
        "3" => $reg->numeroserie,
        "4" => $reg->total,
        "5" => ($reg->estado == '1') ? '<span>EMITIDO</span>'
          : (($reg->estado == '5') ? '<span>PAGADO</span>' :
            '<span>ANULADO</span>')
      );
    }
    $results = array(
      "sEcho" => 1,
      //Información para el datatables
      "iTotalRecords" => count($data),
      //enviamos el total registros al datatable
      "iTotalDisplayRecords" => count($data),
      //enviamos el total registros a visualizar
      "aaData" => $data
    );
    echo json_encode($results);
  break;

  case 'listarcomprobantes':
    $rspta = $notapedido->listarcomprobantesCE();
    $data = array();
    while ($reg = $rspta->fetch_object()) {
      $data[] = array(
        "0" => '<a onclick="agregarDetalleEstadocliente(' . $reg->idboleta . ',\'' . $reg->fecha . '\',\'' . $reg->nombres . '\',\'' . $reg->numeroserie . '\',\'' . $reg->total . '\',\'' . $reg->estado . '\')" data-toggle="tooltip" title="Agregar">+</a>',
        "1" => $reg->fecha,
        "2" => $reg->nombres,
        "3" => $reg->numeroserie,
        "4" => $reg->total,
        "5" => ($reg->estado == '1') ? '<span>EMITIDO</span>'
          : (($reg->estado == '5') ? '<span>PAGADO</span>' :
            '<span>ANULADO</span>')
      );
    }
    $results = array(
      "sEcho" => 1,
      //Información para el datatables
      "iTotalRecords" => count($data),
      //enviamos el total registros al datatable
      "iTotalDisplayRecords" => count($data),
      //enviamos el total registros a visualizar
      "aaData" => $data
    );
    echo json_encode($results);
  break;

  case 'selectAlmacen':
    $rspta = $notapedido->almacenlista();
    while ($reg = $rspta->fetch_object()) {
      echo '<option value=' . $reg->idalmacen . '>' . $reg->nombre . '</option>';
    }
  break;

  case 'selectunidadmedida':
    require_once "../modelos/Consultas.php";
    $consulta = new Consultas();
    $iiddaa = $_GET['idar'];
    $rspta = $consulta->selectumedidadearticulo($iiddaa);
    while ($reg = $rspta->fetch_object()) {  echo '<option value=' . $reg->abre . '>' . $reg->nombreum . '</option>'; }
  break;

  case 'mostrarultimocomprobanteId':
    $rspta = $notapedido->mostrarultimocomprobanteId($_SESSION['idempresa']);
    echo json_encode($rspta);
  break;
}
