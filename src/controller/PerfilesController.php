<?php
use Pitweb\Funciones as PwFunciones;
use Pitweb\Security as PwSecurity;
use Pitweb\Sql as PwSql;
use Pitweb\Form as PwForm;


class PerfilesController extends CrudController
{

	 //Constructor de la clase
  function __construct()
  {
      //Validamos permisos de la clase con los perfiles
      $this->className = "Perfiles";        
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


		//Traemos el modelo
        $this->model = $this->getModel();
        $this->tableName = "FC_SYS_PERFILES";


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


  /* 		$arrMenu = PwSql::executeQuery($this->connection, "SYS_MENU", array("ID", "DESC_MENU"), array("STATUS" => 1), array("DESC_MENU"));
   		$arrMenu = PwFunciones::getArrayFromSql($arrMenu, "ID", "DESC_MENU");


   		$padre = PwSql::executeQuery($this->connection, "SYS_MODULOS", array("ID", "NOMBRE_CLASE"), array("STATUS" => 1, "CLASE" =>"Modulos"), array("NOMBRE_CLASE"), array("=", "!="));
   		$padre = PwFunciones::getArrayFromSql($padre, "ID", "NOMBRE_CLASE");
*/

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
   				"id" => "CVE_PERFIL",   				   				
   				"type" => "text",
   				"label" => "Clave perfil",   
   				"disabled" => false,				
				"required" => true,
				//"editable" => false,
			),

			array(
   				"id" => "DESC_PERFIL",   				   				
   				"type" => "text",
   				"label" => "Descripción",   				   				
   				"disabled" => false,
   				"required" => true		

			),


			array(
   				"id" => "STATUS",   				   				
   				"type" => "check",
   				"label" => "Estatus",
   				"disabled" => false,
   				"required" => false,
   				"value" => "",
   				"defaultValue" => 1,
   				//"consecutivo" =>true
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