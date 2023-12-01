<?php
  ob_start();

  if (strlen(session_id()) < 1) {  session_start();  }//Validamos si existe o no la sesión

  if (!isset($_SESSION["nombre"])) {

    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.

  } else {     
    
    require_once "../modelos/Ajax_general.php";
    require_once "../modelos/Ubigeo.php";
    
    $ajax_general = new Ajax_general($_SESSION['idusuario']);
    $_ubigeo 			= new Ubigeo();

    $scheme_host  =  ($_SERVER['HTTP_HOST'] == 'localhost' ? 'http://localhost/front_jdl/admin/' :  $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].'/');
    $imagen_error = "this.src='../dist/svg/404-v2.svg'";
    $toltip       = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

    switch ($_GET["op"]) {       

      /* ══════════════════════════════════════ U B I G E O  ══════════════════════════════════════ */

      // RENIEC JDL
      case 'reniec_jdl':
        $dni = $_POST["dni"];
        $rspta = $ajax_general->datos_reniec_jdl($dni);
        echo json_encode($rspta);
      break;
      // RENIEC WFACX
      case 'reniec_otro':
        $dni = $_POST["dni"];
        $rspta = $ajax_general->datos_reniec_otro($dni);
        echo json_encode($rspta);
      break;      
      // SUNAT JDL
      case 'sunat_jdl':
        $ruc = $_POST["ruc"];
        $rspta = $ajax_general->datos_sunat_jdl($ruc);
        echo json_encode($rspta, true);
      break;
      // SUNAT WFACX
      case 'sunat_otro':
        $ruc = $_POST["ruc"];
        $rspta = $ajax_general->datos_sunat_otro($ruc);
        echo json_encode($rspta);
      break;           
      
      /* ══════════════════════════════════════ U B I G E O  ══════════════════════════════════════ */
      
      // ══════════════════════════════════════ U B I G E O - S E L E C T 2    D E P A R T A M E N T O ══════════════════════════════════════
      case 'select2_departamento':
        $rspta = $_ubigeo->select2_departamento();
        while ($reg = $rspta->fetch_object()) {
          echo '<option value="' . $reg->nombre . '" iddepartamento = "' . $reg->iddepartamento . '" macroregion_minsa = "' . $reg->macroregion_minsa . '" iso_3166_2 = "' . $reg->iso_3166_2 . '" >' . $reg->nombre . '</option>';
        }
      break;

      case 'select2_departamento_id':
        $rspta = $_ubigeo->select2_departamento_id($_GET['id']);
        echo json_encode($rspta, true);
      break;

      // ══════════════════════════════════════ U B I G E O - S E L E C T 2    P R O V I N C I A ══════════════════════════════════════
      case 'select2_provincia':
        $rspta = $_ubigeo->select2_provincia();
        while ($reg = $rspta->fetch_object()) {
          echo '<option value="' . $reg->nombre . '" idprovincia = "' . $reg->idprovincia . '" iddepartamento = "' . $reg->iddepartamento . '" >' . $reg->nombre . '</option>';
        }
      break;

      case 'select2_provincia_departamento':
        $rspta = $_ubigeo->select2_provincia_departamento($_GET['id']);
        while ($reg = $rspta->fetch_object()) {
          echo '<option value=' . $reg->nombre . ' idprovincia = "' . $reg->idprovincia . '" iddepartamento = "' . $reg->iddepartamento . '" >' . $reg->nombre . '</option>';
        }
      break;

      case 'select2_provincia_id':
        $rspta = $_ubigeo->select2_provincia_id($_GET['id']);
        echo json_encode($rspta, true);
      break;

      // ══════════════════════════════════════ U B I G E O - S E L E C T 2    D I S T R I T O ══════════════════════════════════════
      case 'select2_distrito':        
        $rspta = $_ubigeo->select2_distrito();
        while ($reg = $rspta->fetch_object()) {
          echo '<option value="' . $reg->nombre . '" iddistrito="' . $reg->iddistrito . '" iddepartamento= "' . $reg->iddepartamento . '" ubigeo_inei="' . $reg->ubigeo_inei . '" latitud="' . $reg->latitud . '" longitud="' . $reg->longitud . '" Frontera="' . $reg->Frontera . '" >' . $reg->nombre . '</option>';
        }
      break; 

      case 'select2_distrito_departamento':
        $rspta = $_ubigeo->select2_distrito_departamento($_GET['id']);
        while ($reg = $rspta->fetch_object()) {
          echo '<option value="' . $reg->nombre . '" iddistrito="' . $reg->iddistrito . '" iddepartamento= "' . $reg->iddepartamento . '" ubigeo_inei="' . $reg->ubigeo_inei . '" latitud="' . $reg->latitud . '" longitud="' . $reg->longitud . '" Frontera="' . $reg->Frontera . '" >' . $reg->nombre . '</option>';
        }
      break;

      case 'select2_distrito_provincia':
        $rspta = $_ubigeo->select2_distrito_provincia($_GET['id']);
        while ($reg = $rspta->fetch_object()) {
          echo '<option value="' . $reg->nombre . '" iddistrito="' . $reg->iddistrito . '" iddepartamento= "' . $reg->iddepartamento . '" ubigeo_inei="' . $reg->ubigeo_inei . '" latitud="' . $reg->latitud . '" longitud="' . $reg->longitud . '" Frontera="' . $reg->Frontera . '" >' . $reg->nombre . '</option>';
        }
      break;

      case 'select2_distrito_id':			
        $rspta = $_ubigeo->select2_distrito_id($_GET['id']);
        echo json_encode($rspta, true);
      break;

      // ══════════════════════════════════════ DEFAULT ══════════════════════════════════════
      default: 
        $rspta = ['status'=>'error_code', 'message'=>'Te has confundido en escribir en el <b>swich.</b>', 'data'=>[]]; echo json_encode($rspta, true); 
      break;
    }
      
  }

  ob_end_flush();
?>
