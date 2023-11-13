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

  //Implementamos un método para insertar registros
	public function insertarMarca($nombre)	{
		$sql="insert into familia (descripcion)	values ('$nombre')";
		return ejecutarConsulta($sql);
	}
  
  //Implementamos un método para insertar registros
	public function editarMarca($nombre)	{
		$sql="insert into familia (descripcion)	values ('$nombre')";
		return ejecutarConsulta($sql);
	}

  //Implementamos un método para insertar registros
	public function listarAll()	{
		$sql="SELECT * FROM marca where estado ='1';";
		return ejecutarConsulta($sql);
	}

  //Implementamos un método para insertar registros
	public function select2_marca()	{
		$sql="SELECT * FROM marca where estado ='1';";
		return ejecutarConsulta($sql);
	}
}
