var tabla;

//Función que se ejecuta al inicio
function init(){
	mostrarform(false);
	listar();

	$("#formulario").on("submit",function(e){ guardar(e);})
}

//Función limpiar
function limpiar()
{
	$("#idregistro").val("");
	$("#ano").val("");
	$("#codigo").val("");
	$("#denominacion").val("");
	$("#costoinicial").val("");
	$("#saldoinicial").val("");
	$("#valorinicial").val("");
	$("#compras").val("");
	$("#ventas").val("");
	$("#saldofinal").val("");
	$("#costo").val("");
	$("#valorfinal").val("");
	document.getElementById("btnGuardar").innerHTML = "Agregar";
	
}

//Función mostrar formulario
function mostrarform(flag)
{
	limpiar();
	if (flag)
	{
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").hide();
	}
	else
	{
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#btnagregar").show();
	}
}

//Función cancelarform
function cancelarform()
{
	limpiar();
	mostrarform(false);
}

//Función Listar
function listar()
{
	tabla=$('#tbllistado').dataTable(
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
					url: '../ajax/registroinventario.php?op=listar',
					type : "get",
					dataType : "json",						
					error: function(e){
						console.log(e.responseText);	
					}
				},
		"bDestroy": true,
		"iDisplayLength": 10
	    
	}).DataTable();
}
//Función para guardar o editar

function guardar(e) {
	e.preventDefault(); //No se activará la acción predeterminada del evento
	$("#btnGuardar").prop("disabled", true);
	var formData = new FormData($("#formulario")[0]);

	$.ajax({
		url: "../ajax/registroinventario.php?op=guardar",
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,
		success: function(e) {
			e = JSON.parse(e);
			if(e.status == true){
				sw_success("Excelente!", "Registro guardado exitosamente", 3000);
				mostrarform(false);
				tabla.ajax.reload();
			}else{ver_errore(e);}
		}
	});
	limpiar();
}

function mostrar(idregistro)
{

	$.post("../ajax/registroinventario.php?op=mostrar",{idregistro : idregistro}, function(e, status)
	{
		e = JSON.parse(e);
		if(e.status == true){		
		mostrarform(true);
		$("#idregistro").val(e.data.idregistro);
		$("#ano").val(e.data.ano);
		$("#codigo").val(e.data.codigo);
		$("#denominacion").val(e.data.denominacion);
		$("#costoinicial").val(e.data.costoinicial);
		$("#saldoinicial").val(e.data.saldoinicial);
		$("#valorinicial").val(e.data.valorinicial);
		$("#compras").val(e.data.compras);
		$("#ventas").val(e.data.ventas);
		$("#saldofinal").val(e.data.saldofinal);
		$("#costo").val(e.data.costo);
		$("#valorfinal").val(e.data.valorfinal);
		$('#agregarinventario').modal('show');
		document.getElementById("btnGuardar").innerHTML = "Actualizar";
		}else{ver_errores(e);}
 	})
}


function eliminar(idregistro){
	swal.fire({
	  title: "¿Está seguro?",
	  text: "¿Desea eliminar este registro?",
	  icon: "warning",
	  showCancelButton: true,
	  confirmButtonColor: "#3085d6",
	  cancelButtonColor: "#d33",
	  confirmButtonText: "Sí, eliminar",
	  cancelButtonText: "Cancelar"
	}).then((result) => {
	  if (result.isConfirmed) {
	    $.post("../ajax/registroinventario.php?op=eliminar", {idregistro : idregistro}, function(e){
				e = JSON.parse(e);
				if(e.status == true){
		  		sw_success("Excelente", "Registro eliminado correctamente", 3000);
	      	tabla.ajax.reload();
				}else{ver_errores(e);}
	    });
	  }
	});
}

function refrescartabla()
{
tabla.ajax.reload();
listar();
}


init();