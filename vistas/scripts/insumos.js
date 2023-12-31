var tabla;
var Valuecaja = '';
//Función que se ejecuta al inicio
function init() {

  $("#formulario").on("submit", function (e) { guardaryeditar(e); })

  $("#formnewcate").on("submit", function (e) { guardaryeditarCategoria(e); })

  $("#formularioutilidad").on("submit", function (e) { guardarutilidad(e); })

  //$("#fecharegistro").prop("disabled",false);
  var now = new Date();
  var day = ("0" + now.getDate()).slice(-2);
  var month = ("0" + (now.getMonth() + 1)).slice(-2);
  var today = now.getFullYear() + "-" + (month) + "-" + (day);
  $('#fecharegistro').val(today);

  $('#fecha1').val(today);
  $('#fecha2').val(today);
  $('#fechagasto').val(today);
  $('#fechaingreso').val(today);


  $.post("../ajax/insumos.php?op=selectcate", function (r) { $("#categoriai").html(r); });
  $.post("../ajax/insumos.php?op=select_cajas", function (r) { $("#select_cajas").html(r); listar(); mostrarfechas(); });

  limpiar();

  listarutilidad();

  $("#select_cajas").select2({ theme: "bootstrap4", placeholder: "Seleccione", allowClear: true, });

}

function mostrarfechas() {
  if ($('#select_cajas').val() == null || $('#select_cajas').val() == '' || $('#select_cajas').val() == 'TODOS') {
    $(".fachas_Caja").html(``);
  } else {
    var fa = $('#select_cajas').select2('data')[0].element.attributes.fa.value;
    var fc = $('#select_cajas').select2('data')[0].element.attributes.fc.value;
    $(".fachas_Caja").html(`${fa} - ${fc}`);
  }
}


//Función limpiar
function limpiar() {
  $("#descripcion").val("");
  $("#monto").val("");
  $("#fecharegistro").val("");
  $("#numDOCIDE").val("");
  $("#fecharegistro").val("");

  $("#tipodato").val("null").trigger("change");
  $("#documnIDE").val("null").trigger("change");

  $("#categoriai").val("null").trigger("change");
  $("#acredor").val("Ninguno").trigger("change");

  $("#glosa").val("null").trigger("change");

  setTimeout(function () {
    document.addEventListener('DOMContentLoaded', function () {
      document.getElementById('descripcion').focus();
    });
  }, 100);

}


function focusTest(el) {
  el.select();
}

function foco0() {

  document.getElementById('descripcion').focus();

}

function foco1(e) {
  if (e.keyCode === 13 && !e.shiftKey) {
    document.getElementById('monto').focus();
  }
}

function foco2(e) {
  if (e.keyCode === 13 && !e.shiftKey) {
    document.getElementById('btnGuardar').focus();
  }
}

//Función cancelarform
function cancelarform() {
  limpiar();

}

//Función Listar
function listar() {

  $(document).ready(function () {

    Valuecaja = $('#select_cajas').val();

    tabla = $('#tbllistado').dataTable(
      {
        lengthMenu: [[-1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200,]],//mostramos el menú de registros a revisar
        "aProcessing": true,//Activamos el procesamiento del datatables
        "aServerSide": true,//Paginación y filtrado realizados por el servidor
        dom: "<'row'<'col-md-3'B><'col-md-3 float-left'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",//Definimos los elementos del control de tabla
        buttons: [
          { text: '<i class="fa-solid fa-arrows-rotate" data-toggle="tooltip" data-original-title="Recargar"></i> ', className: "btn bg-gradient-info m-r-5px", action: function (e, dt, node, config) { if (tabla_articulo) { tabla_articulo.ajax.reload(null, false); } } },
          { extend: 'copyHtml5', exportOptions: { columns: [1, 2, 3, 4, 5, 6], }, text: `<i class="fas fa-copy" data-toggle="tooltip" data-original-title="Copiar"></i>`, className: "btn bg-gradient-gray m-r-5px", footer: true, },
          { extend: 'excelHtml5', exportOptions: { columns: [1, 2, 3, 4, 5, 6], }, text: `<i class="far fa-file-excel fa-lg" data-toggle="tooltip" data-original-title="Excel"></i>`, className: "btn bg-gradient-success m-r-5px", footer: true, },
          { extend: 'pdfHtml5', exportOptions: { columns: [1, 2, 3, 4, 5, 6], }, text: `<i class="far fa-file-pdf fa-lg" data-toggle="tooltip" data-original-title="PDF"></i>`, className: "btn bg-gradient-danger m-r-5px", footer: false, orientation: 'landscape', pageSize: 'LEGAL', },
          { extend: "colvis", text: `<i class="fas fa-outdent"></i>`, className: "btn bg-gradient-gray", exportOptions: { columns: "th:not(:last-child)", }, },
        ],
        "ajax":
        {
          url: `../ajax/insumos.php?op=listar&idcaja=${Valuecaja}`,
          type: "get",
          dataType: "json",
          error: function (e) {
            console.log(e.responseText);
          }
        },
        language: {
          lengthMenu: "Mostrar: _MENU_ registros",
          buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
          sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
        },
        "bDestroy": true,
        "iDisplayLength": 5,//Paginación
        "order": [[0, "desc"]]//Ordenar (columna,orden)
      }).DataTable();

  });

}

function calcularutilidad() {
  fecha1 = $("#fecha1").val();
  fecha2 = $("#fecha2").val();
  tabla = $('#tbllistadouti').dataTable(
    {
      "aProcessing": true,//Activamos el procesamiento del datatables
      "aServerSide": true,//Paginación y filtrado realizados por el servidor
      dom: 'Bfrtip',//Definimos los elementos del control de tabla
      buttons: [


      ],
      "ajax":
      {
        url: '../ajax/insumos.php?op=calcularutilidad&f1=' + fecha1 + '&f2=' + fecha2,
        type: "get",
        dataType: "json",
        error: function (e) {
          //console.log(e.responseText);

        }
      },
      "bDestroy": true,
      "iDisplayLength": 5,//Paginación
      "order": [[0, "desc"]]//Ordenar (columna,orden)
    }).DataTable();
  setTimeout(function () {
    listarutilidad();
  }, 500);
}

function recalcularutilidad(idutilidad) {

  tabla = $('#tbllistadouti').dataTable(
    {
      "aProcessing": true,//Activamos el procesamiento del datatables
      "aServerSide": true,//Paginación y filtrado realizados por el servidor
      dom: 'Bfrtip',//Definimos los elementos del control de tabla
      buttons: [


      ],
      "ajax":
      {
        url: '../ajax/insumos.php?op=recalcularutilidad&iduti=' + idutilidad,
        type: "get",
        dataType: "json",
        error: function (e) {
          //console.log(e.responseText);
        }
      },
      "bDestroy": true,
      "iDisplayLength": 5,//Paginación
      "order": [[0, "desc"]]//Ordenar (columna,orden)
    }).DataTable();

  setTimeout(function () {
    listarutilidad();
  }, 500);

}

function listarutilidad() {
  tabla = $('#tbllistadouti').dataTable(
    {
      "aProcessing": true,//Activamos el procesamiento del datatables
      "aServerSide": true,//Paginación y filtrado realizados por el servidor
      dom: 'Bfrtip',//Definimos los elementos del control de tabla
      buttons: [

      ],
      "ajax":
      {
        url: '../ajax/insumos.php?op=listarutilidad',
        type: "get",
        dataType: "json",
        error: function (e) {
          console.log(e.responseText);
        }
      },
      "bDestroy": true,
      "iDisplayLength": 5,//Paginación
      "order": [[0, "desc"]]//Ordenar (columna,orden)
    }).DataTable();
}

// - - - - - - - - -GUARDAR INSUMO---------------
function guardaryeditar(e) {
  e.preventDefault(); //No se activará la acción predeterminada del evento

  var formData = new FormData($("#formulario")[0]);

  $.ajax({
    url: "../ajax/insumos.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {

      if (datos == 'caja_cerrada') {

        sw_warning('Caja cerrada', '<b>Aperturar una Caja</b> antes de seguir con tus operaciones', 5000);

      } else {

        sw_success('Guardado', datos, 5000);

        document.getElementById("mensaje").style.visibility = "hidden";
        listar(); limpiar();
        $("#agregarmaspagos").modal('hide');

      }

    }

  });

}

// - - - - - - - - -GUARDAR CATEGORIA-------------
function guardaryeditarCategoria(e) {
  e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#formnewcate")[0]);
  $.ajax({
    url: "../ajax/insumos.php?op=guardaryeditarcate",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (datos) {
      Swal.fire({
        title: 'Éxito',
        text: datos,
        icon: 'success',
        showConfirmButton: false,
        timer: 1500
      }).then((result) => {
        if (result.isConfirmed) {
          // Cargar los datos en el primer modal
          var categoria = $("#descripcioncate").val();
          $("#categoriai").append(new Option(categoria, categoria));

          // Mostrar el primer modal
          $("#agregarmaspagos").modal('show');
        }
      });

      actcategoria();
    },

    error: function (datos) {
      Swal.fire({
        title: 'Error',
        text: 'Ha ocurrido un error al guardar o editar la categoría.',
        icon: 'error',
        showConfirmButton: false,
        timer: 1500
      });
    }
  });
  $("#agregarmaspagos").modal('show');
  $("#ModalNcategoria").modal('hide');
}

function actcategoria() {
  $.post("../ajax/insumos.php?op=selectcate", function (r) { $("#categoriai").html(r); });
}


function stopRKey(evt) {
  var evt = (evt) ? evt : ((event) ? event : null);
  var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
  if ((evt.keyCode == 13) && (node.type == "text")) { return false; }
}



//------------validar caja abierta------------
function validar_caja() {
  $.post("../ajax/insumos.php?op=Estado_Caja", {}, function (data, status) {
    data = JSON.parse(data);
    console.log(data);

  })
}



//BLOQUEA ENTER 
document.onkeypress = stopRKey;

function mostrar_editar(id_insumo) {
  limpiar();

  $.post("../ajax/insumos.php?op=mostrar", { id_insumo: id_insumo }, function (data, status) {
    data = JSON.parse(data);

    $("#idinsumo").val(data.idinsumo);
    $("#fecharegistro").val(data.fecharegistro);
    $("#tipodato").val(data.tipodato);
    $("#categoriai").val(data.idcategoriai);
    $("#documnIDE").val(data.documnIDE);
    $("#numDOCIDE").val(data.numDOCIDE);
    $("#acredor").val(data.acredor);

    if (data.tipodato == 'ingreso') {
      $("#monto").val(data.ingreso);
    } else {
      $("#monto").val(data.gasto);
    }

    $("#descripcion").val(data.descripcion);

    $("#agregarmaspagos").modal('show');
  })
}


function mayus(e) {
  e.value = e.value.toUpperCase();
}


//Función para desactivar registros
function eliminar(idinsumo) {
  Swal.fire({
    title: '¿Está seguro de eliminar el insumo?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sí',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/insumos.php?op=eliminar", { idinsumo: idinsumo }, function (e) {
        Swal.fire({
          title: e,
          icon: 'success',
          showConfirmButton: false,
          timer: 1500
        });
        tabla.ajax.reload();
      });
    }
  });
  listar();
}


function eliminarutilidad(idutilidad) {
  Swal.fire({
    title: '¿Está Seguro de eliminar?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sí',
    cancelButtonText: 'No'
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/insumos.php?op=eliminarutilidad", { idutilidad: idutilidad }, function (e) {
        Swal.fire(
          'Eliminado!',
          e,
          'success'
        )
        tabla.ajax.reload();
      });
    }
  })
  listarutilidad();
}

function aprobarutilidad(idutilidad) {
  $.post("../ajax/insumos.php?op=aprobarutilidad", { idutilidad: idutilidad }, function (e) {
    tabla.ajax.reload();
  });
  listarutilidad();
}

function reporteutilidad(idutilidad) {
  var rutacarpeta = '../reportes/reportegastosvsingresossemanal.php?id=' + idutilidad;
  $("#modalCom").attr('src', rutacarpeta);
  $("#modalPreview").modal("show");
}

function cargarEmpleados() {

  var baseURL = window.location.protocol + '//' + window.location.host;

  // Verificar si pathname contiene '/vistas/' y eliminarlo.
  var path = window.location.pathname;
  if (path.includes("/vistas/")) {
    path = path.replace("/vistas/", "/");
  }

  var ajaxURL = new URL("ajax/sueldoBoleta.php?action=listar2&op=", baseURL + path);

  $.ajax({
    url: ajaxURL.href,
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      llenarSelect(data.aaData);
    },
    error: function (xhr, status, error) {
      console.error('Error al cargar los tipos de documento de identidad');
      console.error(error);
    }
  });
}

function llenarSelect(data) {
  var select = $('#acredor');
  select.empty();
  select.append($('<option>', {
    value: '',
    text: 'Seleccionar Empleado'
  }));
  $.each(data, function (index, value) {
    var nombreCompleto = value.nombresE;
    if (value.apellidosE) {
      nombreCompleto += ' ' + value.apellidosE;
    }
    select.append($('<option>', {
      value: value.nombreCompleto,
      text: nombreCompleto
    }));
  });
}



$(document).ready(function () {
  cargarEmpleados();
});




init();