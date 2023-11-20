<?php
session_start();
//Activamos el almacenamiento del Buffer
ob_start();


if (!isset($_SESSION["nombre"])) {
  header("Location: ../vistas/login.php");
} else {
  require 'header.php';

  if ($_SESSION['Ventas'] == 1) {


?>



    <div class="content-header">
      <h1>Caja chica del sistema 
        <button class="btn btn-success btn-sm" id="abrCajaBtn" data-bs-toggle="modal" data-bs-target="#agregarsaldoInicial" onclick="verificarSaldoInicial()">Aperturar caja</button>
        <button type="button" class="btn btn-primary" id="cerrarCajaBtn" onclick="cerrarCaja();">Cerrar caja </button>

      </h1>
    </div>
    <div class="row mt-3">
      <div class="col-xl-9">
        <div class="card custom-card">
          <div class="card-body p-0">
            <div class="row g-0">
              <div class="col-xl-4 border-end border-inline-end-dashed " hidden>
                <div class="d-flex flex-wrap align-items-top p-4">
                  <div class="me-3 lh-1"> <span class="avatar avatar-md avatar-rounded bg-primary shadow-sm"> <i class="ti ti-package fs-18"></i> </span> </div>
                  <div class="flex-fill">
                    <h5 class="fw-semibold mb-1" id="total-compras"><i class="fas fa-spinner fa-pulse fa-1x"></i></h5>
                    <p class="text-muted mb-0 fs-12">Compras</p>
                  </div>
                  <div hidden> <span class="badge bg-success-transparent"><i class="ri-arrow-up-s-line align-middle me-1 d-inline-block"></i>1.31%</span> </div>
                </div>
              </div>
              <div class="col-xl-3 border-end border-inline-end-dashed">
                <div class="d-flex flex-wrap align-items-top p-4">
                  <div class="me-3 lh-1"> <span class="avatar avatar-md avatar-rounded bg-warning shadow-sm"> <i class="ti ti-packge-import fs-18"></i> </span> </div>
                  <div class="flex-fill">
                    <h5 class="fw-semibold mb-1" id="total_saldoini"><i class="fas fa-spinner fa-pulse fa-1x"></i></h5>
                    <p class="text-muted mb-0 fs-12">Saldo Inicial</p>
                  </div>
                  <div hidden> <span class="badge bg-success-transparent"><i class="ri-arrow-up-s-line align-middle me-1 d-inline-block"></i>12.05%</span> </div>
                </div>
              </div>
              <div class="col-xl-3 border-end border-inline-end-dashed" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Factura-Boleta-Nota-Pedido">
                <div class="d-flex flex-wrap align-items-top p-4">
                  <div class="me-3 lh-1"> <span class="avatar avatar-md avatar-rounded bg-primary shadow-sm"> <i class="ti ti-package fs-18"></i> </span> </div>
                  <div class="flex-fill">
                    <h5 class="fw-semibold mb-1" id="total_ventas"><i class="fas fa-spinner fa-pulse fa-1x"></i></h5>
                    <p class=" text-muted mb-0 fs-12">Ventas</p>
                  </div>
                  <div hidden> <span class="badge bg-danger-transparent"><i class="ri-arrow-down-s-line align-middle me-1"></i>1.14%</span> </div>
                </div>
              </div>
              <div class="col-xl-3 border-end border-inline-end-dashed">
                <div class="d-flex flex-wrap align-items-top p-4">
                  <div class="me-3 lh-1"> <span class="avatar avatar-md avatar-rounded bg-secondary shadow-sm"> <i class="ti ti-rocket fs-18"></i> </span> </div>
                  <div class="flex-fill">
                    <h5 class="fw-semibold mb-1" id="total_ingreso"><i class="fas fa-spinner fa-pulse fa-1x"></i></h5>
                    <p class=" text-muted mb-0 fs-12">Ingresos</p>
                  </div>
                  <div hidden> <span class="badge bg-danger-transparent"><i class="ri-arrow-down-s-line align-middle me-1"></i>1.14%</span> </div>
                </div>
              </div>
              <div class="col-xl-3">
                <div class="d-flex flex-wrap align-items-top p-4">
                  <div class="me-3 lh-1"> <span class="avatar avatar-md avatar-rounded bg-success shadow-sm"> <i class="ti ti-wallet fs-18"></i> </span> </div>
                  <div class="flex-fill">
                    <h5 class="fw-semibold mb-1" id="total_gasto"><i class="fas fa-spinner fa-pulse fa-1x"></i></h5>
                    <p class="text-muted mb-0 fs-12">Egresos</p>
                  </div>
                  <div hidden> <span class="badge bg-success-transparent"><i class="ri-arrow-up-s-line align-middle me-1 d-inline-block"></i>2.58%</span> </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3">
        <div class="card custom-card card-bg-primary text-fixed-white">
          <div class="card-body p-0">
            <div class="d-flex align-items-top p-4 flex-wrap">
              <div class="me-3 lh-1"> <span class="avatar avatar-md avatar-rounded bg-white text-primary shadow-sm"> <i class="ti ti-coin fs-18"></i> </span> </div>
              <div class="flex-fill">
                <h5 class="fw-semibold mb-1 text-fixed-white" id="total_caja"><i class="fas fa-spinner fa-pulse fa-1x"></i></h5>
                <p class="op-7 mb-0 fs-12">Total en caja</p>
              </div>
              <div hidden> <span class="badge bg-success"><i class="ri-arrow-up-s-line align-middle me-1 d-inline-block"></i>14.69%</span> </div>
            </div>
          </div>
        </div>
      </div>
    </div>


    <div class="col-xxl-12 col-xl-12">
      <div class="card custom-card">
        <div class="card-body">
          <ul class="nav nav-tabs tab-style-2 nav-justified mb-3 d-sm-flex d-block" id="myTab1" role="tablist">
            <li class="nav-item" role="presentation"> <button class="nav-link active" id="order-tab" data-bs-toggle="tab" data-bs-target="#order-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true" tabindex="-1"><i class="ri-gift-line me-1 align-middle"></i>Ventas</button> </li>
            <li class="nav-item" role="presentation" onclick="listar('ingreso');"> <button class="nav-link" id="confirmed-tab" data-bs-toggle="tab" data-bs-target="#confirm-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false"><i class="ri-check-double-line me-1 align-middle"></i>Ingresos</button> </li>
            <li class="nav-item" role="presentation" onclick="listar('gasto');"> <button class="nav-link" id="shipped-tab" data-bs-toggle="tab" data-bs-target="#shipped-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false" tabindex="-1"><i class="ri-shopping-bag-3-line me-1 align-middle"></i>Egresos</button> </li>
            <li class="nav-item" role="presentation"> <button class="nav-link" id="delivered-tab" data-bs-toggle="tab" data-bs-target="#delivered-tab-pane" type="button" role="tab" aria-selected="false" tabindex="-1"><i class="ri-truck-line me-1 align-middle"></i>Resumen</button> </li>
          </ul>
          <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active text-muted" id="order-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
              <ul class="ps-3 mb-0">

                <div class="table-responsive">

                  <table id="tblistadoVentas" class="table table-striped" style="width: 100% !important;">
                    <thead>
                      <tr>
                        <th scope="col">Fecha</th>
                        <th scope="col">N° Comprobante</th>
                        <th scope="col">R.U.C</th>
                        <th scope="col">Razon Social</th>
                        <th scope="col">Importe</th>
                        <th scope="col">Descripción</th>
                        <th scope="col">Tipo Comprobante</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>

                      </tr>
                    </tbody>
                  </table>

                </div>

              </ul>
            </div>
            <div class="tab-pane fade text-muted" id="confirm-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
              <ul class="ps-3 mb-0">

                <div class="table-responsive">

                <table id="tblistadoingreso" class="table table-striped" style="width: 100% !important;">
                    <thead>
                      <tr>
                        <th scope="col">Fecha</th>
                        <th scope="col">Glosa</th>
                        <th scope="col">Naturaleza</th>
                        <th scope="col">Total</th>
                        <th scope="col">Acreedor</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>

                      </tr>
                    </tbody>
                  </table>

                </div>


              </ul>
            </div>
            <div class="tab-pane fade text-muted" id="shipped-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">
              <ul class="ps-3 mb-0">


                <div class="table-responsive">

                  <table id="tblistadogasto" class="table table-striped" style="width: 100% !important;">
                    <thead>
                      <tr>
                        <th scope="col">Fecha</th>
                        <th scope="col">Glosa</th>
                        <th scope="col">Naturaleza</th>
                        <th scope="col">Total</th>
                        <th scope="col">Acreedor</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>

                      </tr>
                    </tbody>
                  </table>

                </div>

              </ul>
            </div>
            <div class="tab-pane fade text-muted" id="delivered-tab-pane" role="tabpanel" tabindex="0" aria-labelledby="delivered-tab">
              <ul class="list-unstyled mb-0">
                <li>EN DESARROLLO  <i class="fas fa-spinner fa-pulse fa-1x"></i> </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>

    </div><!-- /.row -->

    <div class="modal fade text-left" id="agregarsaldoInicial" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="myModalLabel1">Apertura tu caja</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form name="formulario" id="formulario" method="POST">
              <div class="row">
                <div class="mb-3 col-lg-12">
                  <label for="message-text" class="col-form-label">Monto Inicial:</label>
                  <input type="text" class="form-control" name="saldo_inicial" id="saldo_inicial" required>
                </div>
              </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
              <i class="bx bx-x d-block d-sm-none"></i>
              <span class="d-none d-sm-block">Cancelar</span>
            </button>
            <button id="btnGuardarSaldoInicial" type="submit" class="btn btn-primary ml-1">
              <i class="bx bx-check d-block d-sm-none"></i>
              <span class="d-none d-sm-block">Agregar</span>
            </button>
          </div>
          </form>
        </div>
      </div>
    </div>


  <?php
  } else {
    require 'noacceso.php';
  }

  require 'footer.php';
  ?>
  <script type="text/javascript" src="scripts/cajachica.js"></script>
<?php
}
ob_end_flush();
?>