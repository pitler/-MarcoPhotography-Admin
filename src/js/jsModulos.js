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
          getDetailData(detailSave, keyParams, params, extraParams);
          return false;
      });


   

});

