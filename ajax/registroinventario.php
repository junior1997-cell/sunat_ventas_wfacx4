<?php
require_once "../modelos/Registroinventario.php";

$registroinv = new Registroinv();

$idregistro = isset($_POST["idregistro"]) ? limpiarCadena($_POST["idregistro"]) : "";
$ano = isset($_POST["ano"]) ? limpiarCadena($_POST["ano"]) : "";
$codigo = isset($_POST["codigo"]) ? limpiarCadena($_POST["codigo"]) : "";
$denominacion = isset($_POST["denominacion"]) ? limpiarCadena($_POST["denominacion"]) : "";
$costoinicial = isset($_POST["costoinicial"]) ? limpiarCadena($_POST["costoinicial"]) : "";
$saldoinicial = isset($_POST["saldoinicial"]) ? limpiarCadena($_POST["saldoinicial"]) : "";
$valorinicial = isset($_POST["valorinicial"]) ? limpiarCadena($_POST["valorinicial"]) : "";
$compras = isset($_POST["compras"]) ? limpiarCadena($_POST["compras"]) : "";
$ventas = isset($_POST["ventas"]) ? limpiarCadena($_POST["ventas"]) : "";
$saldofinal = isset($_POST["saldofinal"]) ? limpiarCadena($_POST["saldofinal"]) : "";
$costo = isset($_POST["costo"]) ? limpiarCadena($_POST["costo"]) : "";
$valorfinal = isset($_POST["valorfinal"]) ? limpiarCadena($_POST["valorfinal"]) : "";



switch ($_GET["op"]) {
	case 'guardar':
		if (empty($idregistro)) {
			$rspta = $registroinv->insertar($ano, $codigo, $denominacion, $costoinicial, $saldoinicial, $valorinicial, $compras, $ventas, $saldofinal, $costo, $valorfinal);
			echo json_encode($rspta, true);
		} else {
			$rspta = $registroinv->editar($idregistro, $ano, $codigo, $denominacion, $costoinicial, $saldoinicial, $valorinicial, $compras, $ventas, $saldofinal, $costo, $valorfinal);
			echo json_encode($rspta, true);
		}
	break;

	case 'mostrar':
		$rspta = $registroinv->mostrar($idregistro);
		echo json_encode($rspta, true);
	break;

	case 'eliminar':
		$rspta = $registroinv->eliminar($idregistro);
		echo json_encode($rspta, true);
	break;

	case 'listar':
		$rspta = $registroinv->listar();
		$data = [];
		$cod = "";
		if($rspta['data']){
			foreach($rspta['data'] as $key => $value){
				$data[]=[
					"0" => '<div class="botonessed" style="display: block ruby;"><button class="btn btn-warning btn-sm" onclick="mostrar(' . ($value['idregistro']) . ')" data-toggle="tooltip" title="Editar" >
					<i class="fa fa-pencil"></i></button>
					<button class="btn btn-danger btn-sm" onclick="eliminar(' . ($value['idregistro']) . ')" data-toggle="tooltip" title="Eliminar">
					<i class="fa fa-trash"></i></button></div>',
					"1" => ($value['ano']),
					"2" => ($value['codigo']),
					"3" => ($value['denominacion']),
					"4" => ($value['costoinicial']),
					"5" => number_format($value['saldoinicial'], 2),
					"6" => number_format(($value['valorinicial']), 2),
					"7" => number_format($value['compras'], 2),
					"8" => number_format($value['ventas'], 2),
					"9" => number_format($value['saldofinal'], 2),
					"10" => ($value['costo']),
					"11" => number_format($value['valorfinal'], 2)

				];
			}
		}
		$results = array(
			"sEcho" => 1, //Información para el datatables
			"iTotalRecords" => count($data), //enviamos el total registros al datatable
			"iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
			"aaData" => $data
		);
		echo json_encode($results, true);

	break;
}
