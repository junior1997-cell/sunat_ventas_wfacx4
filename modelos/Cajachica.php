<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Cajachica
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  //mostrar total ventas facturas bolestas y notas de pedido
  public function TotalVentas()
  {
    $sql_ini = "SELECT MAX(fecha) AS ultima_fecha FROM caja where estado='1';";

    $sql_cierrcaja = ejecutarConsultaSimpleFila($sql_ini);

    $ultima_fecha = $sql_cierrcaja['ultima_fecha'];

    if (empty($ultima_fecha)){ return ['total_venta'=>0]; }

    $sql = "SELECT SUM(total_venta) as total_venta 
        FROM (
          SELECT SUM(importe_total_venta_27) as total_venta
          FROM factura 
          WHERE fecha_emision_01 >= '$ultima_fecha' AND estado IN ('5','1','6')
          UNION ALL
          SELECT SUM(importe_total_23) as total_venta
          FROM boleta 
          WHERE fecha_emision_01 >= '$ultima_fecha' AND estado IN ('5','1','6')
          UNION ALL
          SELECT SUM(monto_15_2) as total_venta
          FROM notapedido 
          WHERE fecha_emision_01 >= '$ultima_fecha' AND estado IN ('5','1','6')
        ) as tbl1";

    return ejecutarConsultaSimpleFila($sql);

  }

  //mostrar total en caja
  public function TotalCaja()
  {

    $sql_ini = "SELECT MAX(fecha) AS ultima_fecha FROM caja where estado='1';";

    $sql_cierrcaja = ejecutarConsultaSimpleFila($sql_ini);
    $ultima_fecha = $sql_cierrcaja['ultima_fecha'];

    if (empty($ultima_fecha)){
      return ['total_caja'=>0];
    }
   
    $sql = "SELECT SUM(total_caja) as total_caja 
        FROM (
          SELECT SUM(importe_total_venta_27) as total_caja
          FROM factura 
          WHERE fecha_emision_01 >= '$ultima_fecha' AND estado IN ('5','1','6')
          UNION ALL
          SELECT SUM(importe_total_23) as total_caja
          FROM boleta 
          WHERE fecha_emision_01>= '$ultima_fecha' AND estado IN ('5','1','6')
          UNION ALL
          SELECT SUM(monto_15_2) as total_caja
          FROM notapedido 
          WHERE fecha_emision_01 >= '$ultima_fecha' AND estado IN ('5','1','6')
          UNION ALL
          SELECT SUM(ingreso - gasto) as total_caja
          FROM insumos
          WHERE fecharegistro >= '$ultima_fecha'
        ) as tbl1";
    return ejecutarConsultaSimpleFila($sql);
  }

  //total columa ingreso
  public function verIngresos()
  {
    $sql_ini = "SELECT MAX(fecha) AS ultima_fecha FROM caja where estado='1';";

    $sql_cierrcaja = ejecutarConsultaSimpleFila($sql_ini);

    $ultima_fecha = $sql_cierrcaja['ultima_fecha'];

    if (empty($ultima_fecha)){ return ['total_ingreso'=>0]; }

    $sql = "SELECT SUM(ingreso) as total_ingreso FROM insumos WHERE fecharegistro >= '$ultima_fecha'";
    return ejecutarConsultaSimpleFila($sql);

  }

  //total a columna egreso

  public function verEgresos()
  {
    $sql_ini = "SELECT MAX(fecha) AS ultima_fecha FROM caja where estado='1';";

    $sql_cierrcaja = ejecutarConsultaSimpleFila($sql_ini);

    if (empty($sql_cierrcaja['ultima_fecha'])){
            
      return  ['total_gasto'=>0];
    }

    $ultima_fecha =  $sql_cierrcaja['ultima_fecha'];

    $sql = "SELECT SUM(gasto) as total_gasto FROM insumos WHERE fecharegistro >= '$ultima_fecha'";

    return ejecutarConsultaSimpleFila($sql);
  }

  // INSERTAR saldo inicial por día actual
  public function insertarSaldoInicial($saldo_inicial)
  {
    $f_h_apertura = date("Y-m-d H:i:s");
    $sql = "INSERT INTO caja(fecha, montoi) VALUES ('$f_h_apertura','$saldo_inicial')";
    return ejecutarConsulta($sql);
  }

  //ver slaod inciial
  public function verSaldoini()
  {
    $sql = "SELECT montoi FROM caja where estado='1';";

    $Saldoini = ejecutarConsultaSimpleFila($sql);

    if (empty($Saldoini)){return  ['montoi'=>0];}

    return $Saldoini;
  }

  public function cerrarCaja($tipo_doc, $num_doc)
  {

    $sql_ini = "SELECT idcaja,fecha FROM caja ORDER BY fecha DESC LIMIT 1;";
    $ultmacierrcaja = ejecutarConsultaSimpleFila($sql_ini);
    $f_c = $ultmacierrcaja['fecha'];
    $id_caja = empty($ultmacierrcaja['idcaja']) ? '1' : $ultmacierrcaja['idcaja'];

    $sql_0 = "INSERT INTO insumos (tipodato, idcategoriai, fecharegistro, descripcion, ingreso, documnIDE, numDOCIDE, acredor)
    SELECT
     'ingreso', '1' ,CURRENT_TIMESTAMP(), 'INGRESO POR FACTURAS BOLETAS Y NOTAS DE PEDIDO',
     (SELECT IFNULL(SUM(importe_total_venta_27), 0) FROM factura WHERE fecha_emision_01 >= '$f_c' AND estado IN ('5','1','6'))
    + (SELECT IFNULL(SUM(importe_total_23), 0) FROM boleta WHERE fecha_emision_01 >= '$f_c' AND estado IN ('5','1','6'))
    + (SELECT IFNULL(SUM(monto_15_2), 0) FROM notapedido WHERE fecha_emision_01 >= '$f_c' AND estado IN ('5','1','6') 
    ) AS monto,'$tipo_doc','$num_doc',''";

    ejecutarConsulta($sql_0);

    $sql = "SELECT 
       CURRENT_TIMESTAMP(), 
       (SELECT IFNULL(SUM(saldo_inicial), 0)  FROM saldocaja where fecha_creacion>= '$f_c') 
       - (SELECT IFNULL(SUM(gasto), 0) FROM insumos where fecharegistro>= '$f_c') 
       + (SELECT IFNULL(SUM(ingreso), 0) FROM insumos where fecharegistro>= '$f_c'
       ) AS total";
    $sumaT = ejecutarConsultaSimpleFila($sql);
    $total_cierre_caja = empty($sumaT['total']) ? '0' : $sumaT['total'];

    $sql_1 = "UPDATE caja SET montof='$total_cierre_caja',estado='0' WHERE idcaja='$id_caja'";

    return ejecutarConsulta($sql_1);
  }

  // LISTADO DE LAS TABLAS RESUMEN 
  public function tblInsumos($tipodato)
  {

    $sql_ini = "SELECT MAX(fecha) AS ultima_fecha FROM caja where estado='1';";

    $sql_cierrcaja = ejecutarConsultaSimpleFila($sql_ini);

    if (empty($sql_cierrcaja['ultima_fecha'])){
      return [];
    }
    $ultima_fecha = $sql_cierrcaja['ultima_fecha'];

    $sql = "SELECT  i.idinsumo, i.fecharegistro, i.descripcion, i.valor, i.igv, 
    CASE WHEN tipodato = 'gasto' THEN i.gasto ELSE i.ingreso END AS total, i.documnIDE, i.numDOCIDE, i.acredor, ci.descripcionc 
    FROM insumos as i
    inner join categoriainsumos as ci on i.idcategoriai = ci.idcategoriai 
    WHERE tipodato='$tipodato' and fecharegistro>='$ultima_fecha';";
    return ejecutarConsultaArray($sql);

  }

  public function comprobantes()
  {
    $sql_ini = "SELECT MAX(fecha) AS ultima_fecha FROM caja where estado='1';";

    $sql_cierrcaja = ejecutarConsultaSimpleFila($sql_ini);

    if (empty($sql_cierrcaja['ultima_fecha'])){
      return [];
    }
    $ultima_fecha = $sql_cierrcaja['ultima_fecha'];


    $sql = " SELECT id_doc,fecha_emision_01,nun_doc,idcliente,rucCliente,RazonSocial,importe_total,descripcion_ley, tipoDoc

    FROM (    
      SELECT idfactura as id_doc,fecha_emision_01,numeracion_08 as nun_doc,idcliente,rucCliente,RazonSocial,importe_total_venta_27 as importe_total,descripcion_leyenda_31_2 as descripcion_ley, 'FACTURA' as tipoDoc
      FROM factura 
      WHERE fecha_emision_01 >= '$ultima_fecha' AND estado IN ('5','1','6')
      UNION ALL
      SELECT idboleta as id_doc, fecha_emision_01, numeracion_07 as nun_doc, idcliente, rucCliente, RazonSocial, importe_total_23 as importe_total, descripcion_leyenda_26_2 as descripcion_ley, 'BOLETA' as tipoDoc
      FROM BOLETA
      WHERE fecha_emision_01 >= '$ultima_fecha' AND estado IN ('5','1','6')
      UNION ALL
      SELECT idboleta as id_doc, fecha_emision_01, numeracion_07 as nun_doc, idcliente, rucCliente, RazonSocial, importe_total_23 as importe_total, descripcion_leyenda_26_2 as descripcion_ley, 'NOTA PEDIDO' as tipoDoc
      FROM notapedido
      WHERE fecha_emision_01 >= '$ultima_fecha' AND estado IN ('5','1','6') 
    ) as tbl1;";

    return ejecutarConsultaArray($sql);

  }

}
