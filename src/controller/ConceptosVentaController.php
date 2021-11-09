<?php
use Pitweb\Funciones as PwFunciones;
use Pitweb\Security as PwSecurity;
use Pitweb\Sql as PwSql;


class ConceptosVentaController extends CrudController
{

	 //Constructor de la clase
  function __construct()
  {
      //Validamos permisos de la clase con los perfiles
      $this->className = "ConceptosVenta";        
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
        $this->tableName = "FC_CAT_CONCEPTOS_VENTA";


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

        $moneda = PwSql::executeQuery($this->connection, "MONEDAS", array("CVE_MONEDA", "DESC_CORTA_MONEDA"), array(), array("DESC_CORTA_MONEDA"));
        $moneda = PwFunciones::getArrayFromSql($moneda, "CVE_MONEDA", "DESC_CORTA_MONEDA");

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
   				"id" => "CLAVE",   				   				
   				"type" => "text",
   				"label" => "Clave",   
   				"disabled" => false,				
				"required" => true,
				//"editable" => false,
			),

			array(
   				"id" => "CVE_SERVICIO",   				   				
   				"type" => "select",
   				"label" => "Concepto",   				   				
   				"disabled" => false,
                "required" => true,
                "space" => "",
                "arrValues" => $servicio,
            ),
            
            array(
                "id" => "PRECIO_UNITARIO",   				   				
                "type" => "text",
                "label" => "Precio Unitario",   
                "disabled" => false,				
                "required" => true,
                //"editable" => false,
            ),

            array(
                "id" => "IVA",   				   				
                "type" => "text",
                "label" => "IVA",   
                "disabled" => false,				
                "required" => true,
                //"editable" => false,
            ),

            array(
                "id" => "PRECIO_UNITARIO_IVA",   				   				
                "type" => "text",
                "label" => "Precio Unitario con IVA",   
                "disabled" => false,				
                "required" => true,
                //"editable" => false,
            ),

			array(
                "id" => "CVE_MONEDA",   				   				
                "type" => "select",
                "label" => "Moneda",   				   				
                "disabled" => false,
                "required" => true,
                "space" => "",
                "arrValues" => $moneda,
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