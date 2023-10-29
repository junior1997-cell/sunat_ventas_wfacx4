<?php
// Incluimos el archivo de conexión.
require_once "../config/Conexion.php";

class Conex
{
  //Implementamos nuestro constructor
  public function __construct()
  {
  }

  public function listadodb()
  {
    global $conexion;

    $resultado = $conexion->query("SHOW DATABASES");
    $dbs = array();

    while ($fila = $resultado->fetch_array()) {
      $dbs[] = $fila[0];
    }

    return $dbs;
  }


  public function verificar($login, $clave, $empresa)
  {
    $sql = "select u.idusuario, u.nombre, u.tipo_documento, u.num_documento, u.telefono, u.email, u.cargo, u.imagen, u.login, e.nombre_razon_social, e.idempresa, co.igv  from usuario u inner join usuario_empresa ue on u.idusuario=ue.idusuario inner join empresa e on ue.idempresa=e.idempresa inner join configuraciones co on e.idempresa=co.idempresa where u.login='$login' and u.clave='$clave' and  e.idempresa='$empresa' and u.condicion='1'"; // Dejé tu consulta SQL original sin cambios
    return ejecutarConsulta($sql); // Esta función viene de Conexion.php
  }

  public function onoffTempo($st)
  {
    $sql = "update temporizador set estado='$st' where id='1' ";
    return ejecutarConsulta($sql);
  }

  public function consultatemporizador()
  {
    $sql = "select id as idtempo, tiempo, estado from temporizador where id='1' ";
    return ejecutarConsulta($sql);
  }
}