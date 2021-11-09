
<?php
use Pitweb\Funciones as PwFunciones;
use Pitweb\Security as PwSecurity;
use Pitweb\Connection as PwConnection;
use Pitweb\Sql as PwSql;
use Pitweb\Date as PwDate;

class ListController //extends ArrayGrid
{

	public $cvePerfil = "";
	public $connection;
	public $permisos = null;

	public $model = "";
	public $tableName = "";
	public $tableData = "";
	public $tableProp = null;
	public $numberFormatter = array("decimalSeparator" => ".", "thousandsSeparator" =>  " ", "decimalPlaces" => 2, "defaultValue" =>  '0.00');
	public $integerFormatter = array("thousandsSeparator" => ",","defaultValue" =>  '0');
	public $currencyFormatter = array("decimalSeparator" => ".", "thousandsSeparator" =>  ',', "decimalPlaces" => 2, "prefix" => '$ ');
	
	
	//Variable de campos auxiliar opr si quereos sobreescribir los de default
	//Sobre todo para darle formato a las fechas  o campos de acuerdo a la base
	public $fieldsReplace = null;
	
	
	//Constructor de la clase
	function __construct()
	{
		$this->connection = PwConnection::getInstance()->connection;    
		$this->cvePerfil = PwSecurity::decryptVariable ( 2, "cvePerfil" );		

		//Permisos para las acciones
		//Se puede modificar desde la clase hija
		$this->tableProp = array(				 	
	 	
		"dateDefaultFormat" => "d-m-Y", //Formato de default para las fechas
		"fixed" =>array("left" => 1, "right" => 1), //Formato para ver si se bloquean las columnas
		"scrollY" =>400 ,// Para el tamaño del scroll en Y, por default 500
        "extraActions" => null, //Parámetros extra
        "visualizar" => false, //Permiso de visualizacion
        "listQuery" => null, //Query a ejecutar
        "labelParams" => null, //Array con ls nombres de los campos a usar en el query como params		
		);

        $this->permisos = PwSecurity::validateAccess($this->className, $this->cvePerfil, $this->connection);
        $this->tableProp["visualizar"] = $this->permisos["VISUALIZAR"];
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
		
 		$headItems = "";
 		$body = "";

 		$fields = array();
 		$fieldsType = array();
 		$order = array();

		//Llaves de la tabla
        $keys = null;
        
        foreach($this->model as $key=>$item)
		{
		    if(isset($item["key"]) && $item["key"] == true)
			{
			    $keys[] = $item["id"];
			}
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
				"formatter" => isset($modelItem["formatter"]) ? $modelItem["formatter"] : null,
            );

            //Para esconder la columna de la cabecera
			if($fieldsType[$modelItem["id"]]["hideColumn"] == true)
			{
			    continue;
			}

			//Guardamos el ultimo order que encuentre
			if(isset($modelItem["order"]))
			{
			    $order = array($modelItem["id"]." ".$modelItem["order"]);
            }		

			//Vamos por los encabezados
			$headItems .= $headItem;
			$headItems = preg_replace("/__DATA__/", $modelItem["label"], $headItems);
		}

		//Ponemos la info del header
		$tableHead = preg_replace("/__ITEMS__/", $headItems, $tableHead);
		$data = preg_replace("/__HEAD__/", $tableHead, $data);

		//ejecutamos el query
		$condition = null;


		$consulta =  $this->tableProp["listQuery"];
        $ps = PwSql::setSimpleQuery($this->connection,$consulta );		
            
        parse_str($_POST['filterParams'], $filterParams);	
            
        $params = array();

        if(isset($this->tableProp["labelParams"]))
        {
            foreach($this->tableProp["labelParams"] as $formItem)
            {
                $params[] = $filterParams[$formItem];
            }
        }

        $sqlResults = PwSql::executeSimpleQuery($ps, $params, $consulta);         
		
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

					//Si es un select
					if($fieldsType[$fieldName]["type"] == "select" && isset( $fieldsType[$fieldName]["value"][$sqlItem[$fieldName]]))
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


					//Para formatear campos					
					if($fieldsType[$fieldName]["formatter"] && $value != "")
					{
						$formatOptions = $fieldsType[$fieldName]["formatter"];
						$formatType = $formatOptions["type"];
						
						switch($formatType)
						{
							case "integer" :							
							$thousandsSeparator = isset($formatOptions["thousandsSeparator"]) ? $formatOptions["thousandsSeparator"] : $this->integerFormatter["thousandsSeparator"];
							$value = number_format($value, 0 , "", $thousandsSeparator);
							break;

							case "currency" :							
								$thousandsSeparator = isset($formatOptions["thousandsSeparator"]) ? $formatOptions["thousandsSeparator"] : $this->currencyFormatter["thousandsSeparator"];
								$decimalSeparator = isset($formatOptions["decimalSeparator"]) ? $formatOptions["decimalSeparator"] : $this->currencyFormatter["decimalSeparator"];
								$decimalPlaces = isset($formatOptions["decimalPlaces"]) ? $formatOptions["decimalPlaces"] : $this->currencyFormatter["decimalPlaces"];
								$prefix = isset($formatOptions["prefix"]) ? $formatOptions["prefix"] : $this->currencyFormatter["prefix"];
								$value = $prefix.number_format($value, $decimalPlaces , $decimalSeparator, $thousandsSeparator);
							break;
						}
					}

					$tdBodyAux = $tdBody;
					$tdBodyAux = preg_replace("/__DATA__/", $value, $tdBodyAux);
					$tdFields .= $tdBodyAux;
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


    public static function getTemplate($name)
	{

        $template["mainTable"] = <<< TEMP

	<div class="g-pa-20">
		<div class = "row">
			<div class="col-md-12">
			<table id="table" class="display nowrap" >
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

    <script>
		$(document).ready(function()
        {
        	var table = $('#table').DataTable({
				"pageLength": 50,
            	"language": 
		    	{
		    		url: 'pw/assets/vendor/dataTables/media/js/localisation/es.json'
		    	},      
		    	__SCROLLY__
		    	"scrollX": true,		    	 
				__FIXED__

				/*columnDefs: [
					{ width: 180, targets: [0,1] }
				],*/

				dom: 'Bfrtip',
				buttons: [
					'excel',
				]				
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

	return $template[$name];
	}
}