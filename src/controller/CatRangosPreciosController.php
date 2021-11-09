<?php
use Pitweb\Funciones as PwFunciones;
use Pitweb\Security as PwSecurity;
use Pitweb\Sql as PwSql;


class CatRangosPreciosController extends CrudController
{

	 //Constructor de la clase
  function __construct()
  {
      //Validamos permisos de la clase con los perfiles
      $this->className = "CatRangosPrecios";        
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
        $this->tableName = "FC_CAT_RANGOS_PRECIOS";


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
        $servicio = PwSql::executeQuery($this->connection, "FC_SERVICIOS", array("CVE_SERVICIO", "DESC_SERVICIO"), array(), array("DESC_SERVICIO"));
        $servicio = PwFunciones::getArrayFromSql($servicio, "CVE_SERVICIO", "DESC_SERVICIO");

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
   				"id" => "RANGOS_CUOTA_FIJA",   				   				
   				"type" => "text",
   				"label" => "Rangos de Cuota Fija",   
   				"disabled" => false,				
				"required" => true,
				//"editable" => false,
			),

			array(
   				"id" => "CVE_SERVICIO",   				   				
   				"type" => "select",
   				"label" => "Tipo de Rango",   				   				
   				"disabled" => false,
                "required" => true,
                "space" => "",
                "arrValues" => $servicio,
            ),
            
            array(
                "id" => "DESCRIPCION",   				   				
                "type" => "text",
                "label" => "Descripción",   
                "disabled" => false,
                //"editable" => false,
            ),

            array(
                "id" => "DESCUENTO",   				   				
                "type" => "text",
                "label" => "Descuento",   
                "disabled" => false,
                //"editable" => false,
            ),
            
            array(
                "id" => "CUOTA_FIJA",   				   				
                "type" => "check",
                "label" => "Cuota Fija",
                "disabled" => false,
                "required" => false,
                "value" => "0",
                "defaultValue" => 1,
            ),
   		);

		return $model;

   }

}