<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Registroinv
{
	//Implementamos nuestro constructor
	public function __construct()
	{
	}

	//Implementamos un método para insertar registros
	public function insertar($ano, $codigo, $denominacion, $costoinicial, $saldoinicial, $valorinicial, $compras, $ventas, $saldofinal, $costo, $valorfinal)
	{
		$sql = "insert into reginventariosanos (ano, codigo, denominacion, costoinicial, saldoinicial, valorinicial, compras, ventas, saldofinal, costo, valorfinal) 
		values
		 ('$ano', '$codigo', '$denominacion', '$costoinicial', '$saldoinicial', '$valorinicial', '$compras', '$ventas',  '$saldofinal', '$costo', '$valorfinal')";
		return ejecutarConsulta_retornarID2($sql, 'C');
	}

	//Implementamos un método para editar registros
	public function editar($idregistro, $ano, $codigo, $denominacion, $costoinicial, $saldoinicial, $valorinicial, $compras, $ventas, $saldofinal, $costo, $valorfinal)
	{
		$sql = "UPDATE reginventariosanos SET 
		codigo='$codigo', denominacion='$denominacion', costoinicial='$costoinicial', saldoinicial='$saldoinicial', 
		valorinicial='$valorinicial', compras='$compras',ventas='$ventas', saldofinal='$saldofinal', costo='$costo', 
		valorfinal='$valorfinal', ano='$ano'  
		WHERE idregistro='$idregistro'";
		return ejecutarConsulta2($sql, 'U');
	}



	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idarticulo){
		$sql = "SELECT idregistro, codigo, denominacion, costoinicial, saldoinicial, 
		valorinicial, compras, ventas, saldofinal, costo, valorfinal, ano 
		FROM reginventariosanos WHERE idregistro='$idarticulo'";
		return ejecutarConsultaSimpleFila2($sql);
	}

	//Implementar un método para listar los registros
	public function listar(){
		$sql = "SELECT *  from reginventariosanos ORDER BY codigo, ano";
		return ejecutarConsulta2($sql);
	}


	public function eliminar($idregistro){
		$sql = "DELETE FROM reginventariosanos WHERE idregistro='$idregistro'";
		return ejecutarConsulta2($sql);
	}
}
