var tabla;
var modoDemo = false;
var tabla_transferencia;
//Función que se ejecuta al inicio
function init() {
  listar_transferencia();
  $idempresa = $("#idempresa").val(); 
  $stock_max = $("#stock").val();
  $.post("../ajax/transferencia_stock.php?op=selectAlmacen1&idempresa=" + $idempresa, function (r) { $("#idalmacen1").html(r); });

}

//Función limpiar
function limpiar() {
  $("#idalmacen1").val("");
  $("#idarticulos1").empty();
  $("#cantidad").val("");
  $("#idalmacen2").empty();
  $("#idarticulos2").empty();
  $("#stock").empty();
  //console.log("todo okey");
}


// Tabla Lista de Artículos
function listar_transferencia() {
  tabla_transferencia = $('#tbllistado').dataTable({
    lengthMenu: [[-1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200,]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: "<'row'<'col-md-3'B><'col-md-3 float-left'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",//Definimos los elementos del control de tabla
    buttons: [
      { text: '<i class="fa-solid fa-arrows-rotate" data-toggle="tooltip" data-original-title="Recargar"></i> ', className: "btn bg-gradient-info m-r-5px", action: function (e, dt, node, config) { if (tabla_transferencia) { tabla_transferencia.ajax.reload(null, false); } } },
      { extend: 'copyHtml5', exportOptions: { columns: [1, 2, 3, 4, 5, 6], }, text: `<i class="fas fa-copy" data-toggle="tooltip" data-original-title="Copiar"></i>`, className: "btn bg-gradient-gray m-r-5px", footer: true, },
      { extend: 'excelHtml5', exportOptions: { columns: [1, 2, 3, 4, 5, 6], }, text: `<i class="far fa-file-excel fa-lg" data-toggle="tooltip" data-original-title="Excel"></i>`, className: "btn bg-gradient-success m-r-5px", footer: true, },
      { extend: 'pdfHtml5', exportOptions: { columns: [1, 2, 3, 4, 5, 6], }, text: `<i class="far fa-file-pdf fa-lg" data-toggle="tooltip" data-original-title="PDF"></i>`, className: "btn bg-gradient-danger m-r-5px", footer: false, orientation: 'landscape', pageSize: 'LEGAL', },
      { extend: "colvis", text: `<i class="fas fa-outdent"></i>`, className: "btn bg-gradient-gray", exportOptions: { columns: "th:not(:last-child)", }, },
    ],
    "ajax": {
      url: '../ajax/transferencia_stock.php?op=mostrar_tranferencia',
      type: "get",
      dataType: "json",
      error: function (e) { console.log(e.responseText); }
    },
    "bDestroy": true,
    "iDisplayLength": 10,//Paginación
    "order": [[0, "asc"]]//Ordenar (columna,orden)
  }).DataTable();
}


// Mostrar Almacen de destino
function selectAlmacen2() {
    var idalmacen1 = $('#idalmacen1').val();

    $.ajax({
        type: 'POST',
        url: '../ajax/transferencia_stock.php?op=selectAlmacen2',
        data: { idalmacen1: idalmacen1 },
        dataType: 'html',
        success: function(r) {
            $("#idalmacen2").html(r);
        },
        error: function(xhr, status, error) {
            // Manejar errores si es necesario
            console.error(xhr.responseText);
        }
    });
    selectArticulos1();
    $("#idarticulos2").empty();
    $("#stock").empty();
}


// Mostrar Articulo de Origen
function selectArticulos1() {
    var idalmacen1 = $('#idalmacen1').val();

    $.ajax({
        type: 'POST',
        url: '../ajax/transferencia_stock.php?op=selectArticulos1',
        data: { idalmacen1: idalmacen1 },
        dataType: 'html',
        success: function(r) {
            $("#idarticulos1").html(r);
        },
        error: function(xhr, status, error) {
            // Manejar errores si es necesario
            console.error(xhr.responseText);
        }
    });
    
}


function selectArticulos2() {
    var idalmacen2 = $('#idalmacen2').val();

    $.ajax({
        type: 'POST',
        url: '../ajax/transferencia_stock.php?op=selectArticulos2',
        data: { idalmacen2: idalmacen2 },
        dataType: 'html',
        success: function(r) {
            $("#idarticulos2").html(r);
        },
        error: function(xhr, status, error) {
            // Manejar errores si es necesario
            console.error(xhr.responseText);
        }
    });
    
}


function verStock() {
    var idarticulo1 = $('#idarticulos1').val();

    $.ajax({
        type: 'POST',
        url: '../ajax/transferencia_stock.php?op=verStock',
        data: { idarticulo1: idarticulo1 },
        dataType: 'json',
        success: function(response) {
            $("#stock").html(response.stock);
        },
        error: function(xhr, status, error) {
            // Manejar errores si es necesario
            console.error(xhr.responseText);
        }
    });
}


function guardar_transferencia(){
  var formData = new FormData($("#form-transferencia")[0]);
  $.ajax({
    type: 'POST',
    url: '../ajax/transferencia_stock.php?op=guardar_transferencia',
    data: formData,
    dataType: 'json',
    contentType: false,
    processData: false,
    success: function (response) {
        Swal.fire({  icon: 'success',  title: 'Guardado exitoso',   html: 'El registro se guardo correctamente.', });
        console.log(response);
        limpiar();
    },
    error: function (xhr, status, error) {
        // Manejar errores si es necesario
        console.error(xhr.responseText);
    }
});
}


// ...::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M  :::::::::::::::::::::::::::::::::::::::..

$(function () {

  $("#form-transferencia").validate({
    ignore: "",
    rules: { 
      idalmacen1:    { required: true, },
      idalmacen2:    { required: true, },
      idarticulos1:  { required: true, },
      idarticulos2:  { required: true, },
      cantidad:   {
          required: true,
          min: 2,
          max: function() {
            var stock = parseInt($('#stock').text(), 10);
            return isNaN(stock) ? 100 : stock;;
          }
      },
      
    },
    messages: {
      idalmacen1:    { required: "Campo requerido", },
      idalmacen2:    { required: "Campo requerido", },
      idarticulos1:  { required: "Campo requerido", },
      idarticulos2:  { required: "Campo requerido", },
      cantidad:   { required: "Campo requerido", minlength:"Minimo {0} caracteres", maxlength: "Máximo {0} caracteres" },
      
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

    submitHandler: function (form) {
        // Cuando el formulario es válido, ejecutar la función guardar_transferencia
        guardar_transferencia();
        return false; // Evitar que el formulario se envíe de forma convencional
    }
  });

});

function mayus(e) {
	e.value = e.value.toUpperCase();
}

init();