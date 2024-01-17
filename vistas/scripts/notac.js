var tabla_principal_dia; var tabla_principal; var tabla_comprobantes ;
var tipodocu;
var tipo;
$idempresa = $("#idempresa").val();
$iva = $("#iva").val();

//Función que se ejecuta al inicio
function init() {
  mostrarform(false);
  //listar();
  unotodos();
  limpiar();
  document.getElementById("chk1").checked = true;

  $("#formulario").on("submit", function (e) { guardaryeditarNcredito(e); });
  $("#formulario2").on("submit", function (e) { guardaryeditarNcredito(e); });

  $.post("../ajax/notacd.php?op=selectcatalogo9", function (r) { $("#codigo_nota").html(r); });
  $.post("../ajax/vendedorsitio.php?op=selectVendedorsitio&idempresa=" + $idempresa, function (r) { $("#vendedorsitio").html(r); });
  $.post("../ajax/factura.php?op=selectAlmacen", function (r) { $("#almacenlista").html(r); });

  cont = 0;
  tipo = '03';
  conNO = 1;
}

//Funcion para actualizar la pagina cada 20 segundos.

//Función mostrar formulario
function mostrarform(flag) {
  inicializar();
  limpiar();

  if (flag) {
    $("#listadoregistros").hide();
    $("#formularioregistros").show();
    $("#btnagregar").hide();
    $("#codtiponota").val("01");

    listarComprobante();
    listarArticulos();


    $("#btnGuardar").hide();
    $("#btnCancelar").show();
    $("#btnAgregarArt").show();
    $("#detalles").show();
    $("#totales").show();

    var nomcodtipo = $("#codigo_nota option:selected").text();
    $("#nomcodtipo").val(nomcodtipo);
  } else {
    $("#detallesnc").hide();
    $("#detalles").hide();
    $("#totales").hide();
    $("#listadoregistros").show();
    $("#formularioregistros").hide();
    $("#btnagregar").show();
    $("#btnGuardar").hide();
  }
}

function cambiotiponota() {

  var codtiponota = $("#codigo_nota option:selected").val();
  $("#codtiponota").val(codtiponota);

  var nomcodtipo = $("#codigo_nota option:selected").text();
  $("#nomcodtipo").val(nomcodtipo);

  switch (codtiponota) {
    case '01':
      $("#detallesnc").hide();
      $("#detalles").show();
      $("#totales").show();
      $("#pdescu").hide();
      $("#tiponotaC").val("1");
      $(".filas2").remove();
      cont = 0;
      //tipo=1;
      conNO = 1;
      document.getElementById('btnAgregarart').style.display = 'none';
    break;

    case '02':
      $("#detallesnc").hide();
      $("#detalles").show();
      $("#totales").show();
      $("#pdescu").hide();
      $("#tiponotaC").val("2");
      $(".filas2").remove();
      cont = 0;
      //tipo=1;
      conNO = 1;
      document.getElementById('btnAgregarart').style.display = 'none';
    break;

    case '03':
      $("#detallesnc").hide();
      $("#detalles").show();
      $("#totales").show();
      $("#pdescu").hide();
      $("#tiponotaC").val("3");
      $(".filas2").remove();
      cont = 0;
      //tipo=1;
      conNO = 1;
      document.getElementById('btnAgregarart').style.display = 'none';
    break;

    case '04':
      $("#detallesnc").hide();
      $("#detalles").show();
      $("#totales").show();
      $("#pdescu").show();
      $("#tiponotaC").val("4");
      document.getElementById('btnAgregarart').style.display = 'none';
    break;
    // case '05':
    //     $("#detallesnc").show();
    //     $("#detalles").show();
    //     $("#totales").show();
    //     $("#tiponotaC").val("5");
    //     document.getElementById('btnAgregarart').style.display = 'inline';
    // break;
    case '06':
      $("#detallesnc").hide();
      $("#detalles").show();
      $("#totales").show();
      $("#pdescu").hide();
      $("#tiponotaC").val("6");
      $(".filas2").remove();
      cont = 0;
      //tipo=1;
      conNO = 1;
      document.getElementById('btnAgregarart').style.display = 'none';
    break;

    case '07':
      if ($("input[type='radio'][name='tipo_doc_mod']:checked").val() == "04" || $("input[type='radio'][name='tipo_doc_mod']:checked").val() == "05") {
        alert("No se puede hacer para Facturas o boletas de servicio, solo para facturas y boletas de productos, \n utilice anulacion total.");
        $("#tipo_doc_mod").val() = "01";
        $("#codigo_nota").val("01");
      } else {
        $("#detallesnc").show();
        $("#detalles").show();
        $("#totales").show();
        $("#pdescu").hide();
        $("#tiponotaC").val("7");
        document.getElementById('btnAgregarart').style.display = 'inline';
      }
    break;
  }
}

function incremetarNum() {
  var serie = $("#serie option:selected").val();
  $.post("../ajax/notacd.php?op=autonumeracion&ser=" + serie + '&idempresa=' + $idempresa, function (r) {
    var n2 = pad(r, 0);
    $("#numero_nc").val(n2);
    var SerieReal = $("#serie option:selected").text();
    $("#SerieReal").val(SerieReal);
  });
}

function inicializar() {

  $.post("../ajax/notacd.php?op=selectSerie", function (r) {
    $("#serie").html(r);    
    incremetarNum(); // Llama a la función incremetarNum()  
  });
}

//Función para poner ceros antes del numero siguiente de la factura
function pad(n, length) {
  var n = n.toString();
  while (n.length < length)
    n = "0" + n;
  return n;
}

//Función limpiar
function limpiar() {
  $("#idnota").val("");
  $("#numero_comprobante").val("");
  $("#razon_social").val("");
  $("#domicilio_fiscal").val("");
  $("#idnumeracion").val("");
  $("#SerieReal").val("");
  $("#serie").val("");
  $("#numero_nc").val("");
  $("#desc_motivo").val("");
  $("#codtiponota").val("");
  $("#tipocomprobante").val("");
  $("#numero_comprobante").val("");
  $("#numero_documento_cliente").val("");
  $("#razon_social").val("");
  $("#domicilio_fiscal").val("");

  $("#subtotal").val("");
  $("#subtotal_factura").val("");
  $("#igv_").val("");
  $("#total_igv").val("");
  $("#total").val("");
  $("#total_final").val("");

  $(".filas").remove();
  $("#subtotal").html("0");
  $("#igv_").html("0");
  $("#total").html("0");

  $("#pdescuento").val("");
  $("#subtotaldesc").val("");
  $("#igvdescu").val("");
  $("#totaldescu").val("");

  document.getElementById('btnAgregarart').style.display = 'none';

  //Obtenemos la fecha actual
  $("#fecha_emision").prop("disabled", false);
  var now = new Date();
  var day = ("0" + now.getDate()).slice(-2);
  var month = ("0" + (now.getMonth() + 1)).slice(-2);
  var f = new Date();
  cad = f.getHours() + ":" + f.getMinutes() + ":" + f.getSeconds();
  //Para hora y minutos
  //var today = now.getFullYear()+"-"+(month)+"-"+(day)+" "+(cad) ;
  var today = now.getFullYear() + "-" + (month) + "-" + (day);
  $('#fecha_emision').val(today);

  cont = 0;
}

//Función cancelarform
function cancelarform() {
  limpiar();
  detalles = 0;
  mostrarform(false);
}

//Función Listar
function listar() {
  tabla_principal = $('#tbllistado').dataTable( {
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom:"<'row'<'col-md-3'B><'col-md-3 float-left'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",//Definimos los elementos del control de tabla
    buttons: [
      { text: '<i class="fa-solid fa-arrows-rotate" data-toggle="tooltip" data-original-title="Recargar"></i> ', className: "btn bg-gradient-info m-r-5px", action: function ( e, dt, node, config ) { if (tabla_principal) { tabla_principal.ajax.reload(null, false); } } },
      { extend: 'copyHtml5', exportOptions: { columns: [1,2,3,4,5,6], }, text: `<i class="fas fa-copy" data-toggle="tooltip" data-original-title="Copiar"></i>`, className: "btn bg-gradient-gray m-r-5px", footer: true,  }, 
      { extend: 'excelHtml5', exportOptions: { columns: [1,2,3,4,5,6], }, title: 'Lista de articulos', text: `<i class="far fa-file-excel fa-lg" data-toggle="tooltip" data-original-title="Excel"></i>`, className: "btn bg-gradient-success m-r-5px", footer: true,  }, 
      { extend: 'pdfHtml5', exportOptions: { columns: [1,2,3,4,5,6], }, title: 'Lista de articulos', text: `<i class="far fa-file-pdf fa-lg" data-toggle="tooltip" data-original-title="PDF"></i>`, className: "btn bg-gradient-danger m-r-5px", footer: false, orientation: 'landscape', pageSize: 'LEGAL',  },
      { extend: "colvis", text: `<i class="fas fa-outdent"></i>`, className: "btn bg-gradient-gray", exportOptions: { columns: "th:not(:last-child)", }, },
    ],
    "ajax":  {
      url: '../ajax/notacd.php?op=listarNC&idempresa=' + $idempresa,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText);
      }
    },
    "bDestroy": true,
    "iDisplayLength": 10//,//Paginación
    //"order": [[ 2, "asc" ]]//Ordenar (columna,orden)
  }).DataTable();

  // setInterval( function () {
  // tabla.ajax.reload(null, false);
  // }, 10000 );
}

let onOff = false;
function unotodos() {
  if (!onOff) {
    onOff = true;
    listarDia();
    //clearInterval(counter);
  } else {
    onOff = false;
    listar();
    //counter=setInterval(timer, 5000);
  }
}


//Función Listar
function listarDia() {
  tabla_principal_dia = $('#tbllistado').dataTable( {
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom:"<'row'<'col-md-3'B><'col-md-3 float-left'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",//Definimos los elementos del control de tabla
    buttons: [
      { text: '<i class="fa-solid fa-arrows-rotate" data-toggle="tooltip" data-original-title="Recargar"></i> ', className: "btn bg-gradient-info m-r-5px", action: function ( e, dt, node, config ) { if (tabla_principal_dia) { tabla_principal_dia.ajax.reload(null, false); } } },
      { extend: 'copyHtml5', exportOptions: { columns: [1,2,3,4,5,6], }, text: `<i class="fas fa-copy" data-toggle="tooltip" data-original-title="Copiar"></i>`, className: "btn bg-gradient-gray m-r-5px", footer: true,  }, 
      { extend: 'excelHtml5', exportOptions: { columns: [1,2,3,4,5,6], }, title: 'Lista de articulos', text: `<i class="far fa-file-excel fa-lg" data-toggle="tooltip" data-original-title="Excel"></i>`, className: "btn bg-gradient-success m-r-5px", footer: true,  }, 
      { extend: 'pdfHtml5', exportOptions: { columns: [1,2,3,4,5,6], }, title: 'Lista de articulos', text: `<i class="far fa-file-pdf fa-lg" data-toggle="tooltip" data-original-title="PDF"></i>`, className: "btn bg-gradient-danger m-r-5px", footer: false, orientation: 'landscape', pageSize: 'LEGAL',  },
      { extend: "colvis", text: `<i class="fas fa-outdent"></i>`, className: "btn bg-gradient-gray", exportOptions: { columns: "th:not(:last-child)", }, },
    ],
    "ajax":  {
      url: '../ajax/notacd.php?op=listarNCDia&idempresa=' + $idempresa,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText);
      }
    },
    "bDestroy": true,
    "iDisplayLength": 10//,//Paginación
    //"order": [[ 2, "asc" ]]//Ordenar (columna,orden)
  }).DataTable();

  // setInterval( function () {
  // tabla.ajax.reload(null, false);
  // }, 10000 );
}


//Función para guardar o editar

function guardaryeditarNcredito(e) {
  e.preventDefault(); //No se activará la acción predeterminada del evento
  if ($('#numero_nc').val() == "") {
    swal.fire("Revizar précio!, cantidad o Stock");
  } else {
    capturarhora();
    var formData = new FormData($("#formulario")[0]);
    var codtiponota = $("#codigo_nota option:selected").val();
    $("#codtiponota").val(codtiponota);
    $.ajax({
      url: "../ajax/notacd.php?op=guardaryeditarnc&tipodo=" + tipo,
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: function (datos) {
        sw_success('Exito!!',datos);         
        mostrarform(false);
        listarDia();
      }
    });
    limpiar();
  }
  //unotodos();
}

//Funcion para enviararchivo xml a SUNAT
function generarxml(idnota) {
  $.post("../ajax/notacd.php?op=generarxml", { idnota: idnota }, function (e) {
    data = JSON.parse(e);
    sw_success('Se ha generado el archivo XML', 'Haga clic en el siguiente enlace para descargar el archivo: <br><a href="' + data.cabextxml + '" download="' + data.cabxml + '">' + data.cabxml + '</a>');
    
    tabla_principal.ajax.reload();
  });  
}

//Función para enviar xml a sunat
function enviarxmlSUNAT(idnota) {
  $.post("../ajax/notacd.php?op=enviarxmlSUNAT", { idnota: idnota }, function (e) {
    sw_success('Enviado', e);    
    tabla_principal.ajax.reload();
  }).fail(function () { sw_error('Error','No se pudo enviar el archivo a SUNAT'); });
  
}

function limpiarcliente() {
  //NUEVO CLIENTE
  $("#numero_documento").val("");
  $("#razon_social").val("");
  $("#domicilio_fiscal").val("");
  $("#iddepartamento").val("");
  $("#idciudad").val("");
  $("#iddistrito").val("");
  $("#telefono1").val("");
  //=========================
}

function agregarComprobante(idcomprobante, tdcliente, ndcliente, rzcliente, domcliente, tipocomp, numerodoc, subtotal, igv, total, fecha, fecha2) {
  moneda = $("#tipo_moneda").val();
  $(".filas2").remove();
  if (idcomprobante != "") {

    $('#idcomprobante').val(idcomprobante); //Id de factura
    $('#tipo_documento_cliente').val(tdcliente);
    $('#numero_documento_cliente').val(ndcliente);
    $('#razon_social').val(rzcliente);
    $('#fechacomprobante').val(fecha);
    $('#tipocomprobante').val(tipocomp);
    $('#numero_comprobante').val(numerodoc);

    $("#subtotal").html(number_format(subtotal, 2));
    $("#subtotal_comprobante").val(subtotal);
    $("#igv_").html(number_format(igv, 2));
    $("#total_igv").val(igv);
    $("#total").html(number_format(total, 2));
    $("#total_final").val(total);    
    $("#fecha_factura").val(fecha2);

    $("#btnGuardar").show();
  }
  else {
    alert("Error al ingresar el detalle, revisar los datos del cliente");
  }

  //========================================================================

  $.post(`../ajax/notacd.php?op=detalle_comprobante&id=${idcomprobante}&tipo=${tipo}`, function (r) {
    $("#detalles").html(r);
  });

  //============================================================================
  $("#myModalComprobante").modal('hide');
}


function tipomonn() {
  listarComprobante();
}

//Función
function listarComprobante() {
  moneda = $("#tipo_moneda").val();
  tabla_comprobantes = $('#tblacomprobante').dataTable({
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom:"<'row'<'col-md-3'B><'col-md-3 float-left'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",//Definimos los elementos del control de tabla
    buttons: [
      { text: '<i class="fa-solid fa-arrows-rotate" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Recargar"></i> ', className: "btn bg-gradient-info m-r-5px", action: function ( e, dt, node, config ) { if (tabla_comprobantes) { tabla_comprobantes.ajax.reload(null, false); } } },     
    ],
    "ajax":{
      url: '../ajax/notacd.php?op=listarComprobante&tipodo=' + tipo + '&idempresa=' + $idempresa + '&mo=' + moneda,      
      type: "post",
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

function cambio() {
  var tipodocu = $("input[type='radio'][name='tipo_doc_mod']:checked").val();

  if (tipodocu == "01") {
    tipo = '01';
  } else if (tipodocu == "03") {
    tipo = '03';
  } else if (tipodocu == "04") {
    tipo = '04';
  } else {
    tipo = '05';
  }
  $("#hinum").val(tipo);
  // tabla_comprobantes.ajax.reload();
  listarComprobante();
}

//Función ListarArticulos
function listarArticulos() {

  tpf = $('#tipofactura').val();
  tipoprecio = $('#tipoprecio').val();
  almacen = $('#almacenlista').val();
  $iteno = $('#itemno').val();

  tabla = $('#tblarticulos').dataTable( {
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom: 'Bfrtip',//Definimos los elementos del control de tabla
    buttons: [  ],
    "ajax":  {
      url: '../ajax/notacd.php?op=listarArticulosNC&tipoprecioaa=' + tipoprecio + '&tipof=' + tpf + '&itm=' + $iteno + '&alm=' + almacen,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText);
      }
    },
    "bDestroy": true,
    "iDisplayLength": 5,//Paginación
    "order": [[5, "desc"]]//Ordenar (columna,orden)
  }).DataTable();
}

//================== AGREGAR DETALLE PARA BOLETAS =================================

//Función para anular registros
function enviarcorreo(idnota) {
  Swal.fire({
    title: '¿Está seguro de enviar correo al cliente?',
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sí',
    cancelButtonText: 'No'
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/notacd.php?op=enviarcorreo", { idnota: idnota }, function (e) {
        Swal.fire({
          title: e,
          icon: 'success',
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'Aceptar'
        }).then((result) => {
          if (result.isConfirmed) {
            tabla.ajax.reload();
          }
        })
      });
    }
  })
}

//Funcion para enviararchivo xml a SUNAT
function mostrarxml(idnota) {
  $.post("../ajax/notacd.php?op=mostrarxml", { idnota: idnota }, function (e) {
    data = JSON.parse(e);

    if (data.rutafirma) {
      var rutacarpeta = data.rutafirma;
      $("#modalxml").attr('src', rutacarpeta);
      $("#modalPreviewXml").modal("show");
    } else {
      Swal.fire({
        icon: 'info',
        title: 'Archivo XML',
        html: data.cabextxml
      });
    }
  });
}

//Funcion para enviararchivo xml a SUNAT
function mostrarrpta(idnota) {
  Swal.fire({
    title: "¿Está seguro de que desea mostrar la respuesta?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/notacd.php?op=mostrarrpta", { idnota: idnota }, function (
        e
      ) {
        data = JSON.parse(e);
        var rptaS = data.rutaxmlr;
        $("#modalxml").attr("src", rptaS);
        $("#modalPreviewXml").modal("show");
      });
    }
  });
}

function refrescartabla() {
  tabla.ajax.reload();
}

function baja(idnota) {
  var f = new Date();
  cad = f.getHours() + ":" + f.getMinutes() + ":" + f.getSeconds();

  Swal.fire({
    title: "Escriba el motivo de baja de la nota de crédito.",
    input: "textarea",
    showCancelButton: true,
    confirmButtonText: "Aceptar",
    cancelButtonText: "Cancelar",
    allowOutsideClick: false,
    inputValidator: (value) => {
      if (!value) {
        return "Por favor, escriba el motivo de baja";
      }
    },
  }).then((result) => {
    if (result.isConfirmed) {
      var motivo = result.value;
      $.post(
        "../ajax/notacd.php?op=bajanc&comentario=" + motivo + "&hora=" + cad,
        { idnota: idnota },
        function (e) {
          Swal.fire({
            title: "Baja realizada",
            text: e,
            icon: "success",
            confirmButtonText: "Aceptar",
            allowOutsideClick: false,
          }).then(() => {
            tabla.ajax.reload();
          });
        }
      );
    }
  });
}

init();

// .....::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M  :::::::::::::::::::::::::::::::::::::::..

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..

function capturarhora() {
  var f = new Date();
  cad = f.getHours() + ":" + f.getMinutes() + ":" + f.getSeconds();
  $("#hora").val(cad);
}

function redondeo(numero, decimales) {
  var flotante = parseFloat(numero);
  var resultado = Math.round(flotante * Math.pow(10, decimales)) / Math.pow(10, decimales);
  return resultado;
}

function round(value, exp) {
  if (typeof exp === 'undefined' || +exp === 0)
    return Math.round(value);
  value = +value;
  exp = +exp;

  if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0))
    return NaN;
  // Shift
  value = value.toString().split('e');
  value = Math.round(+(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp)));
  // Shift back
  value = value.toString().split('e');
  return +(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp));
}

//Función para el formato de los montos
function number_format(amount, decimals) {

  amount += ''; // por si pasan un numero en vez de un string
  amount = parseFloat(amount.replace(/[^0-9\.]/g, '')); // elimino cualquier cosa que no sea numero o punto

  decimals = decimals || 0; // por si la variable no fue fue pasada

  // si no es un numero o es igual a cero retorno el mismo cero
  if (isNaN(amount) || amount === 0)
    return parseFloat(0).toFixed(decimals);

  // si es mayor o menor que cero retorno el valor formateado como numero
  amount = '' + amount.toFixed(decimals);

  var amount_parts = amount.split('.'),
    regexp = /(\d+)(\d{3})/;

  while (regexp.test(amount_parts[0]))
    amount_parts[0] = amount_parts[0].replace(regexp, '$1' + ',' + '$2');

  return amount_parts.join('.');
}

function round(value, exp) {
  if (typeof exp === 'undefined' || +exp === 0)
    return Math.round(value);
  value = +value;
  exp = +exp;

  if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0))
    return NaN;
  // Shift
  value = value.toString().split('e');
  value = Math.round(+(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp)));
  // Shift back
  value = value.toString().split('e');
  return +(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp));
}


//Función para el formato de los montos
function number_format(amount, decimals) {
  amount += ''; // por si pasan un numero en vez de un string
  amount = parseFloat(amount.replace(/[^0-9\.]/g, '')); // elimino cualquier cosa que no sea numero o punto
  decimals = decimals || 0; // por si la variable no fue fue pasada
  // si no es un numero o es igual a cero retorno el mismo cero
  if (isNaN(amount) || amount === 0){return parseFloat(0).toFixed(decimals);} 
  // si es mayor o menor que cero retorno el valor formateado como numero
  amount = '' + amount.toFixed(decimals);
  var amount_parts = amount.split('.'), regexp = /(\d+)(\d{3})/;
  while (regexp.test(amount_parts[0])){amount_parts[0] = amount_parts[0].replace(regexp, '$1' + ',' + '$2');} 
  return amount_parts.join('.');
}