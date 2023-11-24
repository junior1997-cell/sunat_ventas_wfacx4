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
          <h1>Categoria 
            <button class="btn btn-primary btn-sm" onclick="limpiar_form_familia()" data-bs-toggle="modal" data-bs-target="#modal-agregar-familia">Agregar</button>
            <button class="btn btn-success btn-sm" onclick="limpiar_form_import_familia();" data-bs-toggle="modal" data-bs-target="#modal-importar-familia">Importar</button>
          </h1>
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
          <h1>Marca 
            <button class="btn btn-primary btn-sm" onclick="limpiar_form_marca()" data-bs-toggle="modal" data-bs-target="#modal-agregar-marca">Agregar</button>
            <button class="btn btn-success btn-sm" onclick="limpiar_form_import_marca();" data-bs-toggle="modal" data-bs-target="#modal-importar-marca">Importar</button>
          </h1>
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

    <div class="modal fade text-left" id="modal-importar-familia" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="myModalLabel1">Importa tus categorias</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="alert alert-danger border border-danger mb-3 p-2"> 
              <div class="d-flex align-items-start"> 
                <div class="me-2"> 
                  <svg class="flex-shrink-0 svg-danger" xmlns="http://www.w3.org/2000/svg" height="1.5rem" viewBox="0 0 24 24" width="1.5rem" fill="#000000"> <path d="M0 0h24v24H0V0z" fill="none"></path><path d="M11 7h2v2h-2zm0 4h2v6h-2zm1-9C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"></path>                  </svg>
                </div> 
                <div class="text-danger w-100"> 
                  <div class="fw-semibold d-flex justify-content-between">Para importar categorias masivamente.
                    <button type="button" class="btn-close p-0" data-bs-dismiss="alert" aria-label="Close"><i class="bi bi-x"></i></button>
                  </div> 
                  <div class="fs-12 op-8 mb-1">Tiene que tener encuenta los siguientes columnas y el orden:</div>    
                  <div class="row fs-12">
                    <div class="col-lg-4 px-0">                      
                      <span>1. Nombre</span><br> <span>2. Estado</span>                           
                    </div>                    
                  </div>  
                  <div class="d-inline-flex mt-2"> 
                    <button type="button" class="btn btn-outline-danger font-size-10px py-1 d-inline-block m-r-5px" data-bs-dismiss="alert" aria-label="Close">Cerrar</button> 
                    <a href="../assets/excel/plantilla-categoria.xlsx" class="btn btn-success font-size-10px py-1 d-inline-block" download="Plantilla-categoria">Descargar plantilla</a> 
                  </div>           
                </div> 
              </div> 
            </div>
            <form class="form" name="form-importar-familia" id="form-importar-familia" method="POST" enctype="multipart/form-data">
              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group">
                    <label for="upload_file_familia" class="form-label" id="upload-container-familia"> Cargar excel: </label>
                      <input class="form-control" id="upload_file_familia" name="upload_file_familia" type="file"  accept=".xls,.xlsx, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel">                    
                    <div id="files-list-container-familia"></div>
                  </div>
                </div>  
                <div class="p-l-25px col-lg-12" id="barra_progress_imp_familia_div" style="display: none;">
                  <div  class="progress progress-lg custom-progress-3" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"> 
                    <div id="barra_progress_imp_familia" class="progress-bar" style="width: 0%"> <div class="progress-bar-value">0%</div> </div> 
                  </div>
                </div>              
              </div>
              <button type="submit" style="display: none;" id="submit-form-import-familia">Submit</button>
            </form>
          </div>
          <div class="modal-footer">
            <button  type="button" class="btn btn-danger" data-bs-dismiss="modal">
              <i class="bx bx-x d-block d-sm-none"></i>
              <span class="d-none d-sm-block">Cancelar</span>
            </button>
            <button id="guardar_registro_import_familia" type="submit" class="btn btn-primary ml-1">
              <i class="bx bx-check d-block d-sm-none"></i>
              <span class="d-none d-sm-block">Agregar</span>
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

    <div class="modal fade text-left" id="modal-importar-marca" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="myModalLabel1">Importa tus marca</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="alert alert-danger border border-danger mb-3 p-2"> 
              <div class="d-flex align-items-start"> 
                <div class="me-2"> 
                  <svg class="flex-shrink-0 svg-danger" xmlns="http://www.w3.org/2000/svg" height="1.5rem" viewBox="0 0 24 24" width="1.5rem" fill="#000000"> <path d="M0 0h24v24H0V0z" fill="none"></path><path d="M11 7h2v2h-2zm0 4h2v6h-2zm1-9C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"></path>                  </svg>
                </div> 
                <div class="text-danger w-100"> 
                  <div class="fw-semibold d-flex justify-content-between">Para importar marcas masivamente.
                    <button type="button" class="btn-close p-0" data-bs-dismiss="alert" aria-label="Close"><i class="bi bi-x"></i></button>
                  </div> 
                  <div class="fs-12 op-8 mb-1">Tiene que tener encuenta los siguientes columnas y el orden:</div>    
                  <div class="row fs-12">
                    <div class="col-lg-4 px-0">                      
                      <span>1. Nombre</span><br> <span>2. Estado</span>                           
                    </div>                    
                  </div>  
                  <div class="d-inline-flex mt-2"> 
                    <button type="button" class="btn btn-outline-danger font-size-10px py-1 d-inline-block m-r-5px" data-bs-dismiss="alert" aria-label="Close">Cerrar</button> 
                    <a href="../assets/excel/plantilla-marca.xlsx" class="btn btn-success font-size-10px py-1 d-inline-block" download="Plantilla-marca">Descargar plantilla</a> 
                  </div>           
                </div> 
              </div> 
            </div>

            <form class="form" name="form-importar-marca" id="form-importar-marca" method="POST"  enctype="multipart/form-data">
              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group">
                    <label for="upload_file_marca" class="form-label" id="upload-container-marca"> Cargar excel: </label>
                      <input class="form-control" id="upload_file_marca" name="upload_file_marca" type="file"  accept=".xls,.xlsx, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel">                    
                    <div id="files-list-container-marca"></div>
                  </div>
                </div>  
                <div class="p-l-25px col-lg-12" id="barra_progress_imp_marca_div" style="display: none;">
                  <div  class="progress progress-lg custom-progress-3" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"> 
                    <div id="barra_progress_imp_marca" class="progress-bar" style="width: 0%"> <div class="progress-bar-value">0%</div> </div> 
                  </div>
                </div>           
              </div>
              <button type="submit" style="display: none;" id="submit-form-import-marca">Submit</button>              
            </form>            
          </div>
          <div class="modal-footer">
            <button  type="button" class="btn btn-danger" data-bs-dismiss="modal">
              <i class="bx bx-x d-block d-sm-none"></i>
              <span class="d-none d-sm-block">Cancelar</span>
            </button>
            <button id="guardar_registro_import_marca" type="submit" class="btn btn-primary ml-1">
              <i class="bx bx-check d-block d-sm-none"></i>
              <span class="d-none d-sm-block">Agregar</span>
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