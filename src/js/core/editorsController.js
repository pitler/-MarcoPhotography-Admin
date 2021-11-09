$(document).ready(function() {

      //Agregar desde el boton para nuevo detalle
     //Se usa solo para traer los datos de los checks y los guarda
     $("#btnDetailSave").on('click', function(e) {
  
      
       
          e.preventDefault();     
          //Parámetros predefinidos
          let keyParams = $('#hKeyParams').val();
          let extraParams = $('#extraParams').val();
          let editorParams = new Object();
          
          var boxData = CKEDITOR.instances.editor_es.getData();
          var boxDataEn = CKEDITOR.instances.editor_en.getData();          
          
          editorParams.editor_es = boxData;
          editorParams.editor_en = boxDataEn;
          
          editorParams = JSON.stringify(editorParams);
          
          getDetailData(detailSave, keyParams, editorParams, extraParams);
          return false;
      });
  
  
   
  
  });
  
  /**
  * Regresa la tabla y sus elementos
  * @param {*} keyParams        Parametros con las llaves
  * @param {*} title            Título del modal
  * @param {*} extraParams      Parámetros con campos extras
  * @param {*} params           Parámetros de configuración
  */
  function getTextEditor(keyParams, title, extraParams = null, params) {
  
    $("#detailModalBody").empty();
    $("#detailModalTitle").html("");
    $("#detailModalTitle").html(title);
    $('#detailModal').modal('show');
    //Borramos el fileUpload si existe
    $("#fileUpload").empty();
    $('#btnDetailSave').show();
    
   
    getDetailData(detailList, keyParams, null, extraParams, params);
    return false;
  
  }
  
  /**
  * Hace la lalmada al controlador y printa resultados
  * @param {*} action 
  * @param {*} keyParams 
  * @param {*} params 
  */
  function getDetailData(action, keyParams, editorParams = null, extraParams = null, params) {
  
    
    $.ajax({
        url: "JqController.php",
        type: "POST",
        async: true,
        //dataType: "json",
        data: { "class": controller, "mode": action, "keyParams": keyParams, "editorParams": editorParams, "params":params, "extraParams":extraParams, "encrypt": 2 },
  
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
  
  
             //Si existe algun mensaje
             if (data.modal == "close") {
              $('#detailModal').modal('hide');
              }
  
  
            
  
            return false;
        },
  
        error: function(jqXHR, textStatus, errorThrown) {

            $('.modalSpinner').hide();
            notify("Error del servidor", "error");
        }
    });
  }