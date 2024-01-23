<?php
session_start();
//Activamos el almacenamiento del Buffer
ob_start();


if (!isset($_SESSION["nombre"])) {
  header("Location: ../vistas/login.php");
} else {
  require 'header.php';

  if ($_SESSION['Logistica'] == 1) {
    ?>
      <div class="content-header">
        <h1>Lista de compras <a class="btn btn-success btn-sm" href="transferencia_stock.php">Transferir Stock</a></h1>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">

              <div class="table-responsive">
                <table id="tbllistado" class="table text-nowrap table-striped table-hover" style="width: 100% !important;">
                  <thead>
                    <tr>
                      <th scope="col">Acciones</th>
                      <th scope="col">Fecha</th>
                      <th scope="col">Almacem</th>
                      <th scope="col">Artícilo</th>
                      <th scope="col" style="background-color: #A7FF64;">Unidades Tranferidas</th>
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


      </div><!-- /.row -->


    <?php
  } else {
    require 'noacceso.php';
  }

  require 'footer.php';
  ?>
  <script type="text/javascript" src="scripts/transferencia_stock.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>


<?php
}
ob_end_flush();
?>