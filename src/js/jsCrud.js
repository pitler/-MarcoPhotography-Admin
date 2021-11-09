$(document).ready(function() {

    $("#filterDiv").show();

    //Boton para mostrar el filtro
    $("#filterBtn").click(function(e) {
        $("#filterDiv").toggle();
    })

    //Prende el loader
    $("#headerLoader").hide();

    //La primer accion que se hace al cargar la página
    if (firstAction) {
        getData(firstAction, "");
    }

    //Lista la tabla al dar click al boton
    $("#btnList").click(function(e) {
        getData(listAction, "");

    });

    //Cierr ael modal y resetea la forma
    $("#btnClose").click(function(e) {
        $("#mainForm").trigger("reset");

    });

    //Para el boton de agregar nuevo
    $('#btnAdd').on('click', function(event) {
        showForm(formAction);
    });

    //Manda la consulta del filtro
    $("#filterForm").submit(function(event) {

        event.preventDefault();
        let filterParams = $(this).serialize();
        let action = listAction;
        getData(action, "", filterParams);

        return false;
    });


    /**
     * Se manda al enviar la forma del modal o página
     * Manda la funcíon para guardar los datos 
     */
    $("#mainForm").on('submit', function(event) {
        event.preventDefault();
        addForm();
    });

     

});

/**
 * Función que muestra un modal con la forma para agregar o editar
 * @param {} action         Accion a ejecutar
 * @param {} keyParams      Llaves del elemento con las que busca la info y llena los campos
 */
function showForm(action, keyParams = null) {

    $("#modalTitle").html("");

    if (!keyParams) {
        $("#modalTitle").html("Agregar");
    } else {
        $("#modalTitle").html("Editar");
    }

    var result = $.pwControll({
        params: {
            "class": controller,
            "loader": "headerLoader",
            "errormsg": "loginText",
            "errorDiv": "loginMsg",
            "mode": action,
            "keyParams": keyParams,
            "showModal": showFormModal,
            "encrypt": 2
        },
    });
}

/**
 * Función que manda el modal
 * @param {*} keys 
 */
function getModal(keys) {
    //alert(keys);
    showForm(formAction, keys);
}


//Funcion que envia los datos
function getData(action, formParams, filterParams = null) {
    //Mostramos al loader

    //Hacemos la llamada a la clase
    var result = $.pwControll({
        params: {
            "class": controller,
            "loader": "headerLoader",
            "errormsg": "loginText",
            "errorDiv": "loginMsg",
            "mode": action,
            "validateFields": validateFields,
            "formParams": formParams,
            "filterParams": filterParams,
            "encrypt": 2
        },
    });

   
}

/**
 * Función para eliminar
 * @param {*} keyParams 
 */
function doDelete(keyParams) {

    var answer = confirm('¿ Seguro que desea eliminar el archivo?');

    if (answer == true) {

        getData(deleteAction, keyParams)

    }
}

/**
 * Función para guardar las formas
 * Sirve tanto para modales como para formas en pantalla completa
 */
function addForm()
{
   
    
     //Bandera de error
     errorFlag = false;

     //Para vlaidar los campos forzosos
     var validItem = JSON.parse(validateArr);

    

     //Validamos cada campo y mandamos el nombre y tipo de validacion
     $.each(validItem, function(key, val) {

         validaCampo(val.name, val.type);
     });


     /*for (i in validItem) 
     {        alert(validItem[i]);
       validaCampo(validItem[i]);
     } */

     //Si un campo tiene error, lo detenemos
     if (errorFlag == true) {
         return false;
     }

     //Enviamos a controlador de nuevo
     //Para validar que no existan llaves dupilicadas
     var inputs = $("#mainForm").find(':disabled');
     inputs.prop('disabled', false);
     var formParams = $("#mainForm").serialize();
     inputs.prop('disabled', true);

     var action = insertAction;
     var editFlag = $("#editFlag").val();
     if (editFlag == 2) {         
         action = updateAction;
     }

     getData(action, formParams);

  

}

//Variable para ejecutar la primer acción al cargar la página
var firstAction;