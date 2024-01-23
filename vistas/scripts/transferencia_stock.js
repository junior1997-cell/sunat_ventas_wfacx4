var tabla;
var modoDemo = false;
var tabla_transferencia;
//Función que se ejecuta al inicio
function init() {
	listar_almacen();
    listar_transferencia();

}

//Función limpiar
function limpiar() {
	$("#almacen1").val('');
	$("#articulos1").empty();
	$("#cantidad1").val("");
	$("#almacen2").val('');
	$("#articulos2").empty();
	$("#stock1").empty();
	//console.log("todo okey");
}

// Tabla Lista de Artículos
function listar_transferencia() {
    tabla_transferencia = $('#tbllistado').dataTable( {
      lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
      "aProcessing": true,//Activamos el procesamiento del datatables
      "aServerSide": true,//Paginación y filtrado realizados por el servidor
      dom:"<'row'<'col-md-3'B><'col-md-3 float-left'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",//Definimos los elementos del control de tabla
      buttons: [
        { text: '<i class="fa-solid fa-arrows-rotate" data-toggle="tooltip" data-original-title="Recargar"></i> ', className: "btn bg-gradient-info m-r-5px", action: function ( e, dt, node, config ) { if (tabla_transferencia) { tabla_transferencia.ajax.reload(null, false); } } },
        { extend: 'copyHtml5', exportOptions: { columns: [1,2,3,4,5,6], }, text: `<i class="fas fa-copy" data-toggle="tooltip" data-original-title="Copiar"></i>`, className: "btn bg-gradient-gray m-r-5px", footer: true,  }, 
        { extend: 'excelHtml5', exportOptions: { columns: [1,2,3,4,5,6], }, text: `<i class="far fa-file-excel fa-lg" data-toggle="tooltip" data-original-title="Excel"></i>`, className: "btn bg-gradient-success m-r-5px", footer: true,  }, 
        { extend: 'pdfHtml5', exportOptions: { columns: [1,2,3,4,5,6], }, text: `<i class="far fa-file-pdf fa-lg" data-toggle="tooltip" data-original-title="PDF"></i>`, className: "btn bg-gradient-danger m-r-5px", footer: false, orientation: 'landscape', pageSize: 'LEGAL',  },
        { extend: "colvis", text: `<i class="fas fa-outdent"></i>`, className: "btn bg-gradient-gray", exportOptions: { columns: "th:not(:last-child)", }, },
      ],
      "ajax": {
        url: '../ajax/transferencia_stock.php?op=mostrar_tranferencia',
        type : "get",
        dataType : "json",
        error: function(e){  console.log(e.responseText);  }
      },
      "bDestroy": true,
      "iDisplayLength": 10,//Paginación
      "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
    }).DataTable();
  }



// Función para listar ALMACEN de ORIGEN
function listar_almacen() {
    $.ajax({
        url: '../ajax/transferencia_stock.php?op=mostrar_almacenes', 
        type: "GET",
        dataType: "json",
        success: function (data) {
			console.log(data);
            // Limpiar el select antes de agregar nuevas opciones
            $("#almacen1").empty();

			//Opción predeterminada
			$("#almacen1").append($('<option>', {
                value: '',
                text: 'Seleccionar Origen'
            }));

            // Recorrer los datos y agregar opciones al select
            $.each(data, function (i, item) {
                $("#almacen1").append($('<option>', {
                    value: item.idalmacen,
                    text: item.nombre
                }));
            });
        },
        error: function (e) {
            console.log(e.responseText);
        }
    });
}

// Mostrar articulos de origen cuando se seleccione un almacen de origen
$('#almacen1').change(function () {
    // Obtener el valor seleccionado (idalmacen)
    var idAlmacenSeleccionado = $(this).val();
	cargarAlmacenesEnSelect2(idAlmacenSeleccionado); //LISTAMOS ALMACEN DE DESTINO
    
    // Verificar si se ha seleccionado un almacén
    if (idAlmacenSeleccionado !== '') {
        $.ajax({
            url: '../ajax/transferencia_stock.php?op=articulos_x_almacen', 
            type: 'POST', 
            data: { idalmacen: idAlmacenSeleccionado },
            dataType: 'json',
            success: function (data) {
                // Limpiar el segundo select antes de agregar nuevas opciones
                $("#articulos1").empty();

                // Agregar la opción predeterminada "Seleccionar Artículo"
                $("#articulos1").append($('<option>', {
                    value: '',
                    text: 'Seleccionar Artículo'
                }));

                    // Recorrer los datos y agregar opciones al segundo select
                    $.each(data.data.articulo, function (i, item) {
                        $("#articulos1").append($('<option>', {
                            value: item.idarticulo,
                            text: item.nombre
                        }));
                        
                    });
            },
            error: function (e) {
                console.log(e.responseText);
            }
        });
    } else {
        // Si no se selecciona un almacén, limpiar el select de articulos
        $("#articulos1").empty();
    }
});

// Función para listar ALMACEN de DESTINO --------> EXCLUYENDO EL ALMACEN DE ORIGEN SELECCIONADO
function cargarAlmacenesEnSelect2(excluirIdAlmacen) {
    $.ajax({
        url: '../ajax/transferencia_stock.php?op=mostrar_almacenes',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            $("#almacen2").empty();
			$("#articulos2").empty();
            $("#almacen2").append($('<option>', {
                value: '',
                text: 'Seleccionar Almacén Destino'
            }));

            // Recorrer los datos y agregar opciones al select
            $.each(data, function (i, item) {
                // Excluir el almacén seleccionado en almacen1
                if (item.idalmacen !== excluirIdAlmacen) {
                    $("#almacen2").append($('<option>', {
                        value: item.idalmacen,
                        text: item.nombre
                    }));
                }
            });
        },
        error: function (e) {
            console.log(e.responseText);
        }
    });
}

// Función para listar los articulos de Almacen Destino
function cargarArticulosEnSelect2(idAlmacen) {
    if (idAlmacen !== '') {
        $.ajax({
            url: '../ajax/transferencia_stock.php?op=articulos_x_almacen',
            type: 'POST',
            data: { idalmacen: idAlmacen },
            dataType: 'json',
            success: function (data) {
                $("#articulos2").empty();
                $("#articulos2").append($('<option>', {
                    value: '',
                    text: 'Seleccionar Artículo Destino'
                }));

                // Recorrer los datos y agregar opciones al select
                $.each(data.data.articulo, function (i, item) {
                    $("#articulos2").append($('<option>', {
                        value: item.idarticulo,
                        text: item.nombre
                    }));
                });
            },
            error: function (e) {
                console.log(e.responseText);
            }
        });
    } else {
        // Si no se selecciona un almacén, limpiar el select de artículos
        $("#articulos2").empty();
    }
}

// Mostrar articulos de destino cuando se seleccione un almacen de destino
$('#almacen2').change(function () {
    var idAlmacenSeleccionado = $(this).val();
    cargarArticulosEnSelect2(idAlmacenSeleccionado);
});

// Mostrar stock cuando se seleccione un articulo de origen
$('#articulos1').change(function () {
    actualizarStock();
});


// Función para ver stock del Articulo Origen
function actualizarStock() {
    var idarticuloSelect = $("#articulos1").val();
    $.ajax({
        url: '../ajax/transferencia_stock.php?op=ver_stock', // Ruta al archivo PHP que maneja la lógica de obtener el stock
        type: 'POST',
        data: { idarticulo: idarticuloSelect },
        dataType: 'json',
        success: function (data) {
            // Actualizar la etiqueta <p> con el stock obtenido
            $("#stock1").text(data.data.cantidad.stock);
        },
        error: function (e) {
            console.log(e.responseText);
        }
    });
}


function guardar_y_editar_stock(e){
    var formData = new FormData($("#formulario")[0]);
    $.ajax({
        url: "../ajax/transferencia_stock.php?op=guardar_stock_transferido",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (datos) {
                Swal.fire({  icon: 'success',  title: 'Guardado exitoso',   html: 'El registro se guardo correctamente.', });
                limpiar();
        },
        error: function () {
            Swal.fire({ icon: 'error', title: 'Error al guardar', html: 'Ha ocurrido un error al guardar los datos', });
          }
      });
}
  




init();
