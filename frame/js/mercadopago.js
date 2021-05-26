
Mercadopago.getIdentificationTypes();
$('#cardNumber').numeric();
$('#securityCode').numeric();
$('#cardExpirationMonth').numeric();
$('#cardExpirationYear').numeric();

function setPaymentMethodInfo(status, response) {
  if (status == 200) {
    $("#paymentMethodId").val(response[0].id);
  }
}

function getBin() {
  var ccNumber = document.querySelector('input[data-checkout="cardNumber"]');
  return ccNumber.value.replace(/[ .-]/g, '').slice(0, 6);
};


function guessingPaymentMethod(event) {
  if ($("#cardNumber").val() != "") {
    let bin = getBin();
    if (event.type == "keyup") {
      if (bin.length >= 6) {
        Mercadopago.getPaymentMethod({
          "bin": bin
        }, setPaymentMethodInfo);
      }
    } else {
      setTimeout(function () {
        if (bin.length >= 6) {
          Mercadopago.getPaymentMethod({
            "bin": bin
          }, setPaymentMethodInfo);
        }
      }, 100);
    }
  }
}
function addEvent(el, eventName, handler) {
  if (el.addEventListener) {
   el.addEventListener(eventName, handler);
 } else {
  el.attachEvent('on' + eventName, function(){
    handler.call(el);
  });
}
};
addEvent(document.querySelector('input[data-checkout="cardNumber"]'), 'keyup', guessingPaymentMethod);
addEvent(document.querySelector('input[data-checkout="cardNumber"]'), 'change', guessingPaymentMethod);

function doPay(){

  if ($("#cardNumber").val() == "") {
    launch_toast('Ingrese el número de tarjeta de crédito');
    $("#cardNumber").focus();
    return false;
  }
  else if ($("#securityCode").val() == "") {
    launch_toast('Ingrese el código de seguridad');
    $("#securityCode").focus();
    return false;
  }
  else if ($("#cardExpirationMonth").val() == "") {
    launch_toast('Ingrese el mes de vencimiento');
    $("#cardExpirationMonth").focus();
    return false;
  }
  else if ($("#cardExpirationYear").val() == "") {
    launch_toast('Ingrese el año de vencimiento');
    $("#cardExpirationYear").focus();
    return false;
  }
  else if ($("#cardholderName").val() == "") {
    launch_toast('Ingrese el titular de la tarjeta de crédito');
    $("#cardholderName").focus();
    return false;
  }
  else if ($("#docType").val() == "") {
    launch_toast('Ingrese el tipo de documento');
    $("#docType").focus();
    return false;
  }
  else if ($("#docNumber").val() == "") {
    launch_toast('Ingrese el número de documento');
    $("#docNumber").focus();
    return false;
  } else {

    if(!formValidation()){

      return false;
    }


    $("#status").val('');
    var form = document.querySelector('#regmdtur');
    Mercadopago.createToken(form, sdkResponseHandler);

    let interval = setInterval(function () {
      if ($("#status").val() != '') {
       clearInterval(interval);  

       if($("#status").val()=='400') {

        if ($("#cause_code").val() == 'default') {
          launch_toast('Revisa los datos');
          return false;
        }
        if ($("#cause_code").val() == '205') {
          launch_toast('Ingresa el número de tu tarjeta');
          return false;
        }
        if ($("#cause_code").val() == '208') {
          launch_toast('Elige un mes');
          return false;
        }
        if ($("#cause_code").val() == '209') {
          launch_toast('Elige un año');
          return false;
        }
        if ($("#cause_code").val() == '212' || $("#cause_code").val() == '213' || $("#cause_code").val() == '214') {
          launch_toast('Ingresa tu documento');
          return false;
        }
        if ($("#cause_code").val() == '220') {
          launch_toast('Ingresa tu banco emisor');
          return false;
        }
        if ($("#cause_code").val() == '221') {
          launch_toast('Ingresa el nombre y apellido');
          return false;
        }
        if ($("#cause_code").val() == '224') {
          launch_toast('Ingresa el código de seguridad');
          return false;
        }
        if ($("#cause_code").val() == 'E301') {
          launch_toast('Hay algo mal en ese número. Vuelve a ingresarlo');
          return false;
        }
        if ($("#cause_code").val() == 'E302') {
          launch_toast(' Revisa el código de seguridad');
          return false;
        }
        if ($("#cause_code").val() == '316') {
          launch_toast(' Ingresa un nombre válido');
          return false;
        }
        if ($("#cause_code").val() == '322' || $("#cause_code").val() == '323' || $("#cause_code").val() == '324') {
          launch_toast('Revisa tu documento');
          return false;
        }
        if ($("#cause_code").val() == '325' || $("#cause_code").val() == '326') {
          launch_toast('Revisa la fecha de vencimiento');
          return false;
        }
      }else {

        createMp();

      }
    }
  });

  }

}

function createMp(){
  swal("Confirmá que querés realizar el pago", {
    buttons: {
      cancel: "No",
      confirm: "Si"
    },
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
      var route = $("#url").val() + '/actualizar_turno';
      $("#btn-paye").prop('disabled', true);
      $("#btn-paye").html('<i class="fa fa-circle-o-notch fa-spin"></i> Enviando');
      $.ajax({
        url: route,
        type: 'POST',
        dataType: 'json',
        data: $("#regmdtur").serialize(),
        success: function(data) {
         
          if (data.response == 'false') {

            location.reload();
          } 
          if (data.response == 'no-payment') {
           $("#btn-paye").prop('disabled', false);
           $("#btn-paye").html('Pagar con Mercado Pago');
           launch_toast('El pago no pudo ser procesado');
         } 

         else {
         window.location = $("#url").val() + '/'+$("#url_business").val()+"/"+$("#url_lender").val()+"/turno/"+$("#shift").val();
        }
        },
        error: function(msj) {
          $("#btn-paye").prop('disabled', false);
          $("#btn-paye").html('Pagar con Mercado Pago');
          launch_toast('Ha ocurrido un error por favor intente más tarde');
        }
      });
    }
  });
}

function sdkResponseHandler(status, response) {
  if (status != 200 && status != 201) {
    $("#status").val(status);
    console.log(response.cause[0]['code']);
    $("#cause_code").val(response.cause[0]['code']);

  } else {
    $("#status").val(status);
    $('#token').val(response.id);
  }
  
}