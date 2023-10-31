<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Umedida
{
	//Implementamos nuestro constructor
	public function __construct()
	{
	}

	//Implementamos un método para insertar registros
	public function insertar($nombre, $abre, $equivalencia)
	{
		$sql = "insert into umedida (nombreum, abre, estado, equivalencia)
		values ('$nombre', '$abre', '1', '$equivalencia')";
		return ejecutarConsulta($sql);
	}


	//Implementamos un método para editar registros
	public function editar($idumedida, $nombre, $abre, $equivalencia)
	{
		$sql = "update umedida set nombreum='$nombre' , abre='$abre', equivalencia='$equivalencia' where idunidad='$idumedida'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar familias
	public function desactivar($idumedida)
	{
		$sql = "update umedida set estado='0' where idunidad='$idumedida'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
	public function activar($idumedida)
	{
		$sql = "update umedida set estado='1' where idunidad='$idumedida'";
		return ejecutarConsulta($sql);
	}


	//Implementamos un método para activar categorías
	public function eliminar($idumedida)
	{
		$sql = "delete from umedida where idunidad='$idumedida'";
		return ejecutarConsulta($sql);
	}

	//validar duplicado
	public function validarUnidadMedida($nombre, $abre)
	{
		$sql = "SELECT * from umedida where nombreum='$nombre'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idumedida)
	{
		$sql = "SELECT * from umedida where idunidad='$idumedida'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql = "SELECT * from umedida";
		return ejecutarConsulta($sql);
	}
	//Implementar un método para listar los registros y mostrar en el select
	public function select()
	{
		$sql = "SELECT * from umedida where estado='1'";
		return ejecutarConsulta($sql);
	}
}
