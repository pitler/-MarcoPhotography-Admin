<?php
use Pitweb\Funciones as PwFunciones;
use Pitweb\Security as PwSecurity;
use Pitweb\Sql as PwSql;
use Pitweb\Form as PwForm;
use Pitweb\Connection as PwConnection;



class CuotasClientesController extends CrudController
{

	

  //Constructor de la clase
  function __construct()
  {
      //Validamos permisos de la clase con los perfiles
      $this->className = "CuotasClientes";
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
        $this->tableName = "FC_CUOTAS_CLIENTES";


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

    $clienteAlterno = PwSql::executeQuery($this->connection, "FC_CLIENTES", array("CVE_CLIENTE", "PIZARRA "), array("NIVEL_COVAF" => 1), array("PIZARRA"));
    $clienteAlterno = PwFunciones::getArrayFromSql($clienteAlterno, "CVE_CLIENTE", "PIZARRA");

    //Esto cambiarlo por la nueva tabla
    $cuotas = PwSql::executeQuery($this->connection, "FC_CAT_RANGOS_CUOTA", array("ID", "DESCRIPCION "), null, array("DESCRIPCION"));
    $cuotas = PwFunciones::getArrayFromSql($cuotas, "ID", "DESCRIPCION");

    
    //PwFunciones::getVardumpLog($cuotas);
    
    ////////////////////////////////////////////////////////

    $servicios = PwSql::executeQuery($this->connection, "FC_SERVICIOS", array("ID", "CVE_SERVICIO"), null, array("CVE_SERVICIO"));
    $servicios = PwFunciones::getArrayFromSql($servicios, "ID", "CVE_SERVICIO");



   		$model = array(
/*
   		array(
            "id" => "CVE_CLIENTE",           
            "key"  => true,
            "type" => "text",
            "label" => "Cliente",           
            "required" => true,
            "disabled" => false,
           "value" => "0",  
           "order"  => "asc",
         //  "consecutivo" => true
           ),*/
        array(
            "id" => "CVE_CLIENTE",          
            "key"  => true,
            "type" => "select",
            "label" => "Cliente ",           
            "required" => true,          
            "value" => "",  
            "space" => "",
            "arrValues" => $clienteAlterno,
           
        ),


        array(
            "id" => "CVE_SERVICIO",          
            "key"  => true,
            "type" => "select",
            "label" => "Servicio",           
            "required" => true,          
            "value" => "",  
            "space" => "",
            "arrValues" => $servicios,
           
        ),



        array(
            "id" => "CVE_RANGO",          
            "key"  => true,
            "type" => "select",
            "label" => "Tipo rango ",           
            "required" => true,          
            "value" => "",  
            "space" => "",
            "arrValues" => $cuotas,
           
        ),

   		);

		return $model;

   }
}