<?php
  ob_start();

  if (strlen(session_id()) < 1) {

    session_start(); //Validamos si existe o no la sesión
  }

  if (!isset($_SESSION["nombre"])) {

    $retorno = ['status'=>'login', 'message'=>'Tu sesion a terminado pe, inicia nuevamente', 'data' => [] ];
    echo json_encode($retorno);  //Validamos el acceso solo a los usuarios logueados al sistema.

  } else {     
    
    require_once "../modelos/Ajax_general.php";
    
    $ajax_general   = new Ajax_general($_SESSION['idusuario']);

    $scheme_host  =  ($_SERVER['HTTP_HOST'] == 'localhost' ? 'http://localhost/front_jdl/admin/' :  $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].'/');
    $imagen_error = "this.src='../dist/svg/404-v2.svg'";
    $toltip       = '<script> $(function () { $(\'[data-toggle="tooltip"]\').tooltip(); }); </script>';

    switch ($_GET["op"]) {       

      // buscar datos de RENIEC
      case 'reniec':
        $dni = $_POST["dni"];
        $rspta = $ajax_general->datos_reniec($dni);
        echo json_encode($rspta);
      break;

      case 'consultaDniReniec':
        $dni = $_POST["dni"];
        $rspta = $ajax_general->consultaDniReniec($dni);
        echo json_encode($datosDniCli);
      break;
      
      // buscar datos de SUNAT
      case 'sunat':
        $ruc = $_POST["ruc"];
        $rspta = $ajax_general->datos_sunat($ruc);
        echo json_encode($rspta, true);
      break;

      case 'consultaRucSunat':
        $ruc = $_POST["ruc"];
        $rspta = $ajax_general->consultaRucSunat($ruc);
        echo json_encode($datosRucCli);
      break;           
      
      /* ══════════════════════════════════════ U B I G E O  ══════════════════════════════════════ */
      case 'select2_departamento_name': 
        $rspta = $ajax_general->select2_departamento_name();  $cont = 1; $data = "";        
        foreach ($rspta as $key => $value) {
          echo '<option  value="' . $value['nombre'] . '" >' . $value['nombre']  . '</option>';
        }  
        // echo json_encode($data, true);        
         
      break;    

      case 'select2_provincia_all_name': 
        $rspta = $ajax_general->select2_provincia_all_name();  $cont = 1; $data = "";        
        foreach ($rspta as $key => $value) {
          $data .= '<option  value="' . $value['provincia'] . '" >' . $value['provincia']  . '</option>';
        }  
        echo json_encode($data, true);        
      break;    

      case 'select2_provincia_id_name': 
        $rspta = $ajax_general->select2_provincia_id_name($_POST['id']);  $cont = 1; $data = "";        
        foreach ($rspta as $key => $value) {
          $data .= '<option  value="' . $value['provincia'] . '" >' . $value['provincia']  . '</option>';
        }  
        echo json_encode($data, true);        
      break;   

      case 'select2_distrito_all_name': 
        $rspta = $ajax_general->select2_distrito_all_name();  $cont = 1; $data = "";        
        foreach ($rspta as $key => $value) {
          $data .= '<option  value="' . $value['distrito'] . '" >' . $value['distrito']  . '</option>';
        }  
        echo json_encode($data, true);        
      break;    

      case 'select2_distrito_id_name': 
        $rspta = $ajax_general->select2_distrito_id_name($_POST['id']);  $cont = 1; $data = "";        
        foreach ($rspta as $key => $value) {
          $data .= '<option  value="' . $value['distrito'] . '" >' . $value['distrito']  . '</option>';
        }  
        echo json_encode($data, true);        
      break;   

      default: 
        $rspta = ['status'=>'error_code', 'message'=>'Te has confundido en escribir en el <b>swich.</b>', 'data'=>[]]; echo json_encode($rspta, true); 
      break;
    }
      
  }

  ob_end_flush();
?>
