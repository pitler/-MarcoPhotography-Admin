<?php
use Pitweb\Funciones as PwFunciones;
use Pitweb\Security as PwSecurity;
use Pitweb\Sql as PwSql;
use Pitweb\Form as PwForm;

/*

*/


class LibreriasController extends CrudController
{
	

  //Constructor de la clase
  function __construct()
  {
      //Validamos permisos de la clase con los perfiles
      $this->className = "Librerias";        
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
        $this->tableName = "FC_SYS_LIB";


        switch ($mode)
        {

        	case "list" :        	
        		$data = parent::getList();
        	break;

        	case "getForm" :
        		
        		$data = parent::getForm();
        	break;

        	case "doInsert" :


            //parse_str($_POST['formParams'], $formParams);



        		$data = parent::doInsert();
        	break;

        	case "doUpdate" :
           //PwFunciones::getVardumpLog($_POST["formParams"]);
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

      $sitio = array(1 => "Administrador", 2 => "Sitio público");
   		
   		$model = array(

   			array(
   				"id" => "ID",
   				"key"  => true,
   				"type" => "text",
   				"label" => "Id.",   				
   				"required" => true,   
          "disabled" => true,				
          "value" => 0,
          "editable" => false,
				  "order"	=> "asc",          
          "consecutivo" => true

			),

			array(
   				"id" => "NOMBRE",   				   				
   				"type" => "text",
   				"label" => "Nombre",   
   				"disabled" => false,				
				  "required" => true,
			),

       array(
          "id" => "SITIO",
          "type" => "select",
          "label" => "Sitio",
          "required" => true,
          "space" => true,
          "arrValues" => $sitio,
      ),


      array(
          "id" => "CSS_GLOBAL",
          "type" => "textarea",
          "label" => "CSS Global",                    
          "encode" => true,
          "hideColumn" => true
      ),

       array(
          "id" => "CSS_IMPLEMENTING",
          "type" => "textarea",
          "label" => "CSS implementing",
          "encode" => true,
          "hideColumn" => true
      ),

       array(
          "id" => "CSS_CUSTOM",
          "type" => "textarea",
          "label" => "CSS custom",
          "encode" => true,
          "hideColumn" => true
      ),

       array(
          "id" => "JS_GLOBAL",
          "type" => "textarea",
          "label" => "JS Global",
          "encode" => true,
          "hideColumn" => true
      ),

       array(
          "id" => "JS_IMPLEMENTING",
          "type" => "textarea",
          "label" => "JS implementing",
          "hideColumn" => true,
          "encode" => true,
      ),

       array(
          "id" => "JS_CUSTOM",
          "type" => "textarea",
          "label" => "JS custom",
          "hideColumn" => true,
          "encode" => true
      ),

        array(
          "id" => "INIT",
          "type" => "textarea",
          "label" => "Inicialización",
          "hideColumn" => true,
          "encode" => true,
      ),

      array(
        "id" => "ORDEN",   				   				
        "type" => "text",
        "label" => "Orden",   
        "disabled" => false,				
       "required" => true,
       "value" => 0,
 ),

          array(
          "id" => "STATUS",                     
          "type" => "check",
          "label" => "Estatus",
          "disabled" => false,
          "required" => false,
          "value" => "",
          "defaultValue" => 1,
      ),
		

   	);

		return $model;

   }	
}