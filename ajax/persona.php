<?php

	if (strlen(session_id()) < 1) {	session_start(); }//Validamos si existe o no la sesión

	require_once "../modelos/Persona.php";
	require_once "../modelos/Ciudad.php";
	require_once "../modelos/Departamento.php";
	require_once "../modelos/Distrito.php";

	
	
	$persona 				= new Persona();	
	$_ciudad 				= new Ciudad();	
	$_departamento 	= new Departamento();	
	$_distrito 			= new Distrito();
	

	$idpersona 				= isset($_POST["idpersona"]) ? limpiarCadena($_POST["idpersona"]) : "";
	$tipo_persona 		= isset($_POST["tipo_persona"]) ? limpiarCadena($_POST["tipo_persona"]) : "";
	$nombres 					= isset($_POST["nombres"]) ? limpiarCadena($_POST["nombres"]) : "";
	$apellidos 				= isset($_POST["apellidos"]) ? limpiarCadena($_POST["apellidos"]) : "";
	$tipo_documento 	= isset($_POST["tipo_documento"]) ? limpiarCadena($_POST["tipo_documento"]) : "";
	$numero_documento = isset($_POST["numero_documento"]) ? limpiarCadena($_POST["numero_documento"]) : "";
	$nruc 						= isset($_POST["numero_documento3"]) ? limpiarCadena($_POST["numero_documento3"]) : ""; //Viene de nuevo cliente
	$razon_social 		= isset($_POST["razon_social"]) ? limpiarCadena($_POST["razon_social"]) : "";
	$nombre_comercial = isset($_POST["nombre_comercial"]) ? limpiarCadena($_POST["nombre_comercial"]) : "";
	$domicilio_fiscal = isset($_POST["domicilio_fiscal"]) ? limpiarCadena($_POST["domicilio_fiscal"]) : "";
	$departamento 		= isset($_POST["iddepartamento"]) ? limpiarCadena($_POST["iddepartamento"]) : "";
	$ciudad 					= isset($_POST["idciudad"]) ? limpiarCadena($_POST["idciudad"]) : "";
	$distrito 				= isset($_POST["iddistrito"]) ? limpiarCadena($_POST["iddistrito"]) : "";
	$telefono1 				= isset($_POST["telefono1"]) ? limpiarCadena($_POST["telefono1"]) : "";
	$telefono2 				= isset($_POST["telefono2"]) ? limpiarCadena($_POST["telefono2"]) : "";
	$email 						= isset($_POST["email"]) ? limpiarCadena($_POST["email"]) : "";

	$razon_social3 		= isset($_POST["razon_social3"]) ? limpiarCadena($_POST["razon_social3"]) : "";
	$nombre_comercial3 = isset($_POST["razon_social3"]) ? limpiarCadena($_POST["razon_social3"]) : "";
	$domicilio_fiscal3 = isset($_POST["domicilio_fiscal3"]) ? limpiarCadena($_POST["domicilio_fiscal3"]) : "";

switch ($_GET["op"]) {

	case 'guardaryeditar':
		$validando = $persona->validarProveedor($numero_documento, $tipo_persona);

      if (empty($idpersona)) {

        if(empty($validando["data"])){
					$rspta = $persona->insertar($tipo_persona, htmlspecialchars_decode($nombres), htmlspecialchars_decode($apellidos), $tipo_documento, $numero_documento, htmlspecialchars_decode($razon_social), htmlspecialchars_decode($nombre_comercial), htmlspecialchars_decode($domicilio_fiscal), $departamento, $ciudad, $distrito, $telefono1, $telefono2, htmlspecialchars_decode($email));
					echo json_encode(['status' => 'registrado', 'data' => $rspta]);
        } else{
          $info_repetida = '';
          foreach ($validando["data"] as $key => $value) {
            $info_repetida .= '
            <div class="row">
              <div class="col-md-12 text-left">
              <span class="font-size-15px text-danger"><b>Proveedor: </b>' . 
                ($value['nombres'] . ' ' . $value['apellidos'] !== '' ? 
                  $value['nombres'] . ' ' . $value['apellidos'] : 
                  $value['razon_social']) . 
              '</span>
              ' . ($value['estado'] == 1 ? '<span class="badge bg-success-transparent"><i class="ri-check-fill align-middle me-1"></i>Activo</span>' : '<span class="badge bg-danger-transparent"><i class="ri-close-fill align-middle me-1"></i>Inhabilitado').'</span><br>
              </div>
              
            </div>';
          }
          echo json_encode(['status' => 'duplicado', 'message' => 'duplicado', 'data' => '<ul>' . $info_repetida . '</ul>', 'id_tabla' => '']);
        }

			} else {
				$rspta = $persona->editar($idpersona, $tipo_persona, $nombres, $apellidos, $tipo_documento, $numero_documento, $razon_social, $nombre_comercial, $domicilio_fiscal, $departamento, $ciudad, $distrito, $telefono1, $telefono2, $email);
        echo json_encode(['status' => 'modificado', 'data' => $rspta]);
			}

    
	break;

	case 'guardaryeditarnproveedor':
		$rspta = $persona->insertarnproveedor($tipo_persona, $numero_documento, htmlspecialchars_decode($razon_social));
		echo $rspta ? "Registro correcto" : "No se pudo registrar";
	break;

	case 'guardaryeditarNcliente':
		if (empty($idpersona)) {
			$rspta = $persona->insertar($tipo_persona, htmlspecialchars_decode($nombres), htmlspecialchars_decode($apellidos), $tipo_documento, $nruc, htmlspecialchars_decode($razon_social), htmlspecialchars_decode($nombre_comercial), htmlspecialchars_decode($domicilio_fiscal), $departamento, $ciudad, $distrito, $telefono1, $telefono2, htmlspecialchars_decode($email));
			echo $rspta ? "Registro correcto" : "No se pudo registrar";
		} else {
			$rspta = $persona->editar($idpersona, $tipo_persona, htmlspecialchars_decode($nombres), htmlspecialchars_decode($apellidos), $tipo_documento, $nruc, htmlspecialchars_decode($razon_social), htmlspecialchars_decode($nombre_comercial), htmlspecialchars_decode($domicilio_fiscal), $departamento, $ciudad, $distrito, $telefono1, $telefono2, htmlspecialchars_decode($email));
			echo $rspta ? "Registro actualizado" : "No se pudo actualizar";
		}
	break;

	case 'guardaryeditarNclienteBoleta':
		if (empty($idpersona)) {
			$rspta = $persona->insertar($tipo_persona, htmlspecialchars_decode($nombre_comercial3), htmlspecialchars_decode($nombre_comercial3), $tipo_documento, $nruc, htmlspecialchars_decode($razon_social3), htmlspecialchars_decode($nombre_comercial3), htmlspecialchars_decode($domicilio_fiscal3), '14', '43', '96', $telefono1, $telefono2, htmlspecialchars_decode($email));
			echo $rspta ? "Registro correcto" : "No se pudo registrar";
		} else {
			$rspta = $persona->editar($idpersona, $tipo_persona, htmlspecialchars_decode($nombre_comercial3), htmlspecialchars_decode($nombre_comercial3), $tipo_documento, $nruc, htmlspecialchars_decode($razon_social3), htmlspecialchars_decode($nombre_comercial3), htmlspecialchars_decode($domicilio_fiscal3), $departamento, $ciudad, $distrito, $telefono1, $telefono2, htmlspecialchars_decode($email));
			echo $rspta ? "Registro actualizado" : "No se pudo actualizar";
		}
	break;

	case 'eliminar':
		$rspta = $persona->eliminar($idpersona);
		echo json_encode($rspta, true);
	break;

	case 'mostrar':
		$rspta = $persona->mostrar($idpersona);
		//Codificar el resultado utilizando json
		echo json_encode($rspta, true);
	break;

	//quitar id persona por validacion
	case 'mostrarClienteVarios':
		$rspta = $persona->mostrarIdVarios($idpersona);
		//Codificar el resultado utilizando json
		echo json_encode($rspta);
	break;

	case 'desactivar':
		$rspta = $persona->desactivar($idpersona);
		echo json_encode($rspta, true);
	break;

	case 'activar':
		$rspta = $persona->activar($idpersona);
		echo json_encode($rspta, true);
	break;

	case 'listarp':
		$rspta = $persona->listarp();
		$data = [];

    if($rspta['status']){
      foreach ($rspta['data'] as $key => $value){
        $data[]=[
          "0" => '<button class="btn btn-icon btn-sm btn-warning" onclick="mostrar(' . ($value['idpersona']) . ')"><i class="ri-edit-line"></i></button>'.
            (($value['estado']) ? ' <button class="btn btn-icon btn-sm btn-danger" onclick="desactivar(' . ($value['idpersona']) . ')"><i class="fa fa-close"></i></button>' :
            ' <button class="btn btn-icon btn-sm btn-success" onclick="activar(' . ($value['idpersona']) . ')"><i class="ri-check-double-line"></i></button>'),

          "1" =>  (($value['tipo_doc']) == 'RUC' ? ($value['razon_social']) : ($value['nombres']). ' '. ($value['apellidos']) ),
          "2" => '<b>' . ($value['tipo_doc']) .'</b>: '. ($value['numero_documento']),
          "3" => ($value['telefono1']),
          "4" => ($value['email']),
          "5" => ($value['estado']) ? '<span class="badge bg-success-transparent"><i class="ri-check-fill align-middle me-1"></i>Activo</span>' : '<span class="badge bg-danger-transparent"><i class="ri-close-fill align-middle me-1"></i>Inhabilitado</span>',
          "6" => '<button class="btn btn-icon btn-danger btn-wave waves-effect waves-light" onclick="eliminar('.($value['idpersona']).')"> <i class="ri-delete-bin-line"></i> </button>'
        ];
      }
      $results = [
        "sEcho" => 1, //Información para el datatables
        "iTotalRecords" => count($data), //enviamos el total registros al datatable
        "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
        "aaData" => $data
      ];
      echo json_encode($results, true);
    } else { echo $rspta['code_error'] .' - '. $rspta['message'] .' '. $rspta['data']; }
  break;

	case 'listarc':
		$rspta = $persona->listarc();
		//Vamos a declarar un array
		$data = array();

		while ($reg = $rspta->fetch_object()) {
			$data[] = array(
				"0" => '<button class="btn btn-icon btn-sm btn-warning" onclick="mostrar(' . $reg->idpersona . ')"><i class="fa fa-pencil"> </i></button>' .
					($reg->estado ? ' <button class="btn btn-icon btn-sm btn-danger" onclick="desactivar(' . $reg->idpersona . ')"><i class="fa fa-close" ></i></button> ' :
					' <button class="btn btn-icon btn-sm btn-success" onclick="activar(' . $reg->idpersona . ')"><i class="fa fa-check" ></i></button> '),

				"1" => ($reg->tipo_doc == 'RUC' ? $reg->razon_social : $reg->nombres . ' '. $reg->apellidos ),
				"2" => $reg->tipo_doc,
				"3" => $reg->numero_documento,
				"4" => $reg->telefono1,
				"5" => $reg->email,
				"6" => ($reg->estado) ? '<span class="badge bg-success-transparent"><i class="ri-check-fill align-middle me-1"></i>Activo</span>' : '<span class="badge bg-danger-transparent"><i class="ri-close-fill align-middle me-1"></i>Inhabilitado</span>'
			);
		}
		$results = array(
			"sEcho" => 1, //Información para el datatables
			"iTotalRecords" => count($data), //enviamos el total registros al datatable
			"iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
			"aaData" => $data
		);
		echo json_encode($results, true);
	break;

	

	// ══════════════════════════════════════ S E L E C T 2   ══════════════════════════════════════

	case 'selectCiudad':		
		$rspta = $_ciudad->selectC($_GET['id']);
		while ($reg = $rspta->fetch_object()) {
			echo '<option value=' . $reg->idciudad . '>' . $reg->nombre . '</option>';
		}
	break;

	case 'selectDistrito':		
		$id = $_GET['id'];
		$rspta = $_distrito->selectDI($id);
		while ($reg = $rspta->fetch_object()) {
			echo '<option value=' . $reg->iddistrito . '>' . $reg->nombre . '</option>';
		}
	break;

	case 'ValidarCliente':
		$ndocumento = $_GET['ndocumento'];
		$rspta = $persona->validarCliente($ndocumento);
		echo json_encode($rspta); // ? "Cliente ya existe": "Documento valido";
	break;

	case 'ValidarProveedor':
		$ndocumento = $_GET['ndocumento'];
		$rspta = $persona->validarProveedor($ndocumento, $_GET['tipo_persona']);
		echo json_encode($rspta, true);
	break;

	case 'selectCliente':
		$rspta = $persona->listarc();
		while ($reg = $rspta->fetch_object()) {
			echo '<option value=' . $reg->idpersona . '>' . $reg->numero_documento . '</option>';
		}
	break;

	// ══════════════════════════════════════ Reniec   ══════════════════════════════════════
	case 'buscarclienteRuc':		
		$rspta = $persona->buscarclienteRuc($_POST['key']);
		foreach ($rspta as $key => $row) {
			$id = $row['idpersona'];
			$num_doc = utf8_encode($row['numero_documento']);
			$razon_s = utf8_encode($row['razon_social']);
			$dom_fiscal = utf8_encode($row['domicilio_fiscal']);
			$html = '<div ><a class="suggest-element"  ndocumento="'.$num_doc.'"  ncomercial="' . $razon_s . '"  domicilio="' . $dom_fiscal . '" id="' . $id . '" email="' . $row['email'] . '">' . $razon_s . '</a></div>';
			echo $html;
		}
	break;

	case 'buscarclienteDomicilio':
		// $key = $_POST['key'];
		$rspta = $persona->buscarclientenombre($_POST['key']);
		
		foreach ($rspta as $key => $row) {
			$id = $row['idpersona'];
			$num_doc = utf8_encode($row['numero_documento']);
			$razon_s = utf8_encode($row['razon_social']);
			$dom_fiscal = utf8_encode($row['domicilio_fiscal']);
			echo '<div><a class="suggest-element"  ndocumento="' . $num_doc . '"  ncomercial="' . $razon_s . '"  domicilio="' . $dom_fiscal . '" id="' . $id . '" email="' . $row['email'] . '">' . $razon_s . '</a></div>';
		}
	break;

	case 'combocliente':
		$rpta = $persona->combocliente();
		while ($reg = $rpta->fetch_object()) {
			echo '<option value=' . $reg->numero_documento . '>' . $reg->numero_documento . ' | ' . $reg->nombre_comercial . '</option>';
		}
	break;

	case 'comboclientenoti':
		$rpta = $persona->combocliente();
		while ($reg = $rpta->fetch_object()) {
			echo '<option value=' . $reg->idpersona . '>' . $reg->numero_documento . ' | ' . $reg->nombre_comercial . '</option>';
		}
	break;
}
