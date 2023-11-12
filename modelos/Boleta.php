<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
class Boleta
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  //Implementamos un método para insertar registros para boleta

  public function insertar(
    $idusuario, $fecha_emision_01, $firma_digital_36, $idempresa, $tipo_documento_06, $numeracion_07, $idcl, $codigo_tipo_15_1, $monto_15_2, $sumatoria_igv_18_1, 
    $sumatoria_igv_18_2, $sumatoria_igv_18_3, $sumatoria_igv_18_4, $sumatoria_igv_18_5, $importe_total_23, $codigo_leyenda_26_1, $descripcion_leyenda_26_2,
    $tipo_documento_25_1, $guia_remision_25, $version_ubl_37, $version_estructura_38, $tipo_moneda_24, $tasa_igv, $idarticulo, $numero_orden_item_29, $cantidad_item_12, 
    $codigo_precio_14_1, $precio_unitario, $igvBD, $igvBD2, $afectacion_igv_3, $afectacion_igv_4, $afectacion_igv_5, $afectacion_igv_6, $igvBD3, $vvu, $subtotalBD,
    $codigo, $unidad_medida, $idserie, $SerieReal, $numero_boleta, $tipodocuCliente, $rucCliente, $RazonSocial, $hora, $dctoitem, $vendedorsitio, $tcambio, $totaldescu,
    $domicilio_fiscal, $tipopago, $nroreferencia, $ipagado, $saldo, $descdet, $total_icbper, $tipoboleta, $cantidadreal, $ccuotas, $fechavecredito, $montocuota, $tadc,
    $transferencia, $ncuotahiden, $montocuotacre, $fechapago, $fechavenc, $efectivo, $visa, $yape, $plin, $mastercard, $deposito ) {

    $st = '1';

    if ($SerieReal == '0001' || $SerieReal == '0002') { $st = '6'; }

    $formapago = ''; $montofpago = ''; $monedafpago = '';

    if ($tipopago == 'Contado') { $formapago = 'Contado'; } else {
      $formapago = 'Credito';
      $montofpago = $importe_total_23;
      $monedafpago = $tipo_moneda_24;
    }

    $montotar = 0;
    $montotran = 0;
    if ($tadc == '1') { $montotar = $importe_total_23; }

    if ($transferencia == '1') { $montotran = $importe_total_23; }

    $sql = "INSERT INTO boleta (idusuario, fecha_emision_01, firma_digital_36, idempresa, tipo_documento_06, numeracion_07, idcliente, codigo_tipo_15_1, monto_15_2, sumatoria_igv_18_1, 
    sumatoria_igv_18_2, codigo_tributo_18_3, nombre_tributo_18_4, codigo_internacional_18_5, importe_total_23, codigo_leyenda_26_1, descripcion_leyenda_26_2,  tipo_documento_25_1, guia_remision_25, version_ubl_37, 
    version_estructura_38, tipo_moneda_24, tasa_igv, estado, tipodocuCliente, rucCliente, RazonSocial, tdescuento, vendedorsitio, tcambio, 
    tipopago, nroreferencia, ipagado, saldo, DetalleSunat, icbper, tipoboleta, formapago, montofpago, monedafpago, 
    ccuotas, fechavecredito, montocuota, tarjetadc, transferencia, montotarjetadc, montotransferencia, fechavenc, efectivo, visa,
    yape, plin, mastercard, deposito )
    values
    ('$idusuario', '$fecha_emision_01 $hora', '$firma_digital_36', '$idempresa', '$tipo_documento_06', '$SerieReal-$numero_boleta', '$idcl', '$codigo_tipo_15_1', '$monto_15_2', '$sumatoria_igv_18_1',
    '$sumatoria_igv_18_2', (select codigo from catalogo5 where codigo='$sumatoria_igv_18_3'), (select descripcion from catalogo5 where codigo='$sumatoria_igv_18_3'), (select unece5153 from catalogo5 where codigo='$sumatoria_igv_18_3'), '$importe_total_23', '$codigo_leyenda_26_1', '$descripcion_leyenda_26_2', '$tipo_documento_25_1', '$guia_remision_25', '$version_ubl_37',
    '$version_estructura_38', '$tipo_moneda_24', '$tasa_igv', '$st', '$tipodocuCliente', '$rucCliente', '$RazonSocial', '$totaldescu', '$vendedorsitio', '$tcambio',
    '$formapago', '$nroreferencia', '$ipagado', '$saldo', 'EMITIDO', '$total_icbper', '$tipoboleta', '$formapago', '$montofpago', '$monedafpago',
    '$ccuotas', '$fechavecredito', '$montocuota', '$tadc', '$transferencia', '$montotar', '$montotran', '$fechavenc', '$efectivo', '$visa',
    '$yape', '$plin', '$mastercard', '$deposito')";
    //return ejecutarConsulta($sql);
    $idBoletaNew = ejecutarConsulta_retornarID($sql);
    $sw = true; 

    try {
      // SI EL NUMERO DE COMPROBANTE YA EXISTE NO HARA LA OPERACIon
      if ($idBoletaNew == "") {
        $sw = false;
        $idserie = "";
      } else {
        //=======================================================================

        $ii = 0;

        while ($ii < count($idarticulo)) {
          //Guardar en Detalle
          $sql_detalle = "INSERT INTO detalle_boleta_producto(
          idboleta,  idarticulo, numero_orden_item_29, cantidad_item_12, codigo_precio_14_1, 
          precio_uni_item_14_2, afectacion_igv_item_monto_27_1, afectacion_igv_item_monto_27_2, afectacion_igv_3, afectacion_igv_4, 
          afectacion_igv_5, afectacion_igv_6, igv_item, valor_uni_item_31, valor_venta_item_32, dcto_item, descdet, umedida )
          values (
          '$idBoletaNew', '$idarticulo[$ii]', '$numero_orden_item_29[$ii]', '$cantidad_item_12[$ii]', '$codigo_precio_14_1', 
          '$precio_unitario[$ii]', '$igvBD[$ii]', '$igvBD2[$ii]',(select codigo from catalogo7 where codigo='$afectacion_igv_3[$ii]'), (select codigo from catalogo5 where codigo='$afectacion_igv_4[$ii]'),
          (select descripcion from catalogo5 where codigo='$afectacion_igv_4[$ii]'), (select unece5153 from catalogo5 where codigo='$afectacion_igv_4[$ii]'), 
          '$igvBD3[$ii]', '$vvu[$ii]', '$subtotalBD[$ii]', '$dctoitem[$ii]', '$descdet[$ii]', '$unidad_medida[$ii]' )";

          //Guardar en Kardex
          $sql_kardex = "INSERT INTO kardex (idcomprobante, idarticulo, transaccion, codigo, fecha, tipo_documento, numero_doc, cantidad, costo_1, unidad_medida,
          saldo_final, costo_2, valor_final, idempresa, tcambio, moneda) 
          values
          ('$idBoletaNew', '$idarticulo[$ii]', 'VENTA', '$codigo[$ii]', '$fecha_emision_01', '03' , '$SerieReal-$numero_boleta', '$cantidadreal[$ii]', '$vvu[$ii]', '$unidad_medida[$ii]',
          '' , '' , '' , '$idempresa','$tcambio', '$tipo_moneda_24')";

          $sqlupdatecliente = "UPDATE persona set domicilio_fiscal='$domicilio_fiscal', razon_social='$RazonSocial', nombre_comercial='$RazonSocial', nombres='$RazonSocial'  where idpersona='$idcl'";

          ejecutarConsulta($sql_detalle);
          ejecutarConsulta($sql_kardex);
          ejecutarConsulta($sqlupdatecliente);

          if ($tipoboleta != 'servicios') {
            $sql_update_articulo = "UPDATE  articulo set saldo_finu= saldo_finu - '$cantidadreal[$ii]', ventast=ventast + '$cantidadreal[$ii]', 
            valor_finu=(saldo_iniu+comprast-ventast) * precio_final_kardex, stock=stock - '$cantidadreal[$ii]', 
            valor_fin_kardex=(select valor_final  from kardex where idarticulo='$idarticulo[$ii]' and transaccion='VENTA' order by idkardex desc limit 1)  
            where  idarticulo='$idarticulo[$ii]'";
            ejecutarConsulta($sql_update_articulo);
          }
          $ii = $ii + 1;
        }
      }


      $sqldetallesesionusuario = "INSERT INTO detalle_usuario_sesion (idusuario, tcomprobante, idcomprobante, fechahora) values 
      ('$idusuario', '$tipo_documento_06','$idBoletaNew', now())";
      ejecutarConsulta($sqldetallesesionusuario);

      if ($tipopago == 'Credito') {
        $inc = 0;
        while ($inc < count($ncuotahiden)) {
          //Guardar en Detalle
          $sql_detalle_cuota_credito = "INSERT INTO cuotas ( tipocomprobante, idcomprobante, ncuota, montocuota, fechacuota, estadocuota ) 
          values ( '03',  '$idBoletaNew', '$ncuotahiden[$inc]', '$montocuotacre[$inc]', '$fechapago[$inc]', '1' )";          
          ejecutarConsulta($sql_detalle_cuota_credito) or $sw = false;
          $inc = $inc + 1;
        } //Fin While
      } else { // SI ES AL CONTADO

        $sql_detalle_cuota_credito = "INSERT INTO cuotas ( tipocomprobante, idcomprobante, ncuota, montocuota, fechacuota, estadocuota ) 
        values ( '03', '$idBoletaNew', '1', '$importe_total_23', '$fecha_emision_01', '0' )";
        ejecutarConsulta($sql_detalle_cuota_credito) or $sw = false;
      }
      //Para actualizar numeracion de las series de la factura
      $sql_update_numeracion = "UPDATE numeracion set numero='$numero_boleta' where idnumeracion='$idserie'";
      ejecutarConsulta($sql_update_numeracion);
      //Fin
    } catch (Exception $e) {
      echo 'Error es: ', $e->getMessage(), "\n";
    }
    //=============== EXPORTAR COMPROBANTES A TXT ======================
    return $idBoletaNew; //FIN DE FUNCION GUARDAR
  }

  //=============== EXPORTAR COMPROBANTES A TXT ========================

  //Implementamos un método para anular la factura

  public function anular($idboleta) {

    // $connect = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    // mysqli_query($connect, 'SET NAMES "' . DB_ENCODE . '"');
    // //Si tenemos un posible error en la conexión lo mostramos
    // if (mysqli_connect_errno()) {
    //   printf("Falló conexión a la base de datos: %s\n", mysqli_connect_error());
    //   exit();
    // }

    $query = "SELECT idboleta, idarticulo  from detalle_boleta_producto where idboleta='$idboleta'";
    //$resultado = mysqli_query($connect, $query);
    $resultado = ejecutarConsulta( $query);

    $Idb = array();
    $Ida = array();
    $sw = true;

    while ($fila = mysqli_fetch_assoc($resultado)) {

      for ($i = 0; $i < count($resultado); $i++) {
        $Idb[$i] = $fila["idboleta"];
        $Ida[$i] = $fila["idarticulo"];

        $sql_update_articulo = "UPDATE detalle_boleta_producto de inner join articulo a  on de.idarticulo=a.idarticulo set
        a.saldo_finu=a.saldo_finu + de.cantidad_item_12, a.stock=a.stock + de.cantidad_item_12, a.ventast=a.ventast - de.cantidad_item_12, a.valor_finu=(a.saldo_finu + a.comprast - a.ventast) * a.costo_compra 
        where de.idboleta='$Idb[$i]' and de.idarticulo='$Ida[$i]'";

        //ACTUALIZAR TIPO TRANSACCION KARDEX
        //Guardar en Kardex
        $sql_kardex = "INSERT INTO kardex (idcomprobante, idarticulo, transaccion, codigo, fecha, tipo_documento, numero_doc, cantidad, costo_1, unidad_medida, saldo_final, costo_2,valor_final) 
        values ('$idboleta', 
        (select a.idarticulo from articulo a inner join detalle_boleta_producto dtb on a.idarticulo=dtb.idarticulo where a.idarticulo='$Ida[$i]' and dtb.idboleta = '$Idb[$i]'), 
        'ANULADO', (select a.codigo from articulo a inner join detalle_boleta_producto dtb on a.idarticulo=dtb.idarticulo where a.idarticulo='$Ida[$i]' and dtb.idboleta = '$Idb[$i]'),
        (select fecha_emision_01 from boleta where idboleta='$Idb[$i]'), '03', (select numeracion_07 from boleta where idboleta='$Idb[$i]'), 
        (select dtb.cantidad_item_12 from articulo a inner join detalle_boleta_producto dtb on a.idarticulo=dtb.idarticulo where a.idarticulo='$Ida[$i]' and dtb.idboleta = '$Idb[$i]'), 
        (select dtb.valor_uni_item_31 from articulo a inner join detalle_boleta_producto dtb on a.idarticulo=dtb.idarticulo where a.idarticulo='$Ida[$i]' and dtb.idboleta = '$Idb[$i]'),
        (select a.unidad_medida from articulo a inner join detalle_boleta_producto dtb on a.idarticulo=dtb.idarticulo where a.idarticulo='$Ida[$i]' and dtb.idboleta = '$Idb[$i]'),
        0, 0, 0)";

        $sqlestado = "UPDATE boleta  set  estado='0' where idboleta='$idboleta'";
      }

      ejecutarConsulta($sql_update_articulo) or $sw = false;
      ejecutarConsulta($sql_kardex) or $sw = false;
      ejecutarConsulta($sqlestado) or $sw = false;
    }

    //=================================================

    require_once "../modelos/Factura.php";
    $factura = new Factura();
    $datos = $factura->datosemp($idempresa);
    $datose = $datos->fetch_object();

    //Inclusion de la tabla RUTAS
    require_once "../modelos/Rutas.php";
    $rutas = new Rutas();
    $Rrutas = $rutas->mostrar2();
    $Prutas = $Rrutas->fetch_object();
    $rutadata = $Prutas->rutadata; // ruta de la carpeta DATA
    $rutabaja = $Prutas->rutabaja; // ruta de la carpeta BAJA

    $query = "SELECT date_format(fecha_emision_01, '%Y-%m-%d') as fecha, date_format(fecha_baja, '%Y%m%d') as fechabaja2, date_format(fecha_baja, '%Y-%m-%d') as fechabaja, 
    right(substring_index(numeracion_07,'-',1),3) as serie, tipodocuCliente, rucCliente, RazonSocial, tipo_moneda_24, monto_15_2 as subtotal, sumatoria_igv_18_1 as igv, 
    importe_total_23 as total, tipo_documento_06 as tipocomp, numeracion_07 as numerodoc, b.estado, comentario_baja  
    from boleta b inner join persona p on b.idcliente=p.idpersona where b.idboleta='$idboleta' ";

    //==================================================
    $result = mysqli_query($connect, $query);
    //==================================================

    //==================BOLETAS================================
    $fecha = array();
    $tipocomp = array();
    $numdocu = array();
    $rasoc = array();
    $fechabaja = array();
    $numeroc = array();
    $comen = array();

    $con = 0;
    $fecdeldia = date("Ymd");

    while ($row = mysqli_fetch_assoc($result)) {
      for ($i = 0; $i <= count($result); $i++) {
        $fecha[$i] = $row["fecha"];
        $fechabaja[$i] = $row["fechabaja"];
        $tipocomp[$i] = $row["tipocomp"];
        $numeroc[$i] = $row["numerodoc"];
        $comen[$i] = $row["comentario_baja"];
        $ruc = $datose->numero_ruc;
        $fbaja2 = $row["fechabaja2"];
        $path = $rutadata . $ruc . "-RA-" . $fbaja2 . "-011.cba";
        $handle = fopen($path, "a");
        fwrite($handle, $fecha[$i] . "|" . $fechabaja[$i] . "|" . $tipocomp[$i] . "|" . $numeroc[$i] . "|" . $comen[$i] . "|\r\n");
        fclose($handle);
        $i = $i + 1;
        $con = $con + 1;
      }
    }

    return $sw;
  }

  public function baja($idboleta, $fecha_baja, $com, $hora) {

    $sw = true;
    $connect = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    mysqli_query($connect, 'SET NAMES "' . DB_ENCODE . '"');
    //Si tenemos un posible error en la conexión lo mostramos
    if (mysqli_connect_errno()) {
      printf("Falló conexión a la base de datos: %s\n", mysqli_connect_error());
      exit();
    }

    $query = "SELECT dt.idboleta, a.idarticulo, dt.cantidad_item_12, dt.valor_uni_item_31, a.codigo, a.unidad_medida  from detalle_boleta_producto dt inner join articulo a on dt.idarticulo=a.idarticulo  where idboleta='$idboleta'";
    $resultado = mysqli_query($connect, $query);
    $Idb = array();
    $Ida = array();
    $Ct = array();
    $Cod = array();
    $Vu = array();
    $Um = array();

    while ($fila = mysqli_fetch_assoc($resultado)) {

      for ($i = 0; $i < count($resultado); $i++) {
        $Idb[$i] = $fila["idboleta"];
        $Ida[$i] = $fila["idarticulo"];
        $Ct[$i] = $fila["cantidad_item_12"];
        $Cod[$i] = $fila["codigo"];
        $Vu[$i] = $fila["valor_uni_item_31"];
        $Um[$i] = $fila["unidad_medida"];

        $sql_update_articulo = "UPDATE detalle_boleta_producto de inner join articulo a  on de.idarticulo=a.idarticulo set
        a.saldo_finu=a.saldo_finu + '$Ct[$i]', a.stock=a.stock + '$Ct[$i]', a.ventast=a.ventast - '$Ct[$i]'
        where de.idboleta='$Idb[$i]' and de.idarticulo='$Ida[$i]'";
        $sql_update_articulo_2 = "UPDATE  detalle_boleta_producto de inner join articulo a  on de.idarticulo=a.idarticulo 
        set a.valor_finu=(a.saldo_iniu + a.comprast - a.ventast) * a.costo_compra where de.idboleta='$Idb[$i]' and de.idarticulo='$Ida[$i]'";

        //ACTUALIZAR TIPO TRANSACCION KARDEX
        //Guardar en Kardex
        $sql_kardex = "INSERT INTO kardex (idcomprobante, idarticulo, transaccion, codigo, fecha, tipo_documento, numero_doc, cantidad, 
        costo_1, unidad_medida, saldo_final, costo_2,valor_final) 
        values ('$idboleta', '$Ida[$i]', 'ANULADO', '$Cod[$i]', '$fecha_baja $hora', '03', (select numeracion_07 from boleta where idboleta='$Idb[$i]'), 
        '$Ct[$i]', '$Vu[$i]', '$Um[$i]', 0, 0, 0)";
      }

      ejecutarConsulta($sql_update_articulo) or $sw = false;
      ejecutarConsulta($sql_update_articulo_2) or $sw = false;
      ejecutarConsulta($sql_kardex) or $sw = false;

      $sqlestado = "UPDATE  boleta  set  estado='3', fecha_baja='$fecha_baja $hora', comentario_baja='$com',  DetalleSunat='C/Baja',  CodigoRptaSunat='3'
      where idboleta='$idboleta'";
      ejecutarConsulta($sqlestado) or $sw = false;
    }
    return $sw;
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar($idboleta) {

    $sql = "SELECT b.idboleta, date(b.fecha_emision_01) as fecha, b.idcliente,p.razon_social as cliente, p.numero_documento, p.domicilio_fiscal, u.idusuario, 
    u.nombre as usuario, b.tipo_documento_06, b.numeracion_07, b.importe_total_23, b.estado 
    from boleta b inner join persona p on b.idcliente=p.idpersona inner join usuario u on b.idusuario=u.idusuario WHERE b.idboleta='$idboleta'";
    return ejecutarConsultaSimpleFila($sql);
  }

  public function listarDetalle($idboleta) {
    $sql = "SELECT df.idboleta, df.idarticulo, a.nombre, df.cantidad_item_12, df.valor_uni_item_14, df.valor_venta_item_21, df.igv_item 
    from detalle_fac_art df inner join articulo a on df.idarticulo=a.idarticulo where df.idboleta='$idboleta'";
    return ejecutarConsulta($sql);
  }

  //Implementar un método para listar los registros
  public function listar($idempresa) {
    $sql = "SELECT b.idboleta, date_format(b.fecha_emision_01, '%d/%m/%y') as fecha, b.idcliente, left(p.razon_social, 20) as cliente, b.vendedorsitio,
    u.nombre as usuario, b.tipo_documento_06, b.numeracion_07, format(b.importe_total_23, 2) as importe_total_23, b.estado, p.nombres, p.apellidos, e.numero_ruc, 
    p.email, b.CodigoRptaSunat, b.DetalleSunat, b.tarjetadc, b.montotarjetadc, b.transferencia, b.montotransferencia, b.tipo_moneda_24 as moneda, b.tcambio,
    (b.tcambio * importe_total_23) as valordolsol, b.formapago, group_concat(a.nombre) as nombre_articulo
    from boleta b 
    inner join persona p on b.idcliente = p.idpersona 
    inner join usuario u on b.idusuario = u.idusuario 
    inner join empresa e on b.idempresa = e.idempresa
    left join detalle_boleta_producto db on b.idboleta = db.idboleta 
    left join articulo a on db.idarticulo = a.idarticulo
    where date(b.fecha_emision_01) = current_date and e.idempresa = '$idempresa'
    group by  b.idboleta
    order by b.idboleta desc;";

    return ejecutarConsulta($sql);
  }

  //Implementar un método para listar los registros
  public function listarValidar($ano, $mes, $dia, $idempresa) {

    if ($mes == "'01','02','03','04','05','06','07','08','09','10', '11','12'") {

      $sql = "SELECT b.idboleta, date_format(b.fecha_emision_01,'%d/%m/%y') as fecha, b.idcliente, left(p.razon_social,20) as cliente, b.vendedorsitio, u.nombre as usuario,
        b.tipo_documento_06, b.numeracion_07, format(b.importe_total_23,2) as importe_total_23, b.estado, p.nombres, p.apellidos, e.numero_ruc, p.email, 
        TIMESTAMPDIFF(DAY, b.fecha_emision_01,  curdate()) AS diast, b.DetalleSunat, b.tarjetadc, b.montotarjetadc, b.transferencia, b.montotransferencia,
        b.tipo_moneda_24 as  moneda, b.tcambio, (b.tcambio * importe_total_23) as valordolsol
        from boleta b 
        inner join persona p on b.idcliente=p.idpersona 
        inner join usuario u on b.idusuario=u.idusuario 
        inner join empresa e on b.idempresa=e.idempresa where
        year(fecha_emision_01)='$ano' and month(fecha_emision_01)  in($mes)  and e.idempresa='$idempresa'
        order by b.idboleta desc";
    } else if ($dia == '0') {

      $sql = "SELECT b.idboleta, date_format(b.fecha_emision_01,'%d/%m/%y') as fecha, b.idcliente, left(p.razon_social,20) as cliente, b.vendedorsitio,
      u.nombre as usuario, b.tipo_documento_06, b.numeracion_07, format(b.importe_total_23,2) as importe_total_23, b.estado, p.nombres, p.apellidos, e.numero_ruc, 
      p.email, TIMESTAMPDIFF(DAY,b.fecha_emision_01, curdate()) AS diast, b.DetalleSunat, b.tarjetadc, b.montotarjetadc, b.transferencia, b.montotransferencia, 
      b.tipo_moneda_24 as  moneda, b.tcambio, (b.tcambio * importe_total_23) as valordolsol
      from boleta b 
      inner join persona p on b.idcliente=p.idpersona 
      inner join usuario u on b.idusuario=u.idusuario 
      inner join empresa e on b.idempresa=e.idempresa 
      where year(fecha_emision_01)='$ano' and month(fecha_emision_01)='$mes'  and e.idempresa='$idempresa'
      order by b.idboleta desc";
    } else {

      $sql = "SELECT b.idboleta, date_format(b.fecha_emision_01,'%d/%m/%y') as fecha, b.idcliente, left(p.razon_social,20) as cliente, b.vendedorsitio, u.nombre as usuario, 
      b.tipo_documento_06, b.numeracion_07, format(b.importe_total_23,2) as importe_total_23, b.estado, p.nombres, p.apellidos, e.numero_ruc, p.email,
      TIMESTAMPDIFF(DAY,b.fecha_emision_01 ,  curdate()) AS diast, b.DetalleSunat,  b.tarjetadc, b.montotarjetadc, b.transferencia, b.montotransferencia, 
      b.tipo_moneda_24 as  moneda, b.tcambio, (b.tcambio * importe_total_23) as valordolsol
      from boleta b 
      inner join persona p on b.idcliente=p.idpersona 
      inner join usuario u on b.idusuario=u.idusuario 
      inner join empresa e on b.idempresa=e.idempresa 
      where year(fecha_emision_01)='$ano' and month(fecha_emision_01)='$mes' and day(fecha_emision_01)='$dia' and e.idempresa='$idempresa'
      order by b.idboleta desc";
    }

    return ejecutarConsulta($sql);
  }

  public function ventacabecera($idboleta, $idempresa) {
    $sql = "SELECT b.idboleta, b.idcliente, p.razon_social,p.nombre_comercial, p.nombres as cliente, p.domicilio_fiscal as direccion, p.tipo_documento, p.numero_documento, 
    p.email, p.telefono1, b.idusuario, u.nombre as usuario, b.tipo_documento_06, b.numeracion_07, right(substring_index(b.numeracion_07,'-',1),4) as serie, b.numeracion_07 as numerofac,  
    date_format(b.fecha_emision_01,'%d-%m-%Y') as fecha, date_format(b.fecha_emision_01,'%Y-%m-%d') as fecha2, date_format(b.fecha_emision_01, '%H:%i:%s') as hora, 
    b.importe_total_23 as totalLetras, b.importe_total_23 as Itotal, b.estado, e.numero_ruc, e.ciudad as dep, b.tdescuento, b.descripcion_leyenda_26_2,
    b.guia_remision_25 as guia, b.vendedorsitio, b.monto_15_2 as subtotal, b.sumatoria_igv_18_1 as igv, b.nombre_tributo_18_4 as nombretrib, b.ipagado, b.saldo, b.tipopago, 
    b.efectivo, b.visa, b.yape, b.plin, b.mastercard as masterC, b.deposito, b.nroreferencia, b.icbper, b.tipo_moneda_24 as moneda,
    b.hashc, GROUP_CONCAT(lpad(cu.ncuota,3,'0'),'|',cu.montocuota,'|',date_format(cu.fechacuota, '%Y-%m-%d')) as cuotas
    FROM boleta b
    INNER JOIN persona p ON b.idcliente = p.idpersona
    INNER JOIN usuario u ON b.idusuario = u.idusuario
    INNER JOIN empresa e ON b.idempresa = e.idempresa
    INNER JOIN cuotas cu ON cu.idcomprobante = b.idboleta
    WHERE b.idboleta = '$idboleta' AND e.idempresa = '$idempresa' and cu.tipocomprobante = '03'
    GROUP BY b.idboleta";
    return ejecutarConsulta($sql);
  }

  public function ventadetalle($idboleta) {

    $sql = "SELECT  
        a.nombre as articulo, 
        a.codigo, 
        format(db.cantidad_item_12,2) as cantidad_item_12, 
        db.valor_uni_item_31, 
        db.precio_uni_item_14_2, 
        db.valor_venta_item_32, 
        format((db.cantidad_item_12 * db.precio_uni_item_14_2),2) as subtotal,
        db.dcto_item, 
        db.descdet,
        um.nombreum as unidad_medida,
        afectacion_igv_5 as nombretribu,
        db.precio_uni_item_14_2 as precio,
        format(db.valor_venta_item_32,2) as subtotal2,
        db.umedida,
        db.numero_orden_item_29 as norden
        from

        detalle_boleta_producto db inner join articulo a on db.idarticulo=a.idarticulo  inner join umedida um on a.unidad_medida=um.idunidad

        where 

        db.idboleta='$idboleta'";

    return ejecutarConsulta($sql);
  }

  public function listarDR($ano, $mes, $idempresa) {

    $sql = "SELECT idboleta, idcliente, numeracion_07 as numeroboleta, date_format(fecha_emision_01,'%d/%m/%y') as fecha, date_format(fecha_baja,'%d/%m/%y') as fechabaja, 
    left(razon_social,20) as cliente, numero_documento as ruccliente, monto_15_2 as opgravada, sumatoria_igv_18_1 as igv, format(importe_total_23,2) as total,
    vendedorsitio, estado 
    from (
      select b.idboleta, b.idcliente, b.numeracion_07, b.fecha_emision_01, b.fecha_baja, p.razon_social, p.numero_documento, b.monto_15_2,        
      b.sumatoria_igv_18_1, b.importe_total_23, b.vendedorsitio, b.estado 
      from boleta b 
      inner join persona p on b.idcliente=p.idpersona 
      inner join usuario u on b.idusuario=u.idusuario 
      inner join empresa e on b.idempresa=e.idempresa 
      where year(b.fecha_emision_01)='$ano' and month(b.fecha_emision_01)='$mes' and b.estado in ('0','3') and e.idempresa='$idempresa'
      union all 
      select b.idboleta, b.idcliente, b.numeracion_07, b.fecha_emision_01, b.fecha_baja, p.razon_social, p.numero_documento, b.monto_15_2, b.sumatoria_igv_18_1,
      b.importe_total_23,
      b.vendedorsitio,
      b.estado 
      from boletaservicio b 
      inner join persona p on b.idcliente=p.idpersona 
      inner join usuario u on b.idusuario=u.idusuario 
      inner join empresa e on b.idempresa=e.idempresa 
      where year(b.fecha_emision_01)='$ano' and month(b.fecha_emision_01)='$mes' and b.estado in ('0','3') and e.idempresa='$idempresa'
    ) as tabla
    order by idboleta desc";

    return ejecutarConsulta($sql);
  }

  public function listarD() {
    $sql = "SELECT documento from correlativo 
    where documento='factura' or documento='boleta' or documento='nota de credito'or documento='nota de debito' 
    group by documento";
    return ejecutarConsulta($sql);
  }

  public function EnviarBoletaWhatsap($idboleta, $numeracion_07) {
    $sql = "SELECT idboleta, numeracion_07 FROM boleta WHERE idboleta = $idboleta AND numeracion_07 = '$numeracion_07'";
    return ejecutarConsulta($sql);
  }

  public function datosemp($idempresa) {
    $sql = "SELECT * from empresa where idempresa='$idempresa'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para dar de baja a factura
  public function ActualizarEstado($idboleta, $st) {
    $sw = true;
    $sqlestado = "UPDATE boleta set estado='$st' where idboleta='$idboleta'";
    ejecutarConsulta($sqlestado) or $sw = false;
    return $sw;
  }

  public function mostrarultimocomprobante($idempresa) {
    $sql = "SELECT numeracion_07 from boleta b inner join empresa e on b.idempresa=e.idempresa  where e.idempresa='$idempresa'  order by idboleta desc limit 1";
    return ejecutarConsultaSimpleFila($sql);
  }

  public function mostrarultimocomprobanteId($idempresa) {
    $sql = "SELECT b.idboleta, e.tipoimpresion from boleta b inner join empresa e on b.idempresa=e.idempresa  where e.idempresa='$idempresa'  order by idboleta desc limit 1";
    return ejecutarConsultaSimpleFila($sql);
  }

  public function downftp($idboleta, $idempresa) {
    //Inclusion de la tabla RUTAS
    require_once "../modelos/Rutas.php";
    $rutas = new Rutas();
    $Rrutas = $rutas->mostrar2($idempresa);
    $Prutas = $Rrutas->fetch_object();
    $rutadata = $Prutas->rutadata; // ruta de la carpeta data

    $connect = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    mysqli_query($connect, 'SET NAMES "' . DB_ENCODE . '"');
    //Si tenemos un posible error en la conexión lo mostramos
    if (mysqli_connect_errno()) {
      printf("Falló conexión a la base de datos: %s\n", mysqli_connect_error());
      exit();
    }

    $sql = "SELECT b.idboleta, p.email,  p.nombres, p.apellidos, p.nombre_comercial, e.numero_ruc, b.tipo_documento_06, b.numeracion_07 
    from boleta b 
    inner join persona p on b.idcliente=p.idpersona 
    inner join empresa e on b.idempresa=e.idempresa 
    where b.idboleta='$idboleta' and e.idempresa='$idempresa'";

    $result = mysqli_query($connect, $sql);
    $con = 0;

    while ($row = mysqli_fetch_assoc($result)) {

      for ($i = 0; $i <= count($result); $i++) {
        $correocliente = $row["email"];
      }

      //Agregar=====================================================
      // Ruta del directorio donde están los archivos
      $path = $rutadata;
      $files = array_diff(scandir($path), array('.', '..'));

      //=============================================================
      $boletaData = $row['numero_ruc'] . "-" . $row['tipo_documento_06'] . "-" . $row['numeracion_07'];
      //Validar si existe el archivo firmado
      foreach ($files as $file) {
        // Divides en dos el nombre de tu archivo utilizando el 
        $dataSt = explode(".", $file);
        // Nombre del archivo
        $fileName = $dataSt[0];
        $st = "1";
        // Extensión del archivo 
        $fileExtension = $dataSt[1];
        if ($boletaData == $fileName) {
          $archivoBoletaData = $fileName;
          // Realizamos un break para que el ciclo se interrumpa
          break;
        }
      }

      $cabext = $rutadata . $archivoBoletaData . '.json';
      // $cabext=$rutadata.$archivoFacturaData.'.cab';
      // $detext=$rutadata.$archivoFacturaData.'.det';
      // $leyext=$rutadata.$archivoFacturaData.'.ley';
      // $triext=$rutadata.$archivoFacturaData.'.tri';
      // $ficheroData = file_get_contents($url);

      $cab = $archivoBoletaData . '.json';
      // $cab=$archivoFacturaData.'.cab';
      // $det=$archivoFacturaData.'.det';
      // $ley=$archivoFacturaData.'.ley';
      // $tri=$archivoFacturaData.'.tri';

      $rpta = array(
        'cabext' => $cabext,
        'cab' => $cab
        // 'detext'=>$detext, 'det'=>$det,
        // 'leyext'=>$leyext, 'ley'=>$ley,
        // 'triext'=>$triext, 'tri'=>$tri
      );

      return $rpta;
      $i = $i + 1;
      $con = $con + 1;
    }
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function enviarcorreo($idboleta, $ema) {
    require_once "../modelos/Factura.php";
    $factura = new Factura();
    $datos = $factura->correo();
    $correo = $datos->fetch_object();
    //Inclusion de la tabla RUTAS
    require_once "../modelos/Rutas.php";
    $rutas = new Rutas();
    $Rrutas = $rutas->mostrar2($idempresa);
    $Prutas = $Rrutas->fetch_object();
    $rutafirma = $Prutas->rutafirma; // ruta de la carpeta FIRMA

    $connect = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    mysqli_query($connect, 'SET NAMES "' . DB_ENCODE . '"');
    //Si tenemos un posible error en la conexión lo mostramos
    if (mysqli_connect_errno()) {
      printf("Falló conexión a la base de datos: %s\n", mysqli_connect_error());
      exit();
    }

    $sqlsendmail = "SELECT b.idboleta, p.email, p.nombres, p.apellidos, p.nombre_comercial, e.numero_ruc, b.tipo_documento_06, b.numeracion_07 
    from boleta b 
    inner join persona p on b.idcliente=p.idpersona 
    inner join empresa e on b.idempresa=e.idempresa 
    where b.idboleta='$idboleta'";
    $result = mysqli_query($connect, $sqlsendmail);
    //$variable=array();
    $con = 0;
    while ($row = mysqli_fetch_assoc($result)) {
      for ($i = 0; $i <= count($result); $i++) { $correocliente = $row["email"]; }
      //Agregar=====================================================
      // Ruta del directorio donde están los archivos
      $path = $rutafirma;
      $pathBoleta = '../boletasPDF/';
      // Arreglo con todos los nombres de los archivos
      $files = array_diff(scandir($path), array('.', '..'));
      $filesBoleta = array_diff(scandir($pathBoleta), array('.', '..'));
      //=============================================================
      $boleta = $row['numero_ruc'] . "-" . $row['tipo_documento_06'] . "-" . $row['numeracion_07'];
      //Validar si existe el archivo firmado
      foreach ($files as $file) {
        // Divides en dos el nombre de tu archivo utilizando el . 
        $dataSt = explode(".", $file);
        // Nombre del archivo
        $fileName = $dataSt[0];
        $st = "1";
        // Extensión del archivo 
        $fileExtension = $dataSt[1];
        if ($boleta == $fileName) {
          $archivoBoleta = $fileName;
          // Realizamos un break para que el ciclo se interrumpa
          break;
        }
      }
      //==========================================================================
      //Validar si existe el archivo firmado
      foreach ($filesBoleta as $fileBoleta) {
        // Divides en dos el nombre de tu archivo utilizando el . 
        $dataStBoleta = explode(".", $fileBoleta);
        // Nombre del archivo
        $fileNameBoleta = $dataStBoleta[0];
        // Extensión del archivo 
        $fileExtensionBoleta = $dataStBoleta[1];
        if ($row['numeracion_07'] == $fileNameBoleta) {
          $archivoBoletaPdf = $fileNameBoleta;
          // Realizamos un break para que el ciclo se interrumpa
          break;
        }
      }
      $url = $rutafirma . $archivoBoleta . '.xml';
      $fichero = file_get_contents($url);
      $urlBoleta = '../boletasPDF/' . $archivoBoletaPdf . '.pdf';
      $ficheroBoleta = file_get_contents($urlBoleta);
      // FUNCION PARA ENVIO DE CORREO CON LA FACTURA AL CLIENTE .
      require '../correo/PHPMailer/class.phpmailer.php';
      require '../correo/PHPMailer/class.smtp.php';
      $mail = new PHPMailer;
      $mail->isSMTP(); // Establecer el correo electrónico para utilizar SMTP
      $mail->Host = $correo->host; // Especificar el servidor de correo a utilizar 
      $mail->SMTPAuth = true; // Habilitar la autenticacion con SMTP
      $mail->Username = $correo->username; // Correo electronico saliente ejemplo: tucorreo@gmail.com
      //$clavehash=hash("SHA256",$correo->password);
      $mail->Password = $correo->password; // Tu contraseña de gmail
      $mail->SMTPSecure = $correo->smtpsecure; // Habilitar encriptacion, `ssl` es aceptada
      $mail->Port = $correo->port; // Puerto TCP  para conectarse 
      $mail->setFrom($correo->username, utf8_decode($correo->nombre)); //Introduzca la dirección de la que debe aparecer el correo electrónico. Puede utilizar cualquier dirección que el servidor SMTP acepte como válida. El segundo parámetro opcional para esta función es el nombre que se mostrará como el remitente en lugar de la dirección de correo electrónico en sí.
      $mail->addReplyTo($correo->username, utf8_decode($correo->nombre)); //Introduzca la dirección de la que debe responder. El segundo parámetro opcional para esta función es el nombre que se mostrará para responder
      $mail->addStringAttachment($fichero, $archivoBoleta . '.xml');
      $mail->addStringAttachment($ficheroBoleta, $archivoBoletaPdf . '.pdf');
      $mail->addAddress($ema); // Agregar quien recibe el e-mail enviado
      //$mail->addAttachment();
      $message = file_get_contents('../correo/email_template.html');
      $message = str_replace('{{first_name}}', utf8_decode($correo->nombre), utf8_decode($correo->mensaje));
      $message = str_replace('{{message}}', utf8_decode($correo->mensaje), utf8_decode($correo->mensaje));
      $message = str_replace('{{customer_email}}', $correo->username, utf8_decode($correo->mensaje));
      $mail->isHTML(true); // Establecer el formato de correo electrónico en HTML
      $mail->Subject = $correo->username;
      $mail->msgHTML($message);
      //$mail->send();
      if (!$mail->send()) {
        //echo '<p style="color:red">No se pudo enviar el mensaje..';
        echo $mail->ErrorInfo;
        //echo "</p>";
      } else {
        echo 'Tu mensaje ha sido enviado';
      }
      // FUNCION PARA ENVIO DE CORREO CON LA FACTURA AL CLIENTE .
      $i = $i + 1;
      $con = $con + 1;
    }
    //Guardar en tabla envicorreo =========================================
    $sql = "INSERT INTO enviocorreo ( numero_documento, cliente, correo, comprobante, fecha_envio )
    values ( (select numero_documento from boleta b inner join persona p on b.idcliente=p.idpersona where b.idboleta='$idboleta'),
    (select razon_social from boleta b inner join persona p on b.idcliente=p.idpersona where b.idboleta='$idboleta'),
    (select email from boleta b inner join persona p on b.idcliente=p.idpersona where b.idboleta='$idboleta'),
    (select numeracion_07 from boleta b inner join persona p on b.idcliente=p.idpersona where b.idboleta='$idboleta'),
    now() )";
    //return ejecutarConsulta($sql);
    $enviarcorreo = ejecutarConsulta($sql);

    //Guardar en tabla envicorreo =========================================

  }

  public function generarxml($idboleta, $idempresa) {

    // $connect = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    // mysqli_query($connect, 'SET NAMES "' . DB_ENCODE . '"');
    // //Si tenemos un posible error en la conexión lo mostramos
    // if (mysqli_connect_errno()) {
    //   printf("Falló conexión a la base de datos: %s\n", mysqli_connect_error());
    //   exit();
    // }

    require_once "../modelos/Factura.php";
    $factura = new Factura();
    $datos = $factura->datosemp($idempresa);
    $configuraciones = $factura->configuraciones($idempresa);
    $configE = $configuraciones->fetch_object();
    $datose = $datos->fetch_object();

    //Inclusion de la tabla RUTAS
    require_once "../modelos/Rutas.php";
    $rutas = new Rutas();
    $Rrutas = $rutas->mostrar2($idempresa);
    $Prutas = $Rrutas->fetch_object();
    $rutadata = $Prutas->rutadata; // ruta de la carpeta DATA
    $rutafirma = $Prutas->rutafirma; // ruta de la carpeta FIRMA
    $rutadatalt = $Prutas->rutadatalt; // ruta de la carpeta DATAALTERNA
    $rutaenvio = $Prutas->rutaenvio; // ruta de la carpeta DATAALTERNA

    $query = "SELECT date_format(b.fecha_emision_01, '%Y-%m-%d') as fecha, right(substring_index(b.numeracion_07,'-',1),1) as serie,
    date_format(b.fecha_emision_01, '%H:%i:%s') as hora, p.tipo_documento as  tipodocuCliente, p.numero_documento, p.razon_social, b.tipo_moneda_24, 
    b.monto_15_2 as subtotal, b.sumatoria_igv_18_1 as igv, b.importe_total_23 as total, b.tipo_documento_06 as tipocomp, b.numeracion_07 as numerodoc, b.estado, 
    b.tdescuento, b.codigo_tributo_18_3 as codigotrib, b.nombre_tributo_18_4  as nombretrib, b.codigo_internacional_18_5 as codigointtrib,b.codigo_tipo_15_1 as opera, 
    e.ubigueo, b.icbper, b.formapago, b.montofpago, b.monedafpago, b.ccuotas, b.fechavecredito, b.montocuota, b.fechavenc     
    from boleta b 
    inner join persona p on b.idcliente=p.idpersona 
    inner join empresa e on b.idempresa=e.idempresa 
    where idboleta='$idboleta' and b.estado in('1','4') order by numerodoc";

    $querycuotas = "SELECT lpad(cu.ncuota,3,'0') as ncuota, cu.montocuota, date_format(cu.fechacuota, '%Y-%m-%d') as fechacuota, format(b.formapago,2) as formapago, 
    b.tipo_moneda_24 as monedaf
    from cuotas cu 
    inner join boleta b on cu.idcomprobante=b.idboleta
    where idcomprobante='$idboleta' and cu.tipocomprobante='03'";

    $querydetbol = "SELECT  b.tipo_documento_06 as tipocomp, b.numeracion_07 as numerodoc,  db.cantidad_item_12 as cantidad, a.codigo, 
    a.nombre as descripcion, um.abre as um, replace(format(db.valor_uni_item_31,5),',','') as vui, db.igv_item as igvi, 
    db.precio_uni_item_14_2 as pvi, db.valor_venta_item_32 as vvi, db.afectacion_igv_item_monto_27_1 as sutribitem, db.numero_orden_item_29 as numorden,
    db.afectacion_igv_3 as aigv, db.afectacion_igv_4 codtrib, db.afectacion_igv_5 as nomtrib, db.afectacion_igv_6 as coditrib,
    a.codigosunat, b.tipo_moneda_24 as moneda, a.mticbperu, b.icbper, db.umedida
    from boleta b 
    inner join detalle_boleta_producto db on b.idboleta=db.idboleta 
    inner join articulo a on db.idarticulo=a.idarticulo 
    inner join umedida um on a.unidad_medida=um.idunidad
    where b.idboleta='$idboleta' and b.estado in ('1','4') order by b.fecha_emision_01";

    // $result = mysqli_query($connect, $query);
    // $resultb = mysqli_query($connect, $querydetbol);
    // $resultcuotas = mysqli_query($connect, $querycuotas);

    $result = ejecutarConsulta( $query);
    $resultb = ejecutarConsultaArray( $querydetbol);
    $resultcuotas = ejecutarConsultaArray( $querycuotas);

    $nombrecomercial = $datose->nombre_comercial;
    $domiciliofiscal = $datose->domicilio_fiscal;
    $codestablecimiento = $datose->ubigueo;
    $codubigueo = $datose->codubigueo;
    $ciudad = $datose->ciudad;
    $distrito = $datose->distrito;
    $interior = $datose->interior;
    $codigopais = $datose->codigopais;

    //Parametros de salida
    $fecha = "";
    $hora = "";
    $serie = "";
    $tipodocu = "";
    $numdocu = "";
    $rasoc = "";
    $moneda = "";
    $codigotrib = "";
    $nombretrib = "";
    $codigointtrib = "";
    $subtotal = "";
    $igv = "";
    $total = "";
    $tdescu = "";
    $opera = "";
    $ubigueo = "";

    $formapago = "";
    $montofpago = "";
    $monedafpago = "";
    $ccuotas = "";
    $fechavecredito = "";
    $montocuota = "";

    $con = 0; //COntador de variable
    $icbper = "";

    while ($row = mysqli_fetch_assoc($result)) {

      //for($i=0; $i <= count($result); $i++){
      $fecha = $row["fecha"]; //Fecha emision
      $serie = $row["serie"];
      $tipodocu = $row["tipodocuCliente"]; //Tipo de documento de cliente ruc o dni
      $numdocu = $row["numero_documento"]; //NUmero de docuemnto de cliente
      $rasoc = $row["razon_social"]; //Nombre de cliente
      $moneda = $row["tipo_moneda_24"];
      $subtotal = $row["subtotal"];
      $igv = $row["igv"];
      $total = $row["total"];
      $tdescu = $row["tdescuento"];
      $hora = $row["hora"];
      $tipocomp = $row["tipocomp"];
      $numerodoc = $row["numerodoc"];
      $ruc = $datose->numero_ruc;
      $ubigueo = $datose->ubigueo;
      $opera = $row["opera"];

      $codigotrib = $row["codigotrib"]; //codigo de tributo de la tabla catalo 5
      $nombretrib = $row["nombretrib"]; //NOmbre de tributo de la tabla catalo 5
      $codigointtrib = $row["codigointtrib"]; //Codigo internacional de la tabla catalo 5

      $formapago = $row["formapago"];
      $montofpago = $row["montofpago"];
      $monedafpago = $row["monedafpago"];
      $ccuotas = $row["ccuotas"];
      $fechavecredito = $row["fechavecredito"];
      $montocuota = $row["montocuota"];

      $icbper = $row["icbper"];

      if ($moneda == 'USD') { $Lmoneda = "DOLARES AMERICANOS"; }

      $Lmoneda = "NUEVOS SOLES";

      require_once "Letras.php";
      $V = new EnLetras();
      $con_letra = strtoupper($V->ValorEnLetras($total, $Lmoneda));

      //======================================== FORMATO XML ========================================================
      //Primera parte
      $boletaXML = '<?xml version="1.0" encoding="utf-8"?>
            <Invoice xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2"
                     xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2"
                     xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"
                     xmlns:ds="http://www.w3.org/2000/09/xmldsig#"
                     xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2">
                <ext:UBLExtensions>
                    <ext:UBLExtension>
                        <ext:ExtensionContent/>
                    </ext:UBLExtension>
                </ext:UBLExtensions>
                <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
                <cbc:CustomizationID>2.0</cbc:CustomizationID>
                <cbc:ID>' . $numerodoc . '</cbc:ID>
                <cbc:IssueDate>' . $fecha . '</cbc:IssueDate>
                <cbc:IssueTime>' . $hora . '</cbc:IssueTime>
                <cbc:InvoiceTypeCode listID="0101">' . $tipocomp . '</cbc:InvoiceTypeCode>
                <cbc:Note languageLocaleID="1000">' . $con_letra . '</cbc:Note>
              <cbc:Note languageLocaleID="2006">Leyenda: Operación sujeta a detracción</cbc:Note>
              <cbc:DocumentCurrencyCode>' . $moneda . '</cbc:DocumentCurrencyCode>
                <cac:Signature>
                  <cbc:ID>' . $ruc . '</cbc:ID>
                  <cbc:Note>SENCON</cbc:Note>
                  <cac:SignatoryParty>
                    <cac:PartyIdentification>  <cbc:ID>' . $ruc . '</cbc:ID> </cac:PartyIdentification>
                    <cac:PartyName> <cbc:Name><![CDATA[' . $nombrecomercial . ']]></cbc:Name>  </cac:PartyName>
                  </cac:SignatoryParty>
                  <cac:DigitalSignatureAttachment>
                    <cac:ExternalReference> <cbc:URI>#SIGN-SENCON</cbc:URI> </cac:ExternalReference>
                  </cac:DigitalSignatureAttachment>
                </cac:Signature>
                <cac:AccountingSupplierParty>
                  <cac:Party>
                    <cac:PartyIdentification> <cbc:ID schemeID="6">' . $ruc . '</cbc:ID> </cac:PartyIdentification>
                    <cac:PartyName> <cbc:Name><![CDATA[' . $nombrecomercial . ']]></cbc:Name> </cac:PartyName>
                    <cac:PartyLegalEntity>
                      <cbc:RegistrationName><![CDATA[' . $nombrecomercial . ']]></cbc:RegistrationName>
                        <cac:RegistrationAddress>
                          <cbc:AddressTypeCode>' . $codestablecimiento . '</cbc:AddressTypeCode>
                          <cbc:CitySubdivisionName>' . $interior . '</cbc:CitySubdivisionName>
                          <cbc:CityName>' . $ciudad . '</cbc:CityName>
                          <cbc:CountrySubentity>' . $ciudad . '</cbc:CountrySubentity>
                          <cbc:CountrySubentityCode>' . $codubigueo . '</cbc:CountrySubentityCode>
                          <cbc:District>' . $distrito . '</cbc:District> 
                          <cac:AddressLine> <cbc:Line><![CDATA[' . $domiciliofiscal . ']]></cbc:Line> </cac:AddressLine>    
                          <cac:Country> <cbc:IdentificationCode>PE</cbc:IdentificationCode> </cac:Country>
                        </cac:RegistrationAddress>
                      </cac:PartyLegalEntity>
                  </cac:Party>
                </cac:AccountingSupplierParty>

                <cac:AccountingCustomerParty>
                  <cac:Party>
                    <cac:PartyIdentification> <cbc:ID schemeID="' . $tipodocu . '">' . $numdocu . '</cbc:ID> </cac:PartyIdentification>
                    <cac:PartyLegalEntity> <cbc:RegistrationName><![CDATA[' . $rasoc . ']]></cbc:RegistrationName> </cac:PartyLegalEntity>
                  </cac:Party>
                </cac:AccountingCustomerParty>';

      //    $boletaXML.='<cac:PaymentTerms>
      //   <cbc:ID>FormaPago</cbc:ID>
      // <cbc:PaymentMeansID>Contado</cbc:PaymentMeansID>
      //   </cac:PaymentTerms>';

      if ($formapago == 'Contado') {
        $boletaXML .= '<cac:PaymentTerms>
        <cbc:ID>FormaPago</cbc:ID>
        <cbc:PaymentMeansID>' . $formapago . '</cbc:PaymentMeansID>
        </cac:PaymentTerms>';
      } else { // SI ES AL CREDITO

        $boletaXML .= '<cac:PaymentTerms>
        <cbc:ID>FormaPago</cbc:ID>
        <cbc:PaymentMeansID>' . $formapago . '</cbc:PaymentMeansID>
        <cbc:Amount currencyID="' . $moneda . '">' . $total . '</cbc:Amount>
        </cac:PaymentTerms>';

        $ncuotacredito ='';
        $montocuotacredito = '';
        $fechacuotacredito = '';
        $formapagocre = '';
        $monedaf = '';
        foreach ($resultcuotas as $key => $rowb) {          
         
          $ncuotacredito = $rowb["ncuota"];
          $montocuotacredito = $rowb["montocuota"];
          $fechacuotacredito = $rowb["fechacuota"];
          $formapagocre = $rowb["formapago"];
          $monedaf = $rowb["monedaf"];

          $boletaXML .= '<cac:PaymentTerms>
          <cbc:ID>FormaPago</cbc:ID>
          <cbc:PaymentMeansID>Cuota' . $ncuotacredito . '</cbc:PaymentMeansID>
          <cbc:Amount currencyID="' . $monedaf . '">' . $montocuotacredito . '</cbc:Amount>
          <cbc:PaymentDueDate>' . $fechacuotacredito . '</cbc:PaymentDueDate>
          </cac:PaymentTerms>';          
        }
      }

      $boletaXML .= ' <!-- Inicio Tributos cabecera-->  
      <cac:TaxTotal>
      <cbc:TaxAmount currencyID="' . $moneda . '">' . $igv . '</cbc:TaxAmount>
        <cac:TaxSubtotal>
        <cbc:TaxableAmount currencyID="' . $moneda . '">' . $subtotal . '</cbc:TaxableAmount>
        <cbc:TaxAmount currencyID="' . $moneda . '">' . $igv . '</cbc:TaxAmount>
        <cac:TaxCategory>
          <cac:TaxScheme>
            <cbc:ID>' . $codigotrib . '</cbc:ID>
            <cbc:Name>' . $nombretrib . '</cbc:Name>
            <cbc:TaxTypeCode>' . $codigointtrib . '</cbc:TaxTypeCode>
          </cac:TaxScheme>
        </cac:TaxCategory>
      </cac:TaxSubtotal>';

      if ($icbper > 0) {
        $boletaXML .= '<cac:TaxSubtotal>
        <cbc:TaxAmount currencyID="' . $moneda . '">' . $icbper . '</cbc:TaxAmount>
          <cac:TaxCategory>
            <cac:TaxScheme>
              <cbc:ID>7152</cbc:ID>
              <cbc:Name>ICBPER</cbc:Name>
              <cbc:TaxTypeCode>OTH</cbc:TaxTypeCode>
            </cac:TaxScheme>
          </cac:TaxCategory>
        </cac:TaxSubtotal>';
      }

      $boletaXML .= ' <!-- Fin Tributos  Cabecera-->
      </cac:TaxTotal>
        <cac:LegalMonetaryTotal>
            <cbc:LineExtensionAmount currencyID="' . $moneda . '">' . $subtotal . '</cbc:LineExtensionAmount>
            <cbc:TaxInclusiveAmount currencyID="' . $moneda . '">' . $total . '</cbc:TaxInclusiveAmount>
            <cbc:AllowanceTotalAmount currencyID="' . $moneda . '">0.00</cbc:AllowanceTotalAmount>
            <cbc:ChargeTotalAmount currencyID="' . $moneda . '">0.00</cbc:ChargeTotalAmount>  
            <cbc:PrepaidAmount currencyID="' . $moneda . '">0.00</cbc:PrepaidAmount>  
            <cbc:PayableAmount currencyID="' . $moneda . '">' . $total . '</cbc:PayableAmount>
        </cac:LegalMonetaryTotal>';
      //}//For cabecera
      // $i = $i + 1;
      $con = $con + 1;
    } //While cabecera

    $codigo = '';
    $cantidad = '';
    $descripcion = '';
    $um = '';
    $vui = '';
    $igvi = '';
    $pvi = '';
    $vvi = '';
    $sutribitem = '';
    $aigv = '';
    $codtrib = '';
    $nomtrib = '';
    $coditrib = '';
    $codigosunat = '';
    $numorden = '';
    $monedaD = '';
    $mticbperu = '';

    foreach ($resultb as $key => $rowb) {
      
        $codigo       = $rowb["codigo"];
        $cantidad     = $rowb["cantidad"];
        $descripcion  = $rowb["descripcion"];
        $vui          = $rowb["vui"];
        $sutribitem   = $rowb["sutribitem"];
        $igvi         = $rowb["igvi"];
        $pvi          = $rowb["pvi"];
        $vvi          = $rowb["vvi"];
        $um           = $rowb["umedida"];
        $tipocompf    = $rowb["tipocomp"];
        $numerodocf   = $rowb["numerodoc"];
        $ruc          = $datose->numero_ruc;
        $aigv         = $rowb["aigv"];
        $codtrib      = $rowb["codtrib"];
        $nomtrib      = $rowb["nomtrib"];
        $coditrib     = $rowb["coditrib"];
        $codigosunat  = $rowb["codigosunat"];
        $numorden     = $rowb["numorden"];
        $monedaD      = $rowb["moneda"];
        $mticbperu    = $rowb["mticbperu"];
        $icbperD      = $rowb["icbper"];

        if ($codtrib == '9997') { $igv_ = "0";  } else { $igv_ = $configE->igv; }

        /* Número de orden del Ítem - Cantidad y Unidad de medida por ítem - Valor de venta del ítem  */

        $boletaXML .= '<cac:InvoiceLine>
        <cbc:ID>' . $numorden . '</cbc:ID>
        <cbc:InvoicedQuantity unitCode="' . $um . '">' . number_format($cantidad, 2, '.', '') . '</cbc:InvoicedQuantity>
        <cbc:LineExtensionAmount currencyID="' . $monedaD . '">' . number_format($vvi, 2, '.', '') . '</cbc:LineExtensionAmount>
        <cac:PricingReference>
          <cac:AlternativeConditionPrice>
            <cbc:PriceAmount currencyID="' . $monedaD . '">' . number_format($pvi, 2, '.', '') . '</cbc:PriceAmount>
            <cbc:PriceTypeCode>01</cbc:PriceTypeCode>
          </cac:AlternativeConditionPrice>
        </cac:PricingReference>
        <cac:TaxTotal>
          <cbc:TaxAmount currencyID="' . $monedaD . '">' . number_format($sutribitem, 2, '.', '') . '</cbc:TaxAmount> 
          <cac:TaxSubtotal>
            <cbc:TaxableAmount currencyID="' . $monedaD . '">' . number_format($vvi, 2, '.', '') . '</cbc:TaxableAmount>
            <cbc:TaxAmount currencyID="' . $monedaD . '">' . number_format($sutribitem, 2, '.', '') . '</cbc:TaxAmount>
            <cac:TaxCategory>
              <cbc:Percent>' . $igv_ . '</cbc:Percent>
              <cbc:TaxExemptionReasonCode>' . $aigv . '</cbc:TaxExemptionReasonCode>
              <cac:TaxScheme>
                <cbc:ID>' . $codtrib . '</cbc:ID>
                <cbc:Name>' . $nomtrib . '</cbc:Name>
                <cbc:TaxTypeCode>' . $coditrib . '</cbc:TaxTypeCode>
              </cac:TaxScheme>
            </cac:TaxCategory>
          </cac:TaxSubtotal>';

        if ($codigo == "ICBPER") {

          $boletaXML .= '<cac:TaxSubtotal>
            <cbc:TaxAmount currencyID="' . $moneda . '">' . $icbperD . '</cbc:TaxAmount>
            <cbc:BaseUnitMeasure unitCode="' . $um . '">' . number_format($cantidad, 0, '.', '') . '</cbc:BaseUnitMeasure>
            <cac:TaxCategory>
            <cbc:PerUnitAmount currencyID="' . $moneda . '">' . number_format($mticbperu, 2, '.', '') . '</cbc:PerUnitAmount>
              <cac:TaxScheme>
                <cbc:ID>7152</cbc:ID>
                <cbc:Name>ICBPER</cbc:Name>
                <cbc:TaxTypeCode>OTH</cbc:TaxTypeCode>
              </cac:TaxScheme>
            </cac:TaxCategory>
          </cac:TaxSubtotal>';
        };

        $boletaXML .= '</cac:TaxTotal>
          <cac:Item>
            <cbc:Description><![CDATA[' . $descripcion . ']]></cbc:Description>
            <cac:SellersItemIdentification>
              <cbc:ID>' . $codigo . '</cbc:ID>
            </cac:SellersItemIdentification>
          </cac:Item>
          <cac:Price>
            <cbc:PriceAmount currencyID="' . $monedaD . '">' . number_format($vui, 5, '.', '') . '</cbc:PriceAmount>
          </cac:Price>
        </cac:InvoiceLine>';     

    } //Find e foreach 

    $boletaXML .= '</Invoice>';

    //FIN DE CABECERA ===================================================================

    // Nos aseguramos de que la cadena que contiene el XML esté en UTF-8
    $boletaXML = mb_convert_encoding($boletaXML, "UTF-8");
    // Grabamos el XML en el servidor como un fichero plano, para
    // poder ser leido por otra aplicación.
    $gestor = fopen($rutafirma . $ruc . "-" . $tipocomp . "-" . $numerodoc . ".xml", 'w');
    fwrite($gestor, $boletaXML);
    fclose($gestor);

    $cabextxml = $rutafirma . $ruc . "-" . $tipocomp . "-" . $numerodoc . ".xml";
    $cabxml = $ruc . "-" . $tipocomp . "-" . $numerodoc . ".xml";
    $nomxml = $ruc . "-" . $tipocomp . "-" . $numerodoc;
    $nomxmlruta = $rutafirma . $ruc . "-" . $tipocomp . "-" . $numerodoc;

    require_once("../greemter/Greenter.php");
    $invo = new Greenter();
    $out = $invo->getDatFac($cabextxml);
    $filenaz = $nomxml . ".zip";
    $zip = new ZipArchive();
    if ($zip->open($filenaz, ZIPARCHIVE::CREATE) === true) {
      $zip->addFile($cabextxml, $cabxml);
      $zip->close();

      $imagen = file_get_contents($filenaz);
      $imageData = base64_encode($imagen);
      rename($cabextxml, $rutafirma . $cabxml);
      rename($filenaz, $rutaenvio . $filenaz);
    } else {
      $out = "Error al comprimir archivo";
    }

    $data[0] = "";
    $sxe = new SimpleXMLElement($cabextxml, null, true);
    $urn = $sxe->getNamespaces(true);
    $sxe->registerXPathNamespace('ds', $urn['ds']);
    $data = $sxe->xpath('//ds:DigestValue');

    $rpta = array('cabextxml' => $cabextxml, 'cabxml' => $cabxml, 'rutafirma' => $cabextxml);
    $sqlDetalle = "UPDATE boleta set DetalleSunat='XML firmado'  , hashc='$data[0]', estado='4'  where idboleta='$idboleta'";
    ejecutarConsulta($sqlDetalle);

    return $rpta;
  } //Fin de funcion

  public function generarxmlEA($ano, $mes, $dia, $idboleta, $estado, $check, $idempresa) {
    $connect = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    mysqli_query($connect, 'SET NAMES "' . DB_ENCODE . '"');
    //Si tenemos un posible error en la conexión lo mostramos
    if (mysqli_connect_errno()) {
      printf("Falló conexión a la base de datos: %s\n", mysqli_connect_error());
      exit();
    }

    require_once "../modelos/Factura.php";
    $factura = new Factura();
    $datos = $factura->datosemp($idempresa);
    $configuraciones = $factura->configuraciones($idempresa);
    $configE = $configuraciones->fetch_object();
    $datose = $datos->fetch_object();

    //Inclusion de la tabla RUTAS
    require_once "../modelos/Rutas.php";
    $rutas = new Rutas();
    $Rrutas = $rutas->mostrar2($idempresa);
    $Prutas = $Rrutas->fetch_object();
    $rutadata = $Prutas->rutadata; // ruta de la carpeta DATA
    $rutafirma = $Prutas->rutafirma; // ruta de la carpeta FIRMA
    $rutadatalt = $Prutas->rutadatalt; // ruta de la carpeta DATAALTERNA
    $rutaenvio = $Prutas->rutaenvio; // ruta de la carpeta DATAALTERNA

    if ($estado == '1' && $estado == '4' || $check == 'true') {
      $query = "SELECT date_format(b.fecha_emision_01, '%Y-%m-%d') as fecha, right(substring_index(b.numeracion_07,'-',1),1) as serie,
      date_format(b.fecha_emision_01, '%H:%i:%s') as hora, p.tipo_documento as  tipodocuCliente, p.numero_documento, p.razon_social, b.tipo_moneda_24, 
      b.monto_15_2 as subtotal, b.sumatoria_igv_18_1 as igv, b.importe_total_23 as total, b.tipo_documento_06 as tipocomp, b.numeracion_07 as numerodoc, 
      b.estado, b.tdescuento, b.codigo_tributo_18_3 as codigotrib, b.nombre_tributo_18_4  as nombretrib, b.codigo_internacional_18_5 as codigointtrib,
      b.codigo_tipo_15_1 as opera, e.ubigueo, b.icbper
      from boleta b 
      inner join persona p on b.idcliente=p.idpersona 
      inner join empresa e on b.idempresa=e.idempresa 
      where year(b.fecha_emision_01)='$ano' and month(b.fecha_emision_01)='$mes' and day(b.fecha_emision_01)='$dia' and b.estado ='$estado' and b.idboleta='$idboleta' 
      order by numerodoc";

      $querydetbol = "SELECT b.tipo_documento_06 as tipocomp, b.numeracion_07 as numerodoc,  db.cantidad_item_12 as cantidad, a.codigo, a.nombre as descripcion, 
      um.abre as um, replace(format(db.valor_uni_item_31,5),',','') as vui, db.igv_item as igvi, db.precio_uni_item_14_2 as pvi, db.valor_venta_item_32 as vvi,
      db.afectacion_igv_item_monto_27_1 as sutribitem, db.numero_orden_item_29 as numorden, db.afectacion_igv_3 as aigv, db.afectacion_igv_4 codtrib, 
      db.afectacion_igv_5 as nomtrib, db.afectacion_igv_6 as coditrib, a.codigosunat, b.tipo_moneda_24 as moneda, a.mticbperu, b.icbper
      from boleta b 
      inner join detalle_boleta_producto db on b.idboleta=db.idboleta 
      inner join articulo a on db.idarticulo=a.idarticulo 
      inner join umedida um on a.unidad_medida=um.idunidad
      where year(b.fecha_emision_01)='$ano' and month(b.fecha_emision_01)='$mes' and day(b.fecha_emision_01)='$dia' and b.estado ='$estado' and b.idboleta='$idboleta' 
      order by b.fecha_emision_01";

      $result = mysqli_query($connect, $query);
      $resultb = mysqli_query($connect, $querydetbol);

      $nombrecomercial = $datose->nombre_comercial;
      $domiciliofiscal = $datose->domicilio_fiscal;
      $codestablecimiento = $datose->ubigueo;
      $codubigueo = $datose->codubigueo;
      $ciudad = $datose->ciudad;
      $distrito = $datose->distrito;
      $interior = $datose->interior;
      $codigopais = $datose->codigopais;

      //Parametros de salida
      $fecha = array();
      $hora = array();
      $serie = array();
      $tipodocu = array();
      $numdocu = array();
      $rasoc = array();
      $moneda = array();
      $codigotrib = array();
      $nombretrib = array();
      $codigointtrib = array();
      $subtotal = array();
      $igv = array();
      $total = array();
      $tdescu = array();
      $opera = array();
      $ubigueo = array();

      $con = 0; //COntador de variable
      $icbper = "";

      while ($row = mysqli_fetch_assoc($result)) {
        for ($i = 0; $i <= count($result); $i++) {

          $fecha[$i] = $row["fecha"]; //Fecha emision
          $serie[$i] = $row["serie"];
          $tipodocu[$i] = $row["tipodocuCliente"]; //Tipo de documento de cliente ruc o dni
          $numdocu[$i] = $row["numero_documento"]; //NUmero de docuemnto de cliente
          $rasoc[$i] = $row["razon_social"]; //Nombre de cliente
          $moneda[$i] = $row["tipo_moneda_24"];
          $subtotal[$i] = $row["subtotal"];
          $igv[$i] = $row["igv"];
          $total[$i] = $row["total"];
          $tdescu[$i] = $row["tdescuento"];
          $hora[$i] = $row["hora"];
          $tipocomp = $row["tipocomp"];
          $numerodoc = $row["numerodoc"];
          $ruc = $datose->numero_ruc;
          $ubigueo = $datose->ubigueo;
          $opera[$i] = $row["opera"];
          $codigotrib[$i] = $row["codigotrib"]; //codigo de tributo de la tabla catalo 5
          $nombretrib[$i] = $row["nombretrib"]; //NOmbre de tributo de la tabla catalo 5
          $codigointtrib[$i] = $row["codigointtrib"]; //Codigo internacional de la tabla catalo 5
          $icbper = $row["icbper"];

          if ($moneda[$i] == 'USD') { $Lmoneda = "DOLARES AMERICANOS"; }
          $Lmoneda = "NUEVOS SOLES";
          require_once "Letras.php";
          $V = new EnLetras();
          $con_letra = strtoupper($V->ValorEnLetras($total[$i], $Lmoneda));

          //======================================== FORMATO XML ========================================================
          //Primera parte
          $boletaXML = '<?xml version="1.0" encoding="utf-8"?>
            <Invoice xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2"
                     xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2"
                     xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"
                     xmlns:ds="http://www.w3.org/2000/09/xmldsig#"
                     xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2">
                <ext:UBLExtensions>
                    <ext:UBLExtension>
                        <ext:ExtensionContent/>
                    </ext:UBLExtension>
                </ext:UBLExtensions>
                <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
                <cbc:CustomizationID>2.0</cbc:CustomizationID>
                <cbc:ID>' . $numerodoc . '</cbc:ID>
                <cbc:IssueDate>' . $fecha[$i] . '</cbc:IssueDate>
                <cbc:IssueTime>' . $hora[$i] . '</cbc:IssueTime>
                <cbc:InvoiceTypeCode listID="0101">' . $tipocomp . '</cbc:InvoiceTypeCode>
                <cbc:Note languageLocaleID="1000">' . $con_letra . '</cbc:Note>
              <cbc:Note languageLocaleID="2006">Leyenda: Operación sujeta a detracción</cbc:Note>
              <cbc:DocumentCurrencyCode>' . $moneda[$i] . '</cbc:DocumentCurrencyCode> 
                <cac:Signature>
                    <cbc:ID>' . $ruc . '</cbc:ID>
                    <cbc:Note>SENCON</cbc:Note>
                    <cac:SignatoryParty>
                        <cac:PartyIdentification>
                            <cbc:ID>' . $ruc . '</cbc:ID>
                        </cac:PartyIdentification>
                        <cac:PartyName>
                            <cbc:Name><![CDATA[' . $nombrecomercial . ']]></cbc:Name>
                        </cac:PartyName>
                    </cac:SignatoryParty>
                    <cac:DigitalSignatureAttachment>
                        <cac:ExternalReference>
                            <cbc:URI>#SIGN-SENCON</cbc:URI>
                        </cac:ExternalReference>
                    </cac:DigitalSignatureAttachment>
                </cac:Signature>

                <cac:AccountingSupplierParty>
                  <cac:Party>
                    <cac:PartyIdentification> <cbc:ID schemeID="6">' . $ruc . '</cbc:ID> </cac:PartyIdentification>
                    <cac:PartyName> <cbc:Name><![CDATA[' . $nombrecomercial . ']]></cbc:Name> </cac:PartyName>
                    <cac:PartyLegalEntity>
                      <cbc:RegistrationName><![CDATA[' . $nombrecomercial . ']]></cbc:RegistrationName>
                      <cac:RegistrationAddress>
                        <cbc:AddressTypeCode>' . $codestablecimiento . '</cbc:AddressTypeCode>
                        <cbc:CitySubdivisionName>' . $interior . '</cbc:CitySubdivisionName>
                        <cbc:CityName>' . $ciudad . '</cbc:CityName>
                        <cbc:CountrySubentity>' . $ciudad . '</cbc:CountrySubentity>
                        <cbc:CountrySubentityCode>' . $codubigueo . '</cbc:CountrySubentityCode>
                        <cbc:District>' . $distrito . '</cbc:District> 
                        <cac:AddressLine>  <cbc:Line><![CDATA[' . $domiciliofiscal . ']]></cbc:Line> </cac:AddressLine>    
                        <cac:Country> <cbc:IdentificationCode>PE</cbc:IdentificationCode> </cac:Country>
                      </cac:RegistrationAddress>
                    </cac:PartyLegalEntity>
                  </cac:Party>
                </cac:AccountingSupplierParty>

                <cac:AccountingCustomerParty>
                  <cac:Party>
                    <cac:PartyIdentification> <cbc:ID schemeID="' . $tipodocu[$i] . '">' . $numdocu[$i] . '</cbc:ID>  </cac:PartyIdentification>
                    <cac:PartyLegalEntity> <cbc:RegistrationName><![CDATA[' . $rasoc[$i] . ']]></cbc:RegistrationName> </cac:PartyLegalEntity>
                  </cac:Party>
                </cac:AccountingCustomerParty>';

          $boletaXML .= '<cac:PaymentTerms>
            <cbc:ID>FormaPago</cbc:ID>
            <cbc:PaymentMeansID>Contado</cbc:PaymentMeansID>
          </cac:PaymentTerms>';

          $boletaXML .= '<!-- Inicio Tributos cabecera-->  
            <cac:TaxTotal>
            <cbc:TaxAmount currencyID="' . $moneda[$i] . '">' . $igv[$i] . '</cbc:TaxAmount>
            <cac:TaxSubtotal>
              <cbc:TaxableAmount currencyID="' . $moneda[$i] . '">' . $subtotal[$i] . '</cbc:TaxableAmount>
              <cbc:TaxAmount currencyID="' . $moneda[$i] . '">' . $igv[$i] . '</cbc:TaxAmount>
              <cac:TaxCategory>
                <cac:TaxScheme>
                  <cbc:ID>' . $codigotrib[$i] . '</cbc:ID>
                  <cbc:Name>' . $nombretrib[$i] . '</cbc:Name>
                  <cbc:TaxTypeCode>' . $codigointtrib[$i] . '</cbc:TaxTypeCode>
                </cac:TaxScheme>
              </cac:TaxCategory>
            </cac:TaxSubtotal>';

          if ($icbper > 0) {

            $boletaXML .= '<cac:TaxSubtotal>
              <cbc:TaxAmount currencyID="' . $moneda[$i] . '">' . $icbper . '</cbc:TaxAmount>
              <cac:TaxCategory>
                <cac:TaxScheme>
                  <cbc:ID>7152</cbc:ID>
                  <cbc:Name>ICBPER</cbc:Name>
                  <cbc:TaxTypeCode>OTH</cbc:TaxTypeCode>
                </cac:TaxScheme>
              </cac:TaxCategory>
            </cac:TaxSubtotal>';
          }

          $boletaXML .= '<!-- Fin Tributos  Cabecera-->
          </cac:TaxTotal>
            <cac:LegalMonetaryTotal>
              <cbc:LineExtensionAmount currencyID="' . $moneda[$i] . '">' . $subtotal[$i] . '</cbc:LineExtensionAmount>
              <cbc:TaxInclusiveAmount currencyID="' . $moneda[$i] . '">' . $total[$i] . '</cbc:TaxInclusiveAmount>
              <cbc:AllowanceTotalAmount currencyID="' . $moneda[$i] . '">0.00</cbc:AllowanceTotalAmount>
              <cbc:ChargeTotalAmount currencyID="' . $moneda[$i] . '">0.00</cbc:ChargeTotalAmount>  
              <cbc:PrepaidAmount currencyID="' . $moneda[$i] . '">0.00</cbc:PrepaidAmount>  
              <cbc:PayableAmount currencyID="' . $moneda[$i] . '">' . $total[$i] . '</cbc:PayableAmount>
            </cac:LegalMonetaryTotal>';
        } //For cabecera

        $i = $i + 1;
        $con = $con + 1;
      } //While cabecera

      $codigo = array();
      $cantidad = array();
      $descripcion = array();
      $um = array();
      $vui = array();
      $igvi = array();
      $pvi = array();
      $vvi = array();
      $sutribitem = array();
      $aigv = array();
      $codtrib = array();
      $nomtrib = array();
      $coditrib = array();
      $codigosunat = array();
      $numorden = array();
      $monedaD = array();
      $mticbperu = array();

      while ($rowb = mysqli_fetch_assoc($resultb)) {
        for ($ib = 0; $ib < count($resultb); $ib++) {
          $codigo[$ib] = $rowb["codigo"];
          $cantidad[$ib] = $rowb["cantidad"];
          $descripcion[$ib] = $rowb["descripcion"];
          $vui[$ib] = $rowb["vui"];
          $sutribitem[$ib] = $rowb["sutribitem"];
          $igvi[$ib] = $rowb["igvi"];
          $pvi[$ib] = $rowb["pvi"];
          $vvi[$ib] = $rowb["vvi"];
          $um[$ib] = $rowb["um"];
          $tipocompf = $rowb["tipocomp"];
          $numerodocf = $rowb["numerodoc"];
          $ruc = $datose->numero_ruc;
          $aigv[$ib] = $rowb["aigv"];
          $codtrib[$ib] = $rowb["codtrib"];
          $nomtrib[$ib] = $rowb["nomtrib"];
          $coditrib[$ib] = $rowb["coditrib"];
          $codigosunat[$ib] = $rowb["codigosunat"];
          $numorden[$ib] = $rowb["numorden"];
          $monedaD[$ib] = $rowb["moneda"];
          $mticbperu[$ib] = $rowb["mticbperu"];
          $icbperD = $rowb["icbper"];

          if ($codtrib[$ib] == '9997') { $igv_ = "0"; } else { $igv_ = $configE->igv; }

          $boletaXML .= '<cac:InvoiceLine>
          <cbc:ID>' . $numorden[$ib] . '</cbc:ID>
          <cbc:InvoicedQuantity unitCode="' . $um[$ib] . '">' . number_format($cantidad[$ib], 2, '.', '') . '</cbc:InvoicedQuantity>
          <cbc:LineExtensionAmount currencyID="' . $monedaD[$ib] . '">' . number_format($vvi[$ib], 2, '.', '') . '</cbc:LineExtensionAmount> 
          <cac:PricingReference>
            <cac:AlternativeConditionPrice>
              <cbc:PriceAmount currencyID="' . $monedaD[$ib] . '">' . number_format($pvi[$ib], 2, '.', '') . '</cbc:PriceAmount>
              <cbc:PriceTypeCode>01</cbc:PriceTypeCode>
            </cac:AlternativeConditionPrice>
          </cac:PricingReference>
          <cac:TaxTotal>
            <cbc:TaxAmount currencyID="' . $monedaD[$ib] . '">' . number_format($sutribitem[$ib], 2, '.', '') . '</cbc:TaxAmount>
            <cac:TaxSubtotal>
              <cbc:TaxableAmount currencyID="' . $monedaD[$ib] . '">' . number_format($vvi[$ib], 2, '.', '') . '</cbc:TaxableAmount>
              <cbc:TaxAmount currencyID="' . $monedaD[$ib] . '">' . number_format($sutribitem[$ib], 2, '.', '') . '</cbc:TaxAmount>
              <cac:TaxCategory>
                <cbc:Percent>' . $igv_ . '</cbc:Percent>
                <cbc:TaxExemptionReasonCode>' . $aigv[$ib] . '</cbc:TaxExemptionReasonCode>
                <cac:TaxScheme>
                  <cbc:ID>' . $codtrib[$ib] . '</cbc:ID>
                  <cbc:Name>' . $nomtrib[$ib] . '</cbc:Name>
                  <cbc:TaxTypeCode>' . $coditrib[$ib] . '</cbc:TaxTypeCode>
                </cac:TaxScheme>
              </cac:TaxCategory>
            </cac:TaxSubtotal>';

          if ($codigo[$ib] == "ICBPER") {

            $boletaXML .= '<cac:TaxSubtotal>
              <cbc:TaxAmount currencyID="' . $moneda[$ib] . '">' . $icbperD . '</cbc:TaxAmount>
              <cbc:BaseUnitMeasure unitCode="' . $um[$ib] . '">' . number_format($cantidad[$ib], 0, '.', '') . '</cbc:BaseUnitMeasure>
              <cac:TaxCategory>
              <cbc:PerUnitAmount currencyID="' . $moneda[$ib] . '">' . number_format($mticbperu[$ib], 2, '.', '') . '</cbc:PerUnitAmount>
                <cac:TaxScheme>
                  <cbc:ID>7152</cbc:ID>
                  <cbc:Name>ICBPER</cbc:Name>
                  <cbc:TaxTypeCode>OTH</cbc:TaxTypeCode>
                </cac:TaxScheme>
              </cac:TaxCategory>
            </cac:TaxSubtotal>';
          };

          $boletaXML .= '</cac:TaxTotal>
          <cac:Item>
            <cbc:Description><![CDATA[' . $descripcion[$ib] . ']]></cbc:Description>
            <cac:SellersItemIdentification>
              <cbc:ID>' . $codigo[$ib] . '</cbc:ID>
            </cac:SellersItemIdentification>
          </cac:Item>
            <cac:Price>
                <cbc:PriceAmount currencyID="' . $monedaD[$ib] . '">' . number_format($vui[$ib], 5, '.', '') . '</cbc:PriceAmount>
            </cac:Price>
          </cac:InvoiceLine>';
        } //Fin for

      } //Find e while 

      $boletaXML .= '</Invoice>';

      //FIN DE CABECERA ===================================================================
      // Nos aseguramos de que la cadena que contiene el XML esté en UTF-8
      $boletaXML = mb_convert_encoding($boletaXML, "UTF-8");

      // Grabamos el XML en el servidor como un fichero plano, para
      // poder ser leido por otra aplicación.
      $gestor = fopen($rutafirma . $ruc . "-" . $tipocomp . "-" . $numerodoc . ".xml", 'w');
      fwrite($gestor, $boletaXML);
      fclose($gestor);

      $cabextxml = $rutafirma . $ruc . "-" . $tipocomp . "-" . $numerodoc . ".xml";
      $cabxml = $ruc . "-" . $tipocomp . "-" . $numerodoc . ".xml";
      $nomxml = $ruc . "-" . $tipocomp . "-" . $numerodoc;
      $nomxmlruta = $rutafirma . $ruc . "-" . $tipocomp . "-" . $numerodoc;

      require_once("../greemter/Greenter.php");
      $invo = new Greenter();
      $out = $invo->getDatFac($cabextxml);

      $filenaz = $nomxml . ".zip";
      $zip = new ZipArchive();

      if ($zip->open($filenaz, ZIPARCHIVE::CREATE) === true) {
        //$zip->addEmptyDir("dummy");
        $zip->addFile($cabextxml, $cabxml);
        $zip->close();
        //if(!file_exists($rutaz)){mkdir($rutaz);}
        $imagen = file_get_contents($filenaz);
        $imageData = base64_encode($imagen);
        rename($cabextxml, $rutafirma . $cabxml);
        rename($filenaz, $rutaenvio . $filenaz);
      } else {
        $out = "Error al comprimir archivo";
      }

      $data[0] = "";

      $sxe = new SimpleXMLElement($cabextxml, null, true);
      $urn = $sxe->getNamespaces(true);
      $sxe->registerXPathNamespace('ds', $urn['ds']);
      $data = $sxe->xpath('//ds:DigestValue');
      $rpta = array('cabextxml' => $cabextxml, 'cabxml' => $cabxml, 'rutafirma' => $cabextxml);

      $sqlDetalle = "UPDATE boleta set DetalleSunat='XML firmado'  , hashc='$data[0]'  where idboleta='$idboleta'";
      ejecutarConsulta($sqlDetalle);

      //PARA ENVIO A SUNAT ================&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&))))))))))))))))))))))))))))))))))))))))))

      require_once "../modelos/Factura.php";
      $factura = new Factura();
      $datos = $factura->correo();
      $correo = $datos->fetch_object();

      require_once "../modelos/Consultas.php";
      $consultas = new consultas();
      $paramcerti = $consultas->paramscerti();
      $datosc = $paramcerti->fetch_object();

      //Inclusion de la tabla RUTAS
      require_once "../modelos/Rutas.php";
      $rutas = new Rutas();
      $Rrutas = $rutas->mostrar2($idempresa);
      $Prutas = $Rrutas->fetch_object();
      $rutafirma = $Prutas->rutafirma; // ruta de la carpeta FIRMA
      $rutaenvio = $Prutas->rutaenvio; // ruta de la carpeta FIRMA
      $rutarpta = $Prutas->rutarpta; // ruta de la carpeta FIRMA
      $rutaunzip = $Prutas->unziprpta; // ruta de la carpeta rpta xml

      $connect = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
      mysqli_query($connect, 'SET NAMES "' . DB_ENCODE . '"');
      //Si tenemos un posible error en la conexión lo mostramos
      if (mysqli_connect_errno()) {
        printf("Falló conexión a la base de datos: %s\n", mysqli_connect_error());
        exit();
      }

      $sqlsendmail = "SELECT  b.idboleta, p.email,  p.nombres, p.apellidos, p.nombre_comercial, e.numero_ruc, b.tipo_documento_06, b.numeracion_07 
      from boleta b 
      inner join persona p on b.idcliente=p.idpersona 
      inner join empresa e on b.idempresa=e.idempresa 
      where year(b.fecha_emision_01)='$ano' and month(b.fecha_emision_01)='$mes' and day(b.fecha_emision_01)='$dia' and b.idboleta='$idboleta' and b.estado='$estado'";
      $result = mysqli_query($connect, $sqlsendmail);

      $con = 0;
      while ($row = mysqli_fetch_assoc($result)) {
        for ($i = 0; $i <= count($result); $i++) {
          $correocliente = $row["email"];
        }

        //Agregar=====================================================
        // Ruta del directorio donde están los archivos

        $path = $rutafirma;
        $files = array_diff(scandir($path), array('.', '..'));

        //=============================================================

        $boleta = $row['numero_ruc'] . "-" . $row['tipo_documento_06'] . "-" . $row['numeracion_07'];

        //Validar si existe el archivo firmado

        foreach ($files as $file) {
          // Divides en dos el nombre de tu archivo utilizando el . 
          $dataSt = explode(".", $file);
          // Nombre del archivo
          $fileName = $dataSt[0];
          $st = "1";
          // Extensión del archivo 
          $fileExtension = $dataSt[1];
          if ($boleta == $fileName) {
            $archivoBoleta = $fileName;
            // Realizamos un break para que el ciclo se interrumpa
            break;
          }
        }

        //$url=$rutafirma.$archivoFactura.'.xml';
        $ZipBoleta = $rutaenvio . $archivoBoleta . '.zip';
        copy($ZipBoleta, $archivoBoleta . '.zip');
        $ZipFinal = $boleta . '.zip';
        //echo $ZipFactura;
        $webservice = $datosc->rutaserviciosunat;
        $usuarioSol = $datosc->usuarioSol;
        $claveSol = $datosc->claveSol;
        $nruc = $datosc->numeroruc;

        //Llamada al WebService=======================================================================
        $service = $webservice;
        $headers = new CustomHeaders($nruc . $usuarioSol, $claveSol);
        $client = new SoapClient( $service,  [ 'cache_wsdl' => WSDL_CACHE_NONE, 'trace' => TRUE, 'soap_version' => SOAP_1_1 ] );

        try {
          $client->__setSoapHeaders([$headers]);
          $fcs = $client->__getFunctions();
          $params = array('fileName' => $ZipFinal, 'contentFile' => file_get_contents($ZipFinal));

          //Llamada al WebService=======================================================================
          $status = $client->sendBill($params); // Comando para enviar xml a SUNAT
          $conte = $client->__getLastResponse();
          $texto = trim(strip_tags($conte));

          $zip = new ZipArchive();
          if ($zip->open("R" . $ZipFinal, ZIPARCHIVE::CREATE) === true) {
            $zip->addEmptyDir("dummy");
            $zip->close();
          }

          $rpt = fopen("R" . $ZipFinal, 'w') or die("no se pudo crear archivo");
          fwrite($rpt, base64_decode($texto));
          fclose($rpt);
          rename("R" . $ZipFinal, $rutarpta . "R" . $ZipFinal);
          unlink($ZipFinal);

          $rutarptazip = $rutarpta . "R" . $ZipFinal;
          $zip = new ZipArchive;
          if ($zip->open($rutarptazip) === TRUE) {
            $zip->extractTo($rutaunzip);
            $zip->close();
          }

          $xmlFinal = $rutaunzip . 'R-' . $boleta . '.xml';
          $data[0] = "";
          $rpta[0] = "";

          $sxe = new SimpleXMLElement($xmlFinal, null, true);
          $urn = $sxe->getNamespaces(true);
          $sxe->registerXPathNamespace('cac', $urn['cbc']);
          $data = $sxe->xpath('//cbc:Description');
          $rpta = $sxe->xpath('//cbc:ResponseCode');

          if ($rpta[0] == '0') {
            $msg = "Aceptada por SUNAT";
            $sqlCodigo = "UPDATE boleta set CodigoRptaSunat='$rpta[0]', DetalleSunat='$data[0]', estado='5' where idboleta='$idboleta'";
          } else {
            $sqlCodigo = "UPDATE boleta set CodigoRptaSunat='$rpta[0]', DetalleSunat='$data[0]', estado='4' where idboleta='$idboleta'";
          }

          ejecutarConsulta($sqlCodigo);
          return $data[0];

          // Llamada al WebService=======================================================================
        } catch (SoapFault $exception) {
          $exception = print_r($client->__getLastResponse());
          $sqlCodigo = "update boleta set CodigoRptaSunat='', DetalleSunat='VERIFICAR ENVIO' where idboleta='$idboleta'";
          ejecutarConsulta($sqlCodigo);
        }
      } //Fin While
    } //Fin de if
  } //Fin de funcion

  public function regenerarxml($idboleta, $idempresa) {

    // $connect = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    // mysqli_query($connect, 'SET NAMES "' . DB_ENCODE . '"');
    // //Si tenemos un posible error en la conexión lo mostramos
    // if (mysqli_connect_errno()) {
    //   printf("Falló conexión a la base de datos: %s\n", mysqli_connect_error());
    //   exit();
    // }

    require_once "../modelos/Factura.php";
    $factura = new Factura();
    $datos = $factura->datosemp($idempresa);
    $configuraciones = $factura->configuraciones($idempresa);
    $configE = $configuraciones->fetch_object();
    $datose = $datos->fetch_object();

    //Inclusion de la tabla RUTAS
    require_once "../modelos/Rutas.php";
    $rutas = new Rutas();
    $Rrutas = $rutas->mostrar2($idempresa);
    $Prutas = $Rrutas->fetch_object();
    $rutadata = $Prutas->rutadata; // ruta de la carpeta DATA
    $rutafirma = $Prutas->rutafirma; // ruta de la carpeta FIRMA
    $rutadatalt = $Prutas->rutadatalt; // ruta de la carpeta DATAALTERNA
    $rutaenvio = $Prutas->rutaenvio; // ruta de la carpeta DATAALTERNA

    $query = "SELECT  date_format(b.fecha_emision_01, '%Y-%m-%d') as fecha, right(substring_index(b.numeracion_07,'-',1),1) as serie, date_format(b.fecha_emision_01, '%H:%i:%s') as hora,
    p.tipo_documento as  tipodocuCliente, p.numero_documento, p.razon_social, b.tipo_moneda_24, b.monto_15_2 as subtotal, b.sumatoria_igv_18_1 as igv, 
    b.importe_total_23 as total, b.tipo_documento_06 as tipocomp, b.numeracion_07 as numerodoc, b.estado,  b.tdescuento, b.codigo_tributo_18_3 as codigotrib, 
    b.nombre_tributo_18_4  as nombretrib, b.codigo_internacional_18_5 as codigointtrib, b.codigo_tipo_15_1 as opera, e.ubigueo, b.icbper, b.formapago, b.montofpago,
    b.monedafpago, b.ccuotas, b.fechavecredito, b.montocuota, b.fechavenc     
    from boleta b 
    inner join persona p on b.idcliente=p.idpersona 
    inner join empresa e on b.idempresa=e.idempresa 
    where idboleta='$idboleta' and b.estado in('1','4','3','5') order by numerodoc";

    $querycuotas = "SELECT lpad(cu.ncuota,3,'0') as ncuota, cu.montocuota, date_format(cu.fechacuota, '%Y-%m-%d') as fechacuota, format(b.formapago,2) as formapago,
    b.tipo_moneda_24 as monedaf
    from cuotas cu inner join boleta b on cu.idcomprobante=b.idboleta
    where idcomprobante='$idboleta' and cu.tipocomprobante='03'";

    $querydetbol = "SELECT b.tipo_documento_06 as tipocomp, b.numeracion_07 as numerodoc, db.cantidad_item_12 as cantidad, a.codigo, a.nombre as descripcion, 
    um.abre as um, replace(format(db.valor_uni_item_31,5),',','') as vui, db.igv_item as igvi, db.precio_uni_item_14_2 as pvi, db.valor_venta_item_32 as vvi,
    db.afectacion_igv_item_monto_27_1 as sutribitem, db.numero_orden_item_29 as numorden, db.afectacion_igv_3 as aigv, db.afectacion_igv_4 codtrib,
    db.afectacion_igv_5 as nomtrib, db.afectacion_igv_6 as coditrib, a.codigosunat, b.tipo_moneda_24 as moneda, a.mticbperu, b.icbper, db.umedida
    from boleta b 
    inner join detalle_boleta_producto db on b.idboleta=db.idboleta 
    inner join articulo a on db.idarticulo=a.idarticulo 
    inner join umedida um on a.unidad_medida=um.idunidad
    where b.idboleta='$idboleta' and b.estado in ('1','4','3','5') order by b.fecha_emision_01";

    // $result = mysqli_query($connect, $query);
    // $resultb = mysqli_query($connect, $querydetbol);
    // $resultcuotas = mysqli_query($connect, $querycuotas);
    $result = ejecutarConsultaArray($query);
    $resultb = ejecutarConsultaArray($querydetbol);
    $resultcuotas = ejecutarConsultaArray($querycuotas);

    $nombrecomercial = $datose->nombre_comercial;
    $domiciliofiscal = $datose->domicilio_fiscal;
    $codestablecimiento = $datose->ubigueo;
    $codubigueo = $datose->codubigueo;
    $ciudad = $datose->ciudad;
    $distrito = $datose->distrito;
    $interior = $datose->interior;
    $codigopais = $datose->codigopais;

    //Parametros de salida
    $fecha = "";
    $hora = "";
    $serie = "";
    $tipodocu = "";
    $numdocu = "";
    $rasoc = "";
    $moneda = "";
    $codigotrib = "";
    $nombretrib = "";
    $codigointtrib = "";
    $subtotal = "";
    $igv = "";
    $total = "";
    $tdescu = "";
    $opera = "";
    $ubigueo = "";

    $formapago = "";
    $montofpago = "";
    $monedafpago = "";
    $ccuotas = "";
    $fechavecredito = "";
    $montocuota = "";

    $con = 0; //COntador de variable
    $icbper = "";
    foreach ($result as $key => $row) {

      //for($i=0; $i <= count($result); $i++){
      $fecha = $row["fecha"]; //Fecha emision
      $serie = $row["serie"];
      $tipodocu = $row["tipodocuCliente"]; //Tipo de documento de cliente ruc o dni
      $numdocu = $row["numero_documento"]; //NUmero de docuemnto de cliente
      $rasoc = $row["razon_social"]; //Nombre de cliente
      $moneda = $row["tipo_moneda_24"];
      $subtotal = $row["subtotal"];
      $igv = $row["igv"];
      $total = $row["total"];
      $tdescu = $row["tdescuento"];
      $hora = $row["hora"];
      $tipocomp = $row["tipocomp"];
      $numerodoc = $row["numerodoc"];
      $ruc = $datose->numero_ruc;
      $ubigueo = $datose->ubigueo;
      $opera = $row["opera"];

      $codigotrib = $row["codigotrib"]; //codigo de tributo de la tabla catalo 5
      $nombretrib = $row["nombretrib"]; //NOmbre de tributo de la tabla catalo 5
      $codigointtrib = $row["codigointtrib"]; //Codigo internacional de la tabla catalo 5

      $formapago = $row["formapago"];
      $montofpago = $row["montofpago"];
      $monedafpago = $row["monedafpago"];
      $ccuotas = $row["ccuotas"];
      $fechavecredito = $row["fechavecredito"];
      $montocuota = $row["montocuota"];

      $icbper = $row["icbper"];

      if ($moneda == 'USD') { $Lmoneda = "DOLARES AMERICANOS";  }
      $Lmoneda = "NUEVOS SOLES";

      require_once "Letras.php";
      $V = new EnLetras();
      $con_letra = strtoupper($V->ValorEnLetras($total, $Lmoneda));

      //======================================== FORMATO XML ========================================================
      //Primera parte

      $boletaXML = '<?xml version="1.0" encoding="utf-8"?>
            <Invoice xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2"
                     xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2"
                     xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"
                     xmlns:ds="http://www.w3.org/2000/09/xmldsig#"
                     xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2">
                <ext:UBLExtensions>
                    <ext:UBLExtension> <ext:ExtensionContent/> </ext:UBLExtension>
                </ext:UBLExtensions>
                <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
                <cbc:CustomizationID>2.0</cbc:CustomizationID>
                <cbc:ID>' . $numerodoc . '</cbc:ID>
                <cbc:IssueDate>' . $fecha . '</cbc:IssueDate>
                <cbc:IssueTime>' . $hora . '</cbc:IssueTime>
                <cbc:InvoiceTypeCode listID="0101">' . $tipocomp . '</cbc:InvoiceTypeCode>
                <cbc:Note languageLocaleID="1000">' . $con_letra . '</cbc:Note>
              <cbc:Note languageLocaleID="2006">Leyenda: Operación sujeta a detracción</cbc:Note>
              <cbc:DocumentCurrencyCode>' . $moneda . '</cbc:DocumentCurrencyCode>
                <cac:Signature>
                    <cbc:ID>' . $ruc . '</cbc:ID>
                    <cbc:Note>SENCON</cbc:Note>
                    <cac:SignatoryParty>
                        <cac:PartyIdentification>
                            <cbc:ID>' . $ruc . '</cbc:ID>
                        </cac:PartyIdentification>
                        <cac:PartyName>
                            <cbc:Name><![CDATA[' . $nombrecomercial . ']]></cbc:Name>
                        </cac:PartyName>
                    </cac:SignatoryParty>
                    <cac:DigitalSignatureAttachment>
                        <cac:ExternalReference>
                            <cbc:URI>#SIGN-SENCON</cbc:URI>
                        </cac:ExternalReference>
                    </cac:DigitalSignatureAttachment>
                </cac:Signature>

                <cac:AccountingSupplierParty>
                  <cac:Party>
                    <cac:PartyIdentification> <cbc:ID schemeID="6">' . $ruc . '</cbc:ID> </cac:PartyIdentification>
                    <cac:PartyName> <cbc:Name><![CDATA[' . $nombrecomercial . ']]></cbc:Name> </cac:PartyName>
                    <cac:PartyLegalEntity>
                      <cbc:RegistrationName><![CDATA[' . $nombrecomercial . ']]></cbc:RegistrationName>
                      <cac:RegistrationAddress>
                        <cbc:AddressTypeCode>' . $codestablecimiento . '</cbc:AddressTypeCode>
                        <cbc:CitySubdivisionName>' . $interior . '</cbc:CitySubdivisionName>
                        <cbc:CityName>' . $ciudad . '</cbc:CityName>
                        <cbc:CountrySubentity>' . $ciudad . '</cbc:CountrySubentity>
                        <cbc:CountrySubentityCode>' . $codubigueo . '</cbc:CountrySubentityCode>
                        <cbc:District>' . $distrito . '</cbc:District> 
                        <cac:AddressLine> <cbc:Line><![CDATA[' . $domiciliofiscal . ']]></cbc:Line> </cac:AddressLine>
                        <cac:Country> <cbc:IdentificationCode>PE</cbc:IdentificationCode> </cac:Country>
                      </cac:RegistrationAddress>
                    </cac:PartyLegalEntity>
                  </cac:Party>
                </cac:AccountingSupplierParty>

                <cac:AccountingCustomerParty>
                  <cac:Party>
                    <cac:PartyIdentification>  <cbc:ID schemeID="' . $tipodocu . '">' . $numdocu . '</cbc:ID> </cac:PartyIdentification>
                    <cac:PartyLegalEntity> <cbc:RegistrationName><![CDATA[' . $rasoc . ']]></cbc:RegistrationName> </cac:PartyLegalEntity>
                  </cac:Party>
                </cac:AccountingCustomerParty>';

      // $boletaXML.='<cac:PaymentTerms>
      // <cbc:ID>FormaPago</cbc:ID>
      // <cbc:PaymentMeansID>Contado</cbc:PaymentMeansID>
      // </cac:PaymentTerms>';

      if ($formapago == 'Contado') {
        $boletaXML .= '<cac:PaymentTerms>
                <cbc:ID>FormaPago</cbc:ID>
                <cbc:PaymentMeansID>' . $formapago . '</cbc:PaymentMeansID>
                </cac:PaymentTerms>';
      } else { // SI ES AL CREDITO

        $boletaXML .= '<cac:PaymentTerms>
                <cbc:ID>FormaPago</cbc:ID>
                <cbc:PaymentMeansID>' . $formapago . '</cbc:PaymentMeansID>
                <cbc:Amount currencyID="' . $moneda . '">' . $total . '</cbc:Amount>
                </cac:PaymentTerms>';

        $ncuotacredito    = '';
        $montocuotacredito= '';
        $fechacuotacredito= '';
        $formapagocre     = '';
        $monedaf          = '';

        foreach ($resultcuotas as $key => $rowb) {
        
          $ncuotacredito      = $rowb["ncuota"];
          $montocuotacredito  = $rowb["montocuota"];
          $fechacuotacredito  = $rowb["fechacuota"];
          $formapagocre       = $rowb["formapago"];
          $monedaf            = $rowb["monedaf"];

          $boletaXML .= '<cac:PaymentTerms>
              <cbc:ID>FormaPago</cbc:ID>
              <cbc:PaymentMeansID>Cuota' . $ncuotacredito . '</cbc:PaymentMeansID>
              <cbc:Amount currencyID="' . $monedaf . '">' . $montocuotacredito . '</cbc:Amount>
              <cbc:PaymentDueDate>' . $fechacuotacredito . '</cbc:PaymentDueDate>
              </cac:PaymentTerms>';          
        }
      }

      $boletaXML .= ' <!-- Inicio Tributos cabecera-->  
      <cac:TaxTotal>
        <cbc:TaxAmount currencyID="' . $moneda . '">' . $igv . '</cbc:TaxAmount>
          <cac:TaxSubtotal>
          <cbc:TaxableAmount currencyID="' . $moneda . '">' . $subtotal . '</cbc:TaxableAmount>
          <cbc:TaxAmount currencyID="' . $moneda . '">' . $igv . '</cbc:TaxAmount>
          <cac:TaxCategory>
            <cac:TaxScheme>
              <cbc:ID>' . $codigotrib . '</cbc:ID>
              <cbc:Name>' . $nombretrib . '</cbc:Name>
              <cbc:TaxTypeCode>' . $codigointtrib . '</cbc:TaxTypeCode>
            </cac:TaxScheme>
          </cac:TaxCategory>
        </cac:TaxSubtotal>';

      if ($icbper > 0) {

        $boletaXML .= '<cac:TaxSubtotal>
        <cbc:TaxAmount currencyID="' . $moneda . '">' . $icbper . '</cbc:TaxAmount>
          <cac:TaxCategory>
            <cac:TaxScheme>
              <cbc:ID>7152</cbc:ID>
              <cbc:Name>ICBPER</cbc:Name>
              <cbc:TaxTypeCode>OTH</cbc:TaxTypeCode>
            </cac:TaxScheme>
          </cac:TaxCategory>
        </cac:TaxSubtotal>';
      }

      $boletaXML .= '<!-- Fin Tributos  Cabecera-->
      </cac:TaxTotal>
        <cac:LegalMonetaryTotal>
          <cbc:LineExtensionAmount currencyID="' . $moneda . '">' . $subtotal . '</cbc:LineExtensionAmount>
          <cbc:TaxInclusiveAmount currencyID="' . $moneda . '">' . $total . '</cbc:TaxInclusiveAmount>
          <cbc:AllowanceTotalAmount currencyID="' . $moneda . '">0.00</cbc:AllowanceTotalAmount>
          <cbc:ChargeTotalAmount currencyID="' . $moneda . '">0.00</cbc:ChargeTotalAmount>  
          <cbc:PrepaidAmount currencyID="' . $moneda . '">0.00</cbc:PrepaidAmount>  
          <cbc:PayableAmount currencyID="' . $moneda . '">' . $total . '</cbc:PayableAmount>
        </cac:LegalMonetaryTotal>';

      //}//For cabecera

      // $i = $i + 1;
      $con = $con + 1;
    } //While cabecera

    $codigo       = "";
    $cantidad     = "";
    $descripcion  = "";
    $um           = "";
    $vui          = "";
    $igvi         = "";
    $pvi          = "";
    $vvi          = "";
    $sutribitem   = "";
    $aigv         = "";
    $codtrib      = "";
    $nomtrib      = "";
    $coditrib     = "";
    $codigosunat  = "";
    $numorden     = "";
    $monedaD      = "";
    $mticbperu    = "";

    foreach ($resultb as $key => $rowb) {     
      
        $codigo = $rowb["codigo"];
        $cantidad = $rowb["cantidad"];
        $descripcion = $rowb["descripcion"];
        $vui = $rowb["vui"];
        $sutribitem = $rowb["sutribitem"];
        $igvi = $rowb["igvi"];
        $pvi = $rowb["pvi"];
        $vvi = $rowb["vvi"];
        $um = $rowb["umedida"];
        $tipocompf = $rowb["tipocomp"];
        $numerodocf = $rowb["numerodoc"];
        $ruc = $datose->numero_ruc;
        $aigv = $rowb["aigv"];
        $codtrib = $rowb["codtrib"];
        $nomtrib = $rowb["nomtrib"];
        $coditrib = $rowb["coditrib"];
        $codigosunat = $rowb["codigosunat"];
        $numorden = $rowb["numorden"];
        $monedaD = $rowb["moneda"];
        $mticbperu = $rowb["mticbperu"];
        $icbperD = $rowb["icbper"];

        if ($codtrib == '9997') { $igv_ = "0"; } else { $igv_ = $configE->igv; }

        /* Número de orden del Ítem - Cantidad y Unidad de medida por ítem - Valor de venta del ítem  */

        $boletaXML .= '<cac:InvoiceLine>
        <cbc:ID>' . $numorden . '</cbc:ID>
        <cbc:InvoicedQuantity unitCode="' . $um . '">' . number_format($cantidad, 2, '.', '') . '</cbc:InvoicedQuantity>
        <cbc:LineExtensionAmount currencyID="' . $monedaD . '">' . number_format($vvi, 2, '.', '') . '</cbc:LineExtensionAmount>
        <cac:PricingReference>
          <cac:AlternativeConditionPrice>
            <cbc:PriceAmount currencyID="' . $monedaD . '">' . number_format($pvi, 2, '.', '') . '</cbc:PriceAmount>
            <cbc:PriceTypeCode>01</cbc:PriceTypeCode>
          </cac:AlternativeConditionPrice>
        </cac:PricingReference>
        <cac:TaxTotal>
          <cbc:TaxAmount currencyID="' . $monedaD . '">' . number_format($sutribitem, 2, '.', '') . '</cbc:TaxAmount>
          <cac:TaxSubtotal>
            <cbc:TaxableAmount currencyID="' . $monedaD . '">' . number_format($vvi, 2, '.', '') . '</cbc:TaxableAmount>
            <cbc:TaxAmount currencyID="' . $monedaD . '">' . number_format($sutribitem, 2, '.', '') . '</cbc:TaxAmount>
            <cac:TaxCategory>
              <cbc:Percent>' . $igv_ . '</cbc:Percent>
              <cbc:TaxExemptionReasonCode>' . $aigv . '</cbc:TaxExemptionReasonCode>
              <cac:TaxScheme>
                <cbc:ID>' . $codtrib . '</cbc:ID>
                <cbc:Name>' . $nomtrib . '</cbc:Name>
                <cbc:TaxTypeCode>' . $coditrib . '</cbc:TaxTypeCode>
              </cac:TaxScheme>
            </cac:TaxCategory>
          </cac:TaxSubtotal>';

        if ($codigo == "ICBPER") {

          $boletaXML .= '
          <cac:TaxSubtotal>
              <cbc:TaxAmount currencyID="' . $moneda . '">' . $icbperD . '</cbc:TaxAmount>
              <cbc:BaseUnitMeasure unitCode="' . $um . '">' . number_format($cantidad, 0, '.', '') . '</cbc:BaseUnitMeasure>
              <cac:TaxCategory>
              <cbc:PerUnitAmount currencyID="' . $moneda . '">' . number_format($mticbperu, 2, '.', '') . '</cbc:PerUnitAmount>
                  <cac:TaxScheme>
                    <cbc:ID>7152</cbc:ID>
                    <cbc:Name>ICBPER</cbc:Name>
                    <cbc:TaxTypeCode>OTH</cbc:TaxTypeCode>
                  </cac:TaxScheme>
              </cac:TaxCategory>
            </cac:TaxSubtotal>';
        };

        $boletaXML .= '</cac:TaxTotal>
          <cac:Item>
            <cbc:Description><![CDATA[' . $descripcion . ']]></cbc:Description>
            <cac:SellersItemIdentification>
            <cbc:ID>' . $codigo . '</cbc:ID>
            </cac:SellersItemIdentification>
          </cac:Item>
          <cac:Price>
            <cbc:PriceAmount currencyID="' . $monedaD . '">' . number_format($vui, 5, '.', '') . '</cbc:PriceAmount>
          </cac:Price>
        </cac:InvoiceLine>';
      

    } //Find de foreach 

    $boletaXML .= '</Invoice>';

    //FIN DE CABECERA ===================================================================
    // Nos aseguramos de que la cadena que contiene el XML esté en UTF-8

    $boletaXML = mb_convert_encoding($boletaXML, "UTF-8");
    // Grabamos el XML en el servidor como un fichero plano, para
    // poder ser leido por otra aplicación.
    $gestor = fopen($rutafirma . $ruc . "-" . $tipocomp . "-" . $numerodoc . ".xml", 'w');
    fwrite($gestor, $boletaXML);
    fclose($gestor);

    $cabextxml = $rutafirma . $ruc . "-" . $tipocomp . "-" . $numerodoc . ".xml";
    $cabxml = $ruc . "-" . $tipocomp . "-" . $numerodoc . ".xml";
    $nomxml = $ruc . "-" . $tipocomp . "-" . $numerodoc;
    $nomxmlruta = $rutafirma . $ruc . "-" . $tipocomp . "-" . $numerodoc;

    require_once("../greemter/Greenter.php");
    $invo = new Greenter();
    $out = $invo->getDatFac($cabextxml);

    $filenaz = $nomxml . ".zip";

    $zip = new ZipArchive();

    if ($zip->open($filenaz, ZIPARCHIVE::CREATE) === true) {
      //$zip->addEmptyDir("dummy");
      $zip->addFile($cabextxml, $cabxml);
      $zip->close();
      //if(!file_exists($rutaz)){mkdir($rutaz);}
      $imagen = file_get_contents($filenaz);
      $imageData = base64_encode($imagen);
      rename($cabextxml, $rutafirma . $cabxml);
      rename($filenaz, $rutaenvio . $filenaz);
    } else {
      $out = "Error al comprimir archivo";
    }
    $data[0] = "";
    $sxe = new SimpleXMLElement($cabextxml, null, true);
    $urn = $sxe->getNamespaces(true);
    $sxe->registerXPathNamespace('ds', $urn['ds']);
    $data = $sxe->xpath('//ds:DigestValue');
    $rpta = array('cabextxml' => $cabextxml, 'cabxml' => $cabxml, 'rutafirma' => $cabextxml);
    // $sqlDetalle="update boleta set DetalleSunat='XML firmado', hashc='$data[0]', estado='4' where idboleta='$idboleta'";
    // ejecutarConsulta($sqlDetalle);
    return $rpta;
  } //Fin de funcion

  public function enviarxmlSUNAT($idboleta, $idempresa) {
    require_once "../modelos/Factura.php";
    $factura = new Factura();
    $datos = $factura->correo();
    $correo = $datos->fetch_object();

    require_once "../modelos/Consultas.php";
    $consultas = new consultas();
    $paramcerti = $consultas->paramscerti();
    $datosc = $paramcerti->fetch_object();

    //Inclusion de la tabla RUTAS
    require_once "../modelos/Rutas.php";
    $rutas = new Rutas();
    $Rrutas = $rutas->mostrar2($idempresa);
    $Prutas = $Rrutas->fetch_object();
    $rutafirma = $Prutas->rutafirma; // ruta de la carpeta FIRMA
    $rutaenvio = $Prutas->rutaenvio; // ruta de la carpeta FIRMA
    $rutarpta = $Prutas->rutarpta; // ruta de la carpeta FIRMA
    $rutaunzip = $Prutas->unziprpta; // ruta de la carpeta rpta xml

    // $connect = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    // mysqli_query($connect, 'SET NAMES "' . DB_ENCODE . '"');
    // //Si tenemos un posible error en la conexión lo mostramos
    // if (mysqli_connect_errno()) {
    //   printf("Falló conexión a la base de datos: %s\n", mysqli_connect_error());
    //   exit();
    // }

    $sqlsendmail = "SELECT b.idboleta, p.email, p.nombres, p.apellidos, p.nombre_comercial, e.numero_ruc, b.tipo_documento_06, b.numeracion_07 
    from boleta b 
    inner join persona p on b.idcliente=p.idpersona inner join empresa e on b.idempresa=e.idempresa 
    where b.idboleta='$idboleta' and e.idempresa='$idempresa' ";
    $result = ejecutarConsultaArray( $sqlsendmail);

    $con = 0;
    foreach ($result as $key => $row) {
      

    // while ($row = mysqli_fetch_assoc($result)) {
    //   for ($i = 0; $i <= count($result); $i++) {
        $correocliente = $row["email"];
      // }

      //Agregar=====================================================
      // Ruta del directorio donde están los archivos

      $path = $rutafirma;
      $files = array_diff(scandir($path), array('.', '..'));

      //=============================================================
      $boleta = $row['numero_ruc'] . "-" . $row['tipo_documento_06'] . "-" . $row['numeracion_07'];

      //Validar si existe el archivo firmado

      foreach ($files as $file) {
        // Divides en dos el nombre de tu archivo utilizando el . 
        $dataSt = explode(".", $file);
        // Nombre del archivo
        $fileName = $dataSt[0];
        $st = "1";
        // Extensión del archivo 
        $fileExtension = $dataSt[1];
        if ($boleta == $fileName) {
          $archivoBoleta = $fileName;
          // Realizamos un break para que el ciclo se interrumpa
          break;
        }
      }

      //$url=$rutafirma.$archivoFactura.'.xml';
      $ZipBoleta = $rutaenvio . $archivoBoleta . '.zip';
      copy($ZipBoleta, $archivoBoleta . '.zip');
      $ZipFinal = $boleta . '.zip';
      //echo $ZipFactura;

      $webservice = $datosc->rutaserviciosunat;
      $usuarioSol = $datosc->usuarioSol;
      $claveSol = $datosc->claveSol;
      $nruc = $datosc->numeroruc;

      //Llamada al WebService=======================================================================
      $service = $webservice;
      $headers = new CustomHeaders($nruc . $usuarioSol, $claveSol);
      $client = new SoapClient( $service, ['cache_wsdl' => WSDL_CACHE_NONE, 'trace' => TRUE, 'soap_version' => SOAP_1_1 ] );

      try {
        $client->__setSoapHeaders([$headers]);
        $fcs = $client->__getFunctions();
        $params = array('fileName' => $ZipFinal, 'contentFile' => file_get_contents($ZipFinal));

        //Llamada al WebService=======================================================================
        $status = $client->sendBill($params); // Comando para enviar xml a SUNAT
        $conte = $client->__getLastResponse();
        $texto = trim(strip_tags($conte));

        $zip = new ZipArchive();
        if ($zip->open("R" . $ZipFinal, ZIPARCHIVE::CREATE) === true) {
          $zip->addEmptyDir("dummy");
          $zip->close();
        }

        $rpt = fopen("R" . $ZipFinal, 'w') or die("no se pudo crear archivo");
        fwrite($rpt, base64_decode($texto));
        fclose($rpt);
        rename("R" . $ZipFinal, $rutarpta . "R" . $ZipFinal);
        unlink($ZipFinal);

        $rutarptazip = $rutarpta . "R" . $ZipFinal;
        $zip = new ZipArchive;

        if ($zip->open($rutarptazip) === TRUE) {
          $zip->extractTo($rutaunzip);
          $zip->close();
        }

        $xmlFinal = $rutaunzip . 'R-' . $boleta . '.xml';

        $data[0] = "";
        $rpta[0] = "";

        $sxe = new SimpleXMLElement($xmlFinal, null, true);
        $urn = $sxe->getNamespaces(true);
        $sxe->registerXPathNamespace('cac', $urn['cbc']);
        $data = $sxe->xpath('//cbc:Description');
        $rpta = $sxe->xpath('//cbc:ResponseCode');

        if ($rpta[0] == '0') {
          $msg = "Aceptada por SUNAT";
          $sqlCodigo = "UPDATE boleta set CodigoRptaSunat='$rpta[0]', DetalleSunat='$data[0]', estado='5' where idboleta='$idboleta'";
        } else {
          $sqlCodigo = "UPDATE boleta set CodigoRptaSunat='$rpta[0]', DetalleSunat='No enviado revizar', estado='4' where idboleta='$idboleta'";
        }

        ejecutarConsulta($sqlCodigo);
        return $data[0];

        // Llamada al WebService=======================================================================
      } catch (SoapFault $exception) {
        $exception = print_r($client->__getLastResponse());
      }
    } //Fin While
    //return $exception;
  }

  public function enviarxmlSUNATbajas($idboleta, $idempresa) {

    require_once "../modelos/Factura.php";
    $factura = new Factura();
    $datos = $factura->correo();
    $correo = $datos->fetch_object();

    require_once "../modelos/Consultas.php";
    $consultas = new consultas();
    $paramcerti = $consultas->paramscerti();
    $datosc = $paramcerti->fetch_object();

    //Inclusion de la tabla RUTAS
    require_once "../modelos/Rutas.php";
    $rutas = new Rutas();
    $Rrutas = $rutas->mostrar2($idempresa);
    $Prutas = $Rrutas->fetch_object();
    $rutafirma = $Prutas->rutafirma; // ruta de la carpeta FIRMA
    $rutaenvio = $Prutas->rutaenvio; // ruta de la carpeta FIRMA
    $rutarpta = $Prutas->rutarpta; // ruta de la carpeta FIRMA
    $rutaunzip = $Prutas->unziprpta; // ruta de la carpeta rpta xml

    // $connect = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    // mysqli_query($connect, 'SET NAMES "' . DB_ENCODE . '"');
    // //Si tenemos un posible error en la conexión lo mostramos
    // if (mysqli_connect_errno()) { printf("Falló conexión a la base de datos: %s\n", mysqli_connect_error());
    //   exit();
    // }

    $sqlsendmail = "SELECT b.idboleta, p.email, p.nombres, p.apellidos, p.nombre_comercial, e.numero_ruc, b.tipo_documento_06, b.numeracion_07 
    from boleta b 
    inner join persona p on b.idcliente=p.idpersona inner join empresa e on b.idempresa=e.idempresa 
    where b.idboleta='$idboleta' and e.idempresa='$idempresa' ";
    $result = mysqli_query($connect, $sqlsendmail);

    $con = 0;

    foreach ($result as $key => $row) {
    
      $correocliente = $row["email"];      

      //Agregar=====================================================
      // Ruta del directorio donde están los archivos
      $path = $rutafirma;
      $files = array_diff(scandir($path), array('.', '..'));

      //=============================================================
      $boleta = $row['numero_ruc'] . "-" . $row['tipo_documento_06'] . "-" . $row['numeracion_07'];

      //Validar si existe el archivo firmado
      foreach ($files as $file) {
        // Divides en dos el nombre de tu archivo utilizando el . 
        $dataSt = explode(".", $file);
        // Nombre del archivo
        $fileName = $dataSt[0];
        $st = "1";
        // Extensión del archivo 
        $fileExtension = $dataSt[1];
        if ($boleta == $fileName) {
          $archivoBoleta = $fileName;
          // Realizamos un break para que el ciclo se interrumpa
          break;
        }
      }

      //$url=$rutafirma.$archivoFactura.'.xml';
      $ZipBoleta = $rutaenvio . $archivoBoleta . '.zip';
      copy($ZipBoleta, $archivoBoleta . '.zip');
      $ZipFinal = $boleta . '.zip';

      //echo $ZipFactura;
      $webservice = $datosc->rutaserviciosunat;
      $usuarioSol = $datosc->usuarioSol;
      $claveSol = $datosc->claveSol;
      $nruc = $datosc->numeroruc;

      //Llamada al WebService=======================================================================
      $service = $webservice;
      $headers = new CustomHeaders($nruc . $usuarioSol, $claveSol);
      $client = new SoapClient( $service, ['cache_wsdl' => WSDL_CACHE_NONE, 'trace' => TRUE, 'soap_version' => SOAP_1_1 ] );

      try {
        $client->__setSoapHeaders([$headers]);
        $fcs = $client->__getFunctions();
        $params = array('fileName' => $ZipFinal, 'contentFile' => file_get_contents($ZipFinal));

        //Llamada al WebService=======================================================================
        $status = $client->sendBill($params); // Comando para enviar xml a SUNAT
        $conte = $client->__getLastResponse();
        $texto = trim(strip_tags($conte));

        $zip = new ZipArchive();
        if ($zip->open("R" . $ZipFinal, ZIPARCHIVE::CREATE) === true) {
          $zip->addEmptyDir("dummy");
          $zip->close();
        }

        $rpt = fopen("R" . $ZipFinal, 'w') or die("no se pudo crear archivo");
        fwrite($rpt, base64_decode($texto));
        fclose($rpt);
        rename("R" . $ZipFinal, $rutarpta . "R" . $ZipFinal);
        unlink($ZipFinal);

        $rutarptazip = $rutarpta . "R" . $ZipFinal;
        $zip = new ZipArchive;
        if ($zip->open($rutarptazip) === TRUE) {
          $zip->extractTo($rutaunzip);
          $zip->close();
        }

        $xmlFinal = $rutaunzip . 'R-' . $boleta . '.xml';
        $data[0] = "";
        $rpta[0] = "";

        $sxe = new SimpleXMLElement($xmlFinal, null, true);
        $urn = $sxe->getNamespaces(true);
        $sxe->registerXPathNamespace('cac', $urn['cbc']);
        $data = $sxe->xpath('//cbc:Description');
        $rpta = $sxe->xpath('//cbc:ResponseCode');

        $sqlCodigo = "update boleta set CodigoRptaSunat='', DetalleSunat='C/BAJA' where idboleta='$idboleta'";
        ejecutarConsulta($sqlCodigo);

        return $data[0];
        // Llamada al WebService=======================================================================

      } catch (SoapFault $exception) {
        $exception = print_r($client->__getLastResponse());
      }
    } //Fin While
    //return $exception;
  }

  public function mostrarxml($idboleta, $idempresa) {

    // $connect = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    // mysqli_query($connect, 'SET NAMES "' . DB_ENCODE . '"');

    // //Si tenemos un posible error en la conexión lo mostramos
    // if (mysqli_connect_errno()) {
    //   printf("Falló conexión a la base de datos: %s\n", mysqli_connect_error());
    //   exit();
    // }

    require_once "../modelos/Factura.php";
    $factura = new Factura();
    $datos = $factura->datosemp($idempresa);
    $datose = $datos->fetch_object();
    $nombrecomercial = $datose->nombre_comercial;

    //Inclusion de la tabla RUTAS
    require_once "../modelos/Rutas.php";
    $rutas = new Rutas();
    $Rrutas = $rutas->mostrar2($idempresa);
    $Prutas = $Rrutas->fetch_object();
    $rutadata = $Prutas->rutadata; // ruta de la carpeta DATA
    $rutafirma = $Prutas->rutafirma; // ruta de la carpeta FIRMA
    $rutadatalt = $Prutas->rutadatalt; // ruta de la carpeta DATAALTERNA
    $rutaenvio = $Prutas->rutaenvio; // ruta de la carpeta rutaenvio
    $rutaunzipxml = $Prutas->unziprpta; // ruta de la carpeta ruta unziprpta

    $query = "SELECT b.tipo_documento_06 as tipocomp, b.numeracion_07 as numerodoc 
    from boleta b 
    inner join persona p on b.idcliente=p.idpersona 
    inner join empresa e on b.idempresa=e.idempresa 
    where idboleta='$idboleta' and b.estado in('1','4','5') order by numerodoc";
    $result = ejecutarConsultaArray($query);

    if ($result) {

      foreach ($result as $key => $row) {      
        $tipocomp = $row["tipocomp"];
        $numerodoc = $row["numerodoc"];
        $ruc = $datose->numero_ruc;        
      }

      $cabextxml = $rutafirma . $ruc . "-" . $tipocomp . "-" . $numerodoc . ".xml";
      $rpta = array('rutafirma' => $cabextxml);
    } else {
      $rpta = array('rutafirma' => 'Aún no se ha creado el archivo XML.');
    }
    return $rpta;
  }

  public function mostrarrpta($idboleta, $idempresa) {

    // $connect = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    // mysqli_query($connect, 'SET NAMES "' . DB_ENCODE . '"');
    // //Si tenemos un posible error en la conexión lo mostramos
    // if (mysqli_connect_errno()) {
    //   printf("Falló conexión a la base de datos: %s\n", mysqli_connect_error());
    //   exit();
    // }

    require_once "../modelos/Factura.php";
    $factura = new Factura();
    $datos = $factura->datosemp($idempresa);
    $datose = $datos->fetch_object();

    //Inclusion de la tabla RUTAS
    require_once "../modelos/Rutas.php";
    $rutas = new Rutas();
    $Rrutas = $rutas->mostrar2($idempresa);
    $Prutas = $Rrutas->fetch_object();
    $rutarpta = $Prutas->rutarpta; // ruta de la carpeta DATA
    $rutaunzipxml = $Prutas->unziprpta; // ruta de la carpeta ruta unziprpta

    $query = "SELECT b.tipo_documento_06 as tipocomp, b.numeracion_07 as numerodoc 
    from boleta b 
    inner join persona p on b.idcliente=p.idpersona 
    inner join empresa e on b.idempresa=e.idempresa 
    where idboleta='$idboleta' and b.estado in('5','4') order by numerodoc";
    $result = ejecutarConsultaArray( $query);

    $con = 0; //COntador de variable

    foreach ($result as $key => $row) {     
      $tipocomp = $row["tipocomp"];
      $numerodoc = $row["numerodoc"];
      $ruc = $datose->numero_ruc;      
    }

    $rutarptazip = $rutarpta . 'R' . $ruc . "-" . $tipocomp . "-" . $numerodoc . ".zip";
    // $zip = new ZipArchive;
    // //en la función open se le pasa la ruta de nuestro archivo (alojada en carpeta temporal)
    // if ($zip->open($rutarptazip) === TRUE) 
    // {
    //   //función para extraer el ZIP, le pasamos la ruta donde queremos que nos descomprima
    //   $zip->extractTo($rutaunzipxml);
    //   $zip->close();
    // }
    $rutaxmlrpta = $rutaunzipxml . 'R-' . $ruc . "-" . $tipocomp . "-" . $numerodoc . ".xml";
    $rpta = array('rpta' => $rutarptazip, 'rutaxmlr' => $rutaxmlrpta);
    return $rpta;
  }

  public function almacenlista() {
    $sql = "SELECT * from almacen where estado='1' order by idalmacen";
    return ejecutarConsulta($sql);
  }

  //============================================ REPORTE ===================================================
  public function reconsultarcdr($idboleta, $idempresa) {
    require_once "../modelos/Factura.php";
    $factura = new Factura();
    $datos = $factura->correo();
    $correo = $datos->fetch_object();


    require_once "../modelos/Consultas.php";
    $consultas = new consultas();
    $paramcerti = $consultas->paramscerti();
    $datosc = $paramcerti->fetch_object();

    //Inclusion de la tabla RUTAS
    require_once "../modelos/Rutas.php";
    $rutas = new Rutas();
    $Rrutas = $rutas->mostrar2($idempresa);
    $Prutas = $Rrutas->fetch_object();
    $rutafirma = $Prutas->rutafirma; // ruta de la carpeta FIRMA
    $rutaenvio = $Prutas->rutaenvio; // ruta de la carpeta FIRMA
    $rutarpta = $Prutas->rutarpta; // ruta de la carpeta FIRMA
    $rutaunzip = $Prutas->unziprpta; // ruta de la carpeta rpta xml

    // $connect = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    // mysqli_query($connect, 'SET NAMES "' . DB_ENCODE . '"');
    // //Si tenemos un posible error en la conexión lo mostramos
    // if (mysqli_connect_errno()) {
    //   printf("Falló conexión a la base de datos: %s\n", mysqli_connect_error());
    //   exit();
    // }

    $sqlsendmail = "SELECT b.idboleta, p.email,  p.nombres, p.apellidos, p.nombre_comercial, e.numero_ruc, b.tipo_documento_06, b.numeracion_07, 
    substring(b.numeracion_07,1,4) as serie, substring(b.numeracion_07,6) as numero
    from boleta b 
    inner join persona p on b.idcliente=p.idpersona 
    inner join empresa e on b.idempresa=e.idempresa 
    where b.idboleta='$idboleta' and e.idempresa='$idempresa' ";
    $result = ejecutarConsultaArray( $sqlsendmail);

    $con = 0;

    foreach ($result as $key => $row) {    
    
      $correocliente = $row["email"];      

      //Agregar=====================================================
      // Ruta del directorio donde están los archivos
      $path = $rutafirma;
      $files = array_diff(scandir($path), array('.', '..'));
      //=============================================================
      $boleta = $row['numero_ruc'] . "-" . $row['tipo_documento_06'] . "-" . $row['numeracion_07'];

      //Validar si existe el archivo firmado
      foreach ($files as $file) {
        // Divides en dos el nombre de tu archivo utilizando el . 
        $dataSt = explode(".", $file);
        // Nombre del archivo
        $fileName = $dataSt[0];
        $st = "1";
        // Extensión del archivo 
        $fileExtension = $dataSt[1];
        if ($boleta == $fileName) {
          $archivoBoleta = $fileName;
          // Realizamos un break para que el ciclo se interrumpa
          break;
        }
      }
      //$url=$rutafirma.$archivoFactura.'.xml';
      $ZipBoleta = $rutaenvio . $archivoBoleta . '.zip';
      copy($ZipBoleta, $archivoBoleta . '.zip');
      $ZipFinal = $boleta . '.zip';
      //echo $ZipFactura;

      $webservice = $datosc->rutaserviciosunat;
      $usuarioSol = $datosc->usuarioSol;
      $claveSol = $datosc->claveSol;
      $nruc = $datosc->numeroruc;

      //Llamada al WebService=======================================================================
      //$service = $webservice;
      $service = "https://e-factura.sunat.gob.pe/ol-it-wsconscpegem/billConsultService?wsdl";

      $headers = new CustomHeaders($nruc . $usuarioSol, $claveSol);
      $client = new SoapClient( $service, [ 'cache_wsdl' => WSDL_CACHE_NONE, 'trace' => TRUE, 'soap_version' => SOAP_1_1 ] );
      try {
        $client->__setSoapHeaders([$headers]);
        $fcs = $client->__getFunctions();
        $params = [
          'rucComprobante' => $nruc,
          'tipoComprobante' => $row['tipo_documento_06'],
          'serieComprobante' => $row['serie'],
          'numeroComprobante' => $row['numero'],
        ];

        //Llamada al WebService=======================================================================
        $response = $client->__soapCall('getStatusCdr', ['parameters' => $params]);
        isset($response->statusCdr->content) ? file_put_contents($rutarpta . "R" . $ZipFinal, $response->statusCdr->content) : '';
        $result = (object) [
          'statusCode' => $response->statusCdr->statusCode,
          'statusMessage' => $response->statusCdr->statusMessage,
          'cdr' => $ZipFinal
        ];

        if ($response->statusCdr->statusCode == "0004") {

          $zip = new ZipArchive();
          if ($zip->open("R" . $ZipFinal, ZIPARCHIVE::CREATE) === true) {
            $zip->addEmptyDir("dummy");
            $zip->close();
          }

          $rutarptazip = $rutarpta . "R" . $ZipFinal;
          $zip = new ZipArchive;
          if ($zip->open($rutarptazip) === TRUE) {
            $zip->extractTo($rutaunzip);
            $zip->close();
          }
          $xmlFinal = $rutaunzip . 'R-' . $boleta . '.xml';
          $data[0] = "";
          $rpta[0] = "";
          $sxe = new SimpleXMLElement($xmlFinal, null, true);
          $urn = $sxe->getNamespaces(true);
          $sxe->registerXPathNamespace('cac', $urn['cbc']);
          $data = $sxe->xpath('//cbc:Description');
          $rpta = $sxe->xpath('//cbc:ResponseCode');

          if ($rpta[0] == '0') {
            $msg = "Aceptada por SUNAT";
            $sqlCodigo = "update boleta set CodigoRptaSunat='$rpta[0]', DetalleSunat='$data[0]', estado='5' where idboleta='$idboleta'";
          } else {
            $sqlCodigo = "update boleta set CodigoRptaSunat='$rpta[0]', DetalleSunat='$data[0]', estado='4' where idboleta='$idboleta'";
          }
          ejecutarConsulta($sqlCodigo);
          return $response->statusCdr->statusMessage . " para comprobante: " . $ZipFinal;
        } else {

          return $response->statusCdr->statusCode;
        }
        // Llamada al WebService=======================================================================
      } catch (SoapFault $exception) {
        $exception = print_r($client->__getLastResponse());
      }
    } //Fin While
    //return $cdr->statusCode;;
  }


  public function mostrartipocambio($fecha) {

    $sql = "SELECT idtipocambio, date_format(fecha, '%Y-%m-%d') as fecha, compra, venta from tcambio where fecha='$fecha'";
    return ejecutarConsultaSimpleFila($sql);
  }

  public function cambiartarjetadc($idboleta, $opcion)
  {
    if ($opcion == '1') {
      $sql = "update boleta set tarjetadc='$opcion' where idboleta='$idboleta'";
    } else {
      $sql = "update boleta set tarjetadc='$opcion', montotarjetadc='0' where idboleta='$idboleta'";
    }

    return ejecutarConsulta($sql);
  }


  public function montotarjetadc($idboleta, $mto)
  {
    $sql = "update boleta set montotarjetadc='$mto' where idboleta='$idboleta'";
    return ejecutarConsulta($sql);
  }




  public function cambiartransferencia($idboleta, $opcion)
  {
    if ($opcion == '1') {
      $sql = "update boleta set transferencia='$opcion' where idboleta='$idboleta'";
    } else {
      $sql = "update boleta set transferencia='$opcion', montotransferencia='0' where idboleta='$idboleta'";
    }


    return ejecutarConsulta($sql);
  }


  public function montotransferencia($idboleta, $mto)  {
    $sql = "update boleta set montotransferencia='$mto' where idboleta='$idboleta'";
    return ejecutarConsulta($sql);
  }

  public function duplicar($idboleta) {
    
    // $connect = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    // mysqli_query($connect, 'SET NAMES "' . DB_ENCODE . '"');
    // //Si tenemos un posible error en la conexión lo mostramos
    // if (mysqli_connect_errno()) {
    //   printf("Falló conexión a la base de datos: %s\n", mysqli_connect_error());
    //   exit();
    // }

    $sw = true;

    $seriebol = "SELECT left(numeracion_07,4) as serie from boleta where idboleta='$idboleta'";
    $buscaserie = ejecutarConsultaArray( $seriebol);
    $serie = "";

    foreach ($buscaserie as $key => $row) {      
      $serie = $row["serie"];      
    }

    $buscanumero = "SELECT numero from numeracion  where serie='$serie'";
    $numeroobt = ejecutarConsultaArray( $buscanumero);

    $nnumero = 0;

    foreach ($numeroobt as $key => $row) {      
      $nnumero = $row["numero"];      
    }

    $nnumero = $nnumero + 1;

    $sqlcabecera = "INSERT INTO boleta ( idusuario, fecha_emision_01, firma_digital_36, idempresa, tipo_documento_06, numeracion_07, idcliente, 
    codigo_tipo_15_1, monto_15_2, sumatoria_igv_18_1, sumatoria_igv_18_2, codigo_tributo_18_3, nombre_tributo_18_4, 
    codigo_internacional_18_5, importe_total_23, codigo_leyenda_26_1, descripcion_leyenda_26_2, tipo_documento_25_1, guia_remision_25, 
    version_ubl_37, version_estructura_38, tipo_moneda_24, tasa_igv, tipodocuCliente, rucCliente, RazonSocial, fecha_baja, comentario_baja, 
    tdescuento, vendedorsitio, icbper, CodigoRptaSunat, DetalleSunat, tcambio, transferencia, ntrans, hashc, montotransferencia, tarjetadc, montotarjetadc, 
    formapago, montofpago, monedafpago, ccuotas, montocuota, fechavecredito )
    select idusuario, fecha_emision_01, firma_digital_36, idempresa, tipo_documento_06, '$serie-$nnumero', idcliente, codigo_tipo_15_1, monto_15_2, 
    sumatoria_igv_18_1, sumatoria_igv_18_2, codigo_tributo_18_3, nombre_tributo_18_4, codigo_internacional_18_5, importe_total_23, codigo_leyenda_26_1, 
    descripcion_leyenda_26_2, tipo_documento_25_1, guia_remision_25, version_ubl_37, version_estructura_38, tipo_moneda_24, tasa_igv,   
    tipodocuCliente, rucCliente, RazonSocial, fecha_baja, comentario_baja, tdescuento, vendedorsitio, icbper, CodigoRptaSunat, 'EMITIDO', tcambio,         
    transferencia, ntrans, hashc, montotransferencia, tarjetadc, montotarjetadc, formapago, montofpago, monedafpago, ccuotas, montocuota, 
    fechavecredito       
    from boleta where idboleta='$idboleta'";
    $idBoletaNew = ejecutarConsulta_retornarID($sqlcabecera);

    $updatenumeracion = "UPDATE numeracion set numero='$nnumero' where serie='$serie'";
    ejecutarConsulta($updatenumeracion);
    //ejecutarConsulta($sqlcabecera) or $sw=false;

    $sqldetalle1 = "SELECT iddetalle, db.idboleta,idarticulo, numero_orden_item_29, cantidad_item_12, codigo_precio_14_1, precio_uni_item_14_2, afectacion_igv_item_monto_27_1,
    afectacion_igv_item_monto_27_2, afectacion_igv_3, afectacion_igv_4, afectacion_igv_5, afectacion_igv_6, igv_item, valor_uni_item_31, valor_venta_item_32, dcto_item, descdet, umedida 
    from boleta b inner join detalle_boleta_producto db on b.idboleta=db.idboleta
    where b.idboleta='$idboleta'";
    $resultdb = ejecutarConsultaArray( $sqldetalle1);

    $idarticulo           = '';
    $numero_orden_item_29 = '';
    $cantidad_item_12     = '';
    $codigo_precio_14_1   = '';
    $precio_uni_item_14_2 = '';
    $afectacion_igv_item_monto_27_1 = '';
    $afectacion_igv_item_monto_27_2 = '';
    $afectacion_igv_3     = '';
    $afectacion_igv_4     = '';
    $afectacion_igv_5     = '';
    $afectacion_igv_6     = '';
    $igv_item             = '';
    $valor_uni_item_31    = '';
    $valor_venta_item_32  = '';
    $dcto_item            = '';
    $descdet              = '';
    $umedida              = '';

    foreach ($resultdb as $key => $row) {    
    
      $idarticulo           = $row["idarticulo"];
      $numero_orden_item_29 = $row["numero_orden_item_29"];
      $cantidad_item_12     = $row["cantidad_item_12"];
      $codigo_precio_14_1   = $row["codigo_precio_14_1"];
      $precio_uni_item_14_2 = $row["precio_uni_item_14_2"];
      $afectacion_igv_item_monto_27_1 = $row["afectacion_igv_item_monto_27_1"];
      $afectacion_igv_item_monto_27_2 = $row["afectacion_igv_item_monto_27_2"];
      $afectacion_igv_3     = $row["afectacion_igv_3"];
      $afectacion_igv_4     = $row["afectacion_igv_4"];
      $afectacion_igv_5     = $row["afectacion_igv_5"];
      $afectacion_igv_6     = $row["afectacion_igv_6"];
      $igv_item             = $row["igv_item"];
      $valor_uni_item_31    = $row["valor_uni_item_31"];
      $valor_venta_item_32  = $row["valor_venta_item_32"];
      $dcto_item            = $row["dcto_item"];
      $descdet              = $row["descdet"];
      $umedida              = $row["umedida"];

      $sqldetalle = "INSERT INTO detalle_boleta_producto ( idboleta, idarticulo,  numero_orden_item_29,  cantidad_item_12, codigo_precio_14_1, precio_uni_item_14_2,
      afectacion_igv_item_monto_27_1, afectacion_igv_item_monto_27_2, afectacion_igv_3, afectacion_igv_4, afectacion_igv_5, 
      afectacion_igv_6, igv_item, valor_uni_item_31, valor_venta_item_32, dcto_item, descdet,  umedida) 
      values  ('$idBoletaNew', '$idarticulo', '$numero_orden_item_29', '$cantidad_item_12', '$codigo_precio_14_1', '$precio_uni_item_14_2', 
      '$afectacion_igv_item_monto_27_1', '$afectacion_igv_item_monto_27_2', '$afectacion_igv_3', '$afectacion_igv_4', '$afectacion_igv_5',
      '$afectacion_igv_6', '$igv_item', '$valor_uni_item_31', '$valor_venta_item_32', '$dcto_item', '$descdet', '$umedida' )";      
      $detalle = ejecutarConsulta($sqldetalle); // or $sw=false; 
      
    }
    return $detalle;
  }

  public function savedetalsesion($idusuario, $tcomprobante, $idcomprobante) {
    $sql = "INSERT INTO detalle_usuario_sesion (idusuario, tcomprobante, idcomprobante, fechahora) values ('$idusuario', '$tcomprobante','$idcomprobante', now())";
    return ejecutarConsulta($sql);
  }
}