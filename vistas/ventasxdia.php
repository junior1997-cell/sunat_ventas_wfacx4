<?php

//Activamos el almacenamiento del Buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
  header("Location: ../vistas/login.php");
} else {
  require 'header.php';

  if ($_SESSION['Contabilidad'] == 1) {
?>
    <!-- <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css'> -->
    <link rel='stylesheet' href='https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css'>
    <link rel='stylesheet' href='https://cdn.datatables.net/buttons/1.2.2/css/buttons.bootstrap.min.css'>

    <style>
      @media screen and (max-width: 767px) {
        div.dt-buttons {
          float: none;
          margin-top: -1em;
          width: 100%;
          text-align: center;
          margin-bottom: 3em;
        }
      }
    </style>
    <div class="content-start transition">
      <!-- Main content -->
      <section class="container-fluid dashboard">
        <div class="content-header">
          <h4>REGISTRO DE VENTAS POR DÍA y MES DE PRODUCTOS Y SERVICIOS</h4>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">

                <form name="formulario" id="formulario">
                  <input type="hidden" name="idempresa" id="idempresa" value="<?php echo $_SESSION['idempresa']; ?>">
                  <div class="row">

                    <div class="card-header col-12 mb-3">
                      <div class="row">

                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                          <label for="">FECHA INICIO </label>
                          <input type="date" class="form-control" placeholder="" name="filtro_fechadel" id="filtro_fechadel" style="width: 100%;" onchange="cargando_search(); delay(function(){filtros()}, 100 );">
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                          <label for="">FECHA FINAL</label>
                          <input type="date" class="form-control" placeholder="" name="filtro_fechaal" id="filtro_fechaal" style="width: 100%;" onchange="cargando_search(); delay(function(){filtros()}, 100 );">
                          <!-- datetime-local -->
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                          <label for="">TIPO COMPROBANTE</label>
                          <!-- <select class="form-control" name="filtro_idmarca" id="filtro_idmarca" style="width: 100%;" onchange="cargando_search(); delay(function(){filtros()}, 100 );"> -->
                          <select class="form-control" name="filtro_tcomprobante" id="filtro_tcomprobante" style="width: 100%;" onchange="cargando_search(); delay(function(){filtros()}, 100 );">
                            <option value="TODOS">TODOS</option>
                            <option value="FACTURA">FACTURA</option>
                            <option value="BOLETA">BOLETA</option>
                            <option value="NOTA PEDIDO">NOTA PEDIDO</option>
                            <option value="NOTA CREDITO">NOTA CREDITO</option>
                          </select>
                          </select>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                          <label> Moneda </label>
                          <select class="form-control" name="tmonedaa" id="tmonedaa" style="width: 100%;" onchange="cargando_search(); delay(function(){filtros()}, 100 );">
                            <option value="PEN">PEN</option>
                            <option disabled>USD</option>
                          </select>
                        </div>


                      </div>
                    </div>

                  </div>

                  <div class="col-lg-12">
                    <div class="panel-body table-responsive" id="listadoregistros">
                      <table id="tbllistadoVentas" class="table table-striped table-bordered table-condensed table-hover">
                        <thead>
                          <tr>
                            <th colspan="9" style="text-align: center !important;" class="cargando bg-danger p-1"><i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando... </th>

                          </tr>
                          <tr>
                            <th>Fecha</th>
                            <th>Documento</th>
                            <th>Cliente</th>
                            <th>N° Doc</th>
                            <th>Productos</th>
                            <th>Subtotal</th>
                            <th>Igv</th>
                            <th>Total</th>
                            <th>Estado</th>
                          </tr>
                        </thead>
                        <tfoot>
                          <tr>
                            <th>Totales</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                          </tr>
                        </tfoot>
                        <tbody>
                        </tbody>
                      </table>
                    </div>
                  </div>
              </div>
              </form>

              <!--Fin centro -->
            </div><!-- /.box -->
          </div><!-- /.col -->
        </div><!-- /.row -->
      </section><!-- /.content -->

    </div>

    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js'></script>
    <script src='https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js'></script>
    <script src='https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js'></script>
    <script src='https://cdn.datatables.net/buttons/1.2.2/js/buttons.colVis.min.js'></script>
    <script src='https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js'></script>
    <script src='https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js'></script>
    <script src='https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js'></script>
    <script src='https://cdn.datatables.net/buttons/1.2.2/js/buttons.bootstrap.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.33/pdfmake.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.33/vfs_fonts.js'></script>

    <script src='https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js'></script>

    < <?php
    } else {
      require 'noacceso.php';
    }
    require 'footer.php';
      ?> <script type=" text/javascript" src="scripts/inventario.js">
      </script> <?php
              }
              ob_end_flush();
                ?>