$(document).ready(function() {


    /*
        $("#btnFAdd").on('click', function(e) {
            alert(1);
        });*/

    /*  $(document).on('click', '.row_data', function(event) {
          event.preventDefault();

          if ($(this).attr('edit_type') == 'button') {
              return false;
          }

          //make div editable
          $(this).closest('div').attr('contenteditable', 'true');
          //add bg css          
    $(this).addClass('bgTable').css('padding', '5px');


    $(this).focus();
*/

    //Para editar
    $(document).on('click', '.btn_edit', function(event) {
        event.preventDefault();
        var tbl_row = $(this).closest('tr');

        var row_id = tbl_row.attr('row_id');

        tbl_row.find('.btn_save').show();
        tbl_row.find('.btn_cancel').show();

        //hide edit button
        tbl_row.find('.btn_edit').hide();

        //make the whole row editable
        tbl_row.find('.row_data')
            .attr('contenteditable', 'true')
            .attr('edit_type', 'button')
            .addClass('bgTable')
            .css('padding', '3px')

        //--->add the original entry > start
        tbl_row.find('.row_data').each(function(index, val) {
            //this will help in case user decided to click on cancel button
            $(this).attr('original_entry', $(this).html());
        });
        //--->add the original entry > end
    });


    //Para cancelar
    $(document).on('click', '.btn_cancel', function(event) {
        event.preventDefault();

        var tbl_row = $(this).closest('tr');

        var row_id = tbl_row.attr('row_id');

        //hide save and cacel buttons
        tbl_row.find('.btn_save').hide();
        tbl_row.find('.btn_cancel').hide();

        //show edit button
        tbl_row.find('.btn_edit').show();

        //make the whole row editable
        tbl_row.find('.row_data')
            .attr('edit_type', 'click')
            .removeClass('bgTable')
            .css('padding', '')

        tbl_row.find('.row_data').each(function(index, val) {
            $(this).html($(this).attr('original_entry'));
        });
    });


    //Para borrar
    $(document).on('click', '.btn_delete', function(event) {
        event.preventDefault();

        var tbl_row = $(this).closest('tr').remove();
        var row_id = tbl_row.attr('row_id');

        let values = row_id.split("_");
        let id = values[1];
        let rango = values[0];

        let params = new Object();
        params.rango = id;
        params.id = rango;
        params = JSON.stringify(params);
        getDetailData(detailDelete, null, params);

    });



    //Para salvar la linea
    $(document).on('click', '.btn_save', function(event) {
        event.preventDefault();
        var tbl_row = $(this).closest('tr');

        var row_id = tbl_row.attr('row_id');


        //hide save and cacel buttons
        tbl_row.find('.btn_save').hide();
        tbl_row.find('.btn_cancel').hide();

        //show edit button
        tbl_row.find('.btn_edit').show();


        //make the whole row editable
        tbl_row.find('.row_data')
            .attr('edit_type', 'click')
            .removeClass('bgTable')
            .css('padding', '')

        //--->get row data > start
        var arr = {};
        tbl_row.find('.row_data').each(function(index, val) {
            var col_name = $(this).attr('col_name');
            var col_val = $(this).html();
            arr[col_name] = col_val;
        });
        //--->get row data > end

        //use the "arr"	object for your ajax call
        $.extend(arr, { row_id: row_id });


        var tbl_row = $(this).closest('tr');
        var row_id = tbl_row.attr('row_id');
        let values = row_id.split("_");

        let id = values[0];
        let rango = values[1];

        let params = new Object();
        params.minimo = arr.fminimo;
        params.monto = arr.fmonto;
        params.id = id;
        params.rango = rango;

        params = JSON.stringify(params);
        getDetailData(detailUpdate, null, params);



    });
    //--->save whole row entery > end

    //--->save single field data > start
    /* $(document).on('focusout', '.row_data', function(event) {
             event.preventDefault();

             if ($(this).attr('edit_type') == 'button') {
                 return false;
             }

             var row_id = $(this).closest('tr').attr('row_id');

             var row_div = $(this)
                 .removeClass('bgTable') //add bg css
                 .css('padding', '')

             var col_name = row_div.attr('col_name');
             var col_val = row_div.html();

             // alert(col_val);
             var arr = {};
             arr[col_name] = col_val;

             //use the "arr"	object for your ajax call
             $.extend(arr, { row_id: row_id });

             //out put to show
             //$('.post_msg').html('<pre class="bg-success">' + JSON.stringify(arr, null, 2) + '</pre>');
             alert("SAve2");

         })*/
    //--->save single field data > end

});

/**
 * Regresa la tabla y sus elementos
 * @param {*} keyParams 
 * @param {*} title 
 */
function rangosData(keyParams, title, extraParams = null) {

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
                return false;
            }

            if (data.status == "delete") {

                errorFlag = true;
                // notify(data.value, "success");
                return false;
            }

            if (data.status == "update") {

                errorFlag = true;
                // notify(data.value, "success");
                return false;
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