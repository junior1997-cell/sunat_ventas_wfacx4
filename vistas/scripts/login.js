

 $.post("../ajax/enlacebd.php?op=empresa", function(r){
        data=JSON.parse(r);
      var lista = document.getElementById("empresaConsulta");
      for (var i = 0; i < data.length; i++) {
        var opt=document.createElement("option");
        opt.setAttribute("value",data[i]);
        opt.setAttribute("label",data[i]);
        //lista.appendChild(opt);
      }
    });

     //Carga de combo para empresa =====================
    $.post("../ajax/enlacebd.php?op=empresa", function(r){
        data=JSON.parse(r);
      var lista = document.getElementById("empresa");
      for (var i = 0; i < data.length; i++) {
        var opt=document.createElement("option");
        opt.setAttribute("value",data[i]);
        opt.setAttribute("label",data[i]);
        //lista.appendChild(opt);
      }
    });


 // //Carga de combo para empresa =====================
 //    $.post("../ajax/conexion.php?op=empresa", function(r){
 //            $("#empresa").html(r);
 //            $('#empresa').selectpicker('refresh');
 //    });

 //     //Carga de combo para empresa =====================
 //    $.post("../ajax/conexion.php?op=empresa", function(r){
 //            $("#empresaConsulta").html(r);
 //            $('#empresaConsulta').selectpicker('refresh');
 //    });




function idempresaF()
{

var idempresa=$('#empresaConsulta').val();
$('#idempresa').val(idempresa);
}


$(function() {
  $("#frmAcceso").on("submit", function(e) {
    e.preventDefault();

    var btnIngresar = $("#btnIngresar");
    var logina = $("#logina").val();
    var clavea = $("#clavea").val();
    var empresa = $("#empresa").val();
    var st = $("#estadot").val();

    btnIngresar.prop("disabled", true).html("Validando datos...");

        $.post("../ajax/enlacebd.php?op=verificarempresa", { "dbase": empresa }, function() {
      $.post(
          "../ajax/usuario.php?op=verificar",
          { "logina": logina, "clavea": clavea, "empresa": empresa, "st": st },
          function(data) {
            if (data != "null") {
              $(location).attr("href", "escritorio");
            } else {
              Swal.fire({
                icon: "error",
                title: "Error",
                text: "Usuario y/o contraseña incorrectos o no tiene permiso para la empresa seleccionada!",
                allowEnterKey: false,
                showConfirmButton: false,
                timer: 1500
              }).then(function() {
                btnIngresar.prop("disabled", false).html("Ingresar");
                $("#logina").focus();
              });
            }
          }
      );
  });

  });
});









function stopRKey(evt) {
var evt = (evt) ? evt : ((event) ? event : null);
var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
if ((evt.keyCode == 13) && (node.type=="text")) {return false;}
}


function focusAgrArt(e)
{
    if(e.keyCode===13  && !e.shiftKey){
       document.getElementById('clavea').focus();
    }
}

document.onkeypress = stopRKey;

function focusTest(el)
{
   el.select();
}

function enter(e, field) {
  // Backspace = 8, Enter = 13, ’0′ = 48, ’9′ = 57, ‘.’ = 46
  key = e.keyCode ? e.keyCode : e.which

  if(e.keyCode===13  && !e.shiftKey)
    {
       document.getElementById('serienumero').focus();
    }

   }

onOff=false;
counter=setInterval(timer, 5000);
count = 0;


function timer()
{
    count++;
    //tabla.ajax.reload(null,false);
}


//PARA ACTUALIZAR ESTADO
 onOff = true;
function pause(){
    if (!onOff) {

        onOff=true;
        clearInterval(counter);
        //listar();
        alert("Temporizador desactivado");
        desactivarTempo();
        mostrarEstado();
        $st=0;
    }else{
        onOff=false;
        alert("Temporizador activado");
        //counter=setInterval(timer, 5000);
        activarTempo();
        mostrarEstado();

    }
    }
//PARA ACTUALIZAR ESTADO


function mostrarEstado()
{
   $.post("../ajax/factura.php?op=datostemporizadopr", function(data)
    {
       data=JSON.parse(data);
       if (data != null){
       $('#idtemporizador').val(data.idtempo);
       $('#estado').val(data.estado);
       $("#tiempo").val(data.tiempo);
       $("#tiempoN").val(data.tiempo);
		    }

    });
}


function activarTempo()
{
   $.post("../ajax/factura.php?op=activartempo&st=1&tiempo="+$("#tiempoN").val(), function(data)
    {
    });
}


function desactivarTempo()
{
   $.post("../ajax/factura.php?op=activartempo&st=0&tiempo="+$("#tiempoN").val(), function(data)
    {
    });
}





function empresa()
{
  empresa=$("#empresaConsulta").val();
  $.post("../ajax/enlacebd.php?op=verificarempresa",{"dbase": empresa});
}
