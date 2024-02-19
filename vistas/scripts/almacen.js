var tabla;
var modoDemo = false;
//Función que se ejecuta al inicio
function init() {
  mostrarform(false);
  listar();
  $("#btnGuardar").on("click", function (e) { $("#submit-form-almacen").submit(); });

}

//Función limpiar
function limpiar() {
  $("#nombrea").val("");
  $("#direccion").val("");
  $("#idalmacen").val("");
  $("#btnGuardar").html('Guardar Cambios').removeClass('disabled send-data');

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

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
  tabla = $('#tbllistado').dataTable({
    responsive: true,
    aProcessing: true,//Activamos el procesamiento del datatables
    aServerSide: true,//Paginación y filtrado realizados por el servidor
    dom: "Bfrtip",
    buttons: [],
    ajax: {
      url: '../ajax/almacen.php?op=listar',
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText); ver_errores(e);
      }
    },
    createdRow: function (row, data, ixdex) { },
    language: {
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    bDestroy: true,
    iDisplayLength: 10,//Paginación
    order: [[0, "desc"]],//Ordenar (columna,orden)
    columnDefs: [],
  }).DataTable();
}
//Función para guardar o editar

function guardaryeditar(e) {
  var formData = new FormData($("#formulario")[0]);
  $.ajax({
    url: "../ajax/almacen.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (e) {
      try {
        e = JSON.parse(e);
        if (e.status == 'registrado') {
          sw_success("Excelente", "Almacen Registrado", 3000);
          listar();
          $('#agregarsucursal').modal('hide');
          
        } else if (e.status == 'modificado') {
          sw_success("Excelente", "Almacen Actualizado", 3000);
          listar();
          $('#agregarsucursal').modal('hide');
        } 
        
        else { ver_errores(e); }
      } catch (err) { console.log("Error: ", err.message); toastr.error('<h5 class="font-size-16px">Error temporal!!</h5> puede intentalo mas tarde, o comuniquese con <i><a href="tel:+51921305769" >921-305-769</a></i> ─ <i><a href="tel:+51921487276" >921-487-276</a></i>'); }

    },
    error: function (jqXhr) { ver_errores(jqXhr); },
  });

}


function mostrar(idalmacen) {
  limpiar();
  $('#agregarsucursal').modal('show');
  $.post("../ajax/almacen.php?op=mostrar", { idalmacen: idalmacen }, function (e, status) {
    e = JSON.parse(e); console.log(e);

    if (e.status == true) {
      mostrarform(true);
      $("#nombrea").val(e.data.nombre);
      $("#direccion").val(e.data.direccion);
      $("#idalmacen").val(e.data.idalmacen);
      document.getElementById("btnGuardar").innerHTML = "Actualizar";
    } else {
      ver_errores(e);
    }

  })
}

//Función para desactivar registros
function desactivar(idalmacen) {
  if (modoDemo) {
    Swal.fire({
      icon: 'warning',
      title: 'Modo demo',
      text: 'No puedes editar o guardar en modo demo',
    });
    return;
  }
  Swal.fire({
    title: "¿Estás seguro de desactivar el almacén?",
    text: "",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí, desactivar",
    cancelButtonText: "Cancelar"
  }).then((result) => {
    if (result.value) {
      $.post("../ajax/almacen.php?op=desactivar", { idalmacen: idalmacen }, function (e) {
        Swal.fire({
          title: "Mensaje",
          text: e,
          icon: "success",
          timer: 2000,
          showConfirmButton: false
        });
        tabla.ajax.reload();
      });
    }
  });


}

//Función para activar registros
function activar(idalmacen) {
  Swal.fire({
    title: "¿Estás seguro de activar la almacén?",
    text: "",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí, activar",
    cancelButtonText: "Cancelar"
  }).then((result) => {
    if (result.value) {
      $.post("../ajax/almacen.php?op=activar", { idalmacen: idalmacen }, function (e) {
        Swal.fire({
          title: "Mensaje",
          text: e,
          icon: "success",
          timer: 2000,
          showConfirmButton: false
        });
        tabla.ajax.reload();
      });
    }
  });
}



function mayus(e) {
  e.value = e.value.toUpperCase();
}

init();

// .....::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M  :::::::::::::::::::::::::::::::::::::::..

$(function () {

  $("#formulario").validate({
    rules: {
      nombrea: { required: true, minlength: 2, maxlength: 50 },
      direccion: { required: true, minlength: 2, maxlength: 50 }
    },
    messages: {
      nombrea: { required: "Campo requerido", minlength: "Minimo {0} caracteres", maxlength: "Maximo {0} caracteres" },
      direccion: { required: "Campo requerido", minlength: "Minimo {0} caracteres", maxlength: "Maximo {0} caracteres" }
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
      guardaryeditar(e);
    },
  });

});
