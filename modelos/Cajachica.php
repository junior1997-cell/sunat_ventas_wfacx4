<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Cajachica
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }
  //hacer consulta a columa ingreso

  public function TotalCompras()
  {
    $sql = "SELECT SUM(total) as total_compras FROM compra WHERE DATE(fecha) = CURRENT_DATE";
    return ejecutarConsulta($sql);
  }

  //mostrar todo el total de caja con todo y ventas
  public function TotalVentas()
  {

    $sql = "SELECT SUM(total_venta) as total_venta 
        FROM (
          SELECT SUM(importe_total_venta_27) as total_venta
          FROM factura 
          WHERE DATE(fecha_emision_01) = CURRENT_DATE AND estado IN ('5','1','6')
          UNION ALL
          SELECT SUM(importe_total_23) as total_venta
          FROM boleta 
          WHERE DATE(fecha_emision_01) = CURRENT_DATE AND estado IN ('5','1','6')
          UNION ALL
          SELECT SUM(monto_15_2) as total_venta
          FROM notapedido 
          WHERE DATE(fecha_emision_01) = CURRENT_DATE AND estado IN ('5','1','6')
        ) as tbl1";
    return ejecutarConsulta($sql);
  }

  //mostrar todo el total de caja con todo y ventas
  public function TotalCaja()
  {

    $sql = "SELECT SUM(total_caja) as total_caja 
        FROM (
          SELECT SUM(importe_total_venta_27) as total_caja
          FROM factura 
          WHERE DATE(fecha_emision_01) = CURRENT_DATE AND estado IN ('5','1','6')
          UNION ALL
          SELECT SUM(importe_total_23) as total_caja
          FROM boleta 
          WHERE DATE(fecha_emision_01) = CURRENT_DATE AND estado IN ('5','1','6')
          UNION ALL
          SELECT SUM(monto_15_2) as total_caja
          FROM notapedido 
          WHERE DATE(fecha_emision_01) = CURRENT_DATE AND estado IN ('5','1','6')
          UNION ALL
          SELECT SUM(ingreso - gasto) as total_caja
          FROM insumos
          WHERE DATE(fecharegistro) = CURRENT_DATE
        ) as tbl1";
    return ejecutarConsulta($sql);
  }


  //hacer consulta a columa ingreso

  public function verIngresos()
  {
    $sql = "SELECT SUM(ingreso) as total_ingreso FROM insumos WHERE DATE(fecharegistro) = CURRENT_DATE";
    return ejecutarConsulta($sql);
  }

  //hacer consulta a columna egreso

  public function verEgresos()
  {
    $sql = "SELECT SUM(gasto) as total_gasto FROM insumos WHERE DATE(fecharegistro) = CURRENT_DATE";
    return ejecutarConsulta($sql);
  }


  // INSERTAR saldo inicial por día actual
  public function insertarSaldoInicial($saldo_inicial)
  {
    $f_h_apertura = date("Y-m-d H:i:s"); 
    if ($this->existeSaldoInicialDiaActual()) {
      // Ya existe un saldo inicial para el día actual, no se puede insertar otro
      return false;
    }
    $sql = "INSERT INTO saldocaja (idsaldoini, saldo_inicial, fecha_creacion) 
                VALUES (null, '$saldo_inicial', '$f_h_apertura')";
    return ejecutarConsulta($sql);
  }

  // Verificar si ya existe un saldo inicial para el día actual
  public function existeSaldoInicialDiaActual()
  {
    $fecha_actual = date("d-m-Y h:i:s");
    $sql = "SELECT COUNT(*) as total FROM saldocaja 
                WHERE fecha_creacion = '$fecha_actual'";
    $resultado = ejecutarConsultaSimpleFila($sql);
    return $resultado['total'] > 0;
  }



  //ver slaod inciial

  public function verSaldoini()
  {
    $sql = "SELECT idsaldoini, saldo_inicial FROM saldocaja WHERE fecha_creacion = CURRENT_DATE()";
    return ejecutarConsulta($sql);
  }


  public function cerrarCaja($tipo_doc,$num_doc)
  {
    $sql_ini="SELECT fecha_cierre FROM cierrecaja ORDER BY `fecha_cierre` DESC LIMIT 1;";
    $ultmacierrcaja = ejecutarConsultaSimpleFila($sql_ini);
    $f_c = empty($ultmacierrcaja['fecha_cierre']) ? date('Y-m-d 00:00:00'):$ultmacierrcaja['fecha_cierre'];

    $sql_0 = "INSERT INTO insumos (tipodato, idcategoriai, fecharegistro, descripcion, ingreso, documnIDE, numDOCIDE, acredor)
    SELECT
     'ingreso', '1' ,CURDATE(), 'INGRESO POR FACTURAS BOLETAS Y NOTAS DE PEDIDO',
     (SELECT IFNULL(SUM(importe_total_venta_27), 0) FROM factura WHERE DATE(fecha_emision_01) >= '$f_c' AND estado IN ('5','1','6'))
    + (SELECT IFNULL(SUM(importe_total_23), 0) FROM boleta WHERE DATE(fecha_emision_01) >= '$f_c' AND estado IN ('5','1','6'))
    + (SELECT IFNULL(SUM(monto_15_2), 0) FROM notapedido WHERE DATE(fecha_emision_01) >= '$f_c' AND estado IN ('5','1','6') 
    ) AS monto,'$tipo_doc','$num_doc',''";

    ejecutarConsulta($sql_0);

    $sql = "INSERT INTO cierrecaja (fecha_cierre, total_caja)
     SELECT 
       CURRENT_TIMESTAMP(), 
       (SELECT IFNULL(SUM(saldo_inicial), 0)  FROM saldocaja where fecha_creacion>= '$f_c') 
       - (SELECT IFNULL(SUM(gasto), 0) FROM insumos where fecharegistro>= '$f_c') 
       + (SELECT IFNULL(SUM(ingreso), 0) FROM insumos where fecharegistro>= '$f_c'
       ) AS total";

    return ejecutarConsulta($sql);
  }


  public function listarCierre()
  {
    $sql_ini="SELECT 
    MAX(fecha_cierre) AS ultima_fecha, 
    (SELECT MAX(fecha_cierre) FROM cierrecaja WHERE fecha_cierre < (SELECT MAX(fecha_cierre) FROM cierrecaja)) AS penultima_fecha 
    FROM cierrecaja";

    $sql_cierrcaja = ejecutarConsultaSimpleFila($sql_ini);

    $ultima_fecha = empty($sql_cierrcaja['ultima_fecha']) ? date('Y-m-d 00:00:00'):$sql_cierrcaja['ultima_fecha'];
    $penultima_fecha = empty($sql_cierrcaja['penultima_fecha']) ? date('Y-m-d 00:00:00'):$sql_cierrcaja['penultima_fecha'];

    $sql = "SELECT cierrecaja.total_caja, SUM(insumos.ingreso) AS total_ingreso, SUM(insumos.gasto) AS total_gasto, saldocaja.saldo_inicial 
    FROM cierrecaja ,insumos, saldocaja 
    where cierrecaja.fecha_cierre >'$penultima_fecha' and cierrecaja.fecha_cierre <='$ultima_fecha'  and
    insumos.fecharegistro >'$penultima_fecha' and insumos.fecharegistro <='$ultima_fecha' and
    saldocaja.fecha_creacion >'$penultima_fecha' and saldocaja.fecha_creacion <='$ultima_fecha'
    GROUP BY cierrecaja.total_caja, saldocaja.saldo_inicial;";
    return ejecutarConsulta($sql);
  }

  // LISTADO DE LAS TABLAS RESUMEN 
  public function tblInsumos($tipodato){

    $sql_ini="SELECT MAX(fecha_creacion) AS ultima_fecha FROM saldocaja";

    $sql_cierrcaja = ejecutarConsultaSimpleFila($sql_ini);

    $ultima_fecha = empty($sql_cierrcaja['ultima_fecha']) ? date('Y-m-d 00:00:00'):$sql_cierrcaja['ultima_fecha'];

    $sql= "SELECT  i.idinsumo, i.fecharegistro, i.descripcion, i.valor, i.igv, i.gasto, i.ingreso, i.documnIDE, i.numDOCIDE, i.acredor, ci.descripcionc 
    FROM insumos as i
    inner join categoriainsumos as ci on i.idcategoriai = ci.idcategoriai 
    WHERE tipodato='$tipodato' and fecharegistro>='$ultima_fecha';";
    return ejecutarConsulta($sql);
    
  }
}
