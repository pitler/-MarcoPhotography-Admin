<?php
use Pitweb\Funciones as PwFunciones;
use Pitweb\Security as PwSecurity;
use Pitweb\Sql as PwSql;
use Pitweb\Form as PwForm;


class PerfilesSitioController extends CrudController
{

	 //Constructor de la clase
  function __construct()
  {
      //Validamos permisos de la clase con los perfiles
      $this->className = "Modulos";        
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
		
			
		//Propiedades para un boton extra
		/*$this->tableProp["extraActions"] = array(
			array("button" => "u-btn-info", "name" => "btnGrid", "function" => "rangosData", "icon" => "fa fa-clone", "title" => "Asignación de librerias"),
		);*/


		//Traemos el modelo
        $this->model = $this->getModel();
        $this->tableName = "SITE_PERFILES";


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


   		$arrTipo = PwSql::executeQuery($this->connection, "SITE_TIPO_USUARIO", array("ID", "NOMBRE"), null, array("NOMBRE"));
   		$arrTipo = PwFunciones::getArrayFromSql($arrTipo, "ID", "NOMBRE");
PwFunciones::getVardumpLog($arrTipo);

   	//	$operadora = PwSql::executeQuery($this->connection, "SYS_OPERADORAS", array("RAIZ", "NOMBRE"), null, array("NOMBRE"), );
   	//	$operadora = PwFunciones::getArrayFromSql($operadora, "RAIZ", "NOMBRE");


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
   				"type" => "text",
   				"label" => "Clave perfil",   
   				"disabled" => false,				
				"required" => true,
				//"editable" => false,
			),

			array(
   				"id" => "DESC_PERFIL",   				   				
   				"type" => "text",
   				"label" => "Descripción",   				   				
   				"disabled" => false,
   				"required" => true		

			),


			/*array(
   				"id" => "OPERADORA_DEFAULT",   				   				
   				"type" => "select",
   				"label" => "Operadora default",   				   				
   				"disabled" => false,
   				"required" => true,
   				"space" => true,
   				"arrValues" => $operadora,
			),*/

            array(
                "id" => "TIPO",   				   				
                "type" => "select",
                "label" => "Tipo usuario",   				   				
                "disabled" => false,
                "required" => true,
                "space" => true,
                "arrValues" => $arrTipo,

         ),

			array(
   				"id" => "STATUS",   				   				
   				"type" => "check",
   				"label" => "Estatus",
   				"disabled" => false,
   				"required" => false,
   				"value" => "0",
   				"defaultValue" => 1,
			),
	
	

   		);

		return $model;
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
		$params = array("ID_CLASE" => $id, "SITIO" => 1);
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
				$values = array($id, $cveUtil, 1);
				//insertData($connection, $tabla, $fields, $datos, $params)
				PwSql::insertData($this->connection, "FC_SYS_DETALLE_MODULOS", $fields, $datos, $values);  					   
			}
		}
		$result = json_encode(array("status" => "update", "value" =>"Datos actualizados" , "content" => ""));
		return $result;
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

		$nombreClase = PwFunciones::getIdValue($this->connection, "FC_SYS_MODULOS", "ID", $keyParams["ID"], "NOMBRE_CLASE");
		
		$fields = array("ID", "NOMBRE");
		$condition = array("SITIO" => 1);
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
			$condition = array("ID_CLASE" => $keyParams["ID"], "SITIO" => 1);
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