var tabla_familia;

//Función que se ejecuta al inicio
function init_familia(){
	
	listar_tabla_familia();

	$("#guardar_registro_familia").on("click", function (e) { $("#submit-form-familia").submit(); });	
	$("#guardar_registro_import_familia").on("click", function (e) { if ( $(this).hasClass('send-data')==false) { $("#submit-form-import-familia").submit(); }  });
}

//Función limpiar
function limpiar_form_familia() {
	$("#idfamilia").val("");
	$("#nombrec").val("");	
	$("#guardar_registro_familia").html('Guardar Cambios').removeClass('disabled send-data');

	// Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//Función limpiar
function limpiar_form_import_familia() {

	$("#upload_file_familia").val("");	
	$("#guardar_registro_familia").html('Guardar Cambios').removeClass('disabled send-data');

	// Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//Función Listar
function listar_tabla_familia() {
	tabla_familia=$('#tabla-familia').dataTable(	{
		lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
		"aProcessing": true,//Activamos el procesamiento del datatables
		"aServerSide": true,//Paginación y filtrado realizados por el servidor
		dom: 'Bfrtip',//Definimos los elementos del control de tabla
		dom:"<'row'<'col-md-3'B><'col-md-3 float-left'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",//Definimos los elementos del control de tabla
    buttons: [
      { text: '<i class="fa-solid fa-arrows-rotate" data-toggle="tooltip" data-original-title="Recargar"></i> ', className: "btn bg-gradient-info m-r-5px", action: function ( e, dt, node, config ) { if (tabla_familia) { tabla_familia.ajax.reload(null, false); } } },      
      { extend: 'excelHtml5', exportOptions: { columns: [2,3], }, text: `<i class="far fa-file-excel fa-lg" data-toggle="tooltip" data-original-title="Excel"></i>`, className: "btn bg-gradient-success m-r-5px", footer: true,  }, 
    ],
		"ajax":	{
			url: '../ajax/familia.php?op=listar_tabla_familia',
			type : "get",
			dataType : "json",
			error: function(e){
				console.log(e.responseText);
			}
		},
		language: {
      lengthMenu: "Ver: _MENU_",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
		"bDestroy": true,
		"iDisplayLength": 10,//Paginación
	  "order": [[ 0, "asc" ]]//Ordenar (columna,orden)
	}).DataTable();
}
//Función para guardar o editar

function guardar_y_editar_familia(e) {
	// e.preventDefault(); //No se activará la acción predeterminada del evento	
	var formData = new FormData($("#form-familia")[0]);

	$.ajax({
		url: "../ajax/familia.php?op=guardar_y_editar_familia",
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,
		success: function(datos) {
			if (datos == "Categoría ya registrada") { 
				swal.fire({	title: "Error",	html: datos, icon: "error",timer: 3000, showConfirmButton: true	});
			} else if (datos == 1) {                 
				Swal.fire({	title: "Excelente",	html: 'Registrado correctamentetrue', icon: "success",	timer: 3500, showConfirmButton: true	});				
				tabla_familia.ajax.reload(null, false);
				$('#modal-agregar-familia').modal('hide');
			}else{				
				swal.fire({	title: "Error",	html: datos, icon: "error",timer: 3000, showConfirmButton: true	});
			}
		},
		xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_familia").css({"width": percentComplete+'%'});
          $("#barra_progress_familia div").text(`${percentComplete.toFixed(1)} %`);
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_producto").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
			$("#barra_progress_familia_div").show();
      $("#barra_progress_familia").css({ width: "0%",  }).addClass('progress-bar-striped progress-bar-animated');
			$("#barra_progress_familia div").text("0%");
    },
    complete: function () {
			$("#barra_progress_familia_div").hide();
      $("#barra_progress_familia").css({ width: "0%", }).text("0%").removeClass('progress-bar-striped progress-bar-animated');
			$("#barra_progress_familia div").text("0%");
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
	});	
}

function importar_familia(e) {
	// e.preventDefault(); //No se activará la acción predeterminada del evento	
	var formData = new FormData($("#form-importar-familia")[0]);

	$.ajax({
		url: "../ajax/familia.php?op=importar_familia",
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,
		success: function(e) {
			try {
				e = JSON.parse(e); console.log(e);
				if (e.status == true) { 					             
					Swal.fire({	title: "Excelente",	html: 'Registrado correctamente', icon: "success", showConfirmButton: true	});				
					tabla_familia.ajax.reload(null, false);
					$('#modal-importar-familia').modal('hide');
				}else{				
					swal.fire({	title: "Error",	html: e, icon: "error", showConfirmButton: true	});
				}
			} catch (error) {
				swal.fire({	title: "Error",	html: error, icon: "error", showConfirmButton: true	});
				console.log(error);
			}
			$("#guardar_registro_import_familia").html('Guardar Cambios').removeClass('disabled send-data');
		},
		xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total)*100;
          /*console.log(percentComplete + '%');*/
          $("#barra_progress_imp_familia").css({"width": percentComplete+'%'});
          $("#barra_progress_imp_familia div").text(`${percentComplete.toFixed(1)} %`);
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_import_familia").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled send-data');
			$("#barra_progress_imp_familia_div").show();
      $("#barra_progress_imp_familia").css({ width: "0%",  }).addClass('progress-bar-striped progress-bar-animated');
			$("#barra_progress_imp_familia div").text("0%");
    },
    complete: function () {
			$("#barra_progress_imp_familia_div").hide();
      $("#barra_progress_imp_familia").css({ width: "0%", }).text("0%").removeClass('progress-bar-striped progress-bar-animated');
			$("#barra_progress_imp_familia div").text("0%");
    },
    error: function (jqXhr) { console.log(jqXhr); },
	});	
}


function mostrar_editar_familia(idfamilia) {
	limpiar_form_familia();
	$('#modal-agregar-familia').modal('show');
	$("#guardar_registro_familia").html("Actualizar");
	$.post("../ajax/familia.php?op=mostrar",{idfamilia : idfamilia}, function(data, status)	{
		data = JSON.parse(data);		
		$("#nombrec").val(data.descripcion);
		$("#idfamilia").val(data.idfamilia);
 	});
}

//Función para desactivar registros
function desactivar_familia(idfamilia) {
	Swal.fire({
		title: '¿Está seguro de desactivar esta categoria?',
		icon: 'question',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Sí, desactivar!',
		cancelButtonText: 'Cancelar'
	}).then((result) => {
		if (result.isConfirmed) {
			$.post("../ajax/familia.php?op=desactivar", {idfamilia : idfamilia}, function(e){
				Swal.fire({		title: 'Categoria Desactivada',	html: e, icon: 'success', showConfirmButton: true,	timer: 3500	});
				tabla_familia.ajax.reload(null, false);
			});
		}
	});
}


//Función para activar registros
function activar_familia(idfamilia) {
	Swal.fire({
		title: '¿Está Seguro de activar esta categoria?',
		icon: 'question',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Sí, activar!',
		cancelButtonText: 'Cancelar'
	}).then((result) => {
		if (result.isConfirmed) {
			$.post("../ajax/familia.php?op=activar", {idfamilia : idfamilia}, function(e){
				Swal.fire({	title: 'Categoria Activada', html: e, icon: 'success', showConfirmButton: true,	timer: 3500	});
				tabla_familia.ajax.reload(null, false);
			});
		}
	});
}

function mayus(e) {  e.value = e.value.toUpperCase(); }

init_familia();

// .....::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M  :::::::::::::::::::::::::::::::::::::::..

$(function () {

  $("#form-familia").validate({
    rules: { 
      nombrec: { required: true, minlength:2, maxlength:50 } 
    },
    messages: {
      nombrec: { required: "Campo requerido", minlength:"Minimo {0} caracteres", maxlength:"Maximo {0} caracteres" },
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
      guardar_y_editar_familia(e);      
    },
  });

  $("#form-importar-familia").validate({
    rules: { 
      upload_file_familia: { required: true,  extension: "xls|xlsx", } 
    },
    messages: {
      upload_file_familia: { required: "Campo requerido", extension: "Ingrese imagenes validas ( {0} )", },
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
      importar_familia(e);      
    },
  });
});
