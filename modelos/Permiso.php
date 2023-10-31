<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Permiso
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}
	
	public function listar()
	{
		$sql="SELECT * from permiso where not idpermiso in('6','7') ";
		return ejecutarConsulta($sql);		
	}

	public function listarEmpresa()
	{
		$sql="SELECT * from empresa";
		return ejecutarConsulta($sql);		
	}
	
}

?>