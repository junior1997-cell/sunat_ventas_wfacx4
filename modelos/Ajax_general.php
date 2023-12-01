<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Ajax_general
{
	//Implementamos nuestro variable global
  public $id_usr_sesion;

  //Implementamos nuestro constructor
  public function __construct($id_usr_sesion = 0)
  {
    $this->id_usr_sesion = $id_usr_sesion;
  } 

  // ══════════════════════════════════════ RENIEC JDL ══════════════════════════════════════
  public function datos_reniec_jdl($dni) { 

    $url = "https://dniruc.apisperu.com/api/v1/dni/".$dni."?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Imp1bmlvcmNlcmNhZG9AdXBldS5lZHUucGUifQ.bzpY1fZ7YvpHU5T83b9PoDxHPaoDYxPuuqMqvCwYqsM";
    
    $curl = curl_init();                              //  Iniciamos curl    
    curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, 0 );  // Desactivamos verificación SSL    
    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );  // Devuelve respuesta aunque sea falsa    
    curl_setopt( $curl, CURLOPT_HTTPHEADER, [ 'Accept: application/json' ] );// Especificamo los MIME-Type que son aceptables para la respuesta.    
    curl_setopt( $curl, CURLOPT_URL, $url );          // Establecemos la URL    
    $json = curl_exec( $curl );                       // Ejecutmos curl    
    curl_close( $curl );                              // Cerramos curl
    return json_decode( $json, true );
  }

  // ══════════════════════════════════════ RENIEC WFACX ══════════════════════════════════════
  public function datos_reniec_otro($ruc)	{ 
    $token = 'apis-token-1.aTSI1U7KEuT-6bbbCguH-4Y8TI6KS73N';
    $nndnii = $_GET['nrodni'];

    // Iniciar llamada a API
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.apis.net.pe/v1/dni?numero=' . $nndnii,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 2,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array( 'Referer: https://apis.net.pe/consulta-dni-api', 'Authorization: Bearer' . $token ),
    ));
    $response = curl_exec($curl); // Ejecutmos curl 
    curl_close($curl);            // Cerramos curl
    
    return json_decode($response);
  }

  // ══════════════════════════════════════ SUNAT JDL ══════════════════════════════════════
  public function datos_sunat_jdl($ruc)	{ 
    $url = "https://dniruc.apisperu.com/api/v1/ruc/".$ruc."?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Imp1bmlvcmNlcmNhZG9AdXBldS5lZHUucGUifQ.bzpY1fZ7YvpHU5T83b9PoDxHPaoDYxPuuqMqvCwYqsM";    
    $curl = curl_init();                              //  Iniciamos curl    
    curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, 0 );  // Desactivamos verificación SSL    
    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );  // Devuelve respuesta aunque sea falsa    
    curl_setopt( $curl, CURLOPT_HTTPHEADER, [ 'Accept: application/json' ] );// Especificamo los MIME-Type que son aceptables para la respuesta.    
    curl_setopt( $curl, CURLOPT_URL, $url );          // Establecemos la URL    
    $json = curl_exec( $curl );                       // Ejecutmos curl    
    curl_close( $curl );                              // Cerramos curl
    return json_decode( $json, true );
  }  

  // ══════════════════════════════════════ SUNAT WFACX ══════════════════════════════════════
  public function datos_sunat_otro($ruc)	{ 
    $token = 'apis-token-1.aTSI1U7KEuT-6bbbCguH-4Y8TI6KS73N';  

    // Iniciar llamada a API
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.apis.net.pe/v1/ruc?numero=' . $ruc,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array(
        'Referer: https://apis.net.pe/api-ruc',
        'Authorization: Bearer' . $token
      ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    // Datos listos para usar
    return json_decode($response);
  }



}
