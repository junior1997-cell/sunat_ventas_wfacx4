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
                    <th>Ruc</th>
                    <th>Telefono 1</th>
                    <th>Web</th>
                    <th>Logo</th>
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

                <form name="formulario" id="formulario" method="post">

                  <div class="card-body">
                    <div class="row">
                      <div class="col-auto mb-3 col-lg-2">
                        <label>N° RUC</label>
                        <div class="input-group mb-2">
                          <input type="text" name="ruc" id="ruc" placeholder="Número de RUC" class="form-control">
                          <div class="input-group-append">
                            <div class="btn btn-success" data-tooltip="Buscar Sunat" data-tooltip-stickto="top" data-tooltip-color="black" onclick="buscar_sunat(null, '#ruc');"><i class="fas fa-search fa-lg"></i></div>
                          </div>
                        </div>
                      </div>
                      <div class="mb-3 col-lg-3">
                        <input type="hidden" name="idempresa" id="idempresa">
                        <label>Razón social</label>
                        <input type="text" name="razonsocial" id="razonsocial" placeholder="Razón Social" class="form-control">
                      </div>
                      <div class="mb-3 col-lg-5">
                        <label>Domicilio fiscal</label>
                        <input type="text" name="domicilio" id="domicilio" placeholder="Domicilio fiscal" class="form-control">
                      </div>
                      <div class="mb-3 col-lg-2">
                        <label>Nombre comercial</label>
                        <input type="text" name="ncomercial" id="ncomercial" placeholder="Nombre comercial" class="form-control">
                      </div>

                      <div class="mb-3 col-lg-2">
                        <label>Teléfono</label>
                        <input type="text" name="tel1" id="tel1" placeholder="Teléfono 1" class="form-control">
                      </div>

                      <div class="mb-3 col-lg-2">
                        <label>Celular</label>
                        <input type="text" name="tel2" id="tel2" placeholder="Teléfono 2" class="form-control">
                      </div>

                      <div class="mb-3 col-lg-2">
                        <label>Email contacto</label>
                        <input type="text" name="correo" id="correo" placeholder="Correo contacto" class="form-control">
                      </div>

                      <div class="mb-3 col-lg-2">
                        <label>Página Web</label>
                        <input type="text" name="web" id="web" placeholder="Página web" class="form-control">
                      </div>

                      <div class="mb-3 col-lg-2">
                        <label>Web de consultas</label>
                        <input type="text" name="webconsul" id="webconsul" placeholder="Página web de consultas" class="form-control">
                      </div>

                      <div class="mb-3 col-lg-2">
                        <label>Código de establecimiento</label>
                        <input type="text" name="ubigueo" id="ubigueo" placeholder="Código de domicilio fiscal" class="form-control" maxlength="5">
                      </div>

                      <div class="mb-3 col-lg-2">
                        <label>Ubigeo Dom Fiscal</label>
                        <input class="form-control" type="text" name="codubigueo" id="codubigueo">
                      </div>

                      <div class="mb-3 col-lg-2">
                        <label>IVA</label>
                        <input type="text" name="igv" id="igv" placeholder="Ingrese el fiscal" class="form-control" maxlength="5">
                      </div>

                      <div class="mb-3 col-lg-2">
                        <label>% Descuento maximo</label>
                        <input type="text" name="porDesc" id="porDesc" placeholder="Limite de descuento" class="form-control" maxlength="5">
                      </div>

                      <div class="mb-3 col-lg-2">
                        <label>Ciudad</label>
                        <input class="form-control" type="text" name="ciudad" id="ciudad" placeholder="Lima">
                      </div>

                      <div class="mb-3 col-lg-2">
                        <label>Distrito</label>
                        <input class="form-control" type="text" name="distrito" id="distrito" placeholder="Lima">
                      </div>

                      <div class="mb-3 col-lg-2">
                        <label>Interior</label>
                        <input class="form-control" type="text" name="interior" id="interior" placeholder="Lima">
                      </div>

                      <div class="mb-3 col-lg-2">
                        <label>Código PAÍS</label>
                        <input class="form-control" type="text" name="codigopais" id="codigopais" placeholder="PE">
                      </div>

                      <div class="mb-3 col-lg-2">
                        <label>Impresión por defecto</label>
                        <select class="form-control" id="tipoimpresion" name="tipoimpresion">
                          <option value="00">Ticket</option>
                          <option value="01">A4 dos copias</option>
                          <option value="02">A4 completa</option>
                        </select>

                      </div>

                      <div class="mb-3 col-lg-2">
                        <label>Texto libre debajo del nombre</label>
                        <input class="form-control" type="text" name="textolibre" id="textolibre">
                      </div>

                      <div class="mb-3 col-lg-3">
                        <label>Logo de empresa</label>
                        <input class="form-control" type="file" class="" name="imagen" id="imagen">
                        <input type="hidden" name="imagenactual" id="imagenactual">
                        <img src="../files/logo/img_defecto.png" width="80px" height="auto" id="imagenmuestra">

                        <hr>
                        <div id="preview"></div>
                      </div>
                    </div>

                    <h5>Cuentas Bancarias</h5>

                    <div class="row">

                      <div class="mb-3 col-lg-2">
                        <label>Banco 1</label>
                        <input class="form-control" type="text" id="banco1" name="banco1">
                      </div>

                      <div class="mb-3 col-lg-2">
                        <label>N° Cuenta</label>
                        <input class="form-control" type="text" name="cuenta1" id="cuenta1">
                      </div>

                      <div class="mb-3 col-lg-2">
                        <label>CCI</label>
                        <input class="form-control" type="text" name="cuentacci1" id="cuentacci1">
                      </div>

                    </div>

                    <div class="row">

                      <div class="mb-3 col-lg-2">
                        <label>Banco 2</label>
                        <input class="form-control" type="text" id="banco2" name="banco2">
                      </div>

                      <div class="mb-3 col-lg-2">
                        <label>N° Cuenta</label>
                        <input class="form-control" type="text" name="cuenta2" id="cuenta2">
                      </div>

                      <div class="mb-3 col-lg-2">
                        <label>CCI</label>
                        <input class="form-control" type="text" name="cuentacci2" id="cuentacci2">
                      </div>

                    </div>

                    <div class="row">

                      <div class="mb-3 col-lg-2">
                        <label>Banco 3</label>
                        <input class="form-control" type="text" id="banco3" name="banco3">
                      </div>

                      <div class="mb-3 col-lg-2">
                        <label>N° Cuenta</label>
                        <input class="form-control" type="text" name="cuenta3" id="cuenta3">
                      </div>

                      <div class="mb-3 col-lg-2">
                        <label>CCI</label>
                        <input class="form-control" type="text" name="cuentacci3" id="cuentacci3">
                      </div>

                    </div>

                    <div class="row mb-4">

                      <div class="mb-3 col-lg-2">
                        <label>Banco 4</label>
                        <input class="form-control" type="text" id="banco4" name="banco4">
                      </div>

                      <div class="mb-3 col-lg-2">
                        <label>N° Cuenta</label>
                        <input class="form-control" type="text" name="cuenta4" id="cuenta4">
                      </div>

                      <div class="mb-3 col-lg-2">
                        <label>CCI</label>
                        <input class="form-control" type="text" name="cuentacci4" id="cuentacci4">
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