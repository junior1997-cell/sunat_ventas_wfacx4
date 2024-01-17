
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


  // Generar QR
  $dataTxt = "RUC_CLIENTE|CODIGO_FUNAT_FACT|SERIE_FACT|NUMERO_FACT|IMPUESTO|MONTO_TOTAL|05/12/2023|CODIGO_SUNAT_DOC|RUC_CLIENTE|";
  $filename = 'TK001-1' . '.png';
  $qr_code = QrCode::create($dataTxt)->setEncoding(new Encoding('UTF-8'))->setErrorCorrectionLevel(ErrorCorrectionLevel::Low)->setSize(600)->setMargin(10)->setRoundBlockSizeMode(RoundBlockSizeMode::Margin)->setForegroundColor(new Color(0, 0, 0))->setBackgroundColor(new Color(255, 255, 255));
  
  $label = Label::create( 'TK001-01')->setTextColor(new Color(255, 0, 0)); // Create generic label  
  $writer = new PngWriter(); // Create IMG
  $result = $writer->write($qr_code, label: $label); 
  $result->saveToFile(__DIR__.'/generador-qr/nota_venta/'.$filename); // Save it to a file  
  $dataUri = $result->getDataUri();// Generate a data URI

?>

<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-vertical-style="overlay" data-theme-mode="light" data-header-styles="light" data-menu-styles="light" data-toggled="close">

<head>

  <!-- Meta Data -->
  <meta charset="UTF-8">
  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title> TK001-1 - Nota de Venta </title>
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
  <div class="container-lg">
    <div class="row justify-content-center">
      <div class="row gy-3">
        <div class="col-xl-12">
          <div class="row">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">     
              <div class="d-flex flex-fill flex-wrap gap-3">
                <div class="avatar avatar-xl avatar-rounded"><img src="../assets/images/brand-logos/logo-mia.png" alt=""></div>
                <div>
                  <h6 class="mb-1 fw-semibold">NEGOCIOS MIA </h6>                  
                  <div class="fs-11 mb-0 "> Barrio San Jose - San Martin - Peru</div>
                  <div class="fs-11 mb-0 text-muted contact-mail text-truncate">gerencia@negociosmia.jdl.pe</div>
                  <div class="fs-11 mb-0 text-muted">968 675 460 - 968 675 460</div>
                </div>
              </div>              
            </div>
            <div class="text-center col-xl-4 col-lg-4 col-md-6 col-sm-6 ms-auto mt-sm-0 mt-3">
              <div class="border border-dark">
                <div class="m-2">                  
                  <h6 class="text-muted mb-2"> RUC: 10009580552 </h6>
                  <h5>NOTA DE VENTA ELECTRONICA</h5>
                  <h5>TK001-1</h5>
                </div>                
              </div>              
            </div>
          </div>
        </div>
        <div class="col-xl-12">
          <table class="font-size-10px">
            <tr>
              <th>Fecha de Emisión</th><td>: <?php echo  date('Y-m-d H:i:s'); ?></td>
            </tr>
            <tr>
              <th>Señor(es)</th><td>: SEVENS INGENIEROS S.A.C</td>
            </tr>
            <tr>
              <th>RUC</th><td>: 20606456892</td>
            </tr>
            <tr>
              <th>Dirección</th><td>: PJ. YUNGAY 151 P.J. SANTA ROSA LAMBAYEQUE-CHICLAYOCHICLAYO </td>
            </tr>            
            <tr>
              <th>Observación</th><td>: -</td>
            </tr>
          </table>
        </div>
        
        <div class="col-xl-12">
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
                <?php 
                  for ($i=1; $i < 15; $i++) { 
                    echo '<tr>
                    <td class="px-1 celda-b-r-1px text-center">'.$i.'</td>
                    <td class="px-1 celda-b-r-1px text-center">PR00043</td>
                    <td class="px-1 celda-b-r-1px ">Branded hoodie ethnic style</td>
                    <td class="px-1 celda-b-r-1px text-center">NIU</td>
                    <td class="px-1 celda-b-r-1px text-center">3</td>
                    <td class="px-1 celda-b-r-1px text-right">60.00</td>
                    <td class="px-1 celda-b-r-1px text-right">0.00</td>
                    <td class="px-1 celda-b-r-1px text-right">180.00</td>
                  </tr>';
                  }
                ?>
                
                                       
              </tbody>
            </table>
          </div>
        </div>        

        <div class="col-xl-12">             
          
          <table  style="width: 100% !important;">            
            <tr>   
              <td class="font-size-12px">
                <span class="">SON: <b>MILQUINIENTOS SETENTS Y CINCO 00/100</b>  </span><br>
                <span class="text-muted">Representación impresa de la Boleta de Venta Electrónica puede ser consultada en jdl.pe </span>
              </td>  
              <td>
                <table class="text-nowrap w-100 table-bordered font-size-10px">
                  <tbody>
                    <tr><th class="text-center" colspan="3">CUENTAS BANCARIAS</th></tr>
                    <tr><td class="px-1">BCP</td> <td class="px-1">Cta: 543543566345654</td> <td class="px-1">CCI: 896869687</td></tr>            
                    <tr><td class="px-1">BBVA</td> <td class="px-1">Cta: 543543566345654</td> <td class="px-1">CCI: 896869687</td></tr>            
                    <tr><td class="px-1">NACION</td> <td class="px-1">Cta: 543543566345654</td> <td class="px-1">CCI: 896869687</td></tr>            
                    
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
                          <td align="right"><p class="mb-0 ">720.00</p></td>
                        </tr>            
                        <tr>
                          <td scope="row"><p class="mb-0 font-size-12px">Descuento </p></td><th>:</th>
                          <td align="right"><p class="mb-0 ">0.00</p></td>
                        </tr>  
                        <tr>
                          <td scope="row"><p class="mb-0 font-size-12px">IGV <span class="text-danger">(0%)</span> </p></td> <th>:</th>
                          <td align="right"><p class="mb-0 ">0.00</p></td>
                        </tr>            
                        <tr>
                          <th scope="row"><p class="mb-0 fs-16">Total</p></th> <th>:</th>
                          <td align="right"><p class="mb-0 fw-semibold fs-16">720.00</p></td>
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