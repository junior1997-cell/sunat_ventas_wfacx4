<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Ubigeo
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	// ══════════════════════════════════════ S E L E C T 2    D E P A R T A M E N T O ══════════════════════════════════════  
	
	public function select2_departamento()	{
		$sql="SELECT * from departamento WHERE estado = '1'";
		return ejecutarConsulta($sql);		
	}

	public function select2_departamento_id($id)	{
		$sql="SELECT * from departamento where estado = '1' and iddepartamento='$id'";
		return ejecutarConsultaSimpleFila($sql);		
	}

	// ══════════════════════════════════════ S E L E C T 2    P R O V I N C I A ══════════════════════════════════════
	public function select2_provincia()	{
		$sql="SELECT p.*, d.nombre as departamento 
		FROM provincia as p
		INNER JOIN departamento as d on d.iddepartamento = p.iddepartamento
		WHERE p.estado = '1';";
		return ejecutarConsulta($sql);		
	}

	public function select2_provincia_departamento($id)	{
		$sql="SELECT p.*, d.nombre as departamento 
		FROM provincia as p
		INNER JOIN departamento as d on d.iddepartamento = p.iddepartamento
		WHERE p.estado = '1' AND d.iddepartamento = '$id';";
		return ejecutarConsulta($sql);		
	}

	public function select2_provincia_id($id)	{
		$sql="SELECT p.*, d.nombre as departamento 
		FROM provincia as p
		INNER JOIN departamento as d on d.iddepartamento = p.iddepartamento
		WHERE p.estado = '1' AND d.idprovincia = '$id';";
		return ejecutarConsultaSimpleFila($sql);		
	}

	// ══════════════════════════════════════ S E L E C T 2    D I S T R I T O ══════════════════════════════════════

	public function select2_distrito()	{
		$sql="SELECT di.*, de.nombre as departamento, p.nombre as provincia
		FROM distrito as di 
		INNER JOIN departamento as de ON de.iddepartamento = di.iddepartamento
		INNER JOIN provincia as p ON p.idprovincia = di.idprovincia
		WHERE di.estado = '1' ;";
		return ejecutarConsulta($sql);		
	}

	public function select2_distrito_departamento($id)	{
		$sql="SELECT di.*, de.nombre as departamento, p.nombre as provincia
		FROM distrito as di 
		INNER JOIN departamento as de ON de.iddepartamento = di.iddepartamento
		INNER JOIN provincia as p ON p.idprovincia = di.idprovincia
		WHERE di.estado = '1' AND di.iddepartamento = '$id';";
		return ejecutarConsulta($sql);		
	}

	public function select2_distrito_provincia($id)	{
		$sql="SELECT di.*, de.nombre as departamento, p.nombre as provincia
		FROM distrito as di 
		INNER JOIN departamento as de ON de.iddepartamento = di.iddepartamento
		INNER JOIN provincia as p ON p.idprovincia = di.idprovincia
		WHERE di.estado = '1' AND di.idprovincia = '$id';";
		return ejecutarConsulta($sql);		
	}

	public function select2_distrito_id($id)	{
		$sql="SELECT di.*, de.nombre as departamento, p.nombre as provincia
		FROM distrito as di 
		INNER JOIN departamento as de ON de.iddepartamento = di.iddepartamento
		INNER JOIN provincia as p ON p.idprovincia = di.idprovincia
		WHERE di.estado = '1' AND di.iddistrito = '$id';";
		return ejecutarConsultaSimpleFila($sql);		
	}
}

?>