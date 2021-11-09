<?php
use Pitweb\Funciones as PwFunciones;
use Pitweb\Security as PwSecurity;
use Pitweb\Sql as PwSql;
use Pitweb\Form as PwForm;


class OrdenesController extends CrudController
{

	 //Constructor de la clase
  function __construct()
  {
      //Validamos permisos de la clase con los perfiles
      $this->className = "Ordenes";        
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
        $this->tableName = "SITE_COMPRAS";


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


    $idUsuario = PwSql::executeQuery($this->connection, "USERS_SHOP", array("ID", "CONCAT(NAME, ' ' ,LASTNAME) AS NOMBRE"), array("STATUS" => 1), array("NOMBRE"));
    $idUsuario = PwFunciones::getArrayFromSql($idUsuario, "ID", "NOMBRE");


    //$padre = PwSql::executeQuery($this->connection, "FC_SYS_MODULOS", array("ID", "NOMBRE_CLASE"), array("STATUS" => 1, "CLASE" =>"Modulos"), array("NOMBRE_CLASE"), array("=", "!="));
    //$padre = PwFunciones::getArrayFromSql($padre, "ID", "NOMBRE_CLASE");


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
         "id" => "ID_USUARIO",   				   				
         "type" => "select",
         "label" => "Usuario",   				   				
         "disabled" => false,
         "required" => true,
         "space" => 0,
         "arrValues" => $idUsuario,
         "disabled" => true,


  ),

     array(
            "id" => "FECHA",   				   				
            "type" => "date",
            "label" => "Fecha",   
            "disabled" => true,				
         "required" => true,
         
     ),

     array(
            "id" => "TOTAL",   				   				
            "type" => "text",
            "label" => "Total",   				   				
            "disabled" => true,
            "required" => true		

     ),

   /*  array(
            "id" => "DETALLE",   				   				
            "type" => "text",
            "label" => "Detalle",   				   				
     ),*/

 array(
   "id" => "SITE_TOKEN",                     
   "type" => "text",
   "label" => "Referencia",
   
),


     array(
         "id" => "PAYMENT_ID",   				   				
         "type" => "text",
         "label" => "Id de pago",   				   				
         "disabled" => true,
         "required" => true		

  ),

  
     array(
         "id" => "STATUS",   				   				
         "type" => "text",
         "label" => "Status",   				   				
         "disabled" => true,
         "required" => true		

  ),

  
     array(
         "id" => "TYPE",   				   				
         "type" => "text",
         "label" => "Forma de pago",   				   				
         "disabled" => true,
         "required" => true		

  ),

  
     array(
         "id" => "PREFERENCE_ID",   				   				
         "type" => "text",
         "label" => "Id sistema",   				   				
         "disabled" => true,
         "required" => true		

  ),



 

     
 


    );

 return $model;

   }
}