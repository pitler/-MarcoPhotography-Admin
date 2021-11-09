<?php
use Pitweb\Funciones as PwFunciones;
use Pitweb\Security as PwSecurity;
use Pitweb\Sql as PwSql;
use Pitweb\Form as PwForm;
use Pitweb\Connection as PwConnection;



class FacturaProgramadaController extends CrudController
{

	 //Constructor de la clase
  function __construct()
  {
      //Validamos permisos de la clase con los perfiles
      $this->className = "FacturaProgramada";        
      parent::__construct();
  }


	public function getData()
	{


    //$this->connection = PwConnection::getInstance(2)->connection;    

		$data = "";

		$encrypt  = PwFunciones::getPVariable("encrypt");

   	$mode = rawurldecode (PwFunciones::getPVariable("mode"));
    if($encrypt == 2)
    {                       	
      $mode = PwSecurity::decryptVariable(1, $mode);           
    }


		//Traemos el modelo
    $this->model = $this->getModel();
    $this->tableName = "FC_FACTURA_PROGRAMADA";


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

      $clientes = PwSql::executeQuery($this->connection, "FC_CLIENTES", array("CVE_CLIENTE", "PIZARRA"), null, array("PIZARRA"));
      $clientes = PwFunciones::getArrayFromSql($clientes, "CVE_CLIENTE", "PIZARRA");

      $tipoCalculo = PwSql::executeQuery($this->connection, "FC_TIPOS_CALCULO", array("CVE_TIPO_CALCULO", "DESC_CALCULO"), null, array("DESC_CALCULO"));
      $tipoCalculo = PwFunciones::getArrayFromSql($tipoCalculo, "CVE_TIPO_CALCULO", "DESC_CALCULO");

      $servicio = PwSql::executeQuery($this->connection, "FC_SERVICIOS", array("CVE_SERVICIO", "DESC_SERVICIO"), null, array("DESC_SERVICIO"));
      $servicio = PwFunciones::getArrayFromSql($servicio, "CVE_SERVICIO", "DESC_SERVICIO");

      $periodicidad = PwSql::executeQuery($this->connection, "FC_PERIODICIDADES", array("CVE_PERIODICIDAD", "DESC_PERIODICIDAD"), null, array("DESC_PERIODICIDAD"));
      $periodicidad = PwFunciones::getArrayFromSql($periodicidad, "CVE_PERIODICIDAD", "DESC_PERIODICIDAD");

      $baseCalculo = PwSql::executeQuery($this->connection, "FC_BASES_CALCULO", array("CVE_BASE_CALCULO", "DESC_BASE_CALCULO"), null, array("DESC_BASE_CALCULO"));
      $baseCalculo = PwFunciones::getArrayFromSql($baseCalculo, "CVE_BASE_CALCULO", "DESC_BASE_CALCULO");



   		$model = array(

       array(
          "id" => "CVE_CLIENTE",                     
          "key"  => true,
          "type" => "select",
          "label" => "Cve. Cliente",                    
          "required" => true,
          "space" => "",
          "arrValues" => $clientes,
          "editable" => false

      ),

     
			 array(
          "id" => "CVE_TIPO_CALCULO",                     
          "key"  => true,
          "type" => "select",
          "label" => "Clave tipo cálculo",                              
          "required" => true,
          "space" => "",
          "arrValues" => $tipoCalculo,
          "editable" => false
      ),


   array(
          "id" => "CVE_SERVICIO",                     
          "key"  => true,
          "type" => "select",
          "label" => "Cve. Servicio",                              
          "required" => true,
          "space" => false,
          "arrValues" => $servicio,
          "editable" => false
      ),

    array(
            "id" => "PORCENTAJE",                      
            "type" => "text",
            "label" => "Porcentaje",
        ),

     array(
          "id" => "PERIODICIDAD",                               
          "type" => "select",
          "label" => "Periodicidad",                              
          "required" => true,
          "space" => false,
          "arrValues" => $periodicidad,          
      ),

      array(
          "id" => "CVE_BASE_CALCULO",                               
          "type" => "select",
          "label" => "Cve. Base cálculo",                                        
          "space" => false,
          "required" => true,
          "arrValues" => $baseCalculo,          
      ),


      array(
          "id" => "STATUS",                     
          "type" => "check",
          "label" => "Estatus",          
          //"required" => false,
          "value" => "0",
          "defaultValue" => 1,
      ),  
  
   	);

		return $model;
   }
}