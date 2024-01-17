<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Notacf
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  //Implementar un método para listar los registros y mostrar en el select
  public function selectD()
  {
    $sql = "SELECT codigo, descripcion from catalogo9";
    return ejecutarConsulta($sql);
  }


  function buscarComprobante($idempresa, $moneda)  {

    $sql = "SELECT  idfactura, tipo_documento as tdcliente, tip_doc, numero_documento as ndcliente, razon_social as rzcliente, ra_so, domicilio_fiscal as domcliente, 
    tipo_documento_07 as tipocomp, numeracion_08 as numerodoc, total_operaciones_gravadas_monto_18_2 as subtotal, sumatoria_igv_22_1 as igv, importe_total_venta_27 as total, 
    date_format(fecha_emision_01, '%d-%m-%Y') as fecha1,  fecha2, tmoneda
    from (
      select f.idfactura, p.tipo_documento, c6.descripcion as tip_doc, p.numero_documento, p.razon_social, 
      CASE WHEN LENGTH(p.razon_social) >= 35 THEN CONCAT (SUBSTRING(p.razon_social, 1, 35), '...' ) ELSE p.razon_social END AS ra_so, 
      p.domicilio_fiscal, f.tipo_documento_07, f.numeracion_08, f.total_operaciones_gravadas_monto_18_2, f.sumatoria_igv_22_1, f.importe_total_venta_27, 
      f.fecha_emision_01, f.fecha_emision_01 as fecha2, f.tipo_moneda_28 as tmoneda
      from factura f 
      inner join persona p on f.idcliente=p.idpersona  
      inner join catalogo6 c6 on c6.codigo=p.tipo_documento  
      inner join empresa e on f.idempresa=e.idempresa 
      where p.tipo_persona='cliente' and f.estado='5' and e.idempresa='$idempresa' and f.tipo_moneda_28='$moneda'     
    ) as tabla order by fecha_emision_01 desc";
    return ejecutarConsulta($sql);
  }


  function buscarComprobanteServicioFactura($idempresa) {

    $sql = "SELECT idfactura, tipo_documento as tdcliente, numero_documento as ndcliente, razon_social as rzcliente,domicilio_fiscal as domcliente, 
    tipo_documento_07 as tipocomp, numeracion_08 as numerodoc, total_operaciones_gravadas_monto_18_2 as subtotal, sumatoria_igv_22_1 as igv, 
    importe_total_venta_27 as total, date_format(fecha_emision_01, '%d-%m-%Y') as fecha1, fecha2
    from (
      select f.idfactura, 
      p.tipo_documento, p.numero_documento, p.razon_social, p.domicilio_fiscal, f.tipo_documento_07, 
      f.numeracion_08, f.total_operaciones_gravadas_monto_18_2, f.sumatoria_igv_22_1, f.importe_total_venta_27, f.fecha_emision_01, 
      f.fecha_emision_01 as fecha2 
      from facturaservicio f 
      inner join persona p on f.idcliente=p.idpersona  
      inner join empresa e on f.idempresa=e.idempresa 
      where p.tipo_persona='cliente' and f.estado='5' and e.idempresa='$idempresa'
    ) as tabla order by fecha_emision_01 desc";
    return ejecutarConsulta($sql);
  }

  function buscarComprobanteId($idcomprobante) {

    $sql = "SELECT idfactura, tipo_documento, numero_documento, razon_social, domicilio_fiscal as domicilio, tipo_documento_07 as tipocomp, numeracion_08 as numerodoc, 
    cantidad_item_12 as cantidad, codigo, idarticulo, codigo_proveedor, nombre as descripcion, format(precio_venta,2) as precio_venta, stock, unidad_medida, 
    precio_unitario, valor_uni_item_14 as vui, afectacion_igv_item_16_1 as igvi, precio_venta_item_15_2 as pvi, valor_venta_item_21 as vvi, 
    total_operaciones_gravadas_monto_18_2 as subtotal, sumatoria_igv_22_1 as igv, importe_total_venta_27 as total, tmoneda, descarti
    from ( 
      select 
      f.idfactura, p.tipo_documento, p.numero_documento, p.razon_social, p.domicilio_fiscal, f.tipo_documento_07, f.numeracion_08, df.cantidad_item_12, a.codigo, a.idarticulo,
      a.codigo_proveedor, a.nombre,  a.precio_venta, a.stock, a.unidad_medida, (a.precio_venta * 1.18) as precio_unitario, df.valor_uni_item_14, df.afectacion_igv_item_16_1, 
      df.precio_venta_item_15_2, df.valor_venta_item_21, f.total_operaciones_gravadas_monto_18_2, f.sumatoria_igv_22_1, 
      f.importe_total_venta_27, f.tipo_moneda_28 as tmoneda, df.descdet as descarti
      from factura f 
      inner join detalle_fac_art df on f.idfactura=df.idfactura 
      inner join articulo a on df.idarticulo=a.idarticulo 
      inner join persona p on f.idcliente=p.idpersona 
      where p.tipo_persona='cliente'  and f.idfactura='$idcomprobante' and f.estado='5'
    ) as tabla";
    return ejecutarConsulta($sql);
  }

  function buscarComprobanteIdFacturaServicio($idcomprobante) {

    $sql = "SELECT  idfactura, tipo_documento, numero_documento, razon_social, domicilio_fiscal as domicilio, tipo_documento_07 as tipocomp, numeracion_08 as numerodoc, 
    cantidad_item_12 as cantidad, codigo, idarticulo, codigo_proveedor, nombre as descripcion, format(precio_venta,2) as precio_venta, stock, unidad_medida, precio_unitario, 
    valor_uni_item_14 as vui, afectacion_igv_item_16_1 as igvi, precio_venta_item_15_2 as pvi, valor_venta_item_21 as vvi, total_operaciones_gravadas_monto_18_2 as subtotal, 
    sumatoria_igv_22_1 as igv, importe_total_venta_27 as total 
    from ( 
      select 
      f.idfactura, p.tipo_documento, p.numero_documento, p.razon_social,p.domicilio_fiscal, f.tipo_documento_07, f.numeracion_08, df.cantidad_item_12, a.codigo, 
      a.id as idarticulo, a.codigo as codigo_proveedor, a.descripcion as nombre, a.valor as precio_venta, a.estado as stock, a.descripcion as unidad_medida, 
      (a.valor * 1.18) as precio_unitario, df.valor_uni_item_14, df.afectacion_igv_item_16_1, df.precio_venta_item_15_2, df.valor_venta_item_21, 
      f.total_operaciones_gravadas_monto_18_2, f.sumatoria_igv_22_1, f.importe_total_venta_27 
      from facturaservicio f 
      inner join detalle_fac_art_ser df on f.idfactura=df.idfactura 
      inner join servicios_inmuebles a on df.idarticulo=a.id 
      inner join persona p on f.idcliente=p.idpersona 
      where p.tipo_persona='cliente'  and f.idfactura='$idcomprobante' and f.estado='5'
    ) as tabla";
    return ejecutarConsulta($sql);
  }

  //Implementamos un método para anular la factura
  public function anularFactura($idfactura) {    

    $query = "SELECT idfactura, idarticulo  from detalle_fac_art where idfactura = '$idfactura'";
    $resultado = ejecutarConsultaArray( $query);

    $Idf = '';
    $Ida = '';
    $sw = true;
    $num_elementos = 0;

    foreach ($resultado as $key => $fila) {      
     
      $Idf = $fila["idfactura"];
      $Ida = $fila["idarticulo"];

      $sql_update_articulo = "UPDATE detalle_fac_art de inner join articulo a on de.idarticulo = a.idarticulo
      set a.saldo_finu = a.saldo_finu + de.cantidad_item_12, a.stock = a.stock + de.cantidad_item_12, a.ventast = a.ventast - de.cantidad_item_12        
      where de.idfactura='$Idf' and de.idarticulo='$Ida'";

      $sql_update_articulo_2 = "UPDATE detalle_fac_art de inner join articulo a  on de.idarticulo = a.idarticulo
      set a.valor_finu=(a.saldo_iniu + a.comprast - a.ventast) * a.costo_compra 
      where de.idfactura='$Idf' and de.idarticulo='$Ida'";

      $sqlbajafactura = "UPDATE factura set estado='0' where idfactura='$Idf'";

      ejecutarConsulta($sql_update_articulo) or $sw = false;
      ejecutarConsulta($sql_update_articulo_2) or $sw = false;
      ejecutarConsulta($sqlbajafactura) or $sw = false;
    } //Fin foreach

    return $sw;
  }

  //Implementamos un método para anular la factura
  public function anularFacturaxItem($idfactura, $idarticulo, $cantidad) {

    $Idf ='';
    $Ida ='';
    $sw = true;
    $num_elementos = 0;

    while ($num_elementos < count($idarticulo)) {

      $query = "SELECT idfactura, idarticulo from detalle_fac_art where idfactura ='$idfactura' and idarticulo='$idarticulo[$num_elementos]'";
      $resultado = ejecutarConsultaArray( $query);
      foreach ($resultado as $key => $fila) {       
        
        $Idf = $fila["idfactura"];
        $Ida = $fila["idarticulo"];

        $sql_update_articulo = "UPDATE detalle_fac_art de inner join articulo a on de.idarticulo = a.idarticulo
        set a.saldo_finu = a.saldo_finu + '$cantidad[$num_elementos]', a.stock = a.stock + '$cantidad[$num_elementos]', 
        a.ventast = a.ventast - '$cantidad[$num_elementos]' where de.idfactura='$Idf' and de.idarticulo='$Ida'";

        $sql_update_articulo_2 = "UPDATE detalle_fac_art de inner join articulo a on de.idarticulo = a.idarticulo
        set a.valor_finu=(a.saldo_iniu + '$cantidad[$num_elementos]') * a.costo_compra 
        where de.idfactura='$Idf' and de.idarticulo='$Ida'";
        
        ejecutarConsulta($sql_update_articulo) or $sw = false;
        ejecutarConsulta($sql_update_articulo_2) or $sw = false;
      }
      $num_elementos = $num_elementos + 1;
      //return $sw; 
    }
  }
}
