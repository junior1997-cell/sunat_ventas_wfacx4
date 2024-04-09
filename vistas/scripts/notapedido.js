var tabla;
var tablaArti;
var tablaArti2;
var numero = "";
toastr.options = {
  closeButton: false,
  debug: false,
  newestOnTop: false,
  progressBar: false,
  rtl: false,
  positionClass: "toast-bottom-center",
  preventDuplicates: false,
  onclick: null,
};

$idempresa = $("#idempresa").val();
$iva = $("#iva").val();

//Función que se ejecuta al inicio
function init() {
  $("#razon_social").val("VARIOS");     // Valor Predeterminado
  $("#numero_documento").val("VARIOS"); // Valor Predeterminado

  $("#formulario").on("submit", function (e) {guardaryeditarNotaPedido(e);});        // EVENTO Guardar Nueva Nota de Venta
  $("#formularioncliente").on("submit", function (e) { guardaryeditarcliente(e); }); // EVENTO Guardar Nuevo Clienta
  $("#formularionarticulo").on("submit", function (e) {guardaryeditararticulo(e);}); // EVENTO Guardar Nuevo Producto

  // Carga de combo para vendedores ==============
  $.post("../ajax/vendedorsitio.php?op=selectVendedorsitio&idempresa=" + $idempresa, function (r) {$("#vendedorsitio").html(r);});
  
  //Cargar el Combo de Almacenes =================
  $.post("../ajax/notapedido.php?op=selectAlmacen", function (r) {$("#almacenlista").html(r);});
  
  // Cargar el Tipo de Cambio Moneda =============
  $.post("../ajax/factura.php?op=tcambiodia", function(r){ $("#tcambio").val(r); });

  // Cargar el Tipo de Impuesto ==================
  $.post("../ajax/factura.php?op=selectTributo", function (r) {  $("#codigo_tributo_18_3").html(r); });

  //Cargar el Combo de Almacenes para Nuevo Producto ============
  $.post( "../ajax/articulo.php?op=selectAlmacen&idempresa=" + $idempresa, function (r) {$("#idalmacennarticulo").html(r);});
  
  //Cargar el Combo de Categorias para Nuevo Producto ===========
  $.post("../ajax/articulo.php?op=selectFamilia", function (r) {$("#idfamilianarticulo").html(r);});
  
  //Cargar Unidad de Medida para Nuevo Producto =================
  $.post("../ajax/factura.php?op=selectunidadmedidanuevopro", function (r) {$("#umedidanp").html(r);});

  mostrarform(false);
  listar();

  cont = 0;
  conNO = 1;
}

// Numeración de la Nota de Venta
function incremetarNum() {
  var serie = $("#serie option:selected").val();
  $.post("../ajax/notapedido.php?op=autonumeracion&ser=" + serie, function (r) {
    var n2 = pad(r, 0);
    $("#numero_baucher").val(n2);

    var SerieReal = $("#serie option:selected").text();
    $("#SerieReal").val(SerieReal);
  });
  document.getElementById("tipo_doc_ide").focus();
}

//Función limpiar
function limpiar() {
 $("#idcliente").val("N");
 $("#numero_guia").val("");
 $("#cliente").val("");
 $("#ipagado_input").val("");
 $("#numero_baucher").val("");
 $("#impuesto").val("0");
 $("#total_notapedido").val("");
 $("#subtotal_notapedido").val("");
 $("#total_igv").val("");
 $(".filas").remove();
 $("#total").html("0");
 $("#tcambio").val("0");
 $("#tipo_doc_ide").val("0").trigger("change");
 $("#codigo_tributo_h").val($("#codigo_tributo_18_3 option:selected").val());
 $("#total").val("");
 $("#nroreferencia").val("");
 $("#total_final").val("");
 $("#ipagado").html("");
 $("#saldo").html("0");
 $("#ipagado_final").val("");
 $("#saldo_saldo").val("");
 $("#tiponota").val("productos");
 $("#montocuota").val("");
 $("#tipopago").val("Contado");
 $("#ccuotas").val("0");
 document.getElementById("mensaje700").style.display = "none";
 const inputFechaVenc = document.getElementById("fechavenc");
 $.post("../ajax/factura.php?op=selectTributo", function (r) { $("#codigo_tributo_18_3").html(r); });
 $("#fecha_emision_01").prop("disabled", false); //Obtenemos la fecha actual

 // Establecer el valor máximo permitido para la fecha de vencimiento en 4 días a partir de la fecha actual
 const fechaMax = new Date();
 fechaMax.setDate(fechaMax.getDate() + 4);
 inputFechaVenc.addEventListener("change", function () {
   const fechaSeleccionada = new Date(this.value);
   const fechaActual = new Date();
   const diasDiferencia = Math.round(
     (fechaSeleccionada - fechaActual) / (1000 * 60 * 60 * 24)
   );

   // Verificar si se ha seleccionado una fecha anterior a la actual
   if ( fechaSeleccionada < fechaActual && fechaSeleccionada.toDateString() !== fechaActual.toDateString() ) {
     Swal.fire({
       title: "Error",
       text: "La fecha de vencimiento no puede ser posterior a 6 días a partir de la fecha actual normado por sunat",
       icon: "error",
       confirmButtonText: "OK",
     });
     this.value = fechaActual.toISOString().slice(0, 10);
   }
   // Verificar si se ha seleccionado una fecha posterior a 4 días a partir de la fecha actual
   else if (diasDiferencia > 4) {
     Swal.fire({
       title: "Error",
       text: "La fecha de vencimiento no puede ser posterior a 6 días a partir de la fecha actual",
       icon: "error",
       confirmButtonText: "OK",
     });
     this.value = fechaActual.toISOString().slice(0, 10);
   }
 });

 document.getElementById("tarjetadc").checked = false;
 document.getElementById("transferencia").checked = false;
 $("#tadc").val("0");
 $("#trans").val("0");
 var now = new Date();
 var day = ("0" + now.getDate()).slice(-2);
 var month = ("0" + (now.getMonth() + 1)).slice(-2);
 var today = now.getFullYear() + "-" + month + "-" + day;
 $("#fecha_emision_01").val(today);
 $("#fechavenc").val(today);
 cont = 0;
 conNO = 1;
}

//Función Listar
function listar() {
 tabla = $("#tbllistado").dataTable({
     aProcessing: true, //Activamos el procesamiento del datatables
     aServerSide: true, //Paginación y filtrado realizados por el servidor
     dom: "Bfrtip", //Definimos los elementos del control de tabla
     buttons: [
       { text: '<i class="fa-solid fa-arrows-rotate" data-toggle="tooltip" data-original-title="Recargar"></i> ', className: "btn bg-gradient-info m-r-5px", action: function ( e, dt, node, config ) { if (tabla) { tabla.ajax.reload(null, false); } } },
       { extend: 'copyHtml5', exportOptions: { columns: [1,2,3,4,5,6,7], }, text: `<i class="fas fa-copy" data-toggle="tooltip" data-original-title="Copiar"></i>`, className: "btn bg-gradient-gray m-r-5px", footer: true,  }, 
       { extend: 'excelHtml5', exportOptions: { columns: [1,2,3,4,5,6,7], }, text: `<i class="far fa-file-excel fa-lg" data-toggle="tooltip" data-original-title="Excel"></i>`, className: "btn bg-gradient-success m-r-5px", footer: true,  }, 
       { extend: 'pdfHtml5', exportOptions: { columns: [1,2,3,4,5,6,7], }, text: `<i class="far fa-file-pdf fa-lg" data-toggle="tooltip" data-original-title="PDF"></i>`, className: "btn bg-gradient-danger m-r-5px", footer: false, orientation: 'landscape', pageSize: 'LEGAL',  },
       { extend: "colvis", text: `<i class="fas fa-outdent"></i>`, className: "btn bg-gradient-gray", exportOptions: { columns: "th:not(:last-child)", }, },
     ],
     ajax: {
       url: "../ajax/notapedido.php?op=listar",
       type: "get",
       dataType: "json",
       error: function (e) {
         console.log(e.responseText);
       },
     },
     bDestroy: true,
     iDisplayLength: 15, //Paginación
     order: [[0, "desc"]], //Ordenar (columna,orden)
    columnDefs: [
      // { targets: [9,10,11,12,13,14,15,16], visible: false, searchable: false, }, 
      { targets: [1], render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD/MM/YYYY'), },
      { targets: [5,6,7], render: function (data, type) { var number = $.fn.dataTable.render.number(',', '.', 2).display(data); if (type === 'display') { let color = 'numero_positivos'; if (data < 0) {color = 'numero_negativos'; } return `<span class="float-left">S/</span> <span class="float-right ${color} "> ${number} </span>`; } return number; }, },      
    ],
   })
   .DataTable();
}

//Función mostrar formulario
function mostrarform(flag) {
 if (flag) {
   $("#listadoregistros").hide();
   $("#formularioregistros").show();
   $("#btnagregar").hide();
   listarArticulos();

   $("#btnGuardar").hide();
   $("#btnCancelar").show();
   $("#btnAgregarArt").show();
   $("#btnAgregarCli").hide();
   $("#refrescartabla").hide();

   $(".charge-serie").html(`<i class="fas fa-spinner fa-pulse"></i>`);
   $(".charge-numero").html(`<i class="fas fa-spinner fa-pulse"></i>`);

   $.post("../ajax/notapedido.php?op=selectSerie", function (r) {
     if (r == '' || r == null) {
       $(".no-tienes-permiso-notapedido").html('No tienes permiso para emitir nota de pedido.');  
       $(".charge-numero").html(``);
     } else {  
       $("#serie").html(r);        
       var serieL = document.getElementById("serie");
       var opt = serieL.value;
       $.post( "../ajax/notapedido.php?op=autonumeracion&ser=" + opt + "&idempresa=" +  $idempresa, function (r) {
         var n2 = pad(r, 0);
         $("#numero_baucher").val(n2);
         var SerieReal = $("#serie option:selected").text();
         $("#SerieReal").val(SerieReal);
         $(".charge-numero").html(``);
       });
     }      
     $(".charge-serie").html(``);
   });
   document.getElementById("codigob").focus();

 } else {
   $("#listadoregistros").show();
   $("#formularioregistros").hide();
   $("#btnagregar").show();
   $("#refrescartabla").show();
 }
}

//Función cancelar formulario
function cancelarform() {
 Swal.fire({
   title: "¿Desea cancelar Nota de pedido?",
   icon: "warning",
   showCancelButton: true,
   confirmButtonColor: "#3085d6",
   cancelButtonColor: "#d33",
   confirmButtonText: "Sí",
   cancelButtonText: "No",
 }).then((result) => {
   if (result.isConfirmed) {
     limpiar();
     evaluar2();
     detalles = 0;
     detalles2 = 0;
     mostrarform(false);
   }
 });
}

//Función Listar Clientes
function listarClientes() {
 tabla = $("#tblaclientes")
   .dataTable({
     aProcessing: true, //Activamos el procesamiento del datatables
     aServerSide: true, //Paginación y filtrado realizados por el servidor
     dom: "Bfrtip", //Definimos los elementos del control de tabla
     buttons: [],
     ajax: {
       url: "../ajax/notapedido.php?op=listarClientesNota",
       type: "get",
       dataType: "json",
       error: function (e) {
         console.log(e.responseText);
       },
     },
     bDestroy: true,
     iDisplayLength: 5, //Paginación
     order: [[0, "desc"]], //Ordenar (columna,orden)
   })
   .DataTable();
}

//Función Listar Articulos
function listarArticulos() {
 $tpb        = $("#tiponota").val();
 $tipoprecio = $("#tipoprecio").val();
 $iteno      = $("#itemno").val();
 almacen     = $("#almacenlista").val();

 tablaArti   = $("#tblarticulos").dataTable({
   lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
   aProcessing: true, //Activamos el procesamiento del datatables
   aServerSide: true, //Paginación y filtrado realizados por el servidor
   searching: true,
   dom:"<'row'<'col-md-3'B><'col-md-3 float-left'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",//Definimos los elementos del control de tabla
   buttons: [{ text: '<i class="fa-solid fa-arrows-rotate" data-toggle="tooltip" data-original-title="Recargar"></i> ', className: "btn bg-gradient-info m-r-5px", action: function ( e, dt, node, config ) { if (tablaArti) { tablaArti.ajax.reload(null, false); } } },],
   ajax: {
     url: "../ajax/notapedido.php?op=listarArticulosnota&tprecio=" + $tipoprecio + "&tb=" + $tpb + "&itm=" + $iteno + "&alm=" + almacen,
     type: "get",
     dataType: "json",
     error: function (e) {
       console.log(e.responseText);
     },
   },
   fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
     // Agregar el input y el botón en la columna correspondiente (índice 5)
     $(nRow) .find("td:eq(5)")
       .html(
         '<div class="row">' +
         '<div class="col-8">' +
         '<input type="number" class="form-control hidebutton text-end" style="width: 100px;" id="product_stock" value="' +
         aData[5] +
         '" />' +
         "</div>" +
         '<div class="col-2 p-0">' +
         '<button class="btn btn-secondary btn-sm m-0" id="btn_editarstock"><i class="fas fa-save"></i></button>' +
         "</div>" +
         "</div>"
       );

     if (aData[5] == "0.00") {
       $("td", nRow).css("background-color", "#fd96a9");

       // Agregar evento de clic al botón de guardar
       $(nRow) .find("#btn_editarstock")
         .click(function () {
           var newStock = $(nRow).find("#product_stock").val();
           var idarticulo = aData[8];
           var formData = new FormData();
           formData.append("idarticuloproduct", idarticulo);
           formData.append("stockproduct", newStock);

           console.log("Nuevo data:", formData);
           $.ajax({
             url: "../ajax/articulo.php?op=editarstockarticulo",
             type: "POST",
             data: formData,
             contentType: false,
             processData: false,
             success: function (response) {
               Swal.fire({
                 icon: "success",
                 title: "¡Éxito!",
                 showConfirmButton: false,
                 timer: 1500,
                 text: response,
               });
               $("#tblarticulos").DataTable().ajax.reload();
             },
             error: function () {
               Swal.fire({
                 icon: "error",
                 title: "Error al guardar",
                 text: "Ha ocurrido un error al actualizar los datos",
               });
             },
           });
         });
     } else {
       $("td", nRow).css("background-color", "");

       // Agregar evento de clic al botón de guardar
       $(nRow)
         .find("#btn_editarstock")
         .click(function () {
           var newStock = $(nRow).find("#product_stock").val();
           var idarticulo = aData[8];
           var formData = new FormData();
           formData.append("idarticuloproduct", idarticulo);
           formData.append("stockproduct", newStock);
           Swal.fire({
             title: "Aún tienes suficiente stock",
             text: "¿Deseas agregar más?",
             showDenyButton: true,
             confirmButtonText: "Sí",
             denyButtonText: "No",
           }).then((result) => {
             if (result.isConfirmed) {
               $.ajax({
                 url: "../ajax/articulo.php?op=editarstockarticulo",
                 type: "POST",
                 data: formData,
                 contentType: false,
                 processData: false,
                 success: function (response) {
                   Swal.fire({
                     icon: "success",
                     title: "¡Éxito!",
                     showConfirmButton: false,
                     timer: 1500,
                     text: response,
                   });
                   $("#tblarticulos").DataTable().ajax.reload();
                 },
                 error: function () {
                   Swal.fire({
                     icon: "error",
                     title: "Error al guardar",
                     text: "Ha ocurrido un error al actualizar los datos",
                   });
                 },
               });
             } else if (result.isDenied) {
               Swal.fire("Los cambios no se guardaron", "", "info");
               $("#tblarticulos").DataTable().ajax.reload();
             }
           });
         });
     }

     // Agregar evento de clic a la imagen
     $(nRow)
       .find("td:eq(6) img")
       .css("cursor", "pointer") // Cambiar el cursor a una mano
       .click(function () {
         mostrarModal(aData); // Llamada a la función para mostrar el modal con los datos
       });
   },

   bDestroy: true,
   iDisplayLength: 10, //Paginación
   order: [[5, "desc"]], //Ordenar (columna,orden)
 }).DataTable();
 $("div.dataTables_filter input").focus(); // PARA PONER INPUT FOCUS
 $("#tblarticulos").DataTable().ajax.reload();
 $("#tblarticulos [type='search']").focus();
}

//Función para guardar o editar Nota Pedido
function guardaryeditarNotaPedido(e) {

 e.preventDefault(); //No se activará la acción predeterminada del evento
 // Verificar si la tabla tiene al menos una fila
 if ($("#tipo_doc_ide").val() === "") { sw_warning('Alerta!!',"Por favor, selecciona un tipo de documento.", 5000 ); return; }

 var tipoDoc = $("#tipo_doc_ide").val();

 // Suponiendo que el valor de "DNI" en el select es "1" o "2", según lo que mencionaste
 if (tipoDoc === "1" || tipoDoc === "2") {
   var dni = $("#numero_documento");
   var regexDNI = /^[0-9]{8}$/;
   if (!regexDNI.test(dni.val())) { sw_warning('Alerta!!',"El DNI debe ser de 8 dígitos.", 5000 ); dni.focus(); return;  }
 }

 var regexNombre = /^[A-Za-záéíóúÁÉÍÓÚñÑ ]+$/;
 var nombre = document.getElementById("razon_social");
 if (!regexNombre.test(nombre.value)) { sw_warning('Alerta!!',"Por favor, introduce un nombre válido (sin caracteres especiales).", 5000 ); nombre.focus(); return; }

 var rowCount = $("#detalles tbody tr").length;
 if (rowCount == 0) { sw_error('Error!!', "La tabla está vacía, por favor agregue al menos un producto.", 5000); return; }

 var cant = document.getElementsByName("cantidad_item_12[]");
 var prec = document.getElementsByName("precio_unitario[]");
 var stk = document.getElementsByName("stock[]");
 sw = 0;
 for (var i = 0; i < cant.length; i++) {
   var inpC = cant[i];
   var inpP = prec[i];
   var inStk = stk[i];
   if (  inpP.value == 0.0 || inpP.value == "" || inpC.value == 0 || inStk.value == 0 ||  $("#numero_baucher").val() == ""  ) {
     sw = sw + 1;
   }
 }

 if (sw != 0) {
   sw_error('Revizar précio!', "Revizar précio!, cantidad, número de Nota-Venta o Stock", 5000);    
   inpP.focus();
 } else {
   swal.fire({
     title: "¿Desea emitir la Nota de Venta?",
     icon: "question",
     showCancelButton: true,
     confirmButtonColor: "#3085d6",
     cancelButtonColor: "#d33",
     confirmButtonText: "Sí, emitir Nota de Venta",
     cancelButtonText: "Cancelar",
   }) .then((result) => {
     if (result.value) {
       capturarhora();
       var formData = new FormData($("#formulario")[0]);
       $.ajax({
         url: "../ajax/notapedido.php?op=guardaryeditarNotaPedido",
         type: "POST",
         data: formData,
         contentType: false,
         processData: false,
         success: function (datos) {
           tipoimpresion();
           mostrarform(false);
           refrescartabla();
         },
       });
       limpiar();
       $("#tdescuentoL").text("");
       $("#ipagado_input").val("");
       $("#ipagado_input").replaceWith( '<h6 id="ipagado">' + $("#ipagado_final").val() + "</h6>" );
       sw = 0;
     }
   });
 }
}

//Función Listar Productos Seleccionados
function mostrar(idboleta) {
 $.post( "../ajax/notapedido.php?op=mostrar", { idboleta: idboleta },
   function (data, status) {
     data = JSON.parse(data);
     mostrarform(true);
     $("#idboleta").val(data.idboleta);
     $("#numero_factura").val(data.numeracion_08);
     $("#numero_documento").val(data.numero_documento);
     $("#razon_social").val(data.cliente);
     $("#domicilio_fiscal").val(data.domicilio_fiscal);
     $("#fecha_emision").prop("disabled", true);
     $("#fecha_emision").val(data.fecha);
     $("#subtotal").html(data.total_operaciones_gravadas_monto_18_2);
     $("#igv_").html(data.sumatoria_igv_22_1);
     $("#total").html(data.importe_total_venta_27);

     //Ocultar y mostrar los botones
     $("#btnGuardar").hide();
     $("#btnCancelar").show();
     $("#btnAgregarArt").hide();
   }
 );

 $.post("../ajax/notapedido.php?op=listarDetalle&id=" + idfactura, function (r) {
   $("#detalles").html(r);
 });
}

//Función Anular Producto Seleccionado
function anular(idboleta) {
 Swal.fire({
   title: "¿Estás seguro de anular la Nota de pedido?",
   showCancelButton: true,
   confirmButtonText: "Sí, anular!",
   cancelButtonText: "No, cancelar!",
   icon: "warning",
   reverseButtons: true,
 }).then((result) => {
   if (result.isConfirmed) {
     $.post( "../ajax/notapedido.php?op=anular", { idboleta: idboleta },
       function (e) {
         Swal.fire("Anulado!", e, "success");
         tabla.ajax.reload();
       }
     );
   }
 });
}

//Función Anular - Nota de Venta Registrada
function baja(idboleta) {
 var f = new Date();
 var cad = f.getHours() + ":" + f.getMinutes() + ":" + f.getSeconds();

 Swal.fire({
   title: "Escriba el motivo de baja de la Nota de Venta.",
   input: "textarea",
   showCancelButton: true,
   confirmButtonText: "Enviar",
   cancelButtonText: "Cancelar",
   showLoaderOnConfirm: true,
   preConfirm: (comentario) => {
     return $.post("../ajax/notapedido.php?op=baja&comentario=" + comentario + "&hora=" + cad, { idboleta: idboleta })
      .then((response) => {
        if (!response) {
          throw new Error(response.statusText);
        }
        return response;
      })
      .catch((error) => {
        Swal.showValidationMessage(`Request failed: ${error}`);
      });
   },
   allowOutsideClick: () => !Swal.isLoading(),
 }).then((result) => {
   if (result.isConfirmed) {
     Swal.fire("Baja Enviada!", result.value, "success");
     tabla.ajax.reload();
   }
 });
}

//Función POR DEFINIR pero sirve :)
function accesoTicket(idboleta) {
 var f = new Date();
 cad = f.getHours() + ":" + f.getMinutes() + ":" + f.getSeconds();
 bootbox.prompt({
   title: "Escriba el nro de ticket.",
   inputType: "textarea",
   callback: function (result) {
     if (result) {
       $.post(
         "../ajax/notapedido.php?op=baja&comentario=" +
           result +
           "&hora=" +
           cad,
         { idboleta: idboleta },
         function (e) {
           bootbox.alert(e);
           tabla.ajax.reload();
         }
       );
     }
   },
 });
}

//Declaración de variables necesarias para trabajar con las compras y sus detalles
var impuesto = 18;
var cont = 0;
var detalles = 0;
$("#btnGuardar").hide();
$("#tipo_comprobante").change(marcarImpuesto);

function marcarImpuesto() {
  var tipo_comprobante = $("#tipo_comprobante option:selected").text();
  if (tipo_comprobante == "NOTA VENTA") {
    $("#impuesto").val(impuesto);
  } else {
    $("#impuesto").val("0");
  }
}

function agregarCliente(
  idpersona,
  razon_social,
  numero_documento,
  domicilio_fiscal,
  tipo_documento
) {
  if (idpersona != "") {
    $("#idcliente").val(idpersona);
    $("#numero_documento").val(numero_documento);
    $("#razon_social").val(razon_social);
    $("#domicilio_fiscal").val(domicilio_fiscal);
    $("#tipo_documento_cliente").val(tipo_documento);
    $("#myModalCli").modal("hide");
  } else {
    alert("Error al ingresar el detalle, revisar los datos del cliente");
  }
}

//Función para aceptar solo numeros con dos decimales
function NumCheck(e, field) {
  // Backspace = 8, Enter = 13, ’0′ = 48, ’9′ = 57, ‘.’ = 46
  key = e.keyCode ? e.keyCode : e.which;

  if (e.keyCode === 13 && !e.shiftKey) {
    document.getElementById("precio_unitario[]").focus();
  }

  // backspace
  if (key == 8) return true;
  if (key == 9) return true;
  if (key > 47 && key < 58) {
    if (field.val() === "") return true;
    var existePto = /[.]/.test(field.val());
    if (existePto === false) {
      regexp = /.[0-9]{10}$/;
    } else {
      regexp = /.[0-9]{2}$/;
    }
    return !regexp.test(field.val());
  }

  if (key == 46) {
    if (field.val() === "") return false;
    regexp = /^[0-9]+$/;
    return regexp.test(field.val());
  }
  return false;
}

//Función para aceptar solo numeros con dos decimales
function NumCheck2(e, field) {
  // Backspace = 8, Enter = 13, ’0′ = 48, ’9′ = 57, ‘.’ = 46
  key = e.keyCode ? e.keyCode : e.which;

  if (e.keyCode === 13 && !e.shiftKey) {
    document.getElementById("codigob").focus();
  }

  // backspace
  if (key == 8) return true;
  if (key == 9) return true;
  if (key > 47 && key < 58) {
    if (field.val() === "") return true;
    var existePto = /[.]/.test(field.val());
    if (existePto === false) {
      regexp = /.[0-9]{10}$/;
    } else {
      regexp = /.[0-9]{2}$/;
    }
    return !regexp.test(field.val());
  }

  if (key == 46) {
    if (field.val() === "") return false;
    regexp = /^[0-9]+$/;
    return regexp.test(field.val());
  }
  return false;
}

//Función Agregar Nuevo Producto
function agregarDetalle( 
 tipoagregacion, idarticulo, familia, codigo_proveedor, codigo, 
 nombre, precio_factura, stock, unidad_medida, precio_unitario, 
 cicbper, mticbperuSunat, factorconversion, factorc, descrip, tipoitem 
 ) {
  
  var cantidad = 0;
  if (idarticulo != "") {
    var subtotal = cantidad * precio_factura;
    var igv = subtotal * ($iva / 100);
    //var pvu = document.getElementsByName("pvu_");
    var total_fin;
    var contador = 1;
    if (parseFloat(stock) == "0") {
      sw_warning('Stock 0', "El stock es 0, actualizar stock!", 5000);      
      $("#codigob").val("");
      quitasuge3();
    } else {
      if ($("#codigo_tributo_18_3").val() == "9997") {
        exo = "";
        op = "";
        precioOculto = precio_factura;
        precio_factura = precio_factura;
        rd = "readonly";
      } else {
        op = "";
        exo = "";
        rd = "";
        precioOculto = precio_factura;
      }

      // TIPO CAMBIO

      if ($("#tipo_moneda_24").val() === "USD") {
        var tipoCambio = parseFloat($("#tcambio").val());

        precio_factura /= tipoCambio;
        precioOculto /= tipoCambio;

        precio_factura = precio_factura.toFixed(2);
        precioOculto = precioOculto.toFixed(2);
      }

      // <textarea class="" name="descdet[]" id="descdet[]" rows="1" cols="70" onkeyup="mayus(this)" onkeypress="return focusDescdet(event, this)">'+descrip+'</textarea>
      var fila = `<tr class="filas" id="fila${cont}">
        <td><i class="fa fa-close" onclick="eliminarDetalle(${cont})" style="color:red;"  data-toggle="tooltip" title="Eliminar item"></i></td>
        <td><span name="numero_orden" id="numero_orden${cont}" ></span>
        <input type="hidden" name="numero_orden_item_29[]" id="numero_orden_item_29[]" value="${conNO}"  ></td>
        <td><input type="hidden" name="idarticulo[]" style="font-family: times, serif; font-size:14pt; font-style:italic" value="${idarticulo}">${nombre}</td>
        <td hidden>
          <textarea class="" name="descdet[]" id="descdet[]" rows="1" cols="70" onkeyup="mayus(this)" onkeypress="return focusDescdet(event, this)">${descrip}</textarea>
          <select name="codigotributo[]" class="" style="display:none;"> <option value="1000">IGV</option><option value="9997">EXO</option><option value="9998">INA</option></select>
          <select name="afectacionigv[]" class="" style="display:none;"> <option value="10">10-GOO</option><option value="20">20-EOO</option><option value="30">30-FRE</option></select>
        </td>
        <td><input type="text"  class="" required="true" name="cantidad_item_12[]" id="cantidad_item_12[]"  onBlur="modificarSubtotales(1)" size="6" onkeypress="return NumCheck(event, this)" value="1" ></td>
        <td>
          <input type="text"  class="" name="descuento[]" id="descuento[]"  onBlur="modificarSubtotales(1)" size="2" onkeypress="return NumCheck(event, this)" >
          <span name="SumDCTO" id="SumDCTO${cont}" style="display:none"></span> <input type="hidden"  required="true" class="" name="sumadcto[]" id="sumadcto[]" >
        </td>
        <td hidden><input type="hidden" name="codigo_proveedor[]" id="codigo_proveedor[]" value="${codigo_proveedor}">${codigo_proveedor}</td>
        <td hidden><input type="text" name="codigo[]" id="codigo[]" value="${codigo}" class="" style="display:none;" ></td>
        <td><input type="hidden" name="unidad_medida[]" id="unidad_medida[]" value="${unidad_medida}">${unidad_medida}</td>
        <td><input type="text" class="" name="precio_unitario[]" id="precio_unitario[]" value="${precio_factura}" onBlur="modificarSubtotales(1)" size="7"   ></td>
        <td><input type="text" class="" name="valor_unitario[]" id="valor_unitario[]" size="5"  value="${precioOculto}" ${exo} onBlur="modificarSubtotales(1)"></td>
        <td><input type="text" class="" name="stock[]" id="stock[]" value="${factorconversion}" disabled="true" size="7"></td>
        <td><span name="subtotal" id="subtotal${cont}"></span>
          <input type="hidden" name="subtotalBD[]" id="subtotalBD["${cont}"]">
          <span name="igvG" id="igvG${cont}" style="background-color:#9fde90bf; display:none;"></span>
          <input type="hidden" name="igvBD[]" id="igvBD["${cont}"]"><input type="hidden" name="igvBD2[]" id="igvBD2["${cont}"]">
          <span name="total" id="total${cont}" style="background-color:#9fde90bf; display:none;" ></span>
          <span name="pvu_" id="pvu_${cont}"  style="display:none"  ></span> <input  type="hidden" name="vvu[]" id="vvu["${cont}"] size="2">
          <input  type="hidden" name="cicbper[]" id="cicbper["${cont}"] value="${cicbper}" >
          <input  type="hidden" name="mticbperu[]" id="mticbperu["${cont}"]" value="${mticbperuSunat}">
          <input type="hidden"  class="" required="true" name="factorc[]" id="factorc[]"   value="${factorc}">
          <input type="hidden"  class="" required="true" name="cantidadreal[]" id="cantidadreal[]" >
          <span name="mticbperuCalculado" id="mticbperuCalculado${cont}" style="background-color:#9fde90bf;display:none;"></span>
        </td>
      </tr>`;

      var id = document.getElementsByName("idarticulo[]");
      var can = document.getElementsByName("cantidad_item_12[]");
      var cantiS = 0;

      for (var i = 0; i < id.length; i++) {
        //for (var c = 0; c < can.length; c++) {
        var idA = id[i];
        var cantiS = can[i];

        if (tipoagregacion == "0") {
          if (tipoitem != "servicios") {
            if (idA.value == idarticulo) {
              cantiS.value = parseFloat(cantiS.value) + 1; //Agrega a la cantidad en 1
              fila = "";
              cont = cont - 1;
              conNO = conNO - 1;
            } else {
              detalles = detalles;
            }
          }
        } else {
          detalles = detalles;
          $("#myModalArt").modal("hide");
        }
      } //Fin for

      detalles = detalles + 1;
      cont++;
      conNO++;
      $("#detalles").append(fila);
      //}

      document.getElementById("numero_documento").focus();
      modificarSubtotales(1);
      tributocodnon();
      toastr_success("Agregado!!", "Agregado al detalle: " + nombre);      
      //$("#myModalArt").modal('hide');

      //para foco
      setTimeout(function () {
        document.getElementById("cantidad_item_12[]").focus();
      }, 500);

      //$('#tblarticulos').DataTable().ajax.reload();
      // $("input[type=search]").focus();
    } //If de stock menor a 20
  } else {
    Swal.fire({
      title: "Error al ingresar el detalle, revisar los datos del artículo",
      icon: "warning",
    });

    cont = 0;
  }
  //if (stock<=20) { alert("El stock esta al limite, verificar!");}
}



  function modificarSubtotales(tipoumm) {
    var noi = document.getElementsByName("numero_orden_item_29[]");
    var cant = document.getElementsByName("cantidad_item_12[]");
    var prec = document.getElementsByName("precio_unitario[]");
    var vuni = document.getElementsByName("valor_unitario[]");
    var st = document.getElementsByName("stock[]");
    var igv = document.getElementsByName("igvG");
    var sub = document.getElementsByName("subtotal");
    var tot = document.getElementsByName("total");
    var pvu = document.getElementsByName("pvu_");
    var mti = document.getElementsByName("mticbperuCalculado");
    var cicbper = document.getElementsByName("cicbper[]");
    var mticbperu = document.getElementsByName("mticbperu[]");
    var dcto = document.getElementsByName("descuento[]");
    var sumadcto = document.getElementsByName("sumadcto[]");
    var dcto2 = document.getElementsByName("SumDCTO");
    var factorc = document.getElementsByName("factorc[]");
    var cantiRe = document.getElementsByName("cantidadreal[]");
  
    for (var i = 0; i < cant.length; i++) {
      var inpNOI = noi[i];
      var inpC = cant[i];
      var inpP = prec[i];
      var inpS = sub[i];
      var inpI = igv[i];
      var inpT = tot[i];
      var inpPVU = pvu[i];
      var inStk = st[i];
      var inpVuni = vuni[i];
      var inD2 = dcto2[i];
      var dctO = dcto[i];
      var sumaDcto = sumadcto[i];
      var codIcbper = cicbper[i];
      var mticbperuNN = mticbperu[i];
      var mtiMonto = mti[i];
      var factorcc = factorc[i];
      var inpCantiR = cantiRe[i];
  
      inStk.value = inStk.value;
      mticbperuNN.value = mticbperuNN.value;
      //Validar cantidad no sobrepase stock actual
      if (parseFloat(inpC.value) > parseFloat(inStk.value)) {
        swal.fire({
          title: "Mensaje",
          text: "La cantidad supera al stock.",
          type: "warning",
        });
      } else {
        if (codIcbper.value == "7152") {
          //SI ES BOLSA
          if ($("#codigo_tributo_h").val() == "1000") {
            //inpPVU.value=inpP.value / 1.18; //Obtener el valor unitario
            inpPVU.value = inpP.value / ($iva / 100 + 1); //Obtener valor unitario
            document.getElementsByName("valor_unitario[]")[i].value = redondeo(
              inpPVU.value,
              5
            ); // Se asigan el valor al campo
            dctO.value = dctO.value;
            sumaDcto.value = sumaDcto.value;
            inpNOI.value = inpNOI.value;
            inpI.value = inpI.value;
            inpS.value = inpC.value * (inpP.value / 1.18); //Calculo de subtotal excluyendo el igv
            inD2.value = (inpC.value * inpP.value * dctO.value) / 100; //Calculo acumulado del descuento
            //FOMULA IGV
            //inpI.value=(inpS.value * 0.18);      //Calculo de IGV
            inpI.value = inpS.value * ($iva / 100); //Calculo de IGV
            //inpIitem = inpPVU.value * 0.18;    // Calculo de igv del valor unitario
            inpIitem = inpPVU.value * ($iva / 100); // Calculo de igv del valor unitario
            mtiMonto.value = mticbperuNN.value * inpC.value; // Calculo de ICbper * cantidad (0.10 * 20)
            inpT.value = inpS.value + inpI.value + mtiMonto.value; //calcula total Icbper subtotal + igv + Icbper
          } else {
            inpPVU.value = inpP.value; // / ($iva/100+1); //Obtener valor unitario
            document.getElementsByName("valor_unitario[]")[i].value = redondeo(
              inpPVU.value,
              5
            ); // Se asigan el valor al campo
            dctO.value = dctO.value;
            sumaDcto.value = sumaDcto.value;
            inpNOI.value = inpNOI.value;
            inpI.value = inpI.value;
            inpS.value = inpC.value * inpP.value; //Calculo de subtotal excluyendo el igv
            inD2.value = (inpC.value * inpP.value * dctO.value) / 100; //Calculo acumulado del descuento
            inpI.value = 0.0; //Calculo de IGV
            inpIitem = inpPVU.value; // * ($iva/100);    // Calculo de igv del valor unitario
            mtiMonto.value = mticbperuNN.value * inpC.value; // Calculo de ICbper * cantidad (0.10 * 20)
            inpT.value = inpS.value + inpI.value + mtiMonto.value; //calcula total Icbper subtotal + igv + Icbper
          }
        } else {
          // sino es bolsa
  
          if ($("#codigo_tributo_h").val() == "1000") {
            // +IGV
            //inpPVU.value=inpP.value / 1.18; //Obtener el valor unitario
            inpPVU.value = inpP.value / ($iva / 100 + 1); //Obtener el valor unitario
            document.getElementsByName("valor_unitario[]")[i].value = redondeo(
              inpPVU.value,
              5
            ); // Se asigan el valor al campo
            dctO.value = dctO.value;
            sumaDcto.value = sumaDcto.value;
            inpNOI.value = inpNOI.value;
            inpI.value = inpI.value;
            inpS.value = inpC.value * (inpP.value / ($iva / 100 + 1)); //Calculo de subtotal excluyendo el igv
            inD2.value = (inpC.value * inpP.value * dctO.value) / 100; //Calculo acumulado del descuento
            //FOMULA IGV
            inpI.value =
              inpC.value * inpP.value -
              (inpC.value * inpP.value) / ($iva / 100 + 1); //Calculo de IGV
            inpT.value =
              inpC.value * inpP.value -
              (inpC.value * inpP.value * dctO.value) / 100; //Calculo del total
            inpIitem = (inpPVU.value * $iva) / 100; // Calculo de igv del valor unitario
            mtiMonto.value = 0.0; // Calculo de ICbper * cantidad (0.10 * 20)
  
            if (tipoumm == "1") {
              inpCantiR.value =
                inStk.value / factorcc.value -
                (inStk.value - inpC.value) / factorcc.value;
            } else {
              inpCantiR.value = inpC.value;
            }
            //alert(inpCantiR.value);
          } else {
            // EXONERADA
  
            //document.getElementsByName("precio_unitario[]")[i].value = redondeo(inpVuni.value,5);
            document.getElementsByName("precio_unitario[]")[i].value = redondeo(
              inpP.value,
              5
            );
            inpNOI.value = inpNOI.value;
            inpI.value = inpI.value;
            dctO.value = dctO.value;
            sumaDcto.value = sumaDcto.value;
            inpS.value = inpC.value * inpP.value;
            inD2.value = (inpC.value * inpVuni.value * dctO.value) / 100; //Calculo acumulado del descuento
            //FOMULA IGV
            inpI.value = 0.0;
            inpT.value =
              inpC.value * inpP.value -
              (inpC.value * inpVuni.value * dctO.value) / 100; //Calculo del total;
            inpPVU.value =
              document.getElementsByName("precio_unitario[]")[i].value;
            //inpIitem = 0.00;
            inpIitem = inpP.value;
            mtiMonto.value = mticbperuNN.value * inpC.value; // Calculo de ICbper * cantidad (0.10 * 20)
            document.getElementsByName("valor_unitario[]")[i].value = redondeo(
              inpP.value,
              5
            ); // Se asigan el valor al campo
          }
        }
  
        document.getElementsByName("subtotal")[i].innerHTML = redondeo(
          inpS.value,
          2
        );
        document.getElementsByName("igvG")[i].innerHTML = redondeo(inpI.value, 4);
        document.getElementsByName("mticbperuCalculado")[i].innerHTML = redondeo(
          mtiMonto.value,
          2
        );
        document.getElementsByName("total")[i].innerHTML = redondeo(
          inpT.value,
          2
        );
        document.getElementsByName("pvu_")[i].innerHTML = redondeo(
          inpPVU.value,
          5
        );
  
        document.getElementsByName("numero_orden")[i].innerHTML = inpNOI.value;
  
        //Lineas abajo son para enviar el arreglo de inputs con los valor de IGV, Subtotal, y precio de venta
  
        //a la tala detalle_fact_art.
  
        document.getElementsByName("subtotalBD[]")[i].value = redondeo(
          inpS.value,
          2
        );
        document.getElementsByName("igvBD[]")[i].value = redondeo(inpI.value, 4);
        document.getElementsByName("igvBD2[]")[i].value = redondeo(inpIitem, 4);
        document.getElementsByName("vvu[]")[i].value = redondeo(inpPVU.value, 5);
        document.getElementsByName("SumDCTO")[i].innerHTML = redondeo(
          inD2.value,
          2
        );
        document.getElementsByName("sumadcto[]")[i].value = redondeo(
          inD2.value,
          2
        );
        //Fin de comentario
      } //Final de if
  
      if (inpP.value == 0) {
        inpP.style.backgroundColor = "#ffa69e";
        //document.getElementById("precio_unitario[]").style.backgroundColor= '#ffa69e';
      } else {
        inpP.style.backgroundColor = "#fffbfe";
        //document.getElementById("precio_unitario[]").style.backgroundColor= '#fffbfe';
      }
  
      if (inpC.value == 0) {
        inpC.style.backgroundColor = "#ffa69e";
        //document.getElementById("precio_unitario[]").style.backgroundColor= '#ffa69e';
      } else {
        inpC.style.backgroundColor = "#fffbfe";
        //document.getElementById("precio_unitario[]").style.backgroundColor= '#fffbfe';
      }
  
      if (inStk.value == 0) {
        inStk.style.backgroundColor = "#ffa69e";
        //document.getElementById("precio_unitario[]").style.backgroundColor= '#ffa69e';
      } else {
        inStk.style.backgroundColor = "#fffbfe";
        //document.getElementById("precio_unitario[]").style.backgroundColor= '#fffbfe';
      }
    } //Final de for
    calcularTotales();
  }

  function agregarItemdetalle() {
    $idarticulo = $("#iiditem").val();
    $familia = $("#familia").val();
    $codigo_proveedor = $("#codigo_proveedor").val();
    $codigo = $("#icodigo").val();
    $nombre = $("#nombre").val();
    $detalleItem = $("#idescripcion").val();
    $precio_boleta = $("#ipunitario").val();
    $stock = $("#stoc").val();
    $unidad_medida = $("#iumedida").val();
    $precio_unitario = $("#idescripcion").val();
    $cicbper = $("#cicbper").val();
    $mticbperuSunat = $("#iicbper2").val();
    $cantidad = $("#icantidad").val();
    $cantiRea = $("#cantidadrealitem").val();
    $factorCi = $("#factorcitem").val();
    if ($unidad_medida != $("#umedidaoculto").val()) {
      $cantidadreal = $cantidad;
      //alert($cantidadreal);
      $unidad_medida = $("#unidadm").val();
    }
    var cantidad = 0;
    if ($idarticulo != "") {
      if (parseFloat(stock) == "0") {
        Swal.fire({
          title: "El stock es 0, actualizar stock!",
          icon: "warning",
        });
        quitasuge3();
      } else {
        if ($("#nombre_tributo_4_p").val() == "9997") {
          exo = "";
          op = "";
          precioOculto = $precio_boleta;
          $precio_boleta = "0";
          rd = "readonly";
        } else {
          op = "";
          exo = "";
          rd = "";
          precioOculto = $precio_boleta;
        }
  
        var fila =
          '<tr class="filas" id="fila' +
          cont +
          '">' +
          '<td><i class="fa fa-close" onclick="eliminarDetalle(' +
          cont +
          ')" style="color:red;"  data-toggle="tooltip" title="Eliminar item"></i></td>' +
          '<td><span name="numero_orden" id="numero_orden' +
          cont +
          '" ></span>' +
          '<input type="hidden" name="numero_orden_item_29[]" id="numero_orden_item_29[]" value="' +
          conNO +
          '"  ></td>' +
          '<td><input type="hidden" name="idarticulo[]" style="font-family: times, serif; font-size:14pt; font-style:italic" value="' +
          $idarticulo +
          '">' +
          $nombre +
          "</td>" +
          '<td><textarea class="" name="descdet[]" id="descdet[]" rows="1" cols="70" onkeyup="mayus(this)" onkeypress="return focusDescdet(event, this)">' +
          $detalleItem +
          "</textarea>" +
          '<select name="codigotributo[]" class="" style="display:none;"> <option value="1000">IGV</option><option value="9997">EXO</option><option value="9998">INA</option></select>' +
          '<select name="afectacionigv[]" class="" style="display:none;"> <option value="10">10-GOO</option><option value="20">20-EOO</option><option value="30">30-FREE</option></select></td>' +
          '<td><input type="text"  class="" required="true" name="cantidad_item_12[]" id="cantidad_item_12[]"  size="6" onkeypress="return NumCheck(event, this)"  value="' +
          $cantidadreal +
          '" >' +
          '<input type="hidden"  name="cantidad2[]" id="cantidad2[]"  readonly value="' +
          $cantidadreal +
          '"  size="6" onkeypress="return NumCheck(event, this)" ></td>' +
          '<td><input type="text"  class="" name="descuento[]" id="descuento[]"   size="2" onkeypress="return NumCheck(event, this)" >' +
          '<span name="SumDCTO" id="SumDCTO' +
          cont +
          '" style="display:none"></span> <input type="hidden"  required="true" class="" name="sumadcto[]" id="sumadcto[]" >  </td>' +
          '<td><input type="hidden" name="codigo_proveedor[]" id="codigo_proveedor[]" value="' +
          $codigo_proveedor +
          '">' +
          $codigo_proveedor +
          "</td>" +
          '<td><input type="text" name="codigo[]" id="codigo[]" value="' +
          $codigo +
          '" class="" style="display:none;" ></td>' +
          '<td><input type="hidden" name="unidad_medida[]" id="unidad_medida[]" value="' +
          $unidad_medida +
          '">' +
          $unidad_medida +
          "</td>" +
          '<td><input type="text" class="" name="precio_unitario[]" id="precio_unitario[]" value="' +
          $precio_boleta +
          '"  size="7"   ></td>' +
          '<td><input type="text" class="" name="valor_unitario[]" id="valor_unitario[]" size="5"  value="' +
          precioOculto +
          '"    ' +
          exo +
          " ></td>" +
          '<td><input type="text" class="" name="stock[]" id="stock[]" value="' +
          $stock +
          '" disabled="true" size="7"></td>' +
          '<td><span name="subtotal" id="subtotal' +
          cont +
          '"></span>' +
          '<input type="hidden" name="subtotalBD[]" id="subtotalBD["' +
          cont +
          '"]">' +
          '<span name="igvG" id="igvG' +
          cont +
          '" style="background-color:#9fde90bf; display:none;"></span>' +
          '<input type="hidden" name="igvBD[]" id="igvBD["' +
          cont +
          '"]"><input type="hidden" name="igvBD2[]" id="igvBD2["' +
          cont +
          '"]">' +
          '<span name="total" id="total' +
          cont +
          '" style="background-color:#9fde90bf; display:none;" ></span>' +
          '<span name="pvu_" id="pvu_' +
          cont +
          '"  style="display:none"  ></span>' +
          '<input  type="hidden" name="vvu[]" id="vvu["' +
          cont +
          '"] size="2">' +
          '<input  type="hidden" name="cicbper[]" id="cicbper["' +
          cont +
          '"] value="' +
          $cicbper +
          '" >' +
          '<input  type="hidden" name="mticbperu[]" id="mticbperu["' +
          cont +
          '"]" value="' +
          $mticbperuSunat +
          '">' +
          '<input type="hidden"  class="" required="true" name="factorc[]" id="factorc[]" >' +
          '<input type="hidden"  class="" required="true" name="cantidadreal[]" id="cantidadreal[]" >' +
          '<span name="mticbperuCalculado" id="mticbperuCalculado' +
          cont +
          '" style="background-color:#9fde90bf;display:none;"></span>' +
          "</td>" +
          "</tr>";
  
        var id = document.getElementsByName("idarticulo[]");
        var ntrib = document.getElementsByName("nombre_tributo_4[]");
        var can = document.getElementsByName("cantidad_item_12[]");
        var cantiS = 0;
        for (var i = 0; i < id.length; i++) {
          var idA = id[i];
          var cantiS = can[i];
          if (idA.value == $idarticulo) {
            //alert("Ya esta ingresado el articulo!");
            cantiS.value = parseFloat(cantiS.value) + parseFloat($cantidad); //Agrega a la cantidad en 1
            fila = "";
            cont = cont - 1;
            conNO = conNO - 1;
          } else {
            detalles = detalles;
          }
        } //PARA VALIDACION SI YA ESTA INGRESADO EL ITEM
        detalles = detalles + 1;
        cont++;
        conNO++;
        $("#detalles").append(fila);
        $("#myModalnuevoitem").modal("hide");
        tributocodnon();
        modificarSubtotales(0);
        limpiarItem();
      } //IF si tiene menos d e 20
    } else {
      alert("Error al ingresar el detalle, revisar los datos del artículo");
      cont = 0;
    }
    //del stock si es 0
  }

function modificarSubtotales(tipoumm) {
  var noi = document.getElementsByName("numero_orden_item_29[]");
  var cant = document.getElementsByName("cantidad_item_12[]");
  var prec = document.getElementsByName("precio_unitario[]");
  var vuni = document.getElementsByName("valor_unitario[]");
  var st = document.getElementsByName("stock[]");
  var igv = document.getElementsByName("igvG");
  var sub = document.getElementsByName("subtotal");
  var tot = document.getElementsByName("total");
  var pvu = document.getElementsByName("pvu_");
  var mti = document.getElementsByName("mticbperuCalculado");
  var cicbper = document.getElementsByName("cicbper[]");
  var mticbperu = document.getElementsByName("mticbperu[]");
  var dcto = document.getElementsByName("descuento[]");
  var sumadcto = document.getElementsByName("sumadcto[]");
  var dcto2 = document.getElementsByName("SumDCTO");
  var factorc = document.getElementsByName("factorc[]");
  var cantiRe = document.getElementsByName("cantidadreal[]");

  for (var i = 0; i < cant.length; i++) {
    var inpNOI = noi[i];
    var inpC = cant[i];
    var inpP = prec[i];
    var inpS = sub[i];
    var inpI = igv[i];
    var inpT = tot[i];
    var inpPVU = pvu[i];
    var inStk = st[i];
    var inpVuni = vuni[i];
    var inD2 = dcto2[i];
    var dctO = dcto[i];
    var sumaDcto = sumadcto[i];
    var codIcbper = cicbper[i];
    var mticbperuNN = mticbperu[i];
    var mtiMonto = mti[i];
    var factorcc = factorc[i];
    var inpCantiR = cantiRe[i];

    inStk.value = inStk.value;
    mticbperuNN.value = mticbperuNN.value;
    //Validar cantidad no sobrepase stock actual
    if (parseFloat(inpC.value) > parseFloat(inStk.value)) {
      swal.fire({
        title: "Mensaje",
        text: "La cantidad supera al stock.",
        type: "warning",
      });
    } else {
      if (codIcbper.value == "7152") {
        //SI ES BOLSA
        if ($("#codigo_tributo_h").val() == "1000") {
          //inpPVU.value=inpP.value / 1.18; //Obtener el valor unitario
          inpPVU.value = inpP.value / ($iva / 100 + 1); //Obtener valor unitario
          document.getElementsByName("valor_unitario[]")[i].value = redondeo(
            inpPVU.value,
            5
          ); // Se asigan el valor al campo
          dctO.value = dctO.value;
          sumaDcto.value = sumaDcto.value;
          inpNOI.value = inpNOI.value;
          inpI.value = inpI.value;
          inpS.value = inpC.value * (inpP.value / 1.18); //Calculo de subtotal excluyendo el igv
          inD2.value = (inpC.value * inpP.value * dctO.value) / 100; //Calculo acumulado del descuento
          //FOMULA IGV
          //inpI.value=(inpS.value * 0.18);      //Calculo de IGV
          inpI.value = inpS.value * ($iva / 100); //Calculo de IGV
          //inpIitem = inpPVU.value * 0.18;    // Calculo de igv del valor unitario
          inpIitem = inpPVU.value * ($iva / 100); // Calculo de igv del valor unitario
          mtiMonto.value = mticbperuNN.value * inpC.value; // Calculo de ICbper * cantidad (0.10 * 20)
          inpT.value = inpS.value + inpI.value + mtiMonto.value; //calcula total Icbper subtotal + igv + Icbper
        } else {
          inpPVU.value = inpP.value; // / ($iva/100+1); //Obtener valor unitario
          document.getElementsByName("valor_unitario[]")[i].value = redondeo(
            inpPVU.value,
            5
          ); // Se asigan el valor al campo
          dctO.value = dctO.value;
          sumaDcto.value = sumaDcto.value;
          inpNOI.value = inpNOI.value;
          inpI.value = inpI.value;
          inpS.value = inpC.value * inpP.value; //Calculo de subtotal excluyendo el igv
          inD2.value = (inpC.value * inpP.value * dctO.value) / 100; //Calculo acumulado del descuento
          inpI.value = 0.0; //Calculo de IGV
          inpIitem = inpPVU.value; // * ($iva/100);    // Calculo de igv del valor unitario
          mtiMonto.value = mticbperuNN.value * inpC.value; // Calculo de ICbper * cantidad (0.10 * 20)
          inpT.value = inpS.value + inpI.value + mtiMonto.value; //calcula total Icbper subtotal + igv + Icbper
        }
      } else {
        // sino es bolsa

        if ($("#codigo_tributo_h").val() == "1000") {
          // +IGV
          //inpPVU.value=inpP.value / 1.18; //Obtener el valor unitario
          inpPVU.value = inpP.value / ($iva / 100 + 1); //Obtener el valor unitario
          document.getElementsByName("valor_unitario[]")[i].value = redondeo(
            inpPVU.value,
            5
          ); // Se asigan el valor al campo
          dctO.value = dctO.value;
          sumaDcto.value = sumaDcto.value;
          inpNOI.value = inpNOI.value;
          inpI.value = inpI.value;
          inpS.value = inpC.value * (inpP.value / ($iva / 100 + 1)); //Calculo de subtotal excluyendo el igv
          inD2.value = (inpC.value * inpP.value * dctO.value) / 100; //Calculo acumulado del descuento
          //FOMULA IGV
          inpI.value =
            inpC.value * inpP.value -
            (inpC.value * inpP.value) / ($iva / 100 + 1); //Calculo de IGV
          inpT.value =
            inpC.value * inpP.value -
            (inpC.value * inpP.value * dctO.value) / 100; //Calculo del total
          inpIitem = (inpPVU.value * $iva) / 100; // Calculo de igv del valor unitario
          mtiMonto.value = 0.0; // Calculo de ICbper * cantidad (0.10 * 20)

          if (tipoumm == "1") {
            inpCantiR.value =
              inStk.value / factorcc.value -
              (inStk.value - inpC.value) / factorcc.value;
          } else {
            inpCantiR.value = inpC.value;
          }
          //alert(inpCantiR.value);
        } else {
          // EXONERADA

          //document.getElementsByName("precio_unitario[]")[i].value = redondeo(inpVuni.value,5);
          document.getElementsByName("precio_unitario[]")[i].value = redondeo(
            inpP.value,
            5
          );
          inpNOI.value = inpNOI.value;
          inpI.value = inpI.value;
          dctO.value = dctO.value;
          sumaDcto.value = sumaDcto.value;
          inpS.value = inpC.value * inpP.value;
          inD2.value = (inpC.value * inpVuni.value * dctO.value) / 100; //Calculo acumulado del descuento
          //FOMULA IGV
          inpI.value = 0.0;
          inpT.value =
            inpC.value * inpP.value -
            (inpC.value * inpVuni.value * dctO.value) / 100; //Calculo del total;
          inpPVU.value =
            document.getElementsByName("precio_unitario[]")[i].value;
          //inpIitem = 0.00;
          inpIitem = inpP.value;
          mtiMonto.value = mticbperuNN.value * inpC.value; // Calculo de ICbper * cantidad (0.10 * 20)
          document.getElementsByName("valor_unitario[]")[i].value = redondeo(
            inpP.value,
            5
          ); // Se asigan el valor al campo
        }
      }

      document.getElementsByName("subtotal")[i].innerHTML = redondeo(
        inpS.value,
        2
      );
      document.getElementsByName("igvG")[i].innerHTML = redondeo(inpI.value, 4);
      document.getElementsByName("mticbperuCalculado")[i].innerHTML = redondeo(
        mtiMonto.value,
        2
      );
      document.getElementsByName("total")[i].innerHTML = redondeo(
        inpT.value,
        2
      );
      document.getElementsByName("pvu_")[i].innerHTML = redondeo(
        inpPVU.value,
        5
      );

      document.getElementsByName("numero_orden")[i].innerHTML = inpNOI.value;

      //Lineas abajo son para enviar el arreglo de inputs con los valor de IGV, Subtotal, y precio de venta

      //a la tala detalle_fact_art.

      document.getElementsByName("subtotalBD[]")[i].value = redondeo(
        inpS.value,
        2
      );
      document.getElementsByName("igvBD[]")[i].value = redondeo(inpI.value, 4);
      document.getElementsByName("igvBD2[]")[i].value = redondeo(inpIitem, 4);
      document.getElementsByName("vvu[]")[i].value = redondeo(inpPVU.value, 5);
      document.getElementsByName("SumDCTO")[i].innerHTML = redondeo(
        inD2.value,
        2
      );
      document.getElementsByName("sumadcto[]")[i].value = redondeo(
        inD2.value,
        2
      );
      //Fin de comentario
    } //Final de if

    if (inpP.value == 0) {
      inpP.style.backgroundColor = "#ffa69e";
      //document.getElementById("precio_unitario[]").style.backgroundColor= '#ffa69e';
    } else {
      inpP.style.backgroundColor = "#fffbfe";
      //document.getElementById("precio_unitario[]").style.backgroundColor= '#fffbfe';
    }

    if (inpC.value == 0) {
      inpC.style.backgroundColor = "#ffa69e";
      //document.getElementById("precio_unitario[]").style.backgroundColor= '#ffa69e';
    } else {
      inpC.style.backgroundColor = "#fffbfe";
      //document.getElementById("precio_unitario[]").style.backgroundColor= '#fffbfe';
    }

    if (inStk.value == 0) {
      inStk.style.backgroundColor = "#ffa69e";
      //document.getElementById("precio_unitario[]").style.backgroundColor= '#ffa69e';
    } else {
      inStk.style.backgroundColor = "#fffbfe";
      //document.getElementById("precio_unitario[]").style.backgroundColor= '#fffbfe';
    }
  } //Final de for
  calcularTotales();
}

function calcularTotales() {
  //var noi = document.getElementsByName("numero_orden_item");
  var sub = document.getElementsByName("subtotal");
  var igv = document.getElementsByName("igvG");
  var mticbperuCalculado = document.getElementsByName("mticbperuCalculado");
  var tot = document.getElementsByName("total");
  var pvu = document.getElementsByName("pvu_");
  var tdcto = document.getElementsByName("SumDCTO");
  var subtotal = 0.0;
  var total_igv = 0.0;
  var total_mticbperu = 0.0;
  var total = 0.0;
  var noi = 0;
  var pvu = 0.0;
  var tdcto = 0.0;
  for (var i = 0; i < sub.length; i++) {
    //noi+=document.getElementsByName("numero_orden_item")[i].value;
    subtotal += document.getElementsByName("subtotal")[i].value;
    total_igv += document.getElementsByName("igvG")[i].value;
    total_mticbperu +=
      document.getElementsByName("mticbperuCalculado")[i].value;
    total += document.getElementsByName("total")[i].value;
    pvu += document.getElementsByName("pvu_")[i].value;
    tdcto += document.getElementsByName("SumDCTO")[i].value;
  }
  //Para validar si el monto es >= a 700 y poder agregar los datos del cliente.
  var botonE = document.getElementById("btnAgregarCli");
  //botonE.disabled=true;
  $("#tdescuentoL").html(redondeo(tdcto, 2));
  $("#total_dcto").val(redondeo(tdcto, 2)); // a base de datos
  $("#subtotal_notapedido").val(redondeo(subtotal, 2));
  $("#subtotalflotante").html(redondeo(subtotal, 2));
  $("#total_igv").val(redondeo(total_igv, 2));
  $("#igvflotante").html(redondeo(total_igv, 2));
  $("#icbper").html(redondeo(parseFloat(total_mticbperu), 2));
  $("#total_icbper").val(redondeo(total_mticbperu, 4)); //Base de datos
  $("#total").html(number_format(redondeo(total, 2), 2));
  $("#totalflotante").html(number_format(redondeo(total, 2), 2));
  $("#total_final").val(redondeo(total, 2));
  $("#pre_v_u").val(redondeo(pvu, 2));
  ipag = $("#ipagado").html();
  itot = $("#total").html();
  if (parseFloat(itot) > parseFloat(ipag)) {
    $("#ipagado").html("0.00");
    $("#saldo").html("0.00");
  } else {
    $("#saldo").html(
      number_format(redondeo(parseFloat(ipag) - parseFloat(itot), 2), 2)
    );
  }
  evaluar();
}

function eliminarDetalle(indice) {
  $("#fila" + indice).remove();
  calcularTotales();
  detalles = detalles - 1;
  conNO = conNO - 1;
  actualizanorden();
  evaluar();
}

function eliminarDetalleP(indice) {
  $("#fila" + indice).remove();
  calcularTotales();
  detalles2 = detalles2 - 1;
  conNO2 = conNO2 - 1;
  actualizanorden();
  evaluar();
}

function mayor700() {
  //=============================================
  var total = $("#total_final").val();
  if (total >= 700) {
    Swal.fire({
      title: "Agregar DNI o C.E. del cliente",
      text: "Es obligatorio agregar un documento nacional de identidad. Tienes el monto MAYOR a 700 soles es normado por sunat",
      icon: "warning",
    });
    $("#btnGuardar").prop("disabled", true);
  } else {
    $("#mensaje700").hide();
    $("#btnGuardar").prop("disabled", false);
  }
}

function redondeo(numero, decimales) {
  var flotante = parseFloat(numero);
  var resultado =
    Math.round(flotante * Math.pow(10, decimales)) / Math.pow(10, decimales);
  return resultado;
}

function decimalAdjust(type, value, exp) {
  // Si el exp no está definido o es cero...
  if (typeof exp === "undefined" || +exp === 0) {
    return Math[type](value);
  }
  value = +value;
  exp = +exp;
  // Si el valor no es un número o el exp no es un entero...
  if (isNaN(value) || !(typeof exp === "number" && exp % 1 === 0)) {
    return NaN;
  }
  // Shift
  value = value.toString().split("e");
  value = Math[type](+(value[0] + "e" + (value[1] ? +value[1] - exp : -exp)));
  // Shift back
  value = value.toString().split("e");
  return +(value[0] + "e" + (value[1] ? +value[1] + exp : exp));

  // Decimal round
  if (!Math.round10) {
    Math.round10 = function (value, exp) {
      return decimalAdjust("round", value, exp);
    };
  }
  // Decimal floor
  if (!Math.floor10) {
    Math.floor10 = function (value, exp) {
      return decimalAdjust("floor", value, exp);
    };
  }
  // Decimal ceil
  if (!Math.ceil10) {
    Math.ceil10 = function (value, exp) {
      return decimalAdjust("ceil", value, exp);
    };
  }
}

function round(value, exp) {
  if (typeof exp === "undefined" || +exp === 0) return Math.round(value);

  value = +value;
  exp = +exp;

  if (isNaN(value) || !(typeof exp === "number" && exp % 1 === 0)) return NaN;

  // Shift
  value = value.toString().split("e");
  value = Math.round(+(value[0] + "e" + (value[1] ? +value[1] + exp : exp)));

  // Shift back
  value = value.toString().split("e");
  return +(value[0] + "e" + (value[1] ? +value[1] - exp : -exp));
}

//Función para el formato de los montos
function number_format(amount, decimals) {
  amount += ""; // por si pasan un numero en vez de un string
  amount = parseFloat(amount.replace(/[^0-9\.]/g, "")); // elimino cualquier cosa que no sea numero o punto

  decimals = decimals || 0; // por si la variable no fue fue pasada

  // si no es un numero o es igual a cero retorno el mismo cero
  if (isNaN(amount) || amount === 0) return parseFloat(0).toFixed(decimals);

  // si es mayor o menor que cero retorno el valor formateado como numero
  amount = "" + amount.toFixed(decimals);

  var amount_parts = amount.split("."),
    regexp = /(\d+)(\d{3})/;

  while (regexp.test(amount_parts[0]))
    amount_parts[0] = amount_parts[0].replace(regexp, "$1" + "," + "$2");

  return amount_parts.join(".");
}


/* =========================                   ========================= <>
<> ========================= PARTE DEL CLIENTE ========================= <>
<> =========================                   ========================= */


function focusI() {
  var tipo = $("#tipo_doc_ide option:selected").val();

  if (tipo == "0") {
    $.post(
      "../ajax/persona.php?op=mostrarClienteVarios",
      function (data, status) {
        data = JSON.parse(data);
        $("#idcliente").val(data.idpersona);
        $("#numero_documento").val(data.numero_documento);
        $("#razon_social").val(data.razon_social);
        $("#domicilio_fiscal").val(data.domicilio_fiscal);
      }
    );

    //document.getElementById('numero_documento').focus();
  }

  if (tipo == "1") {
    $("#numero_documento").val("");
    $("#razon_social").val("");
    $("#domicilio_fiscal").val("");
    document.getElementById("numero_documento").focus();
    document.getElementById("numero_documento").maxLength = 20;
  }

  if (tipo == "4") {
    $("#numero_documento").val("");
    $("#razon_social").val("");
    $("#domicilio_fiscal").val("");
    document.getElementById("numero_documento").focus();
    document.getElementById("numero_documento").maxLength = 15;
  }

  if (tipo == "7") {
    $("#numero_documento").val("");
    $("#razon_social").val("");
    $("#domicilio_fiscal").val("");
    document.getElementById("numero_documento").focus();
    document.getElementById("numero_documento").maxLength = 15;
  }

  if (tipo == "A") {
    $("#numero_documento").val("");
    $("#razon_social").val("");
    $("#domicilio_fiscal").val("");
    document.getElementById("numero_documento").focus();
    document.getElementById("numero_documento").maxLength = 15;
  }

  if (tipo == "6") {
    $("#numero_documento").val("");
    $("#razon_social").val("");
    $("#domicilio_fiscal").val("");
    document.getElementById("numero_documento").focus();
    document.getElementById("numero_documento").maxLength = 11;
  }
}

function agregardni() {
  var dni = $("#numero_documento").val();
  $.post( "../ajax/notapedido.php?op=listarClientesNotaxDoc&doc=" + dni,  function (data, status) {
      data = JSON.parse(data);
      if (data != null) {
        $("#idcliente").val(data.idpersona);
        $("#razon_social").val(data.nombres + " " + data.apellidos);
        $("#domicilio_fiscal").val(data.domicilio_fiscal);
        document.getElementById("btnAgregarArt").style.backgroundColor =  "#367fa9";
        document.getElementById("mensaje700").style.display = "none";
        document.getElementById("btnAgregarArt").focus();
      } else {
        var url = "../ajax/consulta_reniec.php";
        $.ajax({ type: "POST", url: url, data: "dni=" + dni, success: function (datos_dni) {
            var datos = eval(datos_dni);
            if (datos != null) {
              $("#idcliente").val("N");
              $("#razon_social").val( datos[1] + " " + datos[2] + " " + datos[3] );
              $("#domicilio_fiscal").val("");
              document.getElementById("domicilio_fiscal").focus();
            } else {
              $("#idcliente").val("N");
              $("#razon_social").val("");
              document.getElementById("razon_social").placeholder =
                "No Registrado";
              $("#domicilio_fiscal").val("");
              document.getElementById("domicilio_fiscal").placeholder =
                "No Registrado";
              document.getElementById("btnAgregarArt").style.backgroundColor =
                "#35770c";
              document.getElementById("razon_social").style.Color = "#35770c";
              document.getElementById("razon_social").focus();
            }
          },
        });
      }
    }
  );
}

function agregarClientexDoc(e) {
  var dni = $("#numero_documento").val();

  if (e.keyCode === 13 && !e.shiftKey) {
    $("#razon_social").val("");
    $("#domicilio_fiscal").val("");

    $.post(
      "../ajax/notapedido.php?op=listarClientesNotaxDoc&doc=" + dni,
      function (data, status) {
        data = JSON.parse(data);
        if (data != null) {
          $("#idcliente").val(data.idpersona);
          $("#razon_social").val(data.nombres);
          $("#domicilio_fiscal").val(data.domicilio_fiscal);
          document.getElementById("btnAgregarArt").style.backgroundColor =
            "#367fa9";
          document.getElementById("mensaje700").style.display = "none";
          document.getElementById("btnAgregarArt").focus();
          $("#suggestions").fadeOut();
          $("#suggestions2").fadeOut();
          $("#suggestions3").fadeOut();
        } else if ($("#tipo_doc_ide").val() == "1") {
          // SI ES DNI
          $("#razon_social").val("");
          $("#domicilio_fiscal").val("");
          var dni = $("#numero_documento").val();
          //var url = '../ajax/consulta_reniec.php';
          $.post(
            "../ajax/notapedido.php?op=consultaDniSunat&nrodni=" + dni,
            function (data, status) {
              data = JSON.parse(data);
              if (data != null) {
                document.getElementById("razon_social").focus();
                $("#idcliente").val("N");
                //$("#numero_documento3").val(data.numeroDocumento);
                $("#razon_social").val(data.nombre);
              } else {
                alert(data);
                document.getElementById("razon_social").focus();
                $("#idcliente").val("N");
              }
            }
          );
          $("#suggestions").fadeOut();
          $("#suggestions2").fadeOut();
          $("#suggestions3").fadeOut();
        } else if ($("#tipo_doc_ide").val() == "6") {
          // SI ES RUC
          $("#razon_social").val("");
          $("#domicilio_fiscal").val("");
          var dni = $("#numero_documento").val();
          $.post(
            "../ajax/factura.php?op=listarClientesfacturaxDoc&doc=" + dni,
            function (data, status) {
              data = JSON.parse(data);
              if (data != null) {
                $("#idcliente").val(data.idpersona);
                $("#razon_social").val(data.razon_social);
                $("#domicilio_fiscal").val(data.domicilio_fiscal);
              } else {
                $("#idcliente").val("");
                $("#razon_social").val("No registrado");
                $("#domicilio_fiscal").val("No registrado");
                alert("Cliente no registrado");
                $("#ModalNcliente").modal("show");
                $("#nruc").val($("#numero_documento").val());
              }
            }
          );
          $("#suggestions").fadeOut();
          $("#suggestions2").fadeOut();
          $("#suggestions3").fadeOut();
        } else {
          $("#idcliente").val("N");
          $("#razon_social").val("");
          document.getElementById("razon_social").placeholder = "No Registrado";
          $("#domicilio_fiscal").val("");
          document.getElementById("domicilio_fiscal").placeholder =
            "No Registrado";
          document.getElementById("btnAgregarArt").style.backgroundColor =
            "#35770c";
          document.getElementById("razon_social").style.Color = "#35770c";
          document.getElementById("razon_social").focus();
        }
      }
    );
  }
}

function agregarClientexDocCha() {
  var dni = $("#numero_documento").val();
  $("#razon_social").val("");
  $("#domicilio_fiscal").val("");

  $.post( "../ajax/notapedido.php?op=listarClientesNotaxDoc&doc=" + dni, function (data, status) {
      data = JSON.parse(data);
      if (data != null) {
        $("#idcliente").val(data.idpersona);
        $("#razon_social").val(data.nombres);
        $("#domicilio_fiscal").val(data.domicilio_fiscal);
        document.getElementById("btnAgregarArt").style.backgroundColor = "#367fa9";
        document.getElementById("mensaje700").style.display = "none";
        document.getElementById("btnAgregarArt").focus();
        $("#suggestions").fadeOut();
        $("#suggestions2").fadeOut();
        $("#suggestions3").fadeOut();
      } else if ($("#tipo_doc_ide").val() == "1") {
        // SI ES DNI
        $("#razon_social").val("");
        $("#domicilio_fiscal").val("");
        var dni = $("#numero_documento").val();
        console.log(dni);
        //var url = '../ajax/consulta_reniec.php';
        $.post(
          "../ajax/notapedido.php?op=consultaDniSunat&nrodni=" + dni,
          function (data, status) {
            data = JSON.parse(data);
            console.log(data);
            //swal.fire("Error","Nro DNI debe contener 8 digitos", "error");
            if (data != null) {
              $("#idcliente").val("N");

              console.log(data);
              //$("#numero_documento3").val(data.numeroDocumento);
              $("#razon_social").val(data.nombre);
              console.log(data.nombre);
              //swal.fire("Error","Datos no encontrados", "error");
            } else {
              swal.fire("Error", "Datos no encontrados", "error");
              //alert(data);
              console.log(data);
              document.getElementById("razon_social").focus();
              $("#idcliente").val("N");
            }
          }
        );
        $("#suggestions").fadeOut();
        $("#suggestions2").fadeOut();
        $("#suggestions3").fadeOut();
      } else if ($("#tipo_doc_ide").val() == "6") {
        // SI ES RUC
        $("#razon_social").val("");
        $("#domicilio_fiscal").val("");
        var dni = $("#numero_documento").val();
        $.post(
          "../ajax/factura.php?op=listarClientesfacturaxDoc&doc=" + dni,
          function (data, status) {
            data = JSON.parse(data);
            if (data != null) {
              $("#idcliente").val(data.idpersona);
              $("#razon_social").val(data.razon_social);
              $("#domicilio_fiscal").val(data.domicilio_fiscal);
            } else {
              $("#idcliente").val("");
              $("#razon_social").val("No registrado");
              $("#domicilio_fiscal").val("No registrado");
              Swal.fire({
                title: "Cliente no registrado",
                icon: "warning",
              });

              $("#ModalNcliente").modal("show");
              $("#nruc").val($("#numero_documento").val());
            }
          }
        );
        $("#suggestions").fadeOut();
        $("#suggestions2").fadeOut();
        $("#suggestions3").fadeOut();
      } else {
        $("#idcliente").val("N");
        $("#razon_social").val("");
        document.getElementById("razon_social").placeholder = "No Registrado";
        $("#domicilio_fiscal").val("");
        document.getElementById("domicilio_fiscal").placeholder =
          "No Registrado";
        document.getElementById("btnAgregarArt").style.backgroundColor =
          "#35770c";
        document.getElementById("razon_social").style.Color = "#35770c";
        document.getElementById("razon_social").focus();
      }
    }
  );
}

function agregarClientexDoc2() {
  var dni = $("#numero_documento").val();
  $("#razon_social").val("");
  $("#domicilio_fiscal").val("");

  $.post(
    "../ajax/notapedido.php?op=listarClientesNotaxDoc&doc=" + dni,
    function (data, status) {
      data = JSON.parse(data);
      if (data != null) {
        $("#idcliente").val(data.idpersona);
        $("#razon_social").val(data.nombres);
        $("#domicilio_fiscal").val(data.domicilio_fiscal);
        document.getElementById("btnAgregarArt").style.backgroundColor ="#367fa9";
        document.getElementById("mensaje700").style.display = "none";
        document.getElementById("btnAgregarArt").focus();
        $("#suggestions").fadeOut();
        $("#suggestions2").fadeOut();
        $("#suggestions3").fadeOut();
      } else if ($("#tipo_doc_ide").val() == "1") {
        // SI ES DNI
        $("#razon_social").val("");
        $("#domicilio_fiscal").val("");
        var dni = $("#numero_documento").val();
        //var url = '../ajax/consulta_reniec.php';
        $.post(
          "../ajax/notapedido.php?op=consultaDniSunat&nrodni=" + dni,
          function (data, status) {
            data = JSON.parse(data);
            if (data != null) {
              document.getElementById("razon_social").focus();
              $("#idcliente").val("N");
              //$("#numero_documento3").val(data.numeroDocumento);
              $("#razon_social").val(data.nombre);
            } else {
              alert(data);
              document.getElementById("razon_social").focus();
              $("#idcliente").val("N");
            }
          }
        );
        $("#suggestions").fadeOut();
        $("#suggestions2").fadeOut();
        $("#suggestions3").fadeOut();
      } else if ($("#tipo_doc_ide").val() == "6") {
        // SI ES RUC
        $("#razon_social").val("");
        $("#domicilio_fiscal").val("");
        var dni = $("#numero_documento").val();
        $.post("../ajax/factura.php?op=listarClientesfacturaxDoc&doc=" + dni,
          function (data, status) {
            data = JSON.parse(data);
            if (data != null) {
              $("#idcliente").val(data.idpersona);
              $("#razon_social").val(data.razon_social);
              $("#domicilio_fiscal").val(data.domicilio_fiscal);
            } else {
              $("#idcliente").val("");
              $("#razon_social").val("No registrado");
              $("#domicilio_fiscal").val("No registrado");
              alert("Cliente no registrado");
              $("#ModalNcliente").modal("show");
              $("#nruc").val($("#numero_documento").val());
            }
          }
        );
        $("#suggestions").fadeOut();
        $("#suggestions2").fadeOut();
        $("#suggestions3").fadeOut();
      } else {
        $("#idcliente").val("N");
        $("#razon_social").val("");
        $("#domicilio_fiscal").val("");
        document.getElementById("razon_social").placeholder            = "No Registrado";
        document.getElementById("domicilio_fiscal").placeholder        = "No Registrado";
        document.getElementById("btnAgregarArt").style.backgroundColor = "#35770c";
        document.getElementById("razon_social").style.Color            = "#35770c";
        document.getElementById("razon_social").focus();
      }
    }
  );
}

function guardaryeditarcliente(e) {
 e.preventDefault(); //No se activará la acción predeterminada del evento

 var formData = new FormData($("#formularioncliente")[0]);

 $.ajax({
   url: "../ajax/persona.php?op=guardaryeditarNclienteBoleta",
   type: "POST",
   data: formData,
   contentType: false,
   processData: false,
   success: function (datos) {
     // bootbox.alert(datos); 
     toastr_success('Correcto!!', datos);
     tabla.ajax.reload();
     limpiarcliente();
     agregarClientexRucNuevo();
   },
 });
 $("#ModalNcliente").modal("hide");
 $("#myModalCli").modal("hide");
}

function agregarClientexRucNuevo() {
 $.post( "../ajax/factura.php?op=listarClientesfacturaxDocNuevos",  function (data, status) {
   data = JSON.parse(data);

   if (data != null) {
     $("#numero_documento").val(data.numero_documento);
     $("#idcliente").val(data.idpersona);
     $("#razon_social").val(data.razon_social);
     $("#domicilio_fiscal").val(data.domicilio_fiscal);
     $("#tipo_documento_cliente").val(data.tipo_documento);
     document.getElementById("btnAgregarArt").style.backgroundColor =  "#367fa9";
     document.getElementById("btnAgregarArt").focus();
   } else {
     $("#idcliente").val("");
     $("#razon_social").val("No existe");
     $("#domicilio_fiscal").val("No existe");
     $("#tipo_documento_cliente").val("");
     document.getElementById("btnAgregarArt").style.backgroundColor = "#35770c";
     document.getElementById("btnAgregarCli").focus();
   }
 });
}

function focusDir(e) {
  if (e.keyCode === 13 && !e.shiftKey) {
    document.getElementById("domicilio_fiscal").focus();
  }
}

/* =========================                   ========================= <>
<> =========================  FIN DEL CLIENTE  ========================= <>
<> =========================                   ========================= */


//Función para anular registros
function enviarcorreo(idboleta) {
  bootbox.confirm(
    "¿Está Seguro de enviar correo al cliente?",
    function (result) {
      if (result) {
        $.post(
          "../ajax/notapedido.php?op=enviarcorreo",
          { idboleta: idboleta },
          function (e) {
            bootbox.alert(e);
            tabla.ajax.reload();
          }
        );
      }
    }
  );
}


function agregarArt(e) {
  if (e.keyCode === 13 && !e.shiftKey) {
    document.getElementById("btnAgregarArt").focus();
  }
}

function focusAgrArt(e) {
  if (e.keyCode === 13 && !e.shiftKey) {
    document.getElementById("btnAgregarArt").focus();
    document.getElementById("btnAgregarArt").style.backgroundColor = "#35770c";
  }
}

function focusTdoc() {
  document.getElementById("tipo_doc_ide").focus();
}

function stopRKey(evt) {
  var evt = evt ? evt : event ? event : null;
  var node = evt.target ? evt.target : evt.srcElement ? evt.srcElement : null;
  if (evt.keyCode == 13 && node.type == "text") {
    return false;
  }
}

//PARA ELIMINAR ENTER
document.onkeypress = stopRKey;

function capturarhora() {
  var f = new Date();
  cad = f.getHours() + ":" + f.getMinutes() + ":" + f.getSeconds();
  $("#hora").val(cad);
}

function actualizanorden() {
  var total = document.getElementsByName("numero_orden_item_29[]");

  for (var i = 0; i <= total.length; i++) {
    //var contNO=total[i];
    var contNO = total[i];
    contNO.value = i + 1;

    //Lineas abajo son para enviar el arreglo de inputs con los valor de IGV, Subtotal, y precio de venta
    document.getElementsByName("numero_orden")[i].innerHTML = contNO.value;
    document.getElementsByName("numero_orden_item_29[]")[i].value =
      contNO.value;
    //Fin de comentario
  } //Final de for
}

function actualizanordenP() {
  var total = document.getElementsByName("fecha[]");

  for (var i = 0; i <= total.length; i++) {
    //var contNO=total[i];
    var contNO2 = total[i];
    contNO2.value = i + 1;
  } //Final de for
}

function focusTest(el) {
  el.select();
}

$(document).ready(function () {
  var table = $("#tbllistado").DataTable();

  $("#tbllistado tbody").on("click", "tr", function () {
    if ($(this).hasClass("selected")) {
      $(this).removeClass("selected");
    } else {
      table.$("tr.selected").removeClass("selected");
      table.$("tr").removeClass("selected");
      $(this).addClass("selected");
    }
  });

  $("#tbllistado").parents("tr").css("background-color", "green");
  $("#button").click(function () {
    table.row(".selected").remove().draw(false);
  });
});

//Foco para el input cantidad
function focusDescdet(e, field) {
  if (e.keyCode === 13 && !e.shiftKey) {
    document.getElementById("cantidad_item_12[]").focus();
  }
}

function listarComprobantesClientes() {
  $dnicliente = $("#numero_documento").val();
  tablaArti = $("#detallesClientesEstado")
    .dataTable({
      aProcessing: true, //Activamos el procesamiento del datatables
      aServerSide: true, //Paginación y filtrado realizados por el servidor
      dom: "Bfrtip", //Definimos los elementos del control de tabla
      buttons: [],
      ajax: {
        url:
          "../ajax/notapedido.php?op=listarcomprobantesclientesEstado&dnicliente=" +
          $dnicliente,
        type: "get",
        dataType: "json",
        error: function (e) {
          console.log(e.responseText);
        },
      },
      bDestroy: true,
      iDisplayLength: 5, //Paginación
      order: [[0, "desc"]], //Ordenar (columna,orden)
    })
    .DataTable();
  $("#detallesClientesEstado").DataTable().ajax.reload();
}

function agregarClientesComprobantes(
  idnota,
  fecha,
  cliente,
  nroserie,
  total,
  estado
) {
  $est = "";
  var cantidad = 0;
  if (idnota != "") {
    var contador = 1;
    var fila2 =
      '<tr class="filas2" id="fila2' +
      cont2 +
      '">' +
      '<td><a onclick="eliminarDetalleComprobante(' +
      cont2 +
      ')" style="color:red;">X</a>' +
      '<input type="hidden" name="idnota[]" id="idnota[]" value="' +
      idnota +
      '"></td>' +
      '<td><input type="text" class=""  readonly name="fecha[]" id="fecha[]" value="' +
      fecha +
      '" style="display:none;"><span name="fecha2" id="fecha2' +
      cont2 +
      '">' +
      fecha +
      "</span></td> " +
      '<td><input type="text"  class="" name="cliente[]" id="cliente[]" value="' +
      cliente +
      '" readonly style="display:none;"><span name="cliente2" id="cliente2' +
      cont2 +
      '">' +
      cliente +
      "</span></td>" +
      '<td><input type="text"  class="" name="nroserie[]" id="nroserie[]" value="' +
      nroserie +
      '" readonly style="display:none;"><span name="nroserie2" id="nroserie2' +
      cont2 +
      '">' +
      nroserie +
      "</span></td>" +
      '<td><input type="text"  class="" name="totalcomp[]" id="totalcomp[]" value="' +
      total +
      '" readonly style="display:none;">' +
      '<span name="totaldeuda" id="totaldeuda' +
      cont2 +
      '">' +
      total +
      "</span></td>" +
      '<td><input type="text"  class="" name="estadoC[]" id="estadoC[]" value="' +
      $est +
      '" readonly style="display:none;">' +
      '<span name="estadoC" id="estadoC' +
      cont2 +
      '">CANCELADO</span></td>' +
      "</tr>";
    var id = document.getElementsByName("idnota[]");
    for (var i = 0; i < id.length; i++) {
      var idA = id[i];
      if (idA.value == idnota) {
        alert("Ya esta ingresado!");
        fila2 = "";
        cont2 = cont2 - 1;
        conNO2 = conNO2 - 1;
      } else {
        detalles2 = detalles2;
      }
    }
    detalles2 = detalles2 + 1;
    cont2++;
    conNO2++;

    $("#detallesClientesEstadoAgregados").append(fila2);
    modificarSubtotales();
  } else {
    alert("Error al ingresar el detalle, revisar los datos del artículo");
    cont2 = 0;
  }
}

function eliminarDetalleComprobante(indice) {
  $("#fila2" + indice).remove();
  calcularTotales();
  detalles2 = detalles2 - 1;
  conNO2 = conNO2 - 1;
  actualizanordenP();
}

function activartransferencia() {
  var tran_f = document.getElementById("transferencia").checked;
  if (tran_f == true) {
    $("#trans").val("1");
  } else {
    $("#trans").val("0");
  }
}

function cambiartransferencia(idboleta) {
  Swal.fire({
    title: "Desea modificar pago con transferencia",
    input: "select",
    inputOptions: {
      1: "SI",
      0: "NO",
    },
    inputValue: "1",
    showCancelButton: true,
    confirmButtonText: "Aceptar",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.value) {
      $.post(
        "../ajax/notapedido.php?op=cambiartransferencia&opcion=" + result.value,
        {
          idboleta: idboleta,
        },
        function (e) {
          Swal.fire({
            title: e,
            icon: "success",
          });
          tabla.ajax.reload();
        }
      );
    }
  });
}

function montotransferencia(idboleta) {
  swal
    .fire({
      title: "Desea modificar monto de transferencia",
      input: "number",
      showCancelButton: true,
      confirmButtonText: "OK",
      inputValue: "0",
      inputValidator: (value) => {
        return new Promise((resolve) => {
          if (value) {
            resolve();
          } else {
            resolve("Debe ingresar un valor");
          }
        });
      },
    })
    .then((result) => {
      if (result.value) {
        $.post(
          `../ajax/notapedido.php?op=montotransferencia&monto=${result.value}`,
          { idboleta: idboleta },
          function (e) {
            swal.fire({
              text: e,
              type: "success",
            });
            tabla.ajax.reload();
          }
        );
      }
    });
}

function refrescartabla() {
  // tablaArti.ajax.reload();
  tabla.ajax.reload();
}

function guardaryeditararticulo(e) {
  e.preventDefault(); //No se activará la acción predeterminada del evento

  var formData = new FormData($("#formularionarticulo")[0]);

  $.ajax({
    url: "../ajax/articulo.php?op=guardarnuevoarticulo",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
      bootbox.alert(datos);
      tabla.ajax.reload();
      refrescartabla();
      limpiarnuevoarticulo();
      //agregarClientexRucNuevo();
    },
  });

  $("#modalnuevoarticulo").modal("hide");
  $("#myModalArt").modal("show");

  //$("#myModalCli").modal('hide');
}

function nuevoarticulo() {
  $("#modalnuevoarticulo").modal("show");
}

function limpiarnuevoarticulo() {
  $("#nombrenarticulo").val("");
  $("#stocknarticulo").val("");
  $("#precioventanarticulo").val("");
  $("#codigonarticulonarticulo").val("");
  $("#descripcionnarticulo").val("");
}

$(function () {
  $("#myModalArt").on("shown.bs.modal", function (e) {
    $(".focus").focus();
  });
});

$(function () {
  $("#modalnuevoarticulo").on("shown.bs.modal", function (e) {
    $(".focus").focus();
  });
});


function cambiarlistado() {
  listarArticulos();
  // if (tablaArti) { tablaArti.ajax.reload(null, false); }
}

function cambiarlistadoum() {
  $("#itemno").val("1");

  listarArticulos();
  // if (tablaArti) { tablaArti.ajax.reload(null, false); }
}

function cambiarlistadoum2() {
  $("#itemno").val("0");

  listarArticulos();
  // if (tablaArti) { tablaArti.ajax.reload(null, false); }
}

function generarcodigonarti() {
  //alert("asdasdas");
  var caracteres1 = $("#nombrenarticulo").val();
  var codale = "";
  codale = caracteres1.substring(-3, 3);
  var caracteres2 = "ABCDEFGHJKMNPQRTUVWXYZ012346789";
  codale2 = "";
  for (i = 0; i < 3; i++) {
    var autocodigo = "";
    codale2 += caracteres2.charAt(
      Math.floor(Math.random() * caracteres2.length)
    );
  }
  $("#codigonarticulonarticulo").val(codale + codale2);
}

init();

function tipoimpresion() {
  $.post(
    "../ajax/notapedido.php?op=mostrarultimocomprobanteId",
    function (data, status) {
      data = JSON.parse(data);
      if (data != null) {
        $("#idultimocom").val(data.idboleta);
      } else {
        $("#idultimocom").val("");
      }

      if (data.tipoimpresion == "00") {
        var rutacarpeta =
          "../reportes/exNotapedidoTicket.php?id=" + data.idboleta;
        $("#modalCom").attr("src", rutacarpeta);
        $("#modalPreviewXml").modal("show");
      } else if (data.tipoimpresion == "01") {
        var rutacarpeta = "../reportes/exNotapedido.php?id=" + data.idboleta;
        $("#modalCom").attr("src", rutacarpeta);
        $("#modalPreviewXml").modal("show");
      } else {
        var rutacarpeta =
          "../reportes/exNotapedidocompleto.php?id=" + data.idboleta;
        $("#modalCom").attr("src", rutacarpeta);
        $("#modalPreview2").modal("show");
      }
    }
  );
}

$(function () {
  $("#myModalArt").on("shown.bs.modal", function (e) {
    $("div.dataTables_filter input").focus();
  });
});

function preticket2(idnotap) {
  var rutacarpeta = "../reportes/exNotapedidoTicket.php?id=" + idnotap;
  $("#modalComticket").attr("src", rutacarpeta);
  $("#modalPreviewticket").modal("show");
}

function nota2Hojas(idnotap) {
  var rutacarpeta = "../reportes/exNotapedido.php?id=" + idnotap;
  $("#modalCom2Hojas").attr("src", rutacarpeta);
  $("#modalPreview2Hojas").modal("show");
}

function printDiv(nombreDiv) {
  var contenido = document.getElementById(nombreDiv).innerHTML;
  var contenidoOriginal = document.body.innerHTML;

  document.body.innerHTML = contenido;

  window.print();

  document.body.innerHTML = contenidoOriginal;
}

// CREDITOS PENDIENTES
$("#basic-addon1").click(function () {
  var ccuotas = parseInt($("#ccuotasmodal").val());
  console.log(ccuotas);
  $("#divmontocuotas").empty();
  $("#divfechaspago").empty();

  for (var i = 0; i < ccuotas; i++) {
    $("#divmontocuotas").append(
      '<input type="text" name="montocuota[]" value="">'
    );

    var currentDate = new Date();
    currentDate.setMonth(currentDate.getMonth() + i);
    var formattedDate = currentDate.toISOString().split("T")[0];

    $("#divfechaspago").append(
      '<input type="date" name="fechapago[]" value="' + formattedDate + '">'
    );
  }
});

//llenar documentos
function cargarTiposDocIde() {
  $(".charge-doc-identidad").html(`<i class="fas fa-spinner fa-pulse"></i>`);

  var baseURL = window.location.protocol + '//' + window.location.host;

  // Verificar si pathname contiene '/vistas/' y eliminarlo.
  var path = window.location.pathname;
  if (path.includes("/vistas/")) {
    path = path.replace("/vistas/", "/");
  }

  var ajaxURL = new URL("ajax/catalogo6.php?action=listar2&op=", baseURL + path);

  $.ajax({
    url: ajaxURL.href,
    type: "GET",
    dataType: "json",
    success: function (data) {
      llenarSelect(data.aaData);
    },
    error: function (xhr, status, error) {
      console.error("Error al cargar los tipos de documento de identidad");
      console.error(error);
    },
  });
}

function llenarSelect(data) {
  var select = $("#tipo_doc_ide");
  select.empty();
  select.append( $("<option>", { value: "", text: "Tipo de documento", }) );
  $.each(data, function (index, value) {
    if (value.estado === "1") {
      select.append( $("<option>", { value: value.codigo, text: value.documento, }));
    }
  });
  $(".charge-doc-identidad").html(``);
}


$(document).ready(function () {
  cargarTiposDocIde();
});


function quitasuge2() {
  if ($("#razon_social").val() == "") {
    $("#suggestions2").fadeOut();
  }

  $("#suggestions2").fadeOut();
}

function quitasuge3() {
  // if ($('#codigob').val()=="")

  // {

  //  $('#suggestions3').fadeOut();

  // }

  $("#suggestions3").fadeOut();
}

function quitasuge1() {
  if ($("#numero_documento").val() == "") {
    $("#suggestions").fadeOut();
  }

  $("#suggestions").fadeOut();
}

function tributocodnon() {
  $("#codigo_tributo_h").val($("#codigo_tributo_18_3").val());
  $("#nombre_tributo_h").val($("#codigo_tributo_18_3 option:selected").text());
  //$(".filas").remove();
  tribD = $("#codigo_tributo_h").val();
  var id = document.getElementsByName("idarticulo[]");
  var codtrib = document.getElementsByName("codigotributo[]");
  var nombretrib = document.getElementsByName("afectacionigv[]");
  var cantiRe = document.getElementsByName("cantidadreal[]");

  if (tribD == "1000") {
    for (var i = 0; i < id.length; i++) {
      var codtrib2 = codtrib[i];
      var nombretrib2 = nombretrib[i];
      codtrib2.value = "1000";
      nombretrib2.value = "10";
      //cantiRe[i].value=cantidadreal;
    } //PARA VALIDACION SI YA ESTA INGRESADO EL ITEM
  } else if (tribD == "9997") {
    for (var i = 0; i < id.length; i++) {
      var codtrib2 = codtrib[i];
      var nombretrib2 = nombretrib[i];
      codtrib2.value = "9997";
      nombretrib2.value = "20";
    } //PARA VALIDACION SI YA ESTA INGRESADO EL ITEM
  } else {
    for (var i = 0; i < id.length; i++) {
      var codtrib2 = codtrib[i];
      var nombretrib2 = nombretrib[i];
      codtrib2.value = "9998";
      nombretrib2.value = "30";
    } //PARA VALIDACION SI YA ESTA INGRESADO EL ITEM
  }

  $("#subtotal").html("0");
  $("#subtotal_factura").val("");
  $("#igv_").html("0");
  $("#total_igv").val("");
  $("#total").html("0");
  $("#total_final").val("");
  $("#pre_v_u").val("");
  $("#ipagado").html("0");
  $("#saldo").html("0");
  $("#ipagado_final").val("");
  $("#saldo_saldo").val("");

  modificarSubtotales(0);
}

function agregarDetalleItem(
  idarticulo,

  familia,
  codigo_proveedor,
  codigo,
  nombre,

  precio_venta,
  stock,
  abre,

  precio_unitario,
  cicbper,
  mticbperu,
  factorconversion,
  factorc
) {
  $.post(
    "../ajax/notapedido.php?op=selectunidadmedida&idar=" + idarticulo,
    function (r) {
      $("#unidadm").html(r);

      //$('#unidadm').selectpicker('refresh');
    }
  );

  var cantidad = 0;

  if (idarticulo != "") {
    if (familia == "SERVICIO") {
      $("#icantidad").val("1");

      document.getElementById("iicbper2").disabled = true;

      document.getElementById("cicbper").disabled = true;

      document.getElementById("iimpicbper").disabled = true;
    }

    $("#nombrearti").val(nombre);

    $("#iiditem").val(idarticulo);

    $("#icodigo").val(codigo);

    $("#nombre").val(nombre);

    $("#familia").val(familia);

    $("#codigo_proveedor").val(codigo_proveedor);

    $("#stoc").val(stock);

    $("#factorcitem").val(factorc);

    $("#iumedida").val(abre);

    //$("#unidadm").val(abre);

    $("#ipunitario").val(precio_venta);

    $cantiitem = $("#icantidad").val();

    $valoruni = precio_venta / 1.18;

    $("#ivunitario").val($valoruni);

    $("#iicbper2").val(mticbperu);

    $("#cicbper").val(cicbper);

    $("#iimpicbper").val($cantiitem * $("#iicbper2").val());

    $("#myModalArt").modal("hide");

    //$("#myModalserv").modal('hide');

    $("#icantidad").val("1");

    $("#cantidadrealitem").val(factorconversion);

    $("#icantidad").focus();

    calculartotalitem();
  }

  $("#itemno").val("0");

  iit = $("#itemno").val();

  listarArticulos();
}

function focusnroreferencia() {
  document.getElementById("nroreferencia").focus();
}

function botonrapido1() {
  $("#ipagado").html(number_format(redondeo("1", 2), 2));
  ipag = $("#ipagado").html();
  itot = $("#total").html();
  if (parseFloat(itot) > parseFloat(ipag)) {
    Swal.fire({
      title: "Monto inferior al total",
      icon: "warning",
    });
    $("#ipagado").html("0.00");
    $("#saldo").html("0.00");
  } else {
    $("#saldo").html(
      number_format(redondeo(parseFloat(ipag) - parseFloat(itot), 2), 2)
    );
    $("#saldo_final").val(
      number_format(redondeo(parseFloat(ipag) - parseFloat(itot), 2), 2)
    );
  }
  $("#ipagado_final").val(ipag);
  $("#saldo_final").val(
    number_format(redondeo(parseFloat(ipag) - parseFloat(itot), 2), 2)
  );
}

function botonrapido2() {
  $("#ipagado").html(number_format(redondeo("2", 2), 2));

  ipag = $("#ipagado").html();

  itot = $("#total").html();

  if (parseFloat(itot) > parseFloat(ipag)) {
    Swal.fire({
      title: "Monto inferior al total",
      icon: "warning",
    });

    $("#ipagado").html("0.00");

    $("#saldo").html("0.00");
  } else {
    $("#saldo").html(
      number_format(redondeo(parseFloat(ipag) - parseFloat(itot), 2), 2)
    );
  }

  $("#ipagado_final").val(ipag);

  $("#saldo_final").val(
    number_format(redondeo(parseFloat(ipag) - parseFloat(itot), 2), 2)
  );
}

function botonrapido5() {
  $("#ipagado").html(number_format(redondeo("5", 2), 2));

  ipag = $("#ipagado").html();

  itot = $("#total").html();

  if (parseFloat(itot) > parseFloat(ipag)) {
    Swal.fire({
      title: "Monto inferior al total",
      icon: "warning",
    });

    $("#ipagado").html("0.00");

    $("#saldo").html("0.00");
  } else {
    $("#saldo").html(
      number_format(redondeo(parseFloat(ipag) - parseFloat(itot), 2), 2)
    );
  }

  $("#ipagado_final").val(ipag);

  $("#saldo_final").val(
    number_format(redondeo(parseFloat(ipag) - parseFloat(itot), 2), 2)
  );
}

function botonrapido10() {
  $("#ipagado").html(number_format(redondeo("10", 2), 2));

  ipag = $("#ipagado").html();

  itot = $("#total").html();

  if (parseFloat(itot) > parseFloat(ipag)) {
    Swal.fire({
      title: "Monto inferior al total",
      icon: "warning",
    });

    $("#ipagado").html("0.00");

    $("#saldo").html("0.00");
  } else {
    $("#saldo").html(
      number_format(redondeo(parseFloat(ipag) - parseFloat(itot), 2), 2)
    );
  }

  $("#ipagado_final").val(ipag);

  $("#saldo_final").val(
    number_format(redondeo(parseFloat(ipag) - parseFloat(itot), 2), 2)
  );
}

function botonrapido20() {
  $("#ipagado").html(number_format(redondeo("20", 2), 2));
  ipag = $("#ipagado").html();
  itot = $("#total").html();
  if (parseFloat(itot) > parseFloat(ipag)) {
    Swal.fire({
      title: "Monto inferior al total",
      icon: "warning",
    });
    $("#ipagado").html("0.00");
    $("#saldo").html("0.00");
  } else {
    $("#saldo").html(
      number_format(redondeo(parseFloat(ipag) - parseFloat(itot), 2), 2)
    );
  }
  $("#ipagado_final").val(ipag);
  $("#saldo_final").val(
    number_format(redondeo(parseFloat(ipag) - parseFloat(itot), 2), 2)
  );
}

function agregarMontoPer() {
  // Reemplazar el h6 con un input
  $("#ipagado").replaceWith('<input style="text-align: right;height: 2%;border: none; font-weight: bold;width: 70%;float: right;position: relative;/*! margin-right: ; */" type="text" step="0.01" min="0" id="ipagado_input" placeholder="0.00">');

  // Obtener el input y el total
  var ipag_input = $("#ipagado_input");
  var itot=$("#total").html();

  // Al cambiar el valor del input, actualizar el saldo
  ipag_input.on("input", function() {
      var ipag = parseFloat(ipag_input.val());
      var saldo = ipag - parseFloat(itot);
      $("#saldo").html(number_format(redondeo(saldo, 2), 2));
      $("#ipagado_final").val(number_format(redondeo(ipag, 2), 2));
      $("#saldo_final").val(number_format(redondeo(saldo, 2), 2));
  });

  // Enfocar el input para que sea más fácil ingresar el monto
  ipag_input.focus();

  $(window).on("beforeunload", function() {
    $("#ipagado_input").replaceWith('<h6 id="ipagado" class="d-inline">0.00</h6>');
  });
}

function botonrapido50() {
  $("#ipagado").html(number_format(redondeo("50", 2), 2));
  ipag = $("#ipagado").html();
  itot = $("#total").html();
  if (parseFloat(itot) > parseFloat(ipag)) {
    Swal.fire({
      title: "Monto inferior al total",
      icon: "warning",
    });
    $("#ipagado").html("0.00");
    $("#saldo").html("0.00");
  } else {
    $("#saldo").html(
      number_format(redondeo(parseFloat(ipag) - parseFloat(itot), 2), 2)
    );
  }
  $("#ipagado_final").val(ipag);
  $("#saldo_final").val(
    number_format(redondeo(parseFloat(ipag) - parseFloat(itot), 2), 2)
  );
}

function botonrapido100() {
  $("#ipagado").html(number_format(redondeo("100", 2), 2));
  ipag = $("#ipagado").html();
  itot = $("#total").html();
  if (parseFloat(itot) > parseFloat(ipag)) {
    Swal.fire({
      title: "Monto inferior al total",
      icon: "warning",
    });

    $("#ipagado").html("0.00");
    $("#saldo").html("0.00");
  } else {
    $("#saldo").html(
      number_format(redondeo(parseFloat(ipag) - parseFloat(itot), 2), 2)
    );
  }
  $("#ipagado_final").val(ipag);
  $("#saldo_final").val(
    number_format(redondeo(parseFloat(ipag) - parseFloat(itot), 2), 2)
  );
}

function botonrapido200() {
  $("#ipagado").html(number_format(redondeo("200", 2), 2));
  ipag = $("#ipagado").html();
  itot = $("#total").html();
  if (parseFloat(itot) > parseFloat(ipag)) {
    Swal.fire({
      title: "Monto inferior al total",
      icon: "warning",
    });

    $("#ipagado").html("0.00");
    $("#saldo").html("0.00");
  } else {
    $("#saldo").html(
      number_format(redondeo(parseFloat(ipag) - parseFloat(itot), 2), 2)
    );
  }
  $("#ipagado_final").val(ipag);
  $("#saldo_final").val(
    number_format(redondeo(parseFloat(ipag) - parseFloat(itot), 2), 2)
  );
}

/* ---------------------------------------------------- */
//                   CALCULAR VUELTO

function cleanNumber(value) {
  return parseFloat(value.replace(/,/g, ''));
}

$("#ipagado").on("input", function () {
  var importe_pagado = cleanNumber($(this).val());
  var total_pagar = cleanNumber($("#total").text());

  var vuelto;

  if (isNaN(importe_pagado)) {
    $("#vuelto_text").html("Vuelto : ");
    $("#saldo").text("0.00");
    return;
  }

  if (importe_pagado > total_pagar) {
    vuelto = (importe_pagado - total_pagar).toFixed(2);
    $("#vuelto_text").html("Vuelto : ");
    $("#saldo").text(vuelto).css("color", "green");
  } else {
    $("#vuelto_text").html(
      'Vuelto :  <span style="color: red; font-weight: bold">Falta</span>'
    );
    vuelto = (total_pagar - importe_pagado).toFixed(2);
    $("#saldo").text(vuelto).css("color", "red");
  }
});

function focusnroreferencia() {
  opttp = $("#tipopago").val();
  var x = document.getElementById("tipopagodiv");
  if (opttp == "Credito") {
    toFi = $("#total_final").val();
    cuo = $("#ccuotas").find("option:selected").text();
    $("#montocuota").val(toFi / cuo);
    document.getElementById("nroreferenciaf").focus();
  } else {
    $("#montocuota").val("0");
    $("#ccuotas").val("0");
  }
}

function contadocredito() {
  opttp = $("#tipopago").val();
  var x = document.getElementById("tipopagodiv");
  if (opttp == "Credito") {
    x.style.display = "block";
    $("#ccuotas").val("1");
    document.getElementById("fechavenc").readOnly = false;
    focusnroreferencia();
  } else {
    x.style.display = "none";
    $("#montocuota").val("0");
    $("#ccuotas").val("0");
    //focusnroreferencia();
    document.getElementById("divmontocuotas").innerHTML = "";
    document.getElementById("divfechaspago").innerHTML = "";
    document.getElementById("ccuotas").readOnly = false;

    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear() + "-" + month + "-" + day;
    $("#fechavenc").val(today);
    document.getElementById("fechavenc").readOnly = true;
  }
}

function contadocredito() {
  opttp = $("#tipopago").val();
  var x = document.getElementById("tipopagodiv");
  if (opttp == "Credito") {
    x.style.display = "block";
  } else {
    x.style.display = "none";
    $("#montocuota").val("0");
    $("#ccuotas").val("0");
  }
}


// ==================================                       ============================= <>
// ================================== FUNCIONES ADICIONALES ============================= <>
// ==================================                       ============================= <>


// Función para - poner ceros antes del numero siguiente
function pad(n, length) {
  var n = n.toString();
  while (n.length < length) n = "0" + n;
  return n;
}

// Función para - Actualizar Núnero de Nota Venta
function actualizarNum(e) {
 var numero = $("#numero_baucher").val();
 var idnumeracion = $("#serie option:selected").val();
 $.post("../ajax/notapedido.php?op=actualizarNumero&Num=" + numero + "&Idnumeracion=" + idnumeracion, function (r) {});
}

// Función para - Cerral Modal Articulo
function cerrarModal() {
 $("#myModalArt").modal("hide");
 $(".modal-backdrop").remove();
}

// Función para - Evaluar Botón  de Guardado V1
function evaluar() {
 if (detalles > 0) {
   $("#btnGuardar").show();
   //mayor700();
 } else {
   $("#btnGuardar").hide();
   cont = 0;
 }
}

// Función para - Evaluar Botón  de Guardado V2
function evaluar2() {
 if (detalles > 0) {
   $("#btnGuardar").hide();
   cont = 0;
 }
}

// Funció para - NOSE :)
function mayus(e) {
 e.value = e.value.toUpperCase();
}
