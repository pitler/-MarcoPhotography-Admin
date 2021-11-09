
<?php
use Pitweb\Funciones as PwFunciones;
use Pitweb\Security as PwSecurity;
use Pitweb\Connection as PwConnection;
use Pitweb\Form as PwForm;
use Pitweb\Sql as PwSql;
use Pitweb\DBClassGenerator as PwDbClassGenerator;
use Pitweb\Date as PwDate;



class CrudController //extends ArrayGrid
{

	public $cvePerfil = "";
	public $connection;
	public $doInsert = "";
	public $doUpdate = "";
	public $className = "";
	public $permisos = null;

	

	public $model = "";
	public $tableName = "";
	public $tableData = "";
	public $tableProp = null;
	
	//Variable de campos auxiliar opr si quereos sobreescribir los de default
	//Sobre todo para darle formato a las fechas  o campos de acuerdo a la base
	public $fieldsReplace = null;


	/**
	 * Propiedades para dar formato a los campos
	 */
	public $numberFormatter = array("decimalSeparator" => ".", "thousandsSeparator" =>  " ", "decimalPlaces" => 2, "defaultValue" =>  '0.00');
	public $integerFormatter = array("thousandsSeparator" => ",","defaultValue" =>  '0');
	public $currencyFormatter = array("decimalSeparator" => ".", "thousandsSeparator" =>  ',', "decimalPlaces" => 2, "prefix" => '$ ');
	
	
	
	//Constructor de la clase
	function __construct()
	{
		$this->connection = PwConnection::getInstance()->connection;    
		$this->cvePerfil = PwSecurity::decryptVariable ( 2, "cvePerfil" );		
		$this->doInsert = rawurlencode( PwSecurity::encryptVariable ( 1, "",  "doInsert") );
		$this->doUpdate = rawurlencode( PwSecurity::encryptVariable ( 1, "",  "doUpdate") );

		//Permisos para las acciones
		//Se puede modificar desde la clase hija
		$this->tableProp = array(			
	 	"cols" => 2, //Numero de columnas para presentar en el modal
	 	"add" => false, //Boton para agregar
		"update" => false, //Boton para actualizar
		"delete" => false, //Boton para eliminar
		"dateDefaultFormat" => "d-m-Y", //Formato de default para las fechas
		"fixed" =>array("left" => 1, "right" => 1), //Formato para ver si se bloquean las columnas
		"scrollY" =>400 ,// Para el tamaño del scroll en Y, por default 500
		"listQuery" => null, //Si queremos enviar un query armado para presentar la lista
		"listQueryParams" => null, //Parametros para el query definido en listQuery
		"extraActions" => null, //Parámetros extra
		//"noFilterVars" => null, //Array con las variables a inserat o actualizar que no necesitan validación en los filtros
		
		);


		$this->permisos = PwSecurity::validateAccess($this->className, $this->cvePerfil, $this->connection);

		$this->tableProp["add"] = $this->permisos["INSERTAR"];
		$this->tableProp["update"] = $this->permisos["ACTUALIZAR"];
		$this->tableProp["delete"] = $this->permisos["BORRAR"];

	}


	public function getForm()
    {    	
		
		error_log("Entro a crud controller");
    	$keyParams = rawurldecode( PwFunciones::getPVariable("keyParams"));

    	$sqlItem = null;
    	$editFlag = false;

    	//Array para los campos a validar
    	$validateArr = array();

    	$keyParams = json_decode(PwSecurity::decryptVariable(1, $keyParams)); 

    	//Variable para guardar los datepickers si existen
    	$datePickers = "";

    	//Si tiene keyParam, entramos a modo edicion
    	if($keyParams)
    	{
			
    		$sqlResults = PwSql::executeQuery($this->connection,$this->tableName, null, $keyParams);

    		if($sqlResults)
    		{
    			$sqlItem = $sqlResults[0];
    		}

    		$editFlag = true;    		
    	}

    	//Si quieres editar y no tiene valores a pintar en el form
    	if(!$sqlItem && $editFlag == true)
    	{
    		$msg = PwFunciones::getErrorMessage(202);
			$result = json_encode(array("status" => false, "content" =>$msg));
    		return $result;
    	}

		$data= $this->getTemplate("mainForm");
    	$row = $this->getTemplate("row");
    	$formData = "";

    	//Forma e que se acomodan las filas y columnas
    	$xs = 12;
        $sm = 12;
        $md = 6;
        $lg = 6;

       if($this->tableProp["cols"] == 1)
       {
            $xs = 12;
            $sm = 12;
            $md = 12;
            $lg = 12;
       }

       if($this->tableProp["cols"] == 3)
       {
            $xs = 12;
            $sm = 6;
            $md = 4;
            $lg = 4;
       }
	   
	   
   		if($this->model)
		{
			$cont = 1;
			$data .= $row;
			foreach ($this->model  as $key=> $modelParams) 
			{

				$formElement = self::getTemplate("formElement");

				//Si no tiene id, lo quitamos
				if(!$modelParams["id"])
				{
					PwFunciones::setLogError(200, $key);
					continue;
				}

				//Si no es editable, lo deshabilitamos del form
				if($editFlag == true &&  isset($modelParams["editable"]) && $modelParams["editable"] == false)
				{
					$modelParams["disabled"] = true;
				}
				
				//Tamaño del div
				$formElement = preg_replace("/__XS__/", $xs, $formElement);
   				$formElement = preg_replace("/__SM__/", $sm, $formElement);
       			$formElement = preg_replace("/__MD__/", $md, $formElement);
       			$formElement = preg_replace("/__LG__/", $lg, $formElement);

       			//Para el label
       			$label = "";
		        if(isset($modelParams["label"]))
		        {
		            $label = self::getTemplate("labelField");
		            $label = preg_replace("/__ID__/", $modelParams["id"], $label);
		            $label = preg_replace("/__LABEL__/", $modelParams["label"], $label);
		        }
		        $formElement = preg_replace("/__LABEL__/", $label, $formElement);

		        //Si es campo forzoso
		        $required = "";
		        if(isset($modelParams["required"]) && $modelParams["required"] == true)
		        {
		            $required = self::getTemplate("requiredField");
		            $required = preg_replace("/__NAME__/", $modelParams["id"], $required);
		           
		           //Creamos el array a leer
		            $validateItems = array("name" => $modelParams["id"] , "type" => "default");
		            
		            //Si exiten validaciones por campo, en un array
		            if(isset($modelParams["validateField"]) && $modelParams["validateField"] != "")
		            {
						$validateItems["type"] = $modelParams["validateField"];		            
		            }

		            $validateArr[] =  $validateItems;
		        }
		        $formElement = preg_replace("/__REQUIRED__/", $required, $formElement);

				//Vamos por el tipo de campo	
				$type = $modelParams["type"];

				if(isset($sqlItem[$modelParams["id"]]))
				{
					$modelParams["value"] = $sqlItem[$modelParams["id"]];
				}

				//Llama una funcion definida en la clase hija, se debe agregar los parametros necesarios				
				if(isset($modelParams["formAddFunction"]) && $modelParams["formAddFunction"] != "" && $editFlag == false)
				{
					$funcName = $modelParams["formAddFunction"];							
					$value = call_user_func($this->className."Controller::".$funcName);
					$modelParams["value"] = $value;
				}


				switch($type)
				{
					case "text" :					
						$field = PwForm::getText($modelParams);
						break;
					case "password" :					
						$field = PwForm::getPassword($modelParams);
						break;
					case "textarea" :					
						$field = PwForm::getTextArea($modelParams);
						break;
					case "select" :
						 $field = PwForm::getFormSelect($modelParams);
					break;
					case "check" :
						 $field = PwForm::getFormCheck($modelParams);
					break;
					case "datepicker" :
						 $field = PwForm::getFormDatepicker($modelParams, $this->tableProp["dateDefaultFormat"]);
						 $arrDatePicker[$modelParams["id"]] = isset($modelParams["jsformat"]) ? $modelParams["jsformat"] : null ;
					break;

					default :
						$field = PwForm::getText($modelParams);
					break;

				}
				$formElement = preg_replace("/__FIELD__/", $field, $formElement);						

				$formData .= $formElement;

				//Para hacer las filas
				if($cont ==$this->tableProp["cols"])
				{

					$data = preg_replace("/__DATA__/", $formData, $data);
					$data .=$row;					
					$formData = "";
					$cont = 0;
				}

				$cont++;
			}

			
			$data = preg_replace("/__DATA__/", $formData, $data);
			
			if($editFlag == true)
			{
				$paramsAux = array("id" =>"editFlag", "value" => 2);
				$data .=  PwForm::getHidden($paramsAux);
			}


			//Si hay campos datepicker
			if(isset($arrDatePicker))
			{
				$datePickerTemp = self::getTemplate("datepicker");	

				foreach ($arrDatePicker as $dpKey => $dpItem) 
				{
					$datePickers .= $datePickerTemp;
					$datePickers = preg_replace("/__ID__/", $dpKey, $datePickers);
					$format = "";
					if(isset($dpItem) && $dpItem != "")
					{
						
						$format = self::getTemplate("datepickerFormat");
						$format = preg_replace("/__FORMAT__/", $dpItem, $format);
					}
					
					$datePickers = preg_replace("/__FORMAT__/", $format, $datePickers);
				}
			}
			$data = preg_replace("/__DATEPICKERS__/", $datePickers, $data);
			
			//Ponemos los campos a validar			
			$data = preg_replace("/__VALIDATEARR__/", json_encode($validateArr), $data);
		}

		$showModal = PwFunciones::getPVariable("showModal");
		
		if($showModal == 1)
		{
			$content = $data;
			
		}
		else
		{
			$content= $this->getTemplate("mainContent");
			$footer = $this->getTemplate("mainContentFooter");
			$content = preg_replace("/__FOOTER__/",  $footer, $content);
			$content = preg_replace("/__CONTENT__/",  $data, $content);
		
		}


	

		$result = json_encode(array("status" => "true", "content" =>$content));

    	return $result;
    }


    /** 
	 * Función que nos regresa el código que se genera para pintar el grid
	 */	
	protected function getList($resultFlag = false)
    {

		$data = $this->getTemplate("mainTable");

		$tableHead = $this->getTemplate("trHead");
		$headItem = $this->getTemplate("thHead");
		$trBody = $this->getTemplate("trBody");
		$tdBody = $this->getTemplate("tdBody");
		$tdUpdate = $this->getTemplate("tdUpdate");
		$tdDelete = $this->getTemplate("tdDelete");
		$tdAcciones = $this->getTemplate("tdActions");
		$tdExtra = $this->getTemplate("tdExtra");

 		$headItems = "";
 		$body = "";

 		$fields = array();
 		$fieldsType = array();
 		$order = array();

		 //Llaves de la tabla
		 $keys = null;

		//Si es query personalizado, traemos las llaves del modelo
		 if($this->tableProp["listQuery"] != null)
		 {	 
			 
			foreach($this->model as $key=>$item)
			{
				if(isset($item["key"]) && $item["key"] == true)
				{
					$keys[] = $item["id"];
				}
			}		
		 }
		 
		  //Si es normal, se llama la funcion que trae los datos de la definicion de la tabla
		 else
		 {
			
			$keys = $this->getKeys(); 
		 }

		 
 		
 		$keyVals = array();

		
 		//Por cada elemento del modelo
		foreach ($this->model as $modelItem) 
		{		
				
				//Guardamos el array con los campos a mostrar
				$fields[] = $modelItem["id"];

				//Las propiedades que usamos para validar los campos
				$fieldsType[$modelItem["id"]] = array(
						"type" => $modelItem["type"], 
						"value" => isset($modelItem["arrValues"]) ? $modelItem["arrValues"] : "",
						"format" => isset($modelItem["format"]) ? $modelItem["format"] : null,
						"encode" => isset($modelItem["encode"]) ? $modelItem["encode"] : null,
						"hideColumn" => isset($modelItem["hideColumn"]) ? $modelItem["hideColumn"] : null,						
				);

				//Para esconder la columna de la cabecera
				if($fieldsType[$modelItem["id"]]["hideColumn"] == true)
				{
					continue;
				}


				//Guardamos el ultimo order que encuentre
				if(isset($modelItem["order"]))
				{

					error_log("Pongo order :: " . $modelItem["id"]);
					$order = array($modelItem["id"]." ".$modelItem["order"]);
				}		

				//Vamos por los encabezados
				$headItems .= $headItem;
				$headItems = preg_replace("/__DATA__/", $modelItem["label"], $headItems);
		}

		

		if($this->tableProp["update"] == true  || $this->tableProp["delete"] == true)
		{
			$headItems .= $headItem;
			//$headItems .= $headItem .headItems;
			$headItems = preg_replace("/__DATA__/", "Acciones", $headItems);

		}

		
		//Ponemos la info del header
		$tableHead = preg_replace("/__ITEMS__/", $headItems, $tableHead);
		$data = preg_replace("/__HEAD__/", $tableHead, $data);

		//ejecutamos el query
		$condition = null;

		

		//Si se envia un query predefinido para pintar la lista
		if($this->tableProp["listQuery"] != null)
		{

			$consulta =  $this->tableProp["listQuery"];
			$ps = PwSql::setSimpleQuery($this->connection,$consulta );			
			$params = $this->tableProp["listQueryParams"];
			$sqlResults = PwSql::executeSimpleQuery($ps, $params, $consulta);         
			
		}
		//Si el query se hace de forma normal
		else
		{
			
			//Si vienen parametros por medio del filtro y no fue actualizacion ni inserción	
			parse_str($_POST['filterParams'], $filterParams);		
			
			if($filterParams && sizeof($filterParams) > 0)
			{
				foreach($filterParams as $key=>$filter)
				{
					//Quitamos el editFlag por si viene
					if(trim($filter) != "")
					{
						$condition[$key] = $filter;
					}
				}
			}
			$queryFields = $fields;

			//Para reemplazar los campos del array predeterminado
			//Por lo general para las fechas en oracle
			if($this->fieldsReplace)
			{
				foreach ($this->fieldsReplace  as $fKey => $fValue) 
				{
					$position = array_search($fKey, $queryFields);					
					if($position)
					{
						$queryFields[$position] = $fValue;
					}
				}				
			}
			//PwFunciones::getVardumpLog($order);
			$sqlResults = PwSql::executeQuery ( $this->connection,$this->tableName, $queryFields, $condition, $order );
		}
		//PwFunciones::getVardumpLog($sqlResults);
		
		$trFields = "";
		if($sqlResults)
		{
			$trFields = "";
			
			//Por cada resultado
			$keyVals = array();

			foreach ($sqlResults as $sqlItem) 
			{		

				$tdFields = "";
				$trBodyAux = $trBody;
				//PwFunciones::getVardumpLog($sqlItem);

				//error_log($sqlItem["ID"]."-" . $sqlItem["NOMBRE"]."-".$sqlItem["ORDEN"]);
				//Por cada campo				
				foreach ($fields as $fieldName) 
				{	

					$value =  $sqlItem[$fieldName];

					if(in_array($fieldName, $keys))
					{						
						$keyVals[$fieldName] = $value;
					}

					//Para esconder la columna del body
					if($fieldsType[$fieldName]["hideColumn"] == true)
					{

						continue;
					}

				
					
					//Si es un select normal
					if ($fieldsType[$fieldName]["type"] == "select" && isset( $fieldsType[$fieldName]["value"][$sqlItem[$fieldName]]))
					{
						$value = $fieldsType[$fieldName]["value"][$sqlItem[$fieldName]];						
					}

					//Si es un datepicker y tiene valor
					if($fieldsType[$fieldName]["type"] == "datepicker" && $value != "")
					{
						//Si existe un valor
						if($value && $value != "")
						{
							//Preparamos la fecha para formatear
							$value= str_replace("/", "-", $value);
							$value = date_create($value);
							//si tiene un formato especial
							if($fieldsType[$fieldName]["format"] != null)
							{
								
								$value =  date_format($value,  $fieldsType[$fieldName]["format"]);						
							}
							//Usamos el formato de default que se define al inicio de la clase
							else
							{
								$value =  date_format($value,  $this->tableProp["dateDefaultFormat"]);					
							}
						}											
					}

					//Para el encode
					if($fieldsType[$fieldName]["encode"] == true)
					{
						$value = htmlentities(rawurldecode($sqlItem[$fieldName]));
					}

					$tdBodyAux = $tdBody;
					$tdBodyAux = preg_replace("/__DATA__/", $value, $tdBodyAux);
					$tdFields .= $tdBodyAux;
				}

				//Para update, delete o acciones extra
				if($this->tableProp["update"] == true || $this->tableProp["delete"] == true || $this->tableProp["extraActions"])
				{
					
					$tdFields .= $tdAcciones;
					//$tdFields = $tdAcciones . $tdFields;
					$acciones = "";
					//PwFunciones::getVardumpLog($keyVals);
					$kv = rawurlencode(PwSecurity::encryptVariable(1, "", json_encode($keyVals)));


					if($this->tableProp["update"] == true)
					{
						$acciones .= $tdUpdate;						
						$acciones = preg_replace("/__KEYS__/", $kv, $acciones);						
					}

					if($this->tableProp["delete"] == true)
					{
						$acciones .= $tdDelete;
						$acciones = preg_replace("/__KEYS__/", $kv, $acciones);
					}

					//Para acciones extras
					if(isset($this->tableProp["extraActions"]) && is_array($this->tableProp["extraActions"]))
					{

					
						foreach($this->tableProp["extraActions"] as $action)	
						{
					
							$acciones .= $tdExtra;
							$acciones = preg_replace("/__EXTRABTNBUTTON__/", $action["button"], $acciones);
							$acciones = preg_replace("/__BTNNAME__/", $action["name"], $acciones);
							$acciones = preg_replace("/__EXTRAFN__/", $action["function"], $acciones);
							$acciones = preg_replace("/__EXTRAICON__/", $action["icon"], $acciones);							
							$acciones = preg_replace("/__TITLE__/", $action["title"], $acciones);

							$acciones = preg_replace("/__KEYS__/", $kv, $acciones);

							//Si tenemos campos extras
							$extras = null;							
							if(isset($action["extras"]))
							{
								foreach($action["extras"] as $extra)
								{
									$extras[$extra] =  $sqlItem[$extra];									
								}
							
								$extras = rawurlencode(PwSecurity::encryptVariable(1, "", json_encode($extras)));
							}
							$acciones = preg_replace("/__EXTRAS__/", $extras, $acciones);

							//Para enviar parametros en el modal
							$modalParams = null;							
							if(isset($action["params"]))
							{								
								$modalParams = rawurlencode(PwSecurity::encryptVariable(1, "", json_encode($action["params"])));
							}
							$acciones = preg_replace("/__PARAMS__/", $modalParams, $acciones);
						}
					}

					$tdFields = preg_replace("/__ACCIONES__/", $acciones, $tdFields);				
				}

				//Ponemos los campos <td> en el <tr>
				$trBodyAux = preg_replace("/__ITEMS__/", $tdFields, $trBodyAux);				
				//Contactenamos los <tr>
				$trFields .= $trBodyAux;				
			}
		}

		//Para hace el fix de las dataTables
		$fixed = "";
		if(isset($this->tableProp["fixed"]))		
		{
			$fixed = $this->getTemplate("fixed");

			$left = $this->tableProp["fixed"]["left"] ;
			$right = $this->tableProp["fixed"]["right"] ;

			$fixed = preg_replace("/__FLEFT__/", $left, $fixed);
			$fixed = preg_replace("/__FRIGHT__/", $right, $fixed);
		}

		$data = preg_replace("/__FIXED__/", $fixed, $data);	


		//Para poner el scroll en Y
		//Para hace el fix de las dataTables
		$scrollY = "";
		if(isset($this->tableProp["scrollY"]))		
		{
			$scrollY = $this->getTemplate("scrollY");
			$size = $this->tableProp["scrollY"];		
			$scrollY = preg_replace("/__VAL__/", $size, $scrollY);			
		}

		$data = preg_replace("/__SCROLLY__/", $scrollY, $data);	

		//Pintamos los <tr> en el body
		$data = preg_replace("/__BODY__/", $trFields, $data);	
		
		if($resultFlag == true)
		{			
			return $data;
		}

	
	    $result = json_encode(array("status" => "true", "content" =>$data, "message" => "", "type" => "success"));
	    
		return $result;

    }

	/**
	 * Función que se encarga de hacer la inserción del formulario a la base de datos
	 * @param $keyVal 			Boolean		Nos dice si regresamos o no el último key insertado en la tabla
	 * @param $encriptVals		Array		Array con el o los nombres de los campos que vamos a encriptar (Se usa el sha256 por defecto)
	 * @param $overrideFields	Array		Array asociativo con los campos que se sobreescriben de los que trae el formulario, se envian desde el hijo
	 */
	protected  function doInsert($keyVal = null, $encriptVals = null, $overrideFields= null)
    {    	

		

		//Parseamos los datos de la forma
		parse_str($_POST['formParams'], $formParams);

    	//Validamos que no se repitan las lllaves
    	$validateFields = json_decode(rawurldecode(PwFunciones::getPVariable("validateFields")));

    	
    	if($validateFields && sizeof($validateFields) >=1 )
    	{
    		$conditionArr = null;
    		$fieldsArr = null;
    		foreach ($validateFields as $field) 
    		{
    			$conditionArr[$field] = $formParams[$field];
    			$fieldsArr[] = $field;
    		}

    		$result = $this->validateInsert($fieldsArr, $conditionArr);
    		if($result)
    		{
    			return $result;
    		}
    	}

		//Leemos el array y armamos el query con las llaves y los datos
		$keyFields = array();
		$datos = array();

	

		//Traemos los campos de a tabla
		$tableData = $this->getTableData();

		$cont = 0;		

		$fields = array();
		$datos = array();
		$params= array();

		
		foreach ($this->model as $modelItem)
		{
			//Nombre del campo
			$name = $modelItem["id"];

			//Valor del campo			
			//Si envio un array como parametro
			if(isset($formParams[$name]) && is_array($formParams[$name]))
			{
				$varArray = json_encode($formParams[$name]);
				$value = PwFunciones::getVariable($varArray);
			}
			//Si es un valor normal
			else
			{
				$value = PwFunciones::getVariable(isset($formParams[$name])?$formParams[$name] : null );
			}


  			//Si es un check y viene vacio, ponemos el vacio
			if($modelItem["type"] == "check" && !$value)
			{   	
			  	$value = $modelItem["value"];
			}

			//Si es un check y viene vacio, ponemos el vacio
			//Revisar para que sirve este
			if($modelItem["type"] == "select")
			{   	
				//error_log("Select1");

				//PwFunciones::getVardumpLog($_POST);
				//PwFunciones::getVardumpLog($value);
			}



			//Si es un consecutivo
			//Solo para text
			if($modelItem["type"] == "text" && isset($modelItem["consecutivo"]) && $modelItem["consecutivo"] == true)
			{
				//Si no trae un valor por default, calcula el consecutivo
				if($value == 0)
				{
					
					$value = PwFunciones::getConsecutivo ( $this->connection, $this->tableName, $name);	
				}
			}
			
			//Si es un datepicker, vemos cual es el formato para guardarlo en la base
			//Acepta 2 formatos d-m-Y H:i:s y d-m-Y , si se necesita otro agregarlo en la función de Date
			if($modelItem["type"] == "datepicker")
			{
				if($value && $value != "")
        		{            	
					$dateType = PwDate::getDateType(isset($modelItem["format"]) ? $modelItem["format"] : $this->tableProp["dateDefaultFormat"]);					
					$value = PwDate::getDateFormat($value, $dateType);				
				}
				   		
			}

			//Si es una fecha, depende del motor de base de datos le damos el formato
			if($modelItem["type"] == "date")
			{
			    switch (DBASE)
			    {
			    	//Traemos la fecha en formato mySql
			    	case 1 : 
			        $value = PwFunciones::getDateFormat($value, 2);
			        break;
			        //Para oracle
			        case 2 : 
			        $value = PwFunciones::getDateFormat($value, 1);
			        break;
			        //Para mysql
			        case 3 : 
			        $value = PwFunciones::getDateFormat($value, 3);
			        break;				
				}
				
			//	error_log("Date ;; $value");
			}


			if(isset($modelItem["encode"]) && $modelItem["encode"] == true)
			{
		    	$value = rawurlencode(html_entity_decode(PwFunciones::getVariable($formParams[$name], false, false)));			    	 
			}
			    
			 $fields[] = $name;
			 $datos[] = "?";		
			 
			//Si mandamos a encriptar el campo
			 if(isset($encriptVals) && in_array($name, $encriptVals))
			 {				 
				 $value = hash("sha256", $value);				 
			 }

			 //Para sobreescribir el valor del campo por lo que tenga, se envía desde el hijo
			 if(isset($overrideFields) && isset($overrideFields[$name]["value"]))
			 {				
				 $value = $overrideFields[$name]["value"];
				 
			 }
			 $params[] = $value;
		}
		
		
		if($datos && $fields)			
		{
			$strDatos= implode(",", $datos);
			$strFields = implode(",", $fields);
			
			$sqlResult = PwSql::insertData($this->connection, $this->tableName, $strFields, $strDatos, $params);
		}

		$data = self::getList(true);
    	$result = json_encode(array("status" => "true", "content" =>$data, "message" => "Datos insertados con éxito", "type" => "success", "modal" => "close"));		

		return $result;
    }

	
	/**	  
	 * Función encargada de ejecutar actualizaciones en la tabla usada por el grid	 * 
	 */
	protected function doUpdate()
	{
		

		$consulta = false;
		$content = "";
		//Verificamos si se tienen permisos para actualizar
		
		//Leemos el array y armamos el query con las llaves y los datos
		$keyFields = array();
		$datos = array();

		//Traemos los campos de la tabla
		$tableData = $this->getTableData();
	
		//Parseamos los datos de la forma
		parse_str($_POST['formParams'], $formParams);

		
		$cont = 0;		

		foreach ($this->model as $modelItem)
		{

			//Si no es editable, continuamos
			if(isset($modelItem["editable"]) && $modelItem["editable"] == true)
			{
				continue;
			}

			//Nombre del campo 
			$name = $modelItem["id"];
			

			//Si envio un array como parametro
			if(isset($formParams[$name]) && is_array($formParams[$name]))
			{
				$varArray = json_encode($formParams[$name]);
				$value = PwFunciones::getVariable($varArray);
			}
			//Si es un valor normal
			else
			{
				$value = PwFunciones::getVariable(isset($formParams[$name])?$formParams[$name] : null );
			}


			
			
			$tableItem = $tableData[$name];
			if($tableItem["key"]  == 1)
			{
				$keyFields[$name] = $value;
			}
			else
			{
				//PwFunciones::getVardumpLog($tableItem);
				//Si es un datepicker
				/*if($tableItem["type"] == "datepicker")
				{
					
					if($value && $value != "")
        			{
						$value = date_create($value);

            			if(isset($tableItem["format"]) && $tableItem["format"] != null)
            			{
							$value =  date_format($value,  $tableItem["format"]);                      
            			}
            			//Usamos el formato de default que se define al inicio de la clase
            			else
            			{
                			$value =  date_format($value,  $this->tableProp["dateDefaultFormat"]);                       
            			} 
					}  
					
					error_log("Edit Datepicker $name:: $value");

        			
				}*/

				//Si es una fecha, depende del motor de base de datos le damos el formato
				if($tableItem["type"] == "date")
				{
					//error_log("Date ::$name");
				    switch (DBASE)
				    {
				    	//Traemos la fecha en formato mySql
				    	case 1 : 
				        $value = PwDate::getDateFormat($value, 2);
				        break;
				        //Para oracle
				        case 2 : 
				        $value = PwDate::getDateFormat($value, 1);
				        break;
				        //Para mysql
				        case 3 : 
				        $value = PwDate::getDateFormat($value, 3);
				        break;
				
					}
				    	
			    }
			    
			    //Esto es para fechas DATETIME de SQL , se manda en formato YYYYmmdd 
			    if($tableItem["type"] == 'datetime')
			    {

					//error_log("DateTime :: $name");
			       switch (DBASE)
				    {
				    	//Para mySql
				    	case 1 : 
				        $value = PwDate::getDateFormat($value, 11);
				        break;
				        //Para oracle
				        case 2 : 
				        $value = PwDate::getDateFormat($value, 10);
				        break;
				        //Para sqlServer
				        case 3 : 
				        $value = PwDate::getDateFormat($value, 12);
				        break;
				
					}
					
					//	$value = "to_date(?, 'DD-MM-YYYY HH24:MI:SS')//$value";
				    	
			    }

			    //Si es smalldate time			    
				if($tableItem["type"] == 'smalldatetime')
			    {
			         $value = PwFunciones::getDateFormat($value, 12);			        
			    }

			    //Si es un check y viene vacio, ponemos el vacio
			    if($modelItem["type"] == "check" && !$value)
			    {
			    	
			    	$value = $modelItem["value"];
			    }

			    //Para hacer un rawurl encode
			    if(isset($modelItem["encode"]) && $modelItem["encode"] == true)
			    {

		    		$value = rawurlencode(html_entity_decode(trim(PwFunciones::getVariable($formParams[$name], false, false))));			    	 
			    }


			    $datos[$name] = $value;			   
			}
		}

		//Si el array de llaves lleva al menos 1 valor
		//Ejecutamos el update
		if($datos && sizeof($keyFields) >= 1)
		{				
			
			PwSql::updateData($this->connection, $this->tableName, $datos, $keyFields);
		}


		$data = self::getList(true);
	//	PwFunciones::getVardumpLog($data);
    	$result = json_encode(array("status" => "true", "content" =>$data, "message" => "Datos actalizados con éxito", "type" => "success", "modal" => "close"));
		return $result;

	}	

	protected function doDelete()
	{

		$keyParams = rawurldecode(PwFunciones::getPVariable("formParams"));    
		$keyParams = json_decode( PwSecurity::decryptVariable(1,$keyParams));       
		
		if($keyParams)
		{	
			foreach ($keyParams as $key => $value) 
			{
				$keyFields[$key] = $value;
			}

			
			$sqlResult = PwSql::deleteData($this->connection,$this->tableName,$keyFields);		
		}

		$data = self::getList(true);
    	$result = json_encode(array("status" => "true", "content" =>$data, "message" => "Datos eliminados con éxito", "type" => "success", "modal" => "close"));
		return $result;


	}


	private function validateInsert($fields = null, $condition = null)
	{

		$result =  "";
		$order = null;

		
		$sqlResults = PwSql::executeQuery($this->connection, $this->tableName, $fields, $condition, $order);

		$type = "success";
		$message = "";

		if(sizeof($sqlResults) >= 1)
		{
			$type = "error";
			$message = "La llave a insertar ya ha sido usada";
			return  json_encode(array("status" => "true", "content" =>"", "message" => $message, "type" => $type, "modal" => "open"));
		}

		return null;
	}

	
	public function getTableData()
	{
	
		
		$data = PwDbClassGenerator::verifyClass($this->tableName);
		
		if(!$data)
		{
			
			$result = PwDbClassGenerator::createClass($this->tableName, $this->connection);		
			if($result === false)
			{				
				
				PwFunciones::getLogError(201);
			}	
		}
		
		$data = PwDbClassGenerator::getClassContent($this->tableName);
		
		return $data;		
	}


    //Trae los campso llave de la tabla
    public function getKeys()
    {
    	$keys = array();
    	$tableData = $this->getTableData();
    	
    	foreach ($tableData as $key => $tableItem) 
    	{
    		if($tableItem["key"] == 1)
    		{
    			$keys[] = $key;
    		}
    	}
    	return $keys;
    }


   


    public static function getTemplate($name)
	{


		$template ["mainContent"] = <<< TEMP
	
		<div class="row">
    		<div class="col-12">
				<div class="card">        
			   		<form class="g-brd-around g-brd-gray-light-v4 g-pa-10 " role="form" id="mainForm" name="mainForm">
					<div class="card-body">				
						__CONTENT__				
					</div>
					__FOOTER__				
					</form>
				</div>
			</div>
		</div>
	
TEMP;
	

$template ["mainContentFooter"] = <<< TEMP
	

		<div class="card-footer">
			<button class="btn btn-info" style="margin-right: 5px;" id="btnSave" name="btnSave"  onclick="addForm();return false;"><i class="fa fa-save"> </i> Guardar</button>					
		</div>

TEMP;



	$template ["mainForm"] = <<< TEMP
	
	<script>
		$(document).ready(function() {
			__DATEPICKERS__
			validateArr = '__VALIDATEARR__';
		});
    </script>

TEMP;


	$template ["datepicker"] = <<< TEMP
	$( '#__ID__' ).datepicker(
		{
			__FORMAT__
		}
	);
TEMP;


	$template ["datepickerFormat"] = <<< TEMP
	dateFormat:'__FORMAT__'	
TEMP;



	$template ["row"] = <<< TEMP
	<div class = 'row'>
		__DATA__
	</div>
TEMP;


    $template["formElement"] = <<< TEMP
    <div class="form-group col-xs-__XS__ col-sm-__SM__ col-md-__MD__ col-lg-__LG__">
        __LABEL__
        __FIELD__
        __REQUIRED__
    </div>
TEMP;
       

    $template["requiredField"] = <<< TEMP
    <small class="form-control-feedback" id = "__NAME__Error"></small> 
TEMP;

    $template["labelField"] = <<< TEMP
    <label for="__ID__">__LABEL__</label>
TEMP;

	$template["mainTable"] = <<< TEMP

	<div class="row">
    	<div class="col-12">
      		<div class="card">        
        		<div class="card-body">
					<table id="table" class="table table-bordered table-striped  table-hover nowrap" style = "width:100%;" >
       					<thead>
           					__HEAD__
       					</thead>
       					<tbody>           
          					__BODY__
       					</tbody>       
   					</table>
				</div>
			</div>
		</div>
	</div>

    <script>
		$(document).ready(function()
        {
        	var table = $('#table').DataTable({
				"pageLength": 50,
            	"language": 
		    	{
		    		url: 'pw/assets/adminlte/plugins/datatables/localisation/es.json'
		    	},      
		    	__SCROLLY__
		    	"scrollX": true,		    	 
				__FIXED__
				dom: 'Bfrtip',
				buttons: [					
					{ extend: 'excel', className: 'btn-info' },
				],
				//"order": [[ 7, "asc" ]]
				
			});

			
		

			
		});

	</script>

TEMP;

/*** PARA LAS DATATABLES ***/

$template["fixed"] = <<< TEMP
		fixedColumns:   
		{         	   
			leftColumns: __FLEFT__,
            rightColumns: __FRIGHT__  ,          		
        },
TEMP;

/*PARA SCROLL EN Y*/
$template["scrollY"] = <<< TEMP
	"scrollY": "__VAL__px",
TEMP;



 $template["trHead"] = <<< TEMP
		<tr>
			__ITEMS__
        </tr>
TEMP;


 $template["thHead"] = <<< TEMP

		<th>__DATA__</th>
TEMP;


 $template["trBody"] = <<< TEMP

		<tr>
			__ITEMS__
        </tr>
TEMP;

 $template["tdBody"] = <<< TEMP
		
	<td>__DATA__</td>
        	
TEMP;


 $template["tdActions"] = <<< TEMP
		<td>__ACCIONES__</td>        	
TEMP;

$template["tdUpdate"] = <<< TEMP
 <a href="#"   class="btn btn-sm u-btn-lightblue" id = "btnEdit" name = "btnEdit" data-toggle="tooltip" title="Editar" onclick="getModal('__KEYS__');return false;">
	<i class="fa fa-edit"></i>
</a> 
TEMP;

$template["tdDelete"] = <<< TEMP
 <a href="#"   class="btn btn-sm u-btn-lightblue" id = "btnDel" name = "btnDel" data-toggle="tooltip" title="Eliminar" onclick="doDelete('__KEYS__');return false;">
	<i class="fa fa-trash"></i>
</a> 
TEMP;

$template["tdExtra"] = <<< TEMP
 <a href="#"   class="btn btn-sm __EXTRABTNBUTTON__" id = "__BTNNAME__" name = "__BTNNAME__" data-toggle="tooltip" title="__TITLE__" onclick="__EXTRAFN__('__KEYS__', '__TITLE__', '__EXTRAS__', '__PARAMS__');return false;">
	<i class="__EXTRAICON__"></i>
</a> 
TEMP;


	return $template[$name];
	}
	

}