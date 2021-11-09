$(document).ready(function() {

  

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
  $('.modalSpinner').show();
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

  
  $.ajax({
      url: "JqController.php",
      type: "POST",
      async: true,
      //dataType: "json",
      data: { "class": controller, "mode": action, "keyParams": keyParams, "params": params, "extraParams":extraParams, "encrypt": 2 },

      beforeSend: function(objeto) {

         $('.modalSpinner').show();

      },
      success: function(data, textStatus, jqXHR) {
          
          //Escondemos el loader
          $('.modalSpinner').hide();


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

          //Si queremos cerrar el modal
          if (data.modal == "close") {
            $('#detailModal').modal('hide');
          }
    
    

          return false;
      },

      error: function(jqXHR, textStatus, errorThrown) {
          //$("#headerLoader").hide();
          $('.modalSpinner').hide();
          notify("Error del servidor", "error");
      }
  });
}