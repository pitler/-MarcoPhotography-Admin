<?php
use Pitweb\Funciones as PwFunciones;
use Pitweb\Security as PwSecurity;
use Pitweb\Sql as PwSql;


class SiteParamsController extends CrudController
{

    	 //Constructor de la clase
    function __construct()
    {
        //Validamos permisos de la clase con los perfiles
        $this->className = "SiteParams";        
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
            array("button" => "u-btn-info", "name" => "btnGrid", "function" => "getImageList", "icon" => "fa fa-file-image-o", "title" => "Agrega imágenes",  "params" => array("modTitle" => "Params imagen")),
            array("button" => "u-btn-info", "name" => "btnText", "function" => "getTextEditor", "icon" => "fa fa-file-text-o",  "title" => "Agrega Texto",  "params" => array("modTitle" => "Params file")),

		);

		//Traemos el modelo
        $this->model = $this->getModel();   	
        $this->tableName = "SITE_PARAMS";

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
		    case "detailList" :
			    $data = self::getDetail();
			break;

		    case "detailSave" :
			    $data = self::detailSave();
			break;	

			default :
				$data = parent::getList();
        	break;

        }
      
		return $data;	
	}



   private function getModel()
   {

   	

   		$model = array(


			array(
   				"id" => "ID",   				
   				"key"  => true,
   				"type" => "text",
   				"label" => "Id.",   				
   				"required" => true,
   				"disabled" => true,
				"value" => "0",	
				"order"	=> "asc",
                "consecutivo" => true,
            ),
            

            array(
                "id" => "NOMBRE",   				   				
                "type" => "text",
                "label" => "Nombre",   
                "disabled" => false,				
                "required" => false,
            ),
            
			array(
                "id" => "TITULO",   				   				
                "type" => "text",
                "label" => "Título",   
                "disabled" => false,				
                "required" => false,
			),
			
		

            array(
                "id" => "TITULO_EN",   				   				
                "type" => "text",
                "label" => "Título inglés",   
                "disabled" => false,				
                "required" => false,
			),

			array(
				"id" => "TEXTO",
				"type" => "textarea",
				"label" => "Texto",
				"hideColumn" => true,				
			),
			array(
				"id" => "TEXTO_EN",
				"type" => "textarea",
				"label" => "Texto en inglés",
				"hideColumn" => true,				
			),


			array(
				"id" => "TEXTO3",
				"type" => "textarea",
				"label" => "Texto 3",
				"hideColumn" => true,				
			),
			array(
				"id" => "TEXTO3_EN",
				"type" => "textarea",
				"label" => "Texto en inglés 3",
				"hideColumn" => true,				
			),

            array(
                "id" => "TITULO2",   				   				
                "type" => "text",
                "label" => "Título 2",   
                "disabled" => false,				
				"required" => false,
				"encode" => true,
            ),

            array(
                "id" => "TITULO2_EN",   				   				
                "type" => "text",
                "label" => "Título inglés 2",   
                "disabled" => false,				
				"required" => false,
				"encode" => true,
            ),

            array(
                "id" => "TITULO3",   				   				
                "type" => "text",
                "label" => "Título 3",   
                "disabled" => false,				
                "required" => false,
            ),

            array(
                "id" => "TITULO3_EN",   				   				
                "type" => "text",
                "label" => "Título inglés 3",   
                "disabled" => false,				
                "required" => false,
            ),
         
            array(
                "id" => "STATUS",                     
                "type" => "check",
                "label" => "Estatus",
                "disabled" => false,
                "required" => false,
                "value" => "",
                "defaultValue" => 1,
            ),
 
   		);

		return $model;

   }


   /**
    *  Salva el detalle de lo que le enviemos en el modal  
   */
   private function detailSave()
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
	}


	/**
	* Clase que pinta el contenido de los campos a usar
	*/
	private function getDetail($saveAction = null)
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
	}



}