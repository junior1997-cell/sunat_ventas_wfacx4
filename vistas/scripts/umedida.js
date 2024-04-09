var tabla;
var modoDemo = false;
//Función que se ejecuta al inicio
function init() {
	mostrarform(false);
	listar();

	$("#formulario").on("submit", function (e) { guardaryeditar(e); })
}

//Función limpiar
function limpiar() {
	$("#nombre").val("");
	$("#abre").val("");
	$("#equivalencia").val("");
	$("#idunidadm").val("");
	document.getElementById("btnGuardar").innerHTML = "Agregar";

}

function stopRKey(evt) {
	var evt = (evt) ? evt : ((event) ? event : null);
	var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
	if ((evt.keyCode == 13) && (node.type == "text")) { return false; }
}

//BLOQUEA ENTER
document.onkeypress = stopRKey;

//Función mostrar formulario
function mostrarform(flag) {
	limpiar();
	if (flag) {
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		$("#btnGuardar").prop("disabled", false);
		$("#btnagregar").hide();
	}
	else {
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#btnagregar").show();
	}
}

//Función cancelarform
function cancelarform() {
	limpiar();
	mostrarform(false);
}

//Función Listar
function listar() {
	tabla = $('#tbllistado').dataTable(
		{
			"aProcessing": true,//Activamos el procesamiento del datatables
			"aServerSide": true,//Paginación y filtrado realizados por el servidor
			dom: 'Bfrtip',//Definimos los elementos del control de tabla
			buttons: [
				// 'copyHtml5',
				// 'excelHtml5',
				// 'csvHtml5',
				// 'pdf'
			],
			"ajax":
			{
				url: '../ajax/umedida.php?op=listar',
				type: "get",
				dataType: "json",
				error: function (e) {
					console.log(e.responseText);
				}
			},
			"bDestroy": true,
			"iDisplayLength": 15,//Paginación
			"order": [[0, "desc"]]//Ordenar (columna,orden)
		}).DataTable();
}
//Función para guardar o editar

function guardaryeditar(e) {
	e.preventDefault(); //No se activará la acción predeterminada del evento
	if (modoDemo) {
		Swal.fire({
			icon: 'warning',
			title: 'Modo demo',
			text: 'No puedes editar o guardar en modo demo',
		});
		return;
	}
	$("#btnGuardar").prop("disabled", true);
	var formData = new FormData($("#formulario")[0]);
	$.ajax({
		url: "../ajax/umedida.php?op=guardaryeditar",
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,
		success: function (e) {
			try{
        e = JSON.parse(e);

        if(e.status == 'registrado'){
          sw_success( "Correcto!", "Unidad de Medida Registrada correctamente", 3000);
          listar(); $("#agregarunidademedida").modal('hide');
        }

        else if(e.status == 'modificado'){
          sw_success( "Correcto!", "Unidad de Medida Actualizado correctamente", 3000);
          listar(); $("#agregarunidademedida").modal('hide');
        }
        else{ ver_errores(e); }

			} catch (err) { console.log('Error: ', err.message); toastr_error("Error temporal!!",'Puede intentalo mas tarde, o comuniquese con:<br> <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>', 700); }      
		}
	});
}

function mostrar(idunidadm) {
  limpiar();
  $("#agregarunidademedida").modal('show');
	$.post("../ajax/umedida.php?op=mostrar", { idunidadm: idunidadm }, function (e, status) {
		e = JSON.parse(e);
    if(e.status == true){
      mostrarform(true);
      $("#idunidadm").val(e.data.idunidad);
      $("#nombre").val(e.data.nombreum);
      $("#abre").val(e.data.abre);
      $("#equivalencia").val(e.data.equivalencia);
      $('#agregarunidademedida').modal('show');
      document.getElementById("btnGuardar").innerHTML = "Actualizar";
    } else {ver_errores(e);}
	})
}

//Función para desactivar registros
function desactivar(idunidadm) {
	if (modoDemo) {
		Swal.fire({
			icon: 'warning',
			title: 'Modo demo',
			text: 'No puedes editar o guardar en modo demo',
		});
		return;
	}
	Swal.fire({
		title: '¿Está seguro de desactivar la unidad de medida?',
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Sí, desactivar',
		cancelButtonText: 'Cancelar'
	}).then((result) => {
		if (result.isConfirmed) {
			$.post("../ajax/umedida.php?op=desactivar", {
				idunidadm: idunidadm
			}, function (e) {
				e = JSON.parse(e);
        if (e.status == true){
          sw_success("Desactivado", "Almacen desactivado", 3000);
				  tabla.ajax.reload();
        } else {ver_errores(e);}
			});
		}
	})
}

//Función para activar registros
function activar(idunidadm) {
	Swal.fire({
		title: '¿Está seguro de activar la unidad de medida?',
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Sí, activar',
		cancelButtonText: 'Cancelar'
	}).then((result) => {
		if (result.isConfirmed) {
			$.post("../ajax/umedida.php?op=activar", {
				idunidadm: idunidadm
			}, function (e) {
				e = JSON.parse(e);
				if(e.status == true){
					sw_success("Activado", "La unidad de Medida Activa", 3000);
					tabla.ajax.reload();
				}else{ ver_errores(e); }
			});
		}
	})
}

//Función para eliminar registros
function eliminar(idunidadm) {
	if (modoDemo) {
		Swal.fire({
			icon: 'warning',
			title: 'Modo demo',
			text: 'No puedes editar o guardar en modo demo',
		});
		return;
	}
	Swal.fire({
		title: '¿Está seguro de eliminar la unidad de medida?',
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Sí, eliminar',
		cancelButtonText: 'Cancelar'
	}).then((result) => {
		if (result.isConfirmed) {
			$.post("../ajax/umedida.php?op=eliminar", {
				idunidadm: idunidadm
			}, function (e) {
        e = JSON.parse(e);
        if(e.status == true){
          sw_success("Eliminado!", "Almacen elininado exitosamente", 3000)
          tabla.ajax.reload();
        } else {ver_errores(e);}
			});
		}
	})
}

function mayus(e) {
	e.value = e.value.toUpperCase();
}

init();
