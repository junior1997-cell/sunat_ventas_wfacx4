<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Umedida
{
	//Implementamos nuestro constructor
	public $id_usr_sesion; public $id_empresa_sesion;
	//Implementamos nuestro constructor
	public function __construct( )	{
		$this->id_usr_sesion =  isset($_SESSION['idusuario']) ? $_SESSION["idusuario"] : 0;
		$this->id_empresa_sesion = isset($_SESSION['idempresa']) ? $_SESSION["idempresa"] : 0;
	}

	//Implementamos un método para insertar registros
	public function insertar($nombre, $abre, $equivalencia)
	{
		$sql = "insert into umedida (nombreum, abre, estado, equivalencia, user_created)
		values ('$nombre', '$abre', '1', '$equivalencia', '$this->id_usr_sesion')";
		$insertar = ejecutarConsulta_retornarID2($sql, 'U'); if ($insertar['status'] == false) {  return $insertar; } 
		return $insertar;
	}


	//Implementamos un método para editar registros
	public function editar($idumedida, $nombre, $abre, $equivalencia)
	{
		$sql = "update umedida set nombreum='$nombre' , abre='$abre', equivalencia='$equivalencia' where idunidad='$idumedida'";
		return ejecutarConsulta2($sql, 'U');
	}

	//Implementamos un método para desactivar familias
	public function desactivar($idumedida)
	{
		$sql = "update umedida set estado='0' where idunidad='$idumedida'";
		return ejecutarConsulta2($sql);
	}

	//Implementamos un método para activar categorías
	public function activar($idumedida)
	{
		$sql = "update umedida set estado='1' where idunidad='$idumedida'";
		return ejecutarConsulta2($sql);
	}

	//Implementamos un método para activar categorías
	public function eliminar($idumedida)
	{
		$sql = "DELETE FROM umedida WHERE idunidad = '$idumedida'";
		return ejecutarConsulta2($sql);
	}

	//validar duplicado
	public function validarUnidadMedida($nombre)
	{
		$sql = "SELECT * from umedida where nombreum='$nombre'";
		return ejecutarConsultaArray2($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idumedida)
	{
		$sql = "SELECT * from umedida where idunidad='$idumedida'";
		return ejecutarConsultaSimpleFila2($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql = "SELECT * from umedida";
		return ejecutarConsulta2($sql);
	}

	//Implementar un método para listar los registros y mostrar en el select
	public function select()
	{
		$sql = "SELECT * from umedida where estado='1'";
		return ejecutarConsulta($sql);
	}
}
