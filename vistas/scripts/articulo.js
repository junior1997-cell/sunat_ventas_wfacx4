var tabla_articulo;
var tabla_servicio;
var tablas;
var selectedValue = "productos";
var modoDemo = false;

toastr.options = {
  closeButton: false,
  debug: false,
  newestOnTop: false,
  progressBar: false,
  rtl: false,
  positionClass: 'toast-bottom-center',
  preventDuplicates: false,
  onclick: null
};

//var div = document.getElementById("masdatos");
//divT.style.visibility = "hidden";
//Función que se ejecuta al inicio

function init() {

  mostrarform(false);
  listar_filtros();  
  listarservicios();
  $idempresa = $("#idempresa").val();  

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════
  $.post("../ajax/articulo.php?op=selectMarca", function (r) { $("#idmarca").html(r); });
  $.post("../ajax/articulo.php?op=selectFamilia", function (r) { $("#idfamilia").html(r); });
  $.post("../ajax/articulo.php?op=selectAlmacen&idempresa=" + $idempresa, function (r) { $("#idalmacen").html(r); });
  $.post("../ajax/articulo.php?op=selectUnidad", function (r) { $("#unidad_medida").html(r); $("#umedidacompra").html(r); });

  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════
  $("#formulario").on("submit", function (e) { guardaryeditar(e); });
  $("#formnewfamilia").on("submit", function (e) { guardaryeditarFamilia(e); });
  $("#formnewalmacen").on("submit", function (e) { guardaryeditarAlmacen(e); });
  $("#formnewumedida").on("submit", function (e) { guardaryeditarUmedida(e); });
  $("#formprintbar").on("submit", function (e) { });

  // ══════════════════════════════════════ I N I T I A L I Z E   N U M B E R   F O R M A T ══════════════════════════════════════
  $("#filtro_idalmacen").select2({ dropdownParent: $('.card-header'), theme: "bootstrap4", placeholder: "Seleccione", allowClear: true,  });
  $("#filtro_idfamilia").select2({ dropdownParent: $('.card-header'), theme: "bootstrap4", placeholder: "Seleccione", allowClear: true,  });  
  $("#filtro_idmarca").select2({ dropdownParent: $('.card-header'), theme: "bootstrap4", placeholder: "Seleccione", allowClear: true,  });  

  $("#idmarca").select2({ dropdownParent: $('#modalAgregarProducto'),  theme: "bootstrap4", placeholder: "Seleccione", allowClear: true,  });
  $("#idfamilia").select2({ dropdownParent: $('#modalAgregarProducto'),  theme: "bootstrap4", placeholder: "Seleccione", allowClear: true,  });
  $("#umedidacompra").select2({ dropdownParent: $('#modalAgregarProducto'),  theme: "bootstrap4", placeholder: "Seleccione", allowClear: true,  });
  $("#unidad_medida").select2({ dropdownParent: $('#modalAgregarProducto'),  theme: "bootstrap4", placeholder: "Seleccione", allowClear: true,  });

  $("#imagenmuestra").hide();
}

function listar_filtros() {  

  $.post(`../ajax/articulo.php?op=filtros_table`, function (e, textStatus, jqXHR) {
    e = JSON.parse(e); //console.log(e);
    $("#filtro_idfamilia").html(e.filtro_categoria);
    $("#filtro_idalmacen").html(e.filtro_almacen);
    $("#filtro_idmarca").html(e.filtro_marca);
    listar_tabla_principal('todos', 'todos', 'todos');
  }).fail( function(e) { console.log(e); } );
}

//Función limpiar
function limpiar() {

  $("#idmarca").val("").trigger("change");
  $("#idfamilia").val("").trigger("change");
  $("#umedidacompra").val("").trigger("change");
  $("#unidad_medida").val("").trigger("change");

  $("#codigo").val("");
  $("#codigo_proveedor").val("-");
  $("#nombre").val("");
  $("#costo_compra").val("");
  $("#saldo_iniu").val("");
  $(".salini").val("9999999");
  $("#valor_iniu").val("0.00");
  $("#saldo_finu").val("");
  $(".salfin").val("9999999");
  $("#valor_finu").val("0.00");
  $("#stock").val("");
  $(".stokservicio").val("9999999");

  $("#comprast").val("0.00");
  $("#ventast").val("0.00");
  $("#portador").val("0.00");
  $("#merma").val("0.00");
  $("#valor_venta").val("");
  $("#imagenmuestra").attr("src", "../files/articulos/simagen.png");
  $("#imagenactual").val("");
  $("#print").hide();
  $("#idarticulo").val("");
  $("#Nnombre").val("");
  $("#codigosunat").val("");
  $("#ccontable").val("");
  $("#precio2").val("0.00");
  $("#precio3").val("0.00");

  //Nuevos campos
  $("#cicbper").val("");
  $("#nticbperi").val("");
  $("#ctticbperi").val("");
  $("#mticbperu").val("0.00");

  //Nuevos campos
  $("#lote").val("");
  $("#marca").val("");
  $("#fechafabricacion").val("");
  $("#fechavencimiento").val("");
  $("#procedencia").val("");
  $("#fabricante").val("");

  $("#registrosanitario").val("");
  $("#fechaingalm").val("");
  $("#fechafinalma").val("");
  $("#proveedor").val("");
  $("#seriefaccompra").val("");
  $("#numerofaccompra").val("");
  $("#fechafacturacompra").val("");

  $("#preview").empty();
  $("#limitestock").val("0.00");

  //$("#tipoitem").val("productos");
  $("#equivalencia").val("");
  $("#factorc").val("1.0");
  $("#fconversion").val("");
  $(".convumventa").val("9999999");
  $("#descripcion").val("");
  document.getElementById("btnGuardar").innerHTML = "Agregar";
  //document.getElementsByClassName("stokservicio")[0].value = "9999999";
}

//mostrar comrpas en registro
document.getElementById("agregarCompra").addEventListener("change", function () {
  var mostrarCompra = document.getElementById("mostrarCompra");
  if (this.checked) {
    mostrarCompra.style.display = "block";
  } else {
    mostrarCompra.style.display = "none";
  }
});

document.getElementById("agregarOtrosCampos").addEventListener("change", function () {
  var mostrarCompra = document.getElementById("mostraOtroscampos");
  if (this.checked) {
    mostrarCompra.style.display = "block";
  } else {
    mostrarCompra.style.display = "none";
  }
});

function limpiaralmacen() { $("#nombrea").val(""); }

function mostrarcampos() {
  var x = document.getElementById("chk1").checked;
  var div = document.getElementById("masdatos");
  if (x) {
    div.style.visibility = "visible";
  } else {
    div.style.visibility = "hidden";
  }
}

function limpiarcategoria() { $("#nombrec").val(""); }

function limpiarumedida() {
  $("#nombreu").val("");
  $("#abre").val("");
  $("#equivalencia2").val("");
}

//Función mostrar formulario
function mostrarform(flag) {
  limpiar();
  if (flag) {

    $("#listadoregistros").hide();
    $("#listadoregistrosservicios").hide();
    $("#formularioregistros").show();
    $("#btnGuardar").prop("disabled", false);
    $("#btnagregar").hide();
    $("#costo_compra").attr('readonly', false);
    $("#saldo_iniu").attr('readonly', false);
    $("#valor_iniu").attr('readonly', false);
    $("#saldo_finu").attr('readonly', false);
    $("#valor_finu").attr('readonly', false);
    $("#stock").attr('readonly', false);
    $("#comprast").attr('readonly', false);
    $("#ventast").attr('readonly', false);
    $("#preview").empty();
  } else {
    $("#listadoregistros").show();
    $("#listadoregistrosservicios").show();
    $("#formularioregistros").hide();
    $("#btnagregar").show();    
  }
}

//Función cancelarform
function cancelarform() { limpiar(); mostrarform(false);
}

//Función Listar PRODUCTOS
function listar_tabla_principal(idalmacen, idfamilia, idmarca) {
  
  tabla_articulo = $('#tbllistado').dataTable({
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom:"<'row'<'col-md-3'B><'col-md-3 float-left'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",//Definimos los elementos del control de tabla
    buttons: [
      { text: '<i class="fa-solid fa-arrows-rotate" data-toggle="tooltip" data-original-title="Recargar"></i> ', className: "btn bg-gradient-info m-r-5px", action: function ( e, dt, node, config ) { if (tabla_articulo) { tabla_articulo.ajax.reload(null, false); } } },
      { extend: 'copyHtml5', exportOptions: { columns: [1,2,3,4,5,6], }, text: `<i class="fas fa-copy" data-toggle="tooltip" data-original-title="Copiar"></i>`, className: "btn bg-gradient-gray m-r-5px", footer: true,  }, 
      { extend: 'excelHtml5', exportOptions: { columns: [1,2,3,4,5,6], }, text: `<i class="far fa-file-excel fa-lg" data-toggle="tooltip" data-original-title="Excel"></i>`, className: "btn bg-gradient-success m-r-5px", footer: true,  }, 
      { extend: 'pdfHtml5', exportOptions: { columns: [1,2,3,4,5,6], }, text: `<i class="far fa-file-pdf fa-lg" data-toggle="tooltip" data-original-title="PDF"></i>`, className: "btn bg-gradient-danger m-r-5px", footer: false, orientation: 'landscape', pageSize: 'LEGAL',  },
      { extend: "colvis", text: `<i class="fas fa-outdent"></i>`, className: "btn bg-gradient-gray", exportOptions: { columns: "th:not(:last-child)", }, },
    ],
    "ajax": {
      url: `../ajax/articulo.php?op=tbla_principal&idalmacen=${idalmacen}&idfamilia=${idfamilia}&idmarca=${idmarca}`,
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText);
      },
      beforeSend: function () {  },
      complete: function () { $('.cargando').hide(); }
    },
    language: {
      lengthMenu: "Mostrar: _MENU_ registros",
      buttons: { copyTitle: "Tabla Copiada", copySuccess: { _: "%d líneas copiadas", 1: "1 línea copiada", }, },
      sLoadingRecords: '<i class="fas fa-spinner fa-pulse fa-lg"></i> Cargando datos...'
    },
    "bDestroy": true,
    "iDisplayLength": 10,//Paginación
    "order": [[4, "desc"]]//Ordenar (columna,orden)
  }).DataTable();
}

function listarservicios() {
  var $idempresa = $("#idempresa").val();
  tabla_servicio = $('#tbllistadoservicios').dataTable({
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom:"<'row'<'col-md-3'B><'col-md-3 float-left'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",//Definimos los elementos del control de tabla
    buttons: [
      { text: '<i class="fa-solid fa-arrows-rotate" data-toggle="tooltip" data-original-title="Recargar"></i> ', className: "btn bg-gradient-info m-r-5px", action: function ( e, dt, node, config ) { if (tabla_servicio) { tabla_servicio.ajax.reload(null, false); } } },
      { extend: 'copyHtml5', exportOptions: { columns: [2,3,4], }, text: `<i class="fas fa-copy" data-toggle="tooltip" data-original-title="Copiar"></i>`, className: "btn bg-gradient-gray m-r-5px", footer: true,  }, 
      { extend: 'excelHtml5', exportOptions: { columns: [2,3,4], }, text: `<i class="far fa-file-excel fa-lg" data-toggle="tooltip" data-original-title="Excel"></i>`, className: "btn bg-gradient-success m-r-5px", footer: true,  }, 
      { extend: 'pdfHtml5', exportOptions: { columns: [2,3,4], }, text: `<i class="far fa-file-pdf fa-lg" data-toggle="tooltip" data-original-title="PDF"></i>`, className: "btn bg-gradient-danger m-r-5px", footer: false, orientation: 'landscape', pageSize: 'LEGAL',  },
      { extend: "colvis", text: `<i class="fas fa-outdent"></i>`, className: "btn bg-gradient-gray", exportOptions: { columns: "th:not(:last-child)", }, },
    ],
    "ajax": {
      url: '../ajax/articulo.php?op=listarservicios&idempresa=' + $idempresa,
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
    "iDisplayLength": 10,//Paginación
    "order": [[4, "desc"]]//Ordenar (columna,orden)
  }).DataTable();
}

//Función para guardar o editar
function guardaryeditar(e) {
  e.preventDefault(); //No se activará la acción predeterminada del evento
  $("#btnGuardar").prop("disabled", true);
  var formData = new FormData($("#formulario")[0]);

  $.ajax({
    url: "../ajax/articulo.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (datos) {
      Swal.fire({  icon: 'success',  title: 'Guardado exitoso',  showConfirmButton: false, timer: 5000, text: datos, });
      mostrarform(false);
      if (tabla_articulo) { tabla_articulo.ajax.reload(null, false); }
      if (tabla_servicio) {  tabla_servicio.ajax.reload(null, false); }
              
      
      limpiar();
      $("#modalAgregarProducto").modal("hide");
    },
    error: function () {
      Swal.fire({ icon: 'error', title: 'Error al guardar', text: 'Ha ocurrido un error al guardar los datos', });
    }
  });
}

function mostrar(idarticulo) {
  mostrarform(true);
  $.post("../ajax/articulo.php?op=mostrar", { idarticulo: idarticulo }, function (data, status) {
    data = JSON.parse(data);   
    console.log(data);
    $("#idarticulo").val(data.idarticulo);
    $("#idalmacen").val(data.idalmacen);
    $("#codigo_proveedor").val(data.codigo_proveedor);

    $("#idmarca").val(data.idmarca).trigger("change");
    $("#idfamilia").val(data.idfamilia).trigger("change");
    $("#umedidacompra").val(data.umedidacompra).trigger("change");
    $("#unidad_medida").val(data.unidad_medida).trigger("change");
    
    $("#nombre").val(data.nombre);
    $("#costo_compra").val(data.costo_compra);
    //$("#costo_compra").attr('readonly', true);
    $("#saldo_iniu").val(data.saldo_iniu);
    //$("#saldo_iniu").attr('readonly', true);
    $("#valor_iniu").val(data.valor_iniu);
    //$("#valor_iniu").attr('readonly', true);
    $("#saldo_finu").val(data.saldo_finu);
    //$("#saldo_finu").attr('readonly', true);
    $("#valor_finu").val(data.valor_finu);
    //$("#valor_finu").attr('readonly', true);
    $("#stock").val(data.stock);
    //$("#stock").attr('readonly', true);
    $("#comprast").val(data.comprast);
    //$("#comprast").attr('readonly', true);
    $("#ventast").val(data.ventast);
    //$("#ventast").attr('readonly', true);
    $("#portador").val(data.portador);
    $("#merma").val(data.merma);
    $("#valor_venta").val(data.precio_venta);
    $("#imagenmuestra").show();

    if (data.imagen == "" || data.imagen == null) {
      $("#imagenmuestra").attr("src", "../files/articulos/simagen.png");
      $("#imagenactual").val("");
      $("#imagen").val("");
    } else {
      $("#imagenmuestra").attr("src", "../files/articulos/" + data.imagen);
      $("#imagenactual").val(data.imagen);
      $("#imagen").val("");
    }

    $("#codigosunat").val(data.codigosunat);
    $("#ccontable").val(data.ccontable);
    $("#precio2").val(data.precio2);
    $("#precio3").val(data.precio3);

    $("#stockprint").val(data.stock);
    $("#codigoprint").val(data.codigo);
    $("#precioprint").val(data.precio_venta);

    //Nuevos campos
    $("#cicbper").val(data.cicbper);
    $("#nticbperi").val(data.nticbperi);
    $("#ctticbperi").val(data.ctticbperi);
    $("#mticbperu").val(data.mticbperu);

    //Nuevos campos
    $("#codigott").val(data.codigott);
    //$('#codigott').selectpicker('refresh');
    $("#desctt").val(data.desctt);
    //$('#desctt').selectpicker('refresh');
    $("#codigointtt").val(data.codigointtt);
    //$('#codigointtt').selectpicker('refresh');
    $("#nombrett").val(data.nombrett);
    //$('#nombrett').selectpicker('refresh');

    //Nuevos campos
    $("#lote").val(data.lote);
    $("#marca").val(data.marca);
    $("#fechafabricacion").val(data.fechafabricacion);
    $("#fechavencimiento").val(data.fechavencimiento);
    $("#procedencia").val(data.procedencia);
    $("#fabricante").val(data.fabricante);
    $("#registrosanitario").val(data.registrosanitario);
    $("#fechaingalm").val(data.fechaingalm);
    $("#fechafinalma").val(data.fechafinalma);
    $("#proveedor").val(data.proveedor);
    $("#seriefaccompra").val(data.seriefaccompra);
    $("#numerofaccompra").val(data.numerofaccompra);
    $("#fechafacturacompra").val(data.fechafacturacompra);
    $("#limitestock").val(data.limitestock);
    $("#tipoitem").val(data.tipoitem);

    $("#factorc").val(data.factorc);
    $("#descripcion").val(data.descrip);

    var stt = $("#stock").val();
    var fc = $("#factorc").val();
    var stfc = stt * fc;
    $("#fconversion").val(stfc);

    $("#codigo").val(data.codigo); console.log(data.codigo);

    //Nuevos campos
    $('#modalAgregarProducto').modal('show');
    //$('#modalAgregarServicio').modal('show');
    document.getElementById("btnGuardar").innerHTML = "Actualizar";

    // generarbarcode();
  });
}

//Función para desactivar registros
function desactivar(idarticulo) {
  Swal.fire({
    title: '¿Está Seguro de Desactivar el Artículo?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sí, activar',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/articulo.php?op=desactivar", { idarticulo: idarticulo }, function (e) {
        Swal.fire({ title: 'Artículo activado', text: e, icon: 'success', showConfirmButton: false, timer: 5000 });
        if (tabla_articulo) { tabla_articulo.ajax.reload(null, false); }
        if (tabla_servicio) {  tabla_servicio.ajax.reload(null, false); }
      });
    }
  });  
}

//Función para activar registros
function activar(idarticulo) {
  Swal.fire({
    title: '¿Está Seguro de activar el Artículo?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sí, activar',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/articulo.php?op=activar", { idarticulo: idarticulo }, function (e) {
        Swal.fire({ title: 'Artículo activado', text: e, icon: 'success', showConfirmButton: false, timer: 5000 });
        if (tabla_articulo) { tabla_articulo.ajax.reload(null, false); }
        if (tabla_servicio) {  tabla_servicio.ajax.reload(null, false); }        
      });
    }
  });
}

//función para generar el código de barras
function generarbarcode() {
  // codigo = $("#codigo").val();
  // descrip=$("#nombre").val();
  // unidadm=$("#unidad_medida").val();
  // codigof=codigo.concat(descrip, unidadm);

  // JsBarcode("#barcode", codigo, { format: "code128", });
  // $("#print").show();
}

//Función para imprimir el Código de barras
function imprimir() { $("#print").printArea(); }

function calcula_valor_ini() {
  costo_compra = $("#costo_compra").val();
  saldo_iniu = $("#saldo_iniu").val();
  resu = costo_compra * saldo_iniu;
  $("#valor_iniu").val(resu.toFixed(2));
  $("#saldo_finu").val(saldo_iniu);
}

$("#stock").change(function () {
  var stockValue = $(this).val();
  $("#saldo_iniu").val(stockValue);
  $("#saldo_finu").val(stockValue);
  $("#fconversion").val(stockValue);
});

function sfinalstock() {
  sf = $("#saldo_finu").val();
  $("#stock").val(sf);
}

// var valorInicial;
//  document.getElementById("igv").addEventListener("change", function(){
//    if(this.checked){
//      valorInicial = document.getElementById("valor_venta").value;
//      var valorVenta = parseFloat(valorInicial);
//      var valorIgv = (valorVenta * 18) / 100;
//      document.getElementById("valor_venta").value = valorIgv + valorVenta;
//    } else {
//      document.getElementById("valor_venta").value = valorInicial;
//    }
//  });


function mayus(e) { e.value = e.value.toUpperCase(); }

function guardaryeditarFamilia(e) {
  e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#formnewfamilia")[0]);
  $.ajax({
    url: "../ajax/familia.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (datos) {
      bootbox.alert(datos);
      $("#Nnombre").val("");
      if (tabla_articulo) { tabla_articulo.ajax.reload(null, false); }
      if (tabla_servicio) {  tabla_servicio.ajax.reload(null, false); }
      actfamilia();
    }
  });
  limpiarcategoria();
  $("#ModalNfamilia").modal('hide');
}

function guardaryeditarAlmacen(e) {
  e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#formnewalmacen")[0]);
  $.ajax({
    url: "../ajax/familia.php?op=guardaryeditaralmacen",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (datos) {
      bootbox.alert(datos);
      if (tabla_articulo) { tabla_articulo.ajax.reload(null, false); }
      if (tabla_servicio) {  tabla_servicio.ajax.reload(null, false); }
      actalmacen();
    }
  });
  limpiaralmacen();
  $("#ModalNalmacen").modal('hide');
}

function guardaryeditarUmedida(e) {
  e.preventDefault(); //No se activará la acción predeterminada del evento
  var formData = new FormData($("#formnewumedida")[0]);
  $.ajax({
    url: "../ajax/familia.php?op=guardaryeditarUmedida",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (datos) {
      bootbox.alert(datos);
      if (tabla_articulo) { tabla_articulo.ajax.reload(null, false); }
      if (tabla_servicio) {  tabla_servicio.ajax.reload(null, false); }
      actalunidad();
    }
  });
  limpiarumedida();
  $("#ModalNumedida").modal('hide');
}

function actfamilia() {
  $.post("../ajax/articulo.php?op=selectFamilia", function (r) { $("#idfamilia").html(r); });
}

//Cargamos los items al select almacen
function actalmacen() {
  $idempresa = $("#idempresa").val();
  $.post("../ajax/articulo.php?op=selectAlmacen&idempresa=" + $idempresa, function (r) { $("#idalmacen").html(r); });
}
//Cargamos los items al select almacen
function actalunidad() {
  $.post("../ajax/articulo.php?op=selectUnidad", function (r) { $("#unidad_medida").html(r); $("#umedidacompra").html(r); });

}

document.onkeypress = stopRKey;

function stopRKey(evt) {
  var evt = (evt) ? evt : ((event) ? event : null);
  var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
  if ((evt.keyCode == 13) && (node.type == "text")) { return false; }
}

function focusfamil() { document.getElementById('idfamilia').focus(); }
function tipoitem() { document.getElementById('tipoitem').focus(); }
function focuscodprov() {
  //document.getElementById('codigo_proveedor').focus();
  selectedValue = document.getElementById("tipoitem").value;
}

function focusnomb(e, field) {
  if (e.keyCode === 13 && !e.shiftKey) { document.getElementById('nombre').focus(); }
}

function focusum(e, field) {
 // if (e.keyCode === 13 && !e.shiftKey) { document.getElementById('unidad_medida').focus(); }
}

function limitestockf(e, field) {
  if (e.keyCode === 13 && !e.shiftKey) { document.getElementById('limitestock').focus(); }
}

function costoco() {
  //idun= $('#unidad_medida').val();
  //$.post("../ajax/articulo.php?op=mostrarequivalencia&iduni="+idun, function(data,status){
  // data=JSON.parse(data);
  //$('#factorc').val(data.equivalencia);
  //});
  document.getElementById('factorc').focus();
}

function umventa(e, field) {
  //if (e.keyCode === 13 && !e.shiftKey) {document.getElementById('unidad_medida').focus(); }
}

function cinicial() { document.getElementById('factorc').focus(); }

//Función para aceptar solo numeros con dos decimales
function focussaldoi(e, field) {
  // Backspace = 8, Enter = 13, ’0′ = 48, ’9′ = 57, ‘.’ = 46
  key = e.keyCode ? e.keyCode : e.which
  if (e.keyCode === 13 && !e.shiftKey) { document.getElementById('saldo_iniu').focus(); }
  // backspace
  if (key == 8) return true;
  if (key == 9) return true;
  if (key > 47 && key < 58) {
    if (field.value === "") return true;
    var existePto = (/[.]/).test(field.value);
    if (existePto === false) {
      regexp = /.[0-9]{10}$/;
    } else {
      regexp = /.[0-9]{2}$/;
    }
    return !(regexp.test(field.value));
  }

  if (key == 46) {
    if (field.value === "") return false;
    regexp = /^[0-9]+$/;
    return regexp.test(field.value);
  }
  return false;
}

function valori(e, field) {

  // Backspace = 8, Enter = 13, ’0′ = 48, ’9′ = 57, ‘.’ = 46
  key = e.keyCode ? e.keyCode : e.which
  if (e.keyCode === 13 && !e.shiftKey) {
    document.getElementById('valor_iniu').focus();
  }

  // backspace
  if (key == 8) return true;
  if (key == 9) return true;
  if (key > 47 && key < 58) {
    if (field.val() === "") return true;
    var existePto = (/[.]/).test(field.val());
    if (existePto === false) {
      regexp = /.[0-9]{10}$/;
    } else {
      regexp = /.[0-9]{2}$/;
    }
    return !(regexp.test(field.val()));
  }

  if (key == 46) {
    if (field.val() === "") return false;
    regexp = /^[0-9]+$/;
    return regexp.test(field.val());
  }
  return false;
}

function saldof(e, field) {
  // Backspace = 8, Enter = 13, ’0′ = 48, ’9′ = 57, ‘.’ = 46
  key = e.keyCode ? e.keyCode : e.which
  if (e.keyCode === 13 && !e.shiftKey) { document.getElementById('saldo_finu').focus(); }

  // backspace
  if (key == 8) return true;
  if (key == 9) return true;
  if (key > 47 && key < 58) {
    if (field.val() === "") return true;
    var existePto = (/[.]/).test(field.val());
    if (existePto === false) {
      regexp = /.[0-9]{10}$/;
    } else {
      regexp = /.[0-9]{2}$/;
    }
    return !(regexp.test(field.val()));
  }

  if (key == 46) {
    if (field.val() === "") return false;
    regexp = /^[0-9]+$/;
    return regexp.test(field.val());
  }
  return false;
}

function valorf(e, field) {
  // Backspace = 8, Enter = 13, ’0′ = 48, ’9′ = 57, ‘.’ = 46
  key = e.keyCode ? e.keyCode : e.which
  if (e.keyCode === 13 && !e.shiftKey) {
    document.getElementById('valor_finu').focus();
  }

  // backspace
  if (key == 8) return true;
  if (key == 9) return true;
  if (key > 47 && key < 58) {
    if (field.val() === "") return true;
    var existePto = (/[.]/).test(field.val());
    if (existePto === false) {
      regexp = /.[0-9]{10}$/;
    } else {
      regexp = /.[0-9]{2}$/;
    }
    return !(regexp.test(field.val()));
  }

  if (key == 46) {
    if (field.val() === "") return false;
    regexp = /^[0-9]+$/;
    return regexp.test(field.val());
  }
  return false;
}



function st(e, field) {

  // Backspace = 8, Enter = 13, ’0′ = 48, ’9′ = 57, ‘.’ = 46

  key = e.keyCode ? e.keyCode : e.which



  if (e.keyCode === 13 && !e.shiftKey) {

    document.getElementById('stock').focus();

  }

  // backspace

  if (key == 8) return true;

  if (key == 9) return true;

  if (key > 47 && key < 58) {

    if (field.val() === "") return true;

    var existePto = (/[.]/).test(field.val());

    if (existePto === false) {

      regexp = /.[0-9]{10}$/;

    }

    else {

      regexp = /.[0-9]{2}$/;

    }

    return !(regexp.test(field.val()));

  }



  if (key == 46) {

    if (field.val() === "") return false;

    regexp = /^[0-9]+$/;

    return regexp.test(field.val());

  }

  return false;

}



function totalc(e, field) {

  // Backspace = 8, Enter = 13, ’0′ = 48, ’9′ = 57, ‘.’ = 46

  key = e.keyCode ? e.keyCode : e.which

  if (e.keyCode === 13 && !e.shiftKey) {
    document.getElementById('valor_venta').focus();
    var stt = document.getElementById("stock").value;
    var fc = document.getElementById("factorc").value;
    var stfc = stt * fc;
    document.getElementById("fconversion").value = stfc;

  }

  // backspace

  if (key == 8) return true;

  if (key == 9) return true;

  if (key > 47 && key < 58) {

    if (field.value === "") return true;

    var existePto = (/[.]/).test(field.value);

    if (existePto === false) {

      regexp = /.[0-9]{10}$/;

    }

    else {

      regexp = /.[0-9]{2}$/;

    }

    return !(regexp.test(field.value));

  }



  if (key == 46) {

    if (field.value === "") return false;

    regexp = /^[0-9]+$/;

    return regexp.test(field.value);

  }

  return false;


}




function totalv(e, field) {

  // Backspace = 8, Enter = 13, ’0′ = 48, ’9′ = 57, ‘.’ = 46

  key = e.keyCode ? e.keyCode : e.which



  if (e.keyCode === 13 && !e.shiftKey) {

    document.getElementById('ventast').focus();

  }

  // backspace

  if (key == 8) return true;

  if (key == 9) return true;

  if (key > 47 && key < 58) {

    if (field.val() === "") return true;

    var existePto = (/[.]/).test(field.val());

    if (existePto === false) {

      regexp = /.[0-9]{10}$/;

    }

    else {

      regexp = /.[0-9]{2}$/;

    }

    return !(regexp.test(field.val()));

  }



  if (key == 46) {

    if (field.val() === "") return false;

    regexp = /^[0-9]+$/;

    return regexp.test(field.val());

  }

  return false;

}



function porta(e, field) {

  // Backspace = 8, Enter = 13, ’0′ = 48, ’9′ = 57, ‘.’ = 46

  key = e.keyCode ? e.keyCode : e.which



  if (e.keyCode === 13 && !e.shiftKey) {

    document.getElementById('portador').focus();

  }

  // backspace

  if (key == 8) return true;

  if (key == 9) return true;

  if (key > 47 && key < 58) {

    if (field.val() === "") return true;

    var existePto = (/[.]/).test(field.val());

    if (existePto === false) {

      regexp = /.[0-9]{10}$/;

    }

    else {

      regexp = /.[0-9]{2}$/;

    }

    return !(regexp.test(field.val()));

  }



  if (key == 46) {

    if (field.val() === "") return false;

    regexp = /^[0-9]+$/;

    return regexp.test(field.val());

  }

  return false;

}



function mer(e, field) {

  // Backspace = 8, Enter = 13, ’0′ = 48, ’9′ = 57, ‘.’ = 46

  key = e.keyCode ? e.keyCode : e.which



  if (e.keyCode === 13 && !e.shiftKey) {

    document.getElementById('merma').focus();

  }

  // backspace

  if (key == 8) return true;

  if (key == 9) return true;

  if (key > 47 && key < 58) {

    if (field.value === "") return true;

    var existePto = (/[.]/).test(field.value);

    if (existePto === false) {

      regexp = /.[0-9]{10}$/;

    }

    else {

      regexp = /.[0-9]{2}$/;

    }

    return !(regexp.test(field.value));

  }



  if (key == 46) {

    if (field.value === "") return false;

    regexp = /^[0-9]+$/;

    return regexp.test(field.value);

  }

  return false;

}



function preciov(e, field) {

  // Backspace = 8, Enter = 13, ’0′ = 48, ’9′ = 57, ‘.’ = 46

  key = e.keyCode ? e.keyCode : e.which

  if (e.keyCode === 13 && !e.shiftKey) {  document.getElementById('valor_venta').focus(); }
  // backspace
  if (key == 8) return true;
  if (key == 9) return true;
  if (key > 47 && key < 58) {
    if (field.value === "") return true;
    var existePto = (/[.]/).test(field.value);
    if (existePto === false) { regexp = /.[0-9]{10}$/; } else {  regexp = /.[0-9]{2}$/;  }
    return !(regexp.test(field.value));
  }

  if (key == 46) {
    if (field.value === "") return false;
    regexp = /^[0-9]+$/;
    return regexp.test(field.value);
  }
  return false;
}

function limitest(e, field) {
  // Backspace = 8, Enter = 13, ’0′ = 48, ’9′ = 57, ‘.’ = 46
  key = e.keyCode ? e.keyCode : e.which
  //if (e.keyCode === 13 && !e.shiftKey) {document.getElementById('unidad_medida').focus(); }
  // backspace
  if (key == 8) return true;
  if (key == 9) return true;
  if (key > 47 && key < 58) {
    if (field.value === "") return true;
    var existePto = (/[.]/).test(field.value);
    if (existePto === false) {
      regexp = /.[0-9]{10}$/;
    } else {
      regexp = /.[0-9]{2}$/;
    }
    return !(regexp.test(field.value));
  }

  if (key == 46) {
    if (field.value === "") return false;
    regexp = /^[0-9]+$/;
    return regexp.test(field.value);
  }

  return false;
}

function codigoi(e, field) {
  // Backspace = 8, Enter = 13, ’0′ = 48, ’9′ = 57, ‘.’ = 46
  key = e.keyCode ? e.keyCode : e.which
  if (e.keyCode === 13 && !e.shiftKey) {  document.getElementById('codigo').focus(); }

  // backspace
  if (key == 8) return true;
  if (key == 9) return true;
  if (key > 47 && key < 58) {
    if (field.value === "") return true;
    var existePto = (/[.]/).test(field.value);
    if (existePto === false) {
      regexp = /.[0-9]{10}$/;
    } else {
      regexp = /.[0-9]{2}$/;
    }
    return !(regexp.test(field.value));
  }

  if (key == 46) {
    if (field.value === "") return false;
    regexp = /^[0-9]+$/;
    return regexp.test(field.value);
  }
  return false;
}

$(".modal-wide").on("show.bs.modal", function () {
  var height = $(window).height() - 200;
  $(this).find(".modal-body").css("max-height", height);
});

function unidadvalor() { valor = $("#nombreu").val(); $("#abre").val(valor);}

function refrescartabla() { 
  if (tabla_articulo) { tabla_articulo.ajax.reload(null, false); }     
  if (tabla_servicio) {  tabla_servicio.ajax.reload(null, false); }
}

document.getElementById("imagen").onchange = function (e) {
  // Creamos el objeto de la clase FileReader
  let reader = new FileReader();
  // Leemos el archivo subido y se lo pasamos a nuestro fileReader
  reader.readAsDataURL(e.target.files[0]);
  // Le decimos que cuando este listo ejecute el código interno
  reader.onload = function () {
    // let preview = document.getElementById('preview'), image = document.createElement('img');
    // image.src = reader.result;
    // image.width = "50";
    // image.height = "50";
    // preview.innerHTML = '';
    // preview.append(image);
    toastr.success('Imagen cargada');
    $('#imagenmuestra').attr('src', reader.result);
    $("#imagenmuestra").show();
  };

}
// :::::::::::::::::::::::::: NO SE USA :::::::::::::::::::::::
function mostrarequivalencia() {
  idun = $('#unidad_medida').val();
  $.post("../ajax/articulo.php?op=mostrarequivalencia&iduni=" + idun, function (data, status) {
    data = JSON.parse(data);
    $('#equivalencia').val(data.equivalencia);
  });
}

function validarcodigo() {
  cod = $('#codigo').val();
  $.post("../ajax/articulo.php?op=validarcodigo&cdd=" + cod, function (data, status) {
    data = JSON.parse(data);
    if (data && data.codigo == cod) {
      alert("código Existe, debe cambiarlo");
      document.getElementById('codigo').focus();
    }
  });
}

function generarcodigonarti() {
  //alert("asdasdas");
  var caracteres1 = $("#nombre").val();
  var codale = "";
  codale = caracteres1.substring(-3, 3);
  var caracteres2 = "ABCDEFGHJKMNPQRTUVWXYZ012346789";
  codale2 = "";
  for (i = 0; i < 3; i++) {
    var autocodigo = "";
    codale2 += caracteres2.charAt(Math.floor(Math.random() * caracteres2.length));
  }
  $("#codigo").val(codale + codale2);
}

function generarCodigoAutomatico(i_cod) {
  if ($('#generar-cod-correlativo').prop('checked')) {
    $.getJSON('../ajax/articulo.php?action=GenerarCodigo&op=',{i_cod:i_cod}, function (data) {
      $('#codigo').val(data.codigo);
      setCodigoFieldReadonly();  // Asegura que el campo esté como solo lectura
    });
  } else {
    $('#codigo').removeAttr('readonly');
  }
}

// $('#modalAgregarProducto').on('shown.bs.modal', function (e) { generarCodigoAutomatico(); });

if (localStorage.getItem("checkboxState") === "checked") { $('#generar-cod-correlativo').prop('checked', true); }

$('label.toggle-switch').on('mousedown', function (e) {
  var checkbox = $('#generar-cod-correlativo');

  // Si el checkbox ya está marcado y estamos en modo demo
  if (modoDemo && checkbox.prop('checked')) {
    e.preventDefault();  // Prevenir la acción por defecto (desmarcar el checkbox)

    Swal.fire({
      icon: 'warning',
      title: 'Modo demo',
      text: 'No puedes desmarcar en modo demo',
    });
  }
});

$('#generar-cod-correlativo').change(function () {
  // Actualizamos el estado en el localStorage basándonos en el nuevo estado del checkbox.
  if ($(this).prop('checked')) {
    localStorage.setItem("checkboxState", "checked");
  } else {
    localStorage.removeItem("checkboxState");
  }
});


// Función para establecer el campo de código como solo lectura
function setCodigoFieldReadonly() {
  $('#codigo').attr('readonly', 'readonly');
}


init();


// .....::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M  :::::::::::::::::::::::::::::::::::::::..

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..
function cargando_search() {
  var nombre_almacen  = $('#filtro_idalmacen').find(':selected').text();
  var nombre_familia  = ' ─ ' + $('#filtro_idfamilia').find(':selected').text();
  var nombre_marca    = ' ─ ' + $('#filtro_idmarca').find(':selected').text();
  if ($('#filtro_idalmacen').val() == '' || $('#filtro_idalmacen').val() == null ) {  nombre_almacen = ""; }
  if ($('#filtro_idfamilia').val() == '' || $('#filtro_idfamilia').val() == null ) { nombre_familia = "" }
  if ($('#filtro_idmarca').val() == '' || $('#filtro_idmarca').val() == null ) { nombre_marca = "" }
  $('.cargando').show().html(`<i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando ${nombre_almacen} ${nombre_familia} ${nombre_marca} ...`);
}
function filtros() {  

  var idalmacen = $("#filtro_idalmacen").select2('val');
  var idfamilia = $("#filtro_idfamilia").select2('val');  
  var idmarca   = $("#filtro_idmarca").select2('val');  
  
  var nombre_almacen  = $('#filtro_idalmacen').find(':selected').text();
  var nombre_familia  = ' ─ ' + $('#filtro_idfamilia').find(':selected').text();
  var nombre_marca    = ' ─ ' + $('#filtro_idmarca').find(':selected').text();

  if (idalmacen == '' || idalmacen == 0 || idalmacen == null) { idalmacen = ""; nombre_almacen = ""; }
  if (idfamilia == '' || idfamilia == null || idfamilia == 0 ) { idfamilia = ""; nombre_familia = "" }
  if (idmarca == '' || idmarca == null || idmarca == 0 ) { idmarca = ""; nombre_marca = "" }

  $('.cargando').show().html(`<i class="fas fa-spinner fa-pulse fa-sm"></i> Buscando ${nombre_almacen} ${nombre_familia} ${nombre_marca}...`);
  
  listar_tabla_principal(idalmacen, idfamilia, idmarca);
}

function ver_img_zoom(file, nombre) {
  $('.title-name-foto-zoom').html(nombre);
  // $(".tooltip").remove();
  $("#modal-ver-perfil-producto").modal("show");
  $('#div-foto-zoom').html(`<span class="jq_image_zoom"><img class="img-thumbnail" src="${file}" onerror="this.src='../assets/svg/404-v2.svg';" alt="Foto zoom" width="100%"></span>`);
  $('.jq_image_zoom').zoom({ on:'grab' });
}