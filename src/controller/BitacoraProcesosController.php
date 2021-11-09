<?php
use Pitweb\Funciones as PwFunciones;
use Pitweb\Security as PwSecurity;
use Pitweb\Sql as PwSql;
use Pitweb\Form as PwForm;
use Pitweb\Connection as PwConnection;

class BitacoraProcesosController extends CrudController
{

  //Constructor de la clase
  function __construct()
  {
      //Validamos permisos de la clase con los perfiles
      $this->className = "BitacoraProcesos";        
      parent::__construct();
  }

	public function getData()
	{

    //Cambiamos la conexion a la de Oracle
   // $this->connection = PwConnection::getInstance(2)->connection;    

    //Los campos a cambiar del query
    $this->fieldsReplace = array("FECHA_BITACORA" => "to_char(FECHA_BITACORA,'DD-MM-YYYY HH:MI:SS') as FECHA_BITACORA", "FECHA_REFERENCIA" => "to_char(FECHA_REFERENCIA,'DD-MM-YYYY HH:MI:SS') as FECHA_REFERENCIA");

    $this->tableProp["fixed"]= null;

		$data = "";

		$encrypt  = PwFunciones::getPVariable("encrypt");

   	$mode = rawurldecode (PwFunciones::getPVariable("mode"));
    if($encrypt == 2)
    {                       	
      $mode = PwSecurity::decryptVariable(1, $mode);           
    }


		//Traemos el modelo
    $this->model = $this->getModel();
    $this->tableName = "FC_BITACORA_PROCESOS";



    switch ($mode)
    {

      case "list" :        	
        $data = parent::getList();
      break;

      case "getForm" :
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

        $evento = PwSql::executeQuery($this->connection, "FC_V_TIPOS_EVENTOS_BITACORA", array("CVE_TIPO_EVENTO", "DESC_TIPO_EVENTO"), null, array("DESC_TIPO_EVENTO"));
        $evento = PwFunciones::getArrayFromSql($evento, "CVE_TIPO_EVENTO", "DESC_TIPO_EVENTO");


      
   		$model = array(

     		array(
     				"id" => "CVE_USUARIO",   				
     				"key"  => true,
     				"type" => "text",
     				"label" => "Cve. cliente",   				
     				"required" => true,
     				"editable" => false,
  				    "value" => "",	
  				    "order"	=> "asc"
  			),

         array(
            "id" => "FECHA_BITACORA",            
            "type" => "datepicker",
            "label" => "Fecha bitacora",
            "required" => true,   
            "value" => "", 
            "format" => "d-m-Y H:i:s"
            //"order"  => "asc"
        ),


      array(
          "id" => "CVE_TIPO_EVENTO",
          "type" => "select",
          "label" => "Cve.Tipo evento",
          "required" => true,
          "space" => true,
          "arrValues" => $evento,
      ),


        array(
            "id" => "DESC_BITACORA",            
            "type" => "text",
            "label" => "Descripción",
            "required" => true,   
            "value" => "", 
        ),


        array(
            "id" => "TIPO_RESULTADO",          
            "key"  => true,
            "type" => "text",
            "label" => "Resultado",           
            "required" => true,          
            "value" => "", 
            //"order"  => "asc"
        ),

        array(
            "id" => "FIRMA_ELECTRONICA",          
            "key"  => true,
            "type" => "text",
            "label" => "Firma electrónica",           
            "required" => true,          
            "value" => "",             
        ),

        array(
            "id" => "CVE_REFERENCIA",          
            "key"  => true,
            "type" => "text",
            "label" => "Cve. Referencia",           
            "required" => true,          
            "value" => "", 
            //"order"  => "asc"
        ),

         array(
            "id" => "FECHA_REFERENCIA",            
            "type" => "datepicker",
            "label" => "Fecha bitacora",
            "required" => true,   
            "value" => "", 
            "format" => "d-m-Y H:i:s"
        ),


   		);

		return $model;
   }

}