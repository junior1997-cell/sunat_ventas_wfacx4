<?php
require_once "global.php";
// Conexión a la base de datos
$conexion = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
// Verificar si hay errores en la conexión
if ($conexion->connect_errno) {
  die("Falló la conexión a la base de datos: " . $conexion->connect_error);
}
// Establecer el conjunto de caracteres de la conexión
$conexion->set_charset(DB_ENCODE);
// Funciones para ejecutar consultas y limpiar cadenas
if (!function_exists('ejecutarConsulta')) {
  
  function ejecutarConsulta($sql)  {
    global $conexion;
    $query = $conexion->query($sql);
    if (!$query) {
      die("Error en la consulta: " . $conexion->error);
    }
    return $query;
  }

  function ejecutarConsultaArray($sql) {
    global $conexion;
    $query = $conexion->query($sql);
    for ($data = array(); $row = $query->fetch_assoc(); $data[] = $row);
    return $data;
  }


  function ejecutarConsultaArray2($sql) {
    global $conexion;  //$data= Array();	$i = 0;

    $query = $conexion->query($sql);

    if ($conexion->error) {
      try {
        throw new Exception("MySQL error <b> $conexion->error </b> Query:<br> $query", $conexion->errno);
      } catch (Exception $e) {
        //echo "Error No: " . $e->getCode() . " - " . $e->getMessage() . "<br >"; echo nl2br($e->getTraceAsString());
        return array( 
          'status' => false, 
          'code_error' => $e->getCode(), 
          'message' => $e->getMessage(), 
          'data' => '<br><b>Rutas de errores:</b> <br>'.nl2br($e->getTraceAsString()),
        );          
      }
    } else {
      for ($data = []; ($row = $query->fetch_assoc()); $data[] = $row);
      return  array( 
        'status' => true, 
        'code_error' => $conexion->errno, 
        'message' => 'Salió todo ok, en ejecutarConsultaArray2()', 
        'data' => $data, 
        'id_tabla' => '',
        'affected_rows' => $conexion->affected_rows,
        'sqlstate' => $conexion->sqlstate,
        'field_count' => $conexion->field_count,
        'warning_count' => $conexion->warning_count, 
      );
    }
  }



  function ejecutarConsultaSimpleFila2($sql) {
    global $conexion;
    $query = $conexion->query($sql);
    if ($conexion->error) {
      try {
        throw new Exception("MySQL error <b> $conexion->error </b> Query:<br> $query", $conexion->errno);
      } catch (Exception $e) {
        //echo "Error No: " . $e->getCode() . " - " . $e->getMessage() . "<br >"; echo nl2br($e->getTraceAsString());
        $data_errores = array( 
          'status' => false, 
          'code_error' => $e->getCode(), 
          'message' => $e->getMessage(), 
          'data' => '<br><b>Rutas de errores:</b> <br>'.nl2br($e->getTraceAsString()),
        );
        return $data_errores;
      }

    } else {
      $row = $query->fetch_assoc();
      return array( 
        'status' => true, 
        'code_error' => $conexion->errno, 
        'message' => 'Salió todo ok, en ejecutarConsultaSimpleFila()', 
        'data' => $row, 
        'id_tabla' => '',
        'affected_rows' => $conexion->affected_rows,
        'sqlstate' => $conexion->sqlstate,
        'field_count' => $conexion->field_count,
        'warning_count' => $conexion->warning_count, 
      );
    }
  }



  function ejecutarConsultaSimpleFila($sql) {
    $result = ejecutarConsulta($sql);
    $row = $result->fetch_assoc();
    $result->free();
    return $row;
  }

  function ejecutarConsulta_retornarID($sql) {
    global $conexion;
    if (!$conexion->query($sql)) {
      die("Error en la consulta: " . $conexion->error);
    }
    return $conexion->insert_id;
  }
  function limpiarCadena($str) {
    global $conexion;
    $str = mysqli_real_escape_string($conexion, trim($str));
    return htmlspecialchars($str);
  }

  function encodeCadenaHtml($str) {
    // htmlspecialchars($str);
    global $conexion;
    $encod = "UTF-8";
    $str = mysqli_real_escape_string($conexion, trim($str));
    return htmlspecialchars($str, ENT_QUOTES);
  }

  function decodeCadenaHtml($str) {
    $encod = "UTF-8";
    return htmlspecialchars_decode($str, ENT_QUOTES);
  }
}
