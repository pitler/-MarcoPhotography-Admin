<?php
use Pitweb\Funciones as PwFunciones;
use Pitweb\Security as PwSecurity;
use Pitweb\Sql as PwSql;


class SiteSliderController extends CrudController
{

	 //Constructor de la clase
  function __construct()
  {
      //Validamos permisos de la clase con los perfiles
      $this->className = "SiteSlider";        
      parent::__construct();
  }


	public function getData()
	{
		/*$this->tableProp= array(			
			"update" => true,
			"delete" => true
		);

		$this->cols = 2;*/
		$data = "";

		$encrypt  = PwFunciones::getPVariable("encrypt");

   	    $mode = rawurldecode (PwFunciones::getPVariable("mode"));
        if($encrypt == 2)
        {                       	
           	$mode = PwSecurity::decryptVariable(1, $mode);           
        }

		//Propiedades para un boton extra
		$this->tableProp["extraActions"] = array(
			array("button" => "u-btn-info", "name" => "btnGrid", "function" => "getImageList", "icon" => "fa fa-file-image-o", "title" => "Agrega imágenes", "params" => array("modTitle" => "Slider")),
		);

		//Traemos el modelo
        $this->model = $this->getModel();   
        $this->tableName = "SITE_SLIDER";

        switch ($mode)
        {

			case "list" :     
					
        		$data = parent::getList();
        	break;

        	case "getForm" :
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
			
			//Acciones del modal par aimagenes
			/*case "imageList" :
				$data = self::getImageList();
			break;

			case "imageSave" :
			$data = self::saveImage();
			break;	

			case "imageDelete" :
				$data = self::deleteImage();
				break;	*/
                
			default :
				$data = parent::getList();
        	break;

        }
      
		return $data;	
	}



   private function getModel()
   {

   		$padre = PwSql::executeQuery($this->connection, "SITE_MENU", array("ID", "NOMBRE"), null, array("NOMBRE"));
   		$padre = PwFunciones::getArrayFromSql($padre, "ID", "NOMBRE");

           $categoria = PwSql::executeQuery($this->connection, "SITE_CAT_PORTFOLIO", array("ID", "NOMBRE"), array("STATUS" => 1), array("ORDEN"));
           $categoria = PwFunciones::getArrayFromSql($categoria, "ID", "NOMBRE");


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
                "id" => "TITULO",   				   				
                "type" => "text",
                "label" => "Titulo",   
                "disabled" => false,				
                "required" => false,
             //"editable" => false,
            ),
            array(
                "id" => "ENCABEZADO",   				   				
                "type" => "text",
                "label" => "Encabezado",   
                "disabled" => false,				
                "required" => false,
             //"editable" => false,
            ),

            array(
                "id" => "TEXTO",
                "type" => "textarea",
                "label" => "Texto",                    
                //"encode" => true,
                "hideColumn" => true
            ),

            array(
                "id" => "TITULO_EN",   				   				
                "type" => "text",
                "label" => "Titulo inglés",   
                "disabled" => false,				
                "required" => false,
             //"editable" => false,
            ),
            array(
                "id" => "ENCABEZADO_EN",   				   				
                "type" => "text",
                "label" => "Encabezado inglés",   
                "disabled" => false,				
                "required" => false,
             //"editable" => false,
            ),

            array(
                "id" => "TEXTO_EN",
                "type" => "textarea",
                "label" => "Texto inglés",                    
               // "encode" => true,
                "hideColumn" => true
            ),

            array(
				"id" => "CATEGORIA",   				   				
				"type" => "select",
				"label" => "Categoria",   				   				
				"disabled" => false,
				"required" => false,
				"space" => "0",
				"arrValues" => $categoria,


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