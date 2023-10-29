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

        <div class="content-start transition">
              <div class="container-fluid dashboard">
                <div class="content-header">
                  <h1>Configuración de certificado</h1>
                </div>

                <div class="row">

                <div class="col-md-6 col-sm-12">
                  <div class="card">
                    <div  id="formularioregistros">

           
                  <form name="formulario" id="formulario" method="post">
                              <div class="card-body">
                                <div class="row">
                                  <div class="mb-3 col-lg-3">
                                    <label>N° Ruc</label>
                                    <input type="text" name="numeroruc" id="numeroruc" placeholder="Número de RUC" class="form-control">
                                  </div>
                                  <div class="mb-3 col-lg-3">
                                    <input type="hidden" name="idcarga" id="idcarga">
                                    <label>Razón social</label>
                                    <input type="text" name="razon_social" id="razon_social" placeholder="Razón Social" class="form-control">
                                  </div>
                                  <div class="mb-3 col-lg-3">
                                    <label>Usuario sol secundario</label>
                                    <input type="text" name="usuarioSol" id="usuarioSol" placeholder="" class="form-control">
                                  </div>
                                  <div class="mb-3 col-lg-3">
                                    <label>Clave sol secundario</label>
                                    <input type="password" name="claveSol" id="claveSol" placeholder="" class="form-control">
                                  </div>
                                </div>

                                <div class="row">
                                    <div class="mb-3 col-lg-4">
                                      <label>Ubicación de certificado</label>
                                      <select class="form-control" name="rutacertificado" id="rutacertificado" > 
                                        <option  value="../certificado/" selected="true">../certificado/</option>
                                        <option  value="C:/sfs/certificado/">C:/sfs/certificado/</option>
                                      </select>
                                    </div>

                                    <div class="mb-3 col-lg-8">
                                      <label>Ruta del webservice FACTURA</label>
                                      <input type="text" name="rutaserviciosunat" id="rutaserviciosunat" placeholder="../wsdl/billService.xml" class="form-control" value="https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl">
                                    </div>

                                </div>

                                <div class="row">
                                    <div hidden class="mb-3 col-lg-6">
                                      <label>Ruta del webservice GUIA</label>
                                      <input type="text" name="webserviceguia" id="webserviceguia" placeholder="https://e-guiaremision.sunat.gob.pe/ol-ti-itemision-guia-gem/billService?wsdl" class="for" value="https://e-beta.sunat.gob.pe/ol-ti-itemision-guia-gem-beta/billService?wsdl">
                                    </div>
                                    <div class="mb-3 col-lg-6">
                                      <label>Cargar certificado (PFX)</label>
                                      <input type="file" class="form-control" name="pfx" id="pfx" value="">
                                      <input type="hidden" name="cargarcertificado" id="cargarcertificado">
                                    </div>
                                    <div class="mb-3 col-lg-6">
                                    <label>Clave de certificado PFX</label>
                                      <div class="input-group">
            
                                        <input type="password" style="height: 10%;" name="keypfx" id="keypfx" placeholder="" class="form-control" required>
                                        <div class="input-group-append" style="top: -12px;position: relative;">
                                          <button class="btn btn-outline-success"  id="btncargar" name="btncargar" onclick="validarclave();" type="button">Verificar</button>
                                        </div>
                                      </div>
                  
                                    </div>
                                    <div class="mb-3 col-lg-6">
                                      <label>Nombre de archivo .pem actual</label>
                                      <input type="text" name="nombrepem" id="nombrepem" placeholder="" class="form-control">
                                    </div>
                           
                                </div>

                        
                              </div>
                      
                              <div class="card-footer text-right">
                                <button class="btn btn-primary" type="submit" id="btnGuardar">Guardar</button>
                              </div>

                            </form>
                            </div>
                  </div>
                </div>



                </div><!-- /.row -->


              </div><!-- End Container-->
            </div><!-- End Content-->


        <?php
  } else {
    require 'noacceso.php';
  }

  require 'footer.php';
  ?>
    <script type="text/javascript" src="scripts/cargarcertificado.js"></script>
    <?php
}
ob_end_flush();
?>