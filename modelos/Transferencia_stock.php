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
	//LISTAR LOS ALMACENES
	public function listar_almacen()	{
		$sql="SELECT idalmacen, nombre FROM almacen WHERE estado=1 AND estado_delete=1;";
		return ejecutarConsultaArray($sql);
	}

	public function listar_tranferencia()	{
		$sql="SELECT atr.idalmacen_transferencia, atr.fecha, al.nombre almacen, ar.nombre articulo, atr.cantidad,  atr.estado
		FROM almacen_transferencia as atr, almacen as al, articulo as ar
		WHERE atr.idalmacen = al.idalmacen
		AND atr.idarticulo = ar.idarticulo
		AND atr.estado=1 
		AND atr.estado_delete=1;";
		return ejecutarConsulta($sql);
	}


	public function listar_articulo_x_almacen($idalmacen){
		$sql="SELECT a.idarticulo, a.nombre, a.stock FROM articulo AS a, almacen AS al WHERE a.idalmacen = al.idalmacen AND a.idalmacen = '$idalmacen';";
		$articulo = ejecutarConsultaArray2($sql); if($articulo['status'] == false){return $articulo;}
		return $retorno = ['status' => true, 'message'=>'todo ok', 'data'=>['articulo'=>$articulo['data']]];
	}

	public function stock($idarticulo){
		$sql="SELECT a.idarticulo,a.stock FROM articulo AS a, almacen AS al WHERE a.idalmacen = al.idalmacen AND a.idarticulo = '$idarticulo';";
		$cantidad = ejecutarConsultaSimpleFila2($sql); if($cantidad['status'] == false){return $cantidad;}
		return $retorno = ['status' => true, 'message'=>'todo ok', 'data'=>['cantidad'=>$cantidad['data']]];
	}
	public function insertar($almacen1, $articulos1, $cantidad1, $almacen2, $articulos2){
		$sql="INSERT INTO almacen_transferencia (idalmacen, idarticulo, cantidad) values ('$almacen2', '$articulos2', '$cantidad1');";
		$insertar = ejecutarConsulta($sql, 'C');

        $sql_1 = "UPDATE articulo SET stock = stock - $cantidad1 WHERE idalmacen = '$almacen1' AND idarticulo = '$articulos1';";
		$actualizar1 = ejecutarConsulta($sql_1, 'U');

		$sql_2 = "UPDATE articulo SET stock = stock + $cantidad1 WHERE idalmacen = '$almacen2' AND idarticulo = '$articulos2';";
		$actualizar2 = ejecutarConsulta($sql_2, 'U');
		return $retorno = ['status'=>true, 'message'=>'todo okey'];
	}
	

}

?>
