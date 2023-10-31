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

  //CAPTURAR PERSONA  DE RENIEC 
  public function datos_reniec($dni) { 

    $url = "https://dniruc.apisperu.com/api/v1/dni/".$dni."?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Imp1bmlvcmNlcmNhZG9AdXBldS5lZHUucGUifQ.bzpY1fZ7YvpHU5T83b9PoDxHPaoDYxPuuqMqvCwYqsM";
    //  Iniciamos curl
    $curl = curl_init();
    // Desactivamos verificación SSL
    curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, 0 );
    // Devuelve respuesta aunque sea falsa
    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
    // Especificamo los MIME-Type que son aceptables para la respuesta.
    curl_setopt( $curl, CURLOPT_HTTPHEADER, [ 'Accept: application/json' ] );
    // Establecemos la URL
    curl_setopt( $curl, CURLOPT_URL, $url );
    // Ejecutmos curl
    $json = curl_exec( $curl );
    // Cerramos curl
    curl_close( $curl );

    return json_decode( $json, true );
  }

  public function consultaDniReniec($ruc)	{ 
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
      CURLOPT_HTTPHEADER => array(
        'Referer: https://apis.net.pe/consulta-dni-api',
        'Authorization: Bearer' . $token
      ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    // Datos listos para usar
    return json_decode($response);
  }

  //CAPTURAR PERSONA  DE SUNAT
  public function datos_sunat($ruc)	{ 
    $url = "https://dniruc.apisperu.com/api/v1/ruc/".$ruc."?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Imp1bmlvcmNlcmNhZG9AdXBldS5lZHUucGUifQ.bzpY1fZ7YvpHU5T83b9PoDxHPaoDYxPuuqMqvCwYqsM";
    //  Iniciamos curl
    $curl = curl_init();
    // Desactivamos verificación SSL
    curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, 0 );
    // Devuelve respuesta aunque sea falsa
    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
    // Especificamo los MIME-Type que son aceptables para la respuesta.
    curl_setopt( $curl, CURLOPT_HTTPHEADER, [ 'Accept: application/json' ] );
    // Establecemos la URL
    curl_setopt( $curl, CURLOPT_URL, $url );
    // Ejecutmos curl
    $json = curl_exec( $curl );
    // Cerramos curl
    curl_close( $curl );

    return json_decode( $json, true );

  }  

  public function consultaRucSunat($ruc)	{ 
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

  /* ══════════════════════════════════════ U B I G E O S  ══════════════════════════════════════ */

  public function select2_departamento_name(){
    $sql = "SELECT * FROM departamento;";
    return ejecutarConsultaArray($sql);
  }

  public function select2_provincia_all_name(){
    $sql = "SELECT * FROM ubprovincia;";
    return ejecutarConsultaArray($sql);
  }

  public function select2_provincia_id_name($id){
    $sql = "SELECT * FROM ubprovincia WHERE idDepa = '$id';";
    return ejecutarConsultaArray($sql);
  }

  public function select2_distrito_all_name(){
    $sql = "SELECT * FROM ubdistrito;";
    return ejecutarConsultaArray($sql);
  }

  public function select2_distrito_id_name($id){
    $sql = "SELECT * FROM ubdistrito WHERE idProv = '$id';";
    return ejecutarConsultaArray($sql);
  }

}
