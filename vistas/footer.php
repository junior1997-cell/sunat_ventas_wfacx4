<?php
require_once "../modelos/Factura.php";
$factura = new Factura();

$datos = $factura->datosemp($_SESSION['idempresa']);
$datose = $datos->fetch_object();

?>
</div>
</div>




</div>
<!-- Scroll To Top -->
<!-- <div class="scrollToTop">
  <span class="arrow"><i class="ri-arrow-up-s-fill fs-20"></i></span>
</div> -->

<div id="responsive-overlay"></div>
<script src="../custom/modules/jquery/jquery.min.js"></script>
<!-- Popper JS -->
<script src="../assets/libs/@popperjs/core/umd/popper.min.js"></script>
<!-- Bootstrap JS -->
<script src="../assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Defaultmenu JS -->
<script src="../assets/js/defaultmenu.min.js"></script>
<!-- Node Waves JS-->
<script src="../assets/libs/node-waves/waves.min.js"></script>
<!-- Sticky JS -->
<script src="../assets/js/sticky.js"></script>
<!-- Simplebar JS -->
<script src="../assets/libs/simplebar/simplebar.min.js"></script>
<script src="../assets/js/simplebar.js"></script>
<!-- Color Picker JS -->
<script src="../assets/libs/@simonwep/pickr/pickr.es5.min.js"></script>

<!-- Custom JS -->
<script src="../assets/js/custom.js"></script>
<!-- Custom-Switcher JS -->
<script src="../assets/js/custom-switcher.min.js"></script>

<!-- select2 -->
<script src="../assets/libs/select2/js/select2.full.min.js"></script>


<script src="../public/js/jquery.PrintArea.js"></script>
<script src="../public/js/toastr.js"></script>
<script src="../public/js/simpleXML.js"></script>

<!-- jquery-validation -->
<script src="../assets/libs/jquery-validation/jquery.validate.min.js"></script>
<script src="../assets/libs/jquery-validation/additional-methods.min.js"></script>


<!-- DATATABLES -->
<script src="../public/datatables/jquery.dataTables.min.js"></script>
<script src="../public/datatables/dataTables.buttons.min.js"></script>
<script src="../public/datatables/buttons.html5.min.js"></script>
<script src="../public/datatables/buttons.colVis.min.js"></script>
<script src="../public/datatables/jszip.min.js"></script>
<script src="../public/datatables/pdfmake.min.js"></script>
<script src="../public/datatables/vfs_fonts.js"></script>

<!-- sweet alert -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript" src="../assets/js/funcion_general.js"></script>

<!-- JQuery ZOOM imagen -->
<script type="text/javascript" src="../assets/libs/jquery-zoom/jquery.zoom.js"></script>

<script>
  $('.precio_dolar_hoy').on('click', function () { $('#modalTcambio').modal('show'); console.log('ver precio dolar'); });
</script>

</body>

</html>