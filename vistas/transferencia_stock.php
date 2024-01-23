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
      <h1>Tranferencia de Stock</h1>
    </div>

    <div class="row">

      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <form name="formulario" id="formulario" method="  ">
              <div class="row">
                <div class="col-md-6 border p-3" >
                  <h5 class="card-title text-center">Origen</h5>
                  <div class="col-md-12 border-top p-3">
                    <div class="form-group">
                      <div class="row">
                          <div class="col-md-3 mb-4 d-flex align-items-center">
                              <label for="almacenSelect" style="margin-left: 0.5cm; font-size: 16px;" >Almacén</label>
                          </div>
                          <div class="col-md-8 ">
                              <select class="form-control mb-4 " name="almacen1" id="almacen1">
                                  <!-- listar los almacenes disponibles -->
                              </select>
                          </div>
                      </div>

                      <div class="row">
                          <div class="col-md-3 mb-4 d-flex align-items-center">
                              <label for="articulosSelect" style="margin-left: 0.5cm; font-size: 16px;">Artículo</label>
                          </div>
                          <div class="col-md-8">
                              <select class="form-control mb-4" name="articulos1" id="articulos1">
                                  <!-- Opciones para los artículos -->
                              </select>
                          </div>
                      </div>

                      <div class="row">
                          <div class="col-md-3 mb-4 d-flex align-items-center">
                              <label for="cantidadInput" style="margin-left: 0.5cm; font-size: 16px;">Cantidad</label>
                          </div>
                          <div class="col-md-8">
                              <input type="number" class="form-control mb-0" name="cantidad1" id="cantidad1">
                              <p>Stock Máximo: <span id="stock1"></span></p>
                              
                          </div>
                      </div>
                    </div>
                  </div> 
                </div>

                <div class="col-md-6 border p-3" >
                    <h5 class="card-title text-center">Destino</h5>
                    <div class="col-md-12 border-top p-3">
                    <div class="form-group">
                      <div class="row">
                          <div class="col-md-3 mb-4 d-flex align-items-center">
                              <label for="almacenSelect" style="margin-left: 0.5cm; font-size: 16px;" >Almacén</label>
                          </div>
                          <div class="col-md-8 ">
                              <select class="form-control mb-4" name="almacen2" id="almacen2">
                                  <!-- listar los almacenes disponibles -->
                              </select>
                          </div>
                      </div>

                      <div class="row">
                          <div class="col-md-3 mb-4 d-flex align-items-center">
                              <label for="articulosSelect" style="margin-left: 0.5cm; font-size: 16px;">Artículo</label>
                          </div>
                          <div class="col-md-8">
                              <select class="form-control mb-4" name="articulos2" id="articulos2">
                                  <!-- Opciones para los artículos -->
                              </select>
                          </div>
                      </div>

                    </div>
                    <!-- Contenido del destino -->
                </div>
              </div>
            </form>

          </div>
        </div>
        <div class="row">
          <div class="col-md-12 mb-4 d-flex justify-content-end">
              <button class="btn btn-danger btn-sm" style="margin-right: 0.5cm; font-size: 14px;" onclick="limpiar();" id="Limpiar" >Limpiar</button>
              <button class="btn btn-success btn-sm" style="margin-right: 0.5cm; font-size: 14px;" onclick="guardar_y_editar_stock();" id="btnguargar" >Guardar</button>
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