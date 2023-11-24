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
    <div class="">
      <div class="">
        <div class="content-header">
          <h1>Productos 
            <button class="btn btn-primary btn-sm" onclick="limpiar_form_articulo();  generarCodigoAutomatico('PR');" data-bs-toggle="modal" data-bs-target="#modalAgregarProducto">Agregar</button> 
            <button class="btn btn-success btn-sm" onclick="limpiar_form_import_articulo();" data-bs-toggle="modal" data-bs-target="#modal-importar-articulo">Importar Artículos</button>
            <label style="position:relative;top: 3px; float: right;" class="toggle-switch" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Activar generador código de barra correlativamente automático">
              <input id="generar-cod-correlativo" class="cod-correlativo" type="checkbox" checked>
              <span class="slider"></span>
            </label>
          </h1>
          <button hidden class="btn btn-success btn-sm" id="refrescartabla" onclick="refrescartabla()">Refrescar tabla</button>
        </div>
        <div class="row">
          <!-- <div class="col-xl-12"> 
            <div class="card custom-card" > 
              <div class="card-header" style="height: 100px; overflow-y: auto;"> 
                <nav class="nav nav-pills nav-style-6 lista-items" role="tablist" >                  
                  <a class="nav-link active" data-bs-toggle="tab" role="tab" aria-current="page" href="#nav-home" aria-selected="false" tabindex="-1">
                    <div class="spinner-border spinner-border-sm" role="status"> <span class="visually-hidden">Loading...</span> </div>
                  </a>                                   
                </nav> 
              </div> 
              <div class="card-body">                 
                <div class="tab-content"> 
                  <div class="tab-pane text-muted show active" id="nav-home" role="tabpanel"> 
                    -
                  </div> 
                </div> 
              </div> 
            </div> 
          </div> -->
          
          <div class="col-md-12">
            <div class="card">
              <div class="card-header" > 
                <div class="row">
                  <div class="col-lg-4">
                    <label for="filtro_idalmacen">Almacen</label>         
                    <select class="form-control" name="filtro_idalmacen" id="filtro_idalmacen" style="width: 100%;" onchange="cargando_search(); delay(function(){filtros()}, 100 );">
                    </select>
                  </div>
                  <div class="col-lg-4">     
                    <label for="filtro_idfamilia">Categoria</label>                 
                    <select class="form-control" name="filtro_idfamilia" id="filtro_idfamilia" style="width: 100%;" onchange="cargando_search(); delay(function(){filtros()}, 100 );">
                    </select>
                  </div>
                  <div class="col-lg-4">   
                    <label for="filtro_idmarca">Marca</label>                   
                    <select class="form-control" name="filtro_idmarca" id="filtro_idmarca" style="width: 100%;" onchange="cargando_search(); delay(function(){filtros()}, 100 );">
                    </select>
                  </div>
                </div>
              </div> 
              <div class="card-body">
                <div class="table-responsive">
                  <table id="tbllistado" class="table table-striped" style="width: 100% !important;">
                    <thead>
                      <tr > <th colspan="15" style="text-align: center !important;" class="cargando bg-danger p-1"><i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando... </th>
                        
                      </tr>
                      <tr>
                        <th>Opciones</th>
                        <th>Descripción</th>
                        <th>Almacen</th>
                        <th>Cod. interno</th>
                        <th>Stock</th>
                        <th>Precio venta</th>
                        <th>Precio compra</th>
                        <th>Estado</th>
                      </tr>                      
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /.row -->
      </div>
      <!-- End Container-->
    </div>
    <!-- End Content-->
    <style>
      @media (min-width: 992px) {

        .modal-lg,
        .modal-xl {
          max-width: 1200px;
        }
      }
    </style>
    
    <!-- MODAL - AGREGAR PRODUCTO -->
    <div class="modal fade text-left" id="modalAgregarProducto" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="myModalLabel1">Añade nuevo producto</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form name="formulario" id="formulario" method="POST">
              <ul class="nav nav-tabs tab-style-2 nav-justified mb-3 d-sm-flex d-block" id="myTab1" role="tablist">
                <li class="nav-item" role="presentation"> 
                  <button class="nav-link active" id="producto-tab" data-bs-toggle="tab" data-bs-target="#producto-tab-pane" type="button" role="tab" aria-controls="producto-tab-pane" aria-selected="true" tabindex="-1"><i class="ri-gift-line me-1 align-middle"></i> Producto</button> 
                </li>
                <li class="nav-item" role="presentation" > 
                  <button class="nav-link" id="sunat-tab" data-bs-toggle="tab" data-bs-target="#sunat-tab-pane" type="button" role="tab" aria-controls="sunat-tab-pane" aria-selected="false"><i class="ri-check-double-line me-1 align-middle"></i> Sunat</button> 
                </li>
                <li class="nav-item" role="presentation" > 
                  <button class="nav-link" id="proveedor-tab" data-bs-toggle="tab" data-bs-target="#proveedor-tab-pane" type="button" role="tab" aria-controls="proveedor-tab-pane" aria-selected="false" tabindex="-1"><i class="ri-shopping-bag-3-line me-1 align-middle"></i> Proveedor</button> 
                </li>
                <li class="nav-item" role="presentation"> 
                  <button class="nav-link" id="otros-tab" data-bs-toggle="tab" data-bs-target="#otros-tab-pane" type="button" role="tab" aria-selected="false" tabindex="-1"><i class="ri-truck-line me-1 align-middle"></i> Otros</button> 
                </li>
              </ul>

              <div class="tab-content" id="myTabContent">

                <div class="tab-pane fade show active text-muted" id="producto-tab-pane" role="tabpanel" aria-labelledby="producto-tab" tabindex="0">
                  <div class="row">

                    <!-- input ocultos -->
                    <input type="hidden" name="idarticulo" id="idarticulo">                    
                    <input type="hidden" name="idempresa" id="idempresa" value="<?php echo $_SESSION['idempresa']; ?>">
                    <input type="hidden" name="tipoitem" id="tipoitem" value="productos">
                    <input type="hidden" name="umedidacompra" id="umedidacompra" >

                    <!-- almacen -->
                    <div class="mb-3 col-lg-3">
                      <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Almacen:</label>                        
                        <select class="form-control" name="idalmacen" id="idalmacen"  onchange="focusfamil()">
                        </select>
                      </div>                     
                    </div>
                    <!-- categoria -->
                    <div class="mb-3 col-lg-3">
                      <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Categoria:</label>
                        <select class="form-control" name="idfamilia" id="idfamilia" >
                        </select>
                      </div>                      
                    </div>
                    <!-- unidad medida -->                    
                    <div class="mb-3 col-lg-3" >
                      <div class="form-group">
                        <label for="recipient-name" class="col-form-label">U. medida:</label>
                        <select class="form-control" name="unidad_medida" id="unidad_medida" >
                        </select>
                      </div>                      
                    </div>
                    <!-- marca -->
                    <div class="mb-3 col-lg-3">
                      <div class="form-group">
                        <label for="idmarca" class="col-form-label">Marca:</label>
                        <select class="form-control" name="idmarca" id="idmarca" >
                        </select>
                      </div>                      
                    </div>
                    <!-- nombre -->
                    <div class="mb-3 col-lg-6">
                      <div class="form-group">                      
                        <label for="recipient-name" class="col-form-label">Nombre / Descripción:</label>
                        <textarea class="form-control" id="nombre" name="nombre" placeholder="Nombre" rows="2"  onkeyup="mayus(this)"></textarea>
                      </div>
                    </div>
                    <!-- detalle -->
                    <div class="mb-3 col-lg-6">
                      <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Detalles del producto:</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="2"  onkeyup="mayus(this)"></textarea>
                      </div>                      
                    </div>
                    
                    <!-- codigo -->
                    <div class="mb-3 col-lg-2">
                      <div class="form-group">                      
                        <label for="recipient-name" class="col-form-label">Código Interno:</label>
                        <input type="text" class="form-control codigo" name="codigo" id="codigo" placeholder="Código Barras" onkeyup="mayus(this);" onchange="validarcodigo()">
                      </div>
                    </div>
                    <!-- stock -->
                    <div class="mb-3 col-lg-2">
                      <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Cantidad de Stock:</label>
                        <input type="number" class="form-control" name="stock" id="stock" min="0" step="0.01" placeholder="Stock" onkeypress="return totalc(event, this)" data-tooltip="Información de este campo" data-tooltip-more="El stock sera igual al saldo final y saldo inicial (stock = saldo final = saldo inicial)." data-tooltip-stickto="top" data-tooltip-maxwidth="500" data-tooltip-animate-function="foldin" data-tooltip-color="green">
                      </div>
                    </div>
                    <!-- limite de stok -->
                    <div class="mb-3 col-lg-2">
                      <div class="form-group">                      
                        <label for="recipient-name" class="col-form-label">Limite stock:</label>
                        <input type="number" class="form-control" name="limitestock" id="limitestock" max="999.99" min="0" step="0.01" placeholder="Limite de stock" >
                      </div>
                    </div>
                    
                    <!-- precio venta -->
                    <div class="mb-3 col-lg-2">
                      <div class="form-group">                      
                        <label for="recipient-name" class="col-form-label">Precio venta (S/.):</label>
                        <input type="number" class="form-control" name="valor_venta" id="valor_venta" min="0" step="0.01" onkeypress="return codigoi(event, this)" data-tooltip="Información de este campo" data-tooltip-more="El precio que se muestra en los ocmprobantes, incluye IGV." data-tooltip-stickto="top" data-tooltip-maxwidth="500" data-tooltip-animate-function="foldin" data-tooltip-color="green">                  
                      </div>
                    </div>
                    <!-- precio compra -->
                    <div class="mb-3 col-lg-2">
                      <div class="form-group">                      
                        <label for="costo_compra" class="col-form-label">Precio compra (S/.):</label>
                        <input type="number" class="form-control" name="costo_compra" id="costo_compra" min="0" step="0.01" onkeypress="return focussaldoi(event, this)" >
                      </div>
                    </div>
                    <!-- precio mayor -->
                    <div class="mb-3 col-lg-2">
                      <div class="form-group">
                        <label for="precio2" class="col-form-label">Precio por mayor (S/.):</label>
                        <input type="text" class="form-control" name="precio2" id="precio2" min="0" step="0.01"  placeholder="Précio por mayor">
                      </div>                      
                    </div>
                    <!-- precio distribuidor -->
                    <div class="mb-3 col-lg-2">
                      <div class="form-group">
                        <label for="precio3" class="col-form-label">Precio distribuidor (S/.):</label>
                        <input type="text" class="form-control" name="precio3" id="precio3" min="0" step="0.01"  placeholder="Précio distribuidor">
                      </div>                      
                    </div>                   
                    
                    <!-- imagen -->
                    <div class="mb-3 col-lg-4">
                      <div class="form-group">                      
                        <label for="imagenactual" class="col-form-label">Imagen del producto:</label>
                        <input type="file" class="form-control" name="imagen" id="imagen" value="" accept="image/*">
                        <input type="hidden" name="imagenactual" id="imagenactual">
                        
                      </div>
                      <hr>
                      <div class="" id="preview">
                        <img src="../files/articulos/simagen.png" width="150px" height="auto" id="imagenmuestra">
                      </div>
                    </div>
                  </div>
                  <!-- /.row -->
                </div>
                <!-- /.tab-pane -->
                
                <div class="tab-pane fade text-muted" id="sunat-tab-pane" role="tabpanel" aria-labelledby="sunat-tab" tabindex="0">
                  <div class="row">
                    <!-- cod-tributo -->
                    <div class="mb-3 col-lg-3">
                      <label for="codigott" class="col-form-label">Cód. tipo de tributo:</label>
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
                    <!-- tributo -->
                    <div class="mb-3 col-lg-3">
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
                    <!-- codigo-internacional -->
                    <div class="mb-3 col-lg-3">
                      <label for="recipient-name" class="col-form-label">Código internacional:</label>
                      <select name="codigointtt" id="codigointtt" class="form-control">
                        <option value="VAT" selected>VAT</option>
                        <option value="EXC">EXC</option>
                        <option value="FRE">FRE</option>
                        <option value="OTH">OTH</option>
                      </select>
                    </div>
                    <!-- nombre -->
                    <div class="mb-3 col-lg-3">
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
                  <!-- /.row -->
                </div>
                <!-- /.tab-pane -->

                <div class="tab-pane fade text-muted" id="proveedor-tab-pane" role="tabpanel" aria-labelledby="proveedor-tab" tabindex="0">
                  <div class="row">
                    <div class="mb-3 col-lg-4">
                      <label for="recipient-name" class="col-form-label">Proveedor:</label>
                      <input type="text" name="proveedor" id="proveedor" class="form-control">
                    </div>
                    <div class="mb-3 col-lg-4">
                      <label for="recipient-name" class="col-form-label">Código proveedor:</label>
                      <input type="text" class="form-control" name="codigo_proveedor" id="codigo_proveedor" placeholder="Código de proveedor" value="-" onkeyup="mayus(this)">
                    </div>
                    <div class="mb-3 col-lg-2">
                      <label for="recipient-name" class="col-form-label">Serie fac. compra:</label>
                      <input type="text" name="seriefaccompra" id="seriefaccompra" class="form-control">
                    </div>
                    <div class="mb-3 col-lg-2">
                      <label for="recipient-name" class="col-form-label">Número fac. compra:</label>
                      <input type="text" name="numerofaccompra" id="numerofaccompra" class="form-control">
                    </div>
                    <div class="mb-3 col-lg-4">
                      <label for="recipient-name" class="col-form-label">Fecha fac. compra:</label>
                      <input type="date" name="fechafacturacompra" id="fechafacturacompra" class="form-control" style="color:blue;">
                    </div>
                  </div>
                </div>

                <div class="tab-pane fade text-muted" id="otros-tab-pane" role="tabpanel" tabindex="0" aria-labelledby="otros-tab">
                  <div class="row">
                    <div class="mb-3 col-lg-4">
                      <label for="recipient-name" class="col-form-label">Portador:</label>
                      <input type="text" class="form-control" name="portador" id="portador" maxlength="5" onkeypress="return mer(event, this)">
                    </div>
                    <div class="mb-3 col-lg-4">
                      <label for="recipient-name" class="col-form-label">Peso: <small>KG</small></label>
                      <input type="text" class="form-control" name="merma" id="merma" maxlength="5" onkeypress="return preciov(event, this)">
                    </div>
                    <div class="mb-3 col-lg-4">
                      <label for="recipient-name" class="col-form-label">Código SUNAT:</label>
                      <input type="text" class="form-control" name="codigosunat" id="codigosunat" placeholder="Código SUNAT" onkeyup="mayus(this);" data-tooltip="Información de este campo" data-tooltip-more="Validar con el catálogo de productos que brinda SUNAT." data-tooltip-stickto="top" data-tooltip-maxwidth="500" data-tooltip-animate-function="foldin" data-tooltip-color="green">
                    </div>
                    <div class="mb-3 col-lg-4">
                      <label for="recipient-name" class="col-form-label">Cta. contable:</label>
                      <input type="text" class="form-control" name="ccontable" id="ccontable" placeholder="Cuenta contabe" data-tooltip="Información de este campo" data-tooltip-more="Opcional." data-tooltip-stickto="top" data-tooltip-maxwidth="500" data-tooltip-animate-function="foldin" data-tooltip-color="green">
                    </div>
                    <!-- NUEVOS CAMPOS PARA BOLSAS PLASTICAS  -->
                    <div class="mb-3 col-lg-4">
                      <label for="recipient-name" class="col-form-label">Cód. tributo ICBPER:</label>
                      <input type="text" class="form-control" name="cicbper" id="cicbper" placeholder="7152" onkeyup="mayus(this);">
                    </div>
                    <div class="mb-3 col-lg-4">
                      <label for="recipient-name" class="col-form-label">N. tributo ICBPER:</label>
                      <input type="text" class="form-control" name="nticbperi" id="nticbperi" placeholder="ICBPER" onkeyup="mayus(this);">
                    </div>
                    <div class="mb-3 col-lg-4">
                      <label for="recipient-name" class="col-form-label">Cód. tributo ICBPER OTH:</label>
                      <input type="text" class="form-control" name="ctticbperi" id="ctticbperi" placeholder="OTH" onkeyup="mayus(this);">
                    </div>
                    <div class="mb-3 col-lg-4">
                      <label for="recipient-name" class="col-form-label">Mto trib. ICBPER und:</label>
                      <input type="text" class="form-control" name="mticbperu" id="mticbperu" placeholder="0.10" onkeyup="mayus(this);">
                    </div>
                    <!-- NUEVOS CAMPOS PARA BOLSAS PLASTICAS  2-->
                    <div class="mb-3 col-lg-4">
                      <label for="recipient-name" class="col-form-label">Lote:</label>
                      <input type="text" name="lote" id="lote" class="form-control">
                    </div>                    
                    <div class="mb-3 col-lg-4">
                      <label for="recipient-name" class="col-form-label">Fecha de fabricación:</label>
                      <input type="date" name="fechafabricacion" id="fechafabricacion" class="form-control" style="color:blue;">
                    </div>
                    <div class="mb-3 col-lg-4">
                      <label for="recipient-name" class="col-form-label">Fecha de vencimiento:</label>
                      <input type="date" name="fechavencimiento" id="fechavencimiento" class="form-control" style="color:blue;">
                    </div>
                    <div class="mb-3 col-lg-4">
                      <label for="recipient-name" class="col-form-label">Procedencia:</label>
                      <input type="text" name="procedencia" id="procedencia" class="form-control">
                    </div>
                    <div class="mb-3 col-lg-4">
                      <label for="recipient-name" class="col-form-label">Fabricante:</label>
                      <input type="text" name="fabricante" id="fabricante" class="form-control">
                    </div>
                    <div class="mb-3 col-lg-4">
                      <label for="recipient-name" class="col-form-label">Registro Sanitario:</label>
                      <input type="text" name="registrosanitario" id="registrosanitario" class="form-control">
                    </div>
                    <div class="mb-3 col-lg-4">
                      <label for="recipient-name" class="col-form-label">Fecha ing. almacén:</label>
                      <input type="date" name="fechaingalm" id="fechaingalm" class="form-control" style="color:blue;">
                    </div>
                    <div class="mb-3 col-lg-4">
                      <label for="recipient-name" class="col-form-label">Fecha fin stock:</label>
                      <input type="date" name="fechafinalma" id="fechafinalma" class="form-control" style="color:blue;">
                    </div>
                  </div>
                  <!-- /.row -->
                </div>
                <!-- /.tab-pane -->
              </div>

              <div class="row"> 

                <div class="p-l-25px col-lg-12" id="barra_progress_articulo_div" style="display: none;">
                  <div  class="progress progress-lg custom-progress-3" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"> 
                    <div id="barra_progress_articulo" class="progress-bar" style="width: 0%"> <div class="progress-bar-value">0%</div> </div> 
                  </div>
                </div>

                <!-- Factor conversión: -->
                <input type="hidden" class="form-control" name="factorc" id="factorc" onkeypress=" return umventa(event, this)">               
                <!-- Saldo inicial (S/.): -->
                <input type="hidden" class="form-control" name="saldo_iniu" id="saldo_iniu" maxlength="500" placeholder="Saldo inicial" onBlur="calcula_valor_ini()"  onkeypress="return valori(event, this)" data-tooltip="Información de este campo" data-tooltip-more="Si es la primera vez que llena este campo poner el saldo final de su inventario físico. " data-tooltip-stickto="top" data-tooltip-maxwidth="500" data-tooltip-animate-function="foldin" data-tooltip-color="green">                
                <!-- Valor inicial (S/.): -->
                <input type="hidden" class="form-control" name="valor_iniu" id="valor_iniu" maxlength="500" placeholder="Valor inicial" value="0" onkeypress="return saldof(event, this)" data-tooltip="Información de este campo" data-tooltip-more="El valor inicial es el costo compra x saldo inicial." data-tooltip-stickto="top" data-tooltip-maxwidth="500" data-tooltip-animate-function="foldin" data-tooltip-color="green">                
                <!-- Saldo final (mts): -->
                <input type="hidden" class="form-control" name="saldo_finu" id="saldo_finu" maxlength="500" placeholder="Saldo final"  onkeypress="return valorf(event, this)" onBlur="sfinalstock()" data-tooltip="Información de este campo" data-tooltip-more="La primera vez en el registro será igual a saldo inicial (saldofinal=saldo inicial)." data-tooltip-stickto="top" data-tooltip-maxwidth="500" data-tooltip-animate-function="foldin" data-tooltip-color="green">                
                <!-- Valor final (S/.): -->
                <input type="hidden" class="form-control" name="valor_finu" id="valor_finu" maxlength="500" placeholder="Valor Final"  onkeypress="return st(event, this)" data-tooltip="Información de este campo" data-tooltip-more="El valor final es igual al valor incial (valor final=valor inicial)." data-tooltip-stickto="top" data-tooltip-maxwidth="500" data-tooltip-animate-function="foldin" data-tooltip-color="green">                
                <!-- Conversión um venta: -->
                <input type="hidden" class="form-control" name="fconversion" id="fconversion" data-tooltip="Cantidad según factor de conversión por stock actual." data-tooltip-stickto="top" data-tooltip-maxwidth="500" data-tooltip-animate-function="foldin" data-tooltip-color="green" readonly>                
                <!-- Total compras (mts): -->
                <input type="hidden" class="form-control" name="comprast" id="comprast" onkeypress="return totalv(event, this)" placeholder="No se llena" readonly data-tooltip="Información de este campo" data-tooltip-more="Este campo no se llena." data-tooltip-stickto="top" data-tooltip-maxwidth="500" data-tooltip-animate-function="foldin" data-tooltip-color="green">                
                <!-- Total ventas (mts): -->
                <input type="hidden" class="form-control" name="ventast" id="ventast" onkeypress="return porta(event, this)" placeholder="No se llena" readonly data-tooltip="Información de este campo" data-tooltip-more="Este campo no se llena." data-tooltip-stickto="top" data-tooltip-maxwidth="500" data-tooltip-animate-function="foldin" data-tooltip-color="green">                
                
              </div>
              <button type="submit" style="display: none;" id="submit-form-articulo">Submit</button>
            </form>
          </div>
          <div class="eventoCodigoBarra" hidden>
            <button class="btn btn-success btn-sm" type="button" onclick="generarbarcode()">Mostrar codigo de barra</button>
            <button class="btn btn-success btn-sm" type="button" onclick="generarcodigonarti()">Asignar codigo automático</button>
            <button class="btn btn-info btn-sm" type="button" onclick="imprimir()"> <i class="fa fa-print"></i> Imprimir codigos</button>
            <input type="hidden" name="stockprint" id="stockprint">
            <input type="hidden" name="codigoprint" id="codigoprint">
            <input type="hidden" name="precioprint" id="precioprint">
            <div id="print"> <svg id="barcode"></svg> </div>
          </div>
          <div class="modal-footer">
            <button onclick="limpiar_form_articulo()" type="button" class="btn btn-danger" data-bs-dismiss="modal">
              <i class="bx bx-x d-block d-sm-none"></i> <span class="d-none d-sm-block">Cancelar</span>
            </button>
            <button id="guardar_registro_articulo" type="submit" class="btn btn-primary ml-1">
              <i class="bx bx-check d-block d-sm-none"></i>  <span class="d-none d-sm-block">Agregar</span>
            </button>
          </div>
          
        </div>
      </div>
    </div>    

    <!--  MODAL - IMPORTAR PRODUCTO -->
    <div class="modal fade text-left" id="modal-importar-articulo" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="myModalLabel1">Importa tus artículos</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="alert alert-danger border border-danger mb-3 p-2"> 
              <div class="d-flex align-items-start"> 
                <div class="me-2"> 
                  <svg class="flex-shrink-0 svg-danger" xmlns="http://www.w3.org/2000/svg" height="1.5rem" viewBox="0 0 24 24" width="1.5rem" fill="#000000"> <path d="M0 0h24v24H0V0z" fill="none"></path><path d="M11 7h2v2h-2zm0 4h2v6h-2zm1-9C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"></path>                  </svg>
                </div> 
                <div class="text-danger w-100"> 
                  <div class="fw-semibold d-flex justify-content-between">Para importar productos masivamente.
                    <button type="button" class="btn-close p-0" data-bs-dismiss="alert" aria-label="Close"><i class="bi bi-x"></i></button>
                  </div> 
                  <div class="fs-12 op-8 mb-1">Tiene que tener encuenta los siguientes columnas y el orden:</div>    
                  <div class="row fs-12">
                    <div class="col-lg-4 px-0">                      
                      <span>1. Codigo</span><br> <span>2. Nombre producto</span><br> <span>2. Alias producto</span><br> <span>3. Descripcion</span>                       
                    </div>
                    <div class="col-lg-4 px-0"> 
                    <span>4. Categoria</span><br> <span>5. Marca</span><br> <span>6. Precio compra</span><br>  <span>7. Precio venta</span>
                    </div>
                    <div class="col-lg-4 px-0"> 
                    <span>8. Precio por mayor</span><br>  <span>9. Stock</span><br> <span>10. Tipo (productos)</span><br> <span>11. Almacen</span> 
                    </div>
                  </div>  
                  <div class="d-inline-flex mt-2"> 
                    <button type="button" class="btn btn-outline-danger font-size-10px py-1 d-inline-block m-r-5px" data-bs-dismiss="alert" aria-label="Close">Cerrar</button> 
                    <a href="../assets/excel/plantilla-productos.xls" class="btn btn-success font-size-10px py-1 d-inline-block" download="Plantilla-producto">Descargar plantilla</a> 
                  </div>           
                </div> 
              </div> 
            </div>
            <form class="form" name="form-importar-articulo" id="form-importar-articulo" method="POST" enctype="multipart/form-data">
              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group">
                    <label class="form-label" id="upload-container">Cargar excel:</label>
                    <input class="form-control" id="upload_file_articulo" name="upload_file_articulo" type="file" accept=".xls,.xlsx, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel" required>
                  
                    <div id="files-list-container"></div>
                  </div>
                </div>
                <div class="p-l-25px col-lg-12" id="barra_progress_imp_articulo_div" style="display: none;">
                  <div  class="progress progress-lg custom-progress-3" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"> 
                    <div id="barra_progress_imp_articulo" class="progress-bar" style="width: 0%"> <div class="progress-bar-value">0%</div> </div> 
                  </div>
                </div> 
              </div>              
              <button type="submit" style="display: none;" id="submit-form-import-articulo">Submit</button>             
            </form>
          </div>
          <div class="modal-footer">
            <button  type="button" class="btn btn-danger" data-bs-dismiss="modal">
              <i class="bx bx-x d-block d-sm-none"></i>
              <span class="d-none d-sm-block">Cancelar</span>
            </button>
            <button id="guardar_registro_import_articulo" type="submit" class="btn btn-primary ml-1">
              <i class="bx bx-check d-block d-sm-none"></i>
              <span class="d-none d-sm-block">Agregar</span>
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