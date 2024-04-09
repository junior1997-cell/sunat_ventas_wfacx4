<?php
//Incluímos inicialmente la conexión a la base de datos

use Complex\Functions;

require "../config/Conexion.php";



class Notapedido
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
    $codigo, $unidad_medida, $idserie, $SerieReal, $numero_notapedido, $tipodocuCliente, $rucCliente, $RazonSocial, $hora, $dctoitem, $vendedorsitio, $tcambio, $totaldescu,
    $domicilio_fiscal, $tipopago, $nroreferencia, $ipagado, $saldo, $descdet, $total_icbper, $tiponota, $cantidadreal, $ccuotas, $fechavecredito, $montocuota, $tadc,
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

    $sql = "INSERT INTO notapedido (idusuario, fecha_emision_01, firma_digital_36, idempresa, tipo_documento_06, numeracion_07, idcliente, codigo_tipo_15_1, monto_15_2, sumatoria_igv_18_1, 
    sumatoria_igv_18_2, codigo_tributo_18_3, nombre_tributo_18_4, codigo_internacional_18_5, importe_total_23, codigo_leyenda_26_1, descripcion_leyenda_26_2,  tipo_documento_25_1, guia_remision_25, version_ubl_37, 
    version_estructura_38, tipo_moneda_24, tasa_igv, estado, tipodocuCliente, rucCliente, RazonSocial, tdescuento, vendedorsitio, tcambio, 
    icbper, tiponota, formapago, montofpago, monedafpago, 
    ccuotas, fechavecredito, montocuota, tarjetadc, transferencia, montotarjetadc, montotransferencia, efectivo, visa,
    yape, plin, mastercard, deposito )
    values
    ('$idusuario', '$fecha_emision_01 $hora', '$firma_digital_36', '$idempresa', '$tipo_documento_06', '$SerieReal-$numero_notapedido', '$idcl', '$codigo_tipo_15_1', '$monto_15_2', '$sumatoria_igv_18_1',
    '$sumatoria_igv_18_2', (select codigo from catalogo5 where codigo='$sumatoria_igv_18_3'), (select descripcion from catalogo5 where codigo='$sumatoria_igv_18_3'), (select unece5153 from catalogo5 where codigo='$sumatoria_igv_18_3'), '$importe_total_23', '$codigo_leyenda_26_1', '$descripcion_leyenda_26_2', '$tipo_documento_25_1', '$guia_remision_25', '$version_ubl_37',
    '$version_estructura_38', '$tipo_moneda_24', '$tasa_igv', '$st', '$tipodocuCliente', '$rucCliente', '$RazonSocial', '$totaldescu', '$vendedorsitio', '$tcambio',
    '$total_icbper', '$tiponota', '$formapago', '$montofpago', '$monedafpago',
    '$ccuotas', '$fechavecredito', '$montocuota', '$tadc', '$transferencia', '$montotar', '$montotran', '$efectivo', '$visa',
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
          $sql_detalle = "INSERT INTO detalle_notapedido_producto(
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
          ('$idBoletaNew', '$idarticulo[$ii]', 'VENTA', '$codigo[$ii]', '$fecha_emision_01', '03' , '$SerieReal-$numero_notapedido', '$cantidadreal[$ii]', '$vvu[$ii]', '$unidad_medida[$ii]',
          '' , '' , '' , '$idempresa','$tcambio', '$tipo_moneda_24')";

          $sqlupdatecliente = "UPDATE persona set domicilio_fiscal='$domicilio_fiscal', razon_social='$RazonSocial', nombre_comercial='$RazonSocial', nombres='$RazonSocial'  where idpersona='$idcl'";

          ejecutarConsulta($sql_detalle);
          ejecutarConsulta($sql_kardex);
          ejecutarConsulta($sqlupdatecliente);

          if ($tiponota != 'servicios') {
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
      $sql_update_numeracion = "UPDATE numeracion set numero='$numero_notapedido' where idnumeracion='$idserie'";
      ejecutarConsulta($sql_update_numeracion);
      //Fin
    } catch (Exception $e) {
      echo 'Error es: ', $e->getMessage(), "\n";
    }
    //=============== EXPORTAR COMPROBANTES A TXT ======================
    return $idBoletaNew; //FIN DE FUNCION GUARDAR
  }



  //Implementamos un método para anular la factura
  public function anular($idboleta)
  {

    $connect = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    mysqli_query($connect, 'SET NAMES "' . DB_ENCODE . '"');
    //Si tenemos un posible error en la conexión lo mostramos
    if (mysqli_connect_errno()) {
      printf("Falló conexión a la base de datos: %s\n", mysqli_connect_error());
      exit();
    }

    $query = "SELECT idboleta, idarticulo  from detalle_notapedido_producto where idboleta='$idboleta'";
    $resultado = mysqli_query($connect, $query);


    $Idb = array();
    $Ida = array();
    $sw = true;

    while ($fila = mysqli_fetch_assoc($resultado)) {
      $num_filas = mysqli_num_rows($resultado);
      for ($i = 0; $i < $num_filas; $i++) {
        $Idb[$i] = $fila["idboleta"];
        $Ida[$i] = $fila["idarticulo"];

        $sql_update_articulo = "UPDATE
     detalle_notapedido_producto de inner join articulo a  on de.idarticulo=a.idarticulo set
       a.saldo_finu=a.saldo_finu + de.cantidad_item_12,
       a.stock=a.stock + de.cantidad_item_12,
       a.ventast=a.ventast - de.cantidad_item_12,
       a.valor_finu=(a.saldo_finu + a.comprast - a.ventast) * a.costo_compra
        where
        de.idboleta='$Idb[$i]' and de.idarticulo='$Ida[$i]'";

        //ACTUALIZAR TIPO TRANSACCION KARDEX
        //Guardar en Kardex
        $sql_kardex = "insert into
    kardex
     (idcomprobante,
      idarticulo,
      transaccion,
      codigo,
      fecha,
      tipo_documento,
      numero_doc,
      cantidad,
      costo_1,
      unidad_medida,
      saldo_final,
      costo_2,valor_final)

            values

            ('$idboleta',

            (select a.idarticulo from articulo a inner join detalle_notapedido_producto dtb on a.idarticulo=dtb.idarticulo where a.idarticulo='$Ida[$i]' and dtb.idboleta = '$Idb[$i]'),

            'ANULADO',

            (select a.codigo from articulo a inner join detalle_notapedido_producto dtb on a.idarticulo=dtb.idarticulo where a.idarticulo='$Ida[$i]' and dtb.idboleta = '$Idb[$i]'),

             (select fecha_emision_01 from boleta where idboleta='$Idb[$i]'),
             '01',
             (select numeracion_07 from boleta where idboleta='$Idb[$i]'),

  (select dtb.cantidad_item_12 from articulo a inner join detalle_notapedido_producto dtb on a.idarticulo=dtb.idarticulo where a.idarticulo='$Ida[$i]' and dtb.idboleta = '$Idb[$i]'),

  (select dtb.valor_uni_item_31 from articulo a inner join detalle_notapedido_producto dtb on a.idarticulo=dtb.idarticulo where a.idarticulo='$Ida[$i]' and dtb.idboleta = '$Idb[$i]'),

  (select a.unidad_medida from articulo a inner join detalle_notapedido_producto dtb on a.idarticulo=dtb.idarticulo where a.idarticulo='$Ida[$i]' and dtb.idboleta = '$Idb[$i]'),

  0, 0, 0)";

        $sqlestado = "update
        notapedido
        set
        estado='0'
        where
        idboleta='$idboleta'";
      }

      ejecutarConsulta($sql_update_articulo) or $sw = false;
      ejecutarConsulta($sql_kardex) or $sw = false;
      ejecutarConsulta($sqlestado) or $sw = false;
    }

    return $sw;
  }

  public function baja($idnotap, $fecha_baja, $com, $hora) {
    $sw = true;
    $connect = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    mysqli_query($connect, 'SET NAMES "' . DB_ENCODE . '"');
    //Si tenemos un posible error en la conexión lo mostramos
    if (mysqli_connect_errno()) {
      printf("Falló conexión a la base de datos: %s\n", mysqli_connect_error());
      exit();
    }
    $query = "SELECT idboleta, idarticulo  from detalle_notapedido_producto where idboleta='$idnotap'";
    $resultado = mysqli_query($connect, $query);
    $Idb = array();
    $Ida = array();
    while ($fila = mysqli_fetch_assoc($resultado)) {
      $num_filas = mysqli_num_rows($resultado);
      for ($i = 0; $i < $num_filas; $i++) {
        $Idb[$i] = $fila["idboleta"];
        $Ida[$i] = $fila["idarticulo"];

        $sql_update_articulo = "UPDATE detalle_notapedido_producto de 
        inner join articulo a  on de.idarticulo=a.idarticulo 
        set a.saldo_finu=a.saldo_finu + de.cantidad_item_12, a.stock=a.stock + de.cantidad_item_12, a.ventast=a.ventast - de.cantidad_item_12,
        a.valor_finu=(a.saldo_finu + a.comprast - a.ventast) * a.costo_compra
        where de.idboleta='$Idb[$i]' and de.idarticulo='$Ida[$i]'";

        //ACTUALIZAR TIPO TRANSACCION KARDEX
        //Guardar en Kardex
        $sql_kardex = "insert into
    kardex
     (idcomprobante,
      idarticulo,
      transaccion,
      codigo,
      fecha,
      tipo_documento,
      numero_doc,
      cantidad,
      costo_1,
      unidad_medida,
      saldo_final,
      costo_2,valor_final)

            values

            ('$idnotap',

            (select a.idarticulo from articulo a inner join detalle_notapedido_producto dtb on a.idarticulo=dtb.idarticulo where a.idarticulo='$Ida[$i]' and dtb.idboleta = '$Idb[$i]'),

            'ANULADO',

            (select a.codigo from articulo a inner join detalle_notapedido_producto dtb on a.idarticulo=dtb.idarticulo where a.idarticulo='$Ida[$i]' and dtb.idboleta = '$Idb[$i]'),

             (select fecha_emision_01 from notapedido where idboleta='$Idb[$i]'),
             '50',
             (select numeracion_07 from notapedido where idboleta='$Idb[$i]'),

  (select dtb.cantidad_item_12 from articulo a inner join detalle_notapedido_producto dtb on a.idarticulo=dtb.idarticulo where a.idarticulo='$Ida[$i]' and dtb.idboleta = '$Idb[$i]'),

  (select dtb.valor_uni_item_31 from articulo a inner join detalle_notapedido_producto dtb on a.idarticulo=dtb.idarticulo where a.idarticulo='$Ida[$i]' and dtb.idboleta = '$Idb[$i]'),

  (select a.unidad_medida from articulo a inner join detalle_notapedido_producto dtb on a.idarticulo=dtb.idarticulo where a.idarticulo='$Ida[$i]' and dtb.idboleta = '$Idb[$i]'),

  0, 0, 0)";
      }

      ejecutarConsulta($sql_update_articulo) or $sw = false;
      ejecutarConsulta($sql_kardex) or $sw = false;
    }

    $sqlestado = "update
        notapedido
        set
        estado='3',
        fecha_baja='$fecha_baja $hora',
        comentario_baja='$com'
        where
        idboleta='$idnotap'";
    ejecutarConsulta($sqlestado) or $sw = false;



    return $sw;
  }


  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar($idboleta)
  {
    $sql = "SELECT
        b.idboleta,
        date(b.fecha_emision_01) as fecha,
        b.idcliente,p.razon_social as cliente,
        p.numero_documento,
        p.domicilio_fiscal,
        u.idusuario,
        u.nombre as usuario,
        b.tipo_documento_06,
        b.numeracion_07,
        b.importe_total_23,
        b.estado
        from
        notapedido b inner join persona p on b.idcliente=p.idpersona inner join usuario u on b.idusuario=u.idusuario WHERE b.idboleta='$idboleta'";
    return ejecutarConsultaSimpleFila($sql);
  }

  public function listarDetalle($idboleta)
  {
    $sql = "SELECT
        df.idboleta,
        df.idarticulo,
        a.nombre,
        df.cantidad_item_12,
        df.valor_uni_item_14,
        df.valor_venta_item_21,
        df.igv_item
        from
        detalle_fac_art df inner join articulo a on df.idarticulo=a.idarticulo where df.idboleta='$idboleta'";
    return ejecutarConsulta($sql);
  }

  //Implementar un método para listar los registros
  public function listar()
  {
    $sql = "SELECT
        b.idboleta,
        date_format(b.fecha_emision_01,'%d/%m/%y') as fecha,
        b.idcliente,
        left(p.razon_social,20) as cliente,
        b.vendedorsitio,
        u.nombre as usuario,
        b.tipo_documento_06,
        b.numeracion_07,
        b.monto_15_2,
        b.adelanto,
        b.faltante,
        format(b.importe_total_23,2) as importe_total_23,
        b.estado,
        p.nombres,
        p.apellidos,
        e.numero_ruc,
        p.email,
        p.idpersona
        from
        notapedido b inner join persona p on b.idcliente=p.idpersona
        inner join usuario u on b.idusuario=u.idusuario
        inner join empresa e on b.idempresa=e.idempresa
        order by b.idboleta desc";
    return ejecutarConsulta($sql);
  }


  public function ventacabecera($idboleta)
  {
    $sql = "SELECT
        np.idboleta,
        np.idcliente,
        p.razon_social,
        p.nombres as cliente,
        p.domicilio_fiscal as direccion,
        p.tipo_documento,
        p.numero_documento,
        p.email,
        p.telefono1,
        np.idusuario,
        u.nombre as usuario,
        np.tipo_documento_06,
        np.numeracion_07,
        right(substring_index(np.numeracion_07,'-',1),4) as serie,
        np.numeracion_07 as numerofac,
        date_format(np.fecha_emision_01,'%d-%m-%Y') as fecha,
        date_format(np.fecha_emision_01,'%Y-%m-%d') as fecha2,
        date_format(np.fecha_emision_01, '%H:%i:%s') as hora,
        np.importe_total_23 as totalLetras,
        np.importe_total_23 as itotal,
        np.estado,
        e.numero_ruc,
        np.tdescuento,
        np.guia_remision_25 as guia,
        np.vendedorsitio,
        np.sumatoria_igv_18_1,
        np.ncotizacion,
        np.ambtra,
        np.efectivo,
        np.visa,
        np.yape,
        np.plin,
        np.mastercard as masterC,
        np.deposito as dep,
        np.adelanto,
        np.faltante,
        np.monto_15_2 as subtotal

        from
        notapedido np inner join persona p on np.idcliente=p.idpersona
        inner join usuario u on np.idusuario=u.idusuario
        inner join empresa e on np.idempresa=e.idempresa
         where np.idboleta='$idboleta'";
    return ejecutarConsulta($sql);
  }

  public function recibospendientes($idcliente)
  {
    $sql = "SELECT  numeracion_07, importe_total_23 as total from notapedido np inner join persona p on np.idcliente=p.idpersona where p.idpersona='$idcliente' and np.estado='1'";
    return ejecutarConsulta($sql);
  }

  public function ventadetalle($idboleta)
  {
    $sql = "SELECT a.nombre as articulo, a.codigo, format(db.cantidad_item_12,2) as cantidad_item_12, db.valor_uni_item_31,
    db.precio_uni_item_14_2, db.valor_venta_item_32, format(valor_venta_item_32,2) as subtotal, db.dcto_item, db.descdet,
    um.abre
    from detalle_notapedido_producto db 
    inner join articulo a on db.idarticulo=a.idarticulo 
    inner join umedida um on a.unidad_medida=um.idunidad
    where db.idboleta='$idboleta'";
    return ejecutarConsulta($sql);
  }

  public function listarD()
  {
    $sql = "SELECT
        documento
        from
        correlativo
        where
        documento='factura' or documento='boleta' or documento='nota de credito'or documento='nota de debito' group by documento";
    return ejecutarConsulta($sql);
  }

  public function datosemp()
  {

    $sql = "SELECT * from empresa where idempresa='1'";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para dar de baja a factura
  public function ActualizarEstado($idboleta, $st)
  {
    $sw = true;
    $sqlestado = "update notapedido set estado='$st' where idboleta='$idboleta'";
    ejecutarConsulta($sqlestado) or $sw = false;
    return $sw;
  }


  public function listarcomprobantes($dnicliente)
  {
    $sql = "SELECT
        n.idboleta,
        date_format(n.fecha_emision_01,'%d/%m/%y') as fecha,
        n.idcliente,
        left(p.razon_social,20) as cliente,
        u.nombre as usuario,
        n.tipo_documento_06,
        n.numeracion_07,
        format(n.importe_total_23,2) as total,
        n.estado,
        p.nombres,
        p.apellidos,
        e.numero_ruc,
        n.numeracion_07 as numeroserie
        from
        notapedido n inner join persona p on n.idcliente=p.idpersona
        inner join usuario u on n.idusuario=u.idusuario
        inner join empresa e on n.idempresa=e.idempresa
        where p.numero_documento='$dnicliente' and n.estado='1'
        order by n.idboleta desc";
    return ejecutarConsulta($sql);
  }

  public function listarcomprobantesCE()
  {
    $sql = "SELECT
        n.idboleta,
        date_format(n.fecha_emision_01,'%d/%m/%y') as fecha,
        n.idcliente,
        left(p.razon_social,20) as cliente,
        u.nombre as usuario,
        n.tipo_documento_06,
        n.numeracion_07,
        format(n.importe_total_23,2) as total,
        n.estado,
        p.nombres,
        p.apellidos,
        e.numero_ruc,
        n.numeracion_07 as numeroserie
        from
        notapedido n inner join persona p on n.idcliente=p.idpersona
        inner join usuario u on n.idusuario=u.idusuario
        inner join empresa e on n.idempresa=e.idempresa
        where n.estado='1'
        order by n.idboleta desc";
    return ejecutarConsulta($sql);
  }




  public function actualizarestados($idnota, $cestado) {
    $ii = 0;
    $sw = true;
    while ($ii < count($idnota)) {
      //Guardar en Detalle
      $sql = "update notapedido set estado='$cestado' where idboleta= '$idnota[$ii]'";
      ejecutarConsulta($sql) or $sw = false;
      $ii = $ii + 1;
    }
    return $sw;
  }

  public function almacenlista() {
    $sql = "SELECT * from almacen where estado='1' order by idalmacen";
    return ejecutarConsulta($sql);
  }


  public function mostrarultimocomprobanteId($idempresa) {
    $sql = "SELECT np.idboleta, e.tipoimpresion from notapedido np inner join empresa e on np.idempresa=e.idempresa  where e.idempresa='$idempresa'  order by idboleta desc limit 1";
    return ejecutarConsultaSimpleFila($sql);
  }

  // ::::::::::::::::::::::::: NOTA DE VENTA A4 ::::::::::::::::::::::::::::::::::::::
  public function imprimirA4($idboleta){

    $sql_1="SELECT nombre_razon_social, domicilio_fiscal, numero_ruc, telefono1, telefono2, correo, logo,
    banco1, banco2, banco3, banco4, cuenta1, cuenta2, cuenta3, cuenta4, cuentacci1, cuentacci2, cuentacci3, cuentacci4
    FROM empresa
    WHERE idempresa='1';";
    $empresa = ejecutarConsultaSimpleFila2($sql_1); if ($empresa['status'] == false) { return  $empresa;}

    $sql_2="SELECT np.idboleta, DATE_FORMAT(np.fecha_emision_01, '%d/%m/%Y %h:%i %p') as fecha_emision, np.RazonSocial, np.rucCliente, p.domicilio_fiscal,
    np.monto_15_2 n_subtotal, np.tdescuento dest_total, np.sumatoria_igv_18_1 IGV, np.numeracion_07,
    (np.monto_15_2 + np.tdescuento + np.sumatoria_igv_18_1) AS total_general
    FROM notapedido as np, persona as p
    WHERE np.idboleta ='$idboleta'
    AND np.idcliente = p.idpersona;";
    $venta = ejecutarConsultaSimpleFila2($sql_2); if ($venta['status'] == false) { return  $venta;}

    $sql_3="SELECT dnp.iddetalle, a.codigo, a.nombre, dnp.umedida UM, dnp.cantidad_item_12 cantidad, dnp.precio_uni_item_14_2 p_unitario, dnp.dcto_item descuento, dnp.afectacion_igv_item_monto_27_1 a_subtotal
    FROM detalle_notapedido_producto as dnp, notapedido as np, articulo as a
    WHERE dnp.idboleta = '$idboleta'
    AND dnp.idboleta = np.idboleta
    AND dnp.idarticulo = a.idarticulo;";
    $detalles = ejecutarConsultaArray2($sql_3); if ($detalles['status'] == false) {return $detalles;}

    return $retorno=['status'=>true, 'message'=>'consulta ok', 
    'data'=>[  
      'empresa'   =>$empresa['data'],
      'venta'     =>$venta['data'],
      'detalles'  =>$detalles['data']
    ]];

  }




}
