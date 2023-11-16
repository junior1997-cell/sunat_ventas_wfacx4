var tabla_marca;

//Función que se ejecuta al inicio
function init_marca(){
	
	listar_tabla_marca();

	$("#guardar_registro_marca").on("click", function (e) { $("#submit-form-marca").submit(); });	
}

//Función limpiar
function limpiar_form_marca() {
	$("#idmarca").val("");
	$("#nombre_marca").val("");	
	$("#guardar_registro_marca").html("Agregar");

	// Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//Función Listar
function listar_tabla_marca() {
	tabla_marca=$('#tabla-marca').dataTable(	{
		lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
		"aProcessing": true,//Activamos el procesamiento del datatables
		"aServerSide": true,//Paginación y filtrado realizados por el servidor
		dom: 'Bfrtip',//Definimos los elementos del control de tabla
		dom:"<'row'<'col-md-3'B><'col-md-3 float-left'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",//Definimos los elementos del control de tabla
    buttons: [
      { text: '<i class="fa-solid fa-arrows-rotate" data-toggle="tooltip" data-original-title="Recargar"></i> ', className: "btn bg-gradient-info m-r-5px", action: function ( e, dt, node, config ) { if (tabla_marca) { tabla_marca.ajax.reload(null, false); } } },      
      { extend: 'excelHtml5', exportOptions: { columns: [2,3], }, text: `<i class="far fa-file-excel fa-lg" data-toggle="tooltip" data-original-title="Excel"></i>`, className: "btn bg-gradient-success m-r-5px", footer: true,  }, 
    ],
		"ajax":	{
			url: '../ajax/marca.php?op=listar_tabla_marca',
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

function guardar_y_editar_marca(e) {
	// e.preventDefault(); //No se activará la acción predeterminada del evento	
	var formData = new FormData($("#form-marca")[0]);

	$.ajax({
		url: "../ajax/marca.php?op=guardar_y_editar_marca",
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,
		success: function(datos) {
			if (datos == "Categoría ya registrada") { 
				swal.fire({	title: "Error",	html: datos, icon: "error",timer: 3000, showConfirmButton: true	});
			} else if (datos == 1) {                 
				Swal.fire({	title: "Excelente",	html: 'Registrado correctamente', 'icon': "success",	timer: 3500, showConfirmButton: true	});				
				tabla_marca.ajax.reload(null, false);
				$('#modal-agregar-marca').modal('hide');
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
          $("#barra_progress_marca").css({"width": percentComplete+'%'});
          $("#barra_progress_marca div").text(`${percentComplete.toFixed(1)} %`);
        }
      }, false);
      return xhr;
    },
    beforeSend: function () {
      $("#guardar_registro_producto").html('<i class="fas fa-spinner fa-pulse fa-lg"></i>').addClass('disabled');
			$("#barra_progress_marca_div").show();
      $("#barra_progress_marca").css({ width: "0%",  }).addClass('progress-bar-striped progress-bar-animated');
			$("#barra_progress_marca div").text("0%");
    },
    complete: function () {
			$("#barra_progress_marca_div").hide();
      $("#barra_progress_marca").css({ width: "0%", }).text("0%").removeClass('progress-bar-striped progress-bar-animated');
			$("#barra_progress_marca div").text("0%");
    },
    error: function (jqXhr) { ver_errores(jqXhr); },
	});	
}


function mostrar_editar_marca(idmarca) {
	limpiar_form_marca();
	$('#modal-agregar-marca').modal('show');
	$("#guardar_registro_marca").html("Actualizar");
	$.post("../ajax/marca.php?op=mostrar_editar",{idmarca : idmarca}, function(data, status)	{
		data = JSON.parse(data);	
    $("#idmarca").val(data.idmarca);	
		$("#nombre_marca").val(data.descripcion);
		
 	});
}

//Función para desactivar registros
function desactivar_marca(idmarca) {
	Swal.fire({
		title: '¿Está seguro de desactivar esta Marca?',
		icon: 'question',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Sí, desactivar!',
		cancelButtonText: 'Cancelar'
	}).then((result) => {
		if (result.isConfirmed) {
			$.post("../ajax/marca.php?op=desactivar", {idmarca : idmarca}, function(e){
				Swal.fire({		title: 'Marca Desactivada',	html: e, icon: 'success', showConfirmButton: true,	timer: 3500	});
				tabla_marca.ajax.reload(null, false);
			});
		}
	});
}


//Función para activar registros
function activar_marca(idmarca) {
	Swal.fire({
		title: '¿Está Seguro de activar esta Marca?',
		icon: 'question',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Sí, activar!',
		cancelButtonText: 'Cancelar'
	}).then((result) => {
		if (result.isConfirmed) {
			$.post("../ajax/marca.php?op=activar", {idmarca : idmarca}, function(e){
				Swal.fire({	title: 'Marca Activada', html: e, icon: 'success', showConfirmButton: true,	timer: 3500	});
				tabla_marca.ajax.reload(null, false);
			});
		}
	});
}

function mayus(e) {  e.value = e.value.toUpperCase(); }

init_marca();

// .....::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M  :::::::::::::::::::::::::::::::::::::::..

$(function () {

  $("#form-marca").validate({
    rules: { 
      nombre_marca: { required: true, minlength:2, maxlength:50 } 
    },
    messages: {
      nombre_marca: { required: "Campo requerido", minlength:"Minimo {0} caracteres", maxlength:"Maximo {0} caracteres" },
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
      guardar_y_editar_marca(e);      
    },
  });
});
