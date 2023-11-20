<?php
ob_start();
session_start();
if (!isset($_SESSION["nombre"])) {
  header("Location: ../vistas/login.php");
} else {
  require 'header.php';
  if ($_SESSION['Logistica'] == 1) {
?>
    <!-- Custom CSS -->
    <!-- <link rel="stylesheet" href="../public/css/main.css" > -->

    <!-- html5tooltips Styles & animations -->
    <link href="../public/css/html5tooltips.css" rel="stylesheet">
    <link href="../public/css/html5tooltips.animation.css" rel="stylesheet">


    <!--Content Start-->
    <div class="content-start transition  ">
      <div class="container-fluid dashboard">
        <div class="content-header">
          <h1>Servicio <button class="btn btn-primary btn-sm" onclick="limpiar_form_articulo(); generarCodigoAutomatico('SR');" data-bs-toggle="modal" data-bs-target="#modalAgregarProducto">Agregar</button> <button class="btn btn-success btn-sm" id="refrescartabla" onclick="refrescartabla()">Refrescar tabla</button>

            <label style="position:relative;top: 3px; float: right;" class="toggle-switch" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Activar generador código de barra correlativamente automático">
              <input id="generar-cod-correlativo" class="cod-correlativo" type="checkbox" checked>
              <span class="slider"></span>
            </label>
          </h1>
        </div>

        <div class="row">

          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                <div class="table-responsive">
                  <table id="tbllistadoservicios" class="table table-striped" style="width: 100% !important;">
                    <thead>
                      <th>Opciones</th>
                      <th>Descripción del servicio</th>
                      <th>Sucursal</th>
                      <th>Cod. interno</th>
                      <th>Precio venta</th>
                      <!-- <th>...</th> -->
                      <th>Estado</th>

                    </thead>
                    <tbody>
                    </tbody>

                  </table>
                </div>
              </div>
            </div>
          </div>



        </div><!-- /.row -->


      </div><!-- End Container-->
    </div><!-- End Content-->
    <style>
      @media (min-width: 992px) {

        .modal-lg,
        .modal-xl {
          max-width: 1200px;
        }
      }
    </style>

    <!-- MODAL - AGREGAR SERVICIO-->
    <div class="modal fade text-left" id="modalAgregarProducto" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="myModalLabel1">Añade nuevo servicio</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form name="formulario" id="formulario" method="POST">

              <input type="hidden" name="idarticulo" id="idarticulo">
              <input type="hidden" name="idempresa" id="idempresa" value="<?php echo $_SESSION['idempresa']; ?>">
              <input type="hidden" name="tipoitem" id="tipoitem" value="servicios">
              <input type="hidden" name="umedidacompra" id="umedidacompra" >

              <!-- Cantidad de Stock: -->
              <input type="hidden" class="form-control stokservicio" name="stock" id="stock"  placeholder="Stock">
              <!-- Limite stock: -->
              <input type="hidden" class="form-control" name="limitestock" id="limitestock"  placeholder="Limite de stock" data-tooltip="Información de este campo" data-tooltip-more="Aquí ingrese el milite de stock para que se bloquee las ventas al llegar a su limite." data-tooltip-stickto="top" data-tooltip-maxwidth="500" data-tooltip-animate-function="foldin" data-tooltip-color="green" onkeypress=" return limitest(event, this)">
              <!-- Precio por mayor: -->
              <input type="hidden" class="form-control" name="precio2" id="precio2" placeholder="Précio por mayor">
              <!-- Precio distribuidor: -->
              <input type="hidden" class="form-control" name="precio3" id="precio3" placeholder="Précio distribuidor">

              <div class="row">
                <div class="mb-3 col-lg-3">
                  <div class="form-group">
                    <label for="recipient-name" class="col-form-label">Sucursal:</label>                  
                    <select class="form-control" name="idalmacen" id="idalmacen"  onchange="focusfamil()"> </select>
                  </div>                  
                </div>

                <div class="mb-3 col-lg-3">
                  <div class="form-group">
                    <label for="recipient-name" class="col-form-label">Categoria:</label>
                    <select class="form-control" name="idfamilia" id="idfamilia"  >    </select>
                  </div>                  
                </div>                

                <div class="mb-3 col-lg-3">
                  <div class="form-group">
                    <label for="recipient-name" class="col-form-label">U. medida servicio:</label>
                    <select class="form-control" name="unidad_medida" id="unidad_medida"  onchange="costoco()"> </select>
                  </div>                  
                </div>

                <div class="mb-3 col-lg-3">
                  <div class="form-group">
                    <label for="idmarca" class="col-form-label">Marca:</label>
                    <select class="form-control" name="idmarca" id="idmarca" >  </select>
                  </div>                  
                </div>

                <div class="mb-3 col-lg-6">
                  <div class="form-group">
                    <label for="recipient-name" class="col-form-label">Nombre del servicio:</label>
                    <textarea class="form-control" id="nombre" name="nombre" rows="2" onkeyup="mayus(this)"> </textarea>
                  </div>                  
                </div>

                <div class="mb-3 col-lg-6">
                  <div class="form-group">
                    <label for="recipient-name" class="col-form-label">Detalles del servicio:</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="2" onkeyup="mayus(this)"> </textarea>
                  </div>                  
                </div>                

                <div class="mb-3 col-lg-4">
                  <div class="form-group"></div>
                  <label for="recipient-name" class="col-form-label">Código de servicio:</label>
                  <input type="text" class="form-control codigo" name="codigo" id="codigo" placeholder="Código interno de servicio"  onkeyup="mayus(this);" onchange="validarcodigo()">
                </div>

                <div class="mb-3 col-lg-4">
                <div class="form-group"></div>
                  <label for="recipient-name" class="col-form-label">Precio del servicio (S/.):</label>
                  <input type="text" class="form-control" name="valor_venta" id="valor_venta" min="0" step="0.01" onkeypress="return codigoi(event, this)" data-tooltip="Información de este campo" data-tooltip-more="El precio que se muestra en los ocmprobantes, incluye IGV." data-tooltip-stickto="top" data-tooltip-maxwidth="500" data-tooltip-animate-function="foldin" data-tooltip-color="green">
                </div>

                <!-- precio compra -->
                <div class="mb-3 col-lg-4">
                  <div class="form-group">                      
                    <label for="costo_compra" class="col-form-label">Precio inversion(S/.):</label>
                    <input type="number" class="form-control" name="costo_compra" id="costo_compra" min="0" step="0.01" onkeypress="return focussaldoi(event, this)" >
                  </div>
                </div>                              

                <div class="mb-3 col-lg-3">
                  <div class="form-group">
                    <label for="recipient-name" class="col-form-label">Cód. tipo de tributo:</label>
                    <select name="codigott" id="codigott" class="form-control">
                      <option value="1000">1000</option>
                      <option value="1016">1016</option>
                      <option value="2000">2000</option>
                      <option value="7152">7152</option>
                      <option value="9995">9995</option>
                      <option value="9996">9996</option>
                      <option value="9997" selected>9997</option>
                      <option value="9998">9998</option>
                      <option value="9999">9999</option>
                    </select>
                  </div>                  
                </div>

                <div class="mb-3 col-lg-3">
                  <div class="form-group">
                    <label for="recipient-name" class="col-form-label">Tributo:</label>
                    <select name="desctt" id="desctt" class="form-control">
                      <option value="IGV Impuesto General a las Ventas">IGV Impuesto General a las Ventas</option>
                      <option value="Impuesto a la Venta Arroz Pilado">Impuesto a la Venta Arroz Pilado</option>
                      <option value="ISC Impuesto Selectivo al Consumo">ISC Impuesto Selectivo al Consumo</option>
                      <option value="Impuesto al Consumo de las bolsas de plástico">Impuesto al Consumo de las bolsas de plástico</option>
                      <option value="Exportación">Exportación</option>
                      <option value="Gratuito">Gratuito</option>
                      <option value="Exonerado" selected>Exonerado</option>
                      <option value="Inafecto">Inafecto</option>
                      <option value="Otros tributos">Otros tributos</option>
                    </select>
                  </div>                  
                </div>

                <div class="mb-3 col-lg-3">
                  <div class="form-group">
                    <label for="recipient-name" class="col-form-label">Código internacional:</label>
                    <select name="codigointtt" id="codigointtt" class="form-control">
                      <option value="VAT" selected>VAT</option>
                      <option value="EXC">EXC</option>
                      <option value="FRE">FRE</option>
                      <option value="VAT">VAT</option>
                      <option value="OTH">OTH</option>
                    </select>
                  </div>                  
                </div>

                <div class="mb-3 col-lg-3">
                  <div class="form-group">
                    <label for="recipient-name" class="col-form-label">Nombre:</label>
                    <select name="nombrett" id="nombrett" class="form-control">
                      <option value="IGV">IGV</option>
                      <option value="IVAP">IVAP</option>
                      <option value="ISC">ISC</option>
                      <option value="ICBPER">ICBPER</option>
                      <option value="EXP">EXP</option>
                      <option value="GRA">GRA</option>
                      <option value="EXO" selected>EXO</option>
                      <option value="INA">INA</option>
                      <option value="OTROS">OTROS</option>
                    </select>
                  </div>                  
                </div>

                <div class="mb-3 col-lg-4">
                  <div class="form-group">
                    <label for="recipient-name" class="col-form-label">Imagen del servicio:</label>
                    <input type="file" class="form-control" name="imagen" id="imagen" value="" accept="image/*">
                    <input type="hidden" name="imagenactual" id="imagenactual">  
                  </div>                                  
                  <hr>
                  <div class="" id="preview"> <img src="" width="150px" height="auto" id="imagenmuestra"></div>
                </div>                

                <div class="row" id="mostrarCompra" >

                  <div class="p-l-25px col-lg-12" id="barra_progress_articulo_div" style="display: none;">
                    <div  class="progress progress-lg custom-progress-3" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"> 
                      <div id="barra_progress_articulo" class="progress-bar" style="width: 0%"> <div class="progress-bar-value">0%</div> </div> 
                    </div>
                  </div>
                    
                  <!-- Proveedor: -->
                  <input type="hidden" name="proveedor" id="proveedor" class="form-control">                    
                  <!-- Código proveedor: -->
                  <input type="hidden" class="form-control" name="codigo_proveedor" id="codigo_proveedor" placeholder="Código de proveedor" onkeyup="mayus(this)" onkeypress="return focusnomb(event, this)" data-tooltip="Información de este campo" data-tooltip-more="Aquí ingrese si su artículo tiene un código que viene desde el proveedor, es opcional, si no tiene un codigo puede poner un . o -" data-tooltip-stickto="top" data-tooltip-maxwidth="500" data-tooltip-animate-function="foldin" data-tooltip-color="green">
                  <!-- Serie fac. compra: -->
                  <input type="hidden" name="seriefaccompra" id="seriefaccompra" class="form-control">                    
                  <!-- Número fac. compra: -->
                  <input type="hidden" name="numerofaccompra" id="numerofaccompra" class="form-control">
                  <!-- Fecha fac. compra: -->
                  <input type="hidden" name="fechafacturacompra" id="fechafacturacompra" class="form-control" style="color:blue;">                    
                  
                </div>

                <div class="row" id="mostraOtroscampos" >                                  
                    
                  <!-- Portador: -->
                  <input type="hidden" class="form-control" name="portador" id="portador" maxlength="5" onkeypress="return mer(event, this)">
                  <!-- Merma: -->
                  <input type="hidden" class="form-control" name="merma" id="merma" maxlength="5" onkeypress="return preciov(event, this)">
                  <!-- Código SUNAT: -->
                  <input type="hidden" class="form-control" name="codigosunat" id="codigosunat" placeholder="Código SUNAT" onkeyup="mayus(this);" data-tooltip="Información de este campo" data-tooltip-more="Validar con el catálogo de productos que brinda SUNAT." data-tooltip-stickto="top" data-tooltip-maxwidth="500" data-tooltip-animate-function="foldin" data-tooltip-color="green">
                  <!-- Cta. contable: -->
                  <input type="hidden" class="form-control" name="ccontable" id="ccontable" placeholder="Cuenta contabe" data-tooltip="Información de este campo" data-tooltip-more="Opcional." data-tooltip-stickto="top" data-tooltip-maxwidth="500" data-tooltip-animate-function="foldin" data-tooltip-color="green">

                  <!-- NUEVOS CAMPOS PARA BOLSAS PLASTICAS  -->
                  <!-- Cód. tributo ICBPER: -->
                  <input type="hidden" class="form-control" name="cicbper" id="cicbper" placeholder="7152" onkeyup="mayus(this);">  
                  <!-- N. tributo ICBPER: -->
                  <input type="hidden" class="form-control" name="nticbperi" id="nticbperi" placeholder="ICBPER" onkeyup="mayus(this);">
                  <!-- Cód. tributo ICBPER OTH: -->
                  <input type="hidden" class="form-control" name="ctticbperi" id="ctticbperi" placeholder="OTH" onkeyup="mayus(this);">
                  <!-- Mto trib. ICBPER und: -->
                  <input type="hidden" class="form-control" name="mticbperu" id="mticbperu" placeholder="0.10" onkeyup="mayus(this);">

                  <!-- NUEVOS CAMPOS PARA BOLSAS PLASTICAS  2-->
                  <!-- Lote: -->
                  <input type="hidden" name="lote" id="lote" class="form-control"> 
                  <!-- Fecha de fabricación: -->
                  <input type="hidden" name="fechafabricacion" id="fechafabricacion" class="form-control" style="color:blue;">
                  <!-- Fecha de vencimiento: -->
                  <input type="hidden" name="fechavencimiento" id="fechavencimiento" class="form-control" style="color:blue;">
                  <!-- Procedencia: -->
                  <input type="hidden" name="procedencia" id="procedencia" class="form-control">
                  <!-- Fabricante: -->
                  <input type="hidden" name="fabricante" id="fabricante" class="form-control">
                  <!-- Registro Sanitario: -->
                  <input type="hidden" name="registrosanitario" id="registrosanitario" class="form-control">
                  <!-- Fecha ing. almacén: -->
                  <input type="hidden" name="fechaingalm" id="fechaingalm" class="form-control" style="color:blue;">
                  <!-- Fecha fin stock: -->
                  <input type="hidden" name="fechafinalma" id="fechafinalma" class="form-control" style="color:blue;">
                  
                </div>
                
                <!-- Factor conversión: -->
                <input type="hidden" class="form-control" name="factorc" id="factorc" onkeypress=" return umventa(event, this)">                 
                <!-- Saldo inicial (S/.): -->
                <input type="hidden" class="form-control salini" name="saldo_iniu" id="saldo_iniu" maxlength="500" placeholder="Saldo inicial" onBlur="calcula_valor_ini()" onkeypress="return valori(event, this)" data-tooltip="Información de este campo" data-tooltip-more="Si es la primera vez que llena este campo poner el saldo final de su inventario físico. " data-tooltip-stickto="top" data-tooltip-maxwidth="500" data-tooltip-animate-function="foldin" data-tooltip-color="green">
                <!-- Valor inicial (S/.): -->
                <input type="hidden" class="form-control" name="valor_iniu" id="valor_iniu" maxlength="500" placeholder="Valor inicial" value="0" onkeypress="return saldof(event, this)" data-tooltip="Información de este campo" data-tooltip-more="El valor inicial es el costo compra x saldo inicial." data-tooltip-stickto="top" data-tooltip-maxwidth="500" data-tooltip-animate-function="foldin" data-tooltip-color="green">
                <!-- Saldo final (mts): -->
                <input type="hidden" class="form-control salfin" name="saldo_finu" id="saldo_finu" maxlength="500" placeholder="Saldo final"  onkeypress="return valorf(event, this)" onBlur="sfinalstock()" data-tooltip="Información de este campo" data-tooltip-more="La primera vez en el registro será igual a saldo inicial (saldofinal=saldo inicial)." data-tooltip-stickto="top" data-tooltip-maxwidth="500" data-tooltip-animate-function="foldin" data-tooltip-color="green">
                <!-- Valor final (S/.): -->
                <input type="hidden" class="form-control" name="valor_finu" id="valor_finu" maxlength="500" placeholder="Valor Final"  onkeypress="return st(event, this)" data-tooltip="Información de este campo" data-tooltip-more="El valor final es igual al valor incial (valor final=valor inicial)." data-tooltip-stickto="top" data-tooltip-maxwidth="500" data-tooltip-animate-function="foldin" data-tooltip-color="green">
                <!-- Conversión um venta: -->
                <input type="hidden" class="form-control convumventa" name="fconversion" id="fconversion" readonly>
                <!-- Total compras (mts): -->
                <input type="hidden" class="form-control" name="comprast" id="comprast" onkeypress="return totalv(event, this)" placeholder="No se llena" readonly data-tooltip="Información de este campo" data-tooltip-more="Este campo no se llena." data-tooltip-stickto="top" data-tooltip-maxwidth="500" data-tooltip-animate-function="foldin" data-tooltip-color="green">
                <!-- Total Servicios brindados (mts): -->
                <input type="hidden" class="form-control" name="ventast" id="ventast" onkeypress="return porta(event, this)" placeholder="No se llena" readonly data-tooltip="Información de este campo" data-tooltip-more="Este campo no se llena." data-tooltip-stickto="top" data-tooltip-maxwidth="500" data-tooltip-animate-function="foldin" data-tooltip-color="green">
                
              </div>
              <!-- /.row -->

              <button type="submit" style="display: none;" id="submit-form-articulo">Submit</button>
            </form>
          </div>
          <!-- /.modal-body -->

          <div class="eventoCodigoBarra" hidden>
            <button class="btn btn-success btn-sm" type="button" onclick="generarbarcode()">Mostrar codigo de barra</button>
            <button class="btn btn-success btn-sm" type="button" onclick="generarcodigonarti()">Asignar codigo automático</button>
            <button class="btn btn-info btn-sm" type="button" onclick="imprimir()"> <i class="fa fa-print"></i> Imprimir codigos</button>
            <input type="hidden" name="stockprint" id="stockprint">
            <input type="hidden" name="codigoprint" id="codigoprint">
            <input type="hidden" name="precioprint" id="precioprint">
            <div id="print"> <svg id="barcode"></svg>  </div>
          </div>

          <div class="modal-footer">
            <button onclick="limpiar_form_articulo()" type="button" class="btn btn-danger" data-bs-dismiss="modal">
              <i class="bx bx-x d-block d-sm-none"></i> <span class="d-none d-sm-block">Cancelar</span>
            </button>
            <button id="guardar_registro_articulo"  type="submit" class="btn btn-primary ml-1">
              <i class="bx bx-check d-block d-sm-none"></i> <span class="d-none d-sm-block">Agregar</span>
            </button>
          </div>
          
        </div>
      </div>
    </div>

    <!-- MODAL - VER PERFIL INSUMO-->
    <div class="modal fade" id="modal-ver-perfil-producto">
      <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content bg-color-0202022e shadow-none border-0">
          <div class="modal-header">
            <h4 class="modal-title text-white title-name-foto-zoom">Foto Zoom</h4>
            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
              <!-- <span class="text-white cursor-pointer" aria-hidden="true">&times;</span> -->
            </button>
          </div>
          <div class="modal-body"> 
            <div id="div-foto-zoom" class="text-center">
              <!-- vemos la imagen en zoom -->
            </div>
          </div> 
        </div>
      </div>
    </div>

  <?php

  } else {
    require 'noacceso.php';
  }
  require 'footer.php';
  ?>
  <!-- <script type="text/javascript" src="../public/js/JsBarcode.all.min.js"></script> -->
  <script type="text/javascript" src="../public/js/jquery.PrintArea.js"></script>
  <script type="text/javascript" src="scripts/articulo.js"></script>
  <script src="../public/js/html5tooltips.js"></script>
<?php

}
ob_end_flush();
?>