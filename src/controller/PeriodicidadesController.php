<?php
use Pitweb\Funciones as PwFunciones;
use Pitweb\Security as PwSecurity;
use Pitweb\Sql as PwSql;
use Pitweb\Form as PwForm;
use Pitweb\Connection as PwConnection;



class PeriodicidadesController extends CrudController
{

 //Constructor de la clase
  function __construct()
  {
      //Validamos permisos de la clase con los perfiles
      $this->className = " Periodicidades";
      parent::__construct();
  }

	

	public function getData()
	{


    //$this->connection = PwConnection::getInstance(2)->connection;    

    //$this->tableProp["update"] = false;
    //$this->tableProp["delete"] = false;
		

	//	$this->cols = 2;
		$data = "";

		$encrypt  = PwFunciones::getPVariable("encrypt");

   	    $mode = rawurldecode (PwFunciones::getPVariable("mode"));
        if($encrypt == 2)
        {                       	
           	$mode = PwSecurity::decryptVariable(1, $mode);           
        }


		//Traemos el modelo
        $this->model = $this->getModel();
        $this->tableName = "FC_PERIODICIDADES";


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


	/**
   *Modelo donde se definen las propiedades de los elementos de las formas a usar 
   * @return [type]
   */
   private function getModel()
   {


   		$model = array(

   		array(
            "id" => "CVE_PERIODICIDAD",           
            "key"  => true,
            "type" => "text",
            "label" => "Cve. Peridiocidad",           
            "required" => true,
            "disabled" => true,
           "value" => "0",  
           "order"  => "asc",
           "consecutivo" => true
        ),

			array(
   				"id" => "DESC_PERIODICIDAD",   				   				
   				"type" => "text",
   				"label" => "Descripción",   
   				"disabled" => false,				
				"required" => true,
				//"editable" => false,
			),

   		);

		return $model;

   }
}