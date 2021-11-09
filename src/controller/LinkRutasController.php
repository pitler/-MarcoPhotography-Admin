<?php
use Pitweb\Funciones as PwFunciones;
use Pitweb\Security as PwSecurity;
use Pitweb\Sql as PwSql;
use Pitweb\Form as PwForm;


class LinkRutasController extends CrudController
{

	 //Constructor de la clase
  function __construct()
  {
      //Validamos permisos de la clase con los perfiles
      $this->className = "LinkRutas";        
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
        $this->tableName = "SITE_LINKS";


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


    $tipo = array(1 => "Fondos", 2 => "Mandatos");

    
   // $tipo = PwSql::executeQuery($this->connection, "SITE_TIPO_OPERADORA", array("ID", "NOMBRE"), null, array("NOMBRE"));
   // $tipo = PwFunciones::getArrayFromSql($tipo, "ID", "NOMBRE");



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
   				"id" => "CARPETA",   				   				
   				"type" => "text",
   				"label" => "Carpeta",   
   				"disabled" => false,				
				"required" => true,
				//"editable" => false,
            ),
            
            array(
                "id" => "TIPO_OPERADORA",
                "type" => "text",
                "label" => "Tipo operadora",
                "required" => true,
                //"space" => "",
               // "arrValues" => $tipo,
            ),
    

        array(
          "id" => "STATUS",                     
          "type" => "check",
          "label" => "Estatus",
          "disabled" => false,
          "required" => false,
          "value" => "0",
          "defaultValue" => 1,
          //"consecutivo" =>true
      ),

   	);

		return $model;

   }
}