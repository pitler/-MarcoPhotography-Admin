<?php
use Pitweb\Funciones as PwFunciones;
use Pitweb\Security as PwSecurity;
use Pitweb\Sql as PwSql;


class ProductosController extends CrudController
{

    	 //Constructor de la clase
    function __construct()
    {
        //Validamos permisos de la clase con los perfiles
        $this->className = "Productos";        
        parent::__construct();
    }


    public function getData()
	{

		$data = "";

		$encrypt  = PwFunciones::getPVariable("encrypt");

   	    $mode = rawurldecode (PwFunciones::getPVariable("mode"));
        if($encrypt == 2)
        {                       	
           	$mode = PwSecurity::decryptVariable(1, $mode);           
        }

		//Propiedades para un boton extra
		$this->tableProp["extraActions"] = array(
			//array("button" => "u-btn-info", "name" => "btnGrid", "function" => "getImageList", "icon" => "fa fa-file-image-o", "title" => "Agrega imágenes",  "params" => array("modTitle" => "Imagen productos")),            
			array("button" => "u-btn-info", "name" => "btnGrid", "function" => "getImageList", "icon" => "fa fa-file-image-o", "title" => "Agrega imágenes",  "params" => array("modTitle" => "Imagen productos", "sortedList"=>"true")),

		);

		//Traemos el modelo
        $this->model = $this->getModel();   	
        $this->tableName = "SITE_PRODUCTOS";

        switch ($mode)
        {

            case "list" :                     
        		$data = parent::getList();
        	break;

        	case "getForm" :
        		//$data = $this->getForm();
        		$data = parent::getForm();
        	break;

        	case "doInsert" :
        		$data = parent::doInsert();
        	break;

        	case "doUpdate" :
        		$data = parent::doUpdate();        		
        	break;

        	case "doDelete" :
        		$data = parent::doDelete();        		
            break;
            
            	//Acciones del modal
		  /*  case "detailList" :
			    $data = self::getDetail();
			break;

		    case "detailSave" :
			    $data = self::detailSave();
			break;	*/

			default :
				$data = parent::getList();
        	break;

        }
      
		return $data;	
	}



   private function getModel()
   {

    $categorias = PwSql::executeQuery($this->connection, "CATEGORIA_PRODUCTOS", array("ID", "NOMBRE"), null, array("NOMBRE"));		   
    $categorias = PwFunciones::getArrayFromSql($categorias, "ID", "NOMBRE");

    $medidas = PwSql::executeQuery($this->connection, "CATALOGO_MEDIDAS", array("ID", "DESCRIPCION"), null, array("DESCRIPCION"));		   
    $medidas = PwFunciones::getArrayFromSql($medidas, "ID", "DESCRIPCION");
   	

   		$model = array(


			array(
   				"id" => "ID",   				
   				"key"  => true,
   				"type" => "text",
   				"label" => "Id.",   				
   				"required" => true,
   				"disabled" => true,
				"value" => "0",	
			
                "consecutivo" => true,
            ),

            array(
				"id" => "PRECIO",   				   				
				"type" => "text",
				"label" => "Precio",
				"disabled" => false,
				"required" => true,								
		    ),
            

            array(
                "id" => "NOMBRE",   				   				
                "type" => "text",
                "label" => "Nombre",   
                "disabled" => false,				
                "required" => true,
            ),
            
			array(
                "id" => "NOMBRE_EN",   				   				
                "type" => "text",
                "label" => "Nombre inglés",   
                "disabled" => false,				
                "required" => true,
			),
			
		

			array(
				"id" => "DESCRIPCION",
				"type" => "text",
				"label" => "Descripción",
				"hideColumn" => true,				
			),
			array(
				"id" => "DESCRIPCION_EN",
				"type" => "textarea",
				"label" => "Descripción en inglés",
				"hideColumn" => true,				
            ),
            
            
			array(
				"id" => "CATEGORIA",   				   				
				"type" => "select",
				"label" => "Categoría",
				"disabled" => false,
				"required" => false,
				"space" => "",
				"arrValues" => $categorias,
            ),
         
           
			array(
				"id" => "MEDIDAS",   				   				
				"type" => "select",
				"label" => "Medidas",
				"disabled" => false,
				"required" => false,
				"space" => "",
                "arrValues" => $medidas,
                "multiple" => true,
				"multipleSize" => 5,
				"hideColumn" => true,	
            ),
            
		
			array(
                "id" => "TIENE_INVENTARIO",                     
                "type" => "check",
                "label" => "Maneja inventario",
                "disabled" => false,
                "required" => false,
                "value" => "",
                "defaultValue" => 1,
			),
			
			array(
                "id" => "CANTIDAD",   				   				
                "type" => "text",
                "label" => "Cantidad",   
                "disabled" => false,				
                "required" => false,
			),
			
			array(
				"id" => "ORDEN",   				   				
				"type" => "text",
				"label" => "Orden",   
				"disabled" => false,				
			   "required" => true,
			   "order"	=> "ASC",
			   "consecutivo" =>true,
	   "value" => 0				
		 ),

         
            array(
                "id" => "STATUS",                     
                "type" => "check",
                "label" => "Estatus",
                "disabled" => false,
                "required" => true,
                "value" => "",
                "defaultValue" => 1,
            ),
 
   		);

		return $model;

   }


   /**
    *  Salva el detalle de lo que le enviemos en el modal  
   */
  /* private function detailSave()
	{
		

		$keyParams = rawurldecode(PwFunciones::getPVariable("keyParams"));    
		//Dejo en su forma original
		$keyParamsAux = $keyParams;
		//Convertimos el json en array
		if(isset($keyParams) && $keyParams != "")
		{
			$keyParams = get_object_vars(json_decode( PwSecurity::decryptVariable(1,$keyParams)));
		}

		$id = $keyParams["ID"];


		$nombre = PwFunciones::getIdValue($this->connection, "SITE_PARAMS", "ID", $keyParams["ID"], "NOMBRE");


		$personalFilter = array('\"');
		$personaReplace=  array("'");
		$editorParams = get_object_vars(json_decode(PwFunciones::getPVariable("editorParams", false, false, $personalFilter, $personaReplace)));   
		//Checamos los 2 campos de edición
		$datos = array("TEXTO2" => "", "TEXTO2_EN" => "" );

		if($editorParams)
		{
			
			$datos["TEXTO2"] =  rawurlencode($editorParams["editor_es"]);						
			$datos["TEXTO2_EN"] =  rawurlencode($editorParams["editor_en"]);			
		}

	//	PwFunciones::getVardumpLog($datos);

		$keys = array ("ID" => $id);		
		$result = PwSql::updateData($this->connection, "SITE_PARAMS", $datos, $keys);
		
		$result = json_encode(array("status" => "update", "value" =>"Datos actualizados" , "content" => "", "modal"=>"close"));
		return $result;
	}*/


	/**
	* Clase que pinta el contenido de los campos a usar
	*/
	/*private function getDetail($saveAction = null)
	{

		$data = file_get_contents('template/core/textEditorParams.html', true);
		$keyParams = rawurldecode(PwFunciones::getPVariable("keyParams"));    
		//Dejo en su forma original
		$keyParamsAux = $keyParams;
		//Convertimos el json en array
        $keyParams = get_object_vars(json_decode( PwSecurity::decryptVariable(1,$keyParams)));
		
		$id = $keyParams["ID"];
		
		//Si tenems parametros extras
		$extraParams = rawurldecode(PwFunciones::getPVariable("extraParams"));		
		$extraParamsAux = $extraParams;		
		if(isset($extraParams) && $extraParams != "")
		{
			$extraParams =  get_object_vars(json_decode( PwSecurity::decryptVariable(1,$extraParams)));
		}

		$condition = array("ID" => $id);
		$fields = array("TEXTO2", "TEXTO2_EN", "NOMBRE");
		$sqlResult = PwSql::executeQuery($this->connection, "SITE_PARAMS", $fields, $condition);
		$texto = $texto3 = "";
		$textoEn = $textoEn3 = "";
		
		if($sqlResult)
		{
			$sqlItem = $sqlResult[0];
		
			$texto = rawurldecode($sqlItem["TEXTO2"]);
		
			$textoEn = rawurldecode($sqlItem["TEXTO2_EN"]);
			$label = rawurldecode($sqlItem["NOMBRE"]);
		}

		$modTitle = "";
		//Traemos los parametros
		$params = rawurldecode(PwFunciones::getPVariable("params"));    
		$params = get_object_vars(json_decode( PwSecurity::decryptVariable(1,$params)));
		if($params)
		{
			 $modTitle = isset($params["modTitle"]) ? $params["modTitle"] :"";
		}

		
		$data = preg_replace("/__EDITOR__/", $texto, $data);
		$data = preg_replace("/__EDITOREN__/", $textoEn, $data);

		$data = preg_replace("/__HKEYPARAMS__/", $keyParamsAux, $data);	
		$data = preg_replace("/__EXTRAPARAMS__/", $extraParamsAux, $data);
		$data = preg_replace("/__MODULE__/", $modTitle, $data);	
		$data = preg_replace("/__MODVAL__/", $id, $data);	



		$result = json_encode(array("status" => "true", "content" =>$data));

		return $result;
	}*/



}