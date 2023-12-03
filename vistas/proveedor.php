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
    <!-- html5tooltips Styles & animations -->
    <link href="../public/css/html5tooltips.css" rel="stylesheet">
    <link href="../public/css/html5tooltips.animation.css" rel="stylesheet">

    <div class="content-header">
      <h1>Proveedores <button class="btn btn-success btn-sm" onclick="limpiar_form_proveedor()" data-bs-toggle="modal" data-bs-target="#modal-agregar-proveedor">Agregar</button></h1>
    </div>

    <div class="row">

      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
              <table id="tbllistado" class="table table-striped" style="width: 100% !important;">
                <thead>
                  <th>Estado</th>
                  <th>Razón Social</th>
                  <th>Ruc</th>
                  <th>Teléfono</th>
                  <th>Correo</th>
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

    <div class="modal fade text-left" id="modal-agregar-proveedor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="myModalLabel1">Añade nuevo proveedor</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form name="form-proveedor" id="form-proveedor" method="POST">
              <div class="row">               

                <input type="hidden" name="idpersona" id="idpersona">
                <input type="hidden" name="tipo_persona" id="tipo_persona" value="PROVEEDOR">
                <input type="hidden" name="idciudad" id="idciudad">

                <div class="mb-3 col-lg-6">
                  <div class="form-group">
                    <label for="tipo_documento" class="form-label">Tipo Documento:</label>
                    <select class="form-control" name="tipo_documento" id="tipo_documento" required onchange="validate_reglas(this);">
                      <option value="0">S/D</option>
                      <option value="1" selected>DNI</option>
                      <option value="6">RUC</option>
                      <option value="7">PASAPORTE</option>
                      <option value="4">CARNET DE EXTRANJERIA</option>                      
                      <option value="A">CED</option>
                    </select>
                  </div>                  
                </div>

                <div class="mb-3 col-lg-6">
                  <div class="form-group">
                    <label for="numero_documento" class="form-label">N° Documento:</label>
                    <div class="input-group mb-3">
                      <input type="text" name="numero_documento" id="numero_documento"  class="form-control" placeholder="N° documento"  >
                      <button class="btn btn-primary btn-search-sr" type="button"  data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Buscar Sunat/Recniec" onclick="buscar_s_r();"><i class="fas fa-search"></i></button>
                    </div>
                  </div>                 
                </div>

                <div class="mb-3 col-lg-6">
                  <div class="form-group">
                    <label for="nombres" class="form-label">Nombres:</label>                  
                    <input type="text" class="form-control" name="nombres" id="nombres"   onkeyup="to_mayus(this);">
                  </div>                
                </div>

                <div class="mb-3 col-lg-6">
                  <div class="form-group">
                    <label for="apellidos" class="form-label">Apellidos:</label>
                   <input type="text" class="form-control" name="apellidos" id="apellidos"   onkeyup="to_mayus(this);">
                  </div>                  
                </div>

                <div class="mb-3 col-lg-6">
                  <div class="form-group">
                    <label for="razon_social" class="form-label">Razón Social:</label>                    
                    <textarea class="form-control" name="razon_social" id="razon_social"  rows="2" onkeyup="to_mayus(this);"></textarea>
                  </div>                  
                </div>

                <div class="mb-3 col-lg-6">
                  <div class="form-group">
                    <label for="nombre_comercial" class="form-label">Nombre comercial:</label>
                    <textarea class="form-control" name="nombre_comercial" id="nombre_comercial" rows="2" onkeyup="to_mayus(this);"></textarea>                    
                  </div>                  
                </div>

                <div class="mb-3 col-lg-12">
                  <div class="form-group">
                    <label for="domicilio_fiscal" class="form-label">Domicilio fiscal:</label>
                    <textarea class="form-control" name="domicilio_fiscal" id="domicilio_fiscal" rows="2" onkeyup="to_mayus(this);"></textarea>
                  </div>                  
                </div>
                <div class="mb-3 col-lg-6">
                  <div class="form-group">
                    <label for="iddistrito" class="form-label">Distrito:</label>
                    <select class="form-control" name="iddistrito" id="iddistrito" onchange="llenar_dep_prov_ubig(this);">
                    </select>
                  </div>
                </div>
                <div class="mb-3 col-lg-6">
                  <div class="form-group">
                    <label for="idprovincia" class="form-label">Provincia: <span class="chargue-pro"></span> </label>
                    <input type="text" name="idprovincia" id="idprovincia" class="form-control" >
                  </div>    
                </div>
                <div class="mb-3 col-lg-6">                  
                  <div class="form-group">
                    <label for="iddepartamento" class="form-label">Departamento: <span class="chargue-dep"></span></label>
                    <input type="text" name="iddepartamento" id="iddepartamento" class="form-control" >                    
                  </div>    
                </div>   
                
                <div class="mb-3 col-lg-6">                  
                  <div class="form-group">
                    <label for="ubigeo" class="form-label">Ubigeo: <span class="chargue-ubi"></span></label>
                    <input type="text" name="ubigeo" id="ubigeo" class="form-control" >                    
                  </div>    
                </div>   

                <div class="mb-3 col-lg-6">
                  <div class="form-group">
                    <label for="telefono1" class="form-label">Teléfono:</label>
                    <input type="number" class="form-control" name="telefono1" id="telefono1" maxlength="15" >
                  </div> 
                </div> 

                <div class="mb-3 col-lg-6">
                  <div class="form-group">
                    <label for="telefono2" class="form-label">Celular:</label>
                    <input type="number" class="form-control" name="telefono2" id="telefono2" maxlength="15" >
                  </div>                  
                </div>

                <div class="mb-3 col-lg-12">
                  <div class="form-group">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" name="email" id="email" maxlength="50" placeholder="Email">
                  </div>                  
                </div>

                <div class="p-l-25px col-lg-12" id="barra_progress_proveedor_div" style="display: none;">
                  <div  class="progress progress-lg custom-progress-3" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"> 
                    <div id="barra_progress_proveedor" class="progress-bar" style="width: 0%"> <div class="progress-bar-value">0%</div> </div> 
                  </div>
                </div>

              </div>
              <button type="submit" style="display: none;" id="submit-form-proveedor">Submit</button>
            </form>
          </div>
          <div class="modal-footer">
            <button onclick="limpiar_form_proveedor()" type="button" class="btn btn-danger" data-bs-dismiss="modal">
              <i class="bx bx-x d-block d-sm-none"></i>
              <span class="d-none d-sm-block">Cancelar</span>
            </button>
            <button id="guardar_registro_proveedor" type="submit" class="btn btn-primary ml-1">
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
  <script src="../public/js/html5tooltips.js"></script>
  <script type="text/javascript" src="scripts/proveedor.js"></script>
<?php
}
ob_end_flush();
?>