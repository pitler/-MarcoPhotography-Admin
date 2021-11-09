<?php
use Pitweb\Funciones as PwFunciones;
use Pitweb\Security as PwSecurity;
use Pitweb\Sql as PwSql;
use Pitweb\Form as PwForm;


class SiteMenuController extends CrudController
{

	 //Constructor de la clase
  function __construct()
  {
      //Validamos permisos de la clase con los perfiles
      $this->className = "SiteMenu";        
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

		//Propiedades para un boton extra
		$this->tableProp["extraActions"] = array(
			array("button" => "u-btn-info", "name" => "btnGrid", "function" => "getDetail", "icon" => "fa fa-clone", "title" => "Asignación de librerias"),
		);

		//Traemos el modelo
        $this->model = $this->getModel();
        $this->tableName = "SITE_MENU";


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
			
			//Acciones del modal
		case "detailList" :
			$data = self::getDetail();
			break;

		case "detailSave" :
			$data = self::detailSave();
			break;	

        	default :
				$data = parent::getList();
        	break;

        }
      
		return $data;	
	}



   private function getModel()
   {


		   $padre = PwSql::executeQuery($this->connection, "SITE_MENU", array("ID", "NOMBRE"), null, array("NOMBRE"));		   
   		   $padre = PwFunciones::getArrayFromSql($padre, "ID", "NOMBRE");


   	//	$clase = PwSql::executeQuery($this->connection, "FC_SYS_MODULOS", array("CLASE", "NOMBRE_CLASE"), array("STATUS" => 1,), array("NOMBRE_CLASE"));
	//   	$clase = PwFunciones::getArrayFromSql($clase, "CLASE", "NOMBRE_CLASE");


		$posicion = array(0 => "Normal", 1=> "Top Menu", 2=> "Side Menu");

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
                "id" => "CLASE",   				   				
                "type" => "text",
                "label" => "Clase",   
                "disabled" => false,				
                "required" => true,
             //"editable" => false,
			),
			
			array(
				"id" => "POSICION",   				   				
				"type" => "select",
				"label" => "Posición del menú",   				   				
				"disabled" => false,
				"required" => false,
				//"space" => "",
				"arrValues" => $posicion,


		 ),
            array(
                "id" => "NOMBRE",   				   				
                "type" => "text",
                "label" => "Nombre",   
                "disabled" => false,				
                "required" => true,
             //"editable" => false,
            ),

            array(
                "id" => "NOMBRE_EN",   				   				
                "type" => "text",
                "label" => "Nombre Inglés",   
                "disabled" => false,				
                "required" => true,
             //"editable" => false,
            ),

			array(
                "id" => "NO_LINK",   				   				
                "type" => "check",
                "label" => "Sin link",
                "disabled" => false,
                "required" => false,
                "value" => "0",
                "defaultValue" => 1,
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


			array(
   				"id" => "PADRE",   				   				
   				"type" => "select",
   				"label" => "Clase padre",   				   				
   				"disabled" => false,
   				"required" => false,
   				"space" => "0",
   				"arrValues" => $padre,


			),

				

				array(
   				"id" => "REQUIERE_LOGIN",   				   				
   				"type" => "check",
   				"label" => "Requiere login",
   				"disabled" => false,
   				"required" => false,
   				"value" => "0",
   				"defaultValue" => 1,
   			),

				array(
   				"id" => "APARECE_MENU",   				   				
   				"type" => "check",
   				"label" => "Aparece en menú",
   				"disabled" => false,
   				"required" => false,
   				"value" => "0",
   				"defaultValue" => 1,
   			),

				array(
   				"id" => "ES_SECCION",   				   				
   				"type" => "check",
   				"label" => "Es sección",
   				"disabled" => false,
   				"required" => false,
   				"value" => "0",
   				"defaultValue" => 1,
               ),
           
         array(
            "id" => "ORDEN",                     
            "type" => "text",
            "label" => "Órden",
            "value" => "0", 
            "consecutivo" =>true,
        ),
 
   		);

		return $model;

   }

   //Pintamos la tabla
   private function getDetail($saveAction = null)
   {

	   $keyParams = rawurldecode(PwFunciones::getPVariable("keyParams"));    
	   //Dejo en su forma original
	   $keyParamsAux = $keyParams;
	   //Convertimos el json en array
	   $keyParams = get_object_vars(json_decode( PwSecurity::decryptVariable(1,$keyParams)));
	   $tipo = 1;
	   
	   //Si tenems parametros extras
	   $extraParams = rawurldecode(PwFunciones::getPVariable("extraParams"));		
	   $extraParamsAux = $extraParams;		
	   if(isset($extraParams) && $extraParams != "")
	   {
		   $extraParams =  get_object_vars(json_decode( PwSecurity::decryptVariable(1,$extraParams)));
	   }

	   $nombreClase = PwFunciones::getIdValue($this->connection, "SITE_MENU", "ID", $keyParams["ID"], "NOMBRE");
	   
	   $fields = array("ID", "NOMBRE");
	   $condition = array("SITIO" => 2);
	   $order = array("NOMBRE");
	   $sqlResults = PwSql::executeQuery($this->connection, "FC_SYS_LIB", $fields, $condition, $order);
	   

	   $data = file_get_contents('template/utilsCard.html', true);

	   $data = preg_replace("/__HKEYPARAMS__/", $keyParamsAux, $data);	
	   $data = preg_replace("/__EXTRAPARAMS__/", $extraParamsAux, $data);

	   $trItem = $this->getLocalTemplate("trDetail");
	   $trData = "";
	   if($sqlResults)
	   {
		   $fields = array("ID_CLASE", "ID_LIB");
		   $condition = array("ID_CLASE" => $keyParams["ID"], "SITIO" => 2);
		   $order = null;
		   $sqlResultsAux = PwSql::executeQuery($this->connection, "FC_SYS_DETALLE_MODULOS", $fields, $condition, $order);
		   //PwFunciones::getVardumpLog($sqlResultsAux);

		   //Traemos los id
		   $idArray = array([]);
		   foreach($sqlResultsAux as $sqlItemAux)
		   {
			   $idArray[] = $sqlItemAux["ID_LIB"];
		   }

		   foreach ($sqlResults as $sqlItem) 
		   {
			   $trItemAux = $trItem;
			   $trItemAux = preg_replace("/__NAME__/", $sqlItem["NOMBRE"], $trItemAux);
			   $trItemAux = preg_replace("/__UTILID__/", $sqlItem["ID"], $trItemAux);		

			   //error_log("Existe:: " . $sqlResultsAux[$sqlItem["ID"]]);

			   $trItemAux = preg_replace("/__CHECKED__/", in_array($sqlItem["ID"], $idArray) ? "checked" : "", $trItemAux);		
			   
			   //error_log($sqlItem["NOMBRE"] . " :: " . $sqlItem["ID"]);

			   $trData .= $trItemAux;
		   }
	   }

	   $data = preg_replace("/__NOMBRE__/", $nombreClase, $data);
	   $data = preg_replace("/__ITEMS__/", $trData, $data);

	   $result = json_encode(array("status" => "true", "content" =>$data));

	   return $result;
   }
		
		
		
	private function detailSave()
	{
		
		$keyParams = rawurldecode(PwFunciones::getPVariable("keyParams"));    
		//Dejo en su forma original
		$keyParamsAux = $keyParams;
		//Convertimos el json en array
		if(isset($keyParams) && $keyParams != "")
		{
			$keyParams = get_object_vars(json_decode( PwSecurity::decryptVariable(1,$keyParams)));
		}

		$id = $keyParams["ID"];
		$params = array("ID_CLASE" => $id, "SITIO" => 2);
		PwSql::deleteData($this->connection,"FC_SYS_DETALLE_MODULOS",$params);

		$formParams = get_object_vars(json_decode(rawurldecode(PwFunciones::getPVariable("params"))));   
		

		if($formParams["cveUtils"] && $formParams["cveUtils"] != "")
		{
			$utilValues = explode(",", $formParams["cveUtils"]);
			foreach($utilValues as $cveUtil)
			{
				//error_log("Valor :: $cveUtil" );	

				$fields = "ID_CLASE, ID_LIB, SITIO";
				$datos = "?,?,?";			   
				$values = array($id, $cveUtil, 2);
				//insertData($connection, $tabla, $fields, $datos, $params)
				PwSql::insertData($this->connection, "FC_SYS_DETALLE_MODULOS", $fields, $datos, $values);  					   
			}
		}
		$result = json_encode(array("status" => "update", "value" =>"Datos actualizados" , "content" => ""));

		return $result;
	}


	private function getLocalTemplate($name)
	{

		$template ["trDetail"] = <<< TEMP
		<tr>
			<td>__NAME__</td>
			<td><input type= "checkbox"  id = "estatus" name = "estatus" value = "__UTILID__" __CHECKED__></td>
  		</tr>
TEMP;


	   return $template[$name];
   }
}