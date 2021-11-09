<?php
use Pitweb\Funciones as PwFunciones;
use Pitweb\Security as PwSecurity;
use Pitweb\Sql as PwSql;
use Pitweb\Form as PwForm;


class CatPortfolioController extends CrudController
{

	 //Constructor de la clase
  function __construct()
  {
      //Validamos permisos de la clase con los perfiles
      $this->className = "CatPortfolio";        
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
		/*$this->tableProp["extraActions"] = array(
            array("button" => "u-btn-info", "name" => "btnGrid", "function" => "getImageList", "icon" => "fa fa-file-image-o", "title" => "Agrega imágenes",  "params" => array("modTitle" => "Params imagen")),
            

		);*/



		//Traemos el modelo
        $this->model = $this->getModel();
        $this->tableName = "SITE_CAT_PORTFOLIO";


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

			/*case "validateInsert" :
        		$data = $this->validateInsert();
        	break;*/


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
          "consecutivo" => true

			),

			array(
   				"id" => "NOMBRE",   				   				
   				"type" => "text",
   				"label" => "Nombre",   				   				
   				"disabled" => false,
   				"required" => true		

			),

			array(
			"id" => "NOMBRE_EN",   				   				
			"type" => "text",
			"label" => "Nombre Inglés",   				   				
			"disabled" => false,
			"required" => true		

		    ),

			array(
				"id" => "ORDEN",                     
				"type" => "text",
				"label" => "Órden",
				"value" => "0", 
				"consecutivo" =>true,
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