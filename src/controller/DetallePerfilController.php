<?php
use Pitweb\Funciones as PwFunciones;
use Pitweb\Security as PwSecurity;
use Pitweb\Sql as PwSql;
use Pitweb\Form as PwForm;


class DetallePerfilController extends CrudController
{

	 //Constructor de la clase
  function __construct()
  {
      //Validamos permisos de la clase con los perfiles
      $this->className = "DetallePerfil";        
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
		$this->tableName = "FC_SYS_DETALLE_PERFIL";
		



		$this->tableProp['listQuery'] = "SELECT * FROM FC_SYS_DETALLE_PERFIL WHERE ES_CONTROLADOR != ?";

		$this->tableProp['listQueryParams'] = array(1);



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
				self::addController();
				
        	break;

        	case "doUpdate" :
			 
				self::addController();
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


   		$cvePerfil = PwSql::executeQuery($this->connection, "FC_SYS_PERFILES", array("CVE_PERFIL", "DESC_PERFIL"), array("STATUS" => 1), array("DESC_PERFIL"));
   		$cvePerfil = PwFunciones::getArrayFromSql($cvePerfil, "CVE_PERFIL", "DESC_PERFIL");


   		$clase = PwSql::executeQuery($this->connection, "FC_SYS_MODULOS", array("CLASE", "NOMBRE_CLASE"), array("STATUS" => 1,), array("NOMBRE_CLASE"));
	   	$clase = PwFunciones::getArrayFromSql($clase, "CLASE", "NOMBRE_CLASE");


		//controller




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
   				"id" => "CVE_PERFIL",   				   				
   				"type" => "select",
   				"label" => "Clave perfil",   				   				
   				"disabled" => false,
   				"required" => true,
   				"space" => "",
   				"arrValues" => $cvePerfil,


			),

				array(
   				"id" => "CLASE",   				   				
   				"type" => "select",
   				"label" => "Clase ",   				   				   				
   				"required" => true,
   				"space" => "",
   				"arrValues" => $clase
			),

			array(
				"id" => "CONTROLADOR",   				   				
				"type" => "text",
				"label" => "Controlador ",   
				"required" => false,   				
				//"value" => $controller,	
				
          
				
		 ),

				array(
   				"id" => "VISUALIZAR",   				   				
   				"type" => "check",
   				"label" => "Visualizar",
   				"disabled" => false,
   				"required" => false,
   				"value" => "0",
   				"defaultValue" => 1,
   			),

				array(
   				"id" => "INSERTAR",   				   				
   				"type" => "check",
   				"label" => "Insertar",
   				"disabled" => false,
   				"required" => false,
   				"value" => "0",
   				"defaultValue" => 1,
   			),

				array(
   				"id" => "ACTUALIZAR",   				   				
   				"type" => "check",
   				"label" => "Actualizar",
   				"disabled" => false,
   				"required" => false,
   				"value" => "0",
   				"defaultValue" => 1,
   			),

				array(
   				"id" => "BORRAR",   				   				
   				"type" => "check",
   				"label" => "Borrar",
   				"disabled" => false,
   				"required" => false,
   				"value" => "0",
   				"defaultValue" => 1,
   			),
   		);

		return $model;

   }



   /**   
   *   	Se encarga de agregar elcontrolador si es necesario
   *	Trae los parámetros, si ya existe el controlador y es válido, lo actualiza
   *	Si no exist eel controlador y dice que el padre tiene, lo inserta   
   */
   protected function addController()
   {

		//Traemos los valores que se guardan y sacamos la clase
		parse_str($_POST['formParams'], $formParams);
		$modulo = $formParams["CLASE"];
		$controlador = $formParams["CONTROLADOR"];

		//Si tiene controlador 
		$tieneControlador = PwFunciones::getIdValue($this->connection,"FC_SYS_MODULOS", "CLASE", $modulo, "CONTROLADOR");

		if($tieneControlador == 1 && $controlador!= "")
		{

			//Verificamos si existe un controlador 		
			$cvePerfil = $formParams["CVE_PERFIL"];
			$condition = array("CVE_PERFIL" => $cvePerfil, "CLASE" => $controlador);
			$sqlResults = PwSql::executeQuery($this->connection, "FC_SYS_DETALLE_PERFIL", array("ID"), $condition);

			if($sqlResults)
			{
				$id = $sqlResults[0]["ID"];
				//Si hay uin id válido, actualizamos
				if($id && $id > 0)
				{
					//Actualizamos el controlador que ya existe				
					$datos = array(
						"VISUALIZAR" => isset($formParams["VISUALIZAR"]) ?  $formParams["VISUALIZAR"]: 0 , 
						"INSERTAR" => isset($formParams["INSERTAR"]) ?  $formParams["INSERTAR"]: 0, 
						"ACTUALIZAR" =>isset($formParams["ACTUALIZAR"]) ?  $formParams["ACTUALIZAR"]: 0, 
						"BORRAR" => isset($formParams["BORRAR"]) ?  $formParams["BORRAR"]: 0);
						$keyFields = array("ID" => $id);				
						PwSql::updateData($this->connection, "FC_SYS_DETALLE_PERFIL", $datos, $keyFields);
				}
			}
			//No tiene controlador asignado pero en la pantalla dice que si, insertamos
			else
			{
				$fields = "ID, CVE_PERFIL, CLASE, VISUALIZAR, INSERTAR, ACTUALIZAR, BORRAR, ES_CONTROLADOR";
				$datos = "?,?,?,?,?, ?,?,?";
				$params = array(0, $cvePerfil, $controlador,
				isset($formParams["VISUALIZAR"]) ?  $formParams["VISUALIZAR"]: 0 , 
				isset($formParams["INSERTAR"]) ?  $formParams["INSERTAR"]: 0, 
				isset($formParams["ACTUALIZAR"]) ?  $formParams["ACTUALIZAR"]: 0, 
				isset($formParams["BORRAR"]) ?  $formParams["BORRAR"]: 0 , 
				1);

				PwSql::insertData($this->connection, "FC_SYS_DETALLE_PERFIL", $fields, $datos, $params);
			}
		}
   }


/*ublic static function getLocalTemplate($name)
	{	
		$template = array();

		return $template[$name];
	}*/
}