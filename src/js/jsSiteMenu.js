$(document).ready(function() {

     //Agregar desde el boton para nuevo detalle
     //Se usa solo para traer los datos de los checks y los guarda
     $("#btnDetailSave").on('click', function(e) {

        
       
          e.preventDefault();

          var checkboxValues = new Array();
                var cveUtils = '';

          //Leemos los checkboxes seleccionados
          $('input[name="estatus"]:checked').each(function() {
            
                checkboxValues.push($(this).val());
          });	
          
          cveUtils = String(checkboxValues);
          
          //Parámetros predefinidos
          let keyParams = $('#hKeyParams').val();
          let extraParams = $('#extraParams').val();
          let params = new Object();
          params.cveUtils = cveUtils;
          params = JSON.stringify(params);
        //  alert("Params :: " + params);
          getDetailData(detailSave, keyParams, params, extraParams);
          return false;
      });

});

/**
 * Regresa la tabla y sus elementos
 * @param {*} keyParams 
 * @param {*} title 
 */
function getDetail(keyParams, title, extraParams = null) {

    $("#detailModalBody").empty();
    $("#detailModalTitle").html("");
    $("#detailModalTitle").html(title);
    $('#detailModal').modal('show');
    getDetailData(detailList, keyParams, null, extraParams);
    return false;

}

/**
 * Hace la lalmada al controlador y printa resultados
 * @param {*} action 
 * @param {*} keyParams 
 * @param {*} params 
 */
function getDetailData(action, keyParams, params = null, extraParams = null) {

    //alert("Detail call");
    $.ajax({
        url: "JqController.php",
        type: "POST",
        async: true,
        //dataType: "json",
        data: { "class": controller, "mode": action, "keyParams": keyParams, "params": params, "extraParams":extraParams, "encrypt": 2 },

        beforeSend: function(objeto) {
            $("#headerLoader").show();
            //alert(settings.params.loader);
        },
        success: function(data, textStatus, jqXHR) {
            //console.log(params);

            //Escondemos el loader
            $("#headerLoader").hide();


            //Si no hay datos, mandamos mensaje de error
            if (!data || data == null || data == "") {
                notify("Error al cargar el detalle", "error");
                return false;
            }

            //Parseamos el resultado
            var data = jQuery.parseJSON(data);

            if (data.status == "false") {

                errorFlag = true;
                notify(data.value, "error");
            }

            if (data.status == "update") {

                errorFlag = true;                 
                notify(data.value, "success");
                
            }

            //Pintamos el contenido del main
            if (data.content) {
                $("#detailModalBody").html(data.content);
            }

            //Si existe algun mensaje
            if (data.message) {
                notify(data.message, data.type);
            }

            return false;
        },

        error: function(jqXHR, textStatus, errorThrown) {
            $("#headerLoader").hide();
            notify("Error del servidor", "error");
        }
    });
}