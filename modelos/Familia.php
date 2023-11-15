<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Familia
{
	//Implementamos nuestro constructor
	public $id_usr_sesion; public $id_empresa_sesion;
	//Implementamos nuestro constructor
	public function __construct( $id_usr_sesion = 0, $id_empresa_sesion = 0 )
	{
		$this->id_usr_sesion =  isset($_SESSION['idusuario']) ? $_SESSION["idusuario"] : 0;
		$this->id_empresa_sesion = isset($_SESSION['idempresa']) ? $_SESSION["idempresa"] : 0;
	}

	//Implementamos un método para insertar registros
	public function insertarCategoria($nombre)
	{
		$sql="insert into familia (descripcion)
		values ('$nombre')";
		return ejecutarConsulta($sql);
	}

	public function insertaralmacen($nombre, $direc, $idempresa)
	{
		$sql="insert into almacen (nombre, direccion, idempresa)
		values ('$nombre', '$direc', '$idempresa')";
		return ejecutarConsulta($sql);
	}

	public function insertaraunidad($nombre, $abre, $equivalencia)
	{
		$sql="insert into umedida (nombreum, abre, equivalencia)
		values ('$nombre', '$abre', '$equivalencia')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($idfamilia,$nombre)
	{
		$sql="update familia set descripcion='$nombre' where idfamilia='$idfamilia'";
		return ejecutarConsulta($sql);
	}
	

	//Implementamos un método para desactivar familias
	public function desactivar($idfamilia)
	{
		$sql="update familia SET estado='0' where idfamilia='$idfamilia'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
	public function activar($idfamilia)
	{
		$sql="UPDATE familia SET estado='1' where idfamilia='$idfamilia'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idfamilia)
	{
		$sql="SELECT * from familia where idfamilia='$idfamilia'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//validar duplicado
	public function validarCategoria($nombre)	{
		$sql="SELECT * from familia where descripcion='$nombre'";
		return ejecutarConsultaSimpleFila($sql);
	}


	//Implementar un método para listar los registros
	public function listar_tabla_familia()
	{
		$sql="SELECT * from familia";
		return ejecutarConsulta($sql);
	}
	//Implementar un método para listar los registros y mostrar en el select
	public function select()
	{
		$sql="SELECT * from familia where estado=1 and not idfamilia='0' order by idfamilia desc";
		return ejecutarConsulta($sql);
	}
	

	
	
}

?>
