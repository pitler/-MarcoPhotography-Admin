<?php
use Pitweb\Funciones as PwFunciones;
use Pitweb\Security as PwSecurity;
use Pitweb\Sql as PwSql;


class CatTiposCalculoValController extends CrudController
{

	 //Constructor de la clase
  function __construct()
  {
      //Validamos permisos de la clase con los perfiles
      $this->className = "CatTiposCalculoVal";        
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


		//Traemos el modelo
        $this->model = $this->getModel();
        $this->tableName = "FC_CAT_TIPOS_CALCULO_VAL";


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
                "id" => "TIPO",   				   				
                "type" => "text",
                "label" => "Tipo de Cálculo",   
                "disabled" => false,				
                "required" => true,
                //"editable" => false,
            ),

			array(
   				"id" => "DESCRIPCION",   				   				
   				"type" => "text",
   				"label" => "Descripción",   
   				"disabled" => false,				
				"required" => true,
				//"editable" => false,
			),

			array(
   				"id" => "CLIENTES_PASIVOS",   				   				
   				"type" => "text",
   				"label" => "Clientes Pasivos",   				   				
   				"disabled" => false,
   				"required" => true		

			),

			array(
                "id" => "CLIENTES_ACTIVOS",   				   				
                "type" => "text",
                "label" => "Clientes Activos",   				   				
                "disabled" => false,
                "required" => true
            ),

            array(
                "id" => "CUOTA_MINIMA",   				   				
                "type" => "check",
                "label" => "Usa Cuota Minima",
                "disabled" => false,
                "required" => false,
                "value" => "0",
                "defaultValue" => 1,
            ),
   		);

		return $model;

   }


	/*public static function getTemplate($name)
	{
	
		$template = "";

	

	



		return $template[$name];
	}*/
}