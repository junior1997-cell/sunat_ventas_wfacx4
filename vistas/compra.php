<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
  header("Location: ../vistas/login.php");
} else {
  require 'header.php';

  if ($_SESSION['Logistica'] == 1) {
?>
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css"> -->
    <form name="formulario" id="formulario" method="POST">

      <div class="mb-3 content-header">
        <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#myModal">Agregar
          Producto</button>
        <button type="button" class="btn btn-success btn-sm" onclick="redirectToPage()">Ver lista de
          compra</button>
      </div>
      <!-- Start::row-1 -->
      <div class="row">
        <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <div class="card custom-card">
            <div class="card-body card-compra">
              <div class="row">

                <input type="hidden" name="idcompra" id="idcompra">
                <input type="hidden" name="idempresa" id="idempresa" value="<?php echo $_SESSION['idempresa']; ?>">

                <input type="hidden" name="subarticulo" id="subarticulo" value="0">
                <input type="hidden" name="codigos" id="codigos" value="0">
                <input type="hidden" name="nombrea" id="nombrea" value="0">
                <input type="hidden" name="stocka" id="stocka" value="0">
                <input type="hidden" name="codigob" id="codigob" value="0">
                <input type="hidden" name="umcompra" id="umcompra" value="0">                
                <input type="hidden" name="umventa" id="umventa" value="0">              
                <input type="hidden" name="factorc" id="factorc" value="0">                 
                <input type="hidden" name="factorc" id="factorc" value="0">                 
                <input type="hidden" name="vunitario" id="vunitario">
                <input type="hidden" name="hora" id="hora">
                
                <div class="mb-1 col-lg-5">                  
                  <div class="form-group">
                    <label for="idproveedor" class="form-label">Proveedor(*):</label>
                    <select id="idproveedor" name="idproveedor" class="form-control" data-live-search="true" required onchange="cambioproveedor()"></select>
                  </div>
                </div>
                <div class="col-lg-1">
                  <label for="" class="form-label">.</label> <br>
                  <button type="button" class="btn btn-primary btn-block" data-bs-toggle="modal" data-bs-target="#ModalNcategoria" onclick="">+</button>
                </div>
                <div class="mb-1 col-lg-2">
                  <label for="fecha_emision" class="form-label">Fecha:</label>
                  <input type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" name="fecha_emision" id="fecha_emision" required onchange="handler(event);">
                </div>
                <div class="mb-1 col-lg-2">
                  <label for="tipo_comprobante" class="col-form-label">Tipo(*):</label>
                  <select name="tipo_comprobante" id="tipo_comprobante" class="form-control" required onchange="cambiotcomprobante()" onfocus="cambiotcomprobante()">
                    <option value="01">FACTURA</option>
                    <option value="03">BOLETA</option>
                    <option value="56">GUIA REMISIÓN</option>
                  </select>
                </div>
                <div class="mb-1 col-lg-2">
                  <label for="moneda" class="col-form-label">Moneda:</label>
                  <select name="moneda" id="moneda" class="form-control" required onchange="cambiotcambio()">
                    <option value="PEN">SOLES</option>
                    <option value="USD">DOLARES</option>
                  </select>
                </div>
                <div class="mb-1 col-lg-2">
                  <label for="serie_comprobante" class="col-form-label">Serie:</label>
                  <input type="text" class="form-control" name="serie_comprobante" id="serie_comprobante" required="true" onkeyup="mayus(this);" onkeypress="EnterSerie(event)">
                </div>
                <div class="mb-1 col-lg-2">
                  <label for="message-text" class="col-form-label">Número(*):</label>
                  <input type="text" class="form-control" name="num_comprobante" id="num_comprobante" required="true" onkeypress="return EnterNumero(event,this)">
                </div>     
                <div class="mb-1 col-lg-8">
                  <label for="message-text" class="col-form-label">Observacion(*):</label>
                  <textarea name="num_comprobante" id="num_comprobante" class="form-control" rows="1"></textarea>                  
                </div>                 

              </div>
              <input type="hidden" name="tcambio" id="tcambio" class="" onkeyup="modificarSubototales()">
              <input type="hidden" name="idarticulonarti" id="idarticulonarti">
              
            </div>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <div class="card custom-card">
            <div class="card-body">
              <div class="table-responsive">
                <table id="detalles" class="table">
                  <thead>
                    <tr>
                      <th scope="col">Opciones</th>
                      <th scope="col">Artículo</th>
                      <th scope="col">Unidad medida</th>
                      <th scope="col">Cantidad</th>
                      <th scope="col">Costo Unitario</th>
                      <th scope="col">Total</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-auto me-auto">
              <button class="btn btn-primary" type="submit" id="btnGuardar" style="display: none;"><i class="fa fa-save"></i> Guardar</button>
              <button id="btnCancelar" class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
            </div>
            <div class="col-auto">
              <ul class="list-group list-group-flush">
                <li class="list-group-item text-right"><span class="text-left" ><b>Subtotal :</b></span> <span class="text-right" id="subtotal">0.00</span></li>
                <input type="hidden" name="subtotal_compra" id="subtotal_compra">
                <input type="hidden" name="totalcostounitario" id="totalcostounitario">
                <input type="hidden" name="totalcantidad" id="totalcantidad">
                <input type="hidden" name="totalcostounitario" id="totalcostounitario">
                <input type="hidden" name="totalcantidad" id="totalcantidad">
                <li class="list-group-item text-right"><span class="text-left" ><b>IGV :</b></span> <span class="text-right" id="igv_">0.00</span></li>
                <input type="hidden" name="total_igv" id="total_igv">
                <li class="list-group-item text-right"><span class="text-left" ><b>Total :</b></span> <span class="text-right" id="total">0.00</span></li>
                <input type="hidden" name="total_final" id="total_final">
                <input type="hidden" name="pre_v_u" id="pre_v_u">
              </ul>
            </div>            
          </div>
        </div>
        <!-- /.col -->

        
      </div>
      <!-- /.row -->
    </form>

    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModal" aria-hidden="true">
      <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="myModal">Agregar productos</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-lg-12">
                <div class="table-responsive">
                  <table id="tblarticulos" class="table table-striped" style="width: 100% !important;">
                    <thead>
                      <th>Código</th>
                      <th>Código 2</th>
                      <th>Nombre</th>                      
                      <th>Um compra</th>
                      <th>Stock Compra</th>
                      <th>Último précio</th>
                      <th>Opciones</th>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
            <!-- <button type="submit" id="btnGuardarNP" name="btnGuardarNP" value="btnGuardarNP"
                            class="btn btn-danger">Cerrar</button> -->
          </div>
        </div>
      </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="ModalNcategoria" aria-labelledby="ModalNcategoria" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="ModalNcategoria">Agregar nuevo proveedor</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form name="fnuevoprovee" id="fnuevoprovee" method="POST">
              <input type="hidden" name="tipo_persona" id="tipo_persona" value="proveedor">
              <div class="row">
                <div class="mb-3 col-lg-6">
                  <label for="recipient-name" class="col-form-label">Documento:</label>
                  <input type="text" class="form-control" name="numero_documento" id="numero_documento" onblur="validarProveedor();" onkeypress="return NumCheck(event, this)" autofocus="true">
                </div>
                <div class="mb-3 col-lg-6">
                  <label for="message-text" class="col-form-label">Razón social:</label>
                  <input type="text" class="form-control" name="razon_social" id="razon_social" required onkeyup="mayus(this);">
                </div>
              </div>
          </div>
          <div class="modal-footer">
            <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button> -->
            <button type="submit" id="btnGuardarNP" name="btnGuardarNP" value="btnGuardarNP" class="btn btn-primary">Guardar</button>
          </div>
          </form>
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
  <script type="text/javascript" src="scripts/compra.js"></script>
<?php
}
ob_end_flush();
?>