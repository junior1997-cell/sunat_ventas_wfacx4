var tabla_empresa;
var modoDemo = false;


//Función que se ejecuta al inicio

function init() {
	
	//mostrar("1");
	listar();

	// ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════   
  $.post("../ajax/ajax_general.php?op=select2_distrito", function (r) { $("#distrito").html(r); });

	// ══════════════════════════════════════ I N I T I A L I Z E   S E L E C T 2 ══════════════════════════════════════  
	$("#distrito").select2({ dropdownParent: $('#formularioregistros'),  theme: "bootstrap4", placeholder: "Seleccione", allowClear: true,  });

	mostrarform(false);
}

function limpiar() {
	$("#idempresa").val("");
	$("#razonsocial").val("");
	$("#ncomercial").val("");
	$("#domicilio").val("");
	$("#ruc").val("");
	$("#tel1").val("");
	$("#tel2").val("");
	$("#correo").val("");
	$("#web").val("");
	$("#webconsul").val("");
	$("#imagenmuestra").attr("src", "../files/logo/img_defecto.png");
	$("#imagenactual").val("");
	$("#ubigueo").val("0000");
	$("#codubigueo").val("");
	$("#ciudad").val("");
	$("#distrito").val("").trigger("change");
	$("#interior").val("");
	$("#codigopais").val("PE");
	$("#igv").val("");
	$("#porDesc").val("");
	$("#textolibre").val("Empresa peruana");

	$("#razonsocial").focus();
	$("#preview").empty();

	// Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//Función cancelarform
function cancelarform() {	mostrarform(false); }

//Función para guardar o editar
function guardar_y_editar_empresa(e) {
	// e.preventDefault(); //No se activará la acción predeterminada del evento
	if (modoDemo) {
		sw_warning('Modo demo', 'No puedes editar o guardar en modo demo');		
		return;
	}
	//$("#btnGuardar").prop("disabled",true);

	var formData = new FormData($("#form-empresa")[0]);
	$.ajax({
		url: "../ajax/empresa.php?op=guardaryeditar",
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,
		success: function (datos) {
			sw_success('Éxito', datos );			
			mostrarform(false);			
			listar();
		}
	});
}

function mostrarform(flag) {
	limpiar();
	if (flag == true) {
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		$("#btnGuardar").prop("disabled", false);
		$("#btnagregar").hide();
		$("#preview").empty();
	}else {
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#btnagregar").show();
	}
}

function mostrar(idempresa) {
	$.post("../ajax/empresa.php?op=mostrar", { idempresa: idempresa }, function (data, status) {
		data = JSON.parse(data);
		mostrarform(true);

		$("#distrito").val(data.distrito).trigger("change");

		$("#idempresa").val(data.idempresa);
		$("#razonsocial").val(data.nombre_razon_social);
		$("#ncomercial").val(data.nombre_comercial);
		$("#domicilio").val(data.domicilio_fiscal);
		$("#ruc").val(data.numero_ruc);
		$("#tel1").val(data.telefono1);
		$("#tel2").val(data.telefono2);
		$("#correo").val(data.correo);
		$("#web").val(data.web);
		$("#webconsul").val(data.webconsul);
		//$("#imagenmuestra").attr("src","../files/logo/"+data.logo);
		$("#imagenmuestra").show();

		if (data.logo_c_r == 1) { $("#logo_c_r").prop('checked', true).trigger("change");	} else { $("#logo_c_r").prop('checked', false).trigger("change");	}		

		if (data.logo == "" || data.logo == null ) {
			$("#imagenmuestra").attr("src", "../files/logo/img_defecto.png");
			//$("#imagenmuestra").attr("src","c:/sfs/files/logo/simagen.png");
			$("#imagenactual").val("");
			$("#imagen").val("");
		} else {
			$("#imagenmuestra").attr("src", '../files/logo/' + data.logo);
			//$("#imagenmuestra").attr("src","c://sfs//files//logo//" + data.logo);
			$("#imagenactual").val(data.logo);
			$("#imagen").val("");
		}

		//$("#imagenactual").val(data.logo);
		$("#ubigueo").val(data.ubigueo);
		$("#codubigueo").val(data.codubigueo);
		$("#ciudad").val(data.ciudad);		
		$("#interior").val(data.interior);
		$("#codigopais").val(data.codigopais);

		//Configuraciones
		$("#igv").val(data.igv);
		$("#porDesc").val(data.porDesc);
		$("#banco1").val(data.banco1);
		$("#cuenta1").val(data.cuenta1);
		$("#banco2").val(data.banco2);
		$("#cuenta2").val(data.cuenta2);
		$("#banco3").val(data.banco3);
		$("#cuenta3").val(data.cuenta3);
		$("#banco4").val(data.banco4);
		$("#cuenta4").val(data.cuenta4);
		$("#cuentacci1").val(data.cuentacci1);
		$("#cuentacci2").val(data.cuentacci2);
		$("#cuentacci3").val(data.cuentacci3);
		$("#cuentacci4").val(data.cuentacci4);
		$("#tipoimpresion").val(data.tipoimpresion);
		$("#textolibre").val(data.textolibre);
	});
}

function listar() {
	tabla_empresa = $('#tbllistado').dataTable({
		lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
		"aProcessing": true,//Activamos el procesamiento del datatables
		"aServerSide": true,//Paginación y filtrado realizados por el servidor
		dom:"<'row'<'col-md-3'B><'col-md-3 float-left'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",//Definimos los elementos del control de tabla
    buttons: [
      { text: '<i class="fa-solid fa-arrows-rotate" data-toggle="tooltip" data-original-title="Recargar"></i> ', className: "btn bg-gradient-info m-r-5px", action: function ( e, dt, node, config ) { if (tabla_empresa) { tabla_empresa.ajax.reload(null, false); } } },
      { extend: 'copyHtml5', exportOptions: { columns: [1,2,3,4,5,6], }, text: `<i class="fas fa-copy" data-toggle="tooltip" data-original-title="Copiar"></i>`, className: "btn bg-gradient-gray m-r-5px", footer: true,  }, 
      { extend: 'excelHtml5', exportOptions: { columns: [1,2,3,4,5,6], }, text: `<i class="far fa-file-excel fa-lg" data-toggle="tooltip" data-original-title="Excel"></i>`, className: "btn bg-gradient-success m-r-5px", footer: true,  }, 
      { extend: 'pdfHtml5', exportOptions: { columns: [1,2,3,4,5,6], }, text: `<i class="far fa-file-pdf fa-lg" data-toggle="tooltip" data-original-title="PDF"></i>`, className: "btn bg-gradient-danger m-r-5px", footer: false, orientation: 'landscape', pageSize: 'LEGAL',  },
      { extend: "colvis", text: `<i class="fas fa-outdent"></i>`, className: "btn bg-gradient-gray", exportOptions: { columns: "th:not(:last-child)", }, },
    ],
		"ajax":	{
			url: '../ajax/empresa.php?op=listar',
			type: "get",
			dataType: "json",
			error: function (e) {
				console.log(e.responseText);
			}
		},
		"bDestroy": true,
		"iDisplayLength": 10,//Paginación
		"order": [[0, "desc"]]//Ordenar (columna,orden)
	}).DataTable();
}

document.getElementById("imagen").onchange = function (e) {
	// Creamos el objeto de la clase FileReader
	let reader = new FileReader();

	// Leemos el archivo subido y se lo pasamos a nuestro fileReader
	reader.readAsDataURL(e.target.files[0]);

	// Le decimos que cuando este listo ejecute el código interno
	reader.onload = function () {
		// let preview = document.getElementById('preview'),	image = document.createElement('img');
		// image.src = reader.result;
		// image.width = '100';
		// image.height = 'auto';
		// preview.innerHTML = '';
		// preview.append(image);
		$('#imagenmuestra').attr('src', reader.result);
		$("#form-empresa").valid();
	};
}

init();

function buscar_sunat(input = '', input_numdoc) {
	var num_ruc= $(input_numdoc).val();
	$('.btn-search-s').html(`<i class="fas fa-spinner fa-pulse fa-lg"></i>`);
	$.post("../ajax/ajax_general.php?op=sunat_otro", {ruc: num_ruc},	function (e, textStatus, jqXHR) {
		e = JSON.parse(e); console.log(e);
		if (!jQuery.isEmptyObject(e.error) || !jQuery.isEmptyObject(e.message)) {
			sw_error('Error!', e.error, 5000); 
		} else {              
			$('#razonsocial').val(e.nombre);
			$('#ncomercial').val('--');
			$('#domicilio').val(e.direccion);
			
			$('#distrito').val(e.distrito).trigger("change");
			$('#interior').val(e.distrito);
		}
		$('.btn-search-s').html(`<i class="fas fa-search"></i>`);
		$("#form-empresa").valid();
	});
}

function llenar_dep_prov_ubig(input) {

  $(".chargue-pro").html(`<i class="fas fa-spinner fa-pulse text-danger"></i>`); 
  $(".chargue-dep").html(`<i class="fas fa-spinner fa-pulse text-danger"></i>`); 
  $(".chargue-ubi").html(`<i class="fas fa-spinner fa-pulse text-danger"></i>`); 

  if ($(input).select2("val") == null || $(input).select2("val") == '') { 
    $("#departamento").val(""); 
    $("#ciudad").val(""); 
    $("#codubigueo").val(""); 

    $(".chargue-pro").html(''); $(".chargue-dep").html(''); $(".chargue-ubi").html('');
  } else {
    var iddistrito =  $(input).select2('data')[0].element.attributes.iddistrito.value;
    $.post(`../ajax/ajax_general.php?op=select2_distrito_id&id=${iddistrito}`, function (e) {   
      e = JSON.parse(e); console.log(e);
      $("#departamento").val(e.departamento); 
      $("#ciudad").val(e.provincia); 
      $("#codubigueo").val(e.ubigeo_inei); 

      $(".chargue-pro").html(''); $(".chargue-dep").html(''); $(".chargue-ubi").html('');
			$("#form-empresa").valid();
    });
  }  
}

function logo_cu_re(input) {
	if ($(input).is(":checked")) {
		$('.logo_cuadrado_rectangulo').html('Cuadrado');
	} else {
		$('.logo_cuadrado_rectangulo').html('Rectangunlo');
	}
}


// .....::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M  :::::::::::::::::::::::::::::::::::::::..

$(function () {

  $("#form-empresa").validate({
    ignore: "",
    rules: { 
      ruc:      		{ required: true, minlength:11, maxlength:11 },
      razonsocial:	{ required: true, minlength:4, maxlength:100},
      domicilio:  	{ required: true, minlength:4, maxlength:100},
      ncomercial: 	{ required: true, minlength:4, maxlength:100},
      tel1:       	{ minlength:4, maxlength:10 },
      tel2:    			{ minlength:4, maxlength:10 },
      correo:     	{ minlength:4, maxlength:50 },
      web:        	{ required: true, minlength:4, maxlength:50,  }, 
      webconsul:  	{ required: true, minlength:4, maxlength:50,  }, 
      ubigueo:    	{ required: true, minlength:4, maxlength:5, number: true, }, 
      codubigueo: 	{ required: true, minlength:4, maxlength:6,  number: true, min: 0,  }, 
      igv:        	{ required: true, number: true, min: 0,  max: 100, }, 
      porDesc:    	{ required: true, number: true, min: 0,  max: 100,}, 
			departamento: { required: true, minlength:2, maxlength:50, },
      ciudad:     	{ required: true, minlength:2, maxlength:50, }, 
      distrito:   	{ required: true, maxlength:50, }, 
      interior:   	{ required: true, minlength:2, maxlength:50, }, 
      codigopais: 	{ required: true, maxlength:5,  }, 
      tipoimpresion:{ required: true, maxlength:2,  }, 
      textolibre:  	{ required: true, minlength:5, maxlength:100,  }, 
      imagen:     	{ extension: "png|jpg|jpeg|webp|svg",  }, 

      banco1:       { minlength:2, maxlength:100 },
      cuenta1:      { minlength:7, maxlength:15 },
      cuentacci1:   { minlength:10, maxlength:20 },
      banco2:       { minlength:2, maxlength:100 },
			cuenta2:      { minlength:7, maxlength:15 },
      cuentacci2:   { minlength:10, maxlength:20 },
      banco3:       { minlength:2, maxlength:100 },
			cuenta3:      { minlength:7, maxlength:15 },
      cuentacci3:   { minlength:10, maxlength:20 },
      banco4:       { minlength:2, maxlength:100 },
			cuenta4:      { minlength:7, maxlength:15 },
      cuentacci4:   { minlength:10, maxlength:20 },

    },
    messages: {
      ruc:      		{ required: "Campo requerido", minlength:"Minimo {0} caracteres", maxlength:"Maximo {0} caracteres" },
      razonsocial:  { required: "Campo requerido", minlength:"Minimo {0} caracteres", maxlength:"Maximo {0} caracteres" },
      domicilio:  	{ required: "Campo requerido", minlength:"Minimo {0} caracteres", maxlength:"Maximo {0} caracteres" },
      ncomercial:   { required: "Campo requerido", minlength:"Minimo {0} caracteres", maxlength:"Maximo {0} caracteres" },
      tel1:         { minlength:"Minimo {0} caracteres", maxlength:"Maximo {0} caracteres" },
      tel2:    			{ minlength:"Minimo {0} caracteres", maxlength:"Maximo {0} caracteres" },
      correo:       { minlength:"Minimo {0} caracteres", maxlength:"Maximo {0} caracteres" },
      web:          { required: "Campo requerido", minlength:"Minimo {0} caracteres", maxlength:"Maximo {0} caracteres" },
      webconsul:    { required: "Campo requerido", minlength:"Minimo {0} caracteres", maxlength:"Maximo {0} caracteres" },
      ubigueo:    	{ step: "Decimales mayor a {0}", },
      codubigueo:   { step: "Decimales mayor a {0}", },
      igv:        	{ step: "Decimales mayor a {0}", },
      porDesc:      { step: "Decimales mayor a {0}", },
      ciudad:       { maxlength:"Maximo {0} caracteres" },
      distrito:     { minlength:"Minimo {0} caracteres", maxlength:"Maximo {0} caracteres" },
      interior:     { minlength:"Minimo {0} caracteres", maxlength:"Maximo {0} caracteres" },
      codigopais:   { minlength:"Minimo {0} caracteres", maxlength:"Maximo {0} caracteres" },
      tipoimpresion:{ minlength:"Minimo {0} caracteres", maxlength:"Maximo {0} caracteres" },
      textolibre:   { minlength:"Minimo {0} caracteres", maxlength:"Maximo {0} caracteres" },
      imagen:       { extension: "Ingrese imagenes validas ( {0} )", },

			banco1:       { minlength:"Minimo {0} caracteres", maxlength:"Maximo {0} caracteres" },
      cuenta1:      { minlength:"Minimo {0} caracteres", maxlength:"Maximo {0} caracteres" },
      cuentacci1:   { minlength:"Minimo {0} caracteres", maxlength:"Maximo {0} caracteres" },
      banco2:       { minlength:"Minimo {0} caracteres", maxlength:"Maximo {0} caracteres" },
			cuenta2:      { minlength:"Minimo {0} caracteres", maxlength:"Maximo {0} caracteres" },
      cuentacci2:   { minlength:"Minimo {0} caracteres", maxlength:"Maximo {0} caracteres" },
      banco3:       { minlength:"Minimo {0} caracteres", maxlength:"Maximo {0} caracteres" },
			cuenta3:      { minlength:"Minimo {0} caracteres", maxlength:"Maximo {0} caracteres" },
      cuentacci3:   { minlength:"Minimo {0} caracteres", maxlength:"Maximo {0} caracteres" },
      banco4:       { minlength:"Minimo {0} caracteres", maxlength:"Maximo {0} caracteres" },
			cuenta4:      { minlength:"Minimo {0} caracteres", maxlength:"Maximo {0} caracteres" },
      cuentacci4:   { minlength:"Minimo {0} caracteres", maxlength:"Maximo {0} caracteres" },
    },
        
    errorElement: "span",

    errorPlacement: function (error, element) {
      error.addClass("invalid-feedback");
      element.closest(".form-group").append(error);
    },

    highlight: function (element, errorClass, validClass) {
      $(element).addClass("is-invalid").removeClass("is-valid");
    },

    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass("is-invalid").addClass("is-valid");   
    },
    submitHandler: function (e) {
      $(".modal-body").animate({ scrollTop: $(document).height() }, 600); // Scrollea hasta abajo de la página
      guardar_y_editar_empresa(e);      
    },
  });
});

function ver_img_zoom(file, nombre) {
  $('.title-name-foto-zoom').html(nombre);
  // $(".tooltip").remove();
  $("#modal-ver-perfil-empresa").modal("show");
  $('#div-foto-zoom').html(`<span class="jq_image_zoom"><img class="img-thumbnail" src="${file}" onerror="this.src='../assets/svg/404-v2.svg';" alt="Foto zoom" width="100%"></span>`);
  $('.jq_image_zoom').zoom({ on:'grab' });
}