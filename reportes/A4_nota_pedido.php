
<?php 

  require '../vendor/autoload.php';
  use Luecano\NumeroALetras\NumeroALetras;

  use Endroid\QrCode\Color\Color;
  use Endroid\QrCode\Encoding\Encoding;
  use Endroid\QrCode\ErrorCorrectionLevel;
  use Endroid\QrCode\QrCode;
  use Endroid\QrCode\Label\Label;
  use Endroid\QrCode\Logo\Logo;
  use Endroid\QrCode\RoundBlockSizeMode;
  use Endroid\QrCode\Writer\PngWriter;
  use Endroid\QrCode\Writer\ValidationException;

  date_default_timezone_set('America/Lima'); $date_now = date("d_m_Y__h_i_s_A");
  $imagen_error = "this.src='../dist/svg/404-v2.svg'";
  $toltip = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';    
  $scheme_host =  ($_SERVER['HTTP_HOST'] == 'localhost' ? 'http://localhost/venta_romero/' :  $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].'/');


  // CONSULTAR DATOS
  require_once"../modelos/Notapedido.php"; 

  $nota_venta= new Notapedido();
  $numero_a_letra = new NumeroALetras();

  $rspta        = $nota_venta->imprimirA4($_GET['id']);
  $html_venta = ''; $cont = 1;
  if (empty($rspta['data']['venta'])) {    echo "Comprobante no existe"; die();  }

  foreach ($rspta['data']['detalles'] as $key => $reg) {
    $html_venta .= '<tr>
      <td class="px-1 celda-b-r-1px text-center" >'.$cont++.'</td>
      <td class="px-1 celda-b-r-1px text-center" >'.$reg['codigo'].'</td>
      <td class="px-1 celda-b-r-1px text-align">'.$reg['nombre'].'</td>
      <td class="px-1 celda-b-r-1px text-center" >'.$reg['UM'].'</td>
      <td class="px-1 celda-b-r-1px text-center" >'.$reg['cantidad'].'</td>
      <td class="px-1 celda-b-r-1px text-right" >'.number_format( floatval($reg['p_unitario']) , 2, '.',',').'</td>
      <td class="px-1 celda-b-r-1px text-right" >'.number_format(floatval($reg['descuento']) , 2, '.',',').'</td>
      <td class="px-1 celda-b-r-1px text-right">'.number_format( floatval($reg['a_subtotal']) , 2, '.',',').'</td>
    </tr>';
  }

  // Generar QR
  $dataTxt = "
    Cliente: " . $rspta['data']['venta']['RazonSocial'] . "
    Fecha Enisión: " . $rspta['data']['venta']['fecha_emision'] . "
    Total a pagar: " . $rspta['data']['venta']['total_general'] . "
    Contactanos: ".$rspta['data']['empresa']['telefono1']."
  ";
  $filename = $rspta['data']['venta']['numeracion_07'] . '.png';
  $qr_code = QrCode::create($dataTxt)->setEncoding(new Encoding('UTF-8'))->setErrorCorrectionLevel(ErrorCorrectionLevel::Low)->setSize(600)->setMargin(10)->setRoundBlockSizeMode(RoundBlockSizeMode::Margin)->setForegroundColor(new Color(0, 0, 0))->setBackgroundColor(new Color(255, 255, 255));
  
  $label = Label::create( $rspta['data']['venta']['numeracion_07'])->setTextColor(new Color(255, 0, 0)); // Create generic label  
  $writer = new PngWriter(); // Create IMG
  $result = $writer->write($qr_code, label: $label); 
  $result->saveToFile(__DIR__.'/generador-qr/nota_venta/'.$filename); // Save it to a file  
  $dataUri = $result->getDataUri();// Generate a data URI


  //NUMERO A LETRA
  $numero = $rspta['data']['venta']['total_general'];
  $numeroALetras = new NumeroALetras();
  $texto = $numeroALetras->toWords($numero)
?>

<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-vertical-style="overlay" data-theme-mode="light" data-header-styles="light" data-menu-styles="light" data-toggled="close">

<head>

  <!-- Meta Data -->
  <meta charset="UTF-8">
  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title> <?php echo $rspta['data']['venta']['numeracion_07']; ?> - Nota de Venta </title>
  <meta name="Description" content="Bootstrap Responsive Admin Web Dashboard HTML5 Template">
  <meta name="Author" content="Spruko Technologies Private Limited">
  <meta name="keywords" content="admin,admin dashboard,admin panel,admin template,bootstrap,clean,dashboard,flat,jquery,modern,responsive,premium admin templates,responsive admin,ui,ui kit.">

  <!-- Favicon -->
  <link rel="icon" href="../assets/images/brand-logos/favicon.ico" type="image/x-icon">
  <!-- Main Theme Js -->
  <script src="../assets/js/authentication-main.js"></script>
  <!-- Bootstrap Css -->
  <link id="style" href="../assets/libs/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- Style Css -->
  <link href="../assets/css/styles.min.css" rel="stylesheet">
  <!-- Icons Css -->
  <link href="../assets/css/icons.min.css" rel="stylesheet">
  <!-- Style propio -->
  <link rel="stylesheet" href="../assets/css/style_new.css">

</head>

<body onload="window.print();" style="background-color: white !important;">

  <!-- End Switcher -->
  <div class="container-lg" >
    <div class="row justify-content-center">
      <div class="row gy-4 justify-content-center">
        <div class="col-xl-9">
          <div class="row">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">     
              <div class="d-flex flex-fill flex-wrap gap-4">
                <div class="avatar avatar-xl avatar-rounded"><img src="../files/logo/<?php echo $rspta['data']['empresa']['logo']; ?>" alt="" style="width: 100px; height: auto;"></div>
                <div>
                  <h6 class="mb-1 fw-semibold"><?php echo $rspta['data']['empresa']['nombre_razon_social']; ?></h6>                  
                  <div class="fs-10 mb-0 "><?php echo $rspta['data']['empresa']['domicilio_fiscal']; ?></div>
                  <div class="fs-10 mb-0 text-muted contact-mail text-truncate"><?php echo $rspta['data']['empresa']['correo']; ?></div>
                  <div class="fs-10 mb-0 text-muted"><?php echo $rspta['data']['empresa']['telefono1']; ?> - <?php echo $rspta['data']['empresa']['telefono2']; ?></div>
                </div>
              </div>              
            </div>
            <div class="text-center col-xl-4 col-lg-4 col-md-6 col-sm-6 ms-auto mt-sm-0 mt-3">
              <div class="border border-dark">
                <div class="m-2">                  
                  <h6 class="text-muted mb-2"> RUC: <?php echo $rspta['data']['empresa']['numero_ruc']; ?> </h6>
                  <h6>NOTA DE VENTA ELECTRONICA</h6>
                  <h5><?php echo $rspta['data']['venta']['numeracion_07']; ?></h5>
                </div>                
              </div>              
            </div>
          </div>
        </div>
        <div class="col-xl-9">
          <table class="font-size-10px">
            <tr>
              <th style="font-size: 12px;">Fecha de Emisión</th>
              <td style="font-size: 12px;">: <?php echo $rspta['data']['venta']['fecha_emision']; ?></td>
            </tr>
            <tr>
              <th style="font-size: 12px;">Señor(a)</th>
              <td style="font-size: 12px;">: <?php echo $rspta['data']['venta']['RazonSocial']; ?></td>
            </tr>
            <tr>
              <th style="font-size: 12px;">N° Documento</th>
              <td style="font-size: 12px;">: <?php echo $rspta['data']['venta']['rucCliente']; ?></td>
            </tr>
            <tr>
              <th style="font-size: 12px;">Dirección</th>
              <td style="font-size: 12px;">: <?php echo $rspta['data']['venta']['domicilio_fiscal']; ?></td>
            </tr>            
            <tr>
              <th style="font-size: 12px;">Observación</th>
              <td style="font-size: 12px;">: -</td>
            </tr>
          </table>
        </div>
        
        <div class="col-xl-9">
          <div class="table-responsive">
            <table class="text-nowrap border border-dark mt-1 w-100">
              <thead class="border border-dark">
                <tr >
                  <th class="celda-b-r-1px text-center">#</th>
                  <th class="celda-b-r-1px text-center">CODIGO</th>
                  <th class="celda-b-r-1px text-center">DESCRIPTION</th>
                  <th class="celda-b-r-1px text-center">UM</th>
                  <th class="celda-b-r-1px text-center">CANTIDAD</th>
                  <th class="celda-b-r-1px text-center">PRECIO UND</th>
                  <th class="celda-b-r-1px text-center">DCTO</th>
                  <th class="celda-b-r-1px text-center">SUB TOTAL</th>
                </tr>
              </thead>
              <tbody >
              <?php echo $html_venta; ?>
                                       
              </tbody>
            </table>
          </div>
        </div>        

        <div class="col-xl-9">             
          
          <table  style="width: 100% !important;">            
            <tr>   
              <td class="font-size-12px">
                <span class="">SON: <b><?php echo $texto; ?> 00/100</b>  </span><br>
                <span class="text-muted">Representación impresa de la Nota de Venta Electrónica, puede ser consultada en <?php echo $rspta['data']['empresa']['nombre_razon_social']; ?></span>
              </td>  
              <td>
                <table class="text-nowrap w-100 table-bordered font-size-10px">
                  <tbody>
                    <tr><th class="text-center" colspan="3">CUENTAS BANCARIAS</th></tr>
                    <!-- filtramos los datos <<< SI la cuenta existe ENTONCES se muestran sus datos >>>> de lo contrario se ocultan :) ------>
                    <?php if (!empty($rspta['data']['empresa']['cuenta1'])) : ?>
                        <tr>
                            <td class="px-1"><?php echo $rspta['data']['empresa']['banco1']; ?></td>
                            <td class="px-1">Cta: <?php echo $rspta['data']['empresa']['cuenta1']; ?></td>
                            <td class="px-1">CCI: <?php echo $rspta['data']['empresa']['cuentacci1']; ?></td>
                        </tr>
                    <?php endif; ?>

                    <?php if (!empty($rspta['data']['empresa']['cuenta2'])) : ?>
                        <tr>
                            <td class="px-1"><?php echo $rspta['data']['empresa']['banco2']; ?></td>
                            <td class="px-1">Cta: <?php echo $rspta['data']['empresa']['cuenta2']; ?></td>
                            <td class="px-1">CCI: <?php echo $rspta['data']['empresa']['cuentacci2']; ?></td>
                        </tr>
                    <?php endif; ?>

                    <?php if (!empty($rspta['data']['empresa']['cuenta3'])) : ?>
                        <tr>
                            <td class="px-1"><?php echo $rspta['data']['empresa']['banco3']; ?></td>
                            <td class="px-1">Cta: <?php echo $rspta['data']['empresa']['cuenta3']; ?></td>
                            <td class="px-1">CCI: <?php echo $rspta['data']['empresa']['cuentacci3']; ?></td>
                        </tr>
                    <?php endif; ?>

                    <?php if (!empty($rspta['data']['empresa']['cuenta4'])) : ?>
                        <tr>
                            <td class="px-1"><?php echo $rspta['data']['empresa']['banco4']; ?></td>
                            <td class="px-1">Cta: <?php echo $rspta['data']['empresa']['cuenta4']; ?></td>
                            <td class="px-1">CCI: <?php echo $rspta['data']['empresa']['cuentacci4']; ?></td>
                        </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </td>                 
              <td align="center"><img src=<?php echo $dataUri; ?> width="90" height="auto"></td>
              <td>
                <div class="border border-dark rounded-1">
                  <div class="m-1">
                    <table class="text-nowrap w-100">
                      <tbody>
                        <tr>
                          <td scope="row"><p class="mb-0 font-size-12px">Sub Total</p></td> <th>:</th>
                          <td align="right"><p class="mb-0 "><?php echo $rspta['data']['venta']['n_subtotal']; ?></p></td>
                        </tr>            
                        <tr>
                          <td scope="row"><p class="mb-0 font-size-12px">Descuento </p></td><th>:</th>
                          <td align="right"><p class="mb-0 "><?php echo $rspta['data']['venta']['dest_total']; ?></p></td>
                        </tr>  
                        <tr>
                          <td scope="row"><p class="mb-0 font-size-12px">IGV <span class="text-danger">(0%)</span> </p></td> <th>:</th>
                          <td align="right"><p class="mb-0 "><?php echo $rspta['data']['venta']['IGV']; ?></p></td>
                        </tr>            
                        <tr>
                          <th scope="row"><p class="mb-0 fs-16">Total</p></th> <th>:</th>
                          <td align="right"><p class="mb-0 fw-semibold fs-16"><?php echo $rspta['data']['venta']['total_general']; ?></p></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                
              </td>
            </tr>    

          </table>                         
          
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row .gy-3 -->
    </div>
    <!-- /.row .justify-->
  </div>

  <!-- Bootstrap JS -->
  <script src="../assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Show Password JS -->
  <script src="../assets/js/show-password.js"></script>

</body>

</html>