var tabla;
var modoDemo = false;
//Función que se ejecuta al inicio
function init() {
	//mostrarform(false);
	mostrar();
	//listar();

	$("#formulario").on("submit", function (e) {
		guardaryeditar(e);
	})
}


//Función para guardar o editar
function guardaryeditar(e) {
	e.preventDefault(); //No se activará la acción predeterminada del evento
	//$("#btnGuardar").prop("disabled",true);
	if (modoDemo) {
		Swal.fire({
			icon: 'warning',
			title: 'Modo demo',
			text: 'No puedes editar o guardar en modo demo',
		});
		return;
	}
	var formData = new FormData($("#formulario")[0]);

	$.ajax({
		url: "../ajax/cargarcertificado.php?op=guardaryeditar",
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,

		success: function (datos) {
			Swal.fire({
				icon: 'success',
				title: 'Guardado exitoso',
				text: datos,
			}).then((result) => {
				if (result.isConfirmed) {
					mostrar();
				}
			});
		},
		error: function (datos) {
			Swal.fire({
				icon: 'error',
				title: 'Error al guardar',
				text: datos,
			});
		}
	});
}



function validarclave() {
	var formData = new FormData($("#formulario")[0]);
	$.ajax({
		url: "../ajax/cargarcertificado.php?op=validarclave",
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,
		success: function (datos) {
			Swal.fire({
				icon: 'success',
				title: 'Validación exitosa',
				text: datos,
			});
		}
	});
}





function mostrar() {
	$.post("../ajax/cargarcertificado.php?op=mostrar", function (data, status) {
		data = JSON.parse(data);

		$("#idcarga").val(data.idcarga);
		$("#numeroruc").val(data.numeroruc);
		$("#razon_social").val(data.razon_social);
		$("#usuarioSol").val(data.usuarioSol);
		$("#claveSol").val(data.claveSol);
		$("#rutacertificado").val(data.rutacertificado);
		$("#rutaserviciosunat").val(data.rutaserviciosunat);
		$("#webserviceguia").val(data.webserviceguia);
		$("#nombrepem").val(data.nombrepem);
		$("#keypfx").val(data.passcerti);

	})
}


init();