<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Persona
{
	//Implementamos nuestro constructor
	public function __construct()
	{
	}

	//Implementamos un método para insertar registros
	public function insertar($tipo_persona, $nombres, $apellidos, $tipo_documento, $numero_documento, $razon_social, $nombre_comercial, $domicilio_fiscal, $departamento, $ciudad, $distrito, $telefono1, $telefono2, $email)
	{
		$sql = "INSERT into persona (tipo_persona, nombres,apellidos, tipo_documento, numero_documento,razon_social,nombre_comercial, domicilio_fiscal,departamento,ciudad,distrito, telefono1,telefono2, email)
		values ('$tipo_persona','$nombres','$apellidos', '$tipo_documento', '$numero_documento','$razon_social','$nombre_comercial','$domicilio_fiscal','$departamento','$ciudad','$distrito', '$telefono1','$telefono2','$email')";
		return ejecutarConsulta($sql);
	}

	public function insertarnproveedor($tipo_persona, $numero_documento, $razon_social)	{
		$sql = "INSERT into persona (	tipo_persona,	tipo_documento,	numero_documento, razon_social)	values (
		'$tipo_persona', '6',	'$numero_documento', '$razon_social')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para insertar registros
	public function insertardeFactura($nombres, $tipo_documento, $numero_documento, $domicilio_fiscal)	{
		$sql = "INSERT into persona (tipo_persona, nombres, apellidos, tipo_documento, numero_documento,	razon_social,	nombre_comercial, 
		domicilio_fiscal,	departamento,	ciudad,	distrito,	telefono1, telefono2, email)
		values ('cliente','','','$tipo_documento', '$numero_documento','$nombres','$nombres','$domicilio_fiscal','','','', '-','-','')";
		return ejecutarConsulta($sql);
	}

	public function insertardeBoleta($nombres, $tipo_documento, $numero_documento, $domicilio_fiscal)	{
		$sql = "INSERT into persona (tipo_persona, nombres, apellidos, tipo_documento, numero_documento,	razon_social,
		nombre_comercial, domicilio_fiscal,	departamento,	ciudad,	distrito, telefono1, telefono2, email)
		values ('CLIENTE','$nombres','$nombres','$tipo_documento', '$numero_documento','$nombres','$nombres','$domicilio_fiscal','','','', '-','-','')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($idpersona, $tipo_persona, $nombres, $apellidos, $tipo_documento, $numero_documento, $razon_social, $nombre_comercial, $domicilio_fiscal, $departamento, $ciudad, $distrito, $telefono1, $telefono2, $email)
	{
		$sql = "update persona SET tipo_persona='$tipo_persona',nombres='$nombres',apellidos='$apellidos', tipo_documento='$tipo_documento', numero_documento='$numero_documento',razon_social='$razon_social',nombre_comercial='$nombre_comercial', domicilio_fiscal='$domicilio_fiscal', departamento='$departamento', ciudad='$ciudad', distrito='$distrito', telefono1='$telefono1', telefono2='$telefono2', email='$email' where idpersona='$idpersona'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para eliminar persnoa
	public function eliminar($idpersona)	{
		$sql = "DELETE from persona where idpersona='$idpersona'";
		return ejecutarConsulta($sql);
	}


	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idpersona) {
		$sql = "SELECT idpersona, nombres, apellidos, tipo_documento, numero_documento, razon_social, nombre_comercial, domicilio_fiscal, 
		departamento, ciudad, distrito , telefono1, telefono2, email 
		from persona where idpersona='$idpersona' and estado='1'";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function mostrarId()	{
		$sql = "SELECT idpersona 
		from persona 
		where tipo_persona='CLIENTE' and tipo_documento not in(6)	order by idpersona desc limit 0,1";
		return ejecutarConsulta($sql);
	}

	public function mostrarIdFactura() {
		$sql = "SELECT idpersona from persona 
		where tipo_persona='cliente' and tipo_documento not in(0, 1, 6, 7) order by idpersona desc  limit 0,1";
		return ejecutarConsulta($sql);
	}

	public function mostrarIdVarios()	{
		$sql = "SELECT * from persona where tipo_persona='CLIENTE' and idpersona='1'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listarp()	{
		$sql = "SELECT * from persona p 
		inner join catalogo6 ct6 on p.tipo_documento=ct6.codigo 
		where p.tipo_persona='PROVEEDOR' order by idpersona desc";
		return ejecutarConsulta($sql);
	}

	public function listarc()	{
		$sql = "SELECT p.idpersona, p.tipo_persona, p.nombres, p.apellidos, p.tipo_documento, p.numero_documento, p.razon_social, 
		p.nombre_comercial, p.domicilio_fiscal, p.departamento, p.ciudad, p.distrito, p.telefono1, p.telefono2, p.email, p.estado, 
		ct6.codigo, ct6.descripcion, ct6.abrev
		from persona p inner join catalogo6 ct6 on p.tipo_documento = ct6.codigo 
		where p.tipo_persona='CLIENTE';";
		return ejecutarConsulta($sql);
	}

	public function listarclienteFact($nombre) {
		$sql = "SELECT * from 
		persona p inner join catalogo6 ct6 on p.tipo_documento=ct6.codigo 
		where 
		p.tipo_persona='CLIENTE' and p.estado='1' and p.razon_social like '$nombre%'";
		return ejecutarConsulta($sql);
	}

	//Busca por numero de cliente el nombre
	public function listarcnumdocu($numdocumento)	{
		$sql = "SELECT * from persona where tipo_persona='CLIENTE' and num_documento='$numdocumento' and estado='2'";
		return ejecutarConsulta($sql);
	}

	//Busca por nombre de cliente el numero de documento
	public function listarcnom($nombre)	{
		$sql = "SELECT * from persona where tipo_persona='Cliente' and nombre='$nombre' and estado='1'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar registros
	public function desactivar($idpersona) {
		$sql = "UPDATE persona SET estado='0' where idpersona='$idpersona'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar registros
	public function activar($idpersona)	{
		$sql = "UPDATE persona SET estado='1' where idpersona='$idpersona'";
		return ejecutarConsulta($sql);
	}

	public function listarCliVenta()	{
		$sql = "SELECT idpersona, razon_social, numero_documento, domicilio_fiscal, tipo_documento 
    from persona where tipo_persona='cliente' and tipo_documento='6'";
		return ejecutarConsulta($sql);
	}

	//Busca por numero de cliente el documento 
	public function validarCliente($numdocumento)	{
		$sql = "SELECT numero_documento from persona where tipo_persona='CLIENTE' and numero_documento='$numdocumento' and estado='1'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Busca por numero de cliente el documento 
	public function validarProveedor($numdocumento)	{
		$sql = "SELECT numero_documento from persona where tipo_persona='PROVEEDOR' and numero_documento='$numdocumento'";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function buscarClientexDocFactura($doc) {
		$sql = "SELECT * from persona p inner join catalogo6 ct6 on p.tipo_documento=ct6.codigo 
		where p.tipo_persona='CLIENTE' and p.numero_documento='$doc' and tipo_documento='6' and  p.estado='1' ";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function buscarClientexDocFacturaNuevos()	{
		$sql = "SELECT * from persona p inner join catalogo6 ct6 on p.tipo_documento=ct6.codigo where 
		p.tipo_persona='CLIENTE' and  tipo_documento='6' and p.estado='1' order by idpersona desc limit 1";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function buscarClientexDocBoleta($doc)	{
		$sql = "SELECT * from persona p 
		inner join catalogo6 ct6 on p.tipo_documento=ct6.codigo 
		where p.tipo_persona='CLIENTE' and p.numero_documento='$doc' and  tipo_documento in('1','4','7','A')  and  p.estado='1'";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function buscarclienteRuc($key)	{	
		$sql_1 = "SELECT * from persona 
		where tipo_persona='cliente' and tipo_documento='6' and numero_documento like '%$key%' and not idpersona='1' limit 8";
		return ejecutarConsultaArray($sql_1);
	}

	public function buscarclientenombre($key)	{		
		$result = "SELECT * from persona 
		where tipo_persona='cliente' and tipo_documento='6' and razon_social like '%$key%' and not idpersona='1' limit 8";
		return ejecutarConsultaArray($result);			
	}

	public function combocliente() {
		$sql = "SELECT * from persona where tipo_persona='cliente'";
		return ejecutarConsulta($sql);
	}
}
