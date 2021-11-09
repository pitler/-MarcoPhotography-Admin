<?php
use Pitweb\Funciones as PwFunciones;
use Pitweb\Security as PwSecurity;
use Pitweb\Sql as PwSql;
use Pitweb\Form as PwForm;
use Pitweb\Connection as PwConnection;

class TiposClientesController extends CrudController
{


     //Constructor de la clase
      function __construct()
      {
          //Validamos permisos de la clase con los perfiles
          $this->className = "TiposClientes";        
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
        $this->tableName = "FC_TIPOS_CLIENTES";


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
     			"id" => "CVE_TIPO_CLIENTE",   				
     			"key"  => true,
     			"type" => "text",
     			"label" => "Cve. Tipo cliente",   				
     			"required" => true,
     			"disabled" => true,
  				"value" => "0",	
  				"order"	=> "asc",
                "consecutivo" => true
  			),

        array(
            "id" => "DESC_TIPO_CLIENTE",          
            "key"  => true,
            "type" => "text",
            "label" => "Descripción",           
            "required" => true,          
            "value" => "", 
            //"order"  => "asc"
        ),

   		);

		return $model;

   }


}