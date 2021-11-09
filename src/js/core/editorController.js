$(document).ready(function() {

    //  $('#summernote').summernote();
  
      //Agregar desde el boton para nuevo detalle
     //Se usa solo para traer los datos de los checks y los guarda
     $("#btnDetailSave").on('click', function(e) {
  
       
          e.preventDefault();     
          //Parámetros predefinidos
          let keyParams = $('#hKeyParams').val();
          let extraParams = $('#extraParams').val();
          let params = new Object();
          var boxData = CKEDITOR.instances.editor1.getData();
          var boxData2 = CKEDITOR.instances.editor2.getData();
          params.editor1 = boxData;
          params.editor2 = boxData2;
          params = JSON.stringify(params);
          
          getDetailData(detailSave, keyParams, params, extraParams);
          return false;
      });
  
  
   
  
  });
  
  /**
  * Regresa la tabla y sus elementos
  * @param {*} keyParams 
  * @param {*} title 
  */
  function getTextEditor(keyParams, title, extraParams = null) {
  
    $("#fileUpload").empty();
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
    $("#fileUpload").empty();
    
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
  
  
             //Si queremos cerrar el modal
             if (data.modal == "close") {
              $('#detailModal').modal('hide');
              }
  
  
            
  
            return false;
        },
  
        error: function(jqXHR, textStatus, errorThrown) {
            $("#headerLoader").hide();
            notify("Error del servidor", "error");
        }
    });
  }