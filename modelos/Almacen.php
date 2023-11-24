<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Almacen
{
	//Implementamos nuestro constructor
	public $id_usr_sesion; public $id_empresa_sesion;
	//Implementamos nuestro constructor
	public function __construct( )	{
		$this->id_usr_sesion =  isset($_SESSION['idusuario']) ? $_SESSION["idusuario"] : 0;
		$this->id_empresa_sesion = isset($_SESSION['idempresa']) ? $_SESSION["idempresa"] : 0;
	}

	public function insertaralmacen($nombre, $direc, $idempresa) {
		$sql="INSERT into almacen (nombre, direccion, idempresa, user_created)	values ('$nombre', '$direc', '$this->id_empresa_sesion', '$this->id_usr_sesion')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($idalmacen,$nombre, $direccion) {
		$sql="update almacen set nombre='$nombre', direccion='$direccion' where idalmacen='$idalmacen'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar almacens
	public function desactivar($idalmacen)	{
		$sql="update almacen SET estado='0' where idalmacen='$idalmacen'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
	public function activar($idalmacen)	{
		$sql="update almacen SET estado='1' where idalmacen='$idalmacen'";
		return ejecutarConsulta($sql);
	}

	//validar duplicado
	public function validarAlmacen($nombre)	{
		$sql="SELECT * from almacen where nombre='$nombre'";
		return ejecutarConsultaSimpleFila($sql);
	}
	
	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idalmacen)	{
		$sql="SELECT * from almacen where idalmacen='$idalmacen'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()	{
		$sql="SELECT * from almacen order by idalmacen";
		return ejecutarConsulta($sql);
	}
	
	//Implementar un método para listar los registros y mostrar en el select
	// public function select()
	// {
	// 	$sql="SELECT * from almacen where estado=1 and not idalmacen='1'  order by idalmacen desc ";
	// 	return ejecutarConsulta($sql);
	// }

	//Implementar un método para listar los registros y mostrar en el select
	public function select(){
		$sql="SELECT * from almacen a inner join empresa e on a.idempresa=e.idempresa  where  e.idempresa='$this->id_empresa_sesion' order by idalmacen desc";
		return ejecutarConsulta($sql);
	}

	public function selectunidad() {
		$sql="SELECT * from umedida order by case when idunidad = 58 then 0 else 1 end,	idunidad desc;";
		return ejecutarConsulta($sql);
	}

	public function almacenlista() {
		$sql="SELECT * from almacen where not idalmacen='1' order by idalmacen";
		return ejecutarConsulta($sql);
	}

}

?>
