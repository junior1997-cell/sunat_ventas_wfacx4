<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
  header("Location: ../vistas/login.php");
} else {
  require 'header.php';

  if ($_SESSION['Ventas'] == 1) {
?>
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css"> -->
    <!--Contenido-->
    
    <!-- Content Wrapper. Contains page content -->
    <div class="content-start transition">
      <!-- Main content -->
      <section class="container-fluid dashboard">
        <div class="content-header">
          <h1>Nota de crédito <button class="btn btn-info btn-sm" id="btnagregar" onclick="mostrarform(true)">Nuevo</button> <button class="btn btn-success btn-sm" id="refrescartabla" onclick="refrescartabla()">Refrescar</button></h1>
          <input hidden type="checkbox" name="chk1" id="chk1" onclick="unotodos()" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Mostrar por día o todos los comprobantes">
        </div>
        <div class="row" >
          <div class="col-md-12">

            <div class="table-responsive" id="listadoregistros">
              <table id="tbllistado" class="table table-striped" style="font-size: 14px; max-width: 100% !important;">
                <thead >
                  <th>-</th>
                  <th>Número</th>
                  <th>Fecha Emisión</th>
                  <th>Descripción</th>
                  <th>Comp. modificado</th>
                  <th>Cliente</th>
                  <th>Op. Gravada</th>
                  <th>Igv</th>
                  <th>Total</th>
                  <th>Estado</th>
                  <th>Opciones</th>
                </thead>
                <tbody >
                </tbody>

              </table>
            </div>

            <form name="formulario" id="formulario" method="POST">
              <div class="panel-body" id="formularioregistros" style="display: none;">
                
                <!-- <div class="form-group col-lg-12 col-md-4 col-sm-6 col-xs-12">
                <label style="color: red;" > *Para notas de crédito a boletas debe seleccionar la serie B.  </label>
                <label style="color: red;" > *Recuerde que devolución por item solo es para facturas y boletas de productos mas no servicios.</label>
                </div> -->

                <div class="row">

                  <div class="col-md-4">
                    <div class="card">
                      <div class="card-body">
                        <div class="row">

                          <div class="col-lg-12">
                            <div class="flex-container mb-3">
                              <div class="d-flex">
                                <div class="p-2 flex-fill">
                                  <input type="radio" name="tipo_doc_mod" id="boleta-outlined" value="03" class="btn-check tipo_doc_mod" checked onchange="cambio()">
                                  <label class="btn btn-outline-info btn-sm w-100 m-1" for="boleta-outlined">Boleta</label>
                                </div>
                                
                                <div class="p-2 flex-fill">
                                  <input type="radio"  name="tipo_doc_mod" id="factura-outlined" value="01" class="btn-check tipo_doc_mod" onchange="cambio()">
                                  <label label class="btn btn-outline-info btn-sm w-100 m-1" for="factura-outlined">Factura</label>
                                </div>
                              </div>
                            </div>                            
                          </div>

                          <div class="mb-3 col-lg-6">
                            <label for="serie" class="form-label">Serie:</label>
                            <select class="form-control" name="serie" id="serie" onchange="incremetarNum();"></select>
                            <input type="hidden" name="idnumeracion" id="idnumeracion">
                            <input type="hidden" name="SerieReal" id="SerieReal">
                            <input type="hidden" name="idempresa" id="idempresa" value="<?php echo $_SESSION['idempresa']; ?>">
                          </div>

                          <div class="mb-3 col-lg-6">
                            <label for="numero_nc" class="form-label">Número:</label>
                            <input type="text" name="numero_nc" id="numero_nc" class="form-control" required="true" readonly>
                          </div>

                          <div class="mb-3 col-lg-6">
                            <label for="fecha_emision" class="form-label">Fecha operación:</label>
                            <input type="date" class="form-control" name="fecha_emision" id="fecha_emision" required="true" disabled="true">
                          </div>

                          <div class="mb-3 col-lg-6">
                            <label for="codigo_nota" class="form-label">Motivo N. Crédito:</label>
                            <select class="form-control" name="codigo_nota" id="codigo_nota" onchange="cambiotiponota()"></select>
                            <input type="hidden" name="codtiponota" id="codtiponota">
                            <input type="hidden" name="nomcodtipo" id="nomcodtipo">
                          </div>

                          <div class="mb-3 col-lg-6">
                            <label for="tipo_moneda" class="form-label">Moneda:</label>
                            <select class="form-control" name="tipo_moneda" id="tipo_moneda" onchange="tipomonn()">
                              <option value="PEN">PEN</option>
                              <option value="USD">USD</option>
                            </select>
                          </div>

                          <!-- <div class="mb-3 col-lg-6">
                            <label for="tipo_doc_mod" class="form-label">Tipo de comprobante:</label>
                            <select class="form-control" name="tipo_doc_mod" id="tipo_doc_mod" onchange="cambio()">
                              <option value="01">FACTURA PRODUCTO</option>
                              <option value="03">BOLETA PRODUCTO</option>
                            </select>
                          </div> -->

                          <div class="mb-3 col-lg-6">
                            <label for="vendedorsitio" class="form-label">Vendedor:</label>
                            <select class="form-control" name="vendedorsitio" id="vendedorsitio">
                            </select>
                          </div>

                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="d-flex">
                              <div class="flex-fill text-center">
                                <button id="btnCancelar" class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Cancelar"></i> Cancelar</button>
                              </div>
                              <div class="flex-fill text-center">
                                <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Guardar"></i> Guardar</button>
                              </div>
                            </div>                                                    
                          </div>

                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-8">
                    <div class="row">
                      <div class="col-lg-12">
                        <div class="card">
                          <div class="card-body">                         

                            <div class="row">

                              <div class="col-lg-12 mb-3">
                                <button style="margin-left:0px;" data-bs-toggle="modal" data-bs-target="#myModalComprobante" id="btnAgregarcomp" type="button" class="btn btn-primary btn-sm"><i class="fa fa-search" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Buscar Comprobante"></i> Buscar Comprobante</button>

                                <button style="margin-left:0px;" data-bs-toggle="modal" data-bs-target="#myModalArt" id="btnAgregarart" type="button" class="btn btn-danger btn-sm" onclick="cambiarlistadoum2()">
                                  Agregar Productos o Servicios
                                </button>
                              </div>

                              <div class="mb-3 col-lg-3">
                                <label for="numero_comprobante" class="form-label">N. comprobante</label>
                                <input type="text" class="form-control" name="numero_comprobante" id="numero_comprobante" required="true" style="background-color: #DAF7A6; font-weight:bold;" readonly="true">
                              </div>

                              <div class="mb-3 col-lg-3">
                                <label for="numero_documento_cliente" class="form-label">RUC</label>
                                <input type="text" class="form-control" name="numero_documento_cliente" id="numero_documento_cliente" maxlength="15" placeholder="" required="true" style="background-color: #DAF7A6; font-weight:bold;" readonly="true">
                              </div>

                              <div class="mb-3 col-lg-4">
                                <label for="razon_social" class="form-label">Cliente</label>
                                <input type="text" class="form-control" name="razon_social" id="razon_social" maxlength="8" placeholder="" width="50x" required="true" style="background-color: #DAF7A6; font-weight:bold;" readonly="true">
                              </div>

                              <div class="mb-3 col-lg-2">
                                <label for="fechacomprobante" class="form-label">Fecha Emisión</label>
                                <input type="text" class="form-control" name="fechacomprobante" id="fechacomprobante" style="background-color: #DAF7A6; font-weight:bold; "><input type="hidden" name="fechacomprobante2" id="fechacomprobante" readonly="true">
                              </div>

                              <div class="mb-3 col-lg-12">
                                <textarea rows="2" cols="50" class="form-control" placeholder="Escribir todas las obervaciones necesarias" name="desc_motivo" id="desc_motivo" style="background-color: #DAF7A6; font-weight:bold;"> </textarea>
                              </div>

                            </div>

                            <div class="table-responsive">
                              <table id="detallesnc" class="table table-striped" >
                                <thead align="center" >
                                  <th >Sup.</th>
                                  <th >Item</th>
                                  <th >Artículo</th>
                                  <th >Código</th>
                                  <th >-</th>
                                  <th >U.M.</th>
                                  <th >Prec. Uni.</th>
                                  <th >Val. u.</th>
                                  <th >Cant.</th>
                                  <th >Importe</th>
                                </thead>
                                <tbody>
                                </tbody>
                              </table>
                            </div>


                            <div id="pdescu" style="display: none;">
                              <div class="row justify-content-center text-center">

                                <div class="mb-3 col-lg-3">
                                  <label for="pdescuento" class="form-label">% Desc</label>
                                  <input type="text" class="form-control" name="pdescuento" id="pdescuento" maxlength="3" placeholder="%" style="background-color: #FFFF99; font-weight:bold;" onkeypress="return NumChek(event, this);" onchange="calDescuento()">
                                </div>

                                <div class="mb-3 col-lg-3">
                                  <label for="subtotaldesc" class="form-label">Op. Gravada</label>
                                  <input type="text" class="form-control" name="subtotaldesc" id="subtotaldesc" maxlength="3" placeholder="Subtotal" style="background-color: #FFFF99; font-weight:bold;" readonly>
                                </div>

                                <div class="mb-3 col-lg-3">
                                  <label for="igvdescu" class="form-label">IGV</label>
                                  <input type="text" class="form-control" name="igvdescu" id="igvdescu" maxlength="3" placeholder="IGV" style="background-color: #FFFF99; font-weight:bold;" readonly>
                                </div>

                                <div class="mb-3 col-lg-3">
                                  <label for="totaldescu" class="form-label">Importe total</label>
                                  <input type="text" class="form-control" name="totaldescu" id="totaldescu" maxlength="3" placeholder="Total" style="background-color: #FFFF99; font-weight:bold; font-size: 14px;" readonly>
                                </div>

                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-12">
                        <div class="card">
                          <div class="card-body">

                            <div class="table-responsive">
                              <table id="detalles" class="table table-striped" >
                                <thead  >
                                  <th >+</th>
                                  <th >Item</th>
                                  <th >Código</th>
                                  <th >Descripción</th>
                                  <th >Cantidad</th>
                                  <th >Va. Uni.</th>
                                  <th >Pre. Uni.</th>
                                  <th >Valor uni.</th>
                                  <th >Igv</th>
                                </thead>
                                <tbody>
                                </tbody>
                              </table>
                              <div id="totales">
                                <div class="card-body d-flex align-items-end flex-column" >
                                  <div>
                                    <table class="table">
                                      <tbody>
                                        <tr><th class="py-1">Subtotal</th> <td class="py-1">:</td><td class="py-1" id="subtotal">0.00</td></tr>
                                        <tr><th class="py-1">IGV</th>      <td class="py-1">:</td><td class="py-1" id="igv_">0.00</td></tr>
                                        <tr ><th class="py-1 font-size-15px"><b>Total</b> </th> <td class="py-1">:</td><td class="py-1 font-size-15px" id="total">0.00</td></tr>
                                      </tbody>                                    
                                    </table>
                                  </div>
                                  
                                  <input type="hidden" name="subtotal_comprobante" id="subtotal_comprobante">                                  
                                  <input type="hidden" name="total_igv" id="total_igv">                                  
                                  <input type="hidden" name="total_final" id="total_final">                                  
                                </div>                                  
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- /.col-lg-12 -->
                    </div>
                    <!-- /.row -->
                  </div>
                  <!-- /.col-md-8 -->
                </div>
                <!-- /.row -->
              </div>
              <!-- /.panel-body -->

              <div class="form-group col-lg-12">
                <!--Campos para guardar comprobante Nota de credito-->
                <input type="hidden" name="idnota" id="idnota">
                <input type="hidden" name="idcomprobante" id="idcomprobante">
                <input type="hidden" name="tipocomprobante" id="tipocomprobante">
                <input type="hidden" name="nombre" id="nombre" value="NOTA DE CRÉDITO">
                <input type="hidden" name="codigo_nota" id="codigo_nota" value="07">

                <input type="hidden" name="sum_ot_car" id="sum_ot_car" value="0">
                <input type="hidden" name="total_val_venta_oi" id="total_val_venta_oi" value="0">
                <input type="hidden" name="total_val_venta_oe" id="total_val_venta_oe" value="0">
                <input type="hidden" name="sum_isc" id="sum_isc" value="0">
                <input type="hidden" name="sum_ot" id="sum_ot" value="0">
                <input type="hidden" name="fecha_factura" id="fecha_factura">
                <!--Datos del cliente-->
                <input type="hidden" name="idpersona" id="idpersona" required="true">
                <input type="hidden" name="tipo_documento_cliente" id="tipo_documento_cliente">
                <input type="hidden" name="hora" id="hora">
                <input type="hidden" name="tiponotaC" id="tiponotaC">
                <!--Datos del cliente-->
              </div>
              
            </form>
                       
          </div>
        </div>
      </section><!-- /.content -->

    </div><!-- /.content-wrapper -->
    <!--Fin-Contenido-->

    <!-- MODAL - COMPROBANTE PARA ANULAR -->
    <div class="modal fade text-left" id="myModalComprobante" tabindex="-1" role="dialog" aria-labelledby="myModalComprobante" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document" >
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="myModalComprobante">Agrega el comprobante a afectar</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" > 
            
            <div class="table-responsive" id="listadoregistros">
              <table id="tblacomprobante" class="table table-striped text-nowrap" style="max-width: 100% !important; width: 100% !important;">
                <thead >
                  <th><i class="fas fa-cog"></i></th>     
                  <th>Serie</th>             
                  <th>Emisión</th>
                  <th>Razon Social</th>                  
                  <th>Moneda</th>                 
                  <th>Total</th>
                </thead>
                <tbody >
                </tbody>
                <tfoot>
                </tfoot>
              </table>
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
              <i class="bx bx-x d-block d-sm-none"></i>
              <span class="d-none d-sm-block">Cerrar</span>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- MDOAL -->
    <div class="modal fade" id="myModalArt" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" style="width: 85% !important;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Seleccione un Artículo</h4>
          </div>

          <div class="form-group  col-lg-2 col-sm-2 col-md-2 col-xs-12">
            <label>Tipo de item</label>
            <select class="" name="tipofactura" id="tipofactura" onchange="cambiarlistado()">
              TIPO DE FACTURA
              <option value="st">TIPO FACTURA</option>
              <option value="productos" selected="true">PRODUCTOS</option>
              <option value="servicios">SERVICIOS</option>
            </select>
          </div>

          <div class="form-group  col-lg-2 col-sm-12 col-md-12 col-xs-12">
            <h4 class="modal-title">Tipo de precio</h4>
            <select class="" id="tipoprecio" onchange="listarArticulos()">
              <option value='1'>PRECIO PÚBLICO</option>
              <option value='2'>PRÉCIO POR MAYOR</option>
              <option value='3'>PRÉCIO DISTRIBUIDOR</option>
            </select>
          </div>

          <input type="hidden" name="itemno" id="itemno" value="0">

          <div class="form-group  col-lg-2 col-sm-12 col-md-12 col-xs-12">
            <h4 class="modal-title">Almacen</h4>
            <select class="" id="almacenlista" onchange="listarArticulos()">
            </select>
          </div>

          <div class="form-group  col-lg-12 col-sm-6 col-md-6 col-xs-12">
            <div class="table-responsive">
              <table id="tblarticulos" class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                  <th>Opciones</th>
                  <th>Nombre</th>
                  <th>Cód. prov.</th>
                  <th>U.M.</th>

                  <th>Precio</th>
                  <!-- <th>Stock</th> -->
                  <th>Imagen</th>
                </thead>
                <tbody>

                </tbody>
                <tfoot>
                  <th>Opciones</th>
                  <th>Nombre</th>
                  <th>Cód. prov.</th>
                  <th>U.M.</th>

                  <th>Precio</th>
                  <!-- <th>Stock</th> -->
                  <th>Imagen</th>
                </tfoot>
              </table>
            </div>
          </div>
          <div class="modal-footer">
            <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button> -->
          </div>
        </div>
      </div>
    </div>
    <!-- Fin modal -->


    <!-- MODAL -->
    <div class="modal fade" id="modalPreviewXml" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" style="width: 70% !important;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">NOTA DE CREDITO</h4>
          </div>

          <iframe name="modalxml" id="modalxml" border="0" frameborder="0" width="100%" style="height: 800px;" marginwidth="1" src="">
          </iframe>

          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin modal -->

  <?php
  } else {
    require 'noacceso.php';
  }

  require 'footer.php';
  ?>
  <script type="text/javascript" src="scripts/notac.js"></script>

<?php
}
ob_end_flush();
?>