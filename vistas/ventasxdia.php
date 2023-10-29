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
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css"> -->
    <!--Contenido-->
    <!-- Content Wrapper. Contains page content -->

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




                <!-- <div class="panel-body"  id="formularioregistros">
  <form name="formulario" id="formulario" method="POST"> -->

                <form name="formulario" id="formulario">
                  <input type="hidden" name="idempresa" id="idempresa" value="<?php echo $_SESSION['idempresa']; ?>">
                  <div class="row">
                    <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
                      <label> Año: </label>
                      <select class="form-control" name="ano" id="ano">

                        <option value="2017">2017</option>
                        <option value="2018">2018</option>
                        <option value="2019">2019</option>
                        <option value="2020">2020</option>
                        <option value="2021">2021</option>
                        <option value="2022">2022</option>
                        <option value="2023">2023</option>
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                        <option value="2026">2026</option>
                        <option value="2027">2027</option>
                        <option value="2028">2028</option>
                        <option value="2029">2029</option>
                      </select>
                      <input type="hidden" name="ano_1" id="ano_1">
                    </div>



                    <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
                      <label> Mes: </label>
                      <select class="form-control" name="mes" id="mes">
                        <option value="00">todos</option>
                        <option value="1">Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3">Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>
                      </select>
                      <input type="hidden" name="mes_1" id="mes_1">
                    </div>


                    <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
                      <label> Día: </label>
                      <select class="form-control" name="dia" id="dia">
                        <option value="1">01</option>
                        <option value="2">02</option>
                        <option value="3">03</option>
                        <option value="4">04</option>
                        <option value="5">05</option>
                        <option value="6">06</option>
                        <option value="7">07</option>
                        <option value="8">08</option>
                        <option value="9">09</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="20">20</option>
                        <option value="21">21</option>
                        <option value="22">22</option>
                        <option value="23">23</option>
                        <option value="24">24</option>
                        <option value="25">25</option>
                        <option value="26">26</option>
                        <option value="27">27</option>
                        <option value="28">28</option>
                        <option value="29">29</option>
                        <option value="30">30</option>
                        <option value="31">31</option>

                      </select>
                      <input type="hidden" name="mes_1" id="mes_1">
                    </div>



                    <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
                      <label> Moneda: </label>
                      <select class="form-control" name="tmonedaa" id="tmonedaa">
                        <option value="PEN">PEN</option>
                        <option value="USD">USD</option>
                      </select>
                    </div>
                  </div>


                  <div class="form-group col-lg-12 justify-content-center text-center mt-3 mb-3">
                    <button class="btn btn-danger btn-sm" type="button" id="btnReportePDFMes" data-toggle="tooltip"
                      title="Reporte pdf por mes">Reporte PDF Mensual
                    </button>

                  </div>

                  <div class="col-lg-12">
                    <div class="panel-body table-responsive" id="listadoregistros">
                      <table id="tbllistadoVentas" class="table table-striped table-bordered table-condensed table-hover">
                        <thead>
                          <th>Fecha</th>
                          <th>Documento</th>
                          <th>Cliente</th>
                          <th>N° Doc</th>
                          <th>Productos</th>
                          <th>Efectivo</th>
                          <th>Visa</th>
                          <th>Yape</th>
                          <th>Plin</th>
                          <th>Mastercard</th>
                          <th>Deposito</th>
                          <th>Subtotal</th>
                          <th>Igv</th>
                          <th>Total</th>
                          <th>Estado</th>
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
  ?>
    <script type=" text/javascript" src="scripts/inventario.js"></script>


    <!-- <script   type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script> -->
    <?php
}
ob_end_flush();
?>