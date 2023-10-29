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
    function ejecutarConsulta($sql) {
        global $conexion;
        $query = $conexion->query($sql);
        if (!$query) {
            die("Error en la consulta: " . $conexion->error);
        }
        return $query;
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
}
?>
