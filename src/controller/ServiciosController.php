<?php
use Pitweb\Funciones as PwFunciones;
use Pitweb\Security as PwSecurity;
use Pitweb\Sql as PwSql;


class ServiciosController extends CrudController
{

    	 //Constructor de la clase
    function __construct()
    {
        //Validamos permisos de la clase con los perfiles
        $this->className = "Servicios";        
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
		//**** sortedList => trae la lista de imagenes para poder ser ordenada ******///
		$this->tableProp["extraActions"] = array(
            array("button" => "u-btn-info", "name" => "btnGrid", "function" => "getImageList", "icon" => "fa fa-file-image-o", "title" => "Agrega imágenes",  "params" => array("modTitle" => "Params imagen", "sortedList"=>"true")),
          //  array("button" => "u-btn-info", "name" => "btnText", "function" => "getTextEditor", "icon" => "fa fa-file-text-o",  "title" => "Agrega Texto",  "params" => array("modTitle" => "Params file")),

		);

		//Traemos el modelo
		$this->model = $this->getModel();   
	
        $this->tableName = "SITE_SERVICIOS";

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
		   /* case "detailList" :
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

	   
	//$categoria = PwSql::executeQuery($this->connection, "SITE_CAT_PORTFOLIO", array("ID", "NOMBRE"), array("STATUS" => 1), array("ORDEN"));
	//$categoria = PwFunciones::getArrayFromSql($categoria, "ID", "NOMBRE");


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
				"id" => "DESCRIPCION",
				"type" => "textarea",
				"label" => "Descripción",
				"hideColumn" => true,				
			),

			array(
                "id" => "COSTO",   				   				
                "type" => "text",
                "label" => "Costo",   
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
                "required" => false,
                "value" => "",
                "defaultValue" => 1,
            ),
 
   		);

		return $model;

   }




}