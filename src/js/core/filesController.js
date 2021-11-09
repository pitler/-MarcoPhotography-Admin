$(document).ready(function() {
});
/**
 * Regresa la lista de imagenes y el uploader
 * @param {*} keyParams 
 * @param {*} title 
 */
/*function getFileList(keyParams, title) {
    $("#detailModalTitle").html("");
    $("#detailModalTitle").html(title);
    $('#detailModal').modal('show');
    $('.modalSpinner').show();
    getFilesData(filesController, fileList, filePath,  keyParams);
    var uploadFile = "<div id='mulitplefileuploader'>Cargar archivo(s)</div>";	    
	$("#fileUpload").empty();
    $("#fileUpload").append(uploadFile);    	
	settings = getFileUploader(keyParams);
    var uploadObj = $("#mulitplefileuploader").uploadFile(settings);
    return false;
}*/
/**
 * Regresa la lista de imagenes y el uploader
 * Revisar si se puede unir en uno solo
 * @param {*} keyParams 
 * @param {*} title 
 */
function getImageList(keyParams, title, extras, params) 
{
    $("#detailModalBody").empty();
    $("#detailModalTitle").html("");
    $("#detailModalTitle").html(title);
    $('#detailModal').modal('show');
    $('#btnDetailSave').hide();
    getFilesData(filesController, imageList, imagePath,  keyParams, params);
    var uploadImage = "<div id='mulitplefileuploader'>Cargar imagen</div>";	    
	$("#fileUpload").empty();
    $("#fileUpload").append(uploadImage);    
	settings = getFileUploader(imageSave, imageList, imagePath,  keyParams, params);
    var uploadObj = $("#mulitplefileuploader").uploadFile(settings);
    return false;
}
function getFileUploader(saveAction, listAction, filePath, keyParams, params)
{
    var settings = 
	{
    	url : "JqController.php",
    	dragDrop:true,
    	fileName: "archivos",
    	allowedTypes:"jpg,png",
        returnType:"html",
        dragDropStr: "<span><b>Selecciona o arrastra los archivos aqui</b></span>",
        uploadErrorStr:"Carga multiple de archivos no permitida",
        uploadStr:"Cargar",
        cancelStr:"Cancelar",
        maxFileSize:3145728,
        sizeErrorStr:"El tamaño máximo de archivo permitido: ",
        extErrorStr:"Extensión no permitida. Extensiones autorizadas: ",
        uploadErrorStr:"Error al cargar archivo",       
        formData: { "class": filesController, "mode": saveAction, "filePath": filePath, "keyParams": keyParams, "params" : params,  "encrypt": 2 }, 		
        showDelete:false,
        showDone:true,
  		multiple: true,
  		onSuccess: function (files, response, xhr) 
  		{	
  			if(response === "false")
  			{                  
                notify("Existe un error al agregar las imagenes ", "error");  				
  			}  			
            getFilesData(filesController, imageList,filePath, keyParams, params);
            return false;
  		},
        onError: function (files, status, message) 
        {
            getFilesData(filesController, listAction, filePath, keyParams, params);
            notify("Existe un error al agregar las imagenes : "  + message, "error");  		
  	    }	
    };
    return settings;
}
/**
 * Hace la lalmada al controlador y printa resultados
 * @param {*} action 
 * @param {*} keyParams 
 * @param {*} params 
 */
function getFilesData(filesController, action, filePath, keyParams, params) {
    $.ajax({
        url: "JqController.php",
        type: "POST",
        async: true,
        //dataType: "json",
        data: { "class": filesController, "mode": action, "filePath":filePath, "keyParams": keyParams, "params" : params,  "encrypt": 2 },
        beforeSend: function(objeto) {
            $('.modalSpinner').show();
        },
        success: function(data, textStatus, jqXHR) {
            //console.log(params);
            //Escondemos el loader
           // $("#headerLoader").hide();
            $('.modalSpinner').hide();
            //Si no hay datos, mandamos mensaje de error
            if (!data || data == null || data == "") {
                notify("Error al cargar los archivos", "error");
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
                $("#detailModalBody").empty();
                $("#detailModalBody").append(data.content);
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
            $('.modalSpinner').hide();
            notify("Error del servidor", "error");
        }
    });
}
function deleteImage(filePath, params)
{
    //Falta poner alert para eliminar
    getFilesData(filesController, imageDelete, filePath, null, params) ;
}
function addImageLists(arrVars, params)
{
	arrVars = arrVars.join( ", " );
    getFilesData(filesController, imageOrder, null, null, arrVars) ;
    /*$.ajax({
		url : "ajaxDeliver.php",
		type: "POST",    
	data: {"class" :modelExtra,"action":orderImages, "arrVars":arrVars , path:filePath, "encrypt": 2}, 
	success:function(data, textStatus, jqXHR) 
	{
		return true;
	},	
	error: function(jqXHR, textStatus, errorThrown) 
	{
		alert("Existe un error guardar el orden de las imagenes");
		  return false;      	
	}
    });*/
}

function copyImgUrl (url)
{
    //Falta poner alert para eliminar
  
    navigator.clipboard.writeText(url);


}
