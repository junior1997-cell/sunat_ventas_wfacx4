<?php

  //Activamos el almacenamiento del Buffer
  ob_start();
  session_start();
  if (!isset($_SESSION["nombre"])) {
    header("Location: ../vistas/login.php");
  } else {
  require 'header.php';
  if ($_SESSION['Configuracion'] == 1) {
  ?>
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css"> -->
    <!-- html5tooltips Styles & animations -->
    <link href="../public/css/html5tooltips.css" rel="stylesheet">
    <link href="../public/css/html5tooltips.animation.css" rel="stylesheet">

    <!--Contenido-->
    <!-- Content Wrapper. Contains page content -->

    <div class="content-start transition">

      <!-- Main content -->

      <section class="container-fluid dashboard">
        <div class="content-header">
          <h1>Empresa <button class="btn btn-primary btn-sm" onclick="mostrarform(true);"> Agregar</button></h1>
        </div>

        <div class="row rounded-4" style="background:white;">
          <div class="col-md-12 mt-3">

            <div class="m-3">

              <div class="panel-body table-responsive" id="listadoregistros">

                <table id="tbllistado" class="table table-striped" style="width: 100% !important;">
                  <thead>
                    <th>Opciones</th>
                    <th>Nombre comercial</th>
                    <th>Domicilio </th>
                    <th>Web</th>
                  </thead>
                  <tbody>
                  </tbody>

                  <!-- <tfoot>
                    <th>Opciones</th>
                    <th>Razon social</th>
                    <th>Domicilio </th> 
                    <th>Ruc</th>
                    <th>Telefono 1</th>
                    <th>Web</th>
                    <th>Logo</th>
                  </tfoot> -->
                </table>
              </div>

              <div class="panel-body" id="formularioregistros" style="display: none;">

                <form name="form-empresa" id="form-empresa" method="post">

                  <div class="card-body">
                    <div class="row">

                      <input type="hidden" name="idempresa" id="idempresa">

                      <div class="col-auto mb-3 col-lg-2">
                        <div class="form-group">
                          <label class="form-label">N° RUC</label>
                          <div class="input-group mb-2">
                            <input type="text" name="ruc" id="ruc" placeholder="Número de RUC" class="form-control">
                            <div class="input-group-append">
                              <button type="button" class="btn btn-primary btn-search-s" data-tooltip="Buscar Sunat" data-tooltip-stickto="top" data-tooltip-color="black" onclick="buscar_sunat(null, '#ruc');"><i class="fas fa-search fa-lg"></i></button>
                            </div>
                          </div>
                        </div>                        
                      </div>

                      <div class="mb-3 col-lg-4">   
                        <div class="form-group">
                          <label class="form-label">Razón social</label>
                          <textarea name="razonsocial" id="razonsocial" rows="2" class="form-control"></textarea>
                          <!-- <input type="text" name="razonsocial" id="razonsocial" placeholder="Razón Social" class="form-control"> -->
                        </div>                        
                      </div>

                      <div class="mb-3 col-lg-4">
                        <div class="form-group">
                          <label class="form-label">Domicilio fiscal</label>
                          <textarea name="domicilio" id="domicilio" rows="2" class="form-control"></textarea>
                          <!-- <input type="text" name="domicilio" id="domicilio" placeholder="Domicilio fiscal" class="form-control"> -->
                        </div>                        
                      </div>

                      <div class="mb-3 col-lg-2">
                        <div class="form-group">
                          <label class="form-label">Nombre comercial</label>
                          <textarea name="ncomercial" id="ncomercial" rows="2" class="form-control"></textarea>
                          <!-- <input type="text" name="ncomercial" id="ncomercial" placeholder="Nombre comercial" class="form-control"> -->
                        </div>                        
                      </div>

                      <div class="mb-3 col-lg-2">
                        <div class="form-group">
                          <label class="form-label">Teléfono</label>
                          <input type="text" name="tel1" id="tel1" placeholder="Teléfono 1" class="form-control">
                        </div>                        
                      </div>

                      <div class="mb-3 col-lg-2">
                        <div class="form-group">
                          <label class="form-label">Celular</label>
                          <input type="text" name="tel2" id="tel2" placeholder="Teléfono 2" class="form-control">
                        </div>                        
                      </div>

                      <div class="mb-3 col-lg-2">
                        <div class="form-group">
                          <label class="form-label">Email contacto</label>
                          <input type="email" name="correo" id="correo" placeholder="Correo contacto" class="form-control">
                        </div>                        
                      </div>

                      <div class="mb-3 col-lg-2">
                        <div class="form-group">
                          <label class="form-label">Página Web</label>
                          <input type="url" name="web" id="web" placeholder="Página web" class="form-control">
                        </div>                        
                      </div>

                      <div class="mb-3 col-lg-2">
                        <div class="form-group">
                          <label class="form-label">Web de consultas</label>
                          <input type="url" name="webconsul" id="webconsul" placeholder="Página web de consultas" class="form-control">
                        </div>                        
                      </div>

                      <div class="mb-3 col-lg-2">
                        <div class="form-group">
                          <label class="form-label">Código de establecimiento</label>
                          <input type="text" name="ubigueo" id="ubigueo" placeholder="Código de domicilio fiscal" class="form-control" value="0000" maxlength="5">
                        </div>                        
                      </div>

                      <div class="mb-3 col-lg-2">
                        <div class="form-group">
                          <label class="form-label">Ubigeo Dom Fiscal <span class="chargue-ubi"></span></label>
                          <input  type="text" name="codubigueo" id="codubigueo" class="form-control">
                        </div>                        
                      </div>

                      <div class="mb-3 col-lg-2">
                        <div class="form-group">
                          <label class="form-label">IVA</label>
                          <input type="text" name="igv" id="igv" placeholder="Ingrese el fiscal" class="form-control" maxlength="5">
                        </div>                        
                      </div>

                      <div class="mb-3 col-lg-2">
                        <div class="form-group">
                          <label class="form-label">% Descuento maximo</label>
                          <input type="text" name="porDesc" id="porDesc" placeholder="Limite de descuento" class="form-control" maxlength="5">
                        </div>                        
                      </div>                      

                      <div class="mb-3 col-lg-2">
                        <div class="form-group">
                          <label class="form-label">Código PAÍS</label>
                          <select class="form-control" name="codigopais" id="codigopais">
                            <option value="PE">PE</option>
                          </select>
                          <!-- <input class="form-control" type="text" name="codigopais" id="codigopais" placeholder="PE" value="PE"> -->
                        </div>                        
                      </div>

                      <div class="mb-3 col-lg-2">
                        <div class="form-group">
                          <label class="form-label">Impresión por defecto</label>
                          <select class="form-control" id="tipoimpresion" name="tipoimpresion">
                            <option value="00">Ticket</option>
                            <option value="01">A4 dos copias</option>
                            <option value="02">A4 completa</option>
                          </select>
                        </div>
                      </div>

                      <div class="mb-3 col-lg-2">
                        <div class="form-group">
                          <label class="form-label"  data-tooltip="Texto libre debajo del nombre de cada comprobante." data-tooltip-stickto="top" data-tooltip-color="black">Texto libre debajo...</label>
                          <input class="form-control" type="text" name="textolibre" id="textolibre">
                        </div>                        
                      </div>

                      <div class="mb-3 col-lg-2">
                        <div class="form-group">
                          <label class="form-label">Departamento <span class="chargue-dep"></span></label>
                          <input  type="text" name="departamento" id="departamento" class="form-control" placeholder="SAN MARTIN">
                        </div>                        
                      </div>

                      <div class="mb-3 col-lg-2">
                        <div class="form-group">
                          <label class="form-label">Provincia <span class="chargue-pro"></span></label>
                          <input  type="text" name="ciudad" id="ciudad" class="form-control" placeholder="EL DORADO">
                        </div>                        
                      </div>

                      <div class="mb-3 col-lg-2">
                        <div class="form-group">
                          <label class="form-label">Distrito</label>
                          <select name="distrito" id="distrito" class="form-control" onchange="llenar_dep_prov_ubig(this);">
                          </select>
                          <!-- <input class="form-control" type="text" name="distrito" id="distrito" placeholder="SAN JOSE DE SISA" onchange="llenar_dep_prov_ubig(this);"> -->
                        </div>                        
                      </div>

                      <div class="mb-3 col-lg-2">
                        <div class="form-group">
                          <label class="form-label">Interior</label>
                          <input class="form-control" type="text" name="interior" id="interior" placeholder="SAN JOSE DE SISA">
                        </div>                        
                      </div>

                      <div class="mb-3 col-lg-3">
                        <div class="form-group">
                          <label class="form-label">Logo de empresa</label>
                          <input class="form-control" type="file" class="" name="imagen" id="imagen">
                          <input type="hidden" name="imagenactual" id="imagenactual">
                        </div>                        
                        <img src="../files/logo/img_defecto.png" width="80px" height="auto" id="imagenmuestra">
                        <hr>
                        <div id="preview"></div>
                      </div>
                      <div class="col-lg-1">
                        <div class="form-group ">
                          <label class="form-label logo_cuadrado_rectangulo">Rectangulo </label> <br>
                          <div class="custom-toggle-switch d-flex align-items-center mb-4">
                            <input id="logo_c_r" name="logo_c_r" type="checkbox" value="1" onchange="logo_cu_re(this);">
                            <label for="logo_c_r" class="label-dark"></label><span class="ms-3"></span>
                          </div>                          
                        </div>                        
                      </div>
                    </div>

                    <h5>Cuentas Bancarias</h5>

                    <div class="row">

                      <div class="mb-3 col-lg-2">
                        <div class="form-group">
                          <label class="form-label">Banco 1</label>
                          <input class="form-control" type="text" id="banco1" name="banco1">
                        </div>                        
                      </div>

                      <div class="mb-3 col-lg-2">
                        <div class="form-group">
                          <label class="form-label">N° Cuenta</label>
                          <input class="form-control" type="text" name="cuenta1" id="cuenta1">
                        </div>                        
                      </div>

                      <div class="mb-3 col-lg-2">
                        <div class="form-group">
                          <label class="form-label">CCI</label>
                          <input class="form-control" type="text" name="cuentacci1" id="cuentacci1">
                        </div>                        
                      </div>

                    </div>

                    <div class="row">

                      <div class="mb-3 col-lg-2">
                        <div class="form-group">
                          <label class="form-label">Banco 2</label>
                          <input class="form-control" type="text" id="banco2" name="banco2">
                        </div>                        
                      </div>

                      <div class="mb-3 col-lg-2">
                        <div class="form-group">
                          <label class="form-label">N° Cuenta</label>
                          <input class="form-control" type="text" name="cuenta2" id="cuenta2">
                        </div>                        
                      </div>

                      <div class="mb-3 col-lg-2">
                        <div class="form-group">
                          <label class="form-label">CCI</label>
                          <input class="form-control" type="text" name="cuentacci2" id="cuentacci2">
                        </div>                        
                      </div>

                    </div>

                    <div class="row">

                      <div class="mb-3 col-lg-2">
                        <div class="form-group">
                          <label class="form-label">Banco 3</label>
                          <input class="form-control" type="text" id="banco3" name="banco3">
                        </div>                        
                      </div>

                      <div class="mb-3 col-lg-2">
                        <div class="form-group">
                          <label class="form-label">N° Cuenta</label>
                          <input class="form-control" type="text" name="cuenta3" id="cuenta3">
                        </div>                        
                      </div>

                      <div class="mb-3 col-lg-2">
                        <div class="form-group">
                          <label class="form-label">CCI</label>
                          <input class="form-control" type="text" name="cuentacci3" id="cuentacci3">  
                        </div>                        
                      </div>

                    </div>

                    <div class="row mb-4">
                      
                      <div class="mb-3 col-lg-2">
                        <div class="form-group">
                          <label class="form-label">Banco 4</label>
                          <input class="form-control" type="text" id="banco4" name="banco4">
                        </div>                        
                      </div>

                      <div class="mb-3 col-lg-2">
                        <div class="form-group">
                          <label class="form-label">N° Cuenta</label>
                          <input class="form-control" type="text" name="cuenta4" id="cuenta4">
                        </div>                        
                      </div>

                      <div class="mb-3 col-lg-2">
                        <div class="form-group">
                          <label class="form-label">CCI</label>
                          <input class="form-control" type="text" name="cuentacci4" id="cuentacci4">
                        </div>                        
                      </div>

                    </div>

                  </div>

                  <div class="card-footer text-right">
                    <button class="btn btn-primary" type="submit" id="btnGuardar">Guardar</button>
                    <button class="btn btn-danger" onclick="cancelarform()" type="button">Cancelar</button>
                  </div>
                </form>
              </div>
            </div>

          </div><!-- /.row -->

      </section><!-- /.content -->

    </div><!-- /.content-wrapper -->

    <!-- MODAL - VER PERFIL EMPRESA-->
    <div class="modal fade" id="modal-ver-perfil-empresa">
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

    <!--Fin-Contenido-->

  <?php
  } else {  require 'noacceso.php';  }
  require 'footer.php';
  ?>

  <script type="text/javascript" src="scripts/empresa.js"></script>
  <script src="../public/js/html5tooltips.js"></script>

<?php

}

ob_end_flush();

?>