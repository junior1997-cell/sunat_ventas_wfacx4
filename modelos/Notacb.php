<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Notacb
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  function buscarComprobante($idempresa, $moneda)  {

    $sql = "SELECT  b.idboleta, p.idpersona, p.tipo_documento, c6.descripcion as tip_doc, p.numero_documento, 
    CASE  WHEN p.tipo_documento = '6' THEN p.razon_social  ELSE CONCAT(IFNULL(p.nombres,''), ' ' , IFNULL(p.apellidos,'')) END AS razon_social,
    CASE p.tipo_documento WHEN '6' THEN 
      CASE WHEN LENGTH(p.razon_social) >= 35 THEN CONCAT( SUBSTRING(p.razon_social, 1, 35) , '...' ) ELSE p.razon_social END 
    ELSE 
      CASE WHEN LENGTH( CONCAT(IFNULL(p.nombres,''), ' ' , IFNULL(p.apellidos,'')) ) >= 35 THEN CONCAT( SUBSTRING(CONCAT(IFNULL(p.nombres,''), ' ' , IFNULL(p.apellidos,'')), 1, 35) , '...' ) ELSE CONCAT(IFNULL(p.nombres,''), ' ' , IFNULL(p.apellidos,'')) END  
    END AS ra_so, 
    p.domicilio_fiscal as domicilio,
    b.tipo_documento_06 as tipocomp, 
    b.numeracion_07 as numerodoc, b.monto_15_2 as subtotal, b.sumatoria_igv_18_1 as igv, b.importe_total_23 as total, b.tipo_moneda_24 as tmoneda, 
    date_format(b.fecha_emision_01, '%d-%m-%Y') as fecha1, date_format(b.fecha_emision_01, '%Y/%m/%d %h:%i %p') as fecha2 
    from boleta b 
    inner join persona p on b.idcliente= p.idpersona 
    inner join catalogo6 c6 on c6.codigo=p.tipo_documento
    inner join empresa e on b.idempresa=e.idempresa 
    where p.tipo_persona='cliente' and b.estado='5' and e.idempresa='$idempresa' and b.tipo_moneda_24='$moneda' order by b.fecha_emision_01 desc";
    return ejecutarConsulta($sql);
  }

  function buscarComprobanteBoletaServicio($idempresa) {

    $sql = "SELECT  b.idboleta, p.tipo_documento, p.numero_documento, p.razon_social, p.domicilio_fiscal as domicilio, b.tipo_documento_06 as tipocomp, 
    b.numeracion_07 as numerodoc, b.monto_15_2 as subtotal, b.sumatoria_igv_18_1 as igv, b.importe_total_23 as total, date_format(b.fecha_emision_01, '%d-%m-%Y') as fecha1, 
    date_format(b.fecha_emision_01, '%Y/%m/%d %h:%i %p') as fecha2 
    from boletaservicio b 
    inner join persona p on b.idcliente= p.idpersona 
    inner join empresa e on b.idempresa=e.idempresa 
    where p.tipo_persona='cliente' and b.estado='5' and e.idempresa='$idempresa' order by b.fecha_emision_01 desc";
    return ejecutarConsulta($sql);
  }

  function buscarComprobanteId($idcomprobante) {

    $sql = "SELECT idarticulo, precio_unitario, unidad_medida, stock, precio_venta, codigo_proveedor, idboleta, tipo_documento, numero_documento, razon_social, 
    domicilio, tipocomp, numerodoc, cantidad, codigo,  descripcion, vui, igvi, pvi, vvi, subtotal, igv, total 
    from (
      select a.idarticulo, (a.precio_venta * 1.18) as precio_unitario, a.unidad_medida, a.stock, a.precio_venta, a.codigo_proveedor, b.idboleta, p.tipo_documento,  
      p.numero_documento,  p.razon_social,  p.domicilio_fiscal as domicilio,  b.tipo_documento_06 as tipocomp,  b.numeracion_07 as numerodoc,   
      format(db.cantidad_item_12,2) as cantidad, a.codigo, a.nombre as descripcion, db.valor_uni_item_31 as vui, db.afectacion_igv_item_monto_27_1 as igvi, 
      db.precio_uni_item_14_2 as pvi, db.valor_venta_item_32 as vvi, b.monto_15_2 as subtotal, b.sumatoria_igv_18_1 as igv, b.importe_total_23 as total  
      from boleta b 
      inner join detalle_boleta_producto db on b.idboleta=db.idboleta 
      inner join articulo a on db.idarticulo=a.idarticulo 
      inner join persona p on b.idcliente= p.idpersona 
      where p.tipo_persona='cliente' and b.idboleta='$idcomprobante' and b.estado='5'
    ) as tabla";
    return ejecutarConsulta($sql);
  }


  function buscarComprobanteIdBoletaServicio($idcomprobante) {

    $sql = "SELECT idboleta, tipo_documento, numero_documento, razon_social, domicilio, tipocomp, numerodoc, cantidad, codigo, descripcion, vui, igvi, pvi, vvi, 
    subtotal, igv, total 
    from (
      select b.idboleta, p.tipo_documento, p.numero_documento, p.razon_social, p.domicilio_fiscal as domicilio, 
      b.tipo_documento_06 as tipocomp, b.numeracion_07 as numerodoc, format(db.cantidad_item_12,2) as cantidad, a.codigo, 
      a.descripcion, db.valor_uni_item_31 as vui, db.afectacion_igv_item_monto_27_1 as igvi, db.precio_uni_item_14_2 as pvi, 
      db.valor_venta_item_32 as vvi, b.monto_15_2 as subtotal, b.sumatoria_igv_18_1 as igv, b.importe_total_23 as total  
      from boletaservicio b 
      inner join detalle_boleta_producto_ser db on b.idboleta=db.idboleta 
      inner join servicios_inmuebles a on db.idarticulo=a.id 
      inner join persona p on b.idcliente= p.idpersona 
      where p.tipo_persona='cliente' and b.idboleta='$idcomprobante' and b.estado='5'
    )
    as tabla";
    return ejecutarConsulta($sql);
  }

  public function anularBoleta($idboleta)  {
    
    $query = "SELECT idboleta, idarticulo  from detalle_boleta_producto where idboleta='$idboleta'";
    $resultado = ejecutarConsultaArray( $query);

    $Idb = array();
    $Ida = array();
    $sw = true;

    foreach ($resultado as $key => $fila) {   
      
      $Idb = $fila["idboleta"];
      $Ida = $fila["idarticulo"];

      $sql_update_articulo = "UPDATE detalle_boleta_producto de inner join articulo a  on de.idarticulo=a.idarticulo 
      set a.saldo_finu=a.saldo_finu + de.cantidad_item_12, a.stock=a.stock + de.cantidad_item_12, a.ventast=a.ventast - de.cantidad_item_12 
      where de.idboleta='$Idb' and de.idarticulo='$Ida'";
      
      $sql_update_articulo_2 = "UPDATE detalle_boleta_producto de inner join articulo a  on de.idarticulo=a.idarticulo 
      set  a.valor_finu=(a.saldo_iniu + a.comprast - a.ventast) * a.costo_compra  
      where de.idboleta='$Idb' and de.idarticulo='$Ida'";

      $sqlbajaboleta = "UPDATE boleta set estado='0' where idboleta='$Idb'";      

      ejecutarConsulta($sql_update_articulo) or $sw = false;
      ejecutarConsulta($sql_update_articulo_2) or $sw = false;
      ejecutarConsulta($sqlbajaboleta) or $sw = false;
    }
    return $sw;
  }

  public function anularBoletaxItem($idboleta, $idarticulo, $cantidad)  {    

    $Idb = array();
    $Ida = array();
    $sw = true;
    $num_elementos = 0;

    while ($num_elementos < count($idarticulo)) {
      $query = "SELECT idboleta, idarticulo from detalle_boleta_producto where idboleta='$idboleta' and idarticulo='$idarticulo[$num_elementos]'";
      $resultado = ejecutarConsultaArray( $query);

      foreach ($resultado as $key => $fila) {       
     
        $Idb = $fila["idboleta"];
        $Ida = $fila["idarticulo"];

        $sql_update_articulo = "UPDATE detalle_boleta_producto de inner join articulo a  on de.idarticulo=a.idarticulo 
        set a.saldo_finu=a.saldo_finu + '$cantidad[$num_elementos]',   a.stock=a.stock + '$cantidad[$num_elementos]', a.ventast=a.ventast - '$cantidad[$num_elementos]'
        where de.idboleta='$Idb' and de.idarticulo='$Ida'";

        $sql_update_articulo_2 = "UPDATE detalle_boleta_producto de inner join articulo a  on de.idarticulo=a.idarticulo 
        set  a.valor_finu=(a.saldo_iniu + a.comprast - a.ventast) * a.costo_compra
        where de.idboleta='$Idb' and de.idarticulo='$Ida'";
        
        ejecutarConsulta($sql_update_articulo) or $sw = false;
        ejecutarConsulta($sql_update_articulo_2) or $sw = false;
      }
      $num_elementos = $num_elementos + 1;
    }
  }
}
