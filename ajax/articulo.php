<?php
if (strlen(session_id()) < 1) {	session_start(); }//Validamos si existe o no la sesión

require_once "../modelos/Articulo.php";
require_once "../modelos/Almacen.php";
require_once "../modelos/Familia.php";	
require_once "../modelos/Marca.php";	
		
$articulo = new Articulo($_SESSION['idusuario'], $_SESSION['idempresa']);
$almacen = new Almacen();
$familia = new Familia();
$marca = new Marca();

date_default_timezone_set('America/Lima');
$date_now = date("d_m_Y__h_i_s_A");
$imagen_error = "this.src='../assets/svg/404-v2.svg'";
$toltip = '<script> $(function () { $(\'[data-bs-toggle="tooltip"]\').tooltip(); }); </script>';

$idarticulo = isset($_POST["idarticulo"]) ? limpiarCadena($_POST["idarticulo"]) : "";
$idfamilia = isset($_POST["idfamilia"]) ? limpiarCadena($_POST["idfamilia"]) : "";
$codigo_proveedor = isset($_POST["codigo_proveedor"]) ? limpiarCadena($_POST["codigo_proveedor"]) : "";
$idalmacen = isset($_POST["idalmacen"]) ? limpiarCadena($_POST["idalmacen"]) : "";
$codigo = isset($_POST["codigo"]) ? limpiarCadena($_POST["codigo"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
$unidad_medida = isset($_POST["unidad_medida"]) ? limpiarCadena($_POST["unidad_medida"]) : "";
$costo_compra = isset($_POST["costo_compra"]) ? limpiarCadena($_POST["costo_compra"]) : "";
$saldo_iniu = isset($_POST["saldo_iniu"]) ? limpiarCadena($_POST["saldo_iniu"]) : "";
$valor_iniu = isset($_POST["valor_iniu"]) ? limpiarCadena($_POST["valor_iniu"]) : "";
$saldo_finu = isset($_POST["saldo_finu"]) ? limpiarCadena($_POST["saldo_finu"]) : "";
$valor_finu = isset($_POST["valor_finu"]) ? limpiarCadena($_POST["valor_finu"]) : "";
$stock = isset($_POST["stock"]) ? limpiarCadena($_POST["stock"]) : "";
$comprast = isset($_POST["comprast"]) ? limpiarCadena($_POST["comprast"]) : "";
$ventast = isset($_POST["ventast"]) ? limpiarCadena($_POST["ventast"]) : "";
$portador = isset($_POST["portador"]) ? limpiarCadena($_POST["portador"]) : "";
$merma = isset($_POST["merma"]) ? limpiarCadena($_POST["merma"]) : "";
$valor_venta = isset($_POST["valor_venta"]) ? limpiarCadena($_POST["valor_venta"]) : "";
$imagen = isset($_POST["imagen"]) ? limpiarCadena($_POST["imagen"]) : "";
$codigosunat = isset($_POST["codigosunat"]) ? limpiarCadena($_POST["codigosunat"]) : "";
$ccontable = isset($_POST["ccontable"]) ? limpiarCadena($_POST["ccontable"]) : "";
$precio2 = isset($_POST["precio2"]) ? limpiarCadena($_POST["precio2"]) : "";
$precio3 = isset($_POST["precio3"]) ? limpiarCadena($_POST["precio3"]) : "";

//Nuevos codigos
$cicbper = isset($_POST["cicbper"]) ? limpiarCadena($_POST["cicbper"]) : "";
$nticbperi = isset($_POST["nticbperi"]) ? limpiarCadena($_POST["nticbperi"]) : "";
$ctticbperi = isset($_POST["ctticbperi"]) ? limpiarCadena($_POST["ctticbperi"]) : "";
$mticbperu = isset($_POST["mticbperu"]) ? limpiarCadena($_POST["mticbperu"]) : "";

//Nuevos codigos
$codigott = isset($_POST["codigott"]) ? limpiarCadena($_POST["codigott"]) : "";
$desctt = isset($_POST["desctt"]) ? limpiarCadena($_POST["desctt"]) : "";
$codigointtt = isset($_POST["codigointtt"]) ? limpiarCadena($_POST["codigointtt"]) : "";
$nombrett = isset($_POST["nombrett"]) ? limpiarCadena($_POST["nombrett"]) : "";

//N-----------------------
$lote = isset($_POST["lote"]) ? limpiarCadena($_POST["lote"]) : "";
$idmarca = isset($_POST["idmarca"]) ? limpiarCadena($_POST["idmarca"]) : "";
$fechafabricacion = isset($_POST["fechafabricacion"]) ? limpiarCadena($_POST["fechafabricacion"]) : "";
$fechavencimiento = isset($_POST["fechavencimiento"]) ? limpiarCadena($_POST["fechavencimiento"]) : "";
$procedencia = isset($_POST["procedencia"]) ? limpiarCadena($_POST["procedencia"]) : "";
$fabricante = isset($_POST["fabricante"]) ? limpiarCadena($_POST["fabricante"]) : "";
$registrosanitario = isset($_POST["registrosanitario"]) ? limpiarCadena($_POST["registrosanitario"]) : "";
$fechaingalm = isset($_POST["fechaingalm"]) ? limpiarCadena($_POST["fechaingalm"]) : "";
$fechafinalma = isset($_POST["fechafinalma"]) ? limpiarCadena($_POST["fechafinalma"]) : "";
$proveedor = isset($_POST["proveedor"]) ? limpiarCadena($_POST["proveedor"]) : "";
$seriefaccompra = isset($_POST["seriefaccompra"]) ? limpiarCadena($_POST["seriefaccompra"]) : "";
$numerofaccompra = isset($_POST["numerofaccompra"]) ? limpiarCadena($_POST["numerofaccompra"]) : "";
$fechafacturacompra = isset($_POST["fechafacturacompra"]) ? limpiarCadena($_POST["fechafacturacompra"]) : "";
$limitestock = isset($_POST["limitestock"]) ? limpiarCadena($_POST["limitestock"]) : "";
$tipoitem = isset($_POST["tipoitem"]) ? limpiarCadena($_POST["tipoitem"]) : "";
$factorc = isset($_POST["factorc"]) ? limpiarCadena($_POST["factorc"]) : "";
$umedidacompra = isset($_POST["umedidacompra"]) ? limpiarCadena($_POST["umedidacompra"]) : "";
$descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";

$idalmacennarticulo = isset($_POST["idalmacennarticulo"]) ? limpiarCadena($_POST["idalmacennarticulo"]) : "";
$idfamilianarticulo = isset($_POST["idfamilianarticulo"]) ? limpiarCadena($_POST["idfamilianarticulo"]) : "";
$tipoitemnarticulo = isset($_POST["tipoitemnarticulo"]) ? limpiarCadena($_POST["tipoitemnarticulo"]) : "";
$nombrenarticulo = isset($_POST["nombrenarticulo"]) ? limpiarCadena($_POST["nombrenarticulo"]) : "";
$stocknarticulo = isset($_POST["stocknarticulo"]) ? limpiarCadena($_POST["stocknarticulo"]) : "";
$precioventanarticulo = isset($_POST["precioventanarticulo"]) ? limpiarCadena($_POST["precioventanarticulo"]) : "";
$codigonarticulonarticulo = isset($_POST["codigonarticulonarticulo"]) ? limpiarCadena($_POST["codigonarticulonarticulo"]) : "";
$descripcionnarticulo = isset($_POST["descripcionnarticulo"]) ? limpiarCadena($_POST["descripcionnarticulo"]) : "";
$umedidanp = isset($_POST["umedidanp"]) ? limpiarCadena($_POST["umedidanp"]) : "";

if (isset($_GET['action'])) {
	$action = $_GET['action'];
} else {
	$action = '';
}

if ($action == 'GenerarCodigo') {
	$i_cod= $_GET['i_cod'];
	$generatedCode = $articulo->GenerarCodigoCorrelativoAutomatico($i_cod);
	$results = array("codigo" => $generatedCode);
	header('Content-type: application/json');
	echo json_encode($results);
}

require_once "../modelos/Rutas.php";
$rutas = new Rutas();
$Rrutas = $rutas->mostrar2("1");
$Prutas = $Rrutas->fetch_object();
$rutaimagen = $Prutas->rutaarticulos; // ru

switch ($_GET["op"]) {

	case 'guardaryeditar':

		if (!file_exists($_FILES['imagen']['tmp_name']) || !is_uploaded_file($_FILES['imagen']['tmp_name'])) {
			$imagen = $_POST["imagenactual"];
		} else {
			$ext = explode(".", $_FILES["imagen"]["name"]);
			if ($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES['imagen']['type'] == "image/png") {
				$imagen = $date_now . '__' . random_int(0, 20) . round(microtime(true)) . random_int(21, 41) . '.' . end($ext);
				//move_uploaded_file($_FILES["imagen"]["tmp_name"], "../files/articulos/" . $imagen);
				move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaimagen . $imagen);
			}
		}

		if (empty($idarticulo)) {

			$rspta = $articulo->insertar(
				$idalmacen,	$codigo_proveedor,	$codigo,	html_entity_decode($nombre, ENT_QUOTES | ENT_HTML401, 'UTF-8'),	$idfamilia,	$unidad_medida,	
				$costo_compra, $saldo_iniu, $valor_iniu, $saldo_finu, $valor_finu, $stock, $comprast,	$ventast, $portador, $merma, $valor_venta, $imagen, 
				$codigosunat, $ccontable, $precio2, $precio3, $cicbper, $nticbperi,	$ctticbperi,	$mticbperu,	$codigott,	$desctt,	$codigointtt,	$nombrett,
				$lote, $idmarca, $fechafabricacion, $fechavencimiento, $procedencia, $fabricante, $registrosanitario, $fechaingalm,	$fechafinalma, $proveedor,
				$seriefaccompra, $numerofaccompra, $fechafacturacompra, $limitestock, $tipoitem, $umedidacompra, $factorc, $descripcion
			);

			echo $rspta ? "Artículo registrado" : "Error";
		} else {

			$rspta = $articulo->editar(
				$idarticulo, $idalmacen, $codigo_proveedor, $codigo, html_entity_decode($nombre, ENT_QUOTES | ENT_HTML401, 'UTF-8'), $idfamilia, $unidad_medida, $costo_compra,
				$saldo_iniu, $valor_iniu, $saldo_finu, $valor_finu, $stock, $comprast, $ventast, $portador, $merma,	$valor_venta, $imagen, $codigosunat, 
				$ccontable, $precio2, $precio3, $cicbper, $nticbperi,	$ctticbperi, $mticbperu, $codigott, $desctt, $codigointtt, $nombrett, $lote, $idmarca, 
				$fechafabricacion, $fechavencimiento, $procedencia, $fabricante, $registrosanitario, $fechaingalm, $fechafinalma, $proveedor,	$seriefaccompra,
				$numerofaccompra, $fechafacturacompra, $limitestock, $tipoitem, $umedidacompra, $factorc, $descripcion
			);
			echo $rspta ? "Artículo actualizado" : "Artículo no se pudo actualizar";
		}

	break;

	case 'editarstockarticulo':
		$idarticuloproduct = isset($_POST["idarticuloproduct"]) ? limpiarCadena($_POST["idarticuloproduct"]) : "";
		$stockproduct = isset($_POST["stockproduct"]) ? limpiarCadena($_POST["stockproduct"]) : "";
		$rspta = $articulo->editarStockArticulo($idarticuloproduct, $stockproduct);
		echo $rspta ? "Stock del artículo actualizado" : "Stock del artículo no se pudo actualizar";
	break;

	case 'guardarnuevoarticulo':

		if (empty($idarticulo)) {
			$rspta = $articulo->insertar(	$idalmacennarticulo, '', $codigonarticulonarticulo,	html_entity_decode($nombrenarticulo, ENT_QUOTES | ENT_HTML401, 'UTF-8'),
				$idfamilianarticulo, $umedidanp, '', $stocknarticulo, '', $stocknarticulo, '', $stocknarticulo, '',	'', '', '', $precioventanarticulo, '', 
				'', '', $precioventanarticulo, $precioventanarticulo,	'', '', '', '', '', '', '', '', '', '', '', '', '', '', '',	'', '', '', '', '', '', 
				'0', $tipoitemnarticulo, $umedidanp, '1', $descripcionnarticulo
			);
		}

		echo $rspta ? "Artículo registrado" : "Error";
	break;

	case 'desactivar':
		$rspta = $articulo->desactivar($idarticulo);
		echo $rspta ? "Artículo Desactivado" : "Artículo no se puede desactivar";
	break;

	case 'activar':
		$rspta = $articulo->activar($idarticulo);
		echo $rspta ? "Artículo activado" : "Artículo no se puede activar";
		break;
	break;

	case 'mostrar':
		$rspta = $articulo->mostrar($idarticulo);
		//Codificar el resultado utilizando json
		echo json_encode($rspta);
	break;

	case 'mostrarequivalencia':		
		// $idmm = $_GET['iduni'];
		// $rspta = $almacen->selectunidadid($idmm);
		//Codificar el resultado utilizando json
		// echo json_encode($rspta);
	break;

	case 'validarcodigo':
		$coddd = $_GET['cdd'];
		$rspta = $articulo->validarcodigo($coddd);
		//Codificar el resultado utilizando json
		echo json_encode($rspta);
	break;

	case 'articuloBusqueda':
		$codigo = $_GET['codigoa'];
		$rspta = $articulo->articuloBusqueda($codigo);
		//Codificar el resultado utilizando json
		echo json_encode($rspta);
	break;

	case 'filtros_table':
		$rspta = $articulo->filtros_table();
		//Codificar el resultado utilizando json
		$html_almacen = ""; $html_categoria = ""; $html_marca = "";
		foreach ($rspta['filtro_almacen'] as $key => $reg) {
			$html_almacen .=  '<option value="' . $reg['idalmacen'] . '" >' . $reg['nombre'] . ' (' .$reg['cant']. ')' . '</option>';
		}
		foreach ($rspta['filtro_categoria'] as $key => $reg) {
			$html_categoria .=  '<option value="' . $reg['idfamilia'] . '" >' . $reg['descripcion'] . ' (' .$reg['cant']. ')' . '</option>';
		}
		foreach ($rspta['filtro_marca'] as $key => $reg) {
			$html_marca .=  '<option value="' . $reg['idmarca'] . '" >' . $reg['descripcion'] . ' (' .$reg['cant']. ')' . '</option>';
		}

		$retorno = array(
			'filtro_almacen' 		=> '<option value="todos" >Todos </option>' . $html_almacen, 
			'filtro_categoria' 	=> '<option value="todos" >Todos </option>'. $html_categoria, 
			'filtro_marca' 			=> '<option value="todos" >Todos </option>' . $html_marca, 
		);  
		echo json_encode($retorno, true);
		
	break;
	
	//LISTAR PRODUCTOS
	case 'tbla_principal':
		
		$rspta = $articulo->listar( $_GET['idalmacen'], $_GET['idfamilia'], $_GET['idmarca']);
		$url = '../reportes/printbarcode.php?codigopr=';
		//Vamos a declarar un array
		$data = array();
		while ($reg = $rspta->fetch_object()) {
			$imagen_pr = empty($reg->imagen) ? 'simagen.png' : $reg->imagen ;
			$data[] = array(
				"0" => ($reg->estado) ? '<div class="btn-group mb-1">
						<div class="dropdown">
							<button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-cog"></i> </button>
								<div class="dropdown-menu" style="">
									<a class="dropdown-item" href="' . $url . $reg->codigo . '&st=' . $reg->st2 . '&pr=' . $reg->precio . '" target="_blank"><i class="fas fa-barcode"></i> Código de barra</a>
									<button class="dropdown-item" onclick="mostrar(' . $reg->idarticulo . ')" ><i class="fas fa-pencil-alt"></i> Editar</button>
									<button class="dropdown-item" onclick="desactivar(' . $reg->idarticulo . ')" ><i class="fas fa-skull-crossbones"></i> Desactivar</button>
								</div>
						</div>
					</div> ' :'
					<div class="btn-group mb-1">
						<div class="dropdown">
							<button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-cog"></i> </button>
							<div class="dropdown-menu" style="">
									<a class="dropdown-item" href="' . $url . $reg->codigo . '&st=' . $reg->st2 . '&pr=' . $reg->precio . '" target="_blank"><i class="fas fa-barcode"></i> Código de barra</a>
									<button class="dropdown-item" onclick="mostrar(' . $reg->idarticulo . ')" ><i class="fas fa-pencil-alt"></i> Editar</button>
									<button class="dropdown-item" onclick="activar(' . $reg->idarticulo . ')" ><i class="fas fa-skull-crossbones"></i> Activar</button>
							</div>
						</div>
					</div>'
				,
				"1" => '<div class="d-flex align-items-center"> 
					<div class="me-2"> <span class="avatar avatar-rounded-3 cursor-pointer"> <img src="'.$rutaimagen.$imagen_pr.'" height="40px" width="auto" onerror="'.$imagen_error.'" onclick="ver_img_zoom(\'' . $rutaimagen.$imagen_pr . '\', \''.encodeCadenaHtml($reg->nombre).'\');" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Ver imagen"> </span> </div> 
					<div> 
						<span class="d-block fs-14 fw-semibold text-primary">'.$reg->nombre.'</span> 
						<span class="d-block fs-12 text-muted">Marca: <b>'.$reg->marca.'</b> | Categoria: <b>'.$reg->familia.'</b></span> 
					</div> 
				</div>',
				"2" => $reg->nombreal,
				"3" => $reg->codigo,
				"4" => $reg->stock,
				"5" => $reg->precio,
				"6" => $reg->costo_compra,				
				"7" => ($reg->estado ? '<span class="label bg-green">A</span>' :	'<span class="label bg-red">I</span>').$toltip
			);
		}

		$results = array(
			"sEcho" => 1,
			//Información para el datatables
			"iTotalRecords" => count($data),
			//enviamos el total registros al datatable
			"iTotalDisplayRecords" => count($data),
			//enviamos el total registros a visualizar
			"aaData" => $data
		);
		echo json_encode($results);

		
	break;

	case 'listarservicios':

		$idempresa = "1";
		$rspta = $articulo->listarservicios($idempresa);
		//Vamos a declarar un array

		$data = array();

		while ($reg = $rspta->fetch_object()) {
			$imagen_pr = empty($reg->imagen) ? 'simagen.png' : $reg->imagen ;
			$data[] = array(
				"0" => ($reg->estado) ? '<div class="btn-group mb-1">
					<div class="dropdown">
						<button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-cog"></i></button>
						<div class="dropdown-menu" style="">
							<a class="dropdown-item" onclick="mostrar(' . $reg->idarticulo . ')" ><i class="fas fa-pencil-alt"></i> Editar </a>
							<a class="dropdown-item" onclick="desactivar(' . $reg->idarticulo . ')" ><i class="fas fa-skull-crossbones"></i> Desactivar</a>
						</div>
					</div>
		    </div>' :'<div class="btn-group mb-1">
					<div class="dropdown">
						<button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-cog"></i></button>
						<div class="dropdown-menu" style="">
							<a class="dropdown-item" onclick="mostrar(' . $reg->idarticulo . ')" ><i class="fas fa-pencil-alt"></i> Editar </a>
							<a class="dropdown-item" onclick="activar(' . $reg->idarticulo . ')" ><i class="fas fa-skull-crossbones"></i> Activar </a>
						</div>
					</div>
				</div>',
				"1" => '<div class="d-flex align-items-center"> 
					<div class="me-2"> <span class="avatar avatar-rounded-3 cursor-pointer"> <img src="'.$rutaimagen.$imagen_pr.'" height="40px" width="auto" onerror="'.$imagen_error.'" onclick="ver_img_zoom(\'' . $rutaimagen.$imagen_pr . '\', \''.encodeCadenaHtml($reg->nombre).'\');" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Ver imagen"> </span> </div> 
					<div> 
						<span class="d-block fs-14 fw-semibold text-primary">'.$reg->nombre.'</span> 
						<span class="d-block fs-12 text-muted">Marca: <b>'.$reg->marca.'</b> | Categoria: <b>'.$reg->familia.'</b></span> 
					</div> 
				</div>',
				"2" => $reg->nombreal,
				"3" => $reg->codigo,
				"4" => $reg->precio,
				"5" => ($reg->estado ? '<span class="label bg-green">A</span>' :	'<span class="label bg-red">I</span>').$toltip
			);
		}

		$results = array(
			"sEcho" => 1,
			//Información para el datatables
			"iTotalRecords" => count($data),
			//enviamos el total registros al datatable
			"iTotalDisplayRecords" => count($data),
			//enviamos el total registros a visualizar
			"aaData" => $data
		);
		echo json_encode($results);

	break;

	case 'inventarioValorizado':
		$rspta = $articulo->listar( 'todos', 'todos', 'todos' );
		//Vamos a declarar un array
		$data = array();
		while ($reg = $rspta->fetch_object()) {
			$data[] = array(
				"0" => ($reg->estado) ? '<button class="btn btn-warning" onclick="mostrar(' . $reg->idarticulo . ')"> <i class="fa fa-pencil"> </i> </button>' .
					' <button class="btn btn-danger" onclick="desactivar(' . $reg->idarticulo . ')">   <i class="fa fa-close"></i>   </button>' :
					'<button class="btn btn-warning" onclick="mostrar(' . $reg->idarticulo . ')">      <i class="fa fa-pencil"></i> </button>' .
					' <button class="btn btn-primary" onclick="activar(' . $reg->idarticulo . ')">     <i class="fa fa-check"></i>   </button>',
				"1" => $reg->codigo_proveedor,
				"2" => $reg->codigo,
				"3" => $reg->familia,
				"4" => $reg->nombre,
				"5" => $reg->stock,
				"6" => $reg->precio,
				"7" => "<img src='../files/articulos/" . $reg->imagen . "' height='60px' width='60px' >",
				"8" => ($reg->estado) ? '<span class="label bg-green">A</span>' :'<span class="label bg-red">I</span>'
			);
		}

		$results = array(
			"sEcho" => 1,	//Información para el datatables
			"iTotalRecords" => count($data),	//enviamos el total registros al datatable
			"iTotalDisplayRecords" => count($data),	//enviamos el total registros a visualizar
			"aaData" => $data
		);
		echo json_encode($results);
	break;

	case "selectMarca":		
		$rspta = $marca->select2_marca();
		while ($reg = $rspta->fetch_object()) { echo '<option value=' . $reg->idmarca . '>' . $reg->descripcion . '</option>';	}
	break;

	case "selectFamilia":
		
		$rspta = $familia->select();
		while ($reg = $rspta->fetch_object()) { echo '<option value=' . $reg->idfamilia . '>' . $reg->descripcion . '</option>';	}
	break;

	case "selectAlmacen":		
		
		$rspta = $almacen->select();
		while ($reg = $rspta->fetch_object()) {
			echo '<option value=' . $reg->idalmacen . '>' . $reg->nombre . '</option>';
		}
	break;

	case "selectUnidad":		
		$rspta = $almacen->selectunidad();
		while ($reg = $rspta->fetch_object()) {
			echo '<option value=' . $reg->idunidad . '>' . $reg->nombreum . ' | ' . $reg->abre . '</option>';
		}
	break;

	case 'buscararticulo':
		$key = $_POST['key'];
		$rspta = $articulo->buscararticulo($key);
		echo json_encode($rspta); // ? "Cliente ya existe": "Documento valido";
	break;

	case "comboarticulo":
		$anor = $_GET['anor'];
		$alm = $_GET['aml'];
		$rpta = $articulo->comboarticulo($anor, $alm);
		while ($reg = $rpta->fetch_object()) {
			echo '<option value=' . $reg->codigo . '>' . $reg->codigo . ' | ' . $reg->nombre . '     | Año registro: ' . $reg->anoregistro . '</option>';
		}
	break;

	case "comboarticulomg":
		$alm = $_GET['aml'];
		$anor = $_GET['anor'];
		$rpta = $articulo->comboarticulo($anor, $alm);
		while ($reg = $rpta->fetch_object()) {
			echo '<option value=' . $reg->idarticulo . '>' . $reg->codigo . ' | ' . $reg->nombre . '     | Año registro: ' . $reg->anoregistro . '</option>';
		}
	break;

	case "comboarticulokardex":
		//$anor=$_GET['anor'];
		$rpta = $articulo->comboarticuloKardex();
		while ($reg = $rpta->fetch_object()) {
			echo '<option value=' . $reg->codigo . '>' . $reg->codigo . ' | ' . $reg->nombre . '</option>';
		}
	break;

	case "insertarArticulosMasivo":
		// Recoger los datos del formulario (por ejemplo, desde $_POST)
		$codigo = $_POST['codigo'];
		$familia_descripcion = $_POST['familia_descripcion'];
		$nombre = $_POST['nombre'];
		$marca = $_POST['marca'];
		$descrip = $_POST['descrip'];
		$costo_compra = $_POST['costo_compra'];
		$precio_venta = $_POST['precio_venta'];
		$stock = $_POST['stock'];
		$saldo_iniu = $_POST['saldo_iniu'];
		$valor_iniu = $_POST['valor_iniu'];
		$tipoitem = $_POST['tipoitem'];
		$codigott = $_POST['codigott'];
		$desctt = $_POST['desctt'];
		$codigointtt = $_POST['codigointtt'];
		$nombrett = $_POST['nombrett'];
		$nombre_almacen = $_POST['nombre_almacen'];

		// Llama al método del modelo
		$rspta = $articulo->insertarArticulosMasivo(
			$codigo,
			$familia_descripcion,
			$nombre,
			$marca,
			$descrip,
			$costo_compra,
			$precio_venta,
			$stock,
			$saldo_iniu,
			$valor_iniu,
			$tipoitem,
			$codigott,
			$desctt,
			$codigointtt,
			$nombrett,
			$nombre_almacen
		);

		echo $rspta ? "Artículos insertados exitosamente" : "Error al insertar artículos";
		break;
}
