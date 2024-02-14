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
            <form name="form-transferencia" id="form-transferencia" method="POST">
              <!-- DATOS OCULTOS ---->
              <input type="hidden" name="idempresa" id="idempresa" value="<?php echo $_SESSION['idempresa']; ?>">
              <div class="row">
                <div class="col-md-6 border p-3">
                  <h5 class="card-title text-center">Origen</h5>
                  <div class="col-md-12 border-top p-3">

                      <div class="row">
                        <div class="mb-3 col-lg-12">
                          <div class="form-group">
                            <label for="recipient-name" class="form-label" >Almacén (*)</label>
                            <select class="form-control" name="idalmacen1" id="idalmacen1" onchange="selectAlmacen2();"> </select>
                          </div>
                        </div>

                        <div class="mb-3 col-lg-12">
                          <div class="form-group"> 
                            <label for="articulosSelect" class="form-label" >Artículo (*)</label>
                            <select class="form-control" name="idarticulos1" id="idarticulos1" onchange="verStock();"></select>
                          </div>
                        </div>

                        <div class="mb-3 col-lg-12">
                          <div class="form-group">
                            <label for="cantidadInput" class="form-label">Cantidad (*)</label>
                            <input type="number" class="form-control" name="cantidad" id="cantidad">
                            <p>Stock Máximo: <span id="stock" name="stock" ></span></p>
                          </div>
                        </div>

                      </div>                    
                  </div>
                </div>

                <div class="col-md-6 border p-3">
                  <h5 class="card-title text-center">Destino</h5>
                  <div class="col-md-12 border-top p-3">
                    <div class="row">
                      <div class="mb-3 col-lg-12">
                        <div class="form-group">
                          <label for="almacenSelect" class="form-label" >Almacén (*)</label>
                          <select class="form-control" name="idalmacen2" id="idalmacen2" onchange="selectArticulos2();"> <!-- listar los almacenes disponibles --> </select>
                        </div>
                      </div>

                      <div class="mb-3 col-lg-12">
                        <div class="form-group">
                          <label for="articulosSelect" class="form-label" >Artículo (*)</label>
                          <select class="form-control" name="idarticulos2" id="idarticulos2"></select>
                        </div>
                      </div>
                    </div> 
                  </div>
                </div>
              </div>
              <div class="card-footer">
                <div class="float-end">
                  <button onclick="limpiar()" type="button" class="btn btn-light m-1">
                    Limpiar
                  </button>
                  <button type="submit" id="guardar_transferencia" class="btn btn-primary m-1">
                    Guardar
                  </button>
                </div>
              </div>
            </form>
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

<?php
}
ob_end_flush();
?>