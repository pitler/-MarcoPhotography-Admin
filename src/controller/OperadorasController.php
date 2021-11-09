<?php
use Pitweb\Funciones as PwFunciones;
use Pitweb\Security as PwSecurity;
use Pitweb\Sql as PwSql;
use Pitweb\Form as PwForm;

/*

*/


class OperadorasController extends CrudController
{
	

   //Constructor de la clase
  function __construct()
  {
      //Validamos permisos de la clase con los perfiles
      $this->className = "Operadoras";        
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
        $this->tableName = "SYS_OPERADORAS";

        //Al ser oracle, vamos por el formato de fechas con hora para esos campos
        /*if(DBASE == 2)
        {
          $this->fieldsReplace= array(
              "LAST_ACTIVITY" => "to_char(LAST_ACTIVITY, 'DD-MM-YYYY HH24:MI:SS') as LAST_ACTIVITY ",
              "LAST_LOGIN" => "to_char(LAST_LOGIN, 'DD-MM-YYYY HH24:MI:SS') as LAST_LOGIN "
            );
        }*/


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
 * Función donde se define el modelo de los campos ausar en el crud
 * @return Array
 * 
 */
   private function getModel()
   {


    
      $tipo = array(1 => "Fondos", 2 => "Mandatos");
   		
    //  $idioma = array("es" => "Español");

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
   				"id" => "NOMBRE",   				   				
   				"type" => "text",
   				"label" => "Nombre",   
   				"disabled" => false,				
				"required" => true,				
            ),
            
        	array(
                "id" => "RAIZ",   				   				
                "type" => "text",
                "label" => "Raíz",   
                "disabled" => false,				
             "required" => false,				
         ),

         array(
            "id" => "TIPO",
            "type" => "select",
            "label" => "Tipo operadora",
            "required" => true,
            "space" => "",
            "arrValues" => $tipo,
        ),

        
       array(
        "id" => "VECTORES",
        "type" => "textarea",
        "label" => "Vectores",        
    ),

          
    array(
        "id" => "STATUS",   				   				
        "type" => "check",
        "label" => "Activada",
        "disabled" => false,
        "required" => false,
        "value" => "0",
        "defaultValue" => 1,
 ),


  
	

   		);

		return $model;

   }

}