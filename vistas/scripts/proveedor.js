var tabla_proveedor;

//Función que se ejecuta al inicio
function init() {

  listar();

  // ══════════════════════════════════════ S E L E C T 2 ══════════════════════════════════════   
  $.post("../ajax/ajax_general.php?op=select2_distrito", function (r) { $("#iddistrito").html(r); });

  // ══════════════════════════════════════ G U A R D A R   F O R M ══════════════════════════════════════
  $("#guardar_registro_proveedor").on("click", function (e) { if ( $(this).hasClass('send-data')==false) { $("#submit-form-proveedor").submit(); }  });

  // ══════════════════════════════════════ I N I T I A L I Z E   S E L E C T 2 ══════════════════════════════════════  
  $("#iddistrito").select2({ dropdownParent: $('#modal-agregar-proveedor'),  theme: "bootstrap4", placeholder: "Seleccione", allowClear: true,  });

}

function llenar_dep_prov_ubig(input) {

  $(".chargue-pro").html(`<i class="fas fa-spinner fa-pulse text-danger"></i>`); 
  $(".chargue-dep").html(`<i class="fas fa-spinner fa-pulse text-danger"></i>`); 
  $(".chargue-ubi").html(`<i class="fas fa-spinner fa-pulse text-danger"></i>`); 

  if ($(input).select2("val") == null || $(input).select2("val") == '') { 
    $("#iddepartamento").val(""); 
    $("#idprovincia").val(""); 
    $("#ubigeo").val(""); 

    $(".chargue-pro").html(''); $(".chargue-dep").html(''); $(".chargue-ubi").html('');
  } else {
    var iddistrito =  $(input).select2('data')[0].element.attributes.iddistrito.value;
    $.post(`../ajax/ajax_general.php?op=select2_distrito_id&id=${iddistrito}`, function (e) {   
      e = JSON.parse(e); console.log(e);
      $("#iddepartamento").val(e.departamento); 
      $("#idprovincia").val(e.provincia); 
      $("#ubigeo").val(e.ubigeo_inei); 

      $(".chargue-pro").html(''); $(".chargue-dep").html(''); $(".chargue-ubi").html('');
      $("#form-proveedor").valid();
    });
  }  
}

//Función limpiar
function limpiar_form_proveedor() {
  $("#nombres").val("");
  $("#apellidos").val("");
  $("#tipo_documento").val("1");
  $("#numero_documento").val("");
  $("#razon_social").val("");
  $("#domicilio_fiscal").val("");
  $("#nombre_comercial").val("");

  $("#iddepartamento").val("");
  $("#idprovincia").val("");
  $("#iddistrito").val("").trigger("change");
  $("#ciudad").val("");
  $("#ubigeo").val("");  

  $("#telefono1").val("");
  $("#telefono2").val("");
  $("#email").val("");
  $("#idpersona").val("");
  $("#guardar_registro_proveedor").html('Agregar');

  // Limpiamos las validaciones
  $(".form-control").removeClass('is-valid');
  $(".form-control").removeClass('is-invalid');
  $(".error.invalid-feedback").remove();
}

//Función Listar
function listar() {
  tabla_proveedor = $('#tbllistado').dataTable(   {
    lengthMenu: [[ -1, 5, 10, 25, 75, 100, 200,], ["Todos", 5, 10, 25, 75, 100, 200, ]],//mostramos el menú de registros a revisar
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    dom:"<'row'<'col-md-3'B><'col-md-3 float-left'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",//Definimos los elementos del control de tabla
    buttons: [
      { text: '<i class="fa-solid fa-arrows-rotate" data-toggle="tooltip" data-original-title="Recargar"></i> ', className: "btn bg-gradient-info m-r-5px", action: function ( e, dt, node, config ) { if (tabla_proveedor) { tabla_proveedor.ajax.reload(null, false); } } },
      { extend: 'copyHtml5', exportOptions: { columns: [1,2,3,4,5], }, text: `<i class="fas fa-copy" data-toggle="tooltip" data-original-title="Copiar"></i>`, className: "btn bg-gradient-gray m-r-5px", footer: true,  }, 
      { extend: 'excelHtml5', exportOptions: { columns: [1,2,3,4,5], }, title: 'Lista de proveedores', text: `<i class="far fa-file-excel fa-lg" data-toggle="tooltip" data-original-title="Excel"></i>`, className: "btn bg-gradient-success m-r-5px", footer: true,  }, 
      { extend: 'pdfHtml5', exportOptions: { columns: [1,2,3,4,5], }, title: 'Lista de proveedores', text: `<i class="far fa-file-pdf fa-lg" data-toggle="tooltip" data-original-title="PDF"></i>`, className: "btn bg-gradient-danger m-r-5px", footer: false, orientation: 'landscape', pageSize: 'LEGAL',  },
      { extend: "colvis", text: `<i class="fas fa-outdent"></i>`, className: "btn bg-gradient-gray", exportOptions: { columns: "th:not(:last-child)", }, },
    ],
    "ajax":  {
      url: '../ajax/persona.php?op=listarp',
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText);
      }
    },
    "bDestroy": true,
    "iDisplayLength": 10,//Paginación
    "order": [[0, "desc"]]//Ordenar (columna,orden)
  }).DataTable();
}
//Función para guardar o editar

function guardar_y_editar_proveedor(e) {
  // e.preventDefault(); //No se activará la acción predeterminada del evento

  var formData = new FormData($("#form-proveedor")[0]);
  var data_duplicada = $("#nombres").val() ? `${$("#apellidos").val()} ${$("#apellidos").val()}` : `${$("#razon_social").val()}`;
  $.ajax({
    url: "../ajax/persona.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (datos) {
      if (datos == 'duplicado') {
        sw_error('Error', `Estos datos ya existen:<br> <b>${$('#tipo_documento option:selected').text()}:</b> ${$("#numero_documento").val()} <br> <b>Nombres:</b>${data_duplicada} `);
      } else {
        sw_success('Guardado!!', datos);         
        tabla_proveedor.ajax.reload();      
        limpiar_form_proveedor();
        $('#modal-agregar-proveedor').modal('hide');
      }      
    },
    error: function () {
      toastr_error('Error', 'No se pudo guardar los datos');      
    },
  });

}


function mostrar(idpersona) {
  $('#modal-agregar-proveedor').modal('show'); 
  limpiar_form_proveedor();
  $("#guardar_registro_proveedor").html('Actualizar');

  $.post("../ajax/persona.php?op=mostrar", { idpersona: idpersona }, function (data, status) {
    data = JSON.parse(data);   

    $("#idpersona").val(data.idpersona);

    $("#nombres").val(data.nombres);
    $("#apellidos").val(data.apellidos);
    $("#tipo_documento").val(data.tipo_documento).trigger("change");    
    $("#numero_documento").val(data.numero_documento)
    $("#razon_social").val(data.razon_social);
    $("#nombre_comercial").val(data.nombre_comercial);
    $("#domicilio_fiscal").val(data.domicilio_fiscal);

    $("#iddepartamento").val(data.iddepartamento);    
    $("#idciudad").val(data.ciudad);
    $("#iddistrito").val(data.distrito).trigger("change");

    $("#telefono1").val(data.telefono1);
    $("#telefono2").val(data.telefono2);
    $("#email").val(data.email);    
       
  });
}

//Función para desactivar registros
//Función para desactivar registros
function desactivar(idpersona) {
  Swal.fire({
    title: "¿Está seguro?",
    text: "¿Desea desactivar el proveedor?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Sí, desactivar",
    cancelButtonText: "Cancelar",
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    allowOutsideClick: false,
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/persona.php?op=desactivar", { idpersona: idpersona }, function (e) {
        sw_success('Desactivado!!', 'Proveedor desactivado con exito');        
        tabla_proveedor.ajax.reload();        
      });
    }
  });
}

//Función para activar registros
function activar(idpersona) {
  Swal.fire({
    title: "¿Está seguro?",
    text: "¿Desea activar el proveedor?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Sí, activar",
    cancelButtonText: "Cancelar",
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    allowOutsideClick: false,
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../ajax/persona.php?op=activar", { idpersona: idpersona }, function (e) {
        sw_success('Activado!!', 'Proveedor activado con exito');        
        tabla_proveedor.ajax.reload();        
      });
    }
  });
}


//=========================
//Funcion para mayusculas
function mayus(e) {  e.value = e.value.toUpperCase(); }
//=========================

function validarProveedor() {

  var ndocumento = $("#numero_documento").val();
  $.post(`../ajax/persona.php?op=ValidarProveedor&ndocumento=${ndocumento}&tipo_persona=PROVEEDOR`, function (data, status) {
    data = JSON.parse(data);
    if (data) {
      $("#numero_documento").attr("style", "background-color: #FF94A0");
      
    } else {
      $("#numero_documento").attr("style", "background-color: #A7FF64");
    }
  });
}

/*-----------------------------------------------------*/
//          EVENTO CHANGE SELECT TIPO DOCUMENTO

$('#tipo_documento').change(function () {  

  if ($('#tipo_documento').val() == 6 || $('#tipo_documento').val() == 1) {
    $('#l_tipo_documento').text('Tipo Documento (Presione Enter):')
  } else {
    $('#l_tipo_documento').text('Tipo Documento:');
  }
});

/*-----------------------------------------------------*/
//            EVENTO KEYPRESS INPUT NUMERO DOC

$('#numero_documento').keypress(function (e) { if (e.which === 13 && !e.shiftKey) { buscar_s_r(); } });

function buscar_s_r() {
  var val_numdoc = $('#numero_documento').val();
  $('.btn-search-sr').html(`<i class="fas fa-spinner fa-pulse fa-lg"></i>`);

  if (val_numdoc == '') {
    sw_warning('Cuidado..!', "El campo número documento está vacío" );      
    $('.btn-search-sr').html(`<i class="fas fa-search"></i>`);
  } else {

    if ($('#tipo_documento').val() == 6) {

      $.ajax({
        type: 'POST',
        url: "../ajax/factura.php?op=consultaRucSunat&nroucc=" + val_numdoc,
        dataType: 'json',
        success: function (data) { console.log(!jQuery.isEmptyObject(data.error));
          if ( data == null ) {
            toastr_error('Error!!', 'No se logro encontrar los datos intente nuevamente.'); 
          } else if (!jQuery.isEmptyObject(data.error) || !jQuery.isEmptyObject(data.message)) {
            sw_error('Error!', data.error); 
          } else {              
            $('#razon_social').val(data.nombre);
            $('#nombre_comercial').val('--');
            $('#domicilio_fiscal').val(data.direccion);

            $('#iddepartamento').val(data.departamento);
            $('#idciudad').val(data.provincia);
            $('#iddistrito').val(data.distrito).trigger("change");
          }
          $('.btn-search-sr').html(`<i class="fas fa-search"></i>`);
          $("#form-proveedor").valid();
        },
        error: function (data) { toastr_error('Error!','Problemas al obtener la razón social', 700 ); }
      });

    } else if ($('#tipo_documento').val() == 1) {

      $.ajax({
        type: 'POST',
        url: "../ajax/boleta.php?op=consultaDniSunat&nrodni=" + val_numdoc,
        dataType: 'json',
        success: function (data) {
          if ( data == null ) {
            toastr_error('Error!!', 'No se logro encontrar los datos intente nuevamente.'); 
          } else if (!jQuery.isEmptyObject(data.error) || !jQuery.isEmptyObject(data.message)) {
            sw_error('Error!', data.error);
          } else { 
            $('#nombres').val(data.nombres);
            $('#apellidos').val(data.apellidoPaterno + ' ' + data.apellidoMaterno);
          }
          $('.btn-search-sr').html(`<i class="fas fa-search"></i>`);
          $("#form-proveedor").valid();
        },
        error: function (data) { toastr_error('Error!','Problemas al obtener los datos del DNI', 700 ); }
      });
    }else{
      toastr_info('Atencion!!', 'No hay necesidad de buscar, no tenemos informacion con ese <b>tipo de documento</b>.');
      $('.btn-search-sr').html(`<i class="fas fa-search"></i>`);
    }
  }
}

init();



// .....::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M  :::::::::::::::::::::::::::::::::::::::..
$(function () {
  // $('#fecha_proximo_pago').rules('remove', 'required');
  // $('#fecha_proximo_pago').rules('add', { required: true, messages: { required: 'Campo requerido' } }); 
  $("#form-proveedor").validate({
    // ignore: "",
    rules: { 
      tipo_documento:   { required: true, },
      numero_documento: { required: true, minlength:8, maxlength:8},
      nombres:          { required: true, minlength:2, maxlength:50},
      apellidos:        { required: true, minlength:2, maxlength:50 },
      razon_social:     { minlength:2, maxlength:200},
      nombre_comercial: { minlength:2, maxlength:200 },
      domicilio_fiscal: { minlength:2, maxlength:100 },
      iddepartamento:   { minlength:2, maxlength:45 },
      idprovincia:      { minlength:2, maxlength:45 }, 
      iddistrito:       { maxlength:45 }, 
      ubigeo:           { minlength:2, maxlength:6 },       
      telefono1:        { number: true, maxlength: 9,  }, 
      telefono2:        { number: true, maxlength: 9,  }, 
      email:            { email: true,   }, 
    },
    messages: {
      tipo_documento:   { required: "Campo requerido", minlength:"Minimo {0} caracteres", maxlength:"Maximo {0} caracteres" },
      nombres:          { required: "Campo requerido", minlength:"Minimo {0} caracteres", maxlength:"Maximo {0} caracteres" },
      apellidos:        { required: "Campo requerido", minlength:"Minimo {0} caracteres", maxlength:"Maximo {0} caracteres" },
      razon_social:     { minlength:"Minimo {0} caracteres", maxlength:"Maximo {0} caracteres" },
      nombre_comercial: { minlength:"Minimo {0} caracteres", maxlength:"Maximo {0} caracteres" },
      domicilio_fiscal: { minlength:"Minimo {0} caracteres", maxlength:"Maximo {0} caracteres" },
      iddepartamento:   { minlength:"Minimo {0} caracteres", maxlength:"Maximo {0} caracteres" },
      idprovincia:      { minlength:"Minimo {0} caracteres", maxlength:"Maximo {0} caracteres" },
      iddistrito:       { maxlength:"Maximo {0} caracteres" },      
      ubigeo:           { minlength:"Minimo {0} caracteres", maxlength:"Maximo {0} caracteres" },      
      telefono1:        { minlength:"Minimo {0} caracteres", maxlength:"Maximo {0} caracteres" },
      telefono2:        { minlength:"Minimo {0} caracteres", maxlength:"Maximo {0} caracteres" },
      email:            { email: "Ingrese correo valido" },
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
      $(".modal-body").animate({ scrollTop: $(document).height() }, 600); // Scrollea hasta abajo de la página
      guardar_y_editar_proveedor(e);      
    },
  });

  
});

function validate_reglas(input) {

  var input_tipo_doc = $(input).val(); console.log(input_tipo_doc);

  if ( input_tipo_doc == 1 ) { // DNI
    $('#numero_documento').rules('remove', 'minlength maxlength');
    $('#numero_documento').rules('add', { minlength: 8, maxlength: 8, messages: { minlength: 'Minimo {0} caracteres', maxlength: 'Maximo {0} caracteres' } });
    $('#nombres').rules('remove', 'required');
    $('#nombres').rules('add', { required: true, messages: { required: "Campo requerido" } });
    $('#apellidos').rules('remove', 'required');
    $('#apellidos').rules('add', { required: true, messages: { required: "Campo requerido" } });
    $('#razon_social').rules('remove', 'required');
    $('#nombre_comercial').rules('remove', 'required');
    $('#domicilio_fiscal').rules('remove', 'required');
  } else if ( input_tipo_doc == 6 ) { // RUC
    $('#numero_documento').rules('remove', 'minlength maxlength');
    $('#numero_documento').rules('add', { minlength: 11, maxlength: 11, messages: { minlength: 'Minimo {0} caracteres', maxlength: 'Maximo {0} caracteres' } });
    $('#nombres').rules('remove', 'required');
    $('#apellidos').rules('remove', 'required');
    $('#razon_social').rules('remove', 'required');
    $('#razon_social').rules('add', { required: true, messages: { required: "Campo requerido" } });
    $('#nombre_comercial').rules('remove', 'required');
    $('#nombre_comercial').rules('add', { required: true, messages: { required: "Campo requerido" } });
    $('#domicilio_fiscal').rules('remove', 'required');
    $('#domicilio_fiscal').rules('add', { required: true, messages: { required: "Campo requerido" } });
  }else{
    $('#numero_documento').rules('remove', 'minlength maxlength');
    $('#numero_documento').rules('add', { minlength: 5, maxlength: 15, messages: { minlength: 'Minimo {0} caracteres', maxlength: 'Maximo {0} caracteres' } });
    $('#nombres').rules('remove', 'required');
    $('#nombres').rules('add', { required: true, messages: { required: "Campo requerido" } });
    $('#apellidos').rules('remove', 'required');
    $('#apellidos').rules('add', { required: true, messages: { required: "Campo requerido" } });
    $('#razon_social').rules('remove', 'required');
    $('#nombre_comercial').rules('remove', 'required');
    $('#domicilio_fiscal').rules('remove', 'required');
  }
  $("#form-proveedor").valid();
}