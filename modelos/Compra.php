
<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Compra
{  
  public $id_usr_sesion; public $id_empresa_sesion;
  //Implementamos nuestro constructor
  public function __construct( $id_usr_sesion = 0, $id_empresa_sesion = 0 )
  {
    $this->id_usr_sesion =  isset($_SESSION['idusuario']) ? $_SESSION["idusuario"] : 0;
		$this->id_empresa_sesion = isset($_SESSION['idempresa']) ? $_SESSION["idempresa"] : 0;
  }

  //Implementamos un método para insertar registros
  public function insertar($idusuario, $idproveedor, $fecha_emision, $tipo_comprobante, $serie_comprobante, $num_comprobante, $guia, $subtotal_compra, $total_igv, $total_compra, $idarticulo, $valor_unitario,  $cantidad, $subtotalBD,  $codigo, $unidad_medida, $tcambio, $hora, $moneda, $idempresa)  {

    $sql = "INSERT into compra ( idusuario,  idproveedor, fecha,  tipo_documento,  serie,  numero, 
    guia,  subtotal,  igv,  total,  subtotal_$,  igv_$,   total_$,   tcambio,  moneda,   idempresa  ) 
    values ( '$idusuario', '$idproveedor', '$fecha_emision $hora', '$tipo_comprobante', '$serie_comprobante', '$num_comprobante', 
    '$guia', '$subtotal_compra', '$total_igv', '$total_compra', '0',  '0', '0',  '$tcambio', '$moneda',  '$idempresa' )";    
    $idcompranew = ejecutarConsulta_retornarID2($sql);

    $ii = 0;
    $sw = true;
    while ($ii < count($idarticulo)) {
      if ($moneda == "USD") {  $valor_unitario[$ii] = $valor_unitario[$ii] * $tcambio;  }

      $sql_detalle = "INSERT into detalle_compra_producto ( idcompra, idarticulo, valor_unitario, cantidad, subtotal, 
      valor_unitario_$, subtotal_$) 
      values ('$idcompranew', '$idarticulo[$ii]', '$valor_unitario[$ii]', '$cantidad[$ii]', 
      valor_unitario * '$cantidad[$ii]', '0',  '0')";     

      //Guardar en Kardex
      $sql_kardex = "INSERT into kardex ( idcomprobante, idarticulo, transaccion, codigo, fecha, tipo_documento, numero_doc, 
      cantidad, costo_1, unidad_medida, saldo_final, costo_2, valor_final, idempresa, tcambio, moneda )
      values ('$idcompranew', '$idarticulo[$ii]', 'COMPRA', '$codigo[$ii]', '$fecha_emision', 
      '$tipo_comprobante', '$serie_comprobante-$num_comprobante', '$cantidad[$ii]', '$valor_unitario[$ii]', 
      '$unidad_medida[$ii]',
      (select saldo_finu +  '$cantidad[$ii]' from articulo where idarticulo='$idarticulo[$ii]' ),
      (select saldo_finu * precio_final_kardex + ( '$valor_unitario[$ii]' * '$cantidad[$ii]') from articulo where idarticulo='$idarticulo[$ii]')/(select  saldo_finu + '$cantidad[$ii]' from articulo where idarticulo='$idarticulo[$ii]'),
      saldo_final * costo_2, '$idempresa', '$tcambio', '$moneda' )";

      //FORMULA PARA ACTUALIZAR COSTO DE COMPRA Y VALOR FINAL ========================
      // (COSTO DE COMPRA * CANTIDAD)+VALOR FINAL KARDEX / (SALDO FINAL + CANTIDAD)

      $sql_update_articulo = "UPDATE articulo set 
      valor_fin_kardex=(select valor_final from kardex where idarticulo='$idarticulo[$ii]' and transaccion='COMPRA' order by idkardex desc limit 1), 
      precio_final_kardex=(select costo_2 from kardex where idarticulo='$idarticulo[$ii]' order by idkardex desc limit 1),
      saldo_finu=saldo_finu + '$cantidad[$ii]', 
      comprast=comprast + '$cantidad[$ii]', 
      valor_finu=((saldo_iniu + comprast) - ventast) * precio_final_kardex, 
      stock=stock + $cantidad[$ii]
      where idarticulo='$idarticulo[$ii]'";      

      ejecutarConsulta2($sql_detalle) or $sw = false;
      ejecutarConsulta2($sql_kardex) or $sw = false;
      ejecutarConsulta2($sql_update_articulo) or $sw = false;
      $ii = $ii + 1;
    }

    return $idcompranew;

    //  } else { //INTERMEDIO IF TIPO DE MONEDA CASO DOLARES

    //       $sql="insert into compra (
    //           idusuario, 
    //           idproveedor,
    //           fecha, 
    //           tipo_documento, 
    //           serie, 
    //           numero, 
    //           guia, 
    //           subtotal, 
    //           igv, 
    //           total, 
    //           subtotal_$, 
    //           igv_$, 
    //           total_$, 
    //           tcambio,
    //           moneda,
    //           idempresa
    //           ) 
    //         values 
    //         ('$idusuario', 
    //         '$idproveedor', 
    //         '$fecha_emision $hora', 
    //         '$tipo_comprobante', 
    //         '$serie_comprobante', 
    //         '$num_comprobante', 
    //         '$guia', 
    //         '$subtotal_compra' * $tcambio, 
    //         '$total_igv' * $tcambio, 
    //         '$total_compra' * $tcambio, 
    //         '$subtotal_compra', 
    //         '$total_igv',
    //         '$total_compra', 
    //         '$tcambio',
    //         '$moneda',
    //         '$idempresa'
    //           )";
    //          $idcompranew=ejecutarConsulta_retornarID($sql);

    //         $num_elementos=0;
    //         $sw=true;
    //         while ($num_elementos < count($idarticulo))
    //         {
    //             $sql_detalle = "insert into 
    //             detalle_compra_producto 
    //             (
    //             idcompra, 
    //             idarticulo, 
    //             valor_unitario, 
    //             cantidad, 
    //             subtotal, 
    //             valor_unitario_$, 
    //             subtotal_$) 
    //             values
    //              (
    //              '$idcompranew', 
    //              '$idarticulo[$num_elementos]',
    //              '$valor_unitario[$num_elementos]' * $tcambio, 
    //              '$cantidad[$num_elementos]', 
    //              valor_unitario * '$cantidad[$num_elementos]',
    //              '$valor_unitario[$num_elementos]',
    //              valor_unitario_$ * '$cantidad[$num_elementos]')";
    //              //Guardar en Kardex
    //             $sql_kardex="insert into kardex 
    //             (idcomprobante, idarticulo, transaccion, codigo, fecha, tipo_documento, numero_doc, cantidad, costo_1, unidad_medida, saldo_final, costo_2, valor_final, idempresa)

    //              values 
    //              ('$idcompranew',
    //              '$idarticulo[$num_elementos]',
    //              'COMPRA', 
    //              '$codigo[$num_elementos]', 
    //              '$fecha_emision', 
    //              '$tipo_comprobante', 
    //              '$serie_comprobante-$num_comprobante', 
    //              '$cantidad[$num_elementos]', 
    //              '$valor_unitario[$num_elementos]' * $tcambio, 
    //              '$unidad_medida[$num_elementos]',
    //              '0',
    //              '0',
    //              '0', '$idempresa' 
    //            )";

    // //FORMULA PARA ACTUALIZAR COSTO DE COMPRA Y VALOR FINAL ========================
    // // (COSTO DE COMPRA * CANTIDAD)+VALOR FINAL KARDEX / (SALDO FINAL + CANTIDAD)

    //             $sql_update_articulo="update 
    //             articulo 
    //             set 
    //             saldo_finu=saldo_finu + '$cantidad[$num_elementos]', 
    //             comprast=comprast + '$cantidad[$num_elementos]', 
    //             valor_finu=((saldo_iniu + comprast)-ventast)*costo_compra, 
    //             stock=saldo_finu ,
    //                 valor_fin_kardex=(select valor_final 
    //                   from 
    //                   kardex 
    //                   where 
    //                   idarticulo='$idarticulo[$num_elementos]' and transaccion='COMPRA' order by idkardex desc limit 1), 

    //                 precio_final_kardex=(select costo_2 from kardex where idarticulo='$idarticulo[$num_elementos]' order by idkardex desc limit 1)

    //                 where idarticulo='$idarticulo[$num_elementos]'";


    //             ejecutarConsulta($sql_detalle) or $sw = false;
    //             ejecutarConsulta($sql_kardex) or $sw = false;
    //             ejecutarConsulta($sql_update_articulo) or $sw = false;
    //             $num_elementos=$num_elementos + 1;
    //         }

    //         return $idcompranew;

    //     }//FIN DE IF

  } //FIN DE FUNCION


  public function insertarsubarticulo( $idusuario, $idproveedor, $fecha_emision, $tipo_comprobante, $serie_comprobante, $num_comprobante, $guia, $subtotal_compra, $total_igv, 
  $total_compra, $idarticulo, $valor_unitario, $cantidad, $subtotalBD, $codigo, $unidad_medida, $tcambio, $hora, $moneda, $idempresa, $codigobarra, $idarticulonarti,
  $totalcantidad, $totalcostounitario, $vunitario, $factorc ) {

    $sql = "INSERT into compra ( idusuario, idproveedor, fecha, tipo_documento, serie, numero, guia, subtotal, igv, total, subtotal_$, 
    igv_$, total_$, tcambio,moneda, idempresa ) 
    values ( '$idusuario', '$idproveedor', '$fecha_emision $hora', '$tipo_comprobante', '$serie_comprobante', '$num_comprobante', '$guia', '$subtotal_compra', 
    '$total_igv', '$total_compra', '0', '0', '0',  '$tcambio', '$moneda', '$idempresa' )";
    $idcompranew = ejecutarConsulta_retornarID($sql);

    $sw = true;

    $sql_detalle = "INSERT into detalle_compra_producto ( idcompra, idarticulo, valor_unitario, cantidad, subtotal, valor_unitario_$, subtotal_$) 
    values ( '$idcompranew',  '$idarticulonarti', '$vunitario',  '$totalcantidad' / '$factorc', valor_unitario * '$totalcantidad', '0', '0')";
    ejecutarConsulta($sql_detalle) or $sw = false;

    $ii = 0;
    while ($ii < count($idarticulo)) {
      if ($moneda == "USD") { $valor_unitario[$ii] = $valor_unitario[$ii] * $tcambio; }

      $sqlsubarticulo = "INSERT into subarticulo ( idarticulo, codigobarra, valorunitario, preciounitario, stock, umventa, estado) 
      values ( '$idarticulo[$ii]', '$codigo[$ii]', '$valor_unitario[$ii]', ('$valor_unitario[$ii]' * 0.18) + '$valor_unitario[$ii]', 
      '$cantidad[$ii]', '$unidad_medida[$ii]', '1' )";

      //Guardar en Kardex
      $sql_kardex = "INSERT into kardex ( idcomprobante, idarticulo, transaccion, codigo, fecha, tipo_documento, numero_doc,  cantidad,  costo_1,  unidad_medida, 
      saldo_final,  costo_2,  valor_final, idempresa, tcambio, moneda )
      values ('$idcompranew', '$idarticulo[$ii]', 'COMPRA', '$codigo[$ii]', '$fecha_emision', '$tipo_comprobante', '$serie_comprobante-$num_comprobante', '$cantidad[$ii]' / '$factorc', 
      '$valor_unitario[$ii]', '$unidad_medida[$ii]', '0', '0' , '0', '$idempresa' , '$tcambio', '$moneda' )";

      //FORMULA PARA ACTUALIZAR COSTO DE COMPRA Y VALOR FINAL ========================
      // (COSTO DE COMPRA * CANTIDAD)+VALOR FINAL KARDEX / (SALDO FINAL + CANTIDAD)

      $sql_update_articulo = "UPDATE articulo set 
      valor_fin_kardex=(select valor_final from kardex where idarticulo='$idarticulo[$ii]' and transaccion='COMPRA' order by idkardex desc limit 1),
      precio_final_kardex=(select costo_2 from kardex where idarticulo='$idarticulo[$ii]' order by idkardex desc limit 1),
      saldo_finu=saldo_finu + ('$cantidad[$ii]' / '$factorc' ), comprast=comprast + ('$cantidad[$ii]'  / '$factorc'), 
      valor_finu=((saldo_iniu + comprast) - ventast) * precio_final_kardex, stock=saldo_finu where idarticulo='$idarticulo[$ii]';";

      ejecutarConsulta($sqlsubarticulo) or $sw = false;
      ejecutarConsulta($sql_kardex) or $sw = false;
      ejecutarConsulta($sql_update_articulo) or $sw = false;
      $ii = $ii + 1;
    }

    return $idcompranew;
  } //FIN DE FUNCION

  //Implementamos un método para anular categorías
  public function anular($idcompra) {
    $sql = "UPDATE compra set estado='0' WHERE idcompra='$idcompra'";
    return ejecutarConsulta($sql);
  }


  //Implementar un método para mostrar los datos de un registro a modificar
  public function mostrar($idcompra)  {
    $sql = "SELECT c.idcompra,DATE(c.fecha) as fecha,c.idproveedor,p.razon_social as proveedor,u.idusuario,u.nombre as usuario,c.tipo_documento,c.serie,c.numero,c.total,c.igv,c.estado 
    FROM compra c inner join persona p ON c.idproveedor=p.idpersona inner join usuario u ON c.idusuario=u.idusuario WHERE c.idcompra='$idcompra'";
    return ejecutarConsultaSimpleFila($sql);
  }

  //Implementar un método para mostrar los datos de un registro a modificar
  public function eliminarcompra($idcompra)  {
    $sql = "SELECT c.idcompra,DATE(c.fecha) as fecha,c.idproveedor,p.razon_social as proveedor,u.idusuario,u.nombre as usuario,c.tipo_documento,c.serie,c.numero,c.total,c.igv,c.estado 
    FROM compra c inner join persona p ON c.idproveedor=p.idpersona inner join usuario u ON c.idusuario=u.idusuario WHERE c.idcompra='$idcompra'";
    return ejecutarConsultaSimpleFila($sql);
  }

  public function listarDetalle($idcompra) {
    $sql = "SELECT dc.idcompra, dc.idarticulo, a.nombre, dc.cantidad, dc.valor_unitario, dc.valor_venta, dc.subtotal 
    FROM detalle_compra_producto dc inner join articulo a on dc.idarticulo=a.idarticulo where dc.idcompra='$idcompra'";
    return ejecutarConsulta($sql);
  }

  //Implementar un método para listar los registros
  public function listar($idempresa)  {
    $sql = "SELECT c.idcompra, date_format(c.fecha,'%d-%m-%Y') as fecha, c.idproveedor, p.razon_social as proveedor, u.idusuario, u.nombre as usuario, ct1.descripcion, 
    c.serie, c.numero, format(c.total,2) as total, c.igv, c.estado 
    from compra c 
    inner join persona p on c.idproveedor=p.idpersona 
    inner join usuario u on c.idusuario=u.idusuario 
    inner join catalogo1 ct1 on c.tipo_documento=ct1.codigo 
    inner join  empresa e on c.idempresa=e.idempresa 
    where e.idempresa='$idempresa'  order by c.idcompra desc ";
    return ejecutarConsulta2($sql);
  }

  public function regcompra($año, $mes, $moneda, $idempresa) {

    if ($moneda == 'usd') {
      $sql = "SELECT  date_format(c.fecha, '%d') as fecha, c.tipo_documento, c.serie, c.numero, p.numero_documento, p.razon_social, c.subtotal_$ as subtotal, c.igv_$ as igv, c.total_$ as total
      from compra c 
      inner join persona p on c.idproveedor=p.idpersona 
      inner join empresa e on c.idempresa=e.idempresa
      where year(c.fecha)='$año' and month(c.fecha)='$mes' and moneda='$moneda' and e.idempresa='$idempresa' and c.estado='1' order by c.fecha asc";
    } else {
      $sql = "SELECT date_format(c.fecha, '%d') as fecha, c.tipo_documento, c.serie, c.numero, p.numero_documento, p.razon_social, c.subtotal, c.igv, c.total
      from compra c 
      inner join persona p on c.idproveedor=p.idpersona 
      inner join empresa e on c.idempresa=e.idempresa
      where year(c.fecha)='$año' and month(c.fecha)='$mes' and moneda='$moneda'   and e.idempresa='$idempresa' and c.estado='1'  order by c.fecha asc";
    }
    return ejecutarConsulta($sql);
  }

  public function totalregcompra($año, $mes) {
    $sql = "SELECT sum(c.subtotal) as valor_inafecto, sum(c.igv) as igv, sum(c.total) as total  from compra c inner join persona p on c.idproveedor=p.idpersona where year(c.fecha)='$año' and month(c.fecha)='$mes'";
    return ejecutarConsulta($sql);
  }

  public function totalregcompraReporte($año, $mes, $moneda, $idempresa)  {
    if ($moneda == 'usd') {
      $sql = "SELECT sum(c.subtotal_$) as subtotal, sum(c.igv_$) as igv, sum(c.total_$) as total 
      from compra c 
      inner join persona p on c.idproveedor=p.idpersona 
      inner join empresa e on c.idempresa=e.idempresa 
      where year(c.fecha)='$año' and month(c.fecha)='$mes' and moneda='$moneda' and e.idempresa='$idempresa' and c.estado='1'";
    } else {
      $sql = "SELECT sum(c.subtotal) as subtotal, sum(c.igv) as igv, sum(c.total) as total 
      from compra c 
      inner join persona p on c.idproveedor=p.idpersona 
      inner join empresa e on c.idempresa=e.idempresa 
      where year(c.fecha)='$año' and month(c.fecha)='$mes' and moneda='$moneda' and e.idempresa='$idempresa' and c.estado='1'";
    }

    return ejecutarConsulta($sql);
  }

  public function compraReporte1($idcompra)  {
    $sql = "SELECT concat(c.serie,'-' , c.numero) as numero, date_format(c.fecha, '%d-%m-%Y') as fecha, p.razon_social as proveedor, u.nombre as usuario, ct1.descripcion as tdocumento, 
    a.codigo, a.nombre, dtc.valor_unitario as vunitario, dtc.cantidad, dtc.subtotal as stotal, year(c.fecha) as año, c.estado, c.moneda, c.tcambio, um.nombreum
    from compra c 
    inner join detalle_compra_producto dtc on c.idcompra=dtc.idcompra 
    inner join articulo a on dtc.idarticulo=a.idarticulo 
    inner join persona p on c.idproveedor=p.idpersona 
    inner join usuario u on c.idusuario=u.idusuario 
    inner join catalogo1 ct1 on c.tipo_documento=ct1.codigo 
    inner join umedida um on a.umedidacompra=um.idunidad
    where c.idcompra='$idcompra'";
    return ejecutarConsulta($sql);
  }

  public function compraReporte2($idcompra)  {
    $sql = "SELECT  format(subtotal,2) as sbt, format(igv,2) as igv_, format(total,2) as ttl  from compra  where idcompra='$idcompra'";
    return ejecutarConsulta($sql);
  }

  public function datosemp($idempresa)  {
    $sql = "SELECT * from empresa where idempresa='$idempresa'";
    return ejecutarConsulta($sql);
  }

  public function AnularCompra($idcompra, $fechaemision, $idempresa)  {
    $sw = true;    

    $query = "SELECT dc.idcompra, a.idarticulo, dc.cantidad,  dc.valor_unitario, a.codigo, a.unidad_medida  from detalle_compra_producto dc inner join articulo a on dc.idarticulo=a.idarticulo where idcompra = '$idcompra'";
    $resultado = ejecutarConsultaArray2( $query);

    $Idc = [];
    $Ida = [];
    $Ct = [];
    $Cod = [];
    $Vu = [];
    $Um = [];
    $sw = true;
    foreach ($resultado['data'] as $key => $fila) {
      
      $Idc = $fila["idcompra"];
      $Ida = $fila["idarticulo"];
      $Ct = $fila["cantidad"];
      $Cod = $fila["codigo"];
      $Vu = $fila["valor_unitario"];
      $Um = $fila["unidad_medida"];

      $sql_update_articulo = "UPDATE detalle_compra_producto dc 
      inner join articulo a on dc.idarticulo=a.idarticulo 
      set a.saldo_finu=a.saldo_finu - '$Ct', a.stock=a.stock - '$Ct', a.comprast=a.comprast - '$Ct' 
      where dc.idcompra='$Idc' and dc.idarticulo='$Ida'";

      $sql_update_articulo_2 = "UPDATE detalle_compra_producto dc 
      inner join articulo a on dc.idarticulo=a.idarticulo 
      set a.valor_finu=(a.saldo_iniu + a.comprast - ventast) * a.costo_compra
      where dc.idcompra='$Idc' and dc.idarticulo='$Ida'";

      //ACTUALIZAR TIPO TRANSACCIon KARDEX
      //Guardar en Kardex
      $sql_kardex = "UPDATE kardex set transaccion='COMPRA ANULADA' where idcomprobante='$idcompra' and transaccion='COMPRA'";
      
      //Fin de FOR
      ejecutarConsulta2($sql_update_articulo) or $sw = false;
      ejecutarConsulta2($sql_update_articulo_2) or $sw = false;
      ejecutarConsulta2($sql_kardex) or $sw = false;

      $sqlestado = "update compra set estado='3' where idcompra='$idcompra'";
      ejecutarConsulta2($sqlestado) or $sw = false;
    }
    //Fin de WHILE
    return $sw;
  }
}

?>