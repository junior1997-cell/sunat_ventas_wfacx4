<?php

//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Marca
{

  //Implementamos nuestro constructor
  public $id_usr_sesion; public $id_empresa_sesion;
  //Implementamos nuestro constructor
  public function __construct( $id_usr_sesion = 0, $id_empresa_sesion = 0 )
  {
    $this->id_usr_sesion =  isset($_SESSION['idusuario']) ? $_SESSION["idusuario"] : 0;
		$this->id_empresa_sesion = isset($_SESSION['idempresa']) ? $_SESSION["idempresa"] : 0;
  }

	//Implementamos un método para editar registros
	public function crear_marca($nombre)	{
		$sql="INSERT INTO marca( descripcion, user_created) VALUES ('$nombre', '$this->id_usr_sesion')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar_marca($idmarca,$nombre)	{
		$sql="update marca set descripcion='$nombre' where idmarca='$idmarca'";
		return ejecutarConsulta($sql);
	}	

	//Implementamos un método para desactivar marcas
	public function desactivar_marca($idmarca)	{
		$sql="update marca SET estado='0' where idmarca='$idmarca'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
	public function activar_marca($idmarca)	{
		$sql="UPDATE marca SET estado='1' where idmarca='$idmarca'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar_editar($idmarca)	{
		$sql="SELECT * from marca where idmarca='$idmarca'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//validar duplicado
	public function validar_marca($nombre)	{
		$sql="SELECT * from marca where descripcion='$nombre'";
		return ejecutarConsultaSimpleFila($sql);
	}


	//Implementar un método para listar los registros
	public function listar_tabla_principal()	{
		$sql="SELECT * from marca";
		return ejecutarConsulta($sql);
	}

  //Implementamos un método para insertar registros
	public function select2_marca()	{
		$sql="SELECT * FROM marca where estado ='1' and idmarca > 1 order by descripcion asc;";
		return ejecutarConsulta($sql);
	}

	public function insertarMarcaMasivo( $nombre, $estado )  {
    $sql = "CALL insertar_marca_en_masa( '$nombre', '$estado', $this->id_usr_sesion )";
    return ejecutarConsulta($sql);
  }
}
