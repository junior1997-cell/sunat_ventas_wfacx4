<?php 

//Incluímos inicialmente la conexión a la base de datos

require "../config/Conexion.php";

Class Empresa
{
  public $id_usr_sesion; public $id_empresa_sesion;
  //Implementamos nuestro constructor
  public function __construct( $id_usr_sesion = 0, $id_empresa_sesion = 0 )
  {
    $this->id_usr_sesion =  isset($_SESSION['idusuario']) ? $_SESSION["idusuario"] : 0;
		$this->id_empresa_sesion = isset($_SESSION['idempresa']) ? $_SESSION["idempresa"] : 0;
  }

  //Implementamos un método para insertar registros

  public function insertar($razonsocial,$ncomercial,$domicilio,$ruc,$tel1,$tel2,$correo,  $web,$webconsul,$imagen, $ubigueo, $igv, $porDesc, $codubigueo, $ciudad, 
  $distrito, $interior,$codigopais, $banco1, $cuenta1 , $banco2, $cuenta2 , $banco3, $cuenta3 , $banco4, $cuenta4, $cuentacci1, $cuentacci2,$cuentacci3,$cuentacci4, 
  $tipoimpresion, $textolibre) {

    $sw=true;

    $sql="INSERT into  empresa (nombre_razon_social, nombre_comercial, domicilio_fiscal, numero_ruc, telefono1, telefono2, correo, web, webconsul, logo, ubigueo, codubigueo,
    ciudad,distrito,interior,codigopais,banco1,cuenta1,banco2, cuenta2,banco3,cuenta3,
    banco4,cuenta4,cuentacci1,cuentacci2,cuentacci3,cuentacci4,tipoimpresion,textolibre )
    values ('$razonsocial','$ncomercial','$domicilio','$ruc','$tel1','$tel2','$correo','$web','$webconsul','$imagen', '$ubigueo', '$codubigueo', 
    '$ciudad', '$distrito', '$interior', '$codigopais' , '$banco1' , '$cuenta1' , '$banco2' , '$cuenta2' , '$banco3' , '$cuenta3' , 
    '$banco4' , '$cuenta4' , '$cuentacci1', '$cuentacci2', '$cuentacci3', '$cuentacci4', '$tipoimpresion', '$textolibre')";

    $idempresanew=ejecutarConsulta_retornarID($sql);

    $sqlConf="INSERT into configuraciones ( idempresa, igv, porDesc ) values ('$idempresanew','$igv','$porDesc')";
    ejecutarConsulta($sqlConf) or $sw = false;

    return $sw;
  }

  //Implementamos un método para editar registros

  public function editar($idempresa,$razonsocial,$ncomercial,$domicilio,$ruc,$tel1,$tel2,$correo,$web,$webconsul,$imagen, $ubigueo, $igv, $porDesc, $codubigueo, $ciudad,
  $distrito, $interior, $codigopais ,$banco1, $cuenta1 , $banco2, $cuenta2 , $banco3, $cuenta3 , $banco4, $cuenta4, $cuentacci1, $cuentacci2, $cuentacci3, $cuentacci4, 
  $tipoimpresion, $textolibre) {
    $sw=true;

    $sql="UPDATE empresa set 
    nombre_razon_social='$razonsocial',
    nombre_comercial='$ncomercial',
    domicilio_fiscal='$domicilio',
    numero_ruc='$ruc',
    telefono1='$tel1',
    telefono2='$tel2',
    correo='$correo',
    web='$web',
    webconsul='$webconsul',
    logo='$imagen',
    ubigueo='$ubigueo',
    codubigueo='$codubigueo',
    ciudad='$ciudad',
    distrito='$distrito',
    interior='$interior',
    banco1='$banco1',
    cuenta1='$cuenta1',
    banco2='$banco2',
    cuenta2='$cuenta2',
    banco3='$banco3',
    cuenta3='$cuenta3',
    banco4='$banco4',
    cuenta4='$cuenta4',
    cuentacci1='$cuentacci1',
    cuentacci2='$cuentacci2',
    cuentacci3='$cuentacci3',
    cuentacci4='$cuentacci4',
    codigopais='$codigopais',
    tipoimpresion='$tipoimpresion',
    textolibre= '$textolibre'
    where idempresa='$idempresa'";

    $sqlConf="UPDATE configuraciones set igv='$igv', porDesc='$porDesc' where idempresa='$idempresa'";

    ejecutarConsulta($sql) or $sw = false;
    ejecutarConsulta($sqlConf) or $sw = false;

    return $sw;
  }

  public function mostrar($idempresa)  {
    $sql="SELECT  e.*, cf.idconfiguracion, cf.porDesc, cf.igv,  
    r.idruta, r.rutadata, r.rutafirma, r.rutaenvio, r.rutarpta, r.rutadatalt, r.rutabaja, r.rutaresumen, r.rutadescargas, r.rutaple, 
    r.unziprpta, r.rutaarticulos, r.rutalogo, r.rutausuarios, r.salidafacturas, r.salidaboletas, r.rutacertificado, r.salidanotapedidos
    from empresa e  
    left join  configuraciones cf on e.idempresa=cf.idempresa 
    left join rutas r on e.idempresa=r.idempresa
    where e.idempresa='$idempresa'";
    return ejecutarConsultaSimpleFila($sql);
  }

  public function listar() {
    $sql="SELECT * from empresa e inner join rutas r on e.idempresa=r.idempresa where e.idempresa='1'";
    return ejecutarConsulta($sql);      
  }

  public function listar_tabla_principal()  {
    $sql="SELECT * from empresa where estado = '1' and estado_delete = '1';";
    return ejecutarConsulta($sql);      
  }

}

 

?>