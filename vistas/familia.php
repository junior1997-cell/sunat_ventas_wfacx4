<?php
//Activamos el almacenamiento del Buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
  header("Location: ../vistas/login.php");
} else {
  require 'header.php';

  if ($_SESSION['Logistica'] == 1) {

    ?>
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css"> -->
    <!--Contenido-->
    <!-- Content Wrapper. Contains page content -->
    <!--Content Start-->

    

    <div class="row">
      <div class="col-md-6">
        <div class="content-header">
          <h1>Categoria <button class="btn btn-primary btn-sm" onclick="limpiar_form_familia()" data-bs-toggle="modal" data-bs-target="#modal-agregar-familia">Agregar</button></h1>
        </div>
        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
              <table id="tabla-familia" class="table table-striped" style="width: 100% !important;">
                <thead>
                  <th>#</th>
                  <th>Opciones</th>
                  <th>Nombre</th>
                  <th>Estado</th>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="content-header">
          <h1>Marca <button class="btn btn-primary btn-sm" onclick="limpiar_form_marca()" data-bs-toggle="modal" data-bs-target="#modal-agregar-marca">Agregar</button></h1>
        </div>
        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
              <table id="tabla-marca" class="table table-striped" style="width: 100% !important;">
                <thead>
                  <th>#</th>
                  <th>Opciones</th>
                  <th>Nombre</th>
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

    <!-- MODAL - AGREGAR CATEGORIA -->
    <div class="modal fade text-left" id="modal-agregar-familia" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="myModalLabel1">Añade nueva categoria</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form name="form-familia" id="form-familia" method="POST">
              <div class="row">
                <input type="hidden" name="idfamilia" id="idfamilia">

                <div class="mb-3 col-lg-12">
                  <div class="form-group">
                    <label for="recipient-name" class="col-form-label">Nombre:</label>                  
                    <input type="text" class="form-control" name="nombrec" id="nombrec" onkeyup="mayus(this);">
                  </div>
                  
                </div>
                <div class="p-l-25px col-lg-12" id="barra_progress_familia_div" style="display: none;">
                  <div  class="progress progress-lg custom-progress-3" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"> 
                    <div id="barra_progress_familia" class="progress-bar" style="width: 0%"> <div class="progress-bar-value">0%</div> </div> 
                  </div>
                </div>
              </div>
              <button type="submit" style="display: none;" id="submit-form-familia">Submit</button>
            </form>
          </div>
          <div class="modal-footer">
            <button onclick="limpiar_form_familia()" type="button" class="btn btn-danger" data-bs-dismiss="modal">
              <i class="bx bx-x d-block d-sm-none"></i> <span class="d-none d-sm-block">Cancelar</span>
            </button>
            <button id="guardar_registro_familia" type="button" class="btn btn-primary ml-1" >
              <i class="bx bx-check d-block d-sm-none"></i> <span class="d-none d-sm-block">Guardar</span>
            </button>
          </div>          
        </div>
      </div>
    </div>

    <!-- MODAL - AGREGAR MARCA -->
    <div class="modal fade text-left" id="modal-agregar-marca" tabindex="-1" role="dialog" aria-labelledby="label-marca" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="label-marca">Añade nueva marca</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form name="form-marca" id="form-marca" method="POST">
              <div class="row">
                <input type="hidden" name="idmarca" id="idmarca">

                <div class="mb-3 col-lg-12">
                  <div class="form-group">
                    <label for="recipient-name" class="col-form-label">Nombre:</label>                  
                    <input type="text" class="form-control" name="nombre_marca" id="nombre_marca" onkeyup="mayus(this);">
                  </div>
                  
                </div>
                <div class="p-l-25px col-lg-12" id="barra_progress_marca_div" style="display: none;">
                  <div  class="progress progress-lg custom-progress-3" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"> 
                    <div id="barra_progress_marca" class="progress-bar" style="width: 0%"> <div class="progress-bar-value">0%</div> </div> 
                  </div>
                </div>
              </div>
              <button type="submit" style="display: none;" id="submit-form-marca">Submit</button>
            </form>
          </div>
          <div class="modal-footer">
            <button onclick="limpiar_form_marca()" type="button" class="btn btn-danger" data-bs-dismiss="modal">
              <i class="bx bx-x d-block d-sm-none"></i> <span class="d-none d-sm-block">Cancelar</span>
            </button>
            <button id="guardar_registro_marca" type="button" class="btn btn-primary ml-1" >
              <i class="bx bx-check d-block d-sm-none"></i> <span class="d-none d-sm-block">Guardar</span>
            </button>
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
  <script type="text/javascript" src="scripts/familia.js"></script>
  <script type="text/javascript" src="scripts/marca.js"></script>
  <?php
}
ob_end_flush();
?>