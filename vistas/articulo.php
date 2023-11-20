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
          <h1>Productos <button class="btn btn-primary btn-sm" onclick="mostrarform(true);  generarCodigoAutomatico('PR');" data-bs-toggle="modal" data-bs-target="#modalAgregarProducto">Agregar</button> <button class="btn btn-success btn-sm" id="importarDatos" data-bs-toggle="modal" data-bs-target="#importararticulos">Importar Artículos</button>
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
                    <select class="form-control" name="filtro_idalmacen" id="filtro_idalmacen" style="width: 100%;" onchange="cargando_search(); delay(function(){filtros()}, 100 );">
                    </select>
                  </div>
                  <div class="col-lg-4">                    
                    <select class="form-control" name="filtro_idfamilia" id="filtro_idfamilia" style="width: 100%;" onchange="cargando_search(); delay(function(){filtros()}, 100 );">
                    </select>
                  </div>
                  <div class="col-lg-4">                    
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
                    <!-- almacen -->
                    <div class="mb-3 col-lg-3">
                      <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Almacen:</label>
                        <input type="hidden" name="idarticulo" id="idarticulo">
                        <input type="hidden" name="idempresa" id="idempresa" value="<?php echo $_SESSION['idempresa']; ?>">
                        <select class="form-control" name="idalmacen" id="idalmacen" required onchange="focusfamil()">
                        </select>
                      </div>                     
                    </div>
                    <!-- categoria -->
                    <div class="mb-3 col-lg-3">
                      <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Categoria:</label>
                        <select class="form-control" name="idfamilia" id="idfamilia" required>
                        </select>
                      </div>                      
                    </div>
                    <!-- Tipo -->
                    <div class="mb-3 col-lg-3">
                      <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Tipo:</label>
                        <select class="form-control" name="tipoitem" id="tipoitem" onchange="focuscodprov()">
                          <option value="productos" selected > PRODUCTO </option> <!-- <option value="servicios">SERVICIO</option> -->
                        </select>
                      </div>                      
                    </div>
                    <!-- unidad medida -->
                    <div class="mb-3 col-lg-3">
                      <div class="form-group">
                        <label for="recipient-name" class="col-form-label">U. medida:</label>
                        <select class="form-control" name="umedidacompra" id="umedidacompra" required onchange="cinicial()">
                        </select>
                      </div>                      
                    </div>
                    <div class="mb-3 col-lg-3" hidden>
                      <div class="form-group">
                        <label for="recipient-name" class="col-form-label">U. medida venta:</label>
                        <select class="form-control" name="unidad_medida" id="unidad_medida" onchange="costoco()">
                        </select>
                      </div>                      
                    </div>
                    <!-- nombre -->
                    <div class="mb-3 col-lg-4">
                      <div class="form-group">                      
                        <label for="recipient-name" class="col-form-label">Nombre / Descripción:</label>
                        <input type="text" class="form-control" name="nombre" id="nombre" maxlength="500" placeholder="Nombre" required="true" onkeyup="mayus(this);" onkeypress=" return limitestockf(event, this)">
                      </div>
                    </div>
                    <!-- detalle -->
                    <div class="mb-3 col-lg-4">
                      <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Detalles del producto:</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="1" cols="70" onkeyup="mayus(this)"></textarea>
                      </div>                      
                    </div>
                    <!-- stock -->
                    <div class="mb-3 col-lg-2">
                      <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Cantidad de Stock:</label>
                        <input type="text" class="form-control" name="stock" id="stock" maxlength="500" placeholder="Stock" required="true" onkeypress="return totalc(event, this)" data-tooltip="Información de este campo" data-tooltip-more="El stock sera igual al saldo final y saldo inicial (stock = saldo final = saldo inicial)." data-tooltip-stickto="top" data-tooltip-maxwidth="500" data-tooltip-animate-function="foldin" data-tooltip-color="green">
                      </div>
                    </div>
                    <!-- limite de stok -->
                    <div class="mb-3 col-lg-2">
                      <div class="form-group">                      
                        <label for="recipient-name" class="col-form-label">Limite stock:</label>
                        <input type="number" class="form-control" name="limitestock" id="limitestock" max="999.99" min="0" step="0.01" placeholder="Limite de stock" /*onkeypress="return limitest(event, this)"*/ >
                      </div>
                    </div>
                    <!-- codigo -->
                    <div class="mb-3 col-lg-2">
                      <div class="form-group">                      
                        <label for="recipient-name" class="col-form-label">Código Interno:</label>
                        <input type="text" class="form-control codigo" name="codigo" id="codigo" placeholder="Código Barras" required="true" onkeyup="mayus(this);" onchange="validarcodigo()">
                      </div>
                    </div>
                    <!-- precio venta -->
                    <div class="mb-3 col-lg-2">
                      <div class="form-group">                      
                        <label for="recipient-name" class="col-form-label">Precio venta (S/.):</label>
                        <input type="text" class="form-control" name="valor_venta" id="valor_venta" onkeypress="return codigoi(event, this)" step="0.01" data-tooltip="Información de este campo" data-tooltip-more="El precio que se muestra en los ocmprobantes, incluye IGV." data-tooltip-stickto="top" data-tooltip-maxwidth="500" data-tooltip-animate-function="foldin" data-tooltip-color="green">                  
                      </div>
                    </div>
                    <!-- precio mayor -->
                    <div class="mb-3 col-lg-2">
                      <div class="form-group">
                        <label for="precio2" class="col-form-label">Precio por mayor:</label>
                        <input type="text" class="form-control" name="precio2" id="precio2" placeholder="Précio por mayor">
                      </div>                      
                    </div>
                    <!-- precio distribuidor -->
                    <div class="mb-3 col-lg-2">
                      <div class="form-group">
                        <label for="precio3" class="col-form-label">Precio distribuidor:</label>
                        <input type="text" class="form-control" name="precio3" id="precio3" placeholder="Précio distribuidor">
                      </div>                      
                    </div>
                    <!-- precio compra -->
                    <div class="mb-3 col-lg-2">
                      <div class="form-group">                      
                        <label for="costo_compra" class="col-form-label">Precio compra:</label>
                        <input type="text" class="form-control" name="costo_compra" id="costo_compra" maxlength="500" onkeypress="return focussaldoi(event, this)" required>
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
                    <!-- imagen -->
                    <div class="mb-3 col-lg-2">
                      <label for="imagenactual" class="col-form-label">Imagen del producto:</label>
                      <input type="file" class="form-control" name="imagen" id="imagen" value="" accept="image/*">
                      <input type="hidden" name="imagenactual" id="imagenactual">
                      <img src="../files/articulos/simagen.png" width="150px" height="120px" id="imagenmuestra">
                      <hr>
                      <div class="" id="preview"> </div>
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
                      <input type="text" class="form-control" name="codigo_proveedor" id="codigo_proveedor" placeholder="Código de proveedor" required="" value="-" onkeyup="mayus(this)">
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
                
                
                <!-- <div class="mb-3 col-lg-4">
                  <input type="checkbox" id="agregarCompra" name="" value="">
                  <label for="" style="position: relative; bottom: 5px;">Adjuntar detalles compra</label>
                </div>
                <div class="mb-3 col-lg-4">
                  <input type="checkbox" id="agregarOtrosCampos" name="" value="">
                  <label for="" style="position: relative; bottom: 5px;">Más opciones de item</label>
                </div>

                <div class="row" id="mostrarCompra" style="margin: 0 auto; display: none;">
                  <div style="margin: 0 auto;" class="row">                    
                  </div>
                </div>
                <div class="row" id="mostraOtroscampos" style="margin: 0 auto; display: none;">
                  <div style="margin: 0 auto;" class="row">                    
                  </div>
                </div> -->

                <div class="mb-3 col-lg-4" hidden>
                  <label for="recipient-name" class="col-form-label">Factor conversión:</label>
                  <input type="text" class="form-control" name="factorc" id="factorc" onkeypress=" return umventa(event, this)">
                </div>
                <div class="mb-3 col-lg-4" hidden>
                  <label for="recipient-name" class="col-form-label">Saldo inicial (S/.):</label>
                  <input type="text" class="form-control" name="saldo_iniu" id="saldo_iniu" maxlength="500" placeholder="Saldo inicial" onBlur="calcula_valor_ini()" required="false" onkeypress="return valori(event, this)" data-tooltip="Información de este campo" data-tooltip-more="Si es la primera vez que llena este campo poner el saldo final de su inventario físico. " data-tooltip-stickto="top" data-tooltip-maxwidth="500" data-tooltip-animate-function="foldin" data-tooltip-color="green">
                </div>
                <div class="mb-3 col-lg-4" hidden>
                  <label for="recipient-name" class="col-form-label">Valor inicial (S/.):</label>
                  <input value="0" type="text" class="form-control" name="valor_iniu" id="valor_iniu" maxlength="500" placeholder="Valor inicial" required="false" onkeypress="return saldof(event, this)" data-tooltip="Información de este campo" data-tooltip-more="El valor inicial es el costo compra x saldo inicial." data-tooltip-stickto="top" data-tooltip-maxwidth="500" data-tooltip-animate-function="foldin" data-tooltip-color="green">
                </div>
                <div class="mb-3 col-lg-4" hidden>
                  <label for="recipient-name" class="col-form-label">Saldo final (mts):</label>
                  <input type="text" class="form-control" name="saldo_finu" id="saldo_finu" maxlength="500" placeholder="Saldo final" required="false" onkeypress="return valorf(event, this)" onBlur="sfinalstock()" data-tooltip="Información de este campo" data-tooltip-more="La primera vez en el registro será igual a saldo inicial (saldofinal=saldo inicial)." data-tooltip-stickto="top" data-tooltip-maxwidth="500" data-tooltip-animate-function="foldin" data-tooltip-color="green">
                </div>
                <div class="mb-3 col-lg-4" hidden>
                  <label for="recipient-name" class="col-form-label">Valor final (S/.):</label>
                  <input type="text" class="form-control" name="valor_finu" id="valor_finu" maxlength="500" placeholder="Valor Final" required="false" onkeypress="return st(event, this)" data-tooltip="Información de este campo" data-tooltip-more="El valor final es igual al valor incial (valor final=valor inicial)." data-tooltip-stickto="top" data-tooltip-maxwidth="500" data-tooltip-animate-function="foldin" data-tooltip-color="green">
                </div>
                <div class="mb-3 col-lg-4" hidden>
                  <label for="recipient-name" class="col-form-label">Conversión um venta:</label>
                  <input type="text" class="form-control" name="fconversion" id="fconversion" data-tooltip="Cantidad según factor de conversión por stock actual." data-tooltip-stickto="top" data-tooltip-maxwidth="500" data-tooltip-animate-function="foldin" data-tooltip-color="green" readonly>
                </div>
                <div class="mb-3 col-lg-4" hidden>
                  <label for="recipient-name" class="col-form-label">Total compras (mts):</label>
                  <input type="text" class="form-control" name="comprast" id="comprast" onkeypress="return totalv(event, this)" placeholder="No se llena" readonly data-tooltip="Información de este campo" data-tooltip-more="Este campo no se llena." data-tooltip-stickto="top" data-tooltip-maxwidth="500" data-tooltip-animate-function="foldin" data-tooltip-color="green">
                </div>
                <div class="mb-3 col-lg-4" hidden>
                  <label for="recipient-name" class="col-form-label">Total ventas (mts):</label>
                  <input type="text" class="form-control" name="ventast" id="ventast" onkeypress="return porta(event, this)" placeholder="No se llena" readonly data-tooltip="Información de este campo" data-tooltip-more="Este campo no se llena." data-tooltip-stickto="top" data-tooltip-maxwidth="500" data-tooltip-animate-function="foldin" data-tooltip-color="green">
                </div>
                
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
            <button onclick="cancelarform()" type="button" class="btn btn-danger" data-bs-dismiss="modal">
              <i class="bx bx-x d-block d-sm-none"></i>
              <span class="d-none d-sm-block">Cancelar</span>
            </button>
            <button id="guardar_registro_articulo" type="submit" class="btn btn-primary ml-1">
              <i class="bx bx-check d-block d-sm-none"></i>
              <span class="d-none d-sm-block">Agregar</span>
            </button>
          </div>
          
        </div>
      </div>
    </div>    

    <!--  MODAL - IMPORTAR PRODUCTO -->
    <div class="modal fade text-left" id="importararticulos" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
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
                      <span>1. Codigo</span><br> <span>2. Categoria</span><br> <span>3. Nombre producto</span><br> <span>4. Descripcion</span>                           
                    </div>
                    <div class="col-lg-4 px-0"> 
                      <span>5. Precio compra</span><br> <span>6. Marca</span><br> <span>7. Precio venta</span><br> <span>8. Stock</span><br>
                    </div>
                    <div class="col-lg-4 px-0"> 
                      <span>9. Tipo (productos)</span><br> <span>10. Almacen</span> 
                    </div>
                  </div>  
                  <div class="d-inline-flex mt-2"> 
                    <button type="button" class="btn btn-outline-danger font-size-10px py-1 d-inline-block m-r-5px" data-bs-dismiss="alert" aria-label="Close">Cerrar</button> 
                    <a href="../assets/excel/plantilla-productos.xls" class="btn btn-success font-size-10px py-1 d-inline-block" download="Plantilla">Descargar plantilla</a> 
                  </div>           
                </div> 
              </div> 
            </div>
            <form class="form" name="formularioImportar" id="formularioImportar" method="POST" action="importar.php" enctype="multipart/form-data">
              <div class="row">
                <label class="form__container" id="upload-container">
                  <input class="form-control" id="upload-files" name="dataArticulo" type="file" style="position: relative;max-width: 420px;width: 100%; ">
                </label>
                <div id="files-list-container"></div>
              </div>
              <div class="modal-footer">
                <button onclick="cancelarform()" type="button" class="btn btn-danger" data-bs-dismiss="modal">
                  <i class="bx bx-x d-block d-sm-none"></i>
                  <span class="d-none d-sm-block">Cancelar</span>
                </button>
                <button id="btnGuardarImportacion" type="submit" class="btn btn-primary ml-1">
                  <i class="bx bx-check d-block d-sm-none"></i>
                  <span class="d-none d-sm-block">Agregar</span>
                </button>
              </div>
            </form>
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

    <script>
      // const multipleEvents = (element, eventNames, listener) => {
      //     const events = eventNames.split(' ');

      //     events.forEach(event => {
      //         element.addEventListener(event, listener, false);
      //     });
      // };

      // const fileUpload = () => {
      //     const INPUT_FILE = document.querySelector('#upload-files');
      //     const INPUT_CONTAINER = document.querySelector('#upload-container');

      //     multipleEvents(INPUT_FILE, 'click dragstart dragover', () => {
      //         INPUT_CONTAINER.classList.add('active');
      //     });

      //     multipleEvents(INPUT_FILE, 'dragleave dragend drop change', () => {
      //         INPUT_CONTAINER.classList.remove('active');
      //     });

      //     INPUT_FILE.addEventListener('change', () => {
      //         const files = [...INPUT_FILE.files];

      //         if (files.length > 0) {
      //             const file = files[0];
      //             const fileName = file.name;
      //             const fileExtension = fileName.split(".").pop().toLowerCase();

      //             // Verifica las extensiones
      //             if (['xls', 'xlsx', 'xlsm', 'csv'].includes(fileExtension)) {
      //                 INPUT_CONTAINER.textContent = "";
      //                 const iconHTML = `<img src="../files/iconos/excel.png" alt="Icono Excel" class="icon-excel">`;
      //                 const content = `
      //                     <div class="form__files-container">
      //                         ${iconHTML}
      //                         <span class="form__text">${fileName}</span>
      //                         <div class="barra-cargado"></div>
      //                     </div>
      //                 `;

      //                 INPUT_CONTAINER.insertAdjacentHTML('beforeEnd', content);
      //             } else {
      //                 // Usamos Swal.fire en lugar de alert
      //                 Swal.fire({
      //                     icon: 'error',
      //                     title: 'Error',
      //                     text: 'Por favor, selecciona un archivo válido de tipo Excel.'
      //                 });
      //                 INPUT_FILE.value = '';
      //             }
      //         } else {
      //             INPUT_CONTAINER.textContent = "Elija o Arrastre su Archivo";
      //         }
      //     });
      // };

      // fileUpload();
    </script>
  <?php
  } else {
    require 'noacceso.php';
  }
  require 'footer.php';
  ?>
  <!-- <script type="text/javascript" src="../public/js/JsBarcode.all.min.js"></script> -->
  <script>
    document.getElementById('formularioImportar').addEventListener('submit', function(e) {
      e.preventDefault();
      const data = new FormData(this);
      fetch(this.action, { method: 'POST', body: data  })
      .then(response => response.json())
      .then(data => {
        console.log(data); console.log(data.success);
        if (data.success == true) {
          Swal.fire({  icon: 'success',  title: '¡Éxito!',  text: 'Los datos se han importado correctamente.',  showConfirmButton: true, timer: 3500  });
          tabla.ajax.reload();
          listar();
          $('#importararticulos').modal('hide');
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: data.message || 'Ocurrió un error al importar los datos.' });
        }
      }).catch(err => {
        Swal.fire({ icon: 'error', title: 'Error', text: 'Ocurrió un error al enviar el archivo.' });
      });
    });

    $('#importararticulos').on('hidden.bs.modal', function() {
      $('#upload-files').val(''); // Limpiar el input del archivo
    });

    $('button[data-bs-dismiss="modal"]').click(function() {
      $('#upload-files').val(''); // Limpiar el input del archivo
    });


    // Obtenemos el input
    var inputFile = document.getElementById("upload-files");
    // Definimos las extensiones permitidas
    var allowedExtensions = ["xls", "xlsx", "xlsm", "csv"];

    // Agregamos un evento de cambio al input
    inputFile.addEventListener("change", function() {
      // Obtenemos el archivo seleccionado
      var file = this.files[0];
      // Validamos la extensión del archivo
      if (!allowedExtensions.includes(file.name.split(".").pop())) {
        // Mostramos un swal de error
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "Por favor, selecciona un archivo válido de tipo Excel.",
        });
        // Eliminamos el archivo del input
        inputFile.value = null;
      }
    });
  </script>
  <script type="text/javascript" src="../public/js/jquery.PrintArea.js"></script>
  <script type="text/javascript" src="scripts/articulo.js"></script>
  <script src="../public/js/html5tooltips.js"></script>
<?php
}
ob_end_flush();
?>