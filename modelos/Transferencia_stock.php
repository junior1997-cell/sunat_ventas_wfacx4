<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Transferencia_stock
{
	//Implementamos nuestro constructor
	public $id_usr_sesion; public $id_empresa_sesion;
	//Implementamos nuestro constructor
	public function __construct( )	{
		$this->id_usr_sesion =  isset($_SESSION['idusuario']) ? $_SESSION["idusuario"] : 0;
		$this->id_empresa_sesion = isset($_SESSION['idempresa']) ? $_SESSION["idempresa"] : 0;
	}

  // CRUD - T

	public function listar()	{
		$sql="SELECT atr.idalmacen_transferencia, atr.fecha, al.nombre almacen, ar.nombre articulo, atr.cantidad,  atr.estado
		FROM almacen_transferencia as atr, almacen as al, articulo as ar
		WHERE atr.idalmacen = al.idalmacen
		AND atr.idarticulo = ar.idarticulo
		AND atr.estado=1 
		AND atr.estado_delete=1
		ORDER BY atr.fecha DESC;";
		return ejecutarConsulta2($sql);
	}

	public function select1(){
		$sql="SELECT * from almacen a inner join empresa e on a.idempresa=e.idempresa  where  e.idempresa='$this->id_empresa_sesion' order by idalmacen asc";
		return ejecutarConsulta2($sql);
	}

	public function select2($idalmacen1){
		// Consulta para obtener almacenes excluyendo el seleccionado en el primer select
		$sql = "SELECT * FROM almacen a inner join empresa e on a.idempresa=e.idempresa  where  e.idempresa='$this->id_empresa_sesion' AND idalmacen <> '$idalmacen1' ORDER BY idalmacen ASC";
		return ejecutarConsulta2($sql);
	}

	public function selectArt1($idalmacen1){
		// Consulta para obtener almacenes excluyendo el seleccionado en el primer select
		$sql = "SELECT ar.idarticulo, ar.nombre articulo FROM almacen as al, articulo as ar WHERE al.idalmacen = ar.idalmacen AND ar.idalmacen = '$idalmacen1'";
		return ejecutarConsulta2($sql);
	}

	public function selectArt2($idalmacen2){
		// Consulta para obtener almacenes excluyendo el seleccionado en el primer select
		$sql = "SELECT ar.idarticulo, ar.nombre articulo FROM almacen as al, articulo as ar WHERE al.idalmacen = ar.idalmacen AND ar.idalmacen = '$idalmacen2'";
		return ejecutarConsulta2($sql);
	}

	public function verStock($idarticulo1){
		// Consulta para obtener almacenes excluyendo el seleccionado en el primer select
		$sql = "SELECT ar.idarticulo, ar.stock stock FROM almacen as al, articulo as ar WHERE al.idalmacen = ar.idalmacen AND ar.idarticulo = '$idarticulo1'";
		return ejecutarConsultaSimpleFila2($sql);
	}

	public function insertar($idalmacen1, $idalmacen2, $idarticulos1, $idarticulos2, $cantidad){
		$sql="INSERT INTO almacen_transferencia (idalmacen, idarticulo, cantidad) values ('$idalmacen2', '$idarticulos2', '$cantidad');";
		$insertar = ejecutarConsulta_retornarID2($sql, 'C');

    $sql_1 = "UPDATE articulo SET stock = stock - $cantidad WHERE idalmacen = '$idalmacen1' AND idarticulo = '$idarticulos1';";
		$actualizar1 = ejecutarConsulta2($sql_1, 'U');

		$sql_2 = "UPDATE articulo SET stock = stock + $cantidad WHERE idalmacen = '$idalmacen2' AND idarticulo = '$idarticulos2';";
		$actualizar2 = ejecutarConsulta2($sql_2, 'U');

		return $retorno = ['status'=>true, 'message'=>'todo okey'];

	}


	
	

}

?>
