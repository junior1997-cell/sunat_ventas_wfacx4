<?php

if (strlen(session_id()) < 1) {	session_start(); }//Validamos si existe o no la sesión

require_once "../modelos/Umedida.php";

$umedida=new Umedida();

$idunidadme=isset($_POST["idunidadm"])? limpiarCadena($_POST["idunidadm"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$abre=isset($_POST["abre"])? limpiarCadena($_POST["abre"]):"";
$equivalencia=isset($_POST["equivalencia"])? limpiarCadena($_POST["equivalencia"]):"";

switch ($_GET["op"]){

	case 'guardaryeditar':
		$validarUnidadMedida = $umedida->validarUnidadMedida($nombre);
		if(empty($validarUnidadMedida["data"])){

			if (empty($idunidadme)){
				$rspta=$umedida->insertar($nombre, $abre, $equivalencia);
        echo json_encode(['status' => 'registrado', 'data' => $rspta]);
			}	else {
				$rspta=$umedida->editar($idunidadme, $nombre, $abre, $equivalencia);
        echo json_encode(['status' => 'modificado', 'data' => $rspta]);
			}

		}else{
      $info_repetida = '';

      foreach ($validarUnidadMedida["data"] as $key => $value) {
        $info_repetida .= '
				<div class="row">
          <div class="col-md-12 text-left">
            <span class="font-size-15px text-danger"><b>Unidad de Medida: </b>' . $value['nombreum'] .  '</span>
            ' . ($value['estado'] == 1 ? '<span class="badge bg-success-transparent"><i class="ri-check-fill align-middle me-1"></i>Activo</span>' : '<span class="badge bg-danger-transparent"><i class="ri-close-fill align-middle me-1"></i>Inhabilitado').'</span><br>
          </div>
          <div class="col-md-12 text-left">
            <b>Papelera: </b>' . ($value['estado'] == 0 ? '<i class="fas fa-check text-success"></i> SI' : '<i class="fas fa-times text-danger"></i> NO') . ' <b>|</b>
            <b>Eliminado: </b>' . ($value['estado_delete'] == 0 ? '<i class="fas fa-check text-success"></i> SI' : '<i class="fas fa-times text-danger"></i> NO') . '<br>
            
          </div>
        </div>';
      }
      echo json_encode(['status' => 'duplicado', 'message' => 'duplicado', 'data' => '<ul>' . $info_repetida . '</ul>', 'id_tabla' => '']);
    }
	break;

	case 'desactivar':
		$rspta=$umedida->desactivar($idunidadme);
 		echo json_encode($rspta,  true);
 		
	break;

	case 'activar':
		$rspta=$umedida->activar($idunidadme);
    echo json_encode($rspta,  true); 		
	break;

	case 'eliminar':
		$rspta=$umedida->eliminar($idunidadme);
    echo json_encode($rspta,  true); 		
	break;

	case 'mostrar':
		$rspta=$umedida->mostrar($idunidadme);
 		echo json_encode($rspta, true); 		
	break;

	case 'listar':
		$rspta=$umedida->listar();
 		$data= [];

		if ($rspta['status']) {
      foreach ($rspta['data'] as $key => $value){
        $data[]=[
          "0"=>($value['estado'])?'<button class="btn btn-warning btn-sm" onclick="mostrar('.($value['idunidad']).')"><i class="fa fa-pencil"></i></button>'.
            ' <button class="btn btn-danger btn-sm" onclick="desactivar('.($value['idunidad']).')"><i class="fa fa-close"></i></button>':
            '<button class="btn btn-warning btn-sm" onclick="mostrar('.($value['idunidad']).')"><i class="fa fa-pencil"></i></button>'.
            '<button class="btn btn-primary btn-sm" onclick="activar('.($value['idunidad']).')"><i class="fa fa-check"></i></button>',
          "1"=>($value['nombreum']),
          "2"=>($value['abre']),
          "3"=>($value['equivalencia']),
          "4"=>($value['estado'])?'<span class="badge bg-success-transparent"><i class="ri-check-fill align-middle me-1"></i>Activo</span>':
          '<span class="badge bg-danger-transparent"><i class="ri-close-fill align-middle me-1"></i>Inhabilitado</span>',
          "5"=>'<button class="btn btn-icon btn-danger btn-wave waves-effect waves-light" onclick="eliminar('.($value['idunidad']).')"> <i class="ri-archive-line"></i> </button>'
        ];
      }
      $results = [
        "sEcho"=>1,
        "iTotalRecords"=>count($data),
        "iTotalDisplayRecords"=>1,
        "aaData"=>$data
      ];
      echo json_encode($results, true);
    } else {
      echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data'];
      }

	break;
}
?>
