var elems = document.querySelectorAll('.datepicker');
var instances = M.Datepicker.init(elems);
// Or with jQuery
$(document).ready(function() {
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
       minDate: new Date(),
        closeOnSelect: true,
        i18n: {
            cancel: 'Hoy',
            done: 'Seleccionar',
            months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            weekdays: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
            weekdaysAbbrev: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
        },
        onOpen: function() {
            $("#datepicker").val('');
        },
        onClose: function() {}
    });
    $('#lender').on('change', function() {
        $("#service").empty();
        $("#list-opt").empty();
        $("#service").append(`<option value=''>Seleccioná</option>`);
        if ($("#lender").val() != "") {
            $.get($("#url").val() + "/list_services/" + $("#lender").val(), function(res, sta) {
                if (res != null) {
                    $(".input-serv").show();
                    res.forEach(element => {
                        $("#service").append(`<option value=${element.id}> ${element.name} </option>`);
                    });
                } else {
                    $(".input-serv").hide();
                }
            });
        }
    });
    $('#service').on('change', function() {
        if ($("#service").val() != "") {
            $("#list-opt").append('<li id="serv-'+$("#service").val()+'">'+$('#service option:selected').text()+'<label class="edit-profile" onclick="removeLi('+$("#service").val()+')"><i class="fa fa-times"></i></label></li>');

            var services;

            if($("#service_select").val()==""){
                 services = $("#service").val();
            }else{

                 services = $("#service_select").val();
    services = services + "-" + $("#service").val();


            }
            $("#service_select").val(services);

            $("#service").val('');

        }

   });
});

function removeLi(id){
services = $("#service_select").val();
     var patron = "-" + id;
     var patron_1 = id;
    services = services.replace(patron, '');
    services = services.replace(patron_1, '');
    $("#serv-"+id).remove();

    }