<?php
use Pitweb\Funciones as PwFunciones;
use Pitweb\Security as PwSecurity;
use Pitweb\Sql as PwSql;


class CatRangosCuotaVariableController extends CrudController
{

	 //Constructor de la clase
  function __construct()
  {
      //Validamos permisos de la clase con los perfiles
      $this->className = "CatRangosCuotaVariable";        
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
		$this->tableProp["extraActions"] = array(
			array("button" => "u-btn-teal", "name" => "btnGrid", "function" => "rangosData", "icon" => "hs-admin-window", "title" => "DESCRIPCION"),
		);

		//Traemos el modelo
        $this->model = $this->getModel();
        $this->tableName = "FC_CAT_RANGOS_CUOTA_VARIABLE";


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
			case "detailDelete" :
				$data = self::detailDelete();
				break;

			case "detailUpdate" :
				$data = self::detailUpdate();
			break;
        	default :
				$data = parent::getList();
        	break;


        }

      
		return $data;

	
	}

   private function getModel()
   {
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
   				"id" => "DESCRIPCION",   				   				
   				"type" => "text",
   				"label" => "Rango de Cuota Variable",   
   				"disabled" => false,				
				"required" => true,
				//"editable" => false,
			),

			array(
   				"id" => "CLIENTES_VALUACION",   				   				
   				"type" => "text",
   				"label" => "Clientes Valuación",   				   				
   				"disabled" => false,
   				"required" => true		

			),

			array(
                "id" => "CLIENTES_CONTABILIDAD",   				   				
                "type" => "text",
                "label" => "Clientes Contabilidad",   				   				
                "disabled" => false,
                "required" => true
            ),
   		);

		return $model;

   }

   private function detailDelete()
	{
		
		
		$formParams = json_decode(rawurldecode(PwFunciones::getPVariable("params")));   
		

		if($formParams->id && $formParams->rango)
		{
		
			$condition = array("ID" => $formParams->id, "ID_RANGO" => $formParams->rango);
			PwSql::deleteData($this->connection, "FC_DETALLE_RANGOS_CV", $condition);
		}

		$result = json_encode(array("status" => "delete", "content" =>""));
		return $result;


	}

	private function detailSave()
	{
		
		$keyParams = rawurldecode(PwFunciones::getPVariable("keyParams"));    		
		//Convertimos el json en array
		$keyParams = json_decode( PwSecurity::decryptVariable(1,$keyParams));

		$formParams = json_decode(rawurldecode(PwFunciones::getPVariable("params")));   
		$fields = "ID, ID_RANGO, MINIMO, PORCENTAJE, PUNTOS_BASE";
		$datos = "?,?,?,?,?";

		$id = PwFunciones::getConsecutivo ( $this->connection, "FC_DETALLE_RANGOS_CV", "ID");	

		$params = array($id, $keyParams->ID, $formParams->minimo, $formParams->porcentaje, $formParams->puntosBase);

		if($formParams)
		{
			PwSql::insertData($this->connection, "FC_DETALLE_RANGOS_CV", $fields, $datos, $params);
		}

		return self::getDetail(1);
	}

	private function detailUpdate()
	{

		$formParams = json_decode(rawurldecode(PwFunciones::getPVariable("params")));   
		
		if($formParams)
		{
			$datos = array("MINIMO" => str_replace(",", "", $formParams->minimo), "PORCENTAJE" => str_replace(",", "", $formParams->porcentaje),
				"PUNTOS_BASE" => str_replace(",", "", $formParams->puntosBase)
			);
			$keyFields = array("ID"=>$formParams->id, "ID_RANGO"=> $formParams->rango);
			PwSql::updateData($this->connection, "FC_DETALLE_RANGOS_CV", $datos, $keyFields);
		}

		$result = json_encode(array("status" => "update", "content" =>""));
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

		$condition["ID_RANGO"] = $keyParams["ID"];
		$data = self::getLocalTemplate("table");
		$data = preg_replace("/__HKEYPARAMS__/", $keyParamsAux, $data);		


		$fields = null;
		
		$order = array("MINIMO");
		$sqlResults = PwSql::executeQuery($this->connection, "FC_DETALLE_RANGOS_CV", $fields, $condition, $order, false, false, false, false);

		$trDetail = self::getLocalTemplate("trDetail");
		$tdDetail = self::getLocalTemplate("tdDetail");

		$tableData = "";
		if($sqlResults)
		{			
			foreach($sqlResults as $sqlItem)
			{				
				$tdDetailAux = $tdDetail;
				$tdDetailAux = preg_replace("/__VALMINIMO__/", number_format($sqlItem["MINIMO"], 2, ".", ","), $tdDetailAux);
				$tdDetailAux = preg_replace("/__VALPORCENTAJE__/",  number_format($sqlItem["PORCENTAJE"], 6, ".", ","), $tdDetailAux);
				$tdDetailAux = preg_replace("/__VALPUNTOS_BASE__/",  number_format($sqlItem["PUNTOS_BASE"], 6, ".", ","), $tdDetailAux);
				$tdDetailAux = preg_replace("/__ROWID__/", $sqlItem["ID"]."_".$sqlItem["ID_RANGO"], $tdDetailAux);
				$trDetailAux = $trDetail;
				$trDetailAux = preg_replace("/__ITEMS__/", $tdDetailAux, $trDetailAux);
				$trDetailAux = preg_replace("/__ROWID__/", $sqlItem["ID"]."_".$sqlItem["ID_RANGO"], $trDetailAux);
				$tableData .=$trDetailAux;
			}
		}

		$data = preg_replace("/__ITEMS__/", $tableData, $data);
		$result = json_encode(array("status" => "true", "content" =>$data));

		return $result;
	}

	private function getLocalTemplate($name)
   {


	$template ["table"] = <<< TEMP
	
	<div class="row align-middle" >			
			<div class="form-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
            	<label for="minimo">Mínimo (fijo)</label>
				<input type="text" class="form-control" id="minimo" name="minimo" placeholder="" value="">      
				<small class="form-control-feedback" id = "minimoError"></small>                
			</div>			
			<div class="form-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<label for="porcentaje">Porcentaje</label>
				<input type="text" class="form-control " id="porcentaje" name="porcentaje" placeholder="" value=""> 
				<small class="form-control-feedback" id = "porcentajeError"></small>                                
			</div>
			<div class="form-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<label for="puntosBase">Puntos Base</label>
				<input type="text" class="form-control " id="puntosBase" name="puntosBase" placeholder="" value=""> 
				<small class="form-control-feedback" id = "puntosBaseError"></small>                                
			</div>
			<input type="hidden" class="form-control" id="hKeyParams" name="hKeyParams" placeholder="" value="__HKEYPARAMS__"> 
			<div class="form-group col-xs-12 col-sm-12 col-md-3 col-lg-3 align-self-end">
			<button class="btn btn-default " type="button" id = "btnFAdd" name = "btnFAdd">Agregar</button>			
			</div>			
	</div>
	<hr>
	
	
	<div class="tableFixHead text-center">
	<table class = "detailTable" align = "center" id = "detailTable">
		<thead>
			<tr>
				<th width = "30%">Mínimo (fijo)</th>
				<th width = "30%">Porcentaje</th>
				<th width = "30%">Puntos Base</th>
				<th></th>			
			</tr>
		</thead>
		<tbody>
		__ITEMS__
	
		</tbody>
	</table>
	

</div>
<script>
$(document).ready(function() {
	$('.btn_save').hide();
	$('.btn_cancel').hide();  
	//Agregar desde el boton para nuevo detalle
	$("#btnFAdd").on('click', function(e) {
	   
		e.preventDefault();
		errorFlag = false;
		let minimo = $('#minimo').val();
		let porcentaje = $('#porcentaje').val();
		let puntosBase = $('#puntosBase').val();

		validaCampo('minimo', 'numeric');
		validaCampo('porcentaje', 'numeric');
		validaCampo('puntosBase', 'numeric');
		
		if(errorFlag == true)
		{
			return false;
		}

		//Parámetros predefinidos
		let keyParams = $('#hKeyParams').val();
		let params = new Object();
		params.minimo = minimo;
		params.porcentaje = porcentaje;
		params.puntosBase = puntosBase;
		params = JSON.stringify(params);
		getDetailData(detailSave, keyParams, params);
    });



});
</script>
TEMP;

$template ["trDetail"] = <<< TEMP
<tr row_id="__ROWID__">
	__ITEMS__
</tr>
TEMP;


$template ["tdDetail"] = <<< TEMP
<td class = ""><div class="row_data" edit_type="click" col_name="fminimo">__VALMINIMO__</div></td>
<td class = ""><div class="row_data" edit_type="click" col_name="fporcentaje">__VALPORCENTAJE__</div></td>
<td class = ""><div class="row_data" edit_type="click" col_name="fpuntosBase">__VALPUNTOS_BASE__</div></td>
<td class = "">
	<span class="btn_edit"> <a href="#" class="btn btn-sm u-btn-blue " row_id="__ROWID__" > <i class="hs-admin-pencil"></i></a></span>
	<span class="btn_save"> <a href="#" class="btn btn-sm u-btn-teal"  row_id="__ROWID__"> <i class="fa  fa fa-save"> </i> </a></span>
	<span class="btn_cancel"> <a href="#" class="btn btn-sm u-btn-deeporange " row_id="__ROWID__"> <i class="fa  fa fa-minus-circle"> </i> </a></span>
	<span class="btn_delete"> <a href="#" class="btn btn-sm u-btn-primary" row_id="__ROWID__"> <i class="hs-admin-trash"></i></a></span>
</td>


TEMP;

	   return $template[$name];
   }

}