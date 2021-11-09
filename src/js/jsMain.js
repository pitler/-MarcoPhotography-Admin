var errorFlag = false;
var result = "";
(function($) {

    $.extend({
        pwControll: function(options) {

            var settings = $.extend({
                //Ponemos las variables de default
                tipo: "ajax",
                clase: "JqController.php",
                method: "POST",
                async: true,
                rtype: "json"
            }, options);

            var valor = null;

            switch (settings.tipo) {
                //Hacemos un llamado por medio de post
                case "post":
                    $.post(

                        //Clase a ejecutar
                        settings.clase,
                        //Mandamos los datos
                        settings.params,
                        //Datos que regresa
                        function(data) {
                            valor = data;
                            return valor;

                        }, settings.rtype
                    );

                    break;

                case "ajax":
                    $.ajax({
                        url: settings.clase,
                        type: settings.method,
                        async: settings.async,
                        data: settings.params,
                        // dataType:	"json",

                        beforeSend: function(objeto) {
                            $("#" + settings.params.loader).show();

                        },
                        success: function(data, textStatus, jqXHR) {


                           
                            //Escondemos el loader
                            $("#" + settings.params.loader).hide();

                            //Si no hay datos, mandamos mensaje de error
                            if (!data) {

                             
                                notify("Error al cargar jsMain", "error");
                                return false;

                            }

                            //Parseamos el resultado
                            var data = jQuery.parseJSON(data);

                            //Para el login
                          
                            if (settings.params.class == "Login") {


                                
                                if (data.status == "true") {
                                    if (data.action == "newRecover") {
                                        //Reenviamos a recuperar el password
                                      
                                        window.location.replace(data.value);
                                        return false;
                                    }
                                    //Si pasa el login, recargamos la pagina
                                    else {
                                        location.reload(true);
                                    }
                                } else if (data.status == "false") {
                                    notify(data.value, "error");
                                    return false;
                                }
                            }

                            //Para parametros del Recover

                            if (settings.params.class == "Recover") {

                                if (data.status == "false") {
                                    // $("#loginLoader").hide();
                                    errorFlag = true;
                                    notify(data.value, "error");
                                    return false;
                                } else {

                                    if (data.resultMode == 1) {
                                        $(".recover").html(data.value);
                                        return false;
                                    }
                                }
                            }

                            //Para abrir los dialogos de edición
                            //Mandmaos el modal
                            if (settings.params.showModal == 1) {
                                $("#modalBody").html(data.content);
                                $('#formModal').modal('show');
                                return true;
                            }

                            //Para las acciones de presentar datos

                            //Si tiene estatus false, mandamos error
                          
                            if (data.status == "false") {
                                notify(data.message, data.type);
                                return false;
                            }

                            //Si se envia el modal se cierra
                            if (data.modal == "close") {
                                $('#formModal').modal('hide');
                               
                            }

                            //Pintamos el contenido del main
                           
                            if (data.content) {
                                $("#mainContent").html(data.content);
                               
                            }

                            //Si existe algun mensaje
                            if (data.message) {
                                notify(data.message, data.type);
                               
                            }

                            //Resetea la forma
                            if (data.type != "error") {
                                $("#mainForm").trigger("reset");
                            }
                            valor = data;
                        },

                        error: function(jqXHR, textStatus, errorThrown) {

                            alert("Error");
                            $("#" + settings.params.loader).hide();
                            notify("Error del servidor", "error");
                        }
                    });
                    break;
                default:
                    valor = "ajax default";
                    break;
            }

            return valor;
        }
    });

    

})(jQuery);

//Funcionando

function validaCampo(campo, type = null) {

    $("#" + campo + "Error").html("");
    $("#" + campo + "Error").hide();

    $("#" + campo + "Error").parent().removeClass('u-has-error-v1');
    var value = $("#" + campo).val();

    switch (type) {

        case "email":

            let validMail = validateEmail(value);
            if (validMail == false) {
                $("#" + campo + "Error").show().html("* Correo inválido");
                $("#" + campo + "Error").parent().addClass('u-has-error-v1');
                errorFlag = true;
            }
            break;
        case "numeric":
            let isNumeric = $.isNumeric(value);
            if (isNumeric == false) {
                $("#" + campo + "Error").show().html("* El campo debe de ser numérico");
                $("#" + campo + "Error").parent().addClass('u-has-error-v1');
                errorFlag = true;
            }


            break;
        default:
            if (!value || value == "") {
                $("#" + campo + "Error").show().html("* Campo requerido");
                $("#" + campo + "Error").parent().addClass('u-has-error-v1');
                errorFlag = true;
            }
            break;
    }
}

/**
 * 
 * @param {Función que valida sea un mail válido} email 
 */
function validateEmail(email) {
    let result = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return result.test(email);
}



/**Función que se encarga de enviar pop ups con mensajes
 * El primer parametro es el mensaje, el segundo el tipo de mensaje:
 * success  : Confirmación
 * info     : Información
 * error    : Error
 * warning  : Alerta* 
 * 
 */
function notify(message, type) 
{  
    
    switch(type)
    {
        case 'success' :
            toastr.success(message);
        break;
        case 'info' :
            toastr.info(message);
        break;
        case 'error' :
            toastr.error(message);
        break;
        case 'warning' :
            toastr.warning(message);
        break;
        default :
            toastr.info("Notificación no definida");
        break;
        
    }
        
}