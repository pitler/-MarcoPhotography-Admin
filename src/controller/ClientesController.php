<?php
use Pitweb\Funciones as PwFunciones;
use Pitweb\Security as PwSecurity;
use Pitweb\Sql as PwSql;
use Pitweb\Form as PwForm;
use Pitweb\Connection as PwConnection;

class ClientesController extends CrudController
{


    //Constructor de la clase
  function __construct()
  {
      //Validamos permisos de la clase con los perfiles
      $this->className = "Clientes";        
      parent::__construct();
  }


	public function getData()
	{

        $this->tableProp["fixed"]["left"] = 2; 
        
	
		$data = "";

		$encrypt  = PwFunciones::getPVariable("encrypt");

   	    $mode = rawurldecode (PwFunciones::getPVariable("mode"));
        if($encrypt == 2)
        {                       	
           	$mode = PwSecurity::decryptVariable(1, $mode);           
        }

		//Traemos el modelo
        $this->model = $this->getModel();
        $this->tableName = "FC_CLIENTES";

        switch ($mode)
        {
        	case "list" :        	
        		$data = parent::getList();
        	break;

        	case "getForm" :
        		$data = parent::getForm();
        	break;

            case "doInsert" :
                $data = parent::doInsert(true, $encrypt);                              
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


        $cliente = PwSql::executeQuery($this->connection, "FC_TIPOS_CLIENTES", array("CVE_TIPO_CLIENTE", "DESC_TIPO_CLIENTE"), null, array("DESC_TIPO_CLIENTE"));
        $cliente = PwFunciones::getArrayFromSql($cliente, "CVE_TIPO_CLIENTE", "DESC_TIPO_CLIENTE");

        $clienteAlterno = PwSql::executeQuery($this->connection, "FC_CLIENTES", array("CVE_CLIENTE", "PIZARRA "), array("NIVEL_COVAF" => 1), array("PIZARRA"));
        $clienteAlterno = PwFunciones::getArrayFromSql($clienteAlterno, "CVE_CLIENTE", "PIZARRA");

   		$model = array(

        
        array(
            "id" => "CVE_CLIENTE_ALTERNO",          
            "key"  => true,
            "type" => "select",
            "label" => "Cliente alterno",           
            "required" => true,          
            "value" => "",  
            "space" => 0,
            "arrValues" => $clienteAlterno,
           
        ),

        array(
            "id" => "PIZARRA",          
            "key"  => true,
            "type" => "text",
            "label" => "Pizarra",           
            "required" => true,          
            "value" => "", 
            //"order"  => "asc"
        ),
      


        array(
            "id" => "RAZON_SOCIAL",          
            "key"  => true,
            "type" => "text",
            "label" => "Razón social",           
            "required" => true,          
            "value" => "", 
            //"order"  => "asc"
        ),

        array(
            "id" => "RFC",          
            "key"  => true,
            "type" => "text",
            "label" => "RFC",           
            "required" => true,          
            "value" => "", 
            //"order"  => "asc"
        ),

        array(
            "id" => "CALLE",          
            "key"  => true,
            "type" => "text",
            "label" => "Calle",           
            "required" => true,          
            "value" => "", 
            //"order"  => "asc"
        ),

        array(
            "id" => "COLONIA",          
            "key"  => true,
            "type" => "text",
            "label" => "Colonia",           
            "required" => true,          
            "value" => "", 
            //"order"  => "asc"
        ),

        array(
            "id" => "DELEGACION",          
            "key"  => true,
            "type" => "text",
            "label" => "Delegación",           
            "required" => true,          
            "value" => "", 
            //"order"  => "asc"
        ),

        array(
            "id" => "CIUDAD",          
            "key"  => true,
            "type" => "text",
            "label" => "Ciudad",           
            "required" => true,          
            "value" => "", 
            //"order"  => "asc"
        ),
        array(
            "id" => "ESTADO",          
            "key"  => true,
            "type" => "text",
            "label" => "Estado",           
            "required" => true,          
            "value" => "", 
            //"order"  => "asc"
        ),
        array(
            "id" => "CP",          
            "key"  => true,
            "type" => "text",
            "label" => "CP",           
            "required" => true,          
            "value" => "", 
            //"order"  => "asc"
        ),
         

        array(
            "id" => "CVE_TIPO_CLIENTE",          
            "key"  => true,
            "type" => "select",
            "label" => "Tipo cliente",           
            "required" => true,          
            "value" => "",  
            "space" => 0,
            "arrValues" => $cliente,
           
        ),

          array(
          "id" => "ACTUALIZACION_AUT",                     
          "type" => "check",
          "label" => "Actualización automática",
          "disabled" => false,
          "required" => false,
          "value" => 0,
          "defaultValue" => 1,
      ),

      array(
        "id" => "FACTURACION_ALTERNA",                     
        "type" => "check",
        "label" => "Facturación alterna",
        "disabled" => false,
        "required" => false,
        "value" => 0,
        "defaultValue" => 1,
        ),

      
        array(
            "id" => "CVE_CLIENTE",   				
     		"key"  => true,
     		"type" => "text",
     		"label" => "Cve. cliente",   				
     		"required" => true,
     		"disabled" => true,
  			"value" => 0,	
  			"order"	=> "asc",
            "formAddFunction" => "getCveClienteVal",        

              ),
              array(
                "id" => "CVE_COVAF",            
                "type" => "text",
                "label" => "Cve. Covaf",
                "required" => true,   
                "value" => 0, 
                "disabled" => true,
                //"order"  => "asc"
            ),
    
            array(
                "id" => "NIVEL_COVAF",            
                "type" => "text",
                "label" => "Nivelcovaf",
                "required" => true,   
                "value" => "", 
                //"order"  => "asc"
            ),

       



   		);

		return $model;

   }


   /**
    * Funcion extra de la clase que regresa el consecutivo para clientes
    * @param  Connection
    * @return Integer
    */
   public static function getCveClienteVal()
   {

        $connection = PwConnection::getInstance()->connection;    
        $cveCliente = 0;
        
         $consulta = "SELECT SEQ_FC_CLIENTES.NEXTVAL FROM DUAL";                 
         $ps = PwSql::setSimpleQuery($connection, $consulta);
         $params = null;
         $sqlResults = PwSql::executeSimpleQuery($ps, $params, $consulta);         
         if($sqlResults)
         {
            $cveCliente = $sqlResults[0]["NEXTVAL"];
         }

         return $cveCliente;

   }


}
