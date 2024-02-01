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

    <style>
      input[type=number].hidebutton::-webkit-inner-spin-button,
      input[type=number].hidebutton::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
      }
    </style>
    <link href="../public/css/html5tooltips.css" rel="stylesheet">
    <link href="../public/css/html5tooltips.animation.css" rel="stylesheet">

    <!--Contenido-->
    <!-- Content Wrapper. Contains page content -->
    <div class="">
      <!-- Main content -->
      <section class="">
        <!-- Encabezado Nota de Venta -->
        <div class="content-header">
          <h1>Nota de venta
          <button class="btn btn-secondary btn-sm" id="btnagregar" onclick="mostrarform(true); limpiar();">Nuevo</button>
          <button class="btn btn-success btn-sm" id="refrescartabla" onclick="refrescartabla()">Refrescar</button>
          </h1>
        </div>
        <!-- Fin Encabezado -->

        <!-- centro -->
        <div class="row">
          <div class="col-md-12">
            <div class="automaticonotapedido" hidden>
              OFF <input checked type="checkbox" name="chk1" id="chk1" onclick="pause()" data-toggle="tooltip" title="Mostrar estado de enviados a SUNAT"> ON
            </div>

            <!-- Tabla Principal -->
            <div class="table-responsive" id="listadoregistros">
              <table id="tbllistado" class="table table-striped" style="font-size: 14px; max-width: 100% !important;">
                <thead style="text-align:center;">
                  <th>Opciones</th>
                  <!--  <th><i class="fa fa-send"></i></th> -->
                  <th>Fecha</th>
                  <th>Cliente</th>
                  <th>Vendedor</th>
                  <th>Comprobante</th>
                  <th>Total Articulo</th>
                  <th>Adelanto</th>
                  <th>Faltante</th>
                  <!-- <th>Total</th> -->
                  <th>Estado</th>
                </thead>

                <tbody style="text-align:center;">
                </tbody>
              </table>
            </div>
            <!-- Fin Tabla -->

            <!-- Formulario Notas de Venta -->
            <div class="panel-body" id="formularioregistros" style="display: none;">
              <form name="formulario" id="formulario" method="POST" autocomplete="off">
              <div class="row">
                <div class="col-md-3">
                  <div class="card">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-lg-12 text-danger no-tienes-permiso-notapedido" ></div>
                        <div class="mb-3 col-lg-6">
                          <label for="serie" class="col-form-label">Serie: <span class="charge-serie text-danger"><i class="fas fa-spinner fa-pulse"></i></span></label>
                          <SELECT class="form-control" name="serie" id="serie" onchange="incremetarNum()"></SELECT>
                          <input type="hidden" name="idnumeracion" id="idnumeracion">
                          <input type="hidden" name="SerieReal" id="SerieReal">
                        </div>
                        <div class="mb-3 col-lg-6">
                          <label for="numero_notapedido" class="col-form-label">Número: <span class="charge-numero text-danger"><i class="fas fa-spinner fa-pulse"></i></span></label>
                          <input type="text" name="numero_notapedido" id="numero_notapedido" class="form-control" required="true" readonly>
                        </div>                          
                        <!--Campos para guardar comprobante Factura-->
                        <input type="hidden" name="idboleta" id="idboleta"> 
                        <input type="hidden" name="firma_digital_36" id="firma_digital_36" value="44477344">
                        <!--Datos de empresa -->
                        <input type="hidden" name="idempresa" id="idempresa" value="<?php echo $_SESSION['idempresa']; ?>">
                        <input type="hidden" name="tipo_documento_06" id="tipo_documento_06" value="03">
                        <input type="hidden" name="numeracion_07" id="numeracion_07" value="">
                        <!--Datos del cliente-->
                        <input type="hidden" name="idcliente" id="idcliente">
                        <input type="hidden" name="tipo_documento_cliente" id="tipo_documento_cliente" value="0">
                        <!--Datos del cliente-->
                        <!--Datos de impuestos-->
                        <input type="hidden" name="codigo_tipo_15_1" id="codigo_tipo_15_1" value="1001">
                        <input type="hidden" name="codigo_tributo_h" id="codigo_tributo_h">
                        <input type="hidden" name="nombre_tributo_h" id="nombre_tributo_h">
                        <input type="hidden" name="codigo_internacional_5" id="codigo_internacional_5" value="">
                        <input type="hidden" name="tipo_documento_25_1" id="tipo_documento_25_1" value="">
                        <input type="hidden" name="codigo_leyenda_26_1" id="codigo_leyenda_26_1" value="1000">
                        <input type="hidden" name="version_ubl_37" id="version_ubl_37" value="2.0">
                        <input type="hidden" name="version_estructura_38" id="version_estructura_38" value="1.0">
                        <input type="hidden" name="tasa_igv" id="tasa_igv" value="0.18">
                        <!--Fin de campos-->
                        <input type="hidden" name="codigo_precio_14_1" id="codigo_precio" value="01">
                        <!--DETALLE-->
                        <input type="hidden" name="hora" id="hora">
                        <!--DETALLE-->
                        <div class="mb-3 col-lg-6">
                          <label for="fecha_emision_01" class="col-form-label">Fe. emisión:</label>
                          <input type="date" disabled="true" style="font-size: 12pt;" class="form-control" name="fecha_emision_01" id="fecha_emision_01" disabled="true" required="true" onchange="focusTdoc()">
                        </div>
                        <div class="mb-3 col-lg-6">
                          <label for="fechavenc" class="col-form-label">F. vencimiento:</label>
                          <input type="date" class="form-control" name="fechavenc" id="fechavenc" required="true" min="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="mb-3 col-lg-6">
                          <label for="tipo_moneda_24" class="col-form-label">Moneda:</label>
                          <select class="form-control" name="tipo_moneda_24" id="tipo_moneda_24" onchange="tipodecambiosunat();">
                            <option value="PEN" selected="true">PEN</option>
                            <option value="USD">USD</option>
                          </select>
                        </div>
                        <div class="mb-3 col-lg-6">
                          <label for="tcambio" class="col-form-label">T. camb:</label>
                          <input type="text" name="tcambio" id="tcambio" class="form-control" readonly="true">
                        </div>
                        <div class="mb-3 col-lg-6">
                          <label for="tiponota" class="col-form-label">Tipo de items:</label>
                          <select class="form-control" name="tiponota" id="tiponota" onchange="cambiarlistado()">                              
                            <option value="st">SELECCIONE TIPO DE </option>
                            <option value="productos" selected="true">PRODUCTOS</option>
                            <option value="servicios">SERVICIOS</option>
                          </select>
                        </div>
                        <div class="mb-3 col-lg-6">
                          <label for="tipo_doc_ide" class="col-form-label">Documento: <span class="charge-doc-identidad text-danger"><i class="fas fa-spinner fa-pulse"></i></span></label>
                          <select class="form-control" name="tipo_doc_ide" id="tipo_doc_ide" onchange="focusI()">
                          </select>
                        </div>                          
                        <div class="mb-3 col-lg-12">
                          <label for="numero_documento" class="col-form-label">Nro (Presione Enter):</label>
                          <div class="input-group mb-1">                              
                            <input type="text" class="form-control" name="numero_documento" id="numero_documento" placeholder="Número" value="-" required="true" onkeypress="agregarClientexDoc(event)" onchange="agregarClientexDocCha();">                            
                            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Buscar Reniec/Sunat" style="margin: 0 auto;"><i class="fas fa-search"></i></button>
                          </div>
                          <div id="suggestions"> </div>
                        </div>
                        <div class="mb-3 col-lg-12">
                          <label for="razon_social" class="col-form-label">Nombres y apellidos:</label>
                          <input type="text" class="form-control" name="razon_social" id="razon_social" maxlength="50" placeholder="NOMBRE COMERCIAL" width="50x" value="-" required="true" onkeyup="mayus(this);" onkeypress="focusDir(event)" onblur="quitasuge2()">
                          <div id="suggestions2">
                          </div>
                        </div>
                        <div class="mb-3 col-lg-12">
                          <label for="domicilio_fiscal" class="col-form-label">Dirección:</label>
                          <input type="text" class="form-control" name="domicilio_fiscal" id="domicilio_fiscal" value="-" onkeyup="mayus(this);" placeholder="Dirección" onkeypress="agregarArt(event)">
                        </div>
                        <div class="mb-3 col-lg-6">
                          <label for="vendedorsitio" class="col-form-label">Vendedor:</label>
                          <select autofocus name="vendedorsitio" id="vendedorsitio" class="form-control">
                          </select>
                        </div>
                        <div hidden class="mb-3 col-lg-6">
                          <label for="guia_remision_25" class="col-form-label">Nro Guia:</label>
                          <input type="text" name="guia_remision_25" id="guia_remision_25" class="form-control" placeholder="NRO DE GUÍA">
                        </div>
                        <div class="mb-3 col-lg-6">
                          <label for="codigo_tributo_18_3" class="col-form-label">Impuesto:</label>
                          <select class="form-control" name="codigo_tributo_18_3" id="codigo_tributo_18_3" onchange="tributocodnon()">TRIBUTO</select>
                        </div>
                        <div hidden class="mb-3 col-lg-6">
                          <label for="nroreferencia" class="col-form-label">Nro
                            transferencia:</label>
                          <input type="text" name="nroreferencia" id="nroreferencia" class="form-control" style="color: blue;" placeholder="N° Operación">
                        </div>
                        <div class="mt-2 mb-3 col-lg-12">
                          <textarea name="descripcion_leyenda_26_2" id="descripcion_leyenda_26_2" cols="5" rows="3" class="form-control" placeholder="Observaciones"></textarea>
                        </div>
                        <div class="mb-3 col-lg-12">
                          <label for="tipopago" class="col-form-label">Tipo de pago:</label>
                          <select class="form-control" name="tipopago" id="tipopago" onchange="contadocredito()">
                            <option value="nn">SELECCIONE LA FORMA DE PAGO</option>
                            <option value="Contado" selected>CONTADO</option>
                            <option value="Credito">CRÉDITO</option>
                            <option value="Yape">YAPE</option>
                            <option value="Tarjeta">TARJETA</option>
                            <option value="Transferencia">TRANSFERENCIA</option>
                            <option value="Plin">PLIN</option>
                          </select>
                        </div>
                        <div id="tipopagodiv" style="display: none;">
                          <div class="mb-3 col-lg-12">
                            <label for="ccuotas" class="col-form-label">N° de cuotas:</label>
                            <div class="input-group">
                              <span style="cursor:pointer;" class="input-group-text" data-bs-toggle="modal" title="mostrar cuotas" data-bs-target="#modalcuotas" id="basic-addon1">&#9769;</span>
                              <span style="cursor:pointer;" class="input-group-text" onclick="borrarcuotas()" title="Editar cuotas">&#10000;</span>
                              <input name="ccuotas" id="ccuotas" onchange="focusnroreferencia()" class="form-control" value="1" onkeypress="return NumCheck(event, this)">
                            </div>
                          </div>
                        </div>
                        <div class="form-group col-lg-2 col-md-6 col-sm-6 col-xs-12" hidden>
                          <img src="../files/articulos/tarjetadc.png" data-toggle="tooltip" title="Pago por tarjeta">
                          <input type="checkbox" name="tarjetadc" id="tarjetadc" onclick="activartarjetadc();">
                          <input type="hidden" name="tadc" id="tadc">
                        </div>
                        <div class="form-group col-lg-2 col-md-6 col-sm-6 col-xs-12" hidden>
                          <img src="../files/articulos/transferencia.png" data-toggle="tooltip" title="Pago por transferencia"> <input type="checkbox" name="transferencia" id="transferencia" onclick="activartransferencia();">
                          <input type="hidden" name="trans" id="trans">
                        </div>

                        <div class="modal fade text-left" id="modalcuotas" tabindex="-1" role="dialog" aria-labelledby="modalcuotas" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-scrollable" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="modalcuotas">Pago al crédito
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                <div class="container">
                                  <div id="tipopagodiv" style="text-align: center;" class="row">
                                    <div class="col-lg-6">
                                      Monto de cuotas
                                      <div id="divmontocuotas">
                                        <input type="text" name="montocuotacre[]">
                                        <input type="text" name="ncuotahiden[]">
                                      </div>
                                    </div>
                                    <div class="col-lg-6">
                                      Fechas de pago
                                      <div id="divfechaspago">
                                        <input type="text" name="fechapago[]">
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                                  <i class="bx bx-x d-block d-sm-none"></i>
                                  <span class="d-none d-sm-block">Cancelar</span>
                                </button>
                                <button id="btnGuardarC" type="submit" class="btn btn-primary ml-1" data-bs-dismiss="modal">
                                  <i class="bx bx-check d-block d-sm-none"></i>
                                  <span class="d-none d-sm-block">Agregar</span>
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="modal fade" id="" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                          <div class="modal-dialog" style="width: 70% !important;">
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title">CUOTAS Y FECHAS DE PAGO</h4>
                              </div>
                              <h2 id="totalcomp"></h2>
                              <div class="table-responsive">
                                <table class="table table-sm table-striped table-bordered table-condensed table-hover nowrap">
                                  <tr>
                                    <td>CUOTAS</td>
                                    <td>
                                      <div>
                                        <label>Monto de cuotas</label>
                                        <div id="divmontocuotas">
                                        </div>
                                      </div>
                                    </td>
                                    <td>
                                      <div>
                                        <label>Fechas de pago</label>
                                        <div id="divfechaspago">
                                        </div>
                                      </div>
                                    </td>
                                  </tr>
                                </table>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                <!-- <button type="button" class="btn btn-success" onclick="mesescontinuos()" >Meses continuos</button> -->
                              </div>
                            </div>
                          </div>
                        </div>
                        <!-- Fin modal -->
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-9">
                  <div class="card">
                    <div class="card-body">
                      <div class="row">                        
                        <input type="hidden" name="itemno" id="itemno" value="0">
                        <div class="col-3">
                          <button style="margin-left:0px;" type="button" data-bs-toggle="modal" data-bs-target="#myModalArt" id="btnAgregarArt" class="btn btn-primary mb-3" onclick="cambiarlistadoum2()">
                            Agregar Productos o Servicios
                          </button>
                        </div>                        
                        <div class="mb-3 col-lg-9">
                          <!-- <label for="recipient-name" class="col-form-label">Código barra:</label> -->
                          <div class="input-group">                              
                            <span class="input-group-text cursor-pointer charge-add-x-code"  data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Buscar por codigo de producto."><i class="fas fa-barcode fa-lg"></i></span>
                            <input type="text" name="codigob" id="codigob" class="form-control" onkeypress="agregarArticuloxCodigo(event)" onkeyup="mayus(this);" placeholder="Digite o escanee el código de barras" onchange="quitasuge3()" style="background-color: #F5F589;">
                          </div>                            
                          <div id="suggestions3"> </div>
                        </div>
                        <!-- <button data-bs-toggle="modal" data-bs-target="#myModalnuevoitem" id="btnAgregarArt" type="button" class="btn btn-danger btn-sm" onclick="cambiarlistadoum()"> Otra u. medida </button> -->
                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                          <label style="font-size: 16pt; color: red;" hidden="true" id="mensaje700" name="mensaje700">Agregar DNI o C.E. del cliente.</label>
                        </div>
                        <div class="table-responsive">
                          <table id="detalles" class="table table-striped" style="text-align:center;">
                            <thead align="center" >
                              <th>Sup.</th>
                              <th>Item</th>
                              <th>Artículo</th>
                              <!-- <th style="color:white;">Descripción</th> -->
                              <th>Cantidad</th>
                              <th>Dcto. %</th>
                              <!-- <th style="color:white;">Cód. Prov.</th> -->
                              <!-- <th style="color:white;">-</th> -->
                              <th>U.M.</th>
                              <th>Prec. Uni.</th>
                              <th>Val. u.</th>
                              <th>Stock</th>
                              <th>Importe</th>
                            </thead>
                            <tbody>
                            </tbody>
                          </table>
                        </div>
                        <div class="form-group" style="background-color: #b4d4ee;">
                          <div class="text-center">
                            <div style="color:#081A51; font-weight: bold">Items de venta</div>
                              <!-- <button style="background:#081A51; border:none;" type="button" value="1" name="botonpago1" id="botonpago1" class="btn btn-success btn-sm" onclick="botonrapido1()">1</button>
                              <button style="background:#081A51; border:none;" type="button" value="2" name="botonpago2" id="botonpago2" class="btn btn-success btn-sm" onclick="botonrapido2()">2</button>
                              <button style="background:#081A51; border:none;" value="5" type="button" name="botonpago5" id="botonpago5" class="btn btn-success btn-sm" onclick="botonrapido5()">5</button>
                              <button style="background:#081A51; border:none;" value="10" type="button" name="botonpago10" id="botonpago10" class="btn btn-success btn-sm" onclick="botonrapido10()">10</button>
                              <button style="background:#081A51; border:none;" value="20" name="botonpago20" type="button" id="botonpago20" class="btn btn-success btn-sm" onclick="botonrapido20()">20</button>
                              <button style="background:#081A51; border:none;" type="button" value="50" name="botonpago50" type="button" id="botonpago50" class="btn btn-success btn-sm" onclick="botonrapido50()">50</button>
                              <button style="background:#081A51; border:none;" value="100" name="botonpago100" id="botonpago100" type="button" class="btn btn-success btn-sm" onclick="botonrapido100()">100</button>
                              <button style="background:#081A51; border:none;" value="200" name="botonpago200" id="botonpago200"  type="button" class="btn btn-success btn-sm" onclick="botonrapido200()">200</button>
                              <button style="background:#081A51; border:none;" value="200" name="botonpago200" id="botonpago200" type="button" class="btn btn-success btn-sm" onclick="agregarMontoPer()">Agregar monto perzonalizado</button> -->
                          </div>
                        </div>

                        <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-4 col-sm-4">
                          <div class="card" style="border:none;">
                            <div class="card custom-card">
                              <div class="">
                                <h4 class="card-title">Detalle del ingreso</h4>
                                <p class="">
                                  <th id="CuadroT" style="font-weight: bold; background-color:#FFB887;">
                                    <div style="display:flex;">
                                      <label for="">SubT. : </label>
                                      <!-- <h6 hidden style="font-weight: bold; margin: 0 auto; top: 10px; margin-top: 4px; margin-right: 0px;" id="subtotal"> 0.00</h6> -->
                                      <input placeholder="0.00" readonly style="font-weight: bold; margin: 0 auto; top: 10px; margin-top: 4px; margin-right: 0px; text-align: right; border:none;width: 95px;" name="subtotal_notapedido" id="subtotal_notapedido">
                                    </div>
                                    <div style="display:flex;">
                                      <label for="">IGV : </label>
                                      <input placeholder="0.00" readonly style="font-weight: bold; margin: 0 auto; top: 10px; margin-top: 4px; margin-right: 0px; text-align: right; border:none; width: 95px;" name="total_igv" id="total_igv">
                                    </div>
                                    <div style="display:flex;">
                                      <label for="">Descuento : </label>
                                      <h6 style="font-weight: bold; margin: 0 auto; top: 10px; margin-top: 4px; margin-right: 0px; " name="" id="tdescuentoL"> 0.00</h6>
                                      <!-- <h6 hidden style="font-weight: bold; margin: 0 auto; top: 10px; margin-top: 4px; margin-right: 0px;" name="total_dcto" id="total_dcto"> 0.00</h6> -->
                                    </div>
                                    <div style="display:flex;">
                                      <label for="">Total a pagar : </label>
                                      <h6 style="font-weight: bold; margin: 0 auto; top: 10px; margin-top: 4px; margin-right: 0px;" id="total"> 0.00</h6>
                                    </div>
                                    <br>
                                    <h5 class="card-title">Calcular vuelto</h5>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                      <label for="ipagado">Pago con : </label>
                                      <!-- <h6 name="ipagado" id="ipagado" style="font-weight: bold; margin: 0 auto; top: 10px; margin-right: 0px;"> 0.00</h6> -->
                                      <input type="number" class="form-control text-end hidebutton" name="ipagado" id="ipagado" value="0.00" style="width: 100px;">
                                      <!-- <input hidden name="ipagado_final" id="ipagado_final" style="font-weight: bold; margin-left: 75px; width: 100px;"> -->
                                    </div>
                                    <div class="d-flex align-items-center">
                                      <label for="saldo" id="vuelto_text">Vuelto : </label>
                                      <h6 style="font-weight: bold; margin: 0 auto; margin-right: 0px;" name="saldo" id="saldo"> 0.00</h6>
                                      <!-- <h6 hidden style="font-weight: bold; margin: 0 auto; top: 10px; margin-top: 4px; margin-right: 0px;" name="saldo_final" id="saldo_final"> 0.00</h6> -->
                                    </div>
                                    <input type="hidden" name="total_final" id="total_final">
                                    <input type="hidden" name="pre_v_u" id="pre_v_u">
                                    <!-- <input type="hidden" name="subtotal_notapedido" id="subtotal_notapedido"> -->
                                    <!-- <input type="hidden" name="total_igv" id="total_igv"> -->
                                    <input type="hidden" name="total_icbper" id="total_icbper">
                                    <input type="hidden" name="total_dcto" id="total_dcto">
                                    <input type="hidden" name="ipagado_final" id="ipagado_final">
                                  </th>
                                  <!--Datos de impuestos--> <!--TOTAL-->
                                  <input type="hidden" name="saldo_final" id="saldo_final">
                                  </th><!--Datos de impuestos--> <!--TOTAL-->
                                <h4 hidden id="icbper">0</h4>
                                </th><!--Datos de impuestos--> <!--TOTAL-->
                                <!-- </th> -->
                                </p>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-12">
                          <button class="btn btn-primary btn-sm" type="submit" id="btnGuardar" ><i class="fa fa-save"></i>
                            Guardar
                          </button>
                          <button class="btn btn-danger btn-sm" type="button" id="btnCancelar" onclick="cancelarform()" ><i class="fa fa-arrow-circle-left" data-toggle="tooltip" title="Cancelar"></i> Cancelar</button>
                        </div>
                        
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              </form>
            </div>
            <!-- Fin Formulario -->

          </div>
          <!-- FINAL centro -->
      </section>
    </div>



    <!-- MODAL - LISTA DE ARTICULOS Y SERVICIOS -->
    <div class="modal fade text-left" id="myModalArt" tabindex="-1" role="dialog" aria-labelledby="myModalArt" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="myModalArt">Agrega tu producto o servicio</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="mb-3 col-lg-3">
                <label for="tipoprecio" class="col-form-label">Precio:</label>
                <select class="form-control" id="tipoprecio" onchange="listarArticulos()">
                  <option value='1'>PRECIO PÚBLICO</option>
                  <option value='2'>PRECIO POR MAYOR</option>
                  <option value='3'>PRECIO DISTRIBUIDOR</option>
                </select>
              </div>
              <div class="mb-3 col-lg-3">
                <label for="almacenlista" class="col-form-label">Sucursal:</label>
                <select class="form-control" id="almacenlista" onchange="listarArticulos()">
                </select>
              </div>
              <div class="mb-3 col-lg-3"> <label for="tipoprecio" class="form-label">.</label> <br>
                <button class="btn btn-danger" id="refrescartabla" data-bs-target="#modalnuevoarticulo" data-bs-toggle="modal" onclick="nuevoarticulo()">
                  <span class="sr-only"></span>Agregar producto al inventario</button>
              </div>
              <div class="mb-3 col-lg-3"> <label for="tipoprecio" class="form-label">.</label> <br>
                <button class="btn btn-success" id="refrescartabla" onclick="refrescartabla()">
                  <span class="sr-only"></span>Actualizar Tabla</button>
              </div>
              <div class="mb-3 col-lg-12">
                <div class="table-responsive">
                  <table id="tblarticulos" class="table table-striped table-bordered  table-hover">
                    <thead>
                      <th>+++</th>
                      <th>Nombre</th>
                      <th>Código</th>
                      <th>Un. Med.</th>
                      <th>Precio</th>
                      <th>Stock</th>
                      <th>Imagen</th>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                    </tfoot>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
              <i class="bx bx-x d-block d-sm-none"></i>
              <span class="d-none d-sm-block">Cerrar</span>
            </button>
            <!-- <button id="btnGuardar" type="submit" class="btn btn-primary ml-1" data-bs-dismiss="modal">
              <i class="bx bx-check d-block d-sm-none"></i>
              <span class="d-none d-sm-block">Guardar</span>
            </button> -->
          </div>
        </div>
      </div>
    </div>
    <!-- Final - Modal - Lista de Articulos y Servicios -->


    <!-- MODAL - AGREGAR NUEVO ARTICULO -->
    <div class="modal fade text-left" id="modalnuevoarticulo" tabindex="-1" role="dialog" aria-labelledby="modalnuevoarticulo" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalnuevoarticulo">Añade nuevo artículo rapidamente</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form name="formularionarticulo" id="formularionarticulo" method="POST" style="margin: 2%;">
            <div class="row">
              <div class="mb-3 col-lg-6">
                <input type="hidden" name="idarticulonuevo" id="idarticulonuevo">
                <input type="hidden" name="idempresa" id="idempresa" value="<?php echo $_SESSION['idempresa']; ?>">
                <label for="idalmacennarticulo" class="col-form-label">Selecciona el almacen:</label>
                <select class="form-control" name="idalmacennarticulo" id="idalmacennarticulo" required data-live-search="true">
                </select>
              </div>
              <div class="mb-3 col-lg-6">
                <label for="idfamilianarticulo" class="col-form-label">Selecciona tu categoria:</label>
                <select class="form-control" name="idfamilianarticulo" id="idfamilianarticulo" required data-live-search="true">
                </select>
              </div>
              <div hidden class="mb-3 col-lg-6">
                <select class="form-control" name="tipoitemnarticulo" id="tipoitemnarticulo" onchange="focuscodprov()">
                  <option value="productos" selected="true">PRODUCTO</option>
                  <option value="servicios">SERVICIO</option>
                </select>
              </div>
              <div class="mb-3 col-lg-6">
                <label for="nombrenarticulo" class="col-form-label">Nombre del producto:</label>
                <input type="text" class="form-control" name="nombrenarticulo" id="nombrenarticulo" onkeyup="mayus(this);" onkeypress=" return limitestockf(event, this)" autofocus="true" onchange="generarcodigonarti()">
              </div>
              <div class="mb-3 col-lg-6">
                <label for="stocknarticulo" class="col-form-label">Cantidad del stock</label>
                <input type="text" class="form-control" name="stocknarticulo" id="stocknarticulo" maxlength="100" required="true" onkeypress="return NumCheck(event, this)">
              </div>
              <div class="mb-3 col-lg-6">
                <label for="precioventanarticulo" class="col-form-label">Precio de venta:</label>
                <input type="text" class="form-control" name="precioventanarticulo" id="precioventanarticulo" onkeypress="return NumCheck(event, this)">
              </div>
              <div class="mb-3 col-lg-6">
                <label for="codigonarticulonarticulo" class="col-form-label">Codigo del interno del producto:</label>
                <input type="text" class="form-control" name="codigonarticulonarticulo" id="codigonarticulonarticulo">
              </div>
              <div class="mb-3 col-lg-6">
                <label for="umedidanp" class="col-form-label">Unidad de medida:</label>
                <select class="form-control" name="umedidanp" id="umedidanp" required data-live-search="true">
                </select>
              </div>
              <div hidden class="mb-3 col-lg-6">
                <textarea class="form-control" id="descripcionnarticulo" name="descripcionnarticulo" rows="3" cols="70" onkeyup="mayus(this)" onkeypress="return focusDescdet(event, this)"> </textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button data-bs-target="#myModalArt" data-bs-toggle="modal" type="button" class="btn btn-danger" data-bs-dismiss="modal">
                <i class="bx bx-x d-block d-sm-none"></i>
                <span class="d-none d-sm-block">Cancelar</span>
              </button>
              <button id="btnguardarncliente" name="btnguardarncliente" value="btnGuardarcliente" type="submit" class="btn btn-primary ml-1">
                <i class="bx bx-check d-block d-sm-none"></i>
                <span class="d-none d-sm-block">Guardar</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- Fin - Modal - Agregar Nuevo Articulo -->

    <!-- MODAL - AGREGAR NUEVO CLIENTE -->
    <div class="modal fade" id="ModalNcliente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" style="width: 100% !important;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title ">Nuevo cliente</h4>
          </div>
          <div class="modal-body">
            <div class="container">
              <form role="form" method="post" name="busqueda" id="busqueda">
                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <div class="input-group mb-2">
                    <input type="number" class="form-control" name="nruc" id="nruc" placeholder="Ingrese RUC o DNI" pattern="([0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9]|[0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9])" autofocus>
                    <div class="input-group-append">
                      <button type="submit" name="btn-submit" id="btn-submit" class="btn btn-primary btn-search-s" data-tooltip="Buscar Sunat" data-tooltip-stickto="top" data-tooltip-color="black" ><i class="fas fa-search fa-lg"></i></button>
                    </div>
                  </div>
                </div>                  
              </form>
            </div>
            <form name="formularioncliente" id="formularioncliente" method="POST">
              <div class="row">
                <div class="">
                  <input type="hidden" name="idpersona" id="idpersona">
                  <input type="hidden" name="tipo_persona" id="tipo_persona" value="cliente">
                </div>
                <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                  <label>Tipo Doc.:</label>
                  <select class="form-control" name="tipo_documento" id="tipo_documento" required>
                    <option value="6"> RUC </option>
                  </select>
                </div>
                <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                  <label>N. Doc.:</label>
                  <input type="text" class="form-control" name="numero_documento3" id="numero_documento3" maxlength="20" placeholder="Documento" >
                </div>
                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <label>Razón social:</label>
                  <input type="text" class="form-control" name="razon_social3" id="razon_social3" maxlength="100" placeholder="Razón social" required >
                </div>
                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <label>Domicilio:</label>
                  <input type="text" class="form-control" name="domicilio_fiscal3" id="domicilio_fiscal3" maxlength="100" placeholder="Domicilio fiscal" required >
                </div>
                <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                  <label>Telfono:</label>
                  <input type="number" class="form-control" name="telefono1" id="telefono1" maxlength="15" placeholder="Teléfono 1" pattern="([0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9]|[0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9])" onkeypress="return focusemail(event, this)">
                </div>
                <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                  <label>Correo:</label>
                  <input type="text" class="form-control" name="email" id="email" maxlength="50" placeholder="CORREO" >
                </div>
                <div class="form-group col-lg-12 col-md-6 col-sm-6 col-xs-12 text-center mt-5">
                  <button class="btn btn-primary" type="submit" id="btnguardarncliente" name="btnguardarncliente" value="btnGuardarcliente">
                    <i class="fa fa-save"></i> Guardar
                  </button>
                </div>
              </div>              
              
              <!--<div class="form-group col-lg-12 col-md-4 col-sm-6 col-xs-12">
            <iframe border="0" frameborder="0" height="450" width="100%" marginwidth="1"
            src="https://e-consultaruc.sunat.gob.pe/cl-ti-itmrconsruc/jcrS00Alias">
            </iframe>
            </div> -->
            </form>
            <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
            <script src="scripts/ajaxview.js"></script>
            <script>
              //============== original ===========================================================
              $(document).ready(function() {
                $("#btn-submit").click(function(e) {
                  var $this = $(this);
                  e.preventDefault();
                  //============== original ===========================================================

                  var documento = $("#nruc").val(); console.log(documento);
                  $.post("../ajax/factura.php?op=listarClientesfacturaxDoc&doc=" + documento, function(data, status) {
                    data = JSON.parse(data);
                    if (data != null) {
                      alert("Ya esta registrado cliente, se agregarán sus datos!");
                      $('#idpersona').val(data.idpersona);
                      $('#numero_documento').val(data.numero_documento);
                      $("#razon_social3").val(data.razon_social);
                      $('#domicilio_fiscal3').val(data.domicilio_fiscal);
                      //$('#correocli').val(data.email);
                      document.getElementById("btnAgregarArt").style.backgroundColor = '#367fa9';
                      document.getElementById("btnAgregarArt").focus();
                      $("#ModalNcliente").modal('hide');
                    } else {

                      $.ajax({
                        type: 'POST',
                        url: "../ajax/ajax_general.php?op=sunat_otro&ruc=" + documento,
                        dataType: 'json',
                        data:{ruc: documento},
                        beforeSend: function() {},
                        complete: function(data) {  },
                        success: function(data) { console.log(data);
                          $('.before-send').fadeOut(500);
                          if (!jQuery.isEmptyObject(data.error)) {
                            alert(data.error);
                          } else {
                            
                            $("#numero_documento3").val(data.numeroDocumento);
                            $('#razon_social3').val(data.nombre);
                            $('#domicilio_fiscal3').val(data.direccion);
                          }
                          // $.ajaxunblock();
                        },
                        error: function(data) {
                          alert("Problemas al tratar de enviar el formulario");
                          // $.ajaxunblock();
                        }
                      });
                      //============== original ===========================================================
                    }
                    //============== original ===========================================================
                  });

                });
              });
            </script>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i>
              Cerrar</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin - Modal - Agregar Nuevo Cliente -->





    <!-- Modal -->
    <div class="modal fade" id="myModalCli" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" style="width: 100% !important;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Seleccione un cliente</h4>
          </div>

          <div class="table-responsive">
            <table id="tblaclientes" class="table table-striped table-bordered table-condensed table-hover" width=-5px>
              <thead>
                <th>Opciones</th>
                <th>Nombre</th>
                <th>RUC</th>
                <th>Dirección</th>

              </thead>
              <tbody>

              </tbody>
              <tfoot>
                <th>Opciones</th>
                <th>Nombre</th>
                <th>RUC</th>
                <th>Dirección</th>

              </tfoot>
            </table>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          </div>

        </div>
      </div>
    </div>
    <!-- Fin modal -->

    <!-- Modal -->
    <div class="modal fade" id="modalTcambio">
      <div class="modal-dialog" style="width: 100% !important;">
        <div class="modal-content">
          <div class="modal-header">
          </div>
          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <!-- <iframe border="1" frameborder="1" height="310" width="100%" src="https://e-consulta.sunat.gob.pe/cl-at-ittipcam/tcS01Alias"></iframe> -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger btn-ver" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal -->

    <!-- Modal -->
    <div class="modal fade" id="modalPreviewXml" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-md" style="width: 70% !important;">
        <div class="modal-content">

          <div class="modal-header">
            <h4 class="modal-title">NOTA DE PEDIDO</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <iframe name="modalCom" id="modalCom" border="0" frameborder="0" width="100%" style="height: 800px;" src="">
          </iframe>

          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
          </div>

        </div>
      </div>
    </div>

    <!-- Fin modal -->

    <div class="modal fade" id="modalPreviewticket" tabindex="-1" aria-labelledby="modalPreviewticketLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-md" style="max-width: 24% !important;">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalPreviewticketLabel">Ticket de venta</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div id="imp1">
            <div>
              <iframe name="modalComticket" id="modalComticket" border="0" frameborder="0" width="100%"
                style="height: 800px;" marginwidth="1" src="">
              </iframe>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>


    <!-- Fin modal -->
    <div class="modal fade" id="modalPreview2Hojas" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="myModalLabel">Formato 2 Copias</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div id="imp1">
            <div>
              <iframe name="modalCom2Hojas" id="modalCom2Hojas" frameborder="0" style="height: 800px; width: 100%;"
                src=""></iframe>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>

    <script>
      function imprim1(imp1) {
        var printContents = document.getElementById('imp1').innerHTML;
        w = window.open();
        w.document.write(printContents);
        w.document.close(); // necessary for IE >= 10
        w.focus(); // necessary for IE >= 10
        w.print();
        w.close();
        return true;
      }
    </script>

    <?php
  } else {
    require 'noacceso.php';
  }
  require 'footer.php';
  ?>
  <script type="text/javascript" src="scripts/notapedido.js"></script>
  <?php
}
ob_end_flush();
?>