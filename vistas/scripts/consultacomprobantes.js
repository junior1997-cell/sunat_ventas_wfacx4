var now = new Date();
var day = ("0" + now.getDate()).slice(-2);
var month = ("0" + (now.getMonth() + 1)).slice(-2);
var f = new Date();
cad = f.getHours() + ":" + f.getMinutes() + ":" + f.getSeconds();
var today = now.getFullYear() + "-" + (month) + "-" + (day);

$('#fecha1').val(today);
$('#fecha2').val(today);



function listarcomprobantes() {
  //tabla.ajax.reload();
  var $fecha1 = $("#fecha1").val();
  var $fecha2 = $("#fecha2").val();
  var $tcp = $("#tipocomprobante").val();
  var $staCom = $("#sttcompro").val();

  tabla = $('#tbllistado').dataTable( {
      "aProcessing": true,//Activamos el procesamiento del datatables
      "aServerSide": true,//Paginación y filtrado realizados por el servidor
      dom: 'Bfrtip',//Definimos los elementos del control de tabla
      "processing": true,
      "language": {
        'loadingRecords': '&nbsp;',
        'processing': '<i class="fa fa-spinner fa-spin"></i> Procesando datos'
      },
      buttons: [ ],

      "ajax":
      {
        url: '../ajax/ventas.php?op=listarcomprobantes&fc1=' + $fecha1 + '&fc2=' + $fecha2 + '&tcomp=' + $tcp + '&estad=' + $staCom,
        type: "get",
        dataType: "json",
        error: function (e) {
          console.log(e.responseText);
        }
      },

      "rowCallback":
        function (row, data) {

        },

      "bDestroy": true,
      "iDisplayLength": 100,//Paginación
      "order": [[0, "desc"]]//Ordenar (columna,orden)
    }).DataTable();
}

function prea42copias2(idcomprobante, tipocomp) {
  if (tipocomp == '01') {
    var rutacarpeta = '../reportes/exFacturaCompleto.php?id=' + idcomprobante;
    $("#modalCom").attr('src', rutacarpeta);
    $("#modalPreview2").modal("show");
  } else {
    var rutacarpeta = '../reportes/exBoletaCompleto.php?id=' + idcomprobante;
    $("#modalCom").attr('src', rutacarpeta);
    $("#modalPreview2").modal("show");
  }
}

// function prea4completo2(idcomprobantem, tipocomp)
// {
//               var rutacarpeta='../reportes/exFacturaCompleto.php?id='+idfactura;
//               $("#modalCom").attr('src',rutacarpeta);
//               $("#modalPreview2").modal("show");

// }



//Funcion para enviararchivo xml a SUNAT
function mostrarxml(idcomprobante, tipocomp) {

  if (tipocomp == '01') {

    $.post("../ajax/ventas.php?op=mostrarxmlfactura", { idcomprobante: idcomprobante }, function (e) {
      data = JSON.parse(e);
      if (data.rutafirma) {
        var rutacarpeta = data.rutafirma;
        $("#modalxml").attr('src', rutacarpeta);
        $("#modalPreviewXml").modal("show");
        $("#bajaxml").attr('href', rutacarpeta);
      } else {
        bootbox.alert(data.cabextxml);
      }
    }
    );

  } else {
    $.post("../ajax/ventas.php?op=mostrarxmlboleta", { idcomprobante: idcomprobante }, function (e) {
      data = JSON.parse(e);
      if (data.rutafirma) {
        var rutacarpeta = data.rutafirma;
        $("#modalxml").attr('src', rutacarpeta);
        $("#modalPreviewXml").modal("show");
        $("#bajaxml").attr('href', rutacarpeta);
        bootbox.alert(data.cabextxml);
      } else {
        bootbox.alert(data.cabextxml);

      }
    });
  }
}

//Funcion para enviararchivo xml a SUNAT
function mostrarrpta(idcomprobante, tipocomp) {
  if (tipocomp == '01') {

    $.post("../ajax/ventas.php?op=mostrarrptafactura", { idcomprobante: idcomprobante }, function (e) {
      data = JSON.parse(e);
      //bootbox.alert('Se ha generardo el archivo XML: '+data.rpta);
      var rptaS = data.rutaxmlr;
      $("#modalxml").attr('src', rptaS);
      $("#modalPreviewXml").modal("show");
      $("#bajaxml").attr('href', rptaS);

    });

  } else {
    $.post("../ajax/ventas.php?op=mostrarrptaboleta", { idcomprobante: idcomprobante }, function (e) {
      data = JSON.parse(e);
      //bootbox.alert('Se ha generardo el archivo XML: '+data.rpta);
      var rptaS = data.rutaxmlr;
      $("#modalxml").attr('src', rptaS);
      $("#modalPreviewXml").modal("show");
      $("#bajaxml").attr('href', rptaS);


    });
  }
}

//  $(function () {
//        $('#datetimepicker6').datetimepicker();
//        $('#datetimepicker7').datetimepicker({
//    useCurrent: false //Important! See issue #1075
//    });
//        $("#datetimepicker6").on("dp.change", function (e) {
//            $('#datetimepicker7').data("DateTimePicker").minDate(e.date);
//        });
//        $("#datetimepicker7").on("dp.change", function (e) {
//            $('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
//        });
//    });