<?php
use Pitweb\Funciones as PwFunciones;
use Pitweb\Security as PwSecurity;
use Pitweb\Sql as PwSql;
use Pitweb\Form as PwForm;

/*

*/


class UsuariosController extends CrudController
{
	

   //Constructor de la clase
  function __construct()
  {
      //Validamos permisos de la clase con los perfiles
      $this->className = "Usuarios";        
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
        $this->tableName = "FC_SYS_USUARIOS";

        //Al ser oracle, vamos por el formato de fechas con hora para esos campos
        if(DBASE == 2)
        {
          $this->fieldsReplace= array(
              "LAST_ACTIVITY" => "to_char(LAST_ACTIVITY, 'DD-MM-YYYY HH24:MI:SS') as LAST_ACTIVITY ",
              "LAST_LOGIN" => "to_char(LAST_LOGIN, 'DD-MM-YYYY HH24:MI:SS') as LAST_LOGIN "
            );
        }


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
            $encryptFields = array("LLAVE");
            $code = PwFunciones::generaCode(20);
            $overrideFiels = array("CODE" => array("value" => $code));
        		$data = parent::doInsert(true, $encryptFields, $overrideFiels);
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


   		$perfil = PwSql::executeQuery($this->connection, "FC_SYS_PERFILES", array("CVE_PERFIL", "DESC_PERFIL"), array("STATUS" => 1), array("DESC_PERFIL"));
   		$perfil = PwFunciones::getArrayFromSql($perfil, "CVE_PERFIL", "DESC_PERFIL");

      $status = array(0 => "Desactivado", 1 => "Activo", 3 => "Sesión activa", 4 =>"Nuevo usuario", 
        5 => "Bloqueado por intentos", 6 => "Bloqueado or inactividad", 8 => "Bloqueo por inactividad");
   		
      $idioma = array("es" => "Español");

   		$model = array(

   			array(
   				"id" => "CVE_USUARIO",
   				"key"  => true,
   				"type" => "text",
   				"label" => "Clave usuario",   				
   				"required" => true,   				
				  //"value" => "",	
          "editable" => false,
				  "order"	=> "asc"

			),

			array(
   				"id" => "NOM_USUARIO",   				   				
   				"type" => "text",
   				"label" => "Nombre del usuario",   
   				"disabled" => false,				
				  "required" => false,
				  //"editable" => true,
			),


      array(
          "id" => "CVE_PERFIL",
          "type" => "select",
          "label" => "Perfil",
          "required" => true,
          "space" => "",
          "arrValues" => $perfil,
      ),

       array(
          "id" => "LLAVE",
          "type" => "password",
          "label" => "Password",
          "required" => true,
          "editable" => false,
          "hideColumn" => true,          
      ),


      array(
        "id" => "CODE",
        "type" => "text",
        "label" => "Código",
        "required" => false,
        "editable" => false,
        "hideColumn" => true,   
        "disabled" => true,       
    ),


			array(
   				"id" => "CORREO",
   				"type" => "text",
   				"label" => "Correo",
   				"disabled" => false,
   				"required" => true,
          "validateField" => "mail",
			),

        array(
          "id" => "STATUS",
          "type" => "select",
          "label" => "Estatus",
          "disabled" => false,
          "required" => false,
          "space" => false, 
          "value" => 4,
          "arrValues" => $status
      ),


      array(
          "id" => "LAST_LOGIN",
          "type" => "datepicker",
          "label" => "Último inicio de sesion",
          "disabled" => false,
          "required" => false,                    
          "jsformat" => "dd-mm-yy 12:00:00",
          "format" => "d-m-Y H:i:s"
      ),

        array(
          "id" => "LAST_ACTIVITY",
          "type" => "datepicker",
          "label" => "Última actividad",
          "disabled" => false,
          "required" => false,
          "jsformat" => "dd-mm-yy 12:00:00",
          "format" => "d-m-Y H:i:s"
        
      ),


       array(
          "id" => "LANG",
          "type" => "select",
          "label" => "Idioma",
          "disabled" => false,
          "required" => true,
          "space" => false,          
          "arrValues" => $idioma,
      ),

        array(
          "id" => "FECHA_APLICACION",
          "type" => "datepicker",
          "label" => "Fecha aplicación",
          "disabled" => false,
          "required" => false,
          "value" => date("d-m-Y"),

      ),

        array(
          "id" => "FECHA_EXPIRACION",
          "type" => "datepicker",
          "label" => "Fecha expiración",
          "disabled" => false,
          "required" => false,
          "value" => '31-12-2030',
      ),


       array(
          "id" => "VIGENCIA",
          "type" => "text",
          "label" => "Vigencia",
          "disabled" => false,
          "required" => false,  
          "value" => 60,        
      ),

         array(
          "id" => "FECHA_CAMBIO",
          "type" => "datepicker",
          "label" => "Fecha cambio",
          "disabled" => false,
          "required" => false,
          "value" => date("d-m-Y"),
                  
      ),

   array(
          "id" => "INACTIVIDAD",
          "type" => "text",
          "label" => "Días de inactividad",
          "disabled" => false,
          "required" => false,
          "value" => "45",
          "defaultValue" => 1,
          //"consecutivo" =>true
      ),
	

   		);

		return $model;

   }

}