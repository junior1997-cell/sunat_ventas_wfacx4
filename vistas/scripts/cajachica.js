var baseURL = window.location.protocol + '//' + window.location.host;

// Verificar si pathname contiene '/vistas/' y eliminarlo.
var path = window.location.pathname;
if (path.includes("/vistas/")) {
  path = path.replace("/vistas/", "/");
}

// Asegurarnos de que el path termine en "/ajax/"
if (!path.endsWith("/ajax/")) {
  var lastSlashIndex = path.lastIndexOf("/");
  path = path.substring(0, lastSlashIndex) + "/ajax/";
}

// Construir urlconsumo
var urlconsumo = new URL(path, baseURL);

//Función que se ejecuta al inicio
function init() {
  estado_caja(localStorage.getItem('estadocaja'));

  mostrarTotaldeVentas();
  mostrarTotalencaja();
  mostrarTotaldeIngresos();
  mostrarTotaldeEgresos();
  mostrarSaldoINI();
  listartblVantas();

  $("#formulario").on("submit", function (e) {
    guardaryeditar(e);
  })

}

function mostrarTotaldeVentas() {  
  $(document).ready(function () {
    $.ajax({
      url: urlconsumo + "cajachica.php?action=TotalVentas&op=",
      type: 'GET',
      dataType: 'json',
      success: function (data) {
        $('#total_ventas').html('S/ ' + data);
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.log('Error:', textStatus, errorThrown);
      }
    });
  });
}

function mostrarTotalencaja() {
  $(document).ready(function () {
    $.ajax({
      url: urlconsumo + "cajachica.php?action=TotalCaja&op=",
      type: 'GET',
      dataType: 'json',
      success: function (data) {
        const total_caja = data;
        $('#total_caja').html('S/ ' + total_caja);
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.log('Error:', textStatus, errorThrown);
      }
    });
  });
}

function mostrarTotaldeIngresos() {
  $(document).ready(function () {
    $.ajax({
      url: urlconsumo + "cajachica.php?action=TotalIngresos&op=",
      type: 'GET',
      dataType: 'json',
      success: function (data) {
        const totalingreso = data;
        $('#total_ingreso').html('S/ ' + totalingreso);

      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.log('Error:', textStatus, errorThrown);
      }
    });
  });
}

function mostrarTotaldeEgresos() {
  $(document).ready(function () {
    $.ajax({
      url: urlconsumo + "cajachica.php?action=TotalGastos&op=",
      type: 'GET',
      dataType: 'json',
      success: function (data) {
        const totalegreso = data;
        $('#total_gasto').html('S/ ' + totalegreso);
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.log('Error:', textStatus, errorThrown);
      }
    });
  });
}

function mostrarSaldoINI() {
  $(document).ready(function () {
    $.ajax({
      url: urlconsumo + "cajachica.php?action=SaldoInicial&op=",
      type: 'GET',
      dataType: 'json',
      success: function (data) {
        $('#total_saldoini').html('S/ ' + data);
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.log('Error:', textStatus, errorThrown);
      }
    });
  });
}

//TBL INGRESOS Y EGRESOS
function listar(tipo) {
  console.log(tipo);
  tabla = $(`#tblistado${tipo}`).dataTable({
    "aProcessing": true,
    "aServerSide": true,
    "dom": 'Bfrtip',
    "buttons": [],
    "ajax": {
      "url": `../ajax/cajachica.php?action=tblInsumos&tipo=${tipo}&op=`,
      "type": "get",
      "dataType": "json",
      "error": function (e) {
        console.log(e);
      }
    },
    "bDestroy": true,
    "iDisplayLength": 15,
    "order": [[0, ""]],
    "columns": [
      { "data": "fecharegistro" },
      { "data": "descripcion" },
      { "data": "descripcionc" },
      { "data": "total" },
      { "data": "acredor" }
    ]

  }).DataTable();
}

function listartblVantas() {
  tblistadoVentas = $(`#tblistadoVentas`).dataTable({
    "aProcessing": true,
    "aServerSide": true,
    "dom": 'Bfrtip',
    "buttons": [],
    "ajax": {
      "url": `../ajax/cajachica.php?action=comprobantes&op=`,
      "type": "get",
      "dataType": "json",
      "error": function (e) {
        console.log(e);
      }
    },
    "bDestroy": true,
    "iDisplayLength": 15,
    "order": [[0, ""]],
    "columns": [
      { "data": "fecha_emision_01" },
      { "data": "nun_doc" },
      { "data": "rucCliente" },
      { "data": "RazonSocial" },
      { "data": "importe_total" },
      { "data": "descripcion_ley" },
      { "data": "tipoDoc" },

    ]
  }).DataTable();
}

function guardaryeditar(e) {
  e.preventDefault();

  $("#btnGuardarSaldoInicial").prop("disabled", true);
  var formData = new FormData($("#formulario")[0]);

  $.ajax({
    url: "../ajax/cajachica.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {

      // Almacenar una variable en localStorage
      localStorage.setItem('estadocaja', '1');

      // Obtener el valor almacenado en localStorage
      estado_caja(localStorage.getItem('estadocaja'));

      $("#btnGuardarSaldoInicial").prop("disabled", false);

      if (datos === "Ya existe un saldo inicial registrado para hoy, no se puede registrar otro.") {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: datos
        });
      } else {
        Swal.fire({
          icon: 'success',
          title: 'Guardado exitoso',
          text: datos,
          showConfirmButton: false,
          timer: 1500
        });

        $('#agregarsaldoInicial').modal('hide'); // Ocultar el modal


        // Obtener valor del saldo inicial guardado
        var saldoInicial = $('#saldo_inicial').val();

        // Actualizar contenido del h5 con el saldo inicial
        $('#total_saldoini').html('S/ ' + saldoInicial);

        mostrarSaldoINI(); // Ejecutar la función mostrarSaldoINI para actualizar el saldo inicial

      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log('Error:', textStatus, errorThrown);
    }
  });
}

function cerrarCaja() {

  Swal.fire({
    title: "¿Está Seguro de  Cerrar Caja?",
    text: "",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, Cerrar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/cajachica.php?op=cerrarcaja", {}, function (e) {

        Swal.fire({
          icon: 'success',
          title: 'Caja cerrada',
          text: 'Se ha cerrado la caja con éxito',
          showConfirmButton: false,
          timer: 1500
        });

        resetearTotales(); // Restablecer los valores de los totales

        // Almacenar una variable en localStorage
        localStorage.setItem('estadocaja', '0');

        mostrarTotaldeVentas();
        mostrarTotalencaja();
        mostrarTotaldeIngresos();
        mostrarTotaldeEgresos();
        mostrarSaldoINI();
        listartblVantas();

        // Obtener el valor almacenado en localStorage
        estado_caja(localStorage.getItem('estadocaja'));


      });
    }
  });

}

function resetearTotales() {

  $('#total_ingreso').text('0');
  $('#total_gasto').text('0');
  $('#total_saldoini').text('0');
  $('#total-ventas').text('0');

}

function estado_caja(estado) {
  if (estado == '1') {
    $('#cerrarCajaBtn').show();
    $('#abrCajaBtn').hide();

  } else {
    $('#cerrarCajaBtn').hide();
    $('#abrCajaBtn').show();
  }
}

init();